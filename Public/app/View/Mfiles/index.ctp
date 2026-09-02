<div class="mfiles index">
	<h2><?php echo __('Mfiles'); ?></h2>
	<table cellpadding="0" cellspacing="0">
	<thead>
	<tr>
			<th><?php echo $this->Paginator->sort('mkbn1_id', "大区分"); ?></th>
			<th><?php echo $this->Paginator->sort('mkbn3_id', "中区分"); ?></th>
			<th><?php echo $this->Paginator->sort('mkbn2_id', "小区分"); ?></th>
			<th><?php echo $this->Paginator->sort('title', "表　題"); ?></th>
			<th><?php echo $this->Paginator->sort('filesize', "FileSize"); ?></th>
			<th><?php echo $this->Paginator->sort('filetype', "FileType"); ?></th>
			<th><?php echo $this->Paginator->sort('limit_flg', "開示条件"); ?></th>
			<th><?php echo $this->Paginator->sort('disp_flg', "開示制限"); ?></th>
			<th class="actions"><?php echo __('Actions'); ?></th>
	</tr>
	</thead>
	<tbody>
	<?php foreach ($mfiles as $mfile): ?>
	<tr>
		<td>
			<?php echo $this->Html->link($mfile['Mkbn1']['name'], array('controller' => 'mkbn1s', 'action' => 'view', $mfile['Mkbn1']['id'])); ?>
		</td>
		<td>
			<?php echo $this->Html->link($mfile['Mkbn3']['name'], array('controller' => 'mkbn3s', 'action' => 'view', $mfile['Mkbn3']['id'])); ?>
		</td>
		<td>
			<?php echo $this->Html->link($mfile['Mkbn2']['name'], array('controller' => 'mkbn2s', 'action' => 'view', $mfile['Mkbn2']['id'])); ?>
		</td>
		<td><?php echo h($mfile['Mfile']['title']); ?>&nbsp;</td>
		<td><?php echo h((int)($mfile['Mfile']['filesize'] / 1024 /1024)); ?>(MB)&nbsp;</td>
		<td><?php echo h($mfile['Mfile']['filetype']); ?>&nbsp;</td>
		<td><?php echo h($mfile['Mfile']['limit_flg']); ?>&nbsp;</td>
		<td><?php echo h($mfile['Mfile']['disp_flg']); ?>&nbsp;</td>
		<td class="actions">
			<?php echo $this->Html->link(__('詳細'), array('action' => 'view', $mfile['Mfile']['id'])); ?>
			<?php echo $this->Html->link(__('編集'), array('action' => 'edit', $mfile['Mfile']['id'])); ?>
			<?php echo $this->Form->postLink(__('削除'), array('action' => 'delete', $mfile['Mfile']['id']), array('confirm' => __('Are you sure you want to delete # %s?', $mfile['Mfile']['id']))); ?>
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
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('ファイルをアップロード'), array('action' => 'add')); ?></li>
	</ul>
</div>
