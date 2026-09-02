<div class="msums form">
<?php echo $this->Form->create('Msum2'); ?>
	<fieldset>
		<legend><?php echo __('Edit Msum2'); ?></legend>
	<?php
		echo $this->Form->input('id');
		echo $this->Form->input('mryoukin_id');
		echo $this->Form->input('mryoukin2_id');
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit')); ?>
</div>
<?php echo $this->Element('left'); ?>