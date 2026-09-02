<div class="massociations form">
<?php echo $this->Form->create('Massociation'); ?>
	<fieldset>
	<fieldset>
		<legend><?php echo __('マスタファイルと関連付'); ?></legend>
	<?php
		echo $this->Form->input('mfile_id', array(
			'label' => 'マスタファイル',
			'error' => '必須入力項目です。'
			));
		echo $this->Form->input('mryoukin_id', array(
			'label' => '関連するベース講座',
			'error' => '必須入力項目です。'
			));
		echo $this->Form->input('mkbn1_id', array(
			'label' => '大区分',
			'error' => '必須入力項目です。'
			));
		echo $this->Form->input('mkbn2_id', array(
			'label' => '中区分',
			'error' => '必須入力項目です。'
			));
		echo $this->Form->input('mkbn3_id', array(
			'label' => '小区分',
			'error' => '必須入力項目です。'
			));
	?>
	</fieldset>
<?php echo $this->Form->end(__('変　更')); ?>
</div>
</div>
<?php echo $this->Element('left'); ?>