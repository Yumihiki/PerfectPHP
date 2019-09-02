<?php $this->setLayoutVar('title', 'ログイン') ?>

<h2>ログイン</h2>

<form action="<?= $base_url; ?>/account/authenticate" method="post">
  <input type="hidden" name="_token" value="<?= $this->escape($_token); ?>" />

  <?php if (isset($errors) && count($errors) > 0): ?>
  <?= $this->render('errors', [
    'errors' => $errors
  ]); ?>
  <?php endif; ?>

  <?= $this->render('account/inputs', [
    'user_name' => $user_name,
    'password'  => $password,
  ]);
  ?>

  <p>
    <input type="submit" value="ログイン" />
  </p>
</form>