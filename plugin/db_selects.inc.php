<?php
define("USER_DB", 'heartf_school2');
define("USER_ID", 'heartf_2');
define("USER_PASS", '181818');
define("USER_CHATSET", 'utf8');
define("SEVER", 'mysql10086.xserver.jp');

define("TAX", 1.1);
//define("SEVER", 'mysql10086.xserver.jp');

//	define("USER_IMG", 'https://uranai.heartf.com/Public/img/');
//	define("USER_URL", 'https://uranai.heartf.com/Public/');


	function plugin_db_selects_convert($pref, $setw = NULL, $mryouin_id = NULL,$tel = NULL) {

		switch ($pref){
			//setw には　回答表示するかを持っている
			case 'user_voice':
				$output = user_voice($setw, $mryouin_id);
				break;
			case 'cal_map':
				$output = cal_map($setw);
				break;
			case 'cal_tel':
				$output = cal_tel($setw,$mryouin_id);
				break;
			case 'incidental':
				$output = incidental($setw);
				break;
			case 'Recommended':
					$output = Recommended($setw, $mryouin_id);
					break;
			case 'Ryoukin':
					$output = Ryoukin($setw);
					break;

		}

		return($output);
	}

	function Ryoukin($mcoursename_id = null,　$btn = NULL) {

		//DB接続
		//データベースの接続と選択
		$mysqli = new mysqli("mysql10086.xserver.jp", USER_ID, USER_PASS, USER_DB);
		if ($mysqli->connect_error) {
		    error_log($mysqli->connect_error);
		    exit;
		}
		$sql = 'SELECT Vryoukin.id FROM vryoukins as Vryoukin WHERE Vryoukin.mcoursename_id = ' . $mcoursename_id;
		//var_dump($sql);exit;

		//SQLを実行
		$res = $mysqli->query($sql);
		if (!$res) {
		    error_log($mysqli->error);
		    exit;
		}

		//var_dump($res);exit;
		//$num_rows = $res->num_rows;
		//結果の出力
		$Vryoukin = $res->fetch_assoc();

		$sql = 'SELECT Mryoukin.* FROM mryoukins as Mryoukin WHERE Mryoukin.id = ' . $Vryoukin["id"];
		$Mryoukins = $mysqli->query($sql);

		$sql = 'SELECT COUNT( Msubscription.id ) as cnt , Eschedule.*';
		$sql .= ' FROM eschedules AS Eschedule';
		$sql .= ' LEFT JOIN vsubscriptions AS Msubscription ON Eschedule.id = Msubscription.eschedule_id';
		$sql .= ' WHERE Eschedule.enddate + interval 1 day > NOW( ) ';
		$sql .= ' AND Eschedule.mryoukin_id = ' . $Vryoukin["id"];
		$sql .= ' GROUP BY Eschedule.id';
		$sql .= ' ORDER BY Eschedule.date1';

		$rows = $mysqli->query($sql);

	//var_dump($sql);exit;
//View

		$mryoukin['Mryoukin'] = $Mryoukins->fetch_assoc();
	//	var_dump($mryoukin);exit;

		$wk = NULL;
		if($btn) {

			$wk = '<div class="kouza_99">';
			$wk .=  $this->Html->link(' ', array('controller' =>  'Msubscriptions' , 'action' => 'selectkoza', $mryoukin['Mryoukin']['id'], $row['Eschedule']['id']), array( 'target' => '_top' ) );
			return($wk);

		}else{

			$wk .= '<a href="/Public/Msubscriptions/selectkoza/'. $mryoukin['Mryoukin']['id'].'" target="_top"> </a>';
			
		}

		$week = array("日", "月", "火", "水", "木", "金", "土","日夜", "月夜", "火夜", "水夜", "木夜", "金夜", "土夜");
		//$wk .= '<div class="syosai_content">';
		$wk .= '<div class="area_msg">';
		$wk .= "<h3>" . $mryoukin['Mryoukin']['name'] . "</h3>";
		$wk .= '<table width="98%" border="0" cellspacing="1" class="contact_form2">';
		$wk .= '<tr><td class="c_left">講座時間：</td><td class="c_right">' . $mryoukin['Mryoukin']['period'] . '</td></tr>';
		$wk .= '<tr><td class="c_left">受講料金：</td><td class="c_right">' . h(number_format($mryoukin['Mryoukin']['kng'] * TAX)) . '円(内税）';

		if(is_null($mryoukin['Mryoukin']['opday'])) {
				if(!$rows) {
					$wk .= 	'<tr><td class="c_left"></td><td class="c_right">' ;
					$wk .=  '<div class="kouza_inquiry">';
					$wk .=  '<a href="/index.php?Contact" "target="_top"></a>' ;
					$wk .=  "</div></td></tr>";
				} else {
					while ($row['Eschedule'] = $rows->fetch_assoc()) {
//var_dump($row['Eschedule']);exit;
						if(empty($row['Eschedule']['jikan']))
							$jikan = $mryoukin['Mryoukin']['optime'];
						else $jikan = $row['Eschedule']['jikan'];

						if($row['Eschedule']['capacity'] == 0)
							$capacity = $mryoukin['Mryoukin']['capacity'];
						else $capacity = $row['Eschedule']['capacity'];

						$jikan = "(" . $jikan . ")";
						$wk .= '<tr><td class="c_left">開催日時:</td>';
					//	$datetime =  $row['Eschedule']['date1'];
						$w = date('w', strtotime($row['Eschedule']['date1']));
						$date = '定　員：' . $capacity . '人</br>';
						$date .= ' 1日： ' . $row['Eschedule']['date1'] . '('. $week[$w]. ') ' . $jikan;
//	var_dump($deadline->format('Y-m-d'));

	//var_dump($capacity);
	//var_dump( "count =" . $row['Eschedule']['cnt']);
					for($ix=2; $ix <= 9; $ix++) {
						if(empty($row['Eschedule']['date'.$ix]))
							break;
						$w = date('w',  strtotime($row['Eschedule']['date'.$ix]));
						$date .= "</br> " .$ix ."日： " . $row['Eschedule']['date'.$ix]  . '('. $week[$w]. ') ' . $jikan;
					}

					$deadline = new DateTime($row['Eschedule']['deadline']);

					$w = $deadline->modify('-1 days')->format('w');


					$date .= " </br> 申込締切 "  . $deadline->format('Y-m-d')  . '('. $week[$w]. ') ';//    2016-01-25
						$wk .=  '<td class="c_right">' . $date;

						if($deadline->format('Y-m-d')  >= date("Y-m-d")) {
							if( $capacity <= $row['Eschedule']['cnt']){
								$wk .=  '  定員に達しました。締め切りました。 </td></tr>';
							}else {
								$zan = $capacity - $row['Eschedule']['cnt'];
		//var_dump($row[0]['cnt'] . '   '. $capacity);
								if($zan <=  2) $wk .=  '</br> 残席　' . $zan .'席お早めに</br>';
								$wk .=  '<div class="kouza_99">';
								$wk .=  '<a href="/Public/Msubscriptions/selectkoza/' . $row['Eschedule']['mryoukin_id'] . '/' . $row['Eschedule']['id']. '"target="_top"></a>' ;
								$wk .=  "</div></td></tr>";

							}
						} else if($row['Eschedule']['date1'] > date("Y-m-d")) {
									if( $capacity > $row['Eschedule']['cnt']){
										$wk .=  '<div class="kouza_inquiry">';
										$wk .=  '<a href="/index.php?Contact" "target="_top"></a>' ;
										$wk .=  "</div></td></tr>";
									} else $wk .=  '  定員に達しました。締め切りました。 </td></tr>';
							} else {
								//echo "</br> <center>" . $this->Html->image("kouza_kaisai.jpg") . "</center>";
								$wk .=  "</br> <center>好 評 開 催 中</center>";
								$wk .=  "</td></tr>";
							}
						unset($deadline);

					}

					if(!is_null($mryoukin['Mryoukin']['remearks'])) {
						$wk .= '<tr><td class="c_left">備　　考:</td><td class="c_right">' . $mryoukin['Mryoukin']['remearks'];
						$wk .= "</td></tr>";
					}

					if(!empty($mryoukin['Mryoukin']['prerequisite'])) {
						$wk .= '<tr><td class="c_left">受講条件:</td><td class="c_right">' . $mryoukin['Mryoukin']['prerequisite'];
						$wk .=  "</td></tr>";
					}
				}
				//お仕事コース
			} else {
//var_dump($mryoukin['Mryoukin']); exit;
				$wk .=  '<tr><td class="c_left">開催日時:</td><td class="c_right">' . $mryoukin['Mryoukin']['opday'] . '</br>' . $mryoukin['Mryoukin']['optime'];
				if($mryoukin['Mryoukin']['interruption_flg'] == 1)
					$wk .=  'ただいま申し込みができません。</td></tr>';
				else {
					//if(($Mryoukin['Mryoukin']['anytime_flg']  == FALSE)) {
	 			if(($mryoukin['Mryoukin']['anytime_flg']) AND ($mryoukin['Mryoukin']['pending_flg'] == FALSE)) {
					$wk .=  '<h3>曜日ボタンを押してお申し込みいただけます</h3>';
					$nowdate = new DateTime();
					$enddate = new DateTime('2021-02-28');
					$diff = $nowdate->diff($enddate);

					//if(isset($mryoukin['Mryoukin'])){
						for($ix = 0; $ix < 14; $ix++) {
							$wk1 = "day" . sprintf('%x', $ix);
//							var_dump($mryoukin['Mryoukin'][$wk1]);
							if($mryoukin['Mryoukin'][$wk1] > 0) {
								$wk .=  '<div class="kouza_99' . sprintf('%x', $ix) . '">';

								$wk .=  '<a href="/Public/Msubscriptions/selectkoza/' . $mryoukin['Mryoukin']['id'] . '/0/' . $ix. '/"target="_top"></a>' ;
								//$wk .=  $this->Html->link(' ', array('controller' =>  'Msubscriptions', 'action' => 'selectkoza', $mryoukin['Mryoukin']['id'], 0, $ix), array( 'target' => '_top' ));
						$wk .=  "<span>(募集人数". $mryoukin['Mryoukin'][$wk1] . "人)</span></div>";

							} else if($mryoukin['Mryoukin'][$wk1] < 0) {
									$wk .=  '<div class="kouza_99' . sprintf('%x', $ix)  . '2"> ';
									$wk .=  '<a href="/Public/Msubscriptions/selectkoza/' . $row['Eschedule']['mryoukin_id'] . '/0/' . $ix. '2/"target="_top"></a>' ;

									//$wk .=  $this->Html->link(' ', array('controller' =>  'Msubscriptions', 'action' => 'selectkoza', $mryoukin['Mryoukin']['id'], 0, $ix, 2), array( 'target' => '_top' ));

									$wk .=  "</div>";
									//if(!isset($kuuseki[$ix])) $kuuseki[$ix] = 0;
									//echo "<div class='center_0'>(現在空席待ち". $kuuseki[$ix] . "人)</div>";
									//$wk .=  "<div class='center_0'>　</div>";

									}
							}
							$wk .=  "</br>";

					} else {
						$wk .= '<div class="kouza_99">';
						$wk .=  '<a href="/Public/Msubscriptions/selectkoza/' . $Vryoukin["id"] . '/"target="_top"></a>' ;
						$wk .= "</div>";
					}

				$wk .=  "</td></tr>";
				if(!is_null($mryoukin['Mryoukin']['remearks'])) {
					$wk .=  '<tr><td class="c_left">備　　考:</td><td class="c_right">' . $mryoukin['Mryoukin']['remearks'];
					$wk .= "</td></tr>";
				}
			}

		}

	if($mryoukin['Mryoukin']['pending_flg'] == 0) {
		$wk .=  '<tr><td class="c_left">注意事項:</td><td class="c_right">' . '申込のタイミングによっては、受付られない場合がございます。
その場合には24時間以内にご連絡をいたします。予めご了承ください。';
		$wk .=  "</td></tr>";
	}
	$wk .=  '</table>';
	$wk .=  '</div>';
	return($wk);


	}

	function incidental($mryoukin = NULL) {

		$output = $saijyuko = '';

		$output .= "<h4>付帯講座</h4>「心理タロット講座」「手相占い講座基礎」選択命占講座（「西洋占星術講座基礎」もしくは「数秘術基礎講座」）「 人気占い師育成講座 」「店舗実践講座」";
		$saijyuko .= "<h4>再受講無料講座</h4>心理タロット講座";
		if($mryoukin > 11) {
			$output .= "「手相占い講座応用」「占い集客講座」";
			$saijyuko .= " 「手相占い講座基礎」選択命占講座（「西洋占星術講座基礎」もしくは「数秘術基礎講座」） 「手相占い講座応用」 ";
		}

		if($mryoukin > 13)
			$output .= "「手相占い実践講座」、選択命占講座（「西洋占星術実践講座」もしくは「数秘講座実践講座」）、「占いコンサル」";
			$saijyuko .= "「手相占い実践講座」、選択命占講座（「西洋占星術実践講座」もしくは「数秘講座実践講座」）";

		$output .= "命占、相占、卜占全ての占術を学べます。";
		$output .= "</BR>" . $saijyuko;
		return ($output);
	}



	function Recommended($kouza = NULL,$kbn = NULL) {
		if(is_null($kbn))
			$wk = "<h2>オススメ講座のご紹介</h2>";
		else $wk = "<h2>付帯講座のご紹介</h2>";

	//	$wk .= '<ul class="list_c3">';

		if($kouza != 11) {
			$wk .= '<div class="osusumekouza_box">';
			$wk .= '<a href="./index.php?FortuneTellingCourse#top">';
			$wk .= '<img src="swfu/d/osusume_thumb3.jpg" alt="占い師お仕事講座" title="占い師お仕事講座" style="max-width:100%;" class=""></a></br>';
			$wk .= '<a href="./index.php?FortuneTellingCourse#top"　title="FortuneTellingCourse">占い師お仕事講座</a></p></div>';
			//$wk .= '<div class="topic_osigoto"></div>';
			//$wk .= '<div class="box_osigoto">';

			//$wk .= '<h4>占い師お仕事講座</h4>目的をもって学べて、占い知識や技術ノウハウを身に着けて本気で占い師を目指せます。';
			//$wk .= '</div></a></li>';
		}

		if($kouza == 11) {
			$wk .= '<div class="osusumekouza_box">';
			$wk .= '<a href="./index.php?PsychologyTarot#top">';
			$wk .= '<img src="swfu/d/osusume_thumb1.jpg" alt="心理タロット講座" title="心理タロット講座" style="max-width:100%;" class=""></a></br>';
			$wk .= '<a href="./index.php?PsychologyTarot#top"　title="PsychologyTarot">心理タロット講座</a></p></div>';
/*
			$wk .= '<li class="list_c3_box1">';
			//$wk .= '<li class="osusumekouza_box">';
			$wk .= '<a href="./index.php?PsychologyTarot#top">';
			$wk .= '<div class="topic_tarotto"></div>';
			$wk .= '<div class="box_tarotto">';
			$wk .= '<h4>心理タロット講座</h4>タロットと心理スキルを同時に学ぶ事で、より高レベルの占いができるようになります。';
			$wk .= '</div></a></li>';
*/
		}


		if($kouza != 451) {
				$wk .= '<div class="osusumekouza_box">';
				$wk .= '<a href="./index.php?Palmistry#top">';
				$wk .= '<img src="swfu/d/osusume_thumb2.jpg" alt="手相占い講座" title="手相占い講座" style="max-width:100%;" class=""></a></br>';
				$wk .= '<a href="./index.php?Palmistry#top"　title="手相占い講座">手相占い講座</a></p></div>';
/*			}

			$wk .= '<li class="list_c3_box1">';
			$wk .= '<a href="./index.php?手相占い講座#top">';
			$wk .= '<div class="topic_tesou"></div>';
			$wk .= '<div class="box_tesou">';
			$wk .= '<h4>手相占い講座</h4>手相にタロットと占星術を組み合わせて実践的な手相占いを学習していきます。';
			$wk .= '</div></a></li>';
*/
		}

		if($kouza != 411) {
			$wk .= '<div class="osusumekouza_box">';
			$wk .= '<a href="./index.php?WesternAstrology#top">';
			$wk .= '<img src="swfu/d/osusume_thumb6.jpg" alt="西洋占星術講座" title="西洋占星術講座" style="max-width:100%;" class=""></a></br>';
			$wk .= '<a href="./index.php?WesternAstrology#top"　title="西洋占星術講座">西洋占星術講座</a></p></div>';
/*
			$wk .= '<li class="list_c3_box1">';
			$wk .= '<a href="./index.php?西洋占星術講座#top">';
			$wk .= '<div class="topic_star"></div>';
			$wk .= '<div class="box_star">';
			$wk .= '<h4>西洋占星術講座</h4>実践的な西洋占星術を徹底解説。覚えやすく使いやすい内容に加え、占術の知識が豊富な人気講座です。';
			$wk .= '</div></a></li>';
			*/
		}

		if($kouza != 701) {
			$wk .= '<div class="osusumekouza_box">';
			$wk .= '<a href="./index.php?Numerology#top">';
			$wk .= '<img src="swfu/d/osusume_thumb7.jpg" alt="数秘術講座" title="数秘術講座" style="max-width:100%;" class=""></a></br>';
			$wk .= '<a href="./index.php?Numerology#top"　title="数秘術講座">数秘術講座</a></p></div>';

	/*
			$wk .= '<li class="list_c3_box1">';
			$wk .= '<a href="./index.php?Numerology#top">';
			$wk .= '<div class="topic_Numerology"></div>';
			$wk .= '<div class="box_Numerology">';

			$wk .= '<h4>数秘術講座</h4>数秘術は主に「生年月日」から占います。数字そのものに宿っているルールに従って計算をして占います。';
			$wk .= '</div></a></li>';
*/
		}
	//	$wk .= '</ul>';

		return ($wk);
	}

	function cal_map($shop = NULL) {
	// 何かしらの処理 1だったら白山、NULLだったら春日
	$wk = '<div>';
	if($shop == 1)
		$wk .= '<iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2021.0594208087894!2d139.75114145884143!3d35.71965691364731!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x60188d7abb52a91d%3A0x43a0c6de37417954!2z44OP44O844OI44OV44Or44K544Kv44O844OrIOeZveWxseagoQ!5e0!3m2!1sja!2sjp!4v1552053915438"  width="100%" height="400" frameborder="0" style="border:0" allowfullscreen></iframe>';
	else $wk .= '<iframe src="https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d6478.946484742062!2d139.75455719061998!3d35.714578161773346!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x1f7d73279f3d13b7!2z5p2x5Lqs44Gu5Y2g44GE5pWZ5a6k44OP44O844OI44OV44Or44K544Kv44O844OrIOePvuW9ueODl-ODreWNoOOBhOW4q-OBi-OCieWwgumWgOeahOOBq-e_kuOBiOOCi-WtpuagoQ!5e0!3m2!1sja!2sjp!4v1549273475903" width="100%" height="400" frameborder="0" style="border:0" allowfullscreen></iframe>';

	$wk .= '</div>';

	return ($wk);
	}

	function cal_tel($cal = NULL, $sizewk = NULL, $tel = NULL) {
	// 何かしらの処理
	$wk = NULL;

	//$cal が１なら両方だす
	switch ($cal) {

		case 1: // 教室
			$wk .= '<p>Googleカレンダーが<span style="color:red">表示</span>されない場合は</br><a href="https://calendar.google.com/calendar/embed?title=%E5%8D%A0%E3%81%84%E3%81%AE%E5%AD%A6%E6%A0%A1%E3%83%8F%E3%83%BC%E3%83%88%E3%83%95%E3%83%AB%E3%82%B9%E3%82%AF%E3%83%BC%E3%83%AB&amp;showDate=0&amp;showTz=0&amp;mode=AGENDA&amp;height=600&amp;wkst=1&amp;bgcolor=%23FFFFFF&amp;
	src=l6um9mrqv8cq64r2senhsbl9a8%40group.calendar.google.com&amp;color=%235229A3&amp;
	src=g5dieeslh6l882g4633991unmk%40group.calendar.google.com&amp;color=%230D7813&amp;
	src=i5m6huuudo6d1ll8m1hqtnsmv4@group.calendar.google.com&amp;color=%230D7813&amp;
	 ctz=Asia%2FTokyo target="_blank">ここをクリック</a></p>' ;
			$wk .='<iframe src="https://calendar.google.com/calendar/embed?title=%E5%8D%A0%E3%81%84%E3%81%AE%E5%AD%A6%E6%A0%A1%E3%83%8F%E3%83%BC%E3%83%88%E3%83%95%E3%83%AB%E3%82%B9%E3%82%AF%E3%83%BC%E3%83%AB&amp;showDate=0&amp;showTz=0&amp;mode=AGENDA&amp;height=600&amp;wkst=1&amp;bgcolor=%23FFFFFF&amp;
	src=l6um9mrqv8cq64r2senhsbl9a8%40group.calendar.google.com&amp;color=%235229A3&amp;
	src=g5dieeslh6l882g4633991unmk%40group.calendar.google.com&amp;color=%230D7813&amp;
	src=i5m6huuudo6d1ll8m1hqtnsmv4@group.calendar.google.com&amp;color=%230D7813&amp;
	 ctz=Asia%2FTokyo"style="border-width:0" width="100%" height="380" frameborder="0" scrolling="no"></iframe>';
			break;
		case 2:	//白山
			$wk .= '<p>Googleカレンダーが<span style="color:red">表示</span>されない場合は</br><a href="https://calendar.google.com/calendar/embed?showTz=0&amp;mode=AGENDA&amp;height=600&amp;wkst=1&amp;bgcolor=%23FFFFFF&amp;
			src=l6um9mrqv8cq64r2senhsbl9a8%40group.calendar.google.com&amp;color=%235229A3&amp;
			ctz=Asia%2FTokyo target="_blank">ここをクリック</a></p>' ;
			$wk .= '<iframe src="https://calendar.google.com/calendar/embed?showTz=0&amp;mode=AGENDA&amp;height=600&amp;wkst=1&amp;bgcolor=%23FFFFFF&amp;
			src=l6um9mrqv8cq64r2senhsbl9a8%40group.calendar.google.com&amp;color=%235229A3&amp;
			ctz=Asia%2FTokyo"style="border-width:0" width="100%" height="500" frameborder="0" scrolling="no"></iframe>';
			break;
		case 3:	//STUDIO
					$wk .= '<p>Googleカレンダーが<span style="color:red">表示</span>されない場合は</br><a href="https://calendar.google.com/calendar/embed?showTz=0&amp;mode=AGENDA&amp;wkst=1&amp;bgcolor=%23FFFFFF&amp;
							src=g5dieeslh6l882g4633991unmk%40group.calendar.google.com&amp;color=%230D7813&amp;
							src=i5m6huuudo6d1ll8m1hqtnsmv4@group.calendar.google.com&amp;color=%230D7813&amp;
							ctz=Asia%2FTokyo target="_blank">ここをクリック</a></p>' ;
		$wk .= '<iframe src="https://calendar.google.com/calendar/embed?showTz=0&amp;mode=AGENDA&amp;wkst=1&amp;bgcolor=%23FFFFFF&amp;
				src=g5dieeslh6l882g4633991unmk%40group.calendar.google.com&amp;color=%230D7813&amp;
				src=i5m6huuudo6d1ll8m1hqtnsmv4@group.calendar.google.com&amp;color=%230D7813&amp;
				ctz=Asia%2FTokyo"style="border-width:0" width="100%" height="500" frameborder="0" scrolling="no"></iframe>';

			break;
		case 4: //ZOOM
			$wk .= '<p>Googleカレンダーが<span style="color:red">表示</span>されない場合は</br><a href="https://calendar.google.com/calendar/embed?showTz=0&amp;mode=AGENDA&amp;height=600&amp;wkst=1&amp;bgcolor=%23FFFFFF&amp;
				src=5kmbj7ifu41od1b9hoj044lrn4@group.calendar.google.com&amp;color=%230D7813&amp;
				src=i5m6huuudo6d1ll8m1hqtnsmv4@group.calendar.google.com&amp;color=%230D7813&amp;
							ctz=Asia%2FTokyo target="_blank">ここをクリック</a></p>';
			$wk .= ' <iframe src="https://calendar.google.com/calendar/embed?showTz=0&amp;mode=AGENDA&amp;title=ZOOM&amp;wkst=1&amp;bgcolor=%23FFFFFF&amp;
			src=aTVtNmh1dXVkbzZkMWxsOG0xaHF0bnNtdjRAZ3JvdXAuY2FsZW5kYXIuZ29vZ2xlLmNvbQ&
			src=bDZ1bTltcnF2OGNxNjRyMnNlbmhzYmw5YThAZ3JvdXAuY2FsZW5kYXIuZ29vZ2xlLmNvbQ&
			src=amEuamFwYW5lc2UjaG9saWRheUBncm91cC52LmNhbGVuZGFyLmdvb2dsZS5jb20&color=%233F51B5&color=%238E24AA&color=%23D50000" 
			ctz=Asia%2FTokyo"style="border-width:0" width="100%" height="500" frameborder="0" scrolling="no"></iframe>';

				break;
		case 5: //心理タロット
			$wk .= '<p>Googleカレンダーが<span style="color:red">表示</span>されない場合は<a href="https://calendar.google.com/calendar/embed?showTz=0&amp;mode=AGENDA&amp;wkst=1&amp;bgcolor=%23FFFFFF&amp;
				src=i5m6huuudo6d1ll8m1hqtnsmv4@group.calendar.google.com&amp;color=%230D7813&amp;
				ctz=Asia%2FTokyo target="_blank">ここをクリック</a></p>';
			$wk .= ' <iframe src="https://calendar.google.com/calendar/embed?showTz=0&amp;mode=AGENDA&amp;&amp;wkst=1&amp;bgcolor=%23FFFFFF&amp;
				src=i5m6huuudo6d1ll8m1hqtnsmv4@group.calendar.google.com&amp;color=%230D7813&amp;
				ctz=Asia%2FTokyo"style="border-width:0" width="100%" height="500" frameborder="0" scrolling="no"></iframe>';
		break;
		case 6: // すべて
			$wk .= '<p>Googleカレンダーが<span style="color:red">表示</span>されない場合は</br><a href="https://calendar.google.com/calendar/embed?title=%E5%8D%A0%E3%81%84%E3%81%AE%E5%AD%A6%E6%A0%A1%E3%83%8F%E3%83%BC%E3%83%88%E3%83%95%E3%83%AB%E3%82%B9%E3%82%AF%E3%83%BC%E3%83%AB&amp;showDate=0&amp;showTz=0&amp;mode=AGENDA&amp;height=600&amp;wkst=1&amp;bgcolor=%23FFFFFF&amp;
	src=l6um9mrqv8cq64r2senhsbl9a8%40group.calendar.google.com&amp;color=%235229A3&amp;
	src=5kmbj7ifu41od1b9hoj044lrn4@group.calendar.google.com&amp;color=%230D7813&amp;
	src=g5dieeslh6l882g4633991unmk%40group.calendar.google.com&amp;color=%230D7813&amp;
	src=i5m6huuudo6d1ll8m1hqtnsmv4@group.calendar.google.com&amp;color=%230D7813&amp;
			ctz=Asia%2FTokyo target="_blank">ここをクリック</a></p>';
			$wk .='<iframe src="https://calendar.google.com/calendar/embed?title=%E5%8D%A0%E3%81%84%E3%81%AE%E5%AD%A6%E6%A0%A1%E3%83%8F%E3%83%BC%E3%83%88%E3%83%95%E3%83%AB%E3%82%B9%E3%82%AF%E3%83%BC%E3%83%AB&amp;showDate=0&amp;showTz=0&amp;mode=AGENDA&amp;height=600&amp;wkst=1&amp;bgcolor=%23FFFFFF&amp;
	src=l6um9mrqv8cq64r2senhsbl9a8%40group.calendar.google.com&amp;color=%235229A3&amp;
	src=5kmbj7ifu41od1b9hoj044lrn4@group.calendar.google.com&amp;color=%230D7813&amp;
	src=g5dieeslh6l882g4633991unmk%40group.calendar.google.com&amp;color=%230D7813&amp;
	src=i5m6huuudo6d1ll8m1hqtnsmv4@group.calendar.google.com&amp;color=%230D7813&amp;
	ctz=Asia%2FTokyo" style="border-width:0" width="100%" height="380" frameborder="0" scrolling="no"></iframe>';
			break;
		case 7: // すべて
				$wk .= '<p>Googleカレンダーが<span style="color:red">表示</span>されない場合は</br><a href="https://calendar.google.com/calendar/embed?showTz=0&amp;mode=AGENDA&amp;height=600&amp;wkst=1&amp;bgcolor=%23FFFFFF&amp;
			src=0ip8ut0bd2a9j2gaqb6pn8b66o@group.calendar.google.com&amp;color=%23D50000&amp;
							ctz=Asia%2FTokyo target="_blank">ここをクリック</a></p>';
				$wk .= '<iframe src="https://calendar.google.com/calendar/embed?showTz=0&amp;mode=AGENDA&amp;height=600&amp;wkst=1&amp;bgcolor=%23FFFFFF&amp;
			src=0ip8ut0bd2a9j2gaqb6pn8b66o@group.calendar.google.com&amp;color=%23D50000&amp;
			ctz=Asia%2FTokyo"style="border-width:0" width="100%" height="380" frameborder="0" scrolling="no"></iframe>';
			break;
	}

	/*
	if(($cal)) {
	$wk .= '<iframe src="https://calendar.google.com/calendar/embed?showTz=0&amp;mode=AGENDA&amp;height=600&amp;wkst=1&amp;bgcolor=%23FFFFFF&amp;src=l6um9mrqv8cq64r2senhsbl9a8%40group.calendar.google.com&amp;color=%232F6309&amp;ctz=Asia%2FTokyo
	 style="border-width:0" width="100%" height="380" frameborder="0" scrolling="no"></iframe>';
	}
	*/
	if($tel==1) {
	$wk .= '<div class="contact_bg_tel_' . $sizewk . '">';
	//$wk .= '<p><a href="https://school.heartf.com/index.php?%5B%5Btel%3A0120554144%5D%5D" title="tel:0120554144">0120-554-144</a></p>';
	$wk .= '<p><a href="tel:0368741288">03-6874-1288</a></p>';
	$wk .= '</div>';
	} else if(($sizewk == 'a') OR ($sizewk == 'b')) {

	$wk .= '<div class="contact_area">';
	$wk .= '<div class="contact_bg_' . $sizewk . '">';
	$wk .= '<p><span style="color:red;background-color:inherit;"  class="qhm-deco"><strong>無料電話相談</strong></span>も受け付けております。';
	$wk .= 'お気軽にお電話ください。</BR>都合により日時指定の折り返しのお電話になる場合がござます。予めご了承ください。</p>';
	$wk .= '</div>';
	$wk .= '<div class="contact_bg_tel_' . $sizewk . '">';
	//$wk .= '<p><a href="https://school.heartf.com/index.php?%5B%5Btel%3A0120554144%5D%5D" title="tel:0120554144">0120-554-144</a></p>';
	$wk .= '<p><a href="tel:0368741288">03-6874-1288</a></p>';
	$wk .= '</div>';
	$wk .= '<p><a href="https://school.heartf.com/index.php?InformationSession#top" title="無料説明会申込みフォーム"><img src="swfu/d/btn_form_off.jpg" alt="無料説明会申込フォームへ" title="無料説明会申込フォームへ"   style="max-width:100%;"  class=""></a></p>';
	$wk .= '</div>';
	}
	return($wk);
	}


	function user_voice($ans, $mcoursename_id = NULL) {

		$output = NULL;


   $output1 = <<<EOD
   <div class="kansou_bg">
      <div class="kansou_area">
      <div class="kansou_txt">
        <div class="kansou_box">
		        <div class="kansou_title1">講座名</div>
EOD;

$output2 = <<<EOD
        </div>
        <div class="kansou_box">
        <div class="kansou_title1">お名前</div>
EOD;


$output3 = <<<EOD
        </div>
        <div class="kansou_topic">
          <p>
EOD;

$output4 = <<<EOD
</p>
        </div>

      </div>
      </div>
    </div>
EOD;




$sql = <<<EOD
		SELECT  Evoice.nicname as niename, .Msex.kbn as sexkbn, Mnendai.name  as nendai, Mcoursename.vname as kouzaname, Evoice.answer as answer,Evoice.impressions as impressions
			 FROM evoices as Evoice, msex as Msex, mnendais as Mnendai, mcoursenames as Mcoursename
			 WHERE Evoice.msex_id = Msex.id AND Evoice.mnendai_id = Mnendai.id AND Evoice.mcoursename_id = Mcoursename.id
EOD;

//
		if(!is_null($mcoursename_id))
			$sql .= " AND Evoice.mcoursename_id = " . $mcoursename_id;
		$sql .= " ORDER BY Evoice.modified  DESC;";

		//DB接続
		$mysqli = new mysqli("mysql10086.xserver.jp", USER_ID, USER_PASS, USER_DB);
		if ($mysqli->connect_error) {
		    error_log($mysqli->connect_error);
		    exit;
		}
//var_dump($sql);
		$res = $mysqli->query($sql);
		if (!$res) {
			error_log($mysqli->error);
			exit;
		}

//	var_dump($res);exit;
//$num_rows = $res->num_rows;
//結果の出力
		while ($evoice = $res->fetch_assoc()){
//var_dump($evoice);exit;
		//while ($evoice = mysqli_fetch_assoc($data)) {

			$output .= 	$output1. $evoice['kouzaname'];
			$output .= 	$output2. $evoice['niename'] . ' 【' .  $evoice['nendai'] . "　" . $evoice['sexkbn'] . '】';
			$output .= 	$output3. $evoice['impressions'] . $output4;
		}
//		$output .= 	$outputF;
		mysqli_close($mysqli);
	//	mysqli_close($link);
		return($output);
	}



?>
