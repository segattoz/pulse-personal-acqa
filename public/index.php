<?php
declare(strict_types=1);
require dirname(__DIR__) . '/app/bootstrap.php';

ini_set('session.use_strict_mode','1');
session_name('pulse_session');
session_set_cookie_params(['httponly'=>true,'samesite'=>'Lax','secure'=>(!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off'),'path'=>'/']);
session_start();
$_SESSION['_token'] ??= bin2hex(random_bytes(32));
header('X-Content-Type-Options: nosniff');
header('X-Frame-Options: DENY');
header("Content-Security-Policy: default-src 'self'; style-src 'self'; script-src 'self'; img-src 'self' data:; base-uri 'self'; form-action 'self'; frame-ancestors 'none'");

use App\Controllers\AuthController;
use App\Controllers\DashboardController;
use App\Controllers\AppointmentController;

$routes = [
    'GET login'=>[AuthController::class,'login',false],
    'POST login'=>[AuthController::class,'authenticate',false],
    'GET register'=>[AuthController::class,'register',false],
    'POST register'=>[AuthController::class,'store',false],
    'POST logout'=>[AuthController::class,'logout',true],
    'GET dashboard'=>[DashboardController::class,'index',true],
    'POST tasks'=>[DashboardController::class,'task',true],
    'GET appointments'=>[AppointmentController::class,'index',true],
    'GET appointment-form'=>[AppointmentController::class,'form',true],
    'POST appointment-save'=>[AppointmentController::class,'save',true],
    'POST appointment-delete'=>[AppointmentController::class,'delete',true],
];
$route = $_GET['route'] ?? 'dashboard';
$method = $_SERVER['REQUEST_METHOD'];
$target = $routes[$method . ' ' . $route] ?? null;
try {
    if (!$target) { http_response_code(404); echo 'Página não encontrada. <a href="/">Voltar ao início</a>'; exit; }
    if ($target[2] && !isset($_SESSION['user'])) redirect('login');
    if ($method === 'POST' && (!is_string($_POST['_token'] ?? null) || !hash_equals($_SESSION['_token'],$_POST['_token']))) {
        http_response_code(419); echo 'Sessão expirada. Atualize a página e tente novamente.'; exit;
    }
    (new $target[0]())->{$target[1]}();
} catch (Throwable $error) {
    error_log((string)$error,3,dirname(__DIR__) . '/storage/error.log');
    http_response_code(500);
    echo '<h1>Não foi possível concluir a solicitação.</h1><p>Verifique a configuração do banco e tente novamente.</p><a href="/">Voltar</a>';
}
