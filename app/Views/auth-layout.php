<!DOCTYPE html>
<html lang="pt-BR">
<head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><meta name="description" content="Pulse Personal: organize seus treinos, atendimentos e tarefas."><title><?= e($title) ?> · Pulse Personal</title><link rel="stylesheet" href="/assets/app.css"><script src="/assets/app.js" defer></script></head>
<body class="auth-body">
<aside class="auth-story">
    <a class="brand" href="<?= url('login') ?>"><span class="brand-mark"><?= icon('pulse') ?></span><span>pulse<span class="brand-light">personal</span></span></a>
    <div class="story-copy"><span class="eyebrow light">MAIS FOCO NO QUE IMPORTA</span><h1>Seu tempo em ordem.<br>Seus alunos<br><em>em movimento.</em></h1><p>Uma agenda inteligente para quem transforma<br>rotina em resultado.</p></div>
    <div class="workout-art" aria-hidden="true"><div class="art-orbit orbit-one"></div><div class="art-orbit orbit-two"></div><div class="art-card"><span class="art-icon"><?= icon('calendar') ?></span><span>Seu próximo treino<strong>Hoje, 08:00 — 09:00</strong></span><span class="art-tick"><?= icon('check') ?></span></div><div class="art-stat"><span class="art-icon"><?= icon('pulse') ?></span><div>Consistência gera<strong>resultados.</strong></div><div class="mini-bars"><i></i><i></i><i></i><i></i><i></i></div></div><span class="art-dot dot-one"></span><span class="art-dot dot-two"></span></div>
    <footer>PLANEJE. ACOMPANHE. EVOLUA.<span>Personal trainer, de um jeito mais leve.</span></footer>
</aside>
<main class="auth-main"><div class="auth-top">FEITO PARA A SUA ROTINA <?= icon('pulse') ?></div><div class="auth-content"><?php require $content; ?></div><footer>Pulse Personal · Projeto acadêmico de Matheus Souza Segatto</footer></main>
</body></html>
