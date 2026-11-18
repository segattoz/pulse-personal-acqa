<span class="eyebrow">COMECE UMA NOVA ROTINA</span><h2>Mais organização.<br>Mais resultados<span class="orange">.</span></h2><p class="intro">Crie sua conta e cuide de cada etapa do treino.</p>
<?php require dirname(__DIR__) . '/partials/errors.php'; ?>
<form method="post" action="<?= url('register') ?>" class="auth-form compact"><?= csrf() ?>
<label for="name">Nome completo</label><input id="name" name="name" placeholder="Como podemos chamar você?" value="<?= e($old['name'] ?? '') ?>" autocomplete="name" minlength="3" maxlength="100" required>
<label for="email">E-mail</label><input id="email" type="email" name="email" placeholder="seu@email.com" value="<?= e($old['email'] ?? '') ?>" autocomplete="email" maxlength="190" required>
<div class="form-grid"><div><label for="password">Senha</label><input id="password" type="password" name="password" placeholder="Mínimo de 8 caracteres" autocomplete="new-password" minlength="8" maxlength="72" required></div><div><label for="confirmation">Confirme sua senha</label><input id="confirmation" type="password" name="password_confirmation" placeholder="Repita a senha" autocomplete="new-password" minlength="8" maxlength="72" required></div></div>
<button class="button primary wide" type="submit">Criar minha conta <?= icon('arrow') ?></button>
</form><p class="auth-switch">Já tem uma conta? <a href="<?= url('login') ?>">Faça login</a></p>
