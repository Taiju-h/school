<div class="mkbn2s view">
<h2><?php echo __('Mkbn2'); ?></h2>
	<dl>
		<dt><?php echo __('Id'); ?></dt>
		<dd>
			<?php echo h($mkbn2['Mkbn2']['id']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Name'); ?></dt>
		<dd>
			<?php echo h($mkbn2['Mkbn2']['name']); ?>
			&nbsp;
		</dd>
	</dl>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('Edit Mkbn2'), array('action' => 'edit', $mkbn2['Mkbn2']['id'])); ?> </li>
		<li><?php echo $this->Form->postLink(__('Delete Mkbn2'), array('action' => 'delete', $mkbn2['Mkbn2']['id']), array('confirm' => __('Are you sure you want to delete # %s?', $mkbn2['Mkbn2']['id']))); ?> </li>
		<li><?php echo $this->Html->link(__('List Mkbn2s'), array('action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Mkbn2'), array('action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Massociations'), array('controller' => 'massociations', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Massociation'), array('controller' => 'massociations', 'action' => 'add')); ?> </li>
	</ul>
</div>

<?php echo $this->Element('left'); ?>