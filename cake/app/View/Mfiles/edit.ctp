<div class="mfiles form">
<?php
	$options = array(
//		'action'=>'upload',
		'type'=>'file'
	);

echo $this->Form->create('Mfile', $options); ?>
	<fieldset>
		<legend><?php echo __('サムネイル変更'); ?></legend>
	<?php
		echo $this->Form->input('title', array(
			'label' => 'タイトル',
			'error' => '必須入力項目です。'
			));
		
		echo  $this->Form->label('thumbnail', 'サムネイル');

		echo $this->Form->file('thumbnail');
		echo  $this->Form->label('description', '説　明');
		echo $this->Form->textarea('description', array(
			'cols'=>50, 'rows'=>8));
		echo $this->Form->input('limit_flg', array(
			'label' => '会員限定',
			'error' => '必須入力項目です。'
			));
		echo $this->Form->input('disp_flg', array(
			'label' => '公開する。',
			'error' => '必須入力項目です。'
			));
		echo $this->Form->input('list_flg', array(
			'label' => '関連付け一覧に表示するか？',
			'error' => '必須入力項目です。'
			));
		echo $this->Form->input('taboo_flg', array(
			'label' => '禁断の書限定',
			'error' => '必須入力項目です。'
			));	?>
	</fieldset>
<?php echo $this->Form->end(__('修　正')); ?>
</div>
<?php echo $this->Element('left'); ?>