<div class="mpaymentmethods view">
<h2><?php echo __('Mpaymentmethod'); ?></h2>
	<dl>
		<dt><?php echo __('Id'); ?></dt>
		<dd>
			<?php echo h($mpaymentmethod['Mpaymentmethod']['id']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Name'); ?></dt>
		<dd>
			<?php echo h($mpaymentmethod['Mpaymentmethod']['name']); ?>
			&nbsp;
		</dd>
	</dl>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('Edit Mpaymentmethod'), array('action' => 'edit', $mpaymentmethod['Mpaymentmethod']['id'])); ?> </li>
		<li><?php echo $this->Form->postLink(__('Delete Mpaymentmethod'), array('action' => 'delete', $mpaymentmethod['Mpaymentmethod']['id']), array('confirm' => __('Are you sure you want to delete # %s?', $mpaymentmethod['Mpaymentmethod']['id']))); ?> </li>
		<li><?php echo $this->Html->link(__('List Mpaymentmethods'), array('action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Mpaymentmethod'), array('action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Msubscriptions'), array('controller' => 'msubscriptions', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Msubscription'), array('controller' => 'msubscriptions', 'action' => 'add')); ?> </li>
	</ul>
</div>
<div class="related">
	<h3><?php echo __('Related Msubscriptions'); ?></h3>
	<?php if (!empty($mpaymentmethod['Msubscription'])): ?>
	<table cellpadding = "0" cellspacing = "0">
	<tr>
		<th><?php echo __('Id'); ?></th>
		<th><?php echo __('Muser Id'); ?></th>
		<th><?php echo __('Mryoukin Id'); ?></th>
		<th><?php echo __('Mpaymentmethod Id'); ?></th>
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
	<?php foreach ($mpaymentmethod['Msubscription'] as $msubscription): ?>
		<tr>
			<td><?php echo $msubscription['id']; ?></td>
			<td><?php echo $msubscription['muser_id']; ?></td>
			<td><?php echo $msubscription['mryoukin_id']; ?></td>
			<td><?php echo $msubscription['mpaymentmethod_id']; ?></td>
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
