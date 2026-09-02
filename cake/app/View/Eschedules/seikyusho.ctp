<div class="Ekanteihoukokue form">
<?php echo $this->Form->create('Mlecturer'); ?>
	<fieldset>
		<legend><?php echo __('講師　請求書作成'); ?></legend>
	
		<?php echo $this->Form->select('id', $mlecturers, array('empty' => '担当講師を選択')); ?>
		<table style="width: 280px;"><Tr><Td> 
		<?php 
		 echo $this->Form->label('Mlecturer', '年');
		echo $this->Form->year('nen', 2030, 2013, array('value' =>date('Y'), 'orderYear' => 'asc')); ?>
		</td>
		<td>
	<?php
		echo $this->Form->label('Mlecturer.', '月');
		echo $this->Form->month('tuki', array('monthNames' => false, 'value' => date('m'))); ?>
		</td>
		</tr>
		</Table>		
	</fieldset>
<?php echo $this->Form->end(__('作成')); ?>
</div>
<?php echo $this->Element('left'); ?>