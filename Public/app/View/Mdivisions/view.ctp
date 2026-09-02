<div class="mdivisions view">
<h2><?php  echo __('Mdivision'); ?></h2>
	<dl>
		<dt><?php echo __('Id'); ?></dt>
		<dd>
			<?php echo h($mdivision['Mdivision']['id']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Name'); ?></dt>
		<dd>
			<?php echo h($mdivision['Mdivision']['name']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Ry Name'); ?></dt>
		<dd>
			<?php echo h($mdivision['Mdivision']['ry_name']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Mbank'); ?></dt>
		<dd>
			<?php echo $this->Html->link($mdivision['Mbank']['name'], array('controller' => 'mbanks', 'action' => 'view', $mdivision['Mbank']['id'])); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Branch'); ?></dt>
		<dd>
			<?php echo h($mdivision['Mdivision']['branch']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Branchname'); ?></dt>
		<dd>
			<?php echo h($mdivision['Mdivision']['branchname']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Account'); ?></dt>
		<dd>
			<?php echo h($mdivision['Mdivision']['account']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Accountname'); ?></dt>
		<dd>
			<?php echo h($mdivision['Mdivision']['accountname']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Column Count'); ?></dt>
		<dd>
			<?php echo h($mdivision['Mdivision']['column_count']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Td Width'); ?></dt>
		<dd>
			<?php echo h($mdivision['Mdivision']['td_width']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('td Height'); ?></dt>
		<dd>
			<?php echo h($mdivision['Mdivision']['td_height']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('K Email'); ?></dt>
		<dd>
			<?php echo h($mdivision['Mdivision']['k_email']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('U Email'); ?></dt>
		<dd>
			<?php echo h($mdivision['Mdivision']['u_email']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('U Tel'); ?></dt>
		<dd>
			<?php echo h($mdivision['Mdivision']['u_tel']); ?>
			&nbsp;
		</dd>
	</dl>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('Edit Mdivision'), array('action' => 'edit', $mdivision['Mdivision']['id'])); ?> </li>
		<li><?php echo $this->Form->postLink(__('Delete Mdivision'), array('action' => 'delete', $mdivision['Mdivision']['id']), null, __('Are you sure you want to delete # %s?', $mdivision['Mdivision']['id'])); ?> </li>
		<li><?php echo $this->Html->link(__('List Mdivisions'), array('action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Mdivision'), array('action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Mbanks'), array('controller' => 'mbanks', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Mbank'), array('controller' => 'mbanks', 'action' => 'add')); ?> </li>
	</ul>
</div>
