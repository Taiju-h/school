＜申込み手順・進捗＞
<center>
<?php
echo $this->Html->image('step1s.png',array('alt'=>'講座選択'));
echo $this->Html->image('step_y.png',array('alt'=>'⇒'));
echo $this->Html->image('step2s.png',array('alt'=>'生徒登録'));
echo $this->Html->image('step_y.png',array('alt'=>'⇒'));
echo $this->Html->image('step3s.png',array('alt'=>'支払方法'));
echo $this->Html->image('step_y.png',array('alt'=>'⇒'));
echo $this->Html->image('step4.png',array('alt'=>'申込完了'));
//var_dump($this->request->data['Msubscription']['kng1']); exit;
?>
</center>
<h2>支払方法と連絡事項</h2>
<?php echo $this->Form->create('Msubscription');?>
<center>
	<table width="100%" border="0" cellspacing="1" class="contact_form">
		<?php if($this->request->data['Msubscription']['kng1'] > 0) {
		echo '<tr><td class="c_left">支払方法</td>';
		echo '<td class="c_right"><div class="false">';
		echo $this->Form->input('mpaymentmethod_id', array('label' => '', 'div'));
		echo "※付帯講座申し込みの生徒は銀行振込をお選びください。</td></tr>";
	}
	?>

		<tr><td class="c_left">連絡事項</td>
			<td class="c_right"><div class="false"><?php echo $this->Form->input('remarks', array('cols' => 50, 'rows' => 10, 'label' => '', 'div' => 'false')); ?>※連絡事項がございましたら、こちらにご記入ください。</td></tr>

	</table>

<?php echo $this->Form->submit('確認画面へ') ?>
</center>
