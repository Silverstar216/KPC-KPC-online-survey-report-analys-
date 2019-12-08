<?php
/**
 * Author: KMC
 * Date: 10/6/15
 */

$site_url = site_url();
?>


<iframe id="print_frame" name="print_frame" class="hidden"></iframe>
<!--대기동작아이콘-->
<?php

if(!isset($disableLoadingIcon) || $disableLoadingIcon != true){
?>
<div id="wait" style="display:none;width:69px;height:89px;position:absolute;top:50%;left:50%;padding:2px;z-index: 99999">
    <img src='<?=$site_url?>images/img/demo_wait.gif' width="44" height="44" />
</div>
<?php
 }
?>
</div>
</body>
</html>