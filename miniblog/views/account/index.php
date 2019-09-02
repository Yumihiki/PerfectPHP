<?php $this->setLayoutVar('title', 'アカウント') ?>

<h2>アカウント</h2>

<p>
  ユーザID: <a href="<?= $base_url; ?>/user/<?= $this->escape($user['user_name']); ?>"></a>
  <strong><?= $this->escape($user['user_name']); ?></strong>
</p>

<ul>
  <li>
    <a href="<?= $base_url; ?>/">ホーム</a>
  </li>
  <li>
    <a href="<?= $base_url; ?>/account/signout">ログアウト</a>
  </li>
</ul>

<h3>フォロー中</h3>

<?php if (count($followings) > 0): ?>
<ul>
  <?php foreach($followings as $following): ?>
  <li>
    <a href="<?= $base_url; ?>/user/<?= $this->escape($following['user_name']); ?>">
      <?= $this->escape($following['user_name']); ?>
    </a>
  </li>
<?php endforeach; ?>
</ul>
<?php endif; ?>