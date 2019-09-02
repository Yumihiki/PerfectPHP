<ul class="error_list">
  <?php foreach($errors as $error): ?>
  <li><?= $this->escape($error); ?></li>
  <?php endforeach; ?>
</ul>