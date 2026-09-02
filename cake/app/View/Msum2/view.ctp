<div class="msums view">
<h2><?php echo __('Msum2'); ?></h2>
	<dl>
		<dt><?php echo __('Id'); ?></dt>
		<dd>
			<?php echo h($msum['Msum2']['id']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Mryoukin'); ?></dt>
		<dd>
			<?php echo $this->Html->link($msum['Mryoukin']['name'], array('controller' => 'mryoukins', 'action' => 'view', $msum['Mryoukin']['id'])); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Mryoukin2'); ?></dt>
		<dd>
			<?php echo $this->Html->link($msum['Mryoukin2']['name'], array('controller' => 'mryoukin2s', 'action' => 'view', $msum['Mryoukin2']['id'])); ?>
			&nbsp;
		</dd>
	</dl>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('Edit Msum2'), array('action' => 'edit', $msum['Msum2']['id'])); ?> </li>
		<li><?php echo $this->Form->postLink(__('Delete Msum2'), array('action' => 'delete', $msum['Msum2']['id']), array('confirm' => __('Are you sure you want to delete # %s?', $msum['Msum2']['id']))); ?> </li>
		<li><?php echo $this->Html->link(__('List Msum2s'), array('action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Msum2'), array('action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Mryoukins'), array('controller' => 'mryoukins', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Mryoukin'), array('controller' => 'mryoukins', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Mryoukin2s'), array('controller' => 'mryoukin2s', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Mryoukin2'), array('controller' => 'mryoukin2s', 'action' => 'add')); ?> </li>
	</ul>
</div>
