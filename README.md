# ksuwiki

A wiki clone that supports mutiple wiki sites, based on Pukiwiki-1.4.7-utf8.

## Administate 

### list sites
```
<PKWK_HOME>/index.php?cmd=sites
```
### create a site
```
<PKWK_HOME>/index.php?cmd=sites&act=create
```

## Access 
### Access a site in view mode [no edit buttons]
```
<PKWK_HOME>/<SITE>/index.php
<PKWK_HOME>/<SITE>/index.php?PAGE_NAME_ENCODED
<PKWK_HOME>/<SITE>/index.php?plugin=PLUGIN_NAME
```

### Access a site in edit mode [with edit buttons]
```
<PKWK_HOME>/<SITE>!/index.php
<PKWK_HOME>/<SITE>!/index.php?PAGE_NAME_ENCODED
<PKWK_HOME>/<SITE>!/index.php?plugin=PLUGIN_NAME
```