<div class="msubscriptions view">
<h2><?php echo __('ユーザ情報詳細'); ?></h2>
	<dl>
		<dt><?php echo __('Id'); ?></dt>
		<dd>
			<?php echo h($muser['Muser']['id']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('お名前'); ?></dt>
		<dd>
			<?php echo h($muser['Muser']['name']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('フリガナ'); ?></dt>
		<dd>
			<?php echo h($muser['Muser']['furigana']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('生年月日'); ?></dt>
		<dd>
			<?php echo h($muser['Muser']['birthday']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('電話番号'); ?></dt>
		<dd>
			<?php echo h($muser['Muser']['usrtel']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('メールアドレス'); ?></dt>
		<dd>
			<?php echo h($muser['Muser']['usrmail']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('郵便番号'); ?></dt>
		<dd>
			<?php echo h($muser['Muser']['postalcode']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('住所'); ?></dt>
		<dd>
			<?php echo h($muser['Muser']['add1']); ?>
			<?php echo h($muser['Muser']['add2']); ?>
			&nbsp;
		</dd>
	</dl>
<P>
	<h3><?php echo __('申込情報'); ?></h3>
	<table cellpadding="0" cellspacing="0">
	<?php foreach ($msubscriptions as $msubscription): ?>
	<tr>
		<td><?php echo h($msubscription['Mworkst']['name']); ?>&nbsp;</td>
		<td><?php echo h($msubscription['Mryoukin']['name']); ?>&nbsp;</td>
		<td>
			<?php echo $this->Html->link($msubscription['Mpaymentmethod']['name'], array('controller' => 'mpaymentmethods', 'action' => 'view', $msubscription['Mpaymentmethod']['id'])); ?>
		</td>
		<td><?php echo h($msubscription['Msubscription']['date1']); ?>&nbsp;</td>
		<td><?php echo h($msubscription['Msubscription']['admissiondate']); ?>&nbsp;</td>
		<td><?php echo h($msubscription['Msubscription']['modified']); ?>&nbsp;</td>
		<td class="actions">
			<?php if($msubscription['Msubscription']['mworkst_id'] == 10) {  
					echo $this->Form->postLink(__('削除'), array('controller' => 'Msubscriptions',  'action' => 'delete', $msubscription['Msubscription']['firstid']), array('confirm' => __('Are you sure you want to delete # %s?', $msubscription['Msubscription']['id']))); 
					} ?>
			<?php if($msubscription['Msubscription']['mworkst_id'] == 40) {
						echo $this->Html->link(__('受講開始'), array('controller' => 'Msubscriptions', 'action' => 'edit1', $msubscription['Msubscription']['firstid'],50), array('confirm' => __('受講を開始しましたか # %s?', $msubscription['Muser']['name']))); 
				} ?>
			<?php echo $this->Html->link(__('更新'), array('controller' => 'Msubscriptions', 'action' => 'edit', $msubscription['Msubscription']['id'])); ?>
		</td>
	</tr>
<?php endforeach; ?>
	</tbody>
	</table>

	
</div>
<?php echo $this->Element('left'); ?>
