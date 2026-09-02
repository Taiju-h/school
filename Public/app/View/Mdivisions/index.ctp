<div class="mdivisions index">
	<h2><?php echo __('会社情報一覧'); ?></h2>
	<table cellpadding="0" cellspacing="0">
	<tr>
			<th><?php echo $this->Paginator->sort('id'); ?></th>
			<th><?php echo $this->Paginator->sort('name','名称'); ?></th>
			<th><?php echo $this->Paginator->sort('ry_name', '略称'); ?></th>
			<th><?php echo $this->Paginator->sort('u_email', '受付用メール'); ?></th>
			<th><?php echo $this->Paginator->sort('u_tel', '受付電話番号'); ?></th>
	</tr>
	<?php foreach ($mdivisions as $mdivision): ?>
	<tr>
		<td><?php echo h($mdivision['Mdivision']['id']); ?>&nbsp;</td>
		<td><?php echo h($mdivision['Mdivision']['name']); ?>&nbsp;</td>
		<td><?php echo h($mdivision['Mdivision']['ry_name']); ?>&nbsp;</td>
		<td><?php echo h($mdivision['Mdivision']['u_email']); ?>&nbsp;</td>
		<td><?php echo h($mdivision['Mdivision']['u_tel']); ?>&nbsp;</td>
		<td class="actions">
			<?php echo $this->Html->link(__('Edit'), array('action' => 'edit', $mdivision['Mdivision']['id'])); ?>
		</td>
	</tr>
<?php endforeach; ?>
	</table>
</div>
<?php echo $this->Element('left'); ?>
