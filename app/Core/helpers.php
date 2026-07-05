<?php
function e(?string $value): string { return htmlspecialchars($value ?? '', ENT_QUOTES, 'UTF-8'); }
function redirect(string $path): void { header("Location: {$path}"); exit; }
function render(string $view, array $data = [], string $layout = 'layouts/main'): void {
    extract($data); ob_start(); require __DIR__ . '/../Views/' . $view . '.php'; $content = ob_get_clean();
    if($layout) require __DIR__ . '/../Views/' . $layout . '.php'; else echo $content;
}
function flash(string $key, string $message): void { $_SESSION['flash'][$key] = $message; }
function get_flash(string $key): ?string {
    if (empty($_SESSION['flash'][$key])) return null;
    $message = $_SESSION['flash'][$key]; unset($_SESSION['flash'][$key]); return $message;
}
function require_login(): void { if (empty($_SESSION['user_id'])) redirect('/login'); }
function old(string $key, string $default = ''): string { return $_SESSION['old'][$key] ?? $default; }
function clear_old(): void { unset($_SESSION['old']); }