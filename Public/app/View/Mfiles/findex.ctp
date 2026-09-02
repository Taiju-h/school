<?php /*
<div class="study_shell">
	<?php foreach ($mkbn1s as $key => $value): 	?>
		<li class="study_thumb"><?php echo $this->Html->link($value, array( 'action' => 'tindex', $key),array('escape' => false), false);?></li>
	<?php endforeach; ?>
</div>
*/ ?>
<?php foreach ($Mfiles as $Mfile): ?>

<div class="ct">

<div class="contents_title">
<h3><?php echo $Mfile['Mfile']['title']; ?></h3>
</div>

<div class="contents_text">
<a href="<?php $url = "/cake/mfiles/oview/" . $Mfile['Mfile']['id']; echo $url;?>" target="_blank">
<?php $image = base64_encode($Mfile['Mfile']['thumbnail']);
			echo "<img src='data:image/jpeg;base64,${image}' align='left' />" ?></a>
<?php echo "サイズ：" . h((int)($Mfile['Mfile']['filesize'] / 1024 / 1024)); ?>(MB)<BR>
<?php echo "タイプ：" . h($Mfile['Mfile']['filetype']); ?><BR>
<?php if($Mfile['Mfile']['limit_flg']) $wk = "あり"; else  $wk = "なし"; echo "ID/PW：" . $wk ?></br>
<?php echo "説明：" . h($Mfile['Mfile']['description']); ?>

</div>
<?php endforeach ?>