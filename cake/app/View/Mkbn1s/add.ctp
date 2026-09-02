<div class="mkbn1s form">
<?php echo $this->Form->create('Mkbn1'); ?>
	<fieldset>
		<legend><?php echo __('大区分追加'); ?></legend>
	<?php
		echo $this->Form->input('name', array(
			'label' => '区分名',
			'error' => '必須入力項目です。'
			));
		echo $this->Form->input('dname', array(
			'label' => '学習の部屋表示名',
			'error' => '必須入力項目です。'
			));
		echo $this->Form->input('oder', array(
			'label' => '表示順(小さい方から並びます。最初は20単位ぐらいでつけるといいかも）',
			'error' => '必須入力項目です。'
			));
		echo $this->Form->input('taboo_flg', array(
			'label' => '禁断の書専用大区分の場合チェック',
			));
	?>
	</fieldset>
<?php echo $this->Form->end(__('追　加')); ?>
	<h2><?php echo __('大区分一覧'); ?></h2>
	<table cellpadding="0" cellspacing="0">
	<thead>
	<tr>
			<th><?php echo $this->Paginator->sort('name','区分名'); ?></th>
			<th><?php echo $this->Paginator->sort('dname', '学習の部屋表示名'); ?></th>
			<th><?php echo $this->Paginator->sort('oder', '表示順'); ?></th>
			<th><?php echo $this->Paginator->sort('taboo_flg', '禁断の書用'); ?></th>
			<th class="actions"><?php echo __('Actions'); ?></th>
	</tr>
	</thead>
	<tbody>
	<?php foreach ($mkbn1s as $mkbn1): ?>
	<tr>
		<td><?php echo h($mkbn1['Mkbn1']['name']); ?>&nbsp;</td>
		<td><?php echo h($mkbn1['Mkbn1']['dname']); ?>&nbsp;</td>
		<td><?php echo h($mkbn1['Mkbn1']['oder']); ?>&nbsp;</td>
		<td><?php echo h($mkbn1['Mkbn1']['taboo_flg']); ?>&nbsp;</td>
		<td class="actions">
			<?php echo $this->Html->link(__('View'), array('action' => 'view', $mkbn1['Mkbn1']['id'])); ?>
			<?php echo $this->Html->link(__('Edit'), array('action' => 'edit', $mkbn1['Mkbn1']['id'])); ?>
			<?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $mkbn1['Mkbn1']['id']), array('confirm' => __('Are you sure you want to delete # %s?', $mkbn1['Mkbn1']['name']))); ?>
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
