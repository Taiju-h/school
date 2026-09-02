<div class="eschedules view">
<h2><?php echo __('Eschedule'); ?></h2>
	<dl>
		<dt><?php echo __('Id'); ?></dt>
		<dd>
			<?php echo h($eschedule['Eschedule']['id']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Mryoukin'); ?></dt>
		<dd>
			<?php echo $this->Html->link($eschedule['Mryoukin']['name'], array('controller' => 'mryoukins', 'action' => 'view', $eschedule['Mryoukin']['id'])); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Name'); ?></dt>
		<dd>
			<?php echo h($eschedule['Eschedule']['name']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Date1'); ?></dt>
		<dd>
			<?php echo h($eschedule['Eschedule']['date1']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Date2'); ?></dt>
		<dd>
			<?php echo h($eschedule['Eschedule']['date2']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Date3'); ?></dt>
		<dd>
			<?php echo h($eschedule['Eschedule']['date3']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Deadline'); ?></dt>
		<dd>
			<?php echo h($eschedule['Eschedule']['deadline']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Created'); ?></dt>
		<dd>
			<?php echo h($eschedule['Eschedule']['created']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Modified'); ?></dt>
		<dd>
			<?php echo h($eschedule['Eschedule']['modified']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Upddateid'); ?></dt>
		<dd>
			<?php echo h($eschedule['Eschedule']['upddateid']); ?>
			&nbsp;
		</dd>
	</dl>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('Edit Eschedule'), array('action' => 'edit', $eschedule['Eschedule']['id'])); ?> </li>
		<li><?php echo $this->Form->postLink(__('Delete Eschedule'), array('action' => 'delete', $eschedule['Eschedule']['id']), array('confirm' => __('Are you sure you want to delete # %s?', $eschedule['Eschedule']['id']))); ?> </li>
		<li><?php echo $this->Html->link(__('List Eschedules'), array('action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Eschedule'), array('action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Mryoukins'), array('controller' => 'mryoukins', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Mryoukin'), array('controller' => 'mryoukins', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Msubscriptions'), array('controller' => 'msubscriptions', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Msubscription'), array('controller' => 'msubscriptions', 'action' => 'add')); ?> </li>
	</ul>
</div>
<div class="related">
	<h3><?php echo __('Related Msubscriptions'); ?></h3>
	<?php if (!empty($eschedule['Msubscription'])): ?>
	<table cellpadding = "0" cellspacing = "0">
	<tr>
		<th><?php echo __('Id'); ?></th>
		<th><?php echo __('Muser Id'); ?></th>
		<th><?php echo __('Mryoukin Id'); ?></th>
		<th><?php echo __('Mpaymentmethod Id'); ?></th>
		<th><?php echo __('Eschedule Id'); ?></th>
		<th><?php echo __('Admissiondate'); ?></th>
		<th><?php echo __('Data1'); ?></th>
		<th><?php echo __('Kng1'); ?></th>
		<th><?php echo __('Data2'); ?></th>
		<th><?php echo __('Kng2'); ?></th>
		<th><?php echo __('Data3'); ?></th>
		<th><?php echo __('Kng3'); ?></th>
		<th><?php echo __('Data4'); ?></th>
		<th><?php echo __('Kng4'); ?></th>
		<th><?php echo __('Data5'); ?></th>
		<th><?php echo __('Kng5'); ?></th>
		<th><?php echo __('Data6'); ?></th>
		<th><?php echo __('Kng6'); ?></th>
		<th><?php echo __('Data7'); ?></th>
		<th><?php echo __('Kng7'); ?></th>
		<th><?php echo __('Date8'); ?></th>
		<th><?php echo __('Kng8'); ?></th>
		<th><?php echo __('Fee'); ?></th>
		<th><?php echo __('Paidkng'); ?></th>
		<th><?php echo __('Mdivision Id'); ?></th>
		<th><?php echo __('Created'); ?></th>
		<th><?php echo __('Modified'); ?></th>
		<th class="actions"><?php echo __('Actions'); ?></th>
	</tr>
	<?php foreach ($eschedule['Msubscription'] as $msubscription): ?>
		<tr>
			<td><?php echo $msubscription['id']; ?></td>
			<td><?php echo $msubscription['muser_id']; ?></td>
			<td><?php echo $msubscription['mryoukin_id']; ?></td>
			<td><?php echo $msubscription['mpaymentmethod_id']; ?></td>
			<td><?php echo $msubscription['eschedule_id']; ?></td>
			<td><?php echo $msubscription['admissiondate']; ?></td>
			<td><?php echo $msubscription['data1']; ?></td>
			<td><?php echo $msubscription['kng1']; ?></td>
			<td><?php echo $msubscription['data2']; ?></td>
			<td><?php echo $msubscription['kng2']; ?></td>
			<td><?php echo $msubscription['data3']; ?></td>
			<td><?php echo $msubscription['kng3']; ?></td>
			<td><?php echo $msubscription['data4']; ?></td>
			<td><?php echo $msubscription['kng4']; ?></td>
			<td><?php echo $msubscription['data5']; ?></td>
			<td><?php echo $msubscription['kng5']; ?></td>
			<td><?php echo $msubscription['data6']; ?></td>
			<td><?php echo $msubscription['kng6']; ?></td>
			<td><?php echo $msubscription['data7']; ?></td>
			<td><?php echo $msubscription['kng7']; ?></td>
			<td><?php echo $msubscription['date8']; ?></td>
			<td><?php echo $msubscription['kng8']; ?></td>
			<td><?php echo $msubscription['Fee']; ?></td>
			<td><?php echo $msubscription['paidkng']; ?></td>
			<td><?php echo $msubscription['mdivision_id']; ?></td>
			<td><?php echo $msubscription['created']; ?></td>
			<td><?php echo $msubscription['modified']; ?></td>
			<td class="actions">
				<?php echo $this->Html->link(__('View'), array('controller' => 'msubscriptions', 'action' => 'view', $msubscription['id'])); ?>
				<?php echo $this->Html->link(__('Edit'), array('controller' => 'msubscriptions', 'action' => 'edit', $msubscription['id'])); ?>
				<?php echo $this->Form->postLink(__('Delete'), array('controller' => 'msubscriptions', 'action' => 'delete', $msubscription['id']), array('confirm' => __('Are you sure you want to delete # %s?', $msubscription['id']))); ?>
			</td>
		</tr>
	<?php endforeach; ?>
	</table>
<?php endif; ?>

	<div class="actions">
		<ul>
			<li><?php echo $this->Html->link(__('New Msubscription'), array('controller' => 'msubscriptions', 'action' => 'add')); ?> </li>
		</ul>
	</div>
</div>
