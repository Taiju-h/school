＜申込み手順・進捗＞
<center>
<?php
echo $this->Html->image('step1s.png',array('alt'=>'講座選択'));
echo $this->Html->image('step_y.png',array('alt'=>'⇒'));
echo $this->Html->image('step2.png',array('alt'=>'生徒登録'));
echo $this->Html->image('step_y.png',array('alt'=>'⇒'));
echo $this->Html->image('step3.png',array('alt'=>'支払方法'));
echo $this->Html->image('step_y.png',array('alt'=>'⇒'));
echo $this->Html->image('step4.png',array('alt'=>'申込完了'));
?>
</center>
<h2>申込み講座確認</h2>

	<table border="0" cellspacing="1" class="contact_form m10">
	<tr align="center">
			<th>講座名</th>
			<th>料　金</th>
			<th>削除</th>
	</tr>
	<?php $kng = 0;
		foreach ($Mryoukins as $Mryoukin):  ?>
	<tr>
		<td class="c_right"><?php echo $Mryoukin['Mryoukin']['name'];
		if(!empty($Day[$Mryoukin['Mryoukin']['id']][1]))
			echo h($Day[$Mryoukin['Mryoukin']['id']][1]);
		echo "</br>";
		if(!empty($Esche[$Mryoukin['Mryoukin']['id']][1]))
				echo $Esche[$Mryoukin['Mryoukin']['id']][1];
			else echo $Mryoukin['Mryoukin']['opday'] ."</br>". $Mryoukin['Mryoukin']['optime'];?></td>
		<td align="right" class="c_left"><?php echo number_format($Mryoukin['Mryoukin']['kng'] * TAX); ?>&nbsp;</td>
		<td align="right" class="c_del"><?php echo $this->Form->postLink(__('削除'), array('action' => 'delete', 			$Mryoukin['Mryoukin']['id']), array('confirm' => __('この講座を削除しますか? %s', $Mryoukin['Mryoukin']['name']))); ?></td>
	</tr>
	<?php $kng += $Mryoukin['Mryoukin']['kng'];
			if(!$Mryoukin['Mryoukin']['anytime_flg']) $anytime_flg = 0;
 endforeach; ?>
 <?php $kng1 = (int)$kng * TAX; ?>

	<tr>
		<td class="c_right">合　　計</td>
		<td align="right" class="c_left"><?php echo h(number_format($kng1)); ?>&nbsp;</td>
		<td align="right" class="c_del"></td>
	</tr>

	</table>
 <?php  if(! $this->Session->read('PRE_URL')) {
  			echo  "<right></br>";
			if(! $this->Session->read('TANDOKU'))
				echo $this->Html->link('追加で講座を申込む場合こちらへ', FULL_BASE_URL . '/index.php?Application#top', array('target'=> '_top'));
			else echo '心理タロット講座を含む申し込みの場合は単独でしか受け付けませんので、他の講座は選択しないでください。';
			echo "</right><p></p>";
		}
 ?>


<h3>生徒登録</h3>
既に生徒登録をされている場合はログインしてからお支払確定に進んでください。</br>
初めての場合は「新規登録」をしてからお支払い画面に進んでください。
</br>
</br>生徒登録は、入学後、学習の部屋（自習・復習のコンテンツ）を利用するためにも必要となります。</br></br>

<?php

echo '<div class="users_login"> ';
echo $this->Html->link('', array('controller' => 'users', 'action' => 'login'), array('target'=> '_top'));
echo "</div>　";
echo '<div class="users_add"> ';
echo $this->Html->link('', array('controller' => 'users', 'action' => 'add'), array('target'=> '_top'));
echo "</div>";

	?>
