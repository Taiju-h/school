

<?php 	if($btn) {
			echo '<div class="kouza_99">';
			echo $this->Html->link(' ', array('controller' =>  'Msubscriptions' , 'action' => 'selectkoza', $mryoukin['Mryoukin']['id'], $row['Eschedule']['id']), array( 'target' => '_top' ) );
			return;

} else {
	$week = array("日", "月", "火", "水", "木", "金", "土","日夜", "月夜", "火夜", "水夜", "木夜", "金夜", "土夜");
 ?>
<h3> <?php echo $mryoukin['Mryoukin']['name'] ?> </h3>
<table width="98%" border="0" cellspacing="1" class="contact_form">
<tr><td class="c_left">講座時間：</td><td class="c_right"><?php echo $mryoukin['Mryoukin']['period']; ?></td></tr>
<tr><td class="c_left">受講料金：</td><td class="c_right"><?php echo h(number_format($mryoukin['Mryoukin']['kng'] * 1.10)) . '円(内税）';　?>

<?php if(is_null($mryoukin['Mryoukin']['opday'])) {
		if(empty($data)) {
			echo '<tr><td class="c_left"></td><td class="c_right">' ;
			echo '<div class="kouza_inquiry">';
						echo $this->Html->link(' ', FULL_BASE_URL . '/index.php?go=WbHXJN', array( 'target' => '_top' ) );
			echo "</div></td></tr>";
		} else {

			foreach($data as $row) :
				if(empty($row['Eschedule']['jikan']))
					$jikan = $mryoukin['Mryoukin']['optime'];
				else $jikan = $row['Eschedule']['jikan'];
				if($row['Eschedule']['capacity'] == 0)
					$capacity = $mryoukin['Mryoukin']['capacity'];
				else $capacity = $row['Eschedule']['capacity'];

				$jikan = "(" . $jikan . ")";
				echo '<tr><td class="c_left">開催日時:</td>';
			//	$datetime =  $row['Eschedule']['date1'];
				$w = date('w', strtotime($row['Eschedule']['date1']));
				$date = '定　員：' . $capacity . '人</br>';
				$date .= ' 1日： ' . $row['Eschedule']['date1'] . '('. $week[$w]. ') ' . $jikan;
				if(! empty($row['Eschedule']['date2'])) {
				$w = date('w',  strtotime($row['Eschedule']['date2']));

					$date .= "</br> 2日： " . $row['Eschedule']['date2']  . '('. $week[$w]. ') ' . $jikan;
				}
				if(! empty($row['Eschedule']['date3'])) {
				$w = date('w',  strtotime($row['Eschedule']['date3']));

					$date .= "</br> 3日： "  . $row['Eschedule']['date3']  . '('. $week[$w]. ') ' . $jikan;
				}
				$deadline = new DateTime($row['Eschedule']['deadline']);

				$w = $deadline->modify('-1 days')->format('w');



				$date .= " </br> 申込締切 "  . $deadline->format('Y-m-d')  . '('. $week[$w]. ') ';//    2016-01-25
				echo '<td class="c_right">' . $date;
				if($deadline->format('Y-m-d')  >= date("Y-m-d")) {
//var_dump($deadline, $row['Eschedule']['deadline']  ."  " . date("Y-m-d"));
					if( $capacity <= $row[0]['cnt']){
						echo '  定員に達しました。締め切りました。 </td></tr>';
					}else {
						$zan = $capacity - $row[0]['cnt'];
//var_dump($row[0]['cnt'] . '   '. $capacity);
						if($zan <=  2) echo '</br> 残席　' . $zan .'席お早めに</br>';
						echo '<div class="kouza_99">';
						echo $this->Html->link(' ', array('controller' =>  'Msubscriptions' , 'action' => 'selectkoza',  $row['Eschedule']['mryoukin_id'], $row['Eschedule']['id']), array( 'target' => '_top' ) );
						echo "</div></td></tr>";

					}
				} else if($row['Eschedule']['date1'] > date("Y-m-d")) {
							if( $capacity > $row[0]['cnt']){

								echo '<div class="kouza_inquiry">';
								echo $this->Html->link(' ', FULL_BASE_URL . '/index.php?go=WbHXJNarray', array( 'target' => '_top' ) );
								echo "</div></td></tr>";
							} else echo '  定員に達しました。締め切りました。 </td></tr>';
				} else {

						//echo "</br> <center>" . $this->Html->image("kouza_kaisai.jpg") . "</center>";
						echo "</br> <center>好 評 開 催 中</center>";
						echo "</td></tr>";
				}
				unset($deadline);
			endforeach;
		}
		if(!is_null($mryoukin['Mryoukin']['remearks'])) {
			echo '<tr><td class="c_left">備　　考:</td><td class="c_right">' . $mryoukin['Mryoukin']['remearks'];
			echo "</td></tr>";
		}

		if(!empty($mryoukin['Mryoukin']['prerequisite'])) {
			echo '<tr><td class="c_left">受講条件:</td><td class="c_right">' . $mryoukin['Mryoukin']['prerequisite'];
			echo "</td></tr>";
		}

		} else {
			echo '<tr><td class="c_left">開催日時:</td><td class="c_right">' . $mryoukin['Mryoukin']['opday'] . '</br>' . $mryoukin['Mryoukin']['optime'];
			if($mryoukin['Mryoukin']['interruption_flg'] == 1)
					echo 'ただいま申し込みができません。</td></tr>';
			else {
				$nowdate = new DateTime();
				$enddate = new DateTime('2021-02-28');
				$diff = $nowdate->diff($enddate);
		//echo $diff->format('%R%a');

		//var_dump($mryoukin1);
		//echo ($mryoukin1 . '</br>');

				if(isset($mryoukin1)){
					for($ix = 0; $ix < 14; $ix++) {
						$wk = "day" . sprintf('%x', $ix);

		//echo ($wk . $mryoukin1['Mryoukin'][$wk] . '</br>');


					 	if($mryoukin1['Mryoukin'][$wk] > 0) {

							echo '<div class="kouza_99' . sprintf('%x', $ix) . '"> ';
							echo $this->Html->link(' ', array('controller' =>  'Msubscriptions', 'action' => 'selectkoza', $mryoukin['Mryoukin']['id'], 0, $ix), array( 'target' => '_top' ));
							echo "</div>";
								if(($ix == 12) AND ($diff->format('%R%a') > 0))
									echo "<div class='center_0'>" .  $this->Html->image('new012_10.gif', array('alt' => 'New')) . "1月22日開講の新しいコースです。";
							echo "<div class='center_0'>(募集人数". $mryoukin1['Mryoukin'][$wk] . "人)</div>";

						} else if($mryoukin1['Mryoukin'][$wk] < 0) {
							echo '<div class="kouza_99' . sprintf('%x', $ix)  . '2"> ';
							echo $this->Html->link(' ', array('controller' =>  'Msubscriptions', 'action' => 'selectkoza', $mryoukin['Mryoukin']['id'], 0, $ix, 2), array( 'target' => '_top' ));

							echo "</div>";
							//if(!isset($kuuseki[$ix])) $kuuseki[$ix] = 0;
							//echo "<div class='center_0'>(現在空席待ち". $kuuseki[$ix] . "人)</div>";
							echo "<div class='center_0'>　</div>";

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
}

	if($mryoukin['Mryoukin']['pending_flg'] == 0) {
		echo '<tr><td class="c_left">注意事項:</td><td class="c_right">' . '申込のタイミングによっては、受付られない場合がございます。その場合には24時間以内にご連絡をいたします。予めご了承ください。';
		echo "</td></tr>";
	}


?>
</table>
