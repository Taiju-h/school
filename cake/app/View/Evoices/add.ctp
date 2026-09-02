<div class="Evoice form">
<style type="text/css">  
BODY {
	text-align: left;
  }
</style> 
<div class="syosai_content">
<?php echo $this->Form->create('Evoice'); if(is_null($id)) $wk ="新規"; else $wk = "編集中";  ?>
	<fieldset>
		<legend><?php echo __('生徒からの感想 ' .  $wk); ?></legend>
	<?php
		echo '<table><tr><td colspan="4">';
		echo $this->Form->input('mryoukin_id', array(
			'label' => '講座名',
			'error' => '必須入力項目です。'
			)); echo '</td></tr>';
/*
		echo $this->Form->input('mevaluate_id', array(
			'label' => '評　　価',
			'error' => '必須入力項目です。'
			)); echo '</td></tr></table>';


		echo '<table><tr><td>';
*/
		echo '<tr><td>';
		echo $this->Form->input('msex_id', array(
			'label' => '性　　別',
			'error' => '必須入力項目です。'
			)); echo '</td><td>';
		echo $this->Form->input('mnendai_id', array(
			'label' => '年　　代',
			'error' => '必須入力項目です。'
			));
		 echo '</td><td>';
						
		echo $this->Form->input('nicname', array(
			'label' => 'ニックネーム',
			'error' => '必須入力項目です。'
			));
		 echo '</td><td>';

		echo $this->Form->input('username', array(
			'label' => '氏名',
			'error' => '必須入力項目です。'
			));
	 echo '</td></tr><tr><td colspan="4">';
		echo  $this->Form->label('impressions', '感　　想');
		echo $this->Form->textarea('impressions', array(
			'cols'=>100, 'rows'=>10));
		echo '</td></tr></table>';
	?>
	</fieldset>
<?php echo $this->Form->end(__('投　稿')); ?>

※感想に個人情報は書かないようにお願いいたします。</br>
※Webに掲載する場合ございます。</br>


	<h2><?php echo __('生徒の感想'); ?></h2>
	<table cellpadding="0" cellspacing="0">
	<thead>
	<tr>
			<th><?php echo $this->Paginator->sort('mryoukin_id','講座名'); ?></th>
			<th><?php echo $this->Paginator->sort('nicname','ニックネーム'); ?></th>
			<th class="actions"><?php echo __('Actions'); ?></th>
			<th><?php echo $this->Paginator->sort('impressions','感想'); ?></th>
			<th><?php echo $this->Paginator->sort('modified','更新日'); ?></th>
	</tr>
	</thead>
	<tbody>
	<?php foreach ($evoices as $evoice): ?>
	<tr>
		<td><?php echo h($evoice['Mryoukin']['rname']); ?>&nbsp;</td>
		<td><?php echo h($evoice['Evoice']['nicname']); ?>&nbsp;</td>
		<td class="actions">
			<?php echo $this->Html->link(__('変更'), array('action' => 'add', $evoice['Evoice']['id'])); ?>
			<?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $evoice['Evoice']['id']), array('confirm' => __('Are you sure you want to delete # %s?',$evoice['Evoice']['nicname']))); ?>
		</td>
		<td><?php echo h(mb_substr($evoice['Evoice']['impressions'],0,20)); ?>&nbsp;</td>
		<td><?php echo h($evoice['Evoice']['modified']); ?>&nbsp;</td>
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
	</div>

<?php echo $this->Element('left'); ?>
