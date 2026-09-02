<?php foreach ($Massociations as $Massociation): ?>

<div class="contents_title">
<h3><?php echo $Massociation['Mfile']['title']; ?></h3>
</div>
<div class="contents_text">
<table  class="study_tbl"><tr><td>
<a href="<?php $url = "/Public/mfiles/oview/" . $Massociation['Mfile']['id']; echo $url;?>" target="_blank">
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
