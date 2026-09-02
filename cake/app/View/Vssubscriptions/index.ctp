<div class="vssubscriptions index">
	<h2><?php echo __('講座申し込み一覧'); ?></h2>
	<table cellpadding="0" cellspacing="0">
	<thead>
	<tr>
			<th><?php echo $this->Paginator->sort('muser_id', '氏名'); ?></th>
			<th><?php echo $this->Paginator->sort('mworkst_id', '状　態'); ?></th>
			<th><?php echo $this->Paginator->sort('mryoukin_id', '合計金額'); ?></th>
			<th><?php echo $this->Paginator->sort('mpaymentmethod_id', '支払方法'); ?></th>
			<th><?php echo $this->Paginator->sort('created','作成日'); ?></th>
			<th><?php echo $this->Paginator->sort('modified', '更新日'); ?></th>
			<th class="actions"><?php echo __('Actions'); ?></th>
	</tr>
	</thead>
	<tbody>
	<?php foreach ($vssubscriptions as $vssubscription): ?>
	<tr>
		<td>
			<?php echo $this->Html->link($vssubscription['Muser']['name'], array('controller' => 'musers', 'action' => 'view', $vssubscription['Muser']['id'])); ?>
		</td>
		<td><?php echo h($vssubscription['Mworkst']['name']); ?>&nbsp;</td>
		<td><?php echo h(number_format($vssubscription['Vssubscription']['kng'])); ?>円&nbsp;</td>
		<td>
			<?php echo $this->Html->link($vssubscription['Mpaymentmethod']['name'], array('controller' => 'mpaymentmethods', 'action' => 'view', $vssubscription['Mpaymentmethod']['id'])); ?>
		</td>
		<td><?php echo h($vssubscription['Vssubscription']['created']); ?>&nbsp;</td>
		<td><?php echo h($vssubscription['Vssubscription']['modified']); ?>&nbsp;</td>
		<td class="actions">
			<?php echo $this->Html->link(__('入金あり'), array('action' => 'edit1', $vssubscription['Vssubscription']['id'], 40), array('confirm' => __('入金ありました # %s?', $vssubscription['Muser']['name']))); ?>
			<?php echo $this->Html->link(__('連絡なしキャンセル'), array('action' => 'edit1', $vssubscription['Vssubscription']['id'],110), array('confirm' => __('連絡なしキャンセル # %s?', $vssubscription['Muser']['name']))); ?>
			<?php echo $this->Html->link(__('ありキャンセル'), array('action' => 'edit1', $vssubscription['Vssubscription']['id'],100), array('confirm' => __('連絡ありキャンセル # %s?', $vssubscription['Muser']['name']))); ?>
			<?php echo $this->Form->postLink(__('削除'), array('action' => 'delete', $vssubscription['Vssubscription']['id']), array('confirm' => __('Are you sure you want to delete # %s?',  $vssubscription['Muser']['name']))); ?>
			<?php echo $this->Html->link(__('既存生徒2'), array('action' => 'edit1', $vssubscription['Vssubscription']['id'], 41), array('confirm' => __('付帯講座ですか # %s?', $vssubscription['Muser']['name']))); ?>
		</td>
	</tr>
<?php endforeach; ?>
	</tbody>
	</table>
	<p>
	<?php
	echo $this->Paginator->counter(array(
		'format' => __('Page {:page} of {:pages}, showing {:current} records out of {:count} total, starting on record {:start}, ending on {:end}')
	));
	?>	</p>
	<div class="paging">
	<?php
		echo $this->Paginator->prev('< ' . __('previous'), array(), null, array('class' => 'prev disabled'));
		echo $this->Paginator->numbers(array('separator' => ''));
		echo $this->Paginator->next(__('next') . ' >', array(), null, array('class' => 'next disabled'));
	?>
	</div>
</div>
<?php echo $this->Element('left'); ?>
