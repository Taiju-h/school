<?php
    require_once( "config.php" );
    require_once( "cheetan/cheetan.php" );

function action( &$c )
{
	$errmsg	= "";
    if( count( $_POST ) )
    {
		//validate
   		$errmsg	= "";
		$errmsg .= $c->url->validateName($c->data['url']['name'], $c->data['url']['id']);
		$errmsg .= $c->url->validateUrl($c->data["url"]['url']);
		
		if( $errmsg == "" )
		{
			if ( $_POST['mode'] == 'edit' ) {
				$c->SetViewFile( "ctp/edit_confirm.html" );
				$c->set( "url", $c->data['url'] );
			} else if ($_POST['mode'] == 'confirm' ) {
				$c->url->update( $c->data["url"] );
				$c->redirect( "." );
			}
		}
		else{
			$c->set( "errmsg", $errmsg );
			$c->set( "url", $c->data['url'] );
		}
    } 
    else
    {
    	if( !isset($_GET['id']) )
    	{
    		$c->redirect('.');
    	}
	    $c->set( "url", $c->url->findone( '$id==' . $_GET["id"] ) );
    }
}
?>