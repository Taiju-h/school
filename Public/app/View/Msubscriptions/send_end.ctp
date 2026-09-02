<div class="form_kanryo">
<?php if($this->Session->Check('Yoyaku')) {
		$wk = "空席待ち予約";
		$wk1 = "steprs.png";
		$wk2 = "連絡事項";
	} else {
		$wk = "申込み手順";
		$wk1 = "step3s.png";
		$wk2 = "支払方法";
	}
echo '＜' . $wk;
 ?>

・進捗＞
<center>
<?php
echo $this->Html->image('step1s.png',array('alt'=>'講座選択'));
echo $this->Html->image('step_y.png',array('alt'=>'⇒'));
echo $this->Html->image('step2s.png',array('alt'=>'生徒登録'));
echo $this->Html->image('step_y.png',array('alt'=>'⇒'));
echo $this->Html->image($wk1,array('alt'=>$wk2));
echo $this->Html->image('step_y.png',array('alt'=>'⇒'));
echo $this->Html->image('step4s.png',array('alt'=>'申込完了'));
?>
</center>
<PRE>
<?php echo $this->Session->read('wk.mail_temp'); ?>
</PRE>
</div>

メールが受信できない場合はお問合せください。迷惑メールに入っている場合がございます。</br>


<?php CakeSession::destroy();?>
