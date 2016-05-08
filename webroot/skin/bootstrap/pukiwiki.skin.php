<?php
// ------------------------------------------------------------
// Settings (define before here, if you want)

// Set site identities
$_IMAGE['skin']['logo']     = 'pukiwiki.png';
$_IMAGE['skin']['favicon']  = 'skin/bootstrap/img/favicon.png'; // Sample: 'image/favicon.ico';

$flag = isset($vars['admin']) ? 1 : 0 ;

// SKIN_DEFAULT_DISABLE_TOPICPATH
//   1 = Show reload URL
//   0 = Show topicpath
if (! defined('SKIN_DEFAULT_DISABLE_TOPICPATH'))
  define('SKIN_DEFAULT_DISABLE_TOPICPATH', $flag); // 1, 0

// Show / Hide navigation bar UI at your choice
// NOTE: This is not stop their functionalities!
if (! defined('PKWK_SKIN_SHOW_NAVBAR'))
  define('PKWK_SKIN_SHOW_NAVBAR', $flag); // 1, 0

// Show / Hide toolbar UI at your choice
// NOTE: This is not stop their functionalities!
if (! defined('PKWK_SKIN_SHOW_TOOLBAR'))
  define('PKWK_SKIN_SHOW_TOOLBAR', $flag); // 1, 0

// Show / Hide footer UI at your choice
if (! defined('PKWK_SKIN_SHOW_FOOTER'))
        define('PKWK_SKIN_SHOW_FOOTER', $flag); // 1, 0

// ------------------------------------------------------------
// Code start

// Prohibit direct access
if (! defined('UI_LANG')) die('UI_LANG is not set');
if (! isset($_LANG)) die('$_LANG is not set');
if (! defined('PKWK_READONLY')) die('PKWK_READONLY is not set');

$lang  = & $_LANG['skin'];
$link  = & $_LINK;
$image = & $_IMAGE['skin'];
$rw    = ! PKWK_READONLY;

// Decide charset for CSS
$css_charset = 'iso-8859-1';
switch(UI_LANG){
        case 'ja': $css_charset = 'Shift_JIS'; break;
}

// ------------------------------------------------------------
// Output

// HTTP headers
pkwk_common_headers();
header('Cache-control: no-cache');
header('Pragma: no-cache');
header('Content-Type: text/html; charset=' . CONTENT_CHARSET);

function _navigator($key, $value = '', $javascript = '')
{
        $lang = & $GLOBALS['_LANG']['skin'];
        $link = & $GLOBALS['_LINK'];
        if (! isset($lang[$key])) { echo 'LANG NOT FOUND'; return FALSE; }
        if (! isset($link[$key])) { echo 'LINK NOT FOUND'; return FALSE; }
        if (! PKWK_ALLOW_JAVASCRIPT) $javascript = '';

        echo '<a href="' . $link[$key] . '" ' . $javascript . '>' .
                (($value === '') ? $lang[$key] : $value) .
                '</a>';

        return TRUE;
}

// Set toolbar-specific images
$_IMAGE['skin']['reload']   = 'reload.png';
$_IMAGE['skin']['new']      = 'new.png';
$_IMAGE['skin']['edit']     = 'edit.png';
$_IMAGE['skin']['freeze']   = 'freeze.png';
$_IMAGE['skin']['unfreeze'] = 'unfreeze.png';
$_IMAGE['skin']['diff']     = 'diff.png';
$_IMAGE['skin']['upload']   = 'file.png';
$_IMAGE['skin']['copy']     = 'copy.png';
$_IMAGE['skin']['rename']   = 'rename.png';
$_IMAGE['skin']['top']      = 'top.png';
$_IMAGE['skin']['list']     = 'list.png';
$_IMAGE['skin']['search']   = 'search.png';
$_IMAGE['skin']['recent']   = 'recentchanges.png';
$_IMAGE['skin']['backup']   = 'backup.png';
$_IMAGE['skin']['help']     = 'help.png';
$_IMAGE['skin']['rss']      = 'rss.png';
$_IMAGE['skin']['rss10']    = & $_IMAGE['skin']['rss'];
$_IMAGE['skin']['rss20']    = 'rss20.png';
$_IMAGE['skin']['rdf']      = 'rdf.png';

