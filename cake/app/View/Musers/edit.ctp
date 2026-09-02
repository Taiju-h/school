
<div class="Muser form">
<h2><?php echo __('ユーザ情報更新'); ?></h2>
<?php echo $this->Form->create('Muser');  $wid = 'width:270px';?>
	<fieldset>


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
		<tr><td class="c_left" colspan=2 >以下は通信教育の場合は必須入力となります。通学の方は任意です。</td></tr>
		<tr><td class="c_left"><?php echo $this->Form->label('postalcode', "郵便番号(例：123-4567)"); ?></td>
			<td class="c_right"><div class="false"><?php echo $this->Form->input('postalcode', array('label' => '〒', 'div' => 'false', 'style' => 'width:60px')); ?></td></tr>
		<tr><td class="c_left"><?php echo $this->Form->label('add1', "住所"); ?></td>
			<td class="c_right"><div class="false"><?php echo $this->Form->input('add1', array('label' => '', 'div' => 'false', 'style' =>  $wid)); ?></td></tr>
		<tr><td class="c_left"><?php echo $this->Form->label('add2', "建物名・部屋番号"); ?></td>
			<td class="c_right"><div class="false"><?php echo $this->Form->input('add2', array('label' => '', 'div' => 'false', 'style' =>  $wid)); ?></td></tr>
	</table>
	</fieldset>
<?php echo $this->Form->end(__('更　新')); ?>

</div>
<?php echo $this->Element('left'); ?>
