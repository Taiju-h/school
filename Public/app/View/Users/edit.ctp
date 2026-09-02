<h2>ユーザー情報更新画面</h2>
<?php echo $this->Form->create('User'); ?>
	<table width="100%" border="0" cellspacing="1" class="contact_form">
		<tr><td class="c_left"><?php echo $this->Form->label('Name', "お名前"); ?></td>
			<td class="c_right"><div class="false"><?php echo $this->Form->input('name', array('label' => '', 'div' => 'false')); ?></td></tr>
		<tr><td class="c_left"><?php echo $this->Form->label('Furigana', "フリガナ"); ?></td>  
			<td class="c_right"><div class="false"><?php echo $this->Form->input('furigana', array('label' => '', 'div' => 'false')); ?></td></tr>
		<tr><td class="c_left"><?php echo $this->Form->label('usrtel', "電話番号"); ?></td>
			<td class="c_right"><?php echo $this->Form->input('usrtel', array('label' => '', 'div' => 'false')); ?></td></tr>
		<tr><td class="c_left"><?php echo $this->Form->label('usrmail', "メールアドレス"); ?></td>
			<td class="c_right"><div class="false">	<?php echo $this->Form->input('usrmail', array('label' => '', 'div' => 'false','style' => 'width:400px')); ?></td></tr>
		<tr><td class="c_left"><?php echo $this->Form->label('re_usrmail', "確認用メールアドレス"); ?></td>
			<td class="c_right"><div class="false">	<?php echo $this->Form->input('re_usrmail', array('label' => '', 'div' => 'false','style' => 'width:400px')); ?></td></tr>
		<tr><td class="c_left" colspan=2 >以下は通信教育の場合は必須入力となります。通学の方は任意です。</td></tr>
		<tr><td class="c_left"><?php echo $this->Form->label('postalcode', "郵便番号"); ?></td>
			<td class="c_right"><div class="false"><?php echo $this->Form->input('postalcode', array('label' => '', 'div' => 'false', 'style' => 'width:60px')); ?></td></tr>
		<tr><td class="c_left"><?php echo $this->Form->label('add1', "住所１"); ?></td>
			<td class="c_right"><div class="false"><?php echo $this->Form->input('add1', array('label' => '', 'div' => 'false', 'style' => 'width:450px')); ?></td></tr>
		<tr><td class="c_left"><?php echo $this->Form->label('add2', "住所２"); ?></td>
			<td class="c_right"><div class="false"><?php echo $this->Form->input('add2', array('label' => '', 'div' => 'false', 'style' => 'width:450px')); ?></td></tr>
	</table>
<?php echo $this->Form->end(__('更　新')); ?>
