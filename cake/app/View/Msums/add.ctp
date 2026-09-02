<div class="msums form">
<?php echo $this->Form->create('Msum'); ?>
	<fieldset>
		<legend><?php echo __('Add Msum'); ?></legend>
	<?php
		echo $this->Form->input('mryoukin_id');
		echo $this->Form->input('mryoukin2_id');
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit')); ?>
</div>
<?php echo $this->Element('left'); ?>