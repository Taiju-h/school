<div class="Ekanteihoukokue form">
<?php echo $this->Form->create('Mlecturer'); ?>
	<fieldset>
		<legend><?php echo __('受講申し込み一覧'); ?></legend>
	
		<?php echo $this->Form->select('id', $mlecturers, array('empty' => '担当講師を選択してください。')); ?>
	</br>※終了日が今日以降のもので担当講師のものを出力します。
		
	</fieldset>
<?php echo $this->Form->end(__('作成')); ?>
</div>
<?php echo $this->Element('left'); ?>