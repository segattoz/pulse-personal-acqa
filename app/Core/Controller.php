<?php
declare(strict_types=1);
namespace App\Core;

abstract class Controller
{
    protected function view(string $template, array $data = [], bool $auth = false): void
    {
        extract($data, EXTR_SKIP);
        $content = dirname(__DIR__) . '/Views/' . $template . '.php';
        require dirname(__DIR__) . '/Views/' . ($auth ? 'auth-layout' : 'layout') . '.php';
    }

    protected function userId(): int { return (int)$_SESSION['user']['id']; }
    protected function notFound(): never { http_response_code(404); $this->view('error', ['title' => 'Página não encontrada', 'message' => 'Este registro não existe ou não pertence à sua conta.']); exit; }
}
