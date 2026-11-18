<?php
declare(strict_types=1);
require dirname(__DIR__) . '/app/bootstrap.php';
$prefix = $argv[1] ?? '';
if (!preg_match('/^acqa-test-[a-f0-9]{12}$/D',$prefix)) throw new RuntimeException('Prefixo inválido.');
$db = App\Core\Database::connection();
$stmt=$db->prepare('SELECT password FROM users WHERE email=?');
$stmt->execute([$prefix.'-a@teste.local']);
$hash=$stmt->fetchColumn();
if ($hash && (password_get_info($hash)['algoName'] !== 'bcrypt' || !password_verify('Testing@2026',$hash))) throw new RuntimeException('Hash bcrypt não validado.');
$delete=$db->prepare('DELETE FROM users WHERE email IN (?,?)');
$delete->execute([$prefix.'-a@teste.local',$prefix.'-b@teste.local']);
echo "Bcrypt validado; dados exclusivos desta execução removidos.\n";
