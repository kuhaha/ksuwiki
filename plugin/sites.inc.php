<?php
// PLugin for site management
// sites plugin (cmd=sites)
// or (cmd=sites&act=<act>)
// <act>= create|delete|copy|modify

function plugin_sites_action(){
  global $vars;
  global $_site_messages;

  $msg  = '';
  $body = '';

  $actions = array(// show the input form
    'create', // create a new wiki site from scrach 
    'copy',   // create a new wiki site by copy
    'modify', // modify the definition of a site (except site id)  
    'delete', // delete a site definition, move its data to a temp folder
    'list',   // list all wiki site
    'passwd'  // change password of a site
  );
  $act  = 'list'; // default action
  if (isset($vars['act']) and in_array($vars['act'], $actions)){
    $act   = $vars['act'];
  }
  $data_ready = isset($vars['dataready']) ? true : false;
  $site_id   = isset($vars['site_id'])   ? $vars['site_id'] : null;

  $body .= '<h3>' . m('manage') .'::'. m($act) . '</h3>';
  if ($data_ready) {
    $body .= plugin_sites_go($act);
  }else {
    if (in_array($act, array('delete','modify','copy','passwd')) and $site_id==null){
      $body .= 'No site id was specified';
    }else{
      $body .= plugin_sites_form($site_id, $act) ;
    }
  }
  
  return array('msg'=>$msg, 'body'=>$body);
}

function plugin_sites_form($site_id, $act='modify'){
  global $vars, $script;

  if ($act=='list'){
    return _sites_list();
  }
  $title = '';
  $detail= '';
  $admin= '';
  $skin = 'default';
  $toppage= '';

  try{
    //open the configuration database
    $db = new PDO('sqlite:' . PKWK_CONFIG_DB);
    if ($site_id!=null){
      $sql = "SELECT * FROM pkwksite WHERE id=". q($site_id) ;
      $result = $db->query($sql);
      $row = $result->fetch(PDO::FETCH_ASSOC);
      if ($row){
        foreach (array('title','detail','admin','skin','toppage') as $item){
          $$item = $row[$item]; 
        }
      }
    }
    $sql = "SELECT skin FROM pkwkskin ORDER BY skin ";
    $result = $db->query($sql);
    $row = $result->fetch(PDO::FETCH_ASSOC);
    $skin_select ='<select name="skin">';
    while ($row){
      $selected = '';
      if ($row['skin']==$skin){
        $selected = ' selected';
      }
      $skin_select .= '<option value=' .p($row['skin']) . $selected . '>' . $row['skin'] . '</option>';
      $row = $result->fetch(PDO::FETCH_ASSOC);
    }
    $skin_select .= '</select>';
  }catch(PDOException $e){
    $msg = 'Exception : '.$e->getMessage();
    die_message($msg);
  }

  foreach (array('id','id_def','','pass','pass1','pass2','title','detail','admin','skin','toppage','btn_save','btn_reset') as $item){
    $_item  = '_' . $item;  // create simple variables
    $$_item = m($item);     // in name $_id, $_id_def, ...,
  }

  $body = <<<EOD
  <form action="$script" method="post">
  <input type="hidden" name="cmd" value="sites" />
  <input type="hidden" name="act" value="$act" />
  <input type="hidden" name="dataready" value="ok"/>
  <table class="style_table">
EOD;
  $show_id  ='<tr><td class="style_td">' . $_id . '</td><td class="style_td">'. $site_id . '</td></tr>'
    .'<input type="hidden" name="site_id" value="'. $site_id .'"/>';
  $input_id ='<tr><td class="style_td">' . $_id . '</td>'
    .'<td class="style_td"><input type="text" name="site_id" value='. p($site_id) .'size="35"/></td></tr>';
  $input_pass='<tr><td class="style_td">' . $_pass . '</td>' 
    .'<td class="style_td"><input type="password" name="pass" size="35"/></td></tr>';
  $input_pass1='<tr><td class="style_td">' . $_pass1 . '</td>' 
    .'<td class="style_td"><input type="password" name="pass1" size="35"/></td></tr>';
  $input_pass2='<tr><td class="style_td">' . $_pass2 . '</td>' 
    .'<td class="style_td"><input type="password" name="pass2" size="35"/></td></tr>';
  $input_form= <<<EOD
   <tr><td class="style_td">$_title </td><td class="style_td"><input type="text" name="title" value="$title" size="60"/></td></tr>
   <tr><td class="style_td">$_detail</td><td class="style_td"><textarea name="detail" cols="50" rows="5">$detail</textarea></td></tr>
   <tr><td class="style_td">$_admin </td><td class="style_td"><input type="text" name="admin" value="$admin" size="35"/></td></tr>
   <tr><td class="style_td">$_skin </td><td class="style_td">$skin_select</td> </tr>
   <tr><td class="style_td">$_toppage </td><td class="style_td"><input type="text" name="toppage" value="$toppage" size="35"/></td></tr>
EOD;
  switch ($act){
    case 'delete' :
      $body .= $show_id  . $input_pass;
      break;
    case 'passwd' :
      $body .= $show_id  . $input_pass . $input_pass1 . $input_pass2;
      break;
    case 'modify':
      $body .= $show_id  . $input_pass . $input_form;
      break;
    case 'copy':
    case 'create':
      $body .= $input_id . $input_pass . $input_form;
      break;    
  }
  $body .= <<<EOD
  <tr><td class="style_td"></td ><td class="style_td"><input type="submit"  value="$_btn_save" /><input type="reset" value="$_btn_reset" /></td></tr>
    </table></form>
EOD;
  return $body;
}


