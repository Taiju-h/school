
<?php echo $this->Html->image('kindan_contentbanner.jpg');?>


<div class="login_taboo_main">
<div class="login_taboo">
<?php echo $this->Form->create('User'); ?>
<div class="form_kanryo">
	<table border="0" cellspacing="1" class="contact_form m10">
		<tr><td class="c_left"><?php echo $this->Form->label('usrmail', "メールアドレス"); ?></td>
			<td class="c_right"><?php echo $this->Form->input('usrmail', array('label' => '', 'div' => 'false', 'style' => 'width:280px')); ?></td></tr>
		<tr><td class="c_left"><?php echo $this->Form->label('password', "パスワード"); ?></td>
		<td class="c_right"><?php echo $this->Form->password('password', array('label' => '', 'div' => 'false')); ?></td></tr>
    </table>
    <input type="hidden" name="data[User][confirmed]" id="UserConfirmed"/><div class="submit">
<center>
	<?php echo $this->Form->end(__('ログイン')); ?>
</center>
</div>
<div class="form_kanryo">
<p><?php echo $this->Html->link('パスワードを忘れた方、または変更される方はこちらをクリックしてください。', array('controller' => 'Users', 'action' => 'passwordlost'),array('escape' => false), false); ?>
</div>
</div>
</div>
