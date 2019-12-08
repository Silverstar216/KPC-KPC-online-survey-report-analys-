<?php
include_once ('../common.php');
include_once ('./ele_file_convert.php');
?>
 <script type="text/javascript"> 
parent.document.getElementById("upload_button").style.display = "inline"; 
parent.document.getElementById("file_up_loading").style.display = "none";
parent.document.getElementById('btn_preview').style.display = 'block';
parent.document.getElementById('upload_button').style.display='none';
parent.document.getElementById('udoc').value = '<?=$curr_ukey?>';
parent.document.getElementById('udcn').value = '<?php echo $S_url_nm ?>'; 

parent.document.getElementById("file_url_s").innerHTML = "<?php echo $S_url_nm ?>"; 
</script>
<?php
function alert_after($str) {
    echo "<script>
    parent.document.getElementById('upload_button').style.display = 'inline';    
    parent.document.getElementById('file_up_loading').style.display = 'none';
    parent.document.getElementById('udoc').value = '';    
    parent.document.getElementById('file_url_s').innerHTML = '변환전';
    </script>";
    alert_just($str);
}
?>