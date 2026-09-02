<?php
function is_secure( &$controller )
{
	 return false;
}

require_once( "config.php" );
require_once( "cheetan/cheetan.php" );


function action( &$c )
{
	//check name setted
	$name = $_GET['code'];
	if (!$name ) {
		$c->redirect( _getHost().'/' );
	}
	
	//check name registered
	$rs = $c->url->findone('$name=="'.$name.'"');
	if( $rs == false)
	{
		$rs_lost = $c->lost->findone('$name=="'.$name.'"');
		if($rs_lost)
		{	//update
			$rs_lost['count']++;
			$c->lost->update($rs_lost);
		}
		else
		{	//insert
			$new_lost = array(
				'name'=>$name,
				'count'=>1,
				'modified'=>time(),
			);
			$c->lost->insert($new_lost);
		}
	
		$c->redirect( _getHost().'/' );
	}
	
	//count up
	$rs['count']++;
	$c->url->update($rs);

	//redirect
	$c->redirect($rs['url']);
}

?>
