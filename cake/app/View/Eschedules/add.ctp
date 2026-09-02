<div class="Eschedules form">
<?php echo $this->Form->create('Eschedule');
$year = date('Y');if(is_null($id)) $wk ="追　加"; else $wk = "編　集";?>
	<fieldset>
		<legend><?php echo __('スケジュール'.$wk); ?></legend>
	<?php
		echo $this->Form->input('mryoukin_id', array(
			'label' => '講座名',
			'error' => '必須入力項目です。'
			)); ?>
	<table width="100%" border="0" class="contact_form">
		<?php for($ix = 1; $ix <= 9; $ix++) { ?>
				<tr><td class="c_left"><?php echo $this->Form->label('date'.$ix, "開催日".$ix); ?></td>
				<td class="c_right"><Table><Tr><Td> <?php echo $this->Form->year('date'.$ix, $year, $year + 1); echo $this->Form->label('User', '年');?></td>
				<td><?php echo $this->Form->month('date'.$ix, array('monthNames' => false)); echo $this->Form->label('User.', '月');?></td>
				<td><?php echo $this->Form->day('date'.$ix, array('dayhNames' => false)); echo $this->Form->label('User.', '日');?></td></tr></table>
				</td></tr>
		<?php } ?>
		<tr><td class="c_left"><?php echo $this->Form->label('jikan', "開催時間が</BR>デフォルトと</BR>違う場合"); ?></td>
		<td class="c_right"><div class="false"><?php echo $this->Form->input('jikan', array('label' => '', 'div' => 'false')); ?></td></tr>  </td></tr>
		<tr><td class="c_left"><?php echo $this->Form->label('capacity', "定員が</BR>デフォルトと</BR>違う場合"); ?></td>
		<td class="c_right"><div class="false"><?php echo $this->Form->input('capacity', array('label' => '', 'div' => 'false')); ?></td></tr>  </td></tr>
		<?php if(!is_null($id)) { ?>
			<tr><td class="c_left"><?php echo $this->Form->label('deadline', "締切日時"); ?></td>
			<td class="c_right"><div class="false"><?php echo $this->Form->dateTime('deadline', 'YMD', '24', array('monthNames' => false, 'empty ' => false, 'interval' => 15));?></td></tr>
		<?php } ?>
	</table>

	</fieldset>
<?php echo $this->Form->end(__($wk)); ?>
	<h2><?php echo __('締日一覧'); ?></h2>
	<table cellpadding="0" cellspacing="0">
	<thead>
	<tr>
			<th><?php echo $this->Paginator->sort('mryoukin_id','講座名'); ?></th>
			<th><?php echo $this->Paginator->sort('capacity','定員'); ?></th>
			<th><?php echo $this->Paginator->sort('deadline','締切'); ?></th>
			<th><?php echo $this->Paginator->sort('date1', '初回開催日'); ?></th>
			<th><?php echo $this->Paginator->sort('enddate', '最終日'); ?></th>
			<th class="actions"><?php echo __('Actions'); ?></th>
	</tr>
	</thead>
	<tbody>
	<?php foreach ($eschedules as $eschedule): ?>
	<tr>
		<td><?php echo h($eschedule['Mryoukin']['name']); ?>&nbsp;</td>
		<td><?php echo h($eschedule['Eschedule']['capacity']); ?>&nbsp;</td>
		<td><?php echo h($eschedule['Eschedule']['deadline']); ?>&nbsp;</td>
		<td><?php echo h($eschedule['Eschedule']['date1']); ?>&nbsp;</td>
		<td><?php echo h($eschedule['Eschedule']['enddate']); ?>&nbsp;</td>
		<td class="actions">
			<?php echo $this->Html->link(__('編集'), array('action' => 'add', $eschedule['Eschedule']['id'])); ?>
			<?php echo $this->Form->postLink(__('削除'), array('action' => 'delete', $eschedule['Eschedule']['id']), array('confirm' => __('Are you sure you want to delete # %s?', $eschedule['Eschedule']['date1']))); ?>
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
