param([int]$Port = 8080)
$projectRoot = Split-Path $PSScriptRoot -Parent
Set-Location -LiteralPath $projectRoot
$mysqlDir = Get-ChildItem -LiteralPath '.runtime/mysql' -Directory | Select-Object -First 1
$mysqlServer = Join-Path $mysqlDir.FullName 'bin/mysqld.exe'
$databasePort = 3307
if (-not (Get-NetTCPConnection -LocalPort $databasePort -State Listen -ErrorAction SilentlyContinue)) {
    $mysqlArguments = @('--no-defaults', "--basedir=`"$($mysqlDir.FullName)`"", "--datadir=`"$projectRoot/.runtime/mysql-data`"", '--port=3307', '--bind-address=127.0.0.1', '--mysqlx=OFF')
    Start-Process -FilePath $mysqlServer -ArgumentList $mysqlArguments -WindowStyle Hidden -RedirectStandardOutput "$projectRoot/.runtime/mysql-out.log" -RedirectStandardError "$projectRoot/.runtime/mysql-error.log"
    for ($i=0; $i -lt 30; $i++) {
        if (Get-NetTCPConnection -LocalPort $databasePort -State Listen -ErrorAction SilentlyContinue) { break }
        Start-Sleep -Milliseconds 500
    }
}
& "$projectRoot/scripts/serve.ps1" -Port $Port
