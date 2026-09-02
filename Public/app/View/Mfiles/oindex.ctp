<table bgcolor="#C1C1C1" cellpadding="0" cellspacing="1" width="400">
	<?php
		$i = 1;
	foreach ($Mfiles as $Mfile):
		if ($i == 1) { ?>
<tr bgcolor="#ffffff">
		<?php } ?>
		<?php 
		$url = "https://school.heartf.com/cake/mfiles/oview/" . $Mfile['Mfile']['id'];
	//	$wk = $Mfile['Mfile']['id']; ?>
<td colspan="2" width="130" height="180" valign="top"  align="center">

<Div Align="center"><?php echo h($Mfile['Mfile']['title']); ?></br>
<a href="<?php echo $url;?>" target="_blank">
<?php $image = base64_encode($Mfile['Mfile']['thumbnail']);
			echo "<img src='data:image/jpeg;base64,${image}' />" ?></a>
</br>
<?php echo h((int)($Mfile['Mfile']['filesize'] / 1024 / 1024)); ?>(MB)　　<?php echo h($Mfile['Mfile']['filetype']); ?></br>
<?php echo h($Mfile['Mfile']['description']); ?></td>
	<?php if ($i == 3 ) { ?>
			</tr>
		<?php $i = 0;  }
	
		$i = $i + 1;	?>
<?php endforeach; 
for (; $i <= 3 ; $i++) { ?>
		 <td></td>
<?php } ?>
	</tr>
</table>
