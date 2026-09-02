<div class="user form">
<h2>管理者特権</h2>
<h3>ユーザ切り替え</h3>

<?php echo $this->Form->create('User'); ?>
<?php echo $this->Form->input('mkanteishi_id'); ?>
<?php echo $this->Form->end('切り替えます'); ?>
</div>
<?php echo $this->Element('left'); ?>