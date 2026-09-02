<h2>申込み手順</h2>
<center>
<?php 
echo $this->Html->image('step1s.jpg',array('alt'=>'講座選択')); 
echo $this->Html->image('step2.jpg',array('alt'=>'生徒登録')); 
echo $this->Html->image('step3.jpg',array('alt'=>'支払方法')); 
echo $this->Html->image('step4.jpg',array('alt'=>'申込完了')); 
?>
</center>
<h2>申込講座確認</h2>

	<table border="0" cellspacing="1" class="contact_form m10">
	<tr>
			<th>講座名</th>
			<th>料　金</th>
			<th></th>
	</tr>
	<?php $kng = 0; foreach ($Mryoukins as $Mryoukin): ?>
	<tr>
		<td class="c_right"><?php echo h($Mryoukin['Mryoukin']['name']); ?></br>
		<?php if(!empty($Esche[$Mryoukin['Mryoukin']['id']][1]))
				echo $Esche[$Mryoukin['Mryoukin']['id']][1];
			else echo $Mryoukin['Mryoukin']['opday'] . '</br>' .$Mryoukin['Mryoukin']['optime'];
		?>
		</td>
		<td align="right" class="c_left"><?php echo h(number_format($Mryoukin['Mryoukin']['kng'])); ?>&nbsp;</td>
		<td align="right" class="c_del"><?php echo $this->Form->postLink(__('削除'), array('action' => 'delete', $Mryoukin['Mryoukin']['id']), array('confirm' => __('この講座を削除しますか?', $Mryoukin['Mryoukin']['name']))); ?>
</td>
	</tr>
	<?php $kng += $Mryoukin['Mryoukin']['kng'];
			if(!$Mryoukin['Mryoukin']['anytime_flg']) $anytime_flg = 0;
 endforeach; ?>
	<tr>
		<td class="c_right">合　　計</td>
		<td align="right" class="c_left"><?php echo h(number_format($kng)); ?>&nbsp;</td>
		<td align="right" class="c_del"></td>
	</tr>
 
	</table>
 <h2>他の講座も受講される場合</h2>
<?php 
echo '<div class="kouza_list"> ';
echo $this->Html->link('', FULL_BASE_URL . '/index.php?go=bdFnXs', array('target'=> '_top'));
echo "</div>";
?>
<h2>生徒情報の確認</h2>
既に生徒登録をされている場合はログインしてからお支払確定に進んでください。初めての場合は「新規登録」をしてからお支払い画面に進んでください。
</br>これ以降のページは暗号化サイトとなりますので、別ウィンドウで開きます。
</br>
</br>生徒登録は、入学後、学習の部屋（自習・復習のコンテンツ）を利用するためにも必要となります。

<?php 
echo '<div class="users_login"> ';
echo $this->Html->link('', array('controller' => 'users', 'action' => 'login'), array('target'=> '_blank'));
echo "</div>";
echo '<div class="users_add"> ';
echo $this->Html->link('', array('controller' => 'users', 'action' => 'add'), array('target'=> '_blank'));
echo "</div>";

	?>
