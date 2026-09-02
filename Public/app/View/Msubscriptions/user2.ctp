
＜空席待ち手順・進捗＞
<center>
<?php 
echo $this->Html->image('step1s.png',array('alt'=>'講座選択')); 
echo $this->Html->image('step_y.png',array('alt'=>'⇒')); 
echo $this->Html->image('step2.png',array('alt'=>'生徒登録')); 
echo $this->Html->image('step_y.png',array('alt'=>'⇒')); 
echo $this->Html->image('stepr.png',array('alt'=>'連絡事項')); 
echo $this->Html->image('step_y.png',array('alt'=>'⇒')); 
echo $this->Html->image('step4.png',array('alt'=>'申込完了')); 
?>
</center>
<font color ='RED' size ='4'>※現在満席のため、空席待ちの登録となります。空席が出来次第メールにてお知らせいたします。</font>
</br>
<h2>空席待ち講座確認</h2>

	<table border="0" cellspacing="1" class="contact_form m10">
	<tr>
			<th>講座名</th>
	</tr>
	<?php $kng = 0; 
		foreach ($Mryoukins as $Mryoukin):  ?>
	<tr>
		<td class="c_right"><?php echo h($Mryoukin['Mryoukin']['name']  . $Day[$Mryoukin['Mryoukin']['id']][1]); ?></br>
		<?php if(!empty($Esche[$Mryoukin['Mryoukin']['id']][1]))
				echo $Esche[$Mryoukin['Mryoukin']['id']][1];
			else echo $Mryoukin['Mryoukin']['opday'] . '</br>' .$Mryoukin['Mryoukin']['optime'];
		?>
		</td>
</td>
	</tr>
	<?php $kng += $Mryoukin['Mryoukin']['kng'];
			if(!$Mryoukin['Mryoukin']['anytime_flg']) $anytime_flg = 0;
 endforeach; ?>
	</table>
</br>
<h3>生徒登録</h3>
既に生徒登録をされている場合はログインしてから空席待ち確定に進んでください。初めての場合は「新規登録」をしてから空席待ち確定画面に進んでください。
</br>これ以降のページは暗号化サイトとなります。
</br>
</br>生徒登録は、ご予約後お申し込みするのにも必要です。</br></br>

<?php 
echo '<div class="users_login"> ';
echo $this->Html->link('', array('controller' => 'users', 'action' => 'login'), array('target'=> '_top'));
echo "</div>　";
echo '<div class="users_add"> ';
echo $this->Html->link('', array('controller' => 'users', 'action' => 'add'), array('target'=> '_top'));
echo "</div>";

	?>
