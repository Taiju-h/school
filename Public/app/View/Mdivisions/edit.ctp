<div class="mdivisions form">
<?php echo $this->Form->create('Mdivision'); ?>
	<fieldset>
		<legend><?php echo __('会社固有情報　編集'); ?></legend>
	<?php
		echo $this->Form->input('name', array(
			'label' => '名　　称',
			'error' => '必須入力項目です。'
			));
		echo $this->Form->input('ry_name', array(
			'label' => '略　　称',
			'error' => '必須入力項目です。'
			));
		echo $this->Form->input('kaishaname', array(
			'label' => '請求書会社名',
			));
		echo $this->Form->input('postcode', array(
			'label' => '郵便番号',
			));
		echo $this->Form->input('address', array(
			'label' => 'アドレス',
			));
		echo $this->Form->input('address2', array(
			'label' => 'アドレス２',
			));
		echo $this->Form->input('daihyouname', array(
			'label' => '請求者名',
			));
		echo $this->Form->input('telno', array(
			'label' => '請求者電話番号',
			));
		echo $this->Form->input('email', array(
			'label' => '請求者email',
			));
		echo $this->Form->input('mbank_id', array(
			'empty' => '----',
			'label' => '銀　行　名',
			'error' => '必須入力項目です。'
			));
		echo $this->Form->input('branch', array(
			'label' => '支店コード',
			'error' => '必須入力項目です。'
			));
		echo $this->Form->input('branchname', array(
			'label' => '支　店　名',
			'error' => '必須入力項目です。'
			));
		echo $this->Form->input('account', array(
			'label' => '口　座　番　号',
			'error' => '必須入力項目です。'
			));
		echo $this->Form->input('accountname', array(
			'label' => '口座名義人',
			'error' => '必須入力項目です。'
			));
		echo $this->Form->input('u_email', array(
			'label' => '受付送信用メールアドレス称',
			'error' => '必須入力項目です。'
			));
		echo $this->Form->input('u_tel', array(
			'label' => '受付電話番号',
			'error' => '必須入力項目です。'
			));

?>
	</fieldset>
<?php echo $this->Form->end(__('登　　録')); ?>
</div>
<?php echo $this->Element('left'); ?>
