<?php
class CAdmin extends CModel
{
	var $table			= "data/admin.txt";
	var $validatefunc	= array(
							"name" => "notempty",
							"value" => "notempty"
							);
	var $validatemsg	= array(
							"name" => "正しく情報を入力して下さい<br />",
							"value" => "正しく情報を入力して下さい<br />"
							);
							
	function getConfig() {
		$config = array();
		$array = $this->find("", "name ASC");
		foreach($array as $key => $val) {
			$config[$val['name']] = $val['value'];
		}
		return $config;
	}
}
?>