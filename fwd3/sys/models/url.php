<?php
class CUrl extends CModel
{
	var $table			= "data/url.txt";
	var $validatefunc	= array(
							"name" => "notempty",
							"url" => "notempty",
							);
	var $validatemsg	= array(
							"name" => "圧縮名を入力してください<br />",
							"url" => "転送先を設定して下さい<br />",
							);
	
	function validateUrl($url)
	{
	
		if($url == '')
			return "正しくURLを入力して下さい<br />";
		
		if( preg_match('/^(https?|ftp)(:\/\/[-_.!~*\'()a-zA-Z0-9;\/?:\@&=+\_$,%#]+)$/', 
			$url) )
		{
			return '';
		}
		
		return "正しくURLを入力して下さい<br />";
	}
	
	function validateName($name, $id='')
	{
		if($name == '')
			return "正しい圧縮名を入力してください<br />";
		
		//文字列チェック
		if(!preg_match('/^[a-zA-Z0-9]+$/', $name))
		{
			return '正しい圧縮名を入力してください<br />';
		}

		//重複がないかチェック
		$v = $this->findone('$name==\''. $name. '\'');
		if( $v )
		{
			if( $v['id'] != $id )
				return "既に {$name} は使われています。別の名前を設定して下さい<br />";
		}
		

		return '';
	}
}
?>