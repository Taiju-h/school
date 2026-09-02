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
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<?php echo $this->Html->charset(); ?>
	<title>
		<?php echo $cakeDescription ?>:
		<?php echo $title_for_layout; ?>
	</title>
	<?php
		echo $this->Html->css('cake.generic');
	?>
   <meta http-equiv="content-style-type" content="text/css" />
   <meta name="keywords" content="占い学校,東京,占いスクール,心理タロット,オンラインタロット講座,オンライン西洋占星術講座,オンライン数秘術講座,心理,占い師になる,タロット,手相,西洋占星術" />
   <meta name="description" content="占い師の学校。占いと心理の専門スクール。占い師お仕事講座で副業・本業・本気で稼ぐカリスマ。心理タロット。オンライン心理タロット。手相。無料説明会実施中。" />
   <meta name="viewport" content="width=device-width">
   <meta http-equiv="Content-Script-Type" content="text/javascript" />
   <meta http-equiv="imagetoolbar" content="no" />
   <link rel="alternate" type="application/rss+xml" title="RSS" href="https://school.heartf.com/index.php?cmd=rss" />

  <link rel="stylesheet" media="screen" href="/skin/hokukenstyle/heartful_school_sub/main.css?">
  <link rel="stylesheet" media="print" href="/skin/hokukenstyle/heartful_school_sub/main_print.css">
  <link rel="shortcut icon" href="favicon.ico"  type="image/x-icon" /> <script type="text/javascript" src="/js/jquery.js"></script><script type="text/javascript" src="/js/jquery.cookie.js"></script> <link rel="author" href="https://plus.google.com/103178923754360096075" />  <script>
  if (typeof QHM === "undefined") QHM = {};
  QHM = {"window_open":true,"exclude_host_name_regex":"school.heartf.com","default_target":"_blank"};
  </script><meta name="GENERATOR" content="Quick Homepage Maker; version=7.3.7; haik=false" />

  		<script src="/js/jquery.easing.1.3.js" type="text/javascript"></script>
  		<script src="/js/slides.jquery.js" type="text/javascript"></script>
  	<link rel="canonical" href="https://school.heartf.com">
  <script src="/js/qhm.min.js"></script><style>
  .img_margin_left {
    float: left;
    margin: 0 1em 0 0;
  }
  .img_margin_right {
    float: right;
    margin: 0 0 0 1em;
  }
  </style>
  	<style type="text/css">
  a.prev:hover,a.next:hover{
  background-color:transparent!important;
  }

  .slides_container a img {
  	display:block;
  	margin: 0 auto;
  }
  .slides_pagination li {
  	float:left;
  	margin:0 1px;
  	list-style:none;
  }

  .slides_pagination li a {
  	display:block;
  	width:12px;
  	height:0;
  	padding-top:12px;
  	background-image:url(./image/slides/pagination.png);
  	background-position:0 0;
  	float:left;
  	overflow:hidden;
  }

  .slides_pagination li.current a {
  	background-position:0 -12px;
  }

  </style>

  		<style type="text/css">
  #slides_1 .slides_container {
  	width:570px;
  	overflow:hidden;
  	position:relative;
  	display:none;
  }
  #slides_1 .slides_container a {
  	width:570px;
  	height:270px;
  	display:block;
  }

  #container_1 {
  	width:550px;
  	height: 320px;
  	padding:10px;
  	margin:10px auto 0;
  	position:relative;
  	z-index:0;
  	float: left;
  	margin-left: 40px;
  	margin-right: 40px;
  }
  #frame_1 {
  	position:absolute;
  	z-index:0;
  	width:739px;
  	height:341px;
  	top:-3px;
   	left:-80px;
   	max-width: none;
  }
  #slides_1 {
  	position:absolute;
  	top:15px;
  	left:4px;
  	z-index:100;
  }

  #slides_1 .next,#slides_1 .prev {
  	position:absolute;
  	top:107px;
  	left: -24px;
  	width:24px;
  	height:34px;
  	display:block;
  	z-index:101;
  }
  #slides_1 .next img,#slides_1 .prev img{
  -ms-filter: "alpha( opacity=30 )";
  filter: alpha( opacity=30 );
  opacity: 0.3;
  width: 100%;
  }
  #slides_1 .next:hover img,#slides_1 .prev:hover img {
  opacity:0.8;
  }

  #slides_1 .next {
  left: 570px;
  }
  #wrapper ul.slides_pagination {
  	margin:26px auto 0;
  	width:135px;
  	padding: 0;
  }
  </style>
  <script type="text/javascript">
  <!--
  	$(function(){
  			$("#slides_1").slides({
  				preload: true,
  				preloadImage: "/image/slides/slide_loading.gif",
  				play: 6000,
  				pause: 2500,
  				hoverPause: true,
  				slideEasing: "easeOutQuint",
  				paginationClass : "slides_pagination"
  			});
  		});
  //-->
  </script>
  <script type="text/javascript">
  <!--
  $(function(){
  	 $("#slides_1 .next img,#slides_1 .prev img").hover(function(){
  	 $(this).fadeTo("fast",0.8);
  	 },function(){
  	 $(this).fadeTo("fast",0.3);
  	 });
  	 $("#slides_1 .slides_control img").hover(function(){
  	 $(this).fadeTo("100",0.8,"easeOutQuint");
  	 },function(){
  	 $(this).fadeTo("fast",1.0);
  	 });
  });
  //-->
  </script>
  <script>
    (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
    (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
    m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
    })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

    ga('create', 'UA-93150178-1', 'auto');
    ga('send', 'pageview');

  </script>
  <link href="/style_s.css?version=5" rel="stylesheet" type="text/css" media="only screen and (max-width:480px)">
  <script type="text/javascript" src="https://www.google.com/jsapi"></script>
  <script type="text/javascript" src="/js/rss.js"></script>
  <script>
      $(function(){
  				$('#menu').click( function()
  {
  	// [#acdn-target]に[slideToggle()]を実行する
  	$('#globalnavigation2').slideToggle() ;

  } ) ;
      });

  </script>
  <script>
  (function(){
  var _UA = navigator.userAgent;
  if (_UA.indexOf('iPhone') > 0) {
  document.write('<script type="text/javascript" src="/js/jquery.browser.js"><\/script>');
  }else{
  document.write('<script type="text/javascript" src="/js/jquery.browser.js"><\/script><script type="text/javascript" src="/js/jquery-iframe-auto-height.js"><\/script>');
  }
  })();
  </script>
  <script>
    (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
    (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
    m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
    })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

    ga('create', 'UA-75135382-2', 'auto');
    ga('send', 'pageview');

  </script>

  <style type="text/css">
  div.gmap_box,div.gmap_box_right{
  padding-left:5px;
  }

  #container_1 {
  	width:710px;
  	padding:0px;
  	margin:0px auto 0;
  	position:relative;
  	z-index:0;
  	float: none;
  	margin-left: auto;
  	margin-right: auto;
  }

  #slides_1 {
      position: absolute;
  width:710px;
      top: 0px;
      left: 4px;
      z-index: 100;
  }

  #slides_1 .next,#slides_1 .prev {
  	display:none;
  }

  #slides_1 .slides_container{
  width:710px;
  height:290px;
  }

  #slides_1 .slides_container a {
  width:710px;
  height:290px;
  	display:block;
  }

  .slides_container img{
  width:710px;
  }

  .slides_pagination {
  	display:none;
  }

  #frame_1 {
  	display:none;
  }

  @media screen and (max-width: 480px) {
  #container_1 {
  	width:380px;
  	padding:0px;
  	margin:0px auto 0;
  	position:relative;
  	z-index:0;
  	float: none;
  	margin-left: auto;
  	margin-right: auto;
  }

  #slides_1 {
  	width:380px;
      position: absolute;
      top: 0px;
      left: -30px;
      z-index: 100;
  	margin-left:0px;
  }

  #slides_1 img{
  	width:320px;
  }

  #slides_1 .slides_container{
  width:380px;
  height:180px;
  }

  #slides_1 .slides_container a {
  width:380px;
  height:180px;
  	display:block;
  }


  .prev img{
  	display:block;
      position: absolute;
  	top:-70px;
      left: 200px;
      z-index: 9999;
  }

  .next img{
  	display:block;
      position: absolute;
  	top:-70px;
      right: 200px;
      z-index: 9999;
  }
  }
  </style>
  <script src="/js/lity.js" type="text/javascript"></script>
  <link rel="stylesheet" type="text/css" href="/js/lity.css">
  <script src="/js/jquery.flexnav.js" type="text/javascript"></script>
   <script>
  $(function(){
  $(".flexnav").flexNav();
  });
  </script>
  <script>
  $("document").ready(function(){
    $('.multiple-items').slick({
      infinite: true,
      dots:false,
      slidesToShow: 3,
  	autoplay: true,
      slidesToScroll: 1,
  			responsive: [{
  			breakpoint: 1024,settings: { //601px～1024pxでは3画像表示
  				slidesToShow: 3,
  				slidesToScroll: 3,
  			}
  		},
  		{
  			breakpoint: 600,settings: { //481px～600pxでは2画像表示
  				slidesToShow: 2,
  				slidesToScroll: 2
  			}
  		},
  		{
  			breakpoint: 480,settings: {//480px以下では1画像表示
  				slidesToShow: 2,
  				slidesToScroll: 2
  			}
  		}]
    });
  });
  </script>
  <script>

  $(function() {
    var $window = $(window),
        $clone = $('#smunder_yoyaku'),
        threshold = 50;

        $window.on('scroll',function(){
          if($window.scrollTop() > threshold) {
            $clone.fadeIn();
          }else{
            $clone.fadeOut();
          }
        });
  });

  </script>
  <script>
  $(function(){
  	$('.subtitle_tokucyo').click(function(){
  		$(this).toggleClass('selected');
  		$(this).next().slideToggle();
  	});
  });
  </script>
  	<script type="text/javascript">
  		$(function () {
  				$('#slider-area').sliderPro({
  						width: 700,//横幅
  						height: 394,//横幅
  						buttons: false,//ナビゲーションボタン
  						autoScaleLayers: false,//キャプションの自動変形
  						waitForLayers: true,//キャプションのアニメーションが終了してからスライドするか
  						autoplay: true,//自動再生
  						thumbnailPointer: true,//アクティブなサムネイルにマークを付ける
  						thumbnailWidth: 140,//サムネイルの横幅
  						thumbnailHeight: 100,//サムネイルの縦幅
  						arrows: true,//左右の矢印
  						slideDistance: 0,//スライド同士の距離
  						breakpoints: {
  							480: {
  								thumbnailWidth: 80,//サムネイルの横幅
  								thumbnailHeight: 70,//サムネイルの縦幅
  							}
  						}
  				});
  		});
  	</script>
  	<script type="text/javascript">
  		$(function () {
  				$('.slider-area2').sliderPro({
  						width: 500,//横幅
  						height: 280,//横幅
  						buttons: false,//ナビゲーションボタン
  						autoScaleLayers: false,//キャプションの自動変形
  						waitForLayers: true,//キャプションのアニメーションが終了してからスライドするか
  						autoplay: true,//自動再生
  						thumbnailPointer: true,//アクティブなサムネイルにマークを付ける
  						thumbnailWidth: 125,//サムネイルの横幅
  						thumbnailHeight: 90,//サムネイルの縦幅
  						arrows: true,//左右の矢印
  						slideDistance: 0,//スライド同士の距離
  						breakpoints: {
  							480: {
  								thumbnailWidth: 80,//サムネイルの横幅
  								thumbnailHeight: 70,//サムネイルの縦幅
  							}
  						}
  				});
  		});
  	</script>
  	<script src="/js/jquery.sliderPro.min.js" type="text/javascript" defer=""></script>
  	<link rel="stylesheet"  href="/js/slider-pro.css" type="text/css" >
  <link rel="stylesheet" type="text/css" href="/js/slick/slick.css">
  <link rel="stylesheet" type="text/css" href="/js/slick/slick-theme.css">
  <script type="text/javascript" src="/js/slick/slick.js"></script>

  </head>

  <body>
  <div class="sm_content">
  <header id="smunder_yoyaku">
  		<div class="smunder_btn smtel"><a href="#kouzamenu"><img src="/images/sm/kouza_btn.png" alt="講座メニュー"></a>
  			<a href="/index.php?Schedule#top"><img src="/images/sm/sche_btn.png" alt="開催スケジュール"></a>
  			<a href="/index.php?InformationSession"><img src="/images/sm/muryou_btn.png" alt="無料説明会"></a></div>
  </header>
  </div>
  <div id="header">
  <div id="header_area">
  <div id="menu"><img src="/images/sm/menu.png" width="46" height="46" alt="menu"></div>
  <!-- ◆ Head copy ◆ =====================================================  -->
  <div id="headcopy" class="qhm-head-copy">
  <h1>占い学校。占い師になる学校。東京で人気の占い学校。オンライン占い講座。占いと心理の学校。</h1>
  </div><!-- END: id:headcopy -->
    <div id="logo_area">
        <div id="logo">
        <a href="/">占いと心理の専門スクール、ハートフル</a>
	</div><div id="logo_under"><a href="/index.php?PsychologyTarot"><img src="/images/logo_under_btn.jpg" width="220" height="20" alt="心理タロット講座" /></a></div>
    </div>
  	<div id="header_freedial">
      <a href="tel:0368741288">お電話でのお問わ合せ</a></div>
  </div>
  </div>

  <div id="main_bg_area">
  	<div id="slide_area">
      <div class="slide">

  <!-- SITENAVIGATOR2 CONTENTS START -->
  	<div id="container_1">
  			<div id="slides_1">
  				<div class="slides_container"><a href="index.php?FrontPage"><img src="/swfu/d/slide1.jpg" alt="" title="" /></a>
  <a href="index.php?Dojo"><img src="/swfu/d/slide10.jpg" alt="" title="" /></a>
  <a href="index.php?TarotApri"><img src="/swfu/d/slide12.jpg" alt="" title="" /></a>
  <a href="index.php?PsychologyTarot"><img src="/swfu/d/slide0.jpg" alt="" title="" /></a>
  <a href="index.php?FortuneTellingCourse"><img src="/swfu/d/slide3.jpg" alt="" title="" /></a>
  <a href="/Public/Massociations/tindex"><img src="/swfu/d/slide13.jpg" alt="" title="" /></a>
  <a href="index.php?TarotHomeStudy"><img src="/swfu/d/slide7.jpg" alt="" title="" /></a>
  </div>
  	<a href="#" class="prev"><img src="/image/slides/arrow-prev.png" width="24" height="43" alt="Arrow Prev" /></a>
  	<a href="#" class="next"><img src="/image/slides/arrow-next.png" width="24" height="43" alt="Arrow Next" /></a>
  	</div>
  	<img src="/image/slides/frame_default.png" alt="Frame" id="frame_1" width="739" height="341" /></div>

  <!-- SITENAVIGATOR2 CONTENTS END -->
      </div>
      <div class="slide_banner"> <a href="/index.php?AboutSchool"><img src="/images/slide_banner11.jpg" alt="ハートフルが選ばれる理由" width="300" height="130" /></a>
  <a href="/index.php?InformationSession"><img src="/images/slide_banner22.jpg" alt="無料相談、無料見学" width="300" height="130" class="m10" /></a></div>
      </div>
  </div>

  <!-- SITENAVIGATOR CONTENTS END -->
  </div>
