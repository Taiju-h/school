<!doctype html>
<html>
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width">
<title>学習コンテンツ</title>
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
<script type="text/javascript">
$(document).ready(function() {
	  $(this).addClass("study_open");//クリックタブに.activeを追加
      $(".st").hide();//全ての.tab_contentを非表示
          var activeTab = $(this).find("a").attr("href");//アクティブタブコンテンツ
          $(activeTab).fadeIn();//アクティブタブコンテンツをフェードイン
          return false;
     });
});
</script>
<script>
$(function() {
    $(".mokuji_text li").click(function() {
        var num = $(".mokuji_text li").index(this);
        $(".ct").removeClass('active');
        $(".ct").eq(num).addClass('active')
    });
});
</script>
	<?php
echo $this->Html->css('cake.generic');
echo $this->Html->css('study');
echo $this->Html->css('style_s', array('media' => 'only screen and (max-width:480px)'));
	?>
</head>
<body>
	<div class="study_content">
		<?php echo $this->fetch('content'); ?>
	</div>
</body>
</html>
