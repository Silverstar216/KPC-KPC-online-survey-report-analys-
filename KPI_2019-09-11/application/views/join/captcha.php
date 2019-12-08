<?php
/**
 * Created by PhpStorm.
 * User: KMC
 * Date: 2/29/2016
 * Time: 9:47 PM
 */

defined('BASEPATH') OR exit('No direct script access allowed');
$site_url = site_url();
require_once (APPPATH.  "third_party/Captcha/CaptchaBuilderInterface.php");
require_once (APPPATH.  "third_party/Captcha/PhraseBuilderInterface.php");
require_once (APPPATH.  "third_party/Captcha/CaptchaBuilder.php");
require_once (APPPATH.  "third_party/Captcha/PhraseBuilder.php");
use Gregwar\Captcha\CaptchaBuilder;
use Gregwar\Captcha\PhraseBuilder;
$phraseBuilder = new PhraseBuilder(5, '0123456789');
$captcha = new CaptchaBuilder(null, $phraseBuilder);
$captcha->build();
$_SESSION['phrase'] = $captcha->getPhrase();
?>


 <td class="tb01">자동복사방지</td>

    <input type="hidden" name="confirm_captcha" value="<?=$_SESSION['phrase']?>">
    <td class="tb02"><img src="<?php echo $captcha->inline(); ?>" /><button type="button" id="captcha_reload" onclick="refresh()"><span></span></button><input type="text" id="join_captcha" class="form-control" required name="join_captcha"></td>
