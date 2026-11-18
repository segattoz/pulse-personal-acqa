<?php
declare(strict_types=1);

spl_autoload_register(function (string $class): void {
    $path = __DIR__ . '/' . str_replace('\\', '/', preg_replace('/^App\\\\/', '', $class)) . '.php';
    if (is_file($path)) require $path;
});

if (is_file(dirname(__DIR__) . '/.env')) {
    foreach (file(dirname(__DIR__) . '/.env', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) as $line) {
        if (str_starts_with(trim($line), '#') || !str_contains($line, '=')) continue;
        [$key, $value] = explode('=', $line, 2);
        if (getenv(trim($key)) === false) putenv(trim($key) . '=' . trim($value, " \t\n\r\0\x0B\"'"));
    }
}
date_default_timezone_set('America/Sao_Paulo');

function e(mixed $value): string { return htmlspecialchars((string)$value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'); }
function url(string $route, array $params = []): string { return '/index.php?' . http_build_query(['route' => $route] + $params); }
function redirect(string $route, array $params = []): never { header('Location: ' . url($route, $params)); exit; }
function csrf(): string { return '<input type="hidden" name="_token" value="' . e($_SESSION['_token']) . '">'; }
function flash(string $message, string $type = 'success'): void { $_SESSION['flash'] = compact('message', 'type'); }
function icon(string $name): string {
    $paths = [
        'grid' => '<rect x="3" y="3" width="7" height="7" rx="1.5"/><rect x="14" y="3" width="7" height="7" rx="1.5"/><rect x="3" y="14" width="7" height="7" rx="1.5"/><rect x="14" y="14" width="7" height="7" rx="1.5"/>',
        'calendar' => '<rect x="3" y="5" width="18" height="16" rx="2"/><path d="M16 3v4M8 3v4M3 11h18M8 15h2m4 0h2m-8 3h2"/>',
        'check' => '<path d="m5 12 4 4L19 6"/>',
        'task' => '<rect x="4" y="4" width="16" height="17" rx="2"/><path d="M9 3h6v4H9zM8 13l2 2 5-5M8 18h8"/>',
        'plus' => '<path d="M12 5v14M5 12h14"/>',
        'arrow' => '<path d="M5 12h14m-6-6 6 6-6 6"/>',
        'clock' => '<circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 2"/>',
        'users' => '<circle cx="9" cy="8" r="3"/><path d="M3 21v-3a6 6 0 0 1 12 0v3m1-16a3 3 0 0 1 0 6m5 10v-3a6 6 0 0 0-4-5"/>',
        'edit' => '<path d="m16 3 5 5-12 12-6 1 1-6zM14 5l5 5"/>',
        'trash' => '<path d="M3 6h18M9 6V3h6v3M5 6l1 15h12l1-15M10 10v7m4-7v7"/>',
        'logout' => '<path d="M9 4H4v16h5m5-4 4-4-4-4m-6 4h14"/>',
        'pulse' => '<path d="M2 12h5l3-8 4 16 3-8h5"/>',
        'search' => '<circle cx="10.5" cy="10.5" r="6.5"/><path d="m16 16 5 5"/>',
    ];
    return '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">' . ($paths[$name] ?? $paths['pulse']) . '</svg>';
}
