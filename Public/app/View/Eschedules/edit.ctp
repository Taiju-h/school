<div class="eschedules form">
<?php echo $this->Form->create('Eschedule'); ?>
	<fieldset>
		<legend><?php echo __('Edit Eschedule'); ?></legend>
	<?php
		echo $this->Form->input('id');
		echo $this->Form->input('mryoukin_id');
		echo $this->Form->input('name');
		echo $this->Form->input('date1');
		echo $this->Form->input('date2');
		echo $this->Form->input('date3');
		echo $this->Form->input('deadline');
		echo $this->Form->input('upddateid');
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit')); ?>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>

		<li><?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $this->Form->value('Eschedule.id')), array('confirm' => __('Are you sure you want to delete # %s?', $this->Form->value('Eschedule.id')))); ?></li>
		<li><?php echo $this->Html->link(__('List Eschedules'), array('action' => 'index')); ?></li>
		<li><?php echo $this->Html->link(__('List Mryoukins'), array('controller' => 'mryoukins', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Mryoukin'), array('controller' => 'mryoukins', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Msubscriptions'), array('controller' => 'msubscriptions', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Msubscription'), array('controller' => 'msubscriptions', 'action' => 'add')); ?> </li>
	</ul>
</div>
