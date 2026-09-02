<?php if($this->Session->Read('Kizon_flg')) 
	$wk = "＜占い師独立講座付帯講座申し込み・進捗＞";
	else $wk = "＜空席待ち手順・進捗＞";
?>
<center>
<?php 
echo $this->Html->image('step1s.png',array('alt'=>'講座選択')); 
echo $this->Html->image('step_y.png',array('alt'=>'⇒')); 
echo $this->Html->image('step2s.png',array('alt'=>'生徒登録')); 
echo $this->Html->image('step_y.png',array('alt'=>'⇒')); 
echo $this->Html->image('stepr.png',array('alt'=>'連絡事項')); 
echo $this->Html->image('step_y.png',array('alt'=>'⇒')); 
echo $this->Html->image('step4.png',array('alt'=>'申込完了')); 
?>
</center>
<h2>連絡事項</h2>
<?php echo $this->Form->create('Msubscription');?>
<center>
	<table width="100%" border="0" cellspacing="1" class="contact_form">
		<tr><td class="c_left">連絡事項</td>
			<td class="c_right">※連絡事項がございましたら、こちらにご記入ください。</br>
			<div class="false"><?php echo $this->Form->input('remarks', array('cols' => 50, 'rows' => 10, 'label' => '', 'div' => 'false')); ?></td></tr>
	</table>

<?php echo $this->Form->submit('確認画面へ') ?>
</center>