</div>
<!-- ◆ Navigator ◆ ======================================================= -->
<div id="globalnavigation2">

<!-- SITENAVIGATOR CONTENTS START -->
  <ul class="flexnav" data-breakpoint="500">
  <li class="menu_a" style=" width: 105px;"><a href="/index.php?FrontPage" title="FrontPage">ホーム<span class="eigo">HOME</span></a></li>
  <li class="menu_b" ><a href="/index.php?AboutSchool#top">スクールについて<span class="eigo">About school</span></a>
  <ul>
  <li><a href="/index.php?UranaiDebut#top#xfc2158c">卒業生の感想</a></li>
  <li><a href="/index.php?Schedule#top">講座スケジュール</a></li>
  </ul>
</li>
  <li class="menu_c"><a href="/index.php?InformationSession#top">無料説明会動画<span class="eigo">Free visitation</span></a></li>
  <li class="menu_d"><a href="/index.php?CourseNavi">講座一覧<span class="eigo">Course</span></a>
	  <ul>
  <li><a href="/index.php?FortuneTellingCourse#top">占い師お仕事講座</a></li>
  <li><a href="/index.php?PsychologyTarot#top">心理タロット講座</a></li>
  <li><a href="/index.php?Palmistry#top">手相占い講座</a></li>
  <li><a href="/index.php?WesternAstrology#top">西洋占星術講座</a></li>
  <li><a href="/index.php?Numerology#top">数秘術講座</a></li>
  <li><a href="/index.php?TarotHomeStudy#top">オンライン心理タロット講座</a></li>
  <li><a href="/index.php?Tarot1dayarot1day#top">心理タロット1日講座</a></li>
  </ul>
	  </li>
 <li class="menu_h"><a href="/index.php?Application#top">申込一覧<span class="eigo">Applicaton</span></a>
	  <ul>
  <li><a href="/index.php?Application#FortuneTellingCourse">占い師お仕事講座</a></li>
  <li><a href="/index.php?Application#PopularFortuneTeller">人気占い師育成講座</a></li>

  <li><a href="/index.php?Application#Palmistry">手相占い講座</a></li>
  <li><a href="/index.php?Application#Astrology">西洋占星術講座</a></li>
  <li><a href="/index.php?Application#Numerology">数秘術講座</a></li>
  <li><a href="/index.php?Application#Hypno">ヒプノセラピスト講座</a></li>
  <li><a href="/index.php?Application#Healing">ヒーリング療法士講座</a></li>
  <li><a href="/index.php?Application#AstrologyDice">アストロダイス</a></li>
  <li><a href="/index.php?Application#Special">特別講座</a></li>
  <li><a href="/index.php?Application#OneTarot">心理タロット一日講座</a></li>

  </ul>
	  </li>

  <li class="menu_e"><a href="/index.php?Teacher#top">講師の紹介<span class="eigo">Teacher</span></a>
	  <ul>
