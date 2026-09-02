<?php $this->Html->addCrumb( '管理画面', '/'); ?>
<?php $this->Html->addCrumb( 'ログアウト'); ?>
<?php $this->set('breadcrumb_for_layout', $this->Html->getCrumbs(' &raquo; ')) ?>
<?php $this->set('title_for_layout', 'ログアウト'); ?>

<p>ログアウトしました</p>

<div class="btn"><?php echo $this->Html->link('戻る', array('action' => 'login')); ?></div>
