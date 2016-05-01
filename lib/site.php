<?php
////////////////////////////////////////////////
// Open site configuration database (sqlite)
//
function pkwksite_config($site){
	global $vars;

	try{
		//open the configuration database
		$db = new PDO('sqlite:' . PKWK_CONFIG_DB);
		$sql = "SELECT * FROM pkwksite WHERE id= '{$site}'" ;
		$result = $db->query($sql);
		$row = $result->fetch(PDO::FETCH_ASSOC);
		if ($row) {
			foreach ($row as $key=>$value){
				$vars['site_config'][$key] = $value;
			}
			define('SITE_SKIN_FILE', PKWK_HOME . SKIN_DIR . $row['skin'] . '/pukiwiki.skin.php');
			return TRUE;
		}
	
	}catch(PDOException $e){
		$msg = 'Exception : ' . $e->getMessage();
		die_message($msg);
	}
	return FALSE;
}

function pkwksite_accessible($site){
	global  $vars;
	$ip = $_SERVER['REMOTE_ADDR'];
 	if (preg_match('/^127./', $ip)) { //local access always ok
 		return TRUE;
 	}
	$limit = isset($vars['site_config']['limit']) ?  $vars['site_config']['limit'] : '';
//	debug($limit);
	if (empty($limit)) return TRUE;
	foreach( explode(',', $limit ) as $range ){
		$range = trim($subnet);
		if (cidr_match($ip, $range)){
			return TRUE;
		}
	}
	
	return FALSE;
}

////////////////////////////////////////////////////////
// site auth by (id, pass) againest PKWK_CONFIG_DB
//
function pkwksite_editable($site){
	return (isset($vars['site_config']['admin']) && !pkwksite_freezed() );
}

function pkwksite_freezed(){
	global $vars;
	return 	(isset($vars['site_config']['freezed']) && $vars['site_config']['freezed'])   ;
}

////////////////////////////////////////////////////////
// site auth by (id, pass) againest PKWK_CONFIG_DB
//
function pkwksite_auth($site, $pass){
	global $vars;
	try{
		//open the configuration database
		$db = new PDO('sqlite:' . PKWK_CONFIG_DB);
		$p = md5($pass);
		$sql ="select * from pkwksite where id='{$site}' and pass='{$p}' ";
		$result = $db->query($sql);
		if ($result && $result->fetch()){
			return TRUE;
		}
	}catch(PDOException $e){
		$msg = 'Exception : '.$e->getMessage();
		die_message($msg);
	}
	return FALSE;
}
