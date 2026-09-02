
</center>
<h2>ハートフルスクール情報登録画面</h2>

<?php echo $this->Form->create('Muser'); $wid = 'width:270px';?>
	<table width="100%" border="0" cellspacing="1" class="contact_form">
		<tr><td class="c_left"><?php echo $this->Form->label('Name', "お名前"); ?></td>
			<td class="c_right"><div class="false"><?php echo $this->Form->input('name', array('label' => '', 'div' => 'false')); ?></td></tr>
		<tr><td class="c_left"><?php echo $this->Form->label('Furigana', "フリガナ"); ?></td>  
			<td class="c_right"><div class="false"><?php echo $this->Form->input('furigana', array('label' => '', 'div' => 'false')); ?></td></tr>
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

	</table>
<?php echo $this->Form->end(__('登　録')); ?>

