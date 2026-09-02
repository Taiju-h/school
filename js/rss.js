//APIのモジュールを読み込み
google.load("feeds", "1");

function initialize() {
    //フィードの取得
    var feed = new google.feeds.Feed("https://heartf.com/schoolblog/category/school/feed/");

    //エントリの表示数の設定
    feed.setNumEntries(3);

    //関数の定義
    feed.load(function(result) {
        if (!result.error) {
            //読み込みが成功したときの処理
            var container = document.getElementById("feed");
            var htmlstr = "";
            for (var i = 0; i < result.feed.entries.length; i++) {

                var entry = result.feed.entries[i];

                //日付の取得
                var pdate = new Date(entry.publishedDate); //Dateクラス
                var pyear = pdate.getFullYear();   //年
                var pmonth = pdate.getMonth() + 1; //月
                var pday = pdate.getDate();        //日

                //日付を2桁表示に変更
                if (pyear < 2000) pyear += 1900;
                if (pmonth < 10) {pmonth = "0" + pmonth;}
                if (pday < 10) {pday = "0" + pday;}

                var date = pyear + "." + pmonth + "." + pday + " ";

                //画像の取得
                imgsrc = entry.content.match(/src="(.*?)"/igm);

                //html生成
                htmlstr += '<div class="rss"><a href="' + entry.link + '">';
                htmlstr += '<div class="thum"><img ' + imgsrc + ' width="120" height="90"></div>';
                htmlstr += '<span class="info">' + date + '</span>';
                htmlstr += '<br>' +'<span class="tit">' + entry.title + '</span></a></div>';
            }
            container.innerHTML = htmlstr;
        } else {
            //読み込みが失敗したときの処理
            alert(result.error.code + ":" + result.error.message);
        }
    });
}
google.setOnLoadCallback(initialize);