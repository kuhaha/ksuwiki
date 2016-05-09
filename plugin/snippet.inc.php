<?php
/* ---------------------------------------------------------------------------
 settings
--------------------------------------------------------------------------- */

// Live Snippet theme
define('SN_THEME', 'Default');// 'Default'
// Live Snippet [folder path]
define('SN_PATH', WEBROOT_DIR .  'assets/snippet/');
// Live Snippet [tag name]
define('SN_TAG_NAME', 'pre');
// Live Snippet [default language]
define('SN_DEFAULT_LANG', 'php');

define('PLUGIN_SNIPPET_USAGE', '#snippet(): Usage: #snippet(language[,sanbox]){{source code}}<br />' . "\n");
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

  if ($sn_loaded==0){ // Be sure to load only once
    $head_tags[] .= '<script type="text/javascript" src="' . SN_PATH . 'autoloader.js"></script>';
    $sn_loaded = 1;
  }

  $options = array();
  if ($argsCnt > 1){
    $options = array_slice($args, 1);
  }
  
   $rtn .= snippet_get_html($contents, $plng, $options);
  return $rtn;
}

function snippet_get_html($contents, $plng, $options){
  global $_sn_messages;
  $rtn = '';

  if($_sn_messages['sn_init']){
    $_sn_messages['sn_init'] = false;
  }
  $sn_option ='';
  foreach (array('sandbox') as $item){
    if (in_array($item, $options)){
      $sn_option .= $item . '="true" ';
    }
  }
  $rtn .= '<' . SN_TAG_NAME . ' code-language="'. $plng . '" '.$sn_option .' class="snippet cm-s-default">';
  $rtn .= htmlsc(trim($contents));
  $rtn .= '</' . SN_TAG_NAME . '>';
  return $rtn;
}