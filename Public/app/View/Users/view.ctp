<h2><?php  echo __('ユーザ情報'); ?></h2>
	<dl>
	<form action="https://secure.telecomcredit.co.jp/inetcredit/secure/cont.pl" method="post">
		<dt><?php echo __('お　名　前'); ?></dt>
		<dd>
			<?php echo h($user['User']['name']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('電　話　番　号'); ?></dt>
		<dd>
			<?php echo h($user['User']['usrtel']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('お　誕　生　日'); ?></dt>
		<dd>
			<?php echo h($user['User']['birthday']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('メールアドレス'); ?></dt>
		<dd>
			<?php echo h($user['User']['usrmail']); ?>
			&nbsp;
		</dd>
		
		<dt><?php echo __('決済金額'); ?></dt>
		<dd>
		<?php if($user['User']['id'] == 1) { ?>
			<input type="text" name="money" size="10" maxlength="5"></
		</dd>
		<?php } else { ?>
		
		<dd>
			<select name="money" id="money">
				<option value=""></option>
				<option value="3960">３,９６０円(20分)</option>
				<option value="5740">５,７４０円(30分)200円off</option>
				<option value="7520">７,５２０円(40分)400円off</option>
				<option value="9200">９,２００円(50分)700円off</option>
				<option value="10880">１０,８８０円(60分)1000円off</option>
			
			</select>				&nbsp;
		</dd>
		<dt>延長は１分１９８円となります。</dt>
		
		<?php } ?>
		<input type="hidden" name="clientip" value="72531">
		<input type="hidden" name="usrtel" value="<?php echo $user['User']['usrtel']?>">
		<input type="hidden" name="sendid" value="<?php printf("%s%011d", "ISIS_",  $user['User']['id']);?>">
		
<?php echo $this->Form->end(__('決済します')); ?>
<?php echo $this->Html->link(__('ユーザ情報変更'), array('action' => 'edit', $user['User']['id'])); ?>
<?php echo $this->Form->postLink(__('ユーザ情報削除'), array('action' => 'delete', $user['User']['id']), null, __('削除します # %s?', $user['User']['usrmail'])); ?>