function _toolbar($key, $x = 20, $y = 20){
  $lang  = & $GLOBALS['_LANG']['skin'];
  $link  = & $GLOBALS['_LINK'];
  $image = & $GLOBALS['_IMAGE']['skin'];
  if (! isset($lang[$key]) ) { echo 'LANG NOT FOUND';  return FALSE; }
  if (! isset($link[$key]) ) { echo 'LINK NOT FOUND';  return FALSE; }
  if (! isset($image[$key])) { echo 'IMAGE NOT FOUND'; return FALSE; }

  echo '<a href="' . $link[$key] . '">' .
  '<img src="' . IMAGE_DIR . $image[$key] . '" width="' . $x . '" height="' . $y . '" ' .
  'alt="' . $lang[$key] . '" title="' . $lang[$key] . '" />' . '</a>';
  return TRUE;
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title><?= $title ?> - <?= $page_title ?></title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="description" content="">
  <meta name="author" content="">

	<!--link rel="stylesheet/less" href="<?php echo SKIN_DIR;?>/bootstrap/less/bootstrap.less" type="text/css" /-->
	<!--link rel="stylesheet/less" href="<?php echo SKIN_DIR;?>/bootstrap/less/responsive.less" type="text/css" /-->
	<!--script src="<?php echo SKIN_DIR;?>/bootstrap/js/less-1.3.3.min.js"></script-->
	<!--append ‘#!watch’ to the browser URL, then refresh the page. -->
	
	<link href="<?php echo SKIN_DIR;?>/bootstrap/css/bootstrap.min.css" rel="stylesheet">
	<link href="<?php echo SKIN_DIR;?>/bootstrap/css/style.css" rel="stylesheet">
	<link href="<?php echo SKIN_DIR;?>/bootstrap/pukiwiki.css.php" rel="stylesheet">

  <!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
  <!--[if lt IE 9]>
    <script src="<?php echo SKIN_DIR;?>/bootstrap/js/html5shiv.js"></script>
  <![endif]-->

  <!-- Fav and touch icons -->
  <link rel="apple-touch-icon-precomposed" sizes="144x144" href="<?php echo SKIN_DIR;?>/bootstrap/img/apple-touch-icon-144-precomposed.png">
  <link rel="apple-touch-icon-precomposed" sizes="114x114" href="<?php echo SKIN_DIR;?>/bootstrap/img/apple-touch-icon-114-precomposed.png">
  <link rel="apple-touch-icon-precomposed" sizes="72x72" href="<?php echo SKIN_DIR;?>/bootstrap/img/apple-touch-icon-72-precomposed.png">
  <link rel="apple-touch-icon-precomposed" href="<?php echo SKIN_DIR;?>/bootstrap/img/apple-touch-icon-57-precomposed.png">
  <link rel="shortcut icon" href="<?= $image['favicon'] ?>">
  
	<script type="text/javascript" src="<?php echo SKIN_DIR;?>/bootstrap/js/jquery.min.js"></script>
	<script type="text/javascript" src="<?php echo SKIN_DIR;?>/bootstrap/js/bootstrap.min.js"></script>
	<script type="text/javascript" src="<?php echo SKIN_DIR;?>/bootstrap/js/scripts.js"></script>
</head>

<body>
<div class="container"><div class="row clearfix">
<div class="col-md-12 column">
<nav class="navbar navbar-default" role="navigation">
<div class="navbar-header">
 <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1"> <span class="sr-only">Toggle navigation</span><span class="icon-bar"></span><span class="icon-bar"></span><span class="icon-bar"></span></button> <a class="navbar-brand" href="<?= $link['top'] ?>"><?= $page_title ?></a>
</div>
<?php if(PKWK_SKIN_SHOW_NAVBAR) { ?>
<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
<ul class="nav navbar-nav">
	<li><?php _navigator('top') ?></li>
  <?php if ($rw) { ?>	<li><?php _navigator('new') ?></li> <?php } ?>
	<li class="dropdown">
		<a href="#" class="dropdown-toggle" data-toggle="dropdown">メニュー<strong class="caret"></strong></a>
		<ul class="dropdown-menu">
      <?php if ($is_page) { ?>
      <?php if ($rw) { ?>
				<li><?php _navigator('edit') ?></li>
        <?php if ($is_read && $function_freeze) { ?>
        <li><?php (! $is_freeze) ? _navigator('freeze') : _navigator('unfreeze') ?></li>
        <?php } ?>
      <?php } ?>
			<li><?php _navigator('diff') ?></li>
      <?php if ($do_backup) { ?>
			<li><?php _navigator('backup') ?></li>
      <?php } ?>
      <?php if ($rw && (bool)ini_get('file_uploads')) { ?>
			<li><?php _navigator('upload') ?></li>
      <?php } ?>
			<li><?php _navigator('reload') ?></li>
      <?php } ?>
			<li class="divider"></li>
			<li><?php _navigator('list') ?></li>
      <?php if (arg_check('list')) { ?>
			<li><?php _navigator('filelist') ?></li>
      <?php } ?>
			<li><?php _navigator('search') ?></li>
			<li><?php _navigator('recent') ?></li>
			<li><?php _navigator('help')   ?></li>
		</ul>
	</li>
</ul>
<form class="navbar-form navbar-right" role="search" method="post" action="?cmd=search">
<div class="form-group"><input class="form-control" type="text" name="word"></div> 
<button type="submit" class="btn btn-default">検索</button></form>
</div>
<?php } // PKWK_SKIN_SHOW_NAVBAR ?>
</nav>
<div class="page-header">
<?php if ($is_page) { ?>
<?php if(SKIN_DEFAULT_DISABLE_TOPICPATH) { ?>
<h1><?= $page ?></h1><a href="<?php echo $link['reload'] ?>"><small><?= $link['reload'] ?></small></a>
<?php } else { ?>
 <small><?php require_once(PLUGIN_DIR . 'topicpath.inc.php'); echo plugin_topicpath_inline(); ?></small>
<?php } ?>
<?php } ?>
</div></div></div>

<div class="row clearfix"><div class="col-md-9 column" id="body">
<?= $body ?></div>
<?php if (arg_check('read') && exist_plugin_convert('menu')) { ?>
<div class="col-md-3 column" id="menubar"><?php echo do_plugin_convert('menu') ?></div>
<?php } ?>
</div>

<?php if ($notes != '') { ?>
<div class="row clearfix"><div class="col-md-12 column" id="note">
<?php echo $notes ?></div></div>
<?php } ?>

<?php if ($attaches != '') { ?>
<div class="row clearfix"><div class="col-md-12 column" id="attach">
<?php echo $attaches ?></div></div>
<?php } ?>

<?php if (PKWK_SKIN_SHOW_TOOLBAR) { ?>
<div class="row clearfix"><div class="col-md-12 column" id="toolbar">
<?php _toolbar('top') ?>
<?php if ($is_page) { ?>
&nbsp;
<?php if ($rw) { ?>
<?php _toolbar('edit') ?>
<?php if ($is_read && $function_freeze) { ?>
<?php if (! $is_freeze) { _toolbar('freeze'); } else { _toolbar('unfreeze'); } ?>
<?php } ?><?php } ?>
<?php _toolbar('diff') ?>
<?php if ($do_backup) { ?>
  <?php _toolbar('backup') ?>
<?php } ?>
<?php if ($rw) { ?>
<?php if ((bool)ini_get('file_uploads')) { ?>
<?php _toolbar('upload') ?><?php } ?>
<?php _toolbar('copy') ?><?php _toolbar('rename') ?>
<?php } ?>
<?php _toolbar('reload') ?><?php } ?>
 &nbsp;
<?php if ($rw) { ?><?php _toolbar('new') ?><?php } ?>
 <?php _toolbar('list')   ?><?php _toolbar('search') ?><?php _toolbar('recent') ?>&nbsp;<?php _toolbar('help') ?>
 &nbsp; <?php _toolbar('rss10', 36, 14) ?></div></div>
<?php } ?>

<?php if ($lastmodified != '') { ?>
<div class="row clearfix"><div class="col-md-12 column" id="lastmodified">
Last-modified: <?= $lastmodified ?></div></div>
<?php } ?>

<?php if ($related != '') { ?>
<div class="row clearfix"><div class="col-md-12 column" id="related">
Link: <?php echo $related ?></div></div>
<?php } ?>
<div class="row clearfix"><div class="col-md-12 column" id="footer">
 <?php echo S_COPYRIGHT ?>.	</div></div>
</div>
</body>
</html>
