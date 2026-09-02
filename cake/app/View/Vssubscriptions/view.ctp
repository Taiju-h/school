<div class="msubscriptions view">
<h2><?php echo __('Msubscription'); ?></h2>
	<dl>
		<dt><?php echo __('Id'); ?></dt>
		<dd>
			<?php echo h($msubscription['Msubscription']['id']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Muser'); ?></dt>
		<dd>
			<?php echo $this->Html->link($msubscription['Muser']['name'], array('controller' => 'musers', 'action' => 'view', $msubscription['Muser']['id'])); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Mryoukin'); ?></dt>
		<dd>
			<?php echo $this->Html->link($msubscription['Mryoukin']['name'], array('controller' => 'mryoukins', 'action' => 'view', $msubscription['Mryoukin']['id'])); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Mpaymentmethod'); ?></dt>
		<dd>
			<?php echo $this->Html->link($msubscription['Mpaymentmethod']['name'], array('controller' => 'mpaymentmethods', 'action' => 'view', $msubscription['Mpaymentmethod']['id'])); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Data1'); ?></dt>
		<dd>
			<?php echo h($msubscription['Msubscription']['data1']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Kng1'); ?></dt>
		<dd>
			<?php echo h($msubscription['Msubscription']['kng1']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Data2'); ?></dt>
		<dd>
			<?php echo h($msubscription['Msubscription']['data2']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Kng2'); ?></dt>
		<dd>
			<?php echo h($msubscription['Msubscription']['kng2']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Data3'); ?></dt>
		<dd>
			<?php echo h($msubscription['Msubscription']['data3']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Kng3'); ?></dt>
		<dd>
			<?php echo h($msubscription['Msubscription']['kng3']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Data4'); ?></dt>
		<dd>
			<?php echo h($msubscription['Msubscription']['data4']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Kng4'); ?></dt>
		<dd>
			<?php echo h($msubscription['Msubscription']['kng4']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Data5'); ?></dt>
		<dd>
			<?php echo h($msubscription['Msubscription']['data5']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Kng5'); ?></dt>
		<dd>
			<?php echo h($msubscription['Msubscription']['kng5']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Data6'); ?></dt>
		<dd>
			<?php echo h($msubscription['Msubscription']['data6']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Kng6'); ?></dt>
		<dd>
			<?php echo h($msubscription['Msubscription']['kng6']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Data7'); ?></dt>
		<dd>
			<?php echo h($msubscription['Msubscription']['data7']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Kng7'); ?></dt>
		<dd>
			<?php echo h($msubscription['Msubscription']['kng7']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Date8'); ?></dt>
		<dd>
			<?php echo h($msubscription['Msubscription']['date8']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Kng8'); ?></dt>
		<dd>
			<?php echo h($msubscription['Msubscription']['kng8']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Fee'); ?></dt>
		<dd>
			<?php echo h($msubscription['Msubscription']['Fee']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Paidkng'); ?></dt>
		<dd>
			<?php echo h($msubscription['Msubscription']['paidkng']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Mdivision'); ?></dt>
		<dd>
			<?php echo $this->Html->link($msubscription['Mdivision']['name'], array('controller' => 'mdivisions', 'action' => 'view', $msubscription['Mdivision']['id'])); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Created'); ?></dt>
		<dd>
			<?php echo h($msubscription['Msubscription']['created']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Modified'); ?></dt>
		<dd>
			<?php echo h($msubscription['Msubscription']['modified']); ?>
			&nbsp;
		</dd>
	</dl>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('Edit Msubscription'), array('action' => 'edit', $msubscription['Msubscription']['id'])); ?> </li>
		<li><?php echo $this->Form->postLink(__('Delete Msubscription'), array('action' => 'delete', $msubscription['Msubscription']['id']), array('confirm' => __('Are you sure you want to delete # %s?', $msubscription['Msubscription']['id']))); ?> </li>
		<li><?php echo $this->Html->link(__('List Msubscriptions'), array('action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Msubscription'), array('action' => 'add')); ?> </li>
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
