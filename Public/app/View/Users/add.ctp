<div class="form_kanryo">
<?php if($this->Session->Check('Yoyaku')) {
		$wk = "空席待ち予約";
		$wk1 = "steprs.png";
		$wk2 = "連絡事項";
	} else {
		$wk = "申込み手順";
		$wk1 = "step3s.png";
		$wk2 = "支払方法";
	}
 ?>

<h2>申込み手順(進捗)</h2>
<center>
<?php
echo $this->Html->image('step1s.png',array('alt'=>'講座選択'));
echo $this->Html->image('step_y.png',array('alt'=>'⇒'));
echo $this->Html->image('step2s.png',array('alt'=>'生徒登録'));
echo $this->Html->image('step_y.png',array('alt'=>'⇒'));
echo $this->Html->image($wk1 ,array('alt'=>$wk2));
echo $this->Html->image('step_y.png',array('alt'=>'⇒'));
echo $this->Html->image('step4.png',array('alt'=>'申込完了'));
?>
</center>
<h2>ハートフルスクール情報登録画面</h2>

<?php echo $this->Form->create('User'); $wid = 'width:270px';?>
	<table width="100%" border="0" cellspacing="1" class="contact_form">
		<tr><td class="c_left"><?php echo $this->Form->label('Name', "お名前"); ?></td>
			<td class="c_right"><div class="false"><?php echo $this->Form->input('name', array('label' => '', 'div' => 'false')); ?></td></tr>
		<tr><td class="c_left"><?php echo $this->Form->label('Furigana', "フリガナ"); ?></td>
			<td class="c_right"><div class="false"><?php echo $this->Form->input('furigana', array('label' => '', 'div' => 'false')); ?></td></tr>
		<tr><td class="c_left"><?php echo $this->Form->label('birthday', "生年月日"); ?></td>
		<td class="c_right"><Table><Tr><Td> <?php echo $this->Form->year('birthday', 2000, 1900); echo $this->Form->label('User', '年');?></td>
		<td><?php echo $this->Form->month('birthday', array('monthNames' => false)); echo $this->Form->label('User.', '月');?></td>
		<td><?php echo $this->Form->day('birthday', array('dayhNames' => false)); echo $this->Form->label('User.', '日');?></td></tr></table>
		</td></tr>
		<tr><td class="c_left"><?php echo $this->Form->label('usrtel', "電話番号(-なし)"); ?></td>
			<td class="c_right"><?php echo $this->Form->input('usrtel', array('label' => '', 'div' => 'false')); ?></td></tr>
		<tr><td class="c_left"><?php echo $this->Form->label('usrmail', "メールアドレス"); ?></td>
			<td class="c_right"><div class="false">	<?php echo $this->Form->input('usrmail', array('label' => '', 'div' => 'false','style' => $wid)); ?></td></tr>
		<tr><td class="c_left"><?php echo $this->Form->label('re_usrmail', "確認用メールアドレス"); ?></td>
			<td class="c_right"><div class="false">	<?php echo $this->Form->input('re_usrmail', array('label' => '', 'div' => 'false','style' => $wid)); ?></td></tr>
		<tr><td class="c_left"><?php echo $this->Form->label('password', "パスワード"); ?></td>
			<td class="c_right"><?php echo $this->Form->password('password', array('label' => '', 'div' => 'false')); ?></td></tr>
		<tr><td class="c_left"><?php echo $this->Form->label('re_password', "確認用パスワード"); ?></td>
			<td class="c_right"><?php echo $this->Form->password('re_password', array('label' => '', 'div' => 'false')); ?></td></tr>
		<tr><td class="c_left" colspan=2 >以下は西洋占星術を受講される場合は必須です。わからない場合は不明としてください。</td></tr>
			<tr><td class="c_left"><?php echo $this->Form->label('birthtime', "出生時刻(24時間表記)"); ?></td>
				<td class="c_right"><div class="false">	<?php echo $this->Form->input('birthtime', array('label' => '', 'div' => 'false')); ?></td></tr>
			<tr><td class="c_left"><?php echo $this->Form->label('birthplace', "出生地(都道府県と市区町村まで)"); ?></td>
					<td class="c_right"><div class="false">	<?php echo $this->Form->input('birthplace', array('label' => '', 'div' => 'false')); ?></td></tr>

		<tr><td class="c_left" colspan=2 >以下はオンライン講座の場合は必須入力となります。通学の方は任意です。</td></tr>
		<tr><td class="c_left"><?php echo $this->Form->label('postalcode', "郵便番号(例：123-4567)"); ?></td>
			<td class="c_right"><div class="false"><?php echo $this->Form->input('postalcode', array('label' => '〒', 'div' => 'false', 'style' => 'width:60px')); ?></td></tr>
		<tr><td class="c_left"><?php echo $this->Form->label('add1', "住所"); ?></td>
			<td class="c_right"><div class="false"><?php echo $this->Form->input('add1', array('label' => '', 'div' => 'false')); ?></td></tr>
		<tr><td class="c_left"><?php echo $this->Form->label('add2', "建物名・部屋番号"); ?></td>
			<td class="c_right"><div class="false"><?php echo $this->Form->input('add2', array('label' => '', 'div' => 'false')); ?></td></tr>
	</table>
<center>
<?php echo $this->Form->end(__('登　録')); ?>
</center>
