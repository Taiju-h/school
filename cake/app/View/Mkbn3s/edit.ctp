<div class="mkbn3s form">
<?php echo $this->Form->create('Mkbn3'); ?>
	<fieldset>
		<legend><?php echo __('小区分追加'); ?></legend>
	<?php
		echo $this->Form->input('name', array(
			'label' => '区分名',
			'error' => '必須入力項目です。'
			));
		echo $this->Form->input('dname', array(
			'label' => '表示名',
			'error' => '必須入力項目です。'
			));
		echo $this->Form->input('oder', array(
			'label' => '表示順',
			'error' => '必須入力項目です。'
			));
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit')); ?>
</div>
<?php echo $this->Element('left'); ?>