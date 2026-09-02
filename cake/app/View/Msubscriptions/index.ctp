<div class="msubscriptions index">
	<h2><?php
	switch ($sts){
			case 50:
				 echo __('受講済み（一部支払いも含む）一覧');
				 break;
			case 55:
				 echo __('分割払い一覧');
				 break;
			case 40:
				 echo __('入学、および初日受講一覧');
				 break;
			case 30;
				 echo __('入学日が過ぎた未受講一覧');
				 break;
			case 999;
				 echo __('紹介インセンティブ対象一覧');
				 break;
			 case 100:
					  echo __('お仕事コース（副業、本業)受講一覧');
					  break;
			case 110:
		 			echo __('お仕事コース（カリスマ)受講一覧');
		 				break;
			default:
				 echo __('講座申し込み一覧');
	}

		 	?></h2>
	<table cellpadding="0" cellspacing="0">
	<thead>
	<tr>
			<th><?php echo $this->Paginator->sort('muser_id', '氏名'); ?></th>
			<th><?php echo $this->Paginator->sort('mworkst_id', '状　態'); ?></th>
			<th class="actions"><?php echo __('Actions'); ?></th>
			<th><?php echo $this->Paginator->sort('mintroduction_id', 'インセ'); ?></th>
			<th><?php echo $this->Paginator->sort('sale_id', '説明'); ?></th>
			<th><?php echo $this->Paginator->sort('mryoukin_id', '講座名'); ?></th>
			<th><?php echo $this->Paginator->sort('mpaymentmethod_id', '支払方法'); ?></th>
			<th><?php echo $this->Paginator->sort('fee','金額'); ?></th>
			<th><?php echo $this->Paginator->sort('admissiondate','入学日'); ?></th>
			<th><?php echo $this->Paginator->sort('modified', '更新日'); ?></th>
	</tr>
	</thead>
	<tbody>
	<?php

	foreach ($msubscriptions as $msubscription):

	 ?>
	<tr>
		<td>
			<?php echo $this->Html->link($msubscription['Muser']['name'], array('controller' => 'musers', 'action' => 'view', $msubscription['Muser']['id'])); ?>
		</td>
		<td><?php echo h($msubscription['Mworkst']['name']); ?>&nbsp;</td>
		<td class="actions">
			<?php if($msubscription['Msubscription']['mworkst_id'] == 10) {
					echo $this->Form->postLink(__('削除'), array('action' => 'delete', $msubscription['Msubscription']['firstid']), array('confirm' => __('Are you sure you want to delete # %s?', $msubscription['Msubscription']['id'])));
					} ?>
			<?php if($msubscription['Msubscription']['mworkst_id'] == 40) {
						echo $this->Html->link(__('受講開始'), array('action' => 'edit1', $msubscription['Msubscription']['id'],50, $msubscription['Msubscription']['eschedule_id']), array('confirm' => __('受講を開始しましたか # %s?', $msubscription['Muser']['name'])));
				} ?>
			<?php echo $this->Html->link(__('更新'), array('action' => 'edit', $msubscription['Msubscription']['id'])); ?>
		</td>
		<td><?php echo h($msubscription['Mintroduction']['name']); ?>&nbsp;</td>
		<td><?php echo h($msubscription['Sale']['name']); ?>&nbsp;</td>

		<td>
		<?php echo h($msubscription['Mryoukin']['name']);
		if($msubscription['Msubscription']['mday_id'] != 99)
			echo '(' . $msubscription['Mday']['rname'] . ')';

		?>
		</td>
		<td><?php echo h($msubscription['Mpaymentmethod']['rname']);?>&nbsp;</td>
		<td><?php echo h($msubscription['Msubscription']['fee']); ?>&nbsp;</td>
		<td><?php echo h($msubscription['Msubscription']['admissiondate']); ?>&nbsp;</td>
		<td><?php echo h($msubscription['Msubscription']['modified']); ?>&nbsp;</td>
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
