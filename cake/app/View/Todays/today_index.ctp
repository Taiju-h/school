
	<div style="font-size:320%">
<h1>	
<?php echo $msubscriptions[0]['Mryoukin']['name'] . $days , '回目　受付'; ?>
</h1>
	 <table>
<?php	foreach ($msubscriptions as $msubscription) {
			echo '<tr><td style=" height: 50px; padding: 20px;">';
			//echo $msubscription['Muser']['name'];
			echo $this->Html->link(__($msubscription['Muser']['name'] . '様'), array('action' => 'edit2', $msubscription['Msubscription']['id'], $days, $msubscription['Msubscription']['eschedule_id'],$msubscription['Msubscription']['mworkst_id'], ), array('confirm' => __('お名前はあってますか？ # %s?', $msubscription['Muser']['name'] . '様'))); 
			echo '</td></tr>';
		}
// $msubscription['Msubscription']['id']
		echo "</table>";
?>
