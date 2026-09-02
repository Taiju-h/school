<!doctype html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width">
<title>ハートフルschool blog</title>
<meta http-equiv="Pragma" content="no-cache">
<meta http-equiv="Cache-Control" content="no-cache">
<style type="text/css">
body {
	margin: 0;
	padding:0;
	font-family:"ヒラギノ角ゴ Pro W3", "Hiragino Kaku Gothic Pro", "メイリオ", Meiryo, Osaka, "ＭＳ Ｐゴシック", "MS PGothic", sans-serif;
}
	#blog {
		width: 796px;
	}
	
#blog ul {
	overflow: hidden;
	padding: 0;
	margin-top: 0;
	margin-right: 0;
	margin-bottom: 0;
	margin-left: 10px;
}

#blog li {
	float: left;
	width: 240px;
	margin-right: 20px;
	margin-top: 0px;
	margin-bottom:20px;
	font-size: 14px;
	border: 1px solid #C9C9C9;
	list-style-type: none;
	text-align: center;
	height: 256px;
}

#blog li:nth-child(3n){
	margin-right: 0;
}

#blog li a {
	display: block;
	text-decoration: none;
	color: inherit;
}

#blog li a:hover {
	opacity: 0.8;
}

#blog li p {
	text-align: left;
	margin-bottom: 0;
	padding-bottom: 0;
	line-height: 20px;
	padding: 10px 10px 0 10px;
	margin-top: 0;
}

#blog span {
	display: block;
	color: hsla(30,87%,57%,1.00);
}

.box_blog {
	width: 240px;
	height: 140px;
	overflow: hidden;
}

#blog img {
	width: 260px;
	height: auto;
}

@media screen and (max-width:480px) { 
	
	#blog {
		width: 100%;
	}
 #blog li {
	width: 44%;
	font-size: 12px;
	margin-right: 5%;
	height: 215px;
}

#blog li p {
	line-height: 16px;
	padding: 5px 5px 0 5px;
}
#blog li:nth-child(3n){
	margin-right: 5%;
}

#blog li:nth-child(2n){
	margin-right: 0;
}

#blog li:nth-child(1n){
	margin-left: 2%;
}


#blog li p {
	padding-bottom: 10px;
}
	
.box_blog {
    width: 100%;
    height: 110px;
}

#blog img {
	width: 100%;
	height: 100%;
}
}


</style>
</head>

<body>
<div id="blog">
                <ul>
        <?php
include('autoloader.php'); // autoloader.php を読み込む
$feed=new SimplePie; // インスタンス生成
$feed->enable_cache(false); // Cacheは行わない
$feed->set_feed_url('https://heartf.com/schoolblog/feed/');    // フィードしたいRSSのURL
$feed->init();    // パースを実行
$feedItems=$feed->get_items(0,6);    // 表示件数を指定（この場合5個）
foreach($feedItems as $item){
  $date = $item->get_date('Y/m/d');    // 各記事の日付
  $title = $item->get_title();    // 各記事のタイトル
  $link = $item->get_link();    // 各記事のURL
            // 記事中の1枚目の画像を取得
            if(preg_match('|src="(.*?).jpg"|i', $item->get_content(), $match)){
                $img = '<img src="'.$match[1].'.jpg">';
            } else {
                // 画像がないときの処理
                $img = '<img src="#.jpg">';
        }
echo "<li><a href='$link' target='_blank'><div class='box_blog'>$img</div>";
echo "<p><span>$date</span>";
echo "$title</p></a></li>";
}
?>
                </ul>
            </div>
</body>
</html>
