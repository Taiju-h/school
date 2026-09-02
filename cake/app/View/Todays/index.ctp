<style type="text/css">  
BODY {
  font-size: 200%;
  }
</style> 
<?php echo $this->Form->create('Vattendance'); ?>
	<fieldset>
		<legend><?php echo __('開催講座を選択してください'); ?></legend>
	<?php
echo $this->Form->select('id', $vattendances, array('escape' => False));
	 ?>
	</fieldset>
<?php echo $this->Form->end(__('開　催')); ?>

