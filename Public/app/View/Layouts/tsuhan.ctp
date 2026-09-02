<?php
/**
 *
 * PHP 5
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright 2005-2012, Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright 2005-2012, Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       Cake.View.Layouts
 * @since         CakePHP(tm) v 0.10.0.1076
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

?>

<!doctype html>
<html>
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width">
<meta name="keywords" content="東京,占い,タロット教室,占い学校,タロット通信講座" />
<meta name="description" content="タロット通信講座はオリジナル教材と動画で楽しく学べて短期間で占い師になれる。すぐに使える占いと心理を合わせた心理タロットは特許庁も認めた占いのノウハウです。" />
<title>タロット通信講座・スマホや動画で心理も学べる｜占いスクール　ハートフル</title>
<script type="text/javascript" src="https://starot.school.heartf.com/wp-content/themes/starot_onlye-t/js/sr.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
<script type="text/javascript">
if (window.matchMedia('screen and (min-width:768px)').matches) {
$(function(){
  $('a[href^=#]').click(function(){
    var speed = 500;
    var href= $(this).attr("href");
    var target = $(href == "#" || href == "" ? 'html' : href);
    var position = target.offset().top;
    $("html, body").animate({scrollTop:position}, speed, "swing");
    return false;
  });
});
}else{
        //スクリーンサイズが768pxより小さい時の処理
	}
</script>
<script type="text/javascript">
$(document).ready(function() {
  var pagetop = $('.pagetop');
    $(window).scroll(function () {
       if ($(this).scrollTop() > 0) {
            pagetop.fadeIn();
       } else {
            pagetop.fadeOut();
            }
       });
       pagetop.click(function () {
           $('body, html').animate({ scrollTop: 0 }, 500);
              return false;
   });
});
</script>
<link href="https://starot.school.heartf.com/wp-content/themes/starot_onlye-t/style.css" rel="stylesheet" type="text/css" />
<link href="https://starot.school.heartf.com/wp-content/themes/starot_onlye-t/style_s.css" rel="stylesheet" type="text/css" media="only screen and (max-width:480px)">
<?php echo $this->Html->css('cake.generic');?>
</head>

<body>
<h1 align="left">タロット通信講座・スマホや動画で心理も学べる｜占いスクール　ハートフル</h1>
<h2 align="center" id="main_bg">自宅で学んでタロット占い師になれる！</h2>
<div class="pagetop"><a href="#top"><img src="https://starot.school.heartf.com/wp-content/themes/starot_onlye-t/imgs/pagetop.png" width="82" height="72" alt="ページトップへ" /></a></div>
<div id="globalnavigation">
	<ul>
    <li><a href="#1"><img src="https://starot.school.heartf.com/wp-content/themes/starot_onlye-t/imgs/btn1_off.jpg" alt="教材内容" /></a></li>
    <li><a href="#2"><img src="https://starot.school.heartf.com/wp-content/themes/starot_onlye-t/imgs/btn2_off.jpg" alt="講座の特徴" /></a></li>
    <li><a href="#3"><img src="https://starot.school.heartf.com/wp-content/themes/starot_onlye-t/imgs/btn3_off.jpg" alt="よくある質問" /></a></li>
    <li><a href="#4"><img src="https://starot.school.heartf.com/wp-content/themes/starot_onlye-t/imgs/btn4_off.jpg" alt="商品詳細" /></a></li>
    <li><a href="https://starot.school.heartf.com/pay/"><img src="https://starot.school.heartf.com/wp-content/themes/starot_onlye-t/imgs/btn5_off.jpg" alt="配送方法" /></a></li>
    <li><a href="https://starot.school.heartf.com/about/"><img src="https://starot.school.heartf.com/wp-content/themes/starot_onlye-t/imgs/btn6_off.jpg" alt="特定商取引に基づく表記" /></a></li>
    </ul>
</div>
<div id="globalsm">
<table width="90%" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td align="center"><a href="#1"><img src="https://starot.school.heartf.com/wp-content/themes/starot_onlye-t/imgs/btn1.png" alt="教材内容" width="60" height="60"></a></td>
    <td align="center"><a href="#2"><img src="https://starot.school.heartf.com/wp-content/themes/starot_onlye-t/imgs/btn2.png" alt="講座の特徴" width="60" height="60"></a></td>
    <td align="center"><a href="#3"><img src="https://starot.school.heartf.com/wp-content/themes/starot_onlye-t/imgs/btn3.png" alt="よくある質問" width="60" height="60"></a></td>
    <td align="center"><a href="#4"><img src="https://starot.school.heartf.com/wp-content/themes/starot_onlye-t/imgs/btn4.png" alt="商品詳細" width="60" height="60"></a></td>
  </tr>
</table>
<table width="90%" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td align="center"><a href="https://starot.school.heartf.com/pay/"><img src="https://starot.school.heartf.com/wp-content/themes/starot_onlye-t/imgs/btn5.png" alt="教材内容" width="90" height="60"></a></td>
    <td align="center"><a href="https://starot.school.heartf.com/about/"><img src="https://starot.school.heartf.com/wp-content/themes/starot_onlye-t/imgs/btn6.png" alt="講座の特徴" width="90" height="60"></a></td>
  </tr>
</table>
</div>
<div id="container">
<div class="content">
    <div id="main">

			<?php echo $this->Session->flash(); ?>

			<?php echo $this->fetch('content'); ?>
</div></div></div>
<p><!-- / .content -->
</div>
<div id="footer">
	<div class="footer_area">Copyright c 2017 <a href="https://school.heartf.com">株式会社ハートフル</a> All Rights Reserved.</div>
</div>
</body>
</html>
