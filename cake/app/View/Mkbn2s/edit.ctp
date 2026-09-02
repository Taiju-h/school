<div class="mkbn2s form">
<?php echo $this->Form->create('Mkbn2'); ?>
		<legend><?php echo __('中区分変更'); ?></legend>
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
<?php echo $this->Form->end(__('変　更')); ?>
</div>
<?php echo $this->Element('left'); ?>