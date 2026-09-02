<div class="mfiles index">
	<h2><?php echo __('Mfiles'); ?></h2>
	<table cellpadding="0" cellspacing="0">
	<thead>
	<tr>
			<th><?php echo $this->Paginator->sort('id', 'ID'); ?></th>
			<th><?php echo $this->Paginator->sort('mcoursename_id', '単独講座名'); ?></th>
			<th><?php echo $this->Paginator->sort('title', 'タイトル'); ?></th>
			<th><?php echo $this->Paginator->sort('thumbnail','サムネイル'); ?></th>
			<th><?php echo $this->Paginator->sort('id','QR CODE'); ?></th>
			<th><?php echo $this->Paginator->sort('filetype', 'タイプ'); ?></th>
			<th><?php echo $this->Paginator->sort('description','説明'); ?></th>
			<th><?php echo $this->Paginator->sort('limit_flg', '会員'); ?></th>
			<th><?php echo $this->Paginator->sort('disp_flg', '開示'); ?></th>
			<th><?php echo $this->Paginator->sort('taboo_flg', '禁断'); ?></th>
			<th class="actions"><?php echo __('Actions'); ?></th>
	</tr>
	</thead>
	<tbody>
	<?php foreach ($mfiles as $mfile): ?>
	<tr>
		<td><?php echo h($mfile['Mfile']['id']); ?>&nbsp;</td>
		<td><?php echo h($mfile['Mcoursename']['rname']); ?>&nbsp;</td>
		<td><?php echo h($mfile['Mfile']['title']); ?>&nbsp;</td>
		<td><?php $image = base64_encode($mfile['Mfile']['thumbnail']);
			echo "<img src='data:image/jpeg;base64,${image}' />"; ?></td>
		<?php 
		if($mfile['Mfile']['list_flg']) {
			 $filename = './qrcord/' . $mfile['Mfile']['id'] . '.png';
			 $url = FULL_BASE_URL . '/Public/mfiles/oview/' . $mfile['Mfile']['id'];
		} else  {
			 $filename =  './qrcord/' . $mfile['Mfile']['title'] . '.png';
			 $url = FULL_BASE_URL . '/Public/mfiles/oview/' . $mfile['Mfile']['id'] .'/' . $mfile['Mfile']['id'];
		}
		QRcode::png($url, $filename, 'L', 3 , 2);	
		echo '<td>';
		echo '<img src="'. FULL_BASE_URL . '/cake/' . $filename . '" />';?>
		</td>
		
		
		<td><?php echo h($mfile['Mfile']['filetype']); ?>&nbsp;</td>
		<td><?php echo h($mfile['Mfile']['description']); ?>&nbsp;</td>
		<td><?php echo h($mfile['Mfile']['limit_flg']); ?>&nbsp;</td>
		<td><?php echo h($mfile['Mfile']['disp_flg']); ?>&nbsp;</td>
		<td><?php echo h($mfile['Mfile']['taboo_flg']); ?>&nbsp;</td>
		<td class="actions">
			<?php echo $this->Html->link(__('表示'), array('action' => 'view', $mfile['Mfile']['id'])); ?>
			<?php echo $this->Html->link(__('サムネイル編集'), array('action' => 'edit', $mfile['Mfile']['id'])); ?>
			<?php echo $this->Html->link(__('ファイル編集'), array('action' => 'edit2', $mfile['Mfile']['id'])); ?>
			<?php echo $this->Form->postLink(__('削除'), array('action' => 'delete', $mfile['Mfile']['id']), array('confirm' => __('Are you sure you want to delete # %s?', $mfile['Mfile']['title']))); ?>
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