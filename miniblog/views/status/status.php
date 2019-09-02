<div class="status">
  <div class="status_content">
    <a href="<?= $base_url; ?>/user/<?= $this->escape($status['user_name']) ?>">
      <?= $this->escape($status['user_name']); ?>
    </a>
    
    <?= $this->escape($status['body']); ?>
  </div>
  <div>
  <a href="<?= $base_url; ?>/user/<?= $this->escape($status['user_name']);
        ?>/status/<?= $this->escape($status['id']); ?>">
            <?= $this->escape($status['created_at']); ?>
        </a>
  </div>
</div>