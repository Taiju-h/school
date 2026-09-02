<?php
    require_once( "config.php" );
    require_once( "cheetan/cheetan.php" );

function action( &$c )
{

	if( isset($_GET['id']) )
	{
		$id = $_GET['id'];
		$c->lost->del( '$id=='.$id );
	}

	$c->redirect( "lost.php" );
}
?>