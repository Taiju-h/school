<div class="mkbn1s form">
<?php echo $this->Form->create('Mkbn1'); ?>
<?php echo empty($this->data['Mkbn1']['id']) ? null : $this->Form->input('id', array('type' => 'hidden')); ?>

	<fieldset>
		<legend><?php echo __('大区分変更'); ?></legend>
	<?php
		echo $this->Form->input('name', array(
			'label' => '区分名',
			'error' => '必須入力項目です。'
			));
		echo $this->Form->input('dname', array(
			'label' => '学習の部屋表示名',
			'error' => '必須入力項目です。'
			));
		echo $this->Form->input('oder', array(
			'label' => '表示順(小さい方から並びます。最初は20単位ぐらいでつけるといいかも）',
			'error' => '必須入力項目です。'
			));
		echo $this->Form->input('taboo_flg', array(
			'label' => '禁断の書専用大区分の場合チェック',
			));
	?>
	</fieldset>
<?php echo $this->Form->end(__('変　更')); ?>

</div>
<?php echo $this->Element('left'); ?>