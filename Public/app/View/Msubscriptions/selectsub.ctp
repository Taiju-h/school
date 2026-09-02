<h2>受講を希望する講座を選んでください。</h2>
<?php echo $this->Form->create('Msubscription'); ?>
	<table width="100%" border="0" cellspacing="1" class="contact_form">
<?php	
	$name = "講　座";
?>
		<tr><td class="c_left"><?php echo $this->Form->label('Msubscription', $name); ?></td>
			<td class="c_right"><div class="false"><?php echo $this->Form->input('Mryoukin', array('label' => '', 'type' => 'select','multiple'=> 'checkbox','options' => $mryoukins,)) ?></td></tr>

	<?php $name = NULL; 
	//endforeach; 
	?>
		<tr><td class="c_left"><?php echo $this->Form->label('Msubscription', '備　考'); ?></td>
			<td class="c_right"><div class="false">
				<?php echo $this->Form->textarea('Remarks', array(
			'cols'=>50, 'rows'=>8)); ?></td></tr>
	</table>
<table><tr><td> <?php echo $this->Form->Submit(__('お客様情報（次へ）'), array('name'=>'submit'));  ?>	
<td> <?php echo $this->Form->Submit(__('講座種類選択（戻る）'), array('name'=>'back'));?></td></table>
<?php echo $this->Form->end(); ?>	
