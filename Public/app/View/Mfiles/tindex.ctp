<?php echo $this->Html->image('mainvisual.jpg');?>

<div class="study_shell">
	<?php foreach ($mkbn1s as $key => $value): 	?>
		<li class="study_thumb"><?php echo $this->Html->link($value, array( 'action' => 'tindex', $key),array('escape' => false), false);?></li>
	<?php endforeach; ?>
</div>
<?php if(! is_null($id)) { ?>
	<div class="st">
	<div class="study_mokuji">
		<div class="mokuji_text">
	    <ul>
		
		<?php //$foreach ($Mfiles as $key => $value): 	
			foreach ($mkbn3s as $mkbn3): ?>
			<li><?php echo $this->Html->link($mkbn3['Mkbn3']['name'], array( 'action' => 'findex', $mkbn3['Mkbn3']['id'], $kbn1, 3),array('escape' => false), false);?></li>
		<?php endforeach; ?>
		<?php //$foreach ($Mfiles as $key => $value): 	
			foreach ($mkbn2s as $mkbn2): ?>
			<li><?php echo $this->Html->link($mkbn2['Mkbn2']['name'], array( 'action' => 'findex', $mkbn2['Mkbn2']['id'], $kbn1, 2),array('escape' => false), false);?></li>
		<?php endforeach; ?>
	    </ul>
	 </div>

	<?php } ?>
</div>