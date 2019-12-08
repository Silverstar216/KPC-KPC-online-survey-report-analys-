<?php
/**
 * Created by PhpStorm.
 * User: KMC
 * Date: 2/29/2016
 * Time: 9:47 PM
 */

defined('BASEPATH') OR exit('No direct script access allowed');
$site_url = site_url();
?>

<div class="container container-bg">
    <div id="content">
        <div id="contents">
            <div class="m_con">
                <?php
                $this->load->view('index/menu', $this->data);
                ?>
                <div class="content listWrap" style = "float:right; width: 80%">
                    <div class="sub_con">
                        <img style="max-width: 100%;" src="<?=$site_url?>images/bg/03_1.png" >                                        
                        <table class="search_t2">
                            <tr>
                                <th colspan="2" style="width:50%">SMS(90byte이내)</th>
                                <th colspan="2" style="width:50%">LMS(90 ~ 2000byte)</th>
                            </tr>
                            <tr>
                                <td>일반문자</td>
                                <td><?=$prices_1[0]?>원</td>
                                <td>일반문자</td>
                                <td><?=$prices_2[0]?>원</td>
                            </tr>
                            <tr>
                                <td>가정통신문 포함문자</td>
                                <td><?=$prices_1[1]?>원</td>
                                <td>가정통신문 포함문자</td>
                                <td><?=$prices_2[1]?>원</td>
                            </tr>
                            <tr>
                                <td>단순설문+문서포함설문</td>
                                <td><?=$prices_1[2]?>원</td>
                                <td>단순설문+문서포함설문</td>
                                <td><?=$prices_2[2]?>원</td>
                            </tr>
                        </table>

                        <div style="text-align: center; font-size: 16px;">
                            <p> 이용신청서 다운로드</p>
                        </div>
                        <div style="text-align: center; margin-top: 19px;  id="button_before" >
                        <a  href = "<?=$site_url?>index/download/3" ><img src="/images/bg/001.png" style="margin-left: 10px; " id="download_han" ></a>
                        <a  href = "<?=$site_url?>index/download/1"><img src="/images/bg/002.png" style="margin-left: 10px; " id="download_word"></a>
                        <a  href = "<?=$site_url?>index/download/2"><img src="/images/bg/003.png"style="margin-left: 10px; " id="download_pdf"></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>

