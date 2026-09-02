＜申込み手順・進捗＞
<center>
<?php
echo $this->Html->image('step1s.png',array('alt'=>'講座選択'));
echo $this->Html->image('step_y.png',array('alt'=>'⇒'));
echo $this->Html->image('step2s.png',array('alt'=>'生徒登録'));
echo $this->Html->image('step_y.png',array('alt'=>'⇒'));
echo $this->Html->image('step3s.png',array('alt'=>'支払方法'));
echo $this->Html->image('step_y.png',array('alt'=>'⇒'));
echo $this->Html->image('step4s.png',array('alt'=>'申込完了'));
?>
</center>
<h2>申込内容の確認</h2>
<h3>キャンセルポリシーの確認</h3>
<span style="color:red;">
<strong>講座によって異なりますので、ご注意ください。</strong>
</span>
</br>
心理タロット講座、ヒプノセラピスト講座につきましては、初回受講まではキャンセル可能です。
受講後は途中返金はいたしません。その他の講座につきましては、最小開催人数の規定があるため、別途定めるキャンセル規定が適用されます。
</br>
<p></p>
<h3>申込講座確認</h3>

	<table border="0" cellspacing="1" class="contact_form m10">
	<tr align="center">
			<th>講座名</th>
			<th>料　金</th>
			<th>削除</th>
	</tr>
	<?php $kng = 0;
	foreach ($Mryoukins as $Mryoukin):
		$kng += $Mryoukin['Mryoukin']['kng'] * TAX;
	if(!$Mryoukin['Mryoukin']['anytime_flg']) $anytime_flg = 0;?>

	<tr>
		<td class="c_right"><?php echo h($Mryoukin['Mryoukin']['name']);
if(!empty($Day[$Mryoukin['Mryoukin']['id']][1])) echo h($Day[$Mryoukin['Mryoukin']['id']][1]); ?></br>
<?php if(!empty($Esche[$Mryoukin['Mryoukin']['id']][1]))
				echo $Esche[$Mryoukin['Mryoukin']['id']][1];
			else echo $Mryoukin['Mryoukin']['opday'] . '</br>' .$Mryoukin['Mryoukin']['optime'];
		?>
		</td>

		<td align="right" class="c_left">
			<?php $wk_kin = $Mryoukin['Mryoukin']['kng'] * TAX;
			echo h(number_format($wk_kin)); ?>&nbsp;</td>
		<td align="right" class="c_del"><?php echo $this->Form->postLink(__('削除'), array('action' => 'delete', $Mryoukin['Mryoukin']['id']), array('confirm' => __('この講座を削除しますか? %s', $Mryoukin['Mryoukin']['name']))); ?>
	</tr>
<?php endforeach; ?>
 <?php  ?>

	<tr>
		<td class="c_right">合　　計</td>
		<td align="right" class="c_left"><?php echo h(number_format($kng)); ?>&nbsp;</td>
		<td align="right" class="c_del"></td>
	</tr>

	</table>
<p></p>
<?php echo $this->Form->create('Msubscription');?>

<h3>生徒情報の確認</h3>
<div align="center">
	<table border="0" cellspacing="1" class="contact_form m10">
		<tr><td class="c_left">お名前</td>
			<td class="c_right"><?php echo h($user['name']); ?></td></tr>
		<tr><td class="c_left">電話番号</td>
			<td class="c_right"><?php echo h($user['usrtel']); ?></td></tr>
		<tr><td class="c_left">メールアドレス</td>
			<td class="c_right"><?php echo h($user['usrmail']); ?></td></tr>
</table>
 <?php echo $this->Form->Submit(__('ユーザ情報を変更します。'), array('name'=>'user_submit'));?>
</div>
<?php echo $this->Form->create('Msubscription');?>
<h3>支払方法と連絡事項</h3>
<input type="hidden" name="kng" value="<?php echo $kng;?>">

	<table width="100%" border="0" cellspacing="1" class="contact_form">
		<tr><td class="c_left">連絡事項</td>
			<td class="c_right"><div class="false"><?php echo $remarks ?></td></tr>
		<tr><td class="c_left">支払方法</td>
			<td class="c_right"><div class="false"><?php echo $mpaymentmethod ?></td></tr>
		

	</table>
<center>
	<table border="0" cellspacing="1">
		<tr><td><?php echo $this->Form->Submit(__('戻る'), array('name'=>'edit_submit'));?></td>
	<td><?php echo $this->Form->Submit(__('申込を確定する'), array('name'=>'submit'));?></td>
</tr>
</table>
</center>
