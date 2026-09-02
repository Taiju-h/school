<h2>ユーザー情報・キャンセルポリシー確認画面</h2>
	<table border="0" cellspacing="1" class="contact_form m10">
		<tr><td class="c_left">郵便番号(例：123-4567)</td>
			<td class="c_right">〒<?php echo h($user['User']['postalcode']); ?></td></tr>
		<tr><td class="c_left">住所</td>
			<td class="c_right"><?php echo h($user['User']['add1']); ?></td></tr>
		<tr><td class="c_left">建物名・部屋番号</td>
			<td class="c_right"><?php echo h($user['User']['add2']); ?></td></tr>
		<tr><td class="c_left">お名前</td>
			<td class="c_right"><?php echo h($user['User']['name']); ?></td></tr>
		<tr><td class="c_left">電　話　番　号</td>
			<td class="c_right"><?php echo h($user['User']['usrtel']); ?></td></tr>
		<tr><td class="c_left">メールアドレス</td>
			<td class="c_right"><?php echo h($user['User']['usrmail']); ?></td></tr>
</table>
<p>
<div class="form_kanryo">
<p>☆お支払いに関して</p>
<p>
<span style="color:red;">
<strong>返品について　講座によって異なりますので、ご注意ください。</strong>
</span>
</br>
心理タロットプロ講座、ヒプノセラピスト講座につきましては</br>
初回受講まではキャンセル可能です。受講後は途中返金はいたしません。</br>
その他の講座につきましては、最小開催人数の規定があるため、別途定めるキャンセル規定が適用されます。</br>
</div>
<?php echo $this->Form->create('Msubscription'); ?>
	<table width="100%" border="0" cellspacing="1" class="contact_form">
<?php	
	$name = "講座を選んでください。";
//var_dump($mryoukin['Mryoukin']['name']); exit;
?>
		<tr><td class="c_left"><?php echo $this->Form->label('Msubscription', $name); ?></td>
			<td class="c_right"><div class="false"><?php echo $this->Form->input('Mryoukin', array('label' => '', 'type' => 'select','multiple'=> 'checkbox','options' => $mryoukins,)) ?></td></tr>

	<?php $name = NULL; 
	//endforeach; 
	?>
		<tr><td class="c_left"><?php echo $this->Form->label('Msubscription', '備　考'); ?></td>
			<td class="c_right"><div class="false">
				<?php echo $this->Form->textarea('Remarks', array(
			'cols'=>50, 'rows'=>8)); ?></td></tr>
	</table>
<table><tr><td> <?php echo $this->Form->Submit(__('同意し申込します。'), array('name'=>'submit')); ?>	
<td> <?php if($user_flg) echo $this->Form->Submit(__('登録内容を変更します。'), array('name'=>'edit_submit'));?></td></table>
