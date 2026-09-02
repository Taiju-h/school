 
<style> 
    #backButton {
    position: fixed;
    bottom: 20px;
    right: 20px;
    font-size: 18px;
    padding: 10px 20px;
    background-color: #007BFF;
    color: white;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    z-index: 100;
}

    
    .study_content{
    display: none;
    
    }
    
 
.contents_container {
  display: flex;
  flex-wrap: wrap;
}

.contents_text {
  flex: 1 0 21em;
  margin: 1em;
}

@media (max-width: 768px) {
  .contents_text {
    flex: 0 0 100%;
  }
}
   
    
    
      #backButton {
    position: fixed;
    bottom: 20px;
    right: 20px;
    font-size: 18px;
    padding: 10px 20px;
    background-color: #007BFF;
    color: white;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    z-index: 100;
}

.contents_container {
  display: flex;
  flex-wrap: wrap;
  margin: -1em;
}

    .contents_text img{
    margin-bottom: 10px;
    }
.contents_text {
  flex: 1 0 21em;
  margin: 1em;
  border: 1px solid #ccc;
  padding: 1em;
  box-shadow: 2px 2px 5px rgba(0,0,0,0.1);
  border-radius: 5px;
}

    
    
.contents_title h3 {
  color: #007BFF;
  font-size: 20px;
  margin-bottom: 0.5em;
}

.contents_info {
  color: #555;
  font-size: 12px;
}

.contents_desc {
    margin-top: 25px;
  color: #333;
clear: both;
  font-size: 12px;
  margin-top: 1em;
  border: 1px solid #DDD;
  padding: 10px;
  border-radius: 5px;
}

@media (max-width: 768px) {
  .contents_text {
    flex: 0 0 100%;
  }
}
</style>
<button id="backButton" onclick="goBack()">&#8592; 戻る</button>
<script>
function goBack() {
    window.history.back();
}

</script>
<div class="study_content" style="clear:both;">
<?php
//$msg = '</br><font size="5" color ="red">※ただいま臨時メンテナンス中でファイルを閲覧することが出来ません。復旧まで今しばらくお待ちください。</font>'; 
$msg = NULL;
?>

<?php 	$auth =$this->Session->read('Auth');

/*		if(! isset($auth['User']['id'])) {
			echo 'ようこそ！ゲストさま';
		} else {
echo $this->Form->create('Massociation');
			echo 'ようこそ！' . $auth['User']['name'] . '様';
			echo $this->Form->end(__('ログアウトします。'));
	}
*/
echo $msg;
?>

	<div class="study_shell">
		<?php foreach ($mkbn1s as $key => $value):
			if($id == $key) echo '<li class="study_open">';
			else 	echo '<li class="study_thumb">'; ?>
			<?php echo '<a href="' . FULL_BASE_URL . '/Public/Massociations/text/' . $key . '#one">' . $value . '</a><br>'; ?></li>
		<?php endforeach; ?>
	</div></div>
</div> </div>
	<?php if(! is_null($id)) { ?>
<A NAME="one"></a>
	<div class="study_content">
		<div class="st">
		<div class="study_mokuji">
			<div class="mokuji_text">
		    <ul>

			<?php
				foreach ($mkbn3s as $mkbn3): ?>
				<li><?php echo '<a href="' . FULL_BASE_URL . '/Public/Massociations/text/' . $kbn1 . '/3/' . $mkbn3['Mkbn3']['id'] . '#two">' . $mkbn3['Mkbn3']['name'] . '</a><br>'; ?></li>
			<?php endforeach; ?>
			<?php
				foreach ($mkbn2s as $mkbn2):
				?>
				<li><?php echo '<a href="' . FULL_BASE_URL . '/Public/Massociations/text/' . $kbn1 . '/2/' . $mkbn2['Mkbn2']['id'] . '#two">' . $mkbn2['Mkbn2']['name']  . '</a><br>'; ?></li>
			<?php endforeach; ?>
		    </ul>
		 </div>
		 </div>
		 </div> </div>

			<?php } ?>
</div> 
	<?php if(! is_null($id2)) { ?>
<A NAME="two"></a>
<?php echo $msg; ?>

<div class="content">
	<div class="ct">
<div class="contents_container">
  <?php
    $Massociations = array_reverse($Massociations);
    foreach ($Massociations as $Massociation): 
  ?>
  <div class="contents_text">
    <div class="contents_title">
      <h3><?php echo $Massociation['Mfile']['title']; ?></h3>
    </div>
    <div>
      <a href="<?php $url = "/Public/mfiles/oview3/" . $Massociation['Mfile']['id']; echo $url;?>" target="_blank">
      <?php $image = base64_encode($Massociation['Mfile']['thumbnail']);
            echo "<img src='data:image/jpeg;base64,${image}' align='left' style='margin-right: 1em;' />" ?>
      <div class="contents_info">
        <?php echo "<strong>サイズ：</strong>" . h((int)($Massociation['Mfile']['filesize'] / 1024 / 1024)) . "(MB)<BR>"; ?>
        <?php echo "<strong>タイプ：</strong>" . h($Massociation['Mfile']['filetype']) . "<BR>"; ?>
        <?php if($Massociation['Mfile']['limit_flg']) $wk = "あり"; else  $wk = "なし"; echo "<strong>ID/PW：</strong>" . $wk . "<br>"; ?>
      </div>
    </div>
    <div class="contents_desc">
      <?php echo $Massociation['Mfile']['description']; ?>
    </div>
  </div>
  <?php endforeach ?>
</div>

	</div>
</div>



	<?php } ?>
</div>