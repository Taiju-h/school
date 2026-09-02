<?php
App::uses('CakeEmail', 'Network/Email');

class MailComponent extends Component {
	
	public function Reception($Mryoukins) {
		$mail_temp = "このたびは" . $Mryoukins[0]['Mdivision']['name'] .'の講座をお申し込みいただき誠にありがとうございます。' . "\n";
		$mail_temp .= '担当より講座開催につきましての詳細のご案内をいたします。' . "\n\n";
		$mail_temp .= '受信設定をされていらっしゃる場合には解除をお願いいたします。' . "\n";
		$mail_temp .= '２４時間以内に連絡がない場合、お手数ですが' . "\n";
		$mail_temp .= '担当（' .$Mryoukins[0]['Mdivision']['u_email'] . '）までご連絡をお願いします。' . "\n";

		$sess_test = CakeSession::read();

		$mail_temp .= "\n-----------------お客様情報-----------------\n";
		$mail_temp .= 'お　　名　　前：' . $sess_test['user']['name']. "\n";
		$mail_temp .= 'メールアドレス：' . $sess_test['user']['usrmail']. "\n";
		$mail_temp .= '電  話  番  号：' . $sess_test['user']['usrtel']. "\n";
		$mail_temp .= 'お支払い方法 ：' . $Mryoukins[0]['Mpaymentmethod']['name'] . "\n";
		$mail_temp .= "\n----------------お申込み内容----------------\n";
		$mail_temp .= "   講　座　名　";
                                 
		foreach ($Mryoukins as $Msubscription):
			$wk = $Msubscription['Mryoukin']['name'];

			if(is_null($Msubscription['Msubscription']['eschedule_id'])) {
				$wk .= "\n       " . $Msubscription['Mryoukin']['opday'] . "\n" . $Msubscription['Mryoukin']['optime'];
			} else {
				if(empty($Msubscription['Eschedule']['jikan'])) $jikan = $Msubscription['Mryoukin']['optime']; 
				else $jikan = $Msubscription['Eschedule']['jikan'];
				$jikan = "(" . $jikan . ")";
				$wk .= "\n" . '       1日目： ' . $Msubscription['Eschedule']['date1'] . ' ' . $jikan;
			if(! empty($Msubscription['Eschedule']['date2']))
				$wk .= "\n       2日目： " . $Msubscription['Eschedule']['date2'] . ' ' . $jikan;
			if(! empty($Msubscription['Eschedule']['date3']))
				$wk .= "\n       3日目： "  . $Msubscription['Eschedule']['date3'] . ' ' . $jikan;
			}
			$mail_temp .=  $wk . "\n   料　　金　" . number_format($Msubscription['Mryoukin']['kng']) . "円\n"; 
 		endforeach; 
		$mail_temp .= "\n----------------開 催 日 時-----------------\n";
		$mail_temp .= "上に明記がない場合、24時間以内に別途日程調整のメールが届きます。\n";
		$mail_temp .= "明記されている場合、開催時間の10分前を目途にご来校ください。\n";
		$mail_temp .= "（それ以上早く来られましても開いていない場合がございます。)\n";


		$mail_temp .= "\n----------キャンセル・返金規定------------\n";
		$mail_temp .= "キャンセルに関しては以下をご参照ください。\n";
		$mail_temp .= 'http://school.heartf.com/index.php?go=DQR9Sh' ."\n\n";


		$mail_temp .= "ご不明な点がございましたらお気軽にお問合せください。\n\n";

		return($mail_temp);
	}
                                	
	public function Reception_cach($kng) {
		$mail_temp = "\nお支払方法が当日現金払いとなっておりますので、\n";
		$mail_temp .= "おつりの無いようにご用意いただけると幸いです。\n";
		$mail_temp .= "\n--------------お支払いのご案内--------------\n";
		$mail_temp .= "お支払い金額：". number_format($kng) . "円\n";
		return($mail_temp);
	}
	public function Reception_bank($kng, $data2) {
		$mail_temp = "----------------ご連絡事項------------------\n";
		$mail_temp .= "・講座内容にお間違いがないようでしたらお申込み後、5営業日以内にお振込みをお願いします。\n";
		$mail_temp .= "・開催日まで5日を切る場合は開催日の2日前までにお振込みをお願いします。\n";
		$mail_temp .= "・振込手数料はお客様のご負担となりますので、ご了承ください。\n";
		$mail_temp .= "・期限内にお振込みがない場合はご連絡をお願いいたします。ご連絡が無い場合、\n";
		$mail_temp .= "　お申し込みを取消しさせて頂きます。\n";
		
		$mail_temp .= "\nお振込みの確認がとれましたら、お申込みを確定させていただきます。\n";
		
		$mail_temp .= "\n\n------------お振込み金額のご案内------------\n";
		$mail_temp .= "お振込み金額　　" .number_format($kng) . "円\n";
		$mail_temp .= "\n--------------銀行口座のご案内--------------\n";
		$mail_temp .= "銀行名　 " . $data2['Mdivision']['bankname'] ."\n";
		$mail_temp .= "支店名　 " . $data2['Mdivision']['branchname2'] ."\n";
		$mail_temp .= "口座種別　普通　口座番号　　" . $data2['Mdivision']['account2'] ."\n";
		$mail_temp .= "口座名義人 " . $data2['Mdivision']['accountname2'] ."\n";
		return($mail_temp);
	}

	public function mail_fooder($data1 = NULL,  $Remarks = NULL) {
		$mail_temp =NULL;
		if(!empty($Remarks)) {
			$mail_temp .= "\n------------------備　　　　考--------------\n";
			$mail_temp .= $Remarks . "\n";
		}
		$mail_temp .= "\n------------------お問い合わせ--------------\n";
		$mail_temp .= "担   当   者：".$data1['u_name'] . "\n";
		$mail_temp .= "問合せメール:" .$data1['u_email'] . "\n";
		$mail_temp .= "電 話  番 号:" .$data1['u_tel'] . "\n";
		return($mail_temp);
		
	}
	public function send_mail($m_to , $data1, $bcc = NULL) {
		$sess_test = CakeSession::read();
//携帯メールなら
	  /* if (
	      preg_match("/@(docomo|softbank|disney|ezweb|[dhtkrsnqc]\.vodafone|pdx|d[kij]\.pdx|wm\.pdx)\.ne\.jp$/i", $m_to)
	      OR preg_match("/@(jp-[dhtkrsnqc]\.ne\.jp|i\.softbank\.jp|willcom\.com|[a-z]+\.biz\.ezweb\.ne\.jp)$/i", $m_to)
	      OR preg_match("/@(emnet|emobile|emobile-s|ymobile1|ymobile|yahoo|wcm|y-mobile)\.ne\.jp$/i", $m_to)
	   ) 
		   $email = new CakeEmail('isoftbank');

		else */
		$email = new CakeEmail('xserver');
//var_dump($data1);exit;
//		if($m_to == $email->cc) $email->cc(NULL);
		$email->to($m_to);
	//	$email->to('info.heartfull@gmail.com');
		//$email->bcc($data1['u_email']);
  	    $email->emailFormat('text');                            // フォーマット
		//$email->replyTo
		
		$email->subject($sess_test['wk']['m_subject']);
		$sess_test['wk']['mail_temp'] .= $this->mail_fooder($data1);
//var_dump($email);exit;

		$email->send(wordwrap($sess_test['wk']['mail_temp'], 70)); //1行70文字以上対策
		
	}


}
?>
