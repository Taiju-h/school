<div class="msums index">
	<h2><?php echo __('生アクセスログ'); ?></h2>
	<table cellpadding="0" cellspacing="0">
	<thead>
	<tr>
			<th><?php echo $this->Paginator->sort('date','アクセス日付'); ?></th>
			<th><?php echo $this->Paginator->sort('mfile_id','ファイルタイトル'); ?></th>
			<th><?php echo $this->Paginator->sort('muser_id','ユーザ名もしくはIPADDRESS'); ?></th>
	</tr>
	</thead>
	<tbody>
	<?php foreach ($Efilelogs as $Efilelog): ?>
	<tr>
		<td><?php echo h($Efilelog['Efilelog']['date']); ?>&nbsp;</td>
		<td><?php echo h($Efilelog['Efilelog']['mfile_title']); ?>&nbsp;</td>
		<td>
			<?php if(!is_null($Efilelog['Efilelog']['muser_id']))
					echo h($Efilelog['Muser']['name']); 
			else echo h($Efilelog['Efilelog']['ipadd']); ?>&nbsp;</td>
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