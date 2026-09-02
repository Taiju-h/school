<?php
function is_secure( &$controller )
{
	 return false;
}

require_once( "config.php" );
require_once( "cheetan/cheetan.php" );


function action( &$c )
{
	$c->setTemplateFile('template_login.html');
}

?>
