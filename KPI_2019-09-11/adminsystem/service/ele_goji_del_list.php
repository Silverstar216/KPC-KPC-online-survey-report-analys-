<?php
include_once("../common.php");
echo 'start';
if($is_guest)  { 
    echo 'not'; 
    exit;
}
$del_qry = "update edoc_master set edoc_stat = '05' where edoc_ukey = '{$ek}' and edoc_mbid = '{$member['mb_id']}' ";
sql_query($del_qry);
?>