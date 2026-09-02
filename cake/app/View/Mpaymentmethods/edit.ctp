<div class="mpaymentmethods form">
<?php echo $this->Form->create('Mpaymentmethod'); ?>
	<fieldset>
		<legend><?php echo __('Edit Mpaymentmethod'); ?></legend>
	<?php
		echo $this->Form->input('id');
		echo $this->Form->input('name');
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit')); ?>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>

		<li><?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $this->Form->value('Mpaymentmethod.id')), array('confirm' => __('Are you sure you want to delete # %s?', $this->Form->value('Mpaymentmethod.id')))); ?></li>
		<li><?php echo $this->Html->link(__('List Mpaymentmethods'), array('action' => 'index')); ?></li>
		<li><?php echo $this->Html->link(__('List Msubscriptions'), array('controller' => 'msubscriptions', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Msubscription'), array('controller' => 'msubscriptions', 'action' => 'add')); ?> </li>
	</ul>
</div>
