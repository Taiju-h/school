<?php 	if($btn) {
			echo '<div class="kouza_99">';
			echo $this->Html->link(' ', array('controller' =>  'Msubscriptions' , 'action' => 'selectkoza', $mryoukin['Mryoukin']['id'], $row['Eschedule']['id']), array( 'target' => '_top' ) );
			return;

} else {
	$week = array("日", "月", "火", "水", "木", "金", "土");
 ?>

<table width="100%" border="0" cellspacing="1" class="contact_form">
<tr><td class="c_left">講座時間：</td><td class="c_right"><?php echo $mryoukin['Mryoukin']['period']; ?></td></tr>
<tr><td class="c_left">受講料金：</td><td class="c_right"><?php echo h(number_format($mryoukin['Mryoukin']['kng'])) . '円(内税）'; ?></td></tr>
<?php if(is_null($mryoukin['Mryoukin']['opday'])) {
		if(empty($data)) {
			echo '<tr><td class="c_left"></td><td class="c_right">' ;
			echo '<div class="kouza_inquiry">';
						echo $this->Html->link(' ', FULL_BASE_URL . '/index.php?go=WbHXJNarray', array( 'target' => '_top' ) );
			echo "</div></td></tr>";
		} else {		
			
			foreach($data as $row) :
				if(empty($row['Eschedule']['jikan'])) $jikan = $mryoukin['Mryoukin']['optime']; 
				else $jikan = $row['Eschedule']['jikan'];
				$jikan = "(" . $jikan . ")";
				echo '<tr><td class="c_left">開催日時:</td>';
				$w = (int)$datetime->format('w', $row['Eschedule']['date1']);

				$date = ' 1日： ' . $row['Eschedule']['date1'] . '('. $week[$w]. ') ' . $jikan;
				if(! empty($row['Eschedule']['date2'])) {
					$w = (int)$datetime->format('w', $row['Eschedule']['date2']);
				
					$date .= "</br> 2日： " . $row['Eschedule']['date2']  . '('. $week[$w]. ') ' . $jikan;
				}
				if(! empty($row['Eschedule']['date3'])) {
					$w = (int)$datetime->format('w', $row['Eschedule']['date2']);

					$date .= "</br> 3日： "  . $row['Eschedule']['date3']  . '('. $week[$w]. ') ' . $jikan;
				}
				$deadline = new DateTime($row['Eschedule']['deadline']);
			
				
					$w = (int)$datetime->format('w',$deadline->modify('-1 days'));

				$date .= " </br> 申込締切 "  . $datetime ->format('Y-m-d') . '(' . $week[$w] . ')';//    2016-01-25
				unset($deadline);
				echo '<td class="c_right">' . $date;
				if($row['Eschedule']['deadline'] > date("Y-m-d")) {
					if( $mryoukin['Mryoukin']['capacity'] <= $row[0]['cnt']){
						echo '  定員に達しました。締め切りました。 </td></tr>';
					}else { 
						$zan = $mryoukin['Mryoukin']['capacity'] - $row[0]['cnt'];
						if($zan <=  2) echo '</br> 残席　' . $zan .'席お早めに</br>';
						echo '<div class="kouza_99">';
						echo $this->Html->link(' ', array('controller' =>  'Msubscriptions' , 'action' => 'selectkoza', $mryoukin['Mryoukin']['id'], $row['Eschedule']['id']), array( 'target' => '_top' ) );
						echo "</div></td></tr>";
					}
				} else if($row['Eschedule']['date1'] > date("Y-m-d")) {
							if( $mryoukin['Mryoukin']['capacity'] > $row[0]['cnt']){
			
								echo '<div class="kouza_inquiry">';
								echo $this->Html->link(' ', FULL_BASE_URL . '/index.php?go=WbHXJNarray', array( 'target' => '_top' ) );
								echo "</div></td></tr>";
							} else echo '  定員に達しました。締め切りました。 </td></tr>';
				} else {
				
						//echo "</br> <center>" . $this->Html->image("kouza_kaisai.jpg") . "</center>"; 
						echo "</br> <center>好 評 開 催 中</center>"; 
						echo "</td></tr>";
				}
			endforeach;
		}		
		if(!is_null($mryoukin['Mryoukin']['remearks'])) {
			echo '<tr><td class="c_left">備　　考:</td><td class="c_right">' . $mryoukin['Mryoukin']['remearks'];
			echo "</td></tr>";
		}
	
	
		} else {
			echo '<tr><td class="c_left">開催日時:</td><td class="c_right">' . $mryoukin['Mryoukin']['opday'] . '</br>' . $mryoukin['Mryoukin']['optime'];

			if(isset($mryoukin1)){
				for($ix = 0; $ix < 7; $ix++) {
					$wk = "day" . $ix;
				 	if($mryoukin1['Mryoukin'][$wk] == 1) {

						echo '<div class="kouza_99' . $ix . '"> ';
						echo $this->Html->link(' ', array('controller' =>  'Msubscriptions', 'action' => 'selectkoza', $mryoukin['Mryoukin']['id'], 0, $ix), array( 'target' => '_top' ));
						echo "</div>";
					} else if($mryoukin1['Mryoukin'][$wk] == 2) {
						echo '<div class="kouza_99' . $ix . '2"> ';
						echo $this->Html->link(' ', array('controller' =>  'Msubscriptions', 'action' => 'selectkoza', $mryoukin['Mryoukin']['id'], 0, $ix, 2), array( 'target' => '_top' ));
						echo "</div>";
					}
				}
			} else {
						echo '<div class="kouza_99"> ';
						echo $this->Html->link(' ', array('controller' =>  'Msubscriptions', 'action' => 'selectkoza', $mryoukin['Mryoukin']['id']), array( 'target' => '_top' ));
						echo "</div>";
			}
			echo "</td></tr>";
			if(!is_null($mryoukin1['Mryoukin']['remearks'])) {
				echo '<tr><td class="c_left">備　　考:</td><td class="c_right">' . $mryoukin1['Mryoukin']['remearks'];
				echo "</td></tr>";
			}
			if(!isset($mryoukin1) AND (!is_null($mryoukin['Mryoukin']['remearks']))) {
				echo '<tr><td class="c_left">備　　考:</td><td class="c_right">' . $mryoukin['Mryoukin']['remearks'];
				echo "</td></tr>";
				}
	}	
}

	if($mryoukin['Mryoukin']['pending_flg'] == 0) {
		echo '<tr><td class="c_left">注意事項:</td><td class="c_right">' . '申込のタイミングによっては、受付られない場合がございます。その場合には24時間以内にご連絡をいたします。予めご了承ください。';
		echo "</td></tr>";
	}

		
?>			
</table>