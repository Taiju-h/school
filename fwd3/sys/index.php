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
	$c->SetViewFile( "ctp/index_.html" );
	$c->set( "datas", $c->url->find( "", $cond ) );
	$c->set( 'base_url', _getHost().'/fwd3' );
}

?>