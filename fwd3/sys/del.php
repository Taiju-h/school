<?php
    require_once( "config.php" );
    require_once( "cheetan/cheetan.php" );

function action( &$c )
{
    if( count( $_POST ) )
    {
        $c->url->del( '$id==' . $_POST["id"] );
		$c->redirect( "." );
    }
    $c->set( "data", $c->url->findone( '$id==' . $_GET["id"] ) );
}
?>