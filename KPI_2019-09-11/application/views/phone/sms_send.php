<?php
/**
 * Created by Edit plus.
 * User: CHKD
 * Date: 10/9/2018
 */
defined('BASEPATH') OR exit('No direct script access allowed');
$site_url = site_url();

?>

<div class="container container-bg">
 <div id="content">

<!--   -->
<div class="titlegroup">
        <em>보내기</em>      
         <div class="navgroup">     
                 
        </div>     
</div>
<div class="smscontentdiv">
<form name="form_sms" id="form_sms" method="post" action="/service/sms_write_send.php">

	<div class="sec01">
		<textarea>				</textarea>
		<div class="sec_btn">
		<img class="linkImg" src="<?=$site_url?>images/btn/btn_del.png'">
		<span>0/90 byte</span>
		</div>
	</div>

</form>
</div>

<!--   -->
 </div>
</div>
