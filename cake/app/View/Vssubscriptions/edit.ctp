<div class="msubscriptions form">
<?php echo $this->Form->create('Msubscription'); ?>
	<fieldset>
		<legend><?php echo __('Edit Msubscription'); ?></legend>
	<?php
		echo $this->Form->input('id');
		echo $this->Form->input('muser_id');
		echo $this->Form->input('mryoukin_id');
		echo $this->Form->input('mpaymentmethod_id');
		echo $this->Form->input('data1');
		echo $this->Form->input('kng1');
		echo $this->Form->input('data2');
		echo $this->Form->input('kng2');
		echo $this->Form->input('data3');
		echo $this->Form->input('kng3');
		echo $this->Form->input('data4');
		echo $this->Form->input('kng4');
		echo $this->Form->input('data5');
		echo $this->Form->input('kng5');
		echo $this->Form->input('data6');
		echo $this->Form->input('kng6');
		echo $this->Form->input('data7');
		echo $this->Form->input('kng7');
		echo $this->Form->input('date8');
		echo $this->Form->input('kng8');
		echo $this->Form->input('Fee');
		echo $this->Form->input('paidkng');
		echo $this->Form->input('mdivision_id');
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit')); ?>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>

		<li><?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $this->Form->value('Msubscription.id')), array('confirm' => __('Are you sure you want to delete # %s?', $this->Form->value('Msubscription.id')))); ?></li>
		<li><?php echo $this->Html->link(__('List Msubscriptions'), array('action' => 'index')); ?></li>
		<li><?php echo $this->Html->link(__('List Musers'), array('controller' => 'musers', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Muser'), array('controller' => 'musers', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Mryoukins'), array('controller' => 'mryoukins', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Mryoukin'), array('controller' => 'mryoukins', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Mpaymentmethods'), array('controller' => 'mpaymentmethods', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Mpaymentmethod'), array('controller' => 'mpaymentmethods', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Mdivisions'), array('controller' => 'mdivisions', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Mdivision'), array('controller' => 'mdivisions', 'action' => 'add')); ?> </li>
	</ul>
</div>
