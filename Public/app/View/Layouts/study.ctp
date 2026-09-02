<!doctype html>
<html>
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width">
<title>占技コンテンツ</title>

	<?php
echo $this->Html->css('cake.generic');
echo $this->Html->css('study');
echo $this->Html->css('style_s', array('media' => 'only screen and (max-width:480px)'));
	?>
</head>
<body>
<div class="content">
		<?php echo $this->fetch('content'); ?>
	</div>
</body>
</html>
