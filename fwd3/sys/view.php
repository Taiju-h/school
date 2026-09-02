<?php
    require_once( "config.php" );
    require_once( "cheetan/cheetan.php" );

function action( &$c )
{
	$errmsg	= "";
	$c->set( "errmsg", $errmsg );
	$c->set( "data", $c->product->findone( '$id==' . $_GET["id"] ) );

}
?>