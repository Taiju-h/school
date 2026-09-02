<div class="Ekanteihoukokue form">
<?php echo $this->Form->create('Msubscription'); ?>
	<fieldset>
		<legend><?php echo __('集客講座申し込み'); ?></legend>
	
		<?php echo $this->Form->select('muser_id', $vdokuritsums, array('empty' => '参加者を選択')); ?>
		<?php echo $this->Form->select('eschedule_id', $Eschedules, array('empty' => '参加日を選択')); ?>
		
	</fieldset>
<?php echo $this->Form->end(__('作成')); ?>
</div>
<?php echo $this->Element('left'); ?>