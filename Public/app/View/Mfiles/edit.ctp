<div class="mfiles form">
<?php echo $this->Form->create('Mfile'); ?>
	<fieldset>
		<legend><?php echo __('ファイル修正'); ?></legend>
	<?php
		echo $this->Form->input('mkbn1_id', array(
			'label' => '大区分',
			'error' => '必須入力項目です。'
			));
		echo $this->Form->input('mkbn3_id', array(
			'label' => '中区分',
			'error' => '必須入力項目です。'
			));
		echo $this->Form->input('mkbn2_id', array(
			'label' => '小区分',
			'error' => '必須入力項目です。'
			));
		echo $this->Form->input('title', array(
			'label' => 'タイトル',
			'error' => '必須入力項目です。'
			));
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

	?>
	</fieldset>
<?php echo $this->Form->end(__('修　正')); ?>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('一覧に戻る。'), array('action' => 'index')); ?> </li>
	</ul>
</div>
