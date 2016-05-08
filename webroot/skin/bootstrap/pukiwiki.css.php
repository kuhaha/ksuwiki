<?php
// Send header
header('Content-Type: text/css');
$matches = array();
if(ini_get('zlib.output_compression') && preg_match('/\b(gzip|deflate)\b/i', $_SERVER['HTTP_ACCEPT_ENCODING'], $matches)) {
        header('Content-Encoding: ' . $matches[1]);
        header('Vary: Accept-Encoding');
}

// Default charset
$charset = isset($_GET['charset']) ? $_GET['charset']  : '';
switch ($charset) {
        case 'Shift_JIS': break; /* this @charset is for Mozilla's bug */
        default: $charset ='iso-8859-1';
}

// Media
$media   = isset($_GET['media'])   ? $_GET['media']    : '';
if ($media != 'print') $media = 'screen';

// Output CSS ----
?>
@charset "<?= $charset ?>";

div#navigator {
<?php   if ($media == 'print') { ?>
        display:none;
<?php   } ?>
}

div#menubar {
<?php   if ($media == 'print') { ?>
        display:none;
<?php   } else { ?>
        word-break:break-all;
        font-size:90%;
        overflow:hidden;
<?php   } ?>
}

div#attach {
<?php   if ($media == 'print') { ?>
        display:none;
<?php   } ?>
}

div#toolbar {
<?php   if ($media == 'print') { ?>
        display:none;
<?php   } else { ?>
        text-align:right;
<?php   } ?>
}

div#lastmodified {
        font-size:80%;
}

div#related {
<?php   if ($media == 'print') { ?>
        display:none;
<?php   } else { ?>
        font-size:80%;
<?php   } ?>
}

div#footer {
        font-size:70%;
}


div.jumpmenu {
        font-size:60%;
        text-align:right;
}

.anchor_super {
        font-size:xx-small;
        vertical-align:super;
}

