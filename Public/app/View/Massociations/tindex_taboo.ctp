
<?php echo $this->Html->image('kindan_contentbanner.jpg');?>

<div class="study_content">


<?php 	$auth =$this->Session->read('Auth'); 

/*		if(! isset($auth['User']['id'])) {
			echo 'ようこそ！ゲストさま';
		} else {
echo $this->Form->create('Massociation'); 
			echo 'ようこそ！' . $auth['User']['name'] . '様';
			echo $this->Form->end(__('ログアウトします。')); 
	}
*/
?>


	<div class="study_shell">
		<?php foreach ($mkbn1s as $key => $value): 
			if($id == $key) echo '<li class="study_open">';
			else 	echo '<li class="study_thumb">'; ?>
			<?php echo '<a href="' . FULL_BASE_URL . '/Public/Massociations/tindex_taboo/' . $key . '#one">' . $value . '</a><br>'; ?></li>
		<?php endforeach; ?>
	</div></div>
	<?php if(! is_null($id)) { ?>
<A NAME="one"></a>
	<div class="study_content">
		<div class="st">
		<div class="study_mokuji">
			<div class="mokuji_text">
		    <ul>
			
			<?php 
				foreach ($mkbn3s as $mkbn3): ?>
				<li><?php echo '<a href="' . FULL_BASE_URL . '/Public/Massociations/tindex_taboo/' . $kbn1 . '/3/' . $mkbn3['Mkbn3']['id'] . '#two">' . $mkbn3['Mkbn3']['name'] . '</a><br>'; ?></li>
			<?php endforeach; ?>
			<?php
				foreach ($mkbn2s as $mkbn2): 
				?>
				<li><?php echo '<a href="' . FULL_BASE_URL . '/Public/Massociations/tindex_taboo/' . $kbn1 . '/2/' . $mkbn2['Mkbn2']['id'] . '#two">' . $mkbn2['Mkbn2']['name']  . '</a><br>'; ?></li>
			<?php endforeach; ?>
		    </ul>
		 </div>
		 </div>
		 </div> </div>

			<?php } ?>
</div>
	<?php if(! is_null($id2)) { ?>
<A NAME="two"></a>
<div class="content">
	<div class="ct">
	<?php foreach ($Massociations as $Massociation): ?>
	
	<div class="contents_title">
	<h3><?php echo $Massociation['Mfile']['title']; ?></h3>
	</div>
	<div class="contents_text">
	<table  class="study_tbl"><tr><td width="210">
	<a href="<?php $url = "/Public/mfiles/oview_taboo/" . $Massociation['Mfile']['id']; echo $url;?>" target="_blank">
	<?php $image = base64_encode($Massociation['Mfile']['thumbnail']);
				echo "<img src='data:image/jpeg;base64,${image}' align='left' />" ?></a>
	</td>
	<td>
	<?php echo "サイズ：" . h((int)($Massociation['Mfile']['filesize'] / 1024 / 1024)); ?>(MB)<BR>
	<?php echo "タイプ：" . h($Massociation['Mfile']['filetype']); ?><BR>
	<?php if($Massociation['Mfile']['limit_flg']) $wk = "あり"; else  $wk = "なし"; echo "ID/PW：" . $wk ?></br>
	</td></tr>
	<tr><td colspan="2"> 
	【説明：】<?php echo $Massociation['Mfile']['description']; ?>
	</br></td></tr>
	</table>
	</div>
	<?php endforeach ?>
	</div>
	</div>

	<?php } ?>
</div>