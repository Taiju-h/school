<?php
	require_once( "config.php" );
	require_once( "cheetan/cheetan.php" );
	
function action( &$c )
{

	$cond = "id DESC";
	
	if( isset($_GET['order']) && isset($_GET['key']) )
	{
		$cond = $_GET['key']." ".$_GET['order'];
	}
	$c->set( "datas", $c->lost->find( "", $cond ) );
}

?>