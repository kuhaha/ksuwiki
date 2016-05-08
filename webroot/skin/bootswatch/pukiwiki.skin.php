<?php
// PukiWiki - Yet another WikiWikiWeb clone.
// $Id: pukiwiki.skin.php,v 1.41 2005/01/26 13:04:08 henoheno Exp $
//
// PukiWiki default skin

// Set site logo
$_IMAGE['skin']['logo']     = 'pukiwiki.png';
$theme = 'cerulean';
$flag = isset($vars['admin']) ? 1: 0; 

// SKIN_DEFAULT_DISABLE_TOPICPATH
//   1 = Show reload URL
//   0 = Show topicpath
if (! defined('SKIN_DEFAULT_DISABLE_TOPICPATH'))
  define('SKIN_DEFAULT_DISABLE_TOPICPATH', $flag); // 1, 0

// Show / Hide navigation bar UI at your choice
// NOTE: This is not stop their functionalities!

// タイトル周りのナビゲーションバーを表示します
// 注意：この設定は表示の切り替えだけで機能を無効にするわけではありません
if (! defined('PKWK_SKIN_SHOW_NAVBAR'))
  define('PKWK_SKIN_SHOW_NAVBAR', $flag); // [1], 0

// ページ最下部のツールバーを表示します
// 注意：この設定は表示の切り替えだけで機能を無効にするわけではありません

if (! defined('PKWK_SKIN_SHOW_TOOLBAR'))
  define('PKWK_SKIN_SHOW_TOOLBAR', $flag); // [1], 0


unset($flag);
// ----
// Prohibit direct access
if (! defined('UI_LANG')) die('UI_LANG is not set');
if (! isset($_LANG)) die('$_LANG is not set');
if (! defined('PKWK_READONLY')) die('PKWK_READONLY is not set');

$lang  = & $_LANG['skin'];
$link  = & $_LINK;
$image = & $_IMAGE['skin'];
$rw    = ! PKWK_READONLY;

// Decide charset for CSS
$css_charset = 'utf-8';
switch(UI_LANG){
  case 'ja': $css_charset = 'Shift_JIS'; break;
}

// Output HTTP headers
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
    <title>Bootswatch: Spacelab</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="<?=SKIN_DIR .'bootswatch/' . $theme ?>/bootstrap.css" media="screen">
    <link rel="stylesheet" href="<?=SKIN_DIR ?>bootswatch/assets/css/bootswatch.min.css">
    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="../bower_components/html5shiv/dist/html5shiv.js"></script>
      <script src="../bower_components/respond/dest/respond.min.js"></script>
    <![endif]-->
  </head>
  <body>
    <div class="navbar navbar-default navbar-fixed-top">
      <div class="container">
        <div class="navbar-header"><a class="navbar-brand" href="<?= $link['top'] ?>"><?= $page_title ?></a></div>
        <div class="navbar-collapse collapse" id="navbar-main">
        <?php if(PKWK_SKIN_SHOW_NAVBAR) { ?>
        <ul class="nav navbar-nav">
          <li><?php _navigator('top') ?></li>
          <?php if ($rw) { ?> <li><?php _navigator('new') ?></li> <?php } ?>
          <?php if ($rw) { ?>
            <li><?php _navigator('edit') ?></li>
            <?php if ($is_read && $function_freeze) { ?>
            <li><?php (! $is_freeze) ? _navigator('freeze') : _navigator('unfreeze') ?></li>
            <?php } ?>
          <?php } ?>
          <li class="dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown">メニュー<strong class="caret"></strong></a>
          <ul class="dropdown-menu" aria-labelledby="themes">
          <?php if ($is_page) { ?>
                    <li><?php _navigator('diff') ?></li>
          <?php if ($do_backup) { ?><li><?php _navigator('backup') ?></li><?php } ?>
          <?php if ($rw && (bool)ini_get('file_uploads')) { ?><li><?php _navigator('upload') ?></li><?php } ?>
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
        <?php } // PKWK_SKIN_SHOW_NAVBAR ?>
        <form class="navbar-form navbar-right" role="search" method="post" action="?cmd=search">
        <div class="form-group"><input class="form-control" type="text" name="word"></div> 
        <button type="submit" class="btn btn-default">検索</button></form>
        </div>
    
      </div>
    </div>

    <div class="container">
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

<?php if ($lastmodified != '') { ?>
<div class="row clearfix"><div class="col-md-12 column" id="lastmodified">
Last-modified: <?= $lastmodified ?></div></div>
<?php } ?>

<?php if ($related != '') { ?>
<div class="row clearfix"><div class="col-md-12 column" id="related">
Link: <?php echo $related ?></div></div>
<?php } ?>
<div class="row clearfix"><div class="col-md-12 column" id="footer">
 <?php echo S_COPYRIGHT ?>. </div></div>
 <?php } //PKWK_SKIN_SHOW_TOOLBAR?>
</div></div>
<script src="https://code.jquery.com/jquery-1.10.2.min.js"></script>
<script src="<?= SKIN_DIR ?>bootswatch/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
<script src="<?= SKIN_DIR ?>bootswatch/assets/js/bootswatch.js"></script>
</body>
</html>
