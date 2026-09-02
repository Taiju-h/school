<?php
    require_once( "config.php" );
    require_once( "cheetan/cheetan.php" );

function action( &$c )
{
    if( count( $_POST ) )
    {
		//validate
   		$errmsg	= "";
		$errmsg .= $c->url->validateName($c->data['url']['name']);
		$errmsg .= $c->url->validateUrl($c->data["url"]['url']);



		if( $errmsg == "" )
		{
			//set value
			$c->data["url"]["modified"] = date( "YmdHis" );
			$c->data['url']['comment'] = ( $c->data['url']['comment'] != '' ) 
				? $c->data['url']['comment'] : '--';
			$c->data['url']['count'] = ( preg_match('/^[0-9]+$/',$c->data['url']['count']) )
				? $c->data['url']['count'] : '0';
			
	        $c->url->insert( $c->data["url"] );
			$c->redirect( "." );
		}
		else{
			$c->set( "errmsg", $errmsg );
			$c->set( "url", $c->data['url'] );
		}
    }
}
?>