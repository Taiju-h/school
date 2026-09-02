<div class="Eschedules form">
<?php echo $this->Form->create('Vattendance'); ?>
	<fieldset>
		<legend><?php echo __('開催講座を選択してください　本日分のみ'); ?></legend>
	<?php
echo $this->Form->select('id', $vattendances, array('escape' => False));
	 ?>
	</fieldset>
<?php echo $this->Form->end(__('開　催')); ?>

</div>
<?php echo $this->Element('left'); ?>