function _sites_list(){
  global $vars, $script;

  $msg ='';
  try{
    //open the configuration database
    $db = new PDO('sqlite:' . PKWK_CONFIG_DB);
    $sql = "SELECT * FROM pkwksite" ;
    $result = $db->query($sql);
    $body .= '<table class="style_table" width="90%">' ;
    foreach (array('id','title','admin','skin') as $item){
      $body .= '<td class="style_td">' . m($item) . '</td>';
    }
    $btn_create = _img_link('site_create.png', m('create'), -1, 'create') ;
    $body .= '<td class="style_td" colspan=4><center>' .m('operation').  $btn_create . '</center></td>';
    $body .= '<td class="style_td" colspan=2>' . m('link') . '</td>';
    while($row = $result->fetch(PDO::FETCH_ASSOC)){
      $site = $row['id'];
      $body .= '<tr>';
      foreach (array('id','title','admin','skin') as $item){
        $body .= '<td class="style_td">' . $row[$item] . '</td>';
      }
      foreach (array('delete','modify','copy','passwd','open','admin') as $item){
        $body .= '<td class="style_td">' . _img_link('site_'.$item.'.png', m($item), $row['id'], $item) . '</td>';
      }
      $body .= '</tr>';
    }
  }catch(PDOException $e){
    $msg = 'Exception : '.$e->getMessage();
    die_message($msg);
  }
  $body .= '</table>';
  return $body;
}