<li><a href="/index.php?Teacher#k1">佐藤　隆三</a></li>
  <li><a href="/index.php?Teacher#k2">伊藤　あやね</a></li>
  <li><a href="/index.php?Teacher#k3">まんまマリア</a></li>
  <li><a href="/index.php?Teacher#k4">ルビー・ラクシュミー</a></li>
<li><a href="/index.php?Teacher#k5">COCOHARU</a></li>
   <li><a href="/index.php?Teacher#k6">Sakura☆</a></li>
   <li><a href="/index.php?Teacher#k7">佐々木なこみ</a></li>

  </ul>
	  </li>
	  <li class="menu_f"><a href="/index.php?AccessSTUDIO#top">アクセス<span class="eigo">Access</span></a>
	  <ul>
  <li><a href="/index.php?AccessSTUDIO#top">ハートフル白山STUDIO</a></li>
  <li><a href="/index.php?Access#top">ハートフル白山校</a></li>
  </ul>
		  </li>
	  <li class="menu_g"><a href="/index.php?Contact#top">お問い合わせ<span class="eigo">Contact</span></a></li>
  </ul>
</div></div>
<div id="container">
<div class="content">
    <div id="main">

			<?php echo $this->Session->flash(); ?>

			<?php echo $this->fetch('content'); ?>
</div></div></div>
</body>
</html>
