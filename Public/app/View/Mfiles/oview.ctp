<?php $dl = 'https://' . $hostname . '/cake/tmp/' . $mfile['Mfile']['filename'];
if($mfile['Mfile']['filetype'] == "mp4") { ?>
<video width="480" height="270" src="<?php echo $dl?>" controls="controls" autoplay="autoplay"></video>
<?php } else { ?>
<object type="application/pdf" data="<?php echo $dl?>"></object>
<?php } ?>