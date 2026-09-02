<div class="Ekanteihoukokue form">
<?php echo $this->Form->create('Vtodaylist'); ?>
	<fieldset>
		<legend><?php echo __('参加者一覧'); ?></legend>
	
		<?php echo $this->Form->select('kaisaidate', $vtodaylists, array('empty' => '開催日を選択')); ?>
		
	</fieldset>
<?php echo $this->Form->end(__('作成')); ?>
</div>
<?php echo $this->Element('left'); ?>