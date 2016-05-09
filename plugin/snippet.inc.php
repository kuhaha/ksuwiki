<?php
/* ---------------------------------------------------------------------------
 settings
--------------------------------------------------------------------------- */

// Live Snippet theme
define('SN_THEME', 'Default');// 'Default'
// Live Snippet [folder path]
define('SN_PATH', WEBROOT_DIR . 'assets/snippet/');
// Live Snippet [tag name]
define('SN_TAG_NAME', 'pre'); 
// Live Snippet [default language]
define('SN_DEFAULT_LANG', 'php'); 
/* ---------------------------------------------------------------------------
 functions
--------------------------------------------------------------------------- */

function plugin_snippet_init(){
  $messages['_sn_messages'] = array(
    'sn_init' => true
  );
  set_plugin_messages($messages);
}

function plugin_snippet_convert(){
  global $head_tags ;
  global $sn_loaded ; 
  
  $args = func_get_args();
  $argsCnt = func_num_args();
  
  $rtn = '';
  
  $contents = ($argsCnt <= 0) ? '' : end($args);
  $plng = (1 < $argsCnt) ? $args[0] : SN_DEFAULT_LANG;
  $argsCnt = $argsCnt - 2;
  while( $argsCnt > 0 ){
    $sn_option .= ';' . $args[$argsCnt];
    $argsCnt --;
  }
  
  if ($sn_loaded==0){ // Be sure to load only once
    $head_tags[] .= '<script type="text/javascript" src="' . SN_PATH . 'autoloader.js"></script>';
    $sn_loaded = 1;
  }
  
  $sn_option = str_replace( "." , "," , $sn_option );
  
  $rtn .= snippet_get_html($contents, $plng, $sn_option);
  return $rtn;
}

function snippet_get_html($contents, $plng, $sn_option){
  global $_sn_messages;
  $rtn = '';
  
  if($_sn_messages['sn_init']){
    $_sn_messages['sn_init'] = false;
  }
  $rtn .= '<' . SN_TAG_NAME . ' code-language='. _q2($plng) . ' class="snippet  cm-s-default">';
  $rtn .= htmlspecialchars(trim($contents));
  $rtn .= '</' . SN_TAG_NAME . '>';
  return $rtn;
}

function _q2($s){
  return '"' . $s . '"';
}
