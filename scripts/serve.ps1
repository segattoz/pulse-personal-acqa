param([int]$Port = 8080)
$projectRoot = Split-Path $PSScriptRoot -Parent
Set-Location -LiteralPath $projectRoot
$phpExecutable = if (Test-Path '.runtime/php/php.exe') { Join-Path $projectRoot '.runtime/php/php.exe' } else { 'php' }
Write-Host "Pulse Personal: http://127.0.0.1:$Port"
& $phpExecutable -S "127.0.0.1:$Port" -t public
