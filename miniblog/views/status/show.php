<?php $this->setLayoutVar('title', $status['user_name']) ?>

<?= $this->render('status/status', ['status' => $status]); ?> 