function plugin_sites_go($act='modify'){
  global $vars;
  $site_id    = isset($vars['site_id']) ? $vars['site_id'] : null;
  $old_site    = isset($vars['old_site']) ? $vars['old_site'] : null;

  $id      = isset($vars['site_id']) ? q($vars['site_id']) : null;
  $pass    = isset($vars['pass'])  ? q(md5(trim($vars['pass']))) : null;
  $pass1   = isset($vars['pass1']) ? trim($vars['pass1']) : null;
  $pass2   = isset($vars['pass2']) ? trim($vars['pass2']) : null;
  $title   = q($vars['title']);
  $detail  = q($vars['detail']);
  $admin   = q($vars['admin']);
  $lastmod = q(date('Y-n-d'));
  $skin    = q($vars['skin']);
  $toppage = q($vars['toppage']);
  try{
    //open the configuration database
    $db = new PDO('sqlite:' . PKWK_CONFIG_DB);
    switch ($act){
      case 'passwd' :
        if ($pass1==null or $pass2==null or $pass1!==$pass2){
          die_message(m('notmatch'));
        }
        $pass1 = q(md5($pass1));
        $sql  = 'UPDATE pkwksite  SET pass=' . $pass1  ;
        $sql .= " WHERE id=$id AND pass=$pass";
        break;
      case 'modify':
        $sql  = 'UPDATE pkwksite  SET' ;
        $sql .= " title=" . $title . ",detail=" . $detail . ",admin=" . $admin ;
        $sql .= ",lastmod=" . $lastmod . ",skin=" . $skin . ",toppage=" . $toppage ;
        $sql .= " WHERE id=$id AND pass=$pass";
        break;
      case 'copy':
      case 'create':
        $sql  = 'INSERT INTO pkwksite(id,title,detail,admin,lastmod,pass,skin,toppage) VALUES';
        $sql .= "($id,$title,$detail,$admin,$lastmod,$pass,$skin,$toppage)";
        break;
      case 'delete':
        $sql  = "DELETE FROM pkwksite WHERE id=$id AND pass=$pass ";
        break;
      default:
        die_message(m('invalidact'));
    }

    $result = $db->exec($sql);
    $msg = '';
    if ($result > 0){
      $site_home = PKWK_SITE_DIR . $site_id . '/';
      $ok = true;
      switch ($act){
        case 'create':
        case 'copy' :
          $from_dir = ($act=='create') ? PKWK_DATA_DIR : PKWK_SITE_DIR . $old_site. '/';
          if (!file_exists($site_home)){
            $ok = mkdir($site_home, 0755);
            if($ok){
              foreach( array('attach','backup','cache','counter','diff','trackback','wiki') as $dir){
                $from   = $from_dir . $dir;
                $to    =  $site_home .$dir;
                $ok   = copy_r($from, $to);
                if (!ok) break;
              }
            }
          }
          break;
        case 'delete' :
          if (file_exists($site_home)){
            $temp = PKWK_SITE_DIR . '.'.$site_id . '.BAK'; // move site temperarily to .<site>.BAK
            if (file_exists($temp)){
              $ok = rmdir($temp);
            }
            $ok = rename($site_home, $temp);
          }
          break;
        default:
      }
      if ($ok){
        $msg = 'Successfully ' . $act . ' the site!';
      }else{
        $msg = 'Failed to ' . $act . ' the site!';
      }
    }else{
      $msg = 'Failed to save changes to configuration database!' ;
    }
  }catch(PDOException $e){
    $msg = 'Exception : '.$e->getMessage();
    die_message($msg);
  }
  return $msg;
}

// make an image link
function _img_link($img,  $title, $site, $act){
  $url = 'index.php?cmd=sites&act='.$act. '&site_id='.$site;
  if ($act=="open"){
    $url = PKWKSITE_ROOT . $site . '/' ;
  }
  if ($act=="admin"){
    $url = PKWKSITE_ROOT . $site . '!/' ;
  }
  if ($act=="create"){
    $url = 'index.php?cmd=sites&act='.$act;
  }
  $link  = '<a href='  .  p($url) . '>';
  $link .= '<img src=' . p(IMAGE_DIR. $img) . ' height="20px" title='. p($title) . '/></a>' ;
  return $link;
}
// double quoting a string
function p($str){  // abc --> "abc"
  return '"' . $str . '"';
}
// single quoting a string
function q($str){  // abc --> 'abc'
  return "'" . $str . "'";
}
// get message
function m($act){
  global $_site_messages;
  if (isset($_site_messages[$act])) 
    return $_site_messages[$act];
  return '';
}
?>
