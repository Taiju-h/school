<?php
//mb_language("UTF-8");

define('PKWK_DTD_XHTML_1_0_TRANSITIONAL','');
define('PKWK_DTD_HTML_5','');
define('DATA_HOME','');
if(file_exists('../../pukiwiki.ini.php'))
	require_once('../../pukiwiki.ini.php');

define('USER_NAME', $username);
define('SESSION_SAVE_PATH', $session_save_path);

error_reporting(E_ERROR | E_PARSE);

function config_database( &$db )
{
	$db->add( "", "", "", "", "", DBKIND_TEXTSQL );
}


function config_models( &$controller )
{
	$controller->AddModel( dirname(__FILE__) . "/models/admin.php" );
	$controller->AddModel( dirname(__FILE__) . "/models/url.php" );
	$controller->AddModel( dirname(__FILE__) . "/models/lost.php" );
}


function config_controller( &$controller )
{
	$controller->SetTemplateFile( "template.html" );
}


function InitTime( $time )
{
	$year	= substr( $time, 0, 4 );
	$month	= substr( $time, 4, 2 );
	$day	= substr( $time, 6, 2 );
	$hour	= substr( $time, 8, 2 );
	$minute	= substr( $time, 10, 2 );
	$second	= substr( $time, 12, 2 );
	return "$year-$month-$day $hour:$minute:$second";
}

if( !function_exists( "is_secure" ) )
{
	function is_secure( &$controller )
	{
   	 return true;
	}
}


function check_secure( &$controller )
{	
    if( isset($_SESSION['usr']) && $_SESSION['usr'] == USER_NAME )
    {

    }
    else
    {
        $controller->redirect( "../../" );
    }
}

function _getHost($init_url = '') {
		
		static $script;
		
		if( $init_url=='' )
		{
			//get
			if (isset($script)) return $script;
			
			//set automatically
	        foreach (array('SCRIPT_NAME', 'SERVER_ADMIN', 'SERVER_NAME',
	                'SERVER_PORT', 'SERVER_SOFTWARE') as $key) {
	                define($key, isset($_SERVER[$key]) ? $_SERVER[$key] : '');
	                unset(${$key}, $_SERVER[$key]);
	        }
	        
	        $str = (SERVER_PORT == 443 ? 'https://' : 'http://'); // scheme
	        $str .= SERVER_NAME; // host
	        $str .= (SERVER_PORT == 80 ? '' : ':' . SERVER_PORT); // port
	        $str .= $_SERVER['REQUEST_URI'];
	        
	        //親の親
	        $script = dirname( dirname( dirname($str.'dummy') ));
	    }
	    else
	    {
	    	$script = dirname($init_url.'dummy');
	    }
	    
        return $script;
}

function secure_session_start()
{
	$vals = parse_url( _getHost().'/index.php' );
		
	if(TRUE){
	
		$domain = $vals['host'];
		
		if($domain != 'localhost' && $domain != '127.0.0.1'){
			if(isset($vals['port']))
			{
				$domain .= ':'.$vals['port'];
			}
			$dir = str_replace('\\', '', dirname( $vals['path'] ));
			$ckpath = ($dir=='/') ? '/' : $dir.'/';
			
			if( function_exists('ini_set') ){
				ini_set('session.use_trans_sid',0);
				ini_set('session.name', QHM_SESSION_NAME.strlen($ckpath));
				ini_set('session.use_only_cookies', 1);
				ini_set('session.cookie_path', $ckpath);
				ini_set('session.cookie_domain', $domain);
				ini_set('session.cookie_lifetime', 0);
			}
		}
	}
	
	if (SESSION_SAVE_PATH != '') {
		session_save_path('../../'.SESSION_SAVE_PATH);
	}

	session_start();
}

function h($str){
	return htmlspecialchars($str);
}

function pr($v){echo '<pre>';var_dump($v);echo '</pre>';}

//main
if ( isset($script) && $script != '') {
	_getHost($script); // Init matically
}


?>