<?php
	define("USER_DB", 'heartf_school2');
	define("USER_ID", 'heartf_2');
	define("USER_PASS", '181818');
	define("USER_CHATSET", 'utf8');
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
					$output = Recommended($setw);
					break;
		}

		return($output);
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



	function Recommended($shop = NULL) {
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

		case 1:
			$wk .='<iframe src="https://calendar.google.com/calendar/embed?title=%E5%8D%A0%E3%81%84%E3%81%AE%E5%AD%A6%E6%A0%A1%E3%83%8F%E3%83%BC%E3%83%88%E3%83%95%E3%83%AB%E3%82%B9%E3%82%AF%E3%83%BC%E3%83%AB&amp;showDate=0&amp;showTz=0&amp;mode=AGENDA&amp;height=600&amp;wkst=1&amp;bgcolor=%23FFFFFF&amp;
	src=l6um9mrqv8cq64r2senhsbl9a8%40group.calendar.google.com&amp;color=%235229A3&amp;src=g5dieeslh6l882g4633991unmk%40group.calendar.google.com&amp;color=%230D7813&amp;ctz=Asia%2FTokyo" style="border-width:0" width="100%" height="380" frameborder="0" scrolling="no"></iframe>';
			break;
		case 2:
			$wk .= '<iframe src="https://calendar.google.com/calendar/embed?showTz=0&amp;mode=AGENDA&amp;height=600&amp;wkst=1&amp;bgcolor=%23FFFFFF&amp;src=l6um9mrqv8cq64r2senhsbl9a8%40group.calendar.google.com&amp;color=%235229A3&amp;ctz=Asia%2FTokyo"
			style="border-width:0" width="100%" height="380" frameborder="0" scrolling="no"></iframe>';
			break;
		case 3:
			$wk .= '<iframe src="https://calendar.google.com/calendar/embed?showTz=0&amp;mode=AGENDA&amp;height=600&amp;wkst=1&amp;bgcolor=%23FFFFFF&amp;src=g5dieeslh6l882g4633991unmk%40group.calendar.google.com&amp;color=%230D7813&amp;ctz=Asia%2FTokyo"
			style="border-width:0" width="100%" height="380" frameborder="0" scrolling="no"></iframe>';
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
	$wk .= '<p><a href="https://school.heartf.com/index.php?%E7%84%A1%E6%96%99%E8%A6%8B%E5%AD%A6%E7%94%B3%E8%BE%BC%E3%81%BF%E3%83%95%E3%82%A9%E3%83%BC%E3%83%A0" title="無料見学申込みフォーム"><img src="swfu/d/btn_form_off.jpg" alt="無料見学フォームへ" title="無料見学フォームへ"   style="max-width:100%;"  class=""></a></p>';
	$wk .= '</div>';
	}
	return($wk);
	}


	function user_voice($ans, $mryouin_id = NULL) {

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




$SQL = <<<EOD
		SELECT  Evoice.nicname as niename, .Msex.kbn as sexkbn, Mnendai.name  as nendai, Mryoukin.name as kouzaname, Evoice.answer as answer,Evoice.impressions as impressions
			 FROM evoices as Evoice, msex as Msex, mnendais as Mnendai, mryoukins as Mryoukin
			 WHERE Evoice.msex_id = Msex.id AND Evoice.mnendai_id = Mnendai.id AND Evoice.mryoukin_id = Mryoukin.id
EOD;

//
		if(!is_null($mryouin_id))
			$SQL .= "AND Evoice.mryoukin_id = " . $mryouin_id;
		$SQL .= " ORDER BY Evoice.modified  DESC;";

		//DB接続
		$link = mysqli_connect("mysql10086.xserver.jp", USER_ID, USER_PASS);
		mysqli_set_charset(USER_CHATSET, $link );
   		mysqli_select_db(USER_DB, $link );

		$data = mysqli_query($SQL, $link);
var_dump($data);

			while ($evoice = mysqli_fetch_assoc($data)) {

				$output .= 	$output1. $evoice['kouzaname'];
				$output .= 	$output2. $evoice['niename'] . ' 【' .  $evoice['nendai'] . "　" . $evoice['sexkbn'] . '】';
				$output .= 	$output3. $evoice['impressions'] . $output4;
			}
//		$output .= 	$outputF;
		mysqli_close($link);
		return($output);
	}




?>
