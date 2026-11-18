<span class="eyebrow">SUA AGENDA, SEU RITMO</span><h2>Bem-vindo de volta<span class="orange">.</span></h2><p class="intro">Entre na sua conta e prepare o próximo movimento.</p>
<?php require dirname(__DIR__) . '/partials/errors.php'; ?>
<form method="post" action="<?= url('login') ?>" class="auth-form"><?= csrf() ?>
<label for="email">E-mail</label><input id="email" type="email" name="email" placeholder="seu@email.com" value="<?= e($old['email'] ?? '') ?>" autocomplete="username" maxlength="190" required>
<label for="password">Senha</label><div class="password-field"><input id="password" type="password" name="password" placeholder="Digite sua senha" autocomplete="current-password" required><button type="button" class="show-password" data-password="password" aria-label="Mostrar senha">Mostrar</button></div>
<div class="secure-note"><?= icon('check') ?> Seus dados protegidos, sua rotina organizada.</div>
<button class="button primary wide" type="submit">Entrar na minha conta <?= icon('arrow') ?></button>
</form><p class="auth-switch">Ainda não faz parte? <a href="<?= url('register') ?>">Crie sua conta</a></p>
<div class="auth-benefits"><span><?= icon('calendar') ?> Agenda organizada</span><span><?= icon('task') ?> Rotina sob controle</span></div>
