<?php if($this->Session->Read('Kizon_flg')) 
	$wk = "＜占い師独立講座付帯講座申し込み・進捗＞";
	else $wk = "＜空席待ち手順・進捗＞";
?>
<center>
<?php 
echo $this->Html->image('step1s.png',array('alt'=>'講座選択')); 
echo $this->Html->image('step_y.png',array('alt'=>'⇒')); 
echo $this->Html->image('step2s.png',array('alt'=>'生徒登録')); 
echo $this->Html->image('step_y.png',array('alt'=>'⇒')); 
echo $this->Html->image('steprs.png',array('alt'=>'連絡事項')); 
echo $this->Html->image('step_y.png',array('alt'=>'⇒')); 
echo $this->Html->image('step4.png',array('alt'=>'申込完了')); 
?>
</center>
<h2><?php echo $wk; ?></h2>

	<table border="0" cellspacing="1" class="contact_form m10">
	<tr>
			<th>講座名</th>
	</tr>
	<?php foreach ($Mryoukins as $Mryoukin): ?>
	<tr>
		<td class="c_right"><?php echo h($Mryoukin['Mryoukin']['name'] . $Day[$Mryoukin['Mryoukin']['id']][1]); ?></br>
		<?php if(!empty($Esche[$Mryoukin['Mryoukin']['id']][1]))
				echo $Esche[$Mryoukin['Mryoukin']['id']][1];
			else echo $Mryoukin['Mryoukin']['opday'] . '</br>' .$Mryoukin['Mryoukin']['optime'];
		?>
		</td>

	</tr>
<?php endforeach; ?>
<p></p>
<?php echo $this->Form->create('Msubscription');?>

<h3>生徒情報の確認</h3>
<div align="center">		
	<table border="0" cellspacing="1" class="contact_form m10">
		<tr><td class="c_left">お名前</td>
			<td class="c_right"><?php echo h($user['name']); ?></td></tr>
		<tr><td class="c_left">電話番号</td>
			<td class="c_right"><?php echo h($user['usrtel']); ?></td></tr>
		<tr><td class="c_left">メール</br>アドレス</td>
			<td class="c_right"><?php echo h($user['usrmail']); ?></td></tr>
</table>
 <?php echo $this->Form->Submit(__('ユーザ情報を変更します。'), array('name'=>'user_submit'));?>
<?php echo $this->Form->create('Msubscription');?>
<div>
</br>
<h3>連絡事項</h3>

	<table width="100%" border="0" cellspacing="1" class="contact_form">
		<tr><td class="c_left">連絡事項</td>
			<td class="c_right"><div class="false"><?php echo $remarks ?></td></tr>
	</table>
<center>
	<table border="0" cellspacing="1">
		<tr><td><?php echo $this->Form->Submit(__('戻る'), array('name'=>'edit_submit'));?></td>
	<td><?php echo $this->Form->Submit(__('確定する'), array('name'=>'submit'));?></td>
</tr>
</table>
</center>

