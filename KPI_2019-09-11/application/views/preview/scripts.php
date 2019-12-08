<?php
/**
 * Author: KMC
 * Date: 10/6/15
 */

$site_url = site_url();

$polyfill = 0;
if ($this->agent->is_polyfill()) {
    $polyfill = 1;
}

?>

    <!-- /.modal -->
<!--    <script src="--><?//= $site_url ?><!--include/lib/jquery/jquery-1.11.1.min.js"></script>-->
<!--    <script src="--><?//= $site_url ?><!--include/lib/jquery-ui-1.11.0/jquery-ui.min.js"></script>-->
    <script src="<?= $site_url ?>include/lib/bootstrap-3.2.0/js/bootstrap.min.js"></script>

<?php //if(isset($jquery_datetimepicker) && $jquery_datetimepicker == 1) {?>
<!--    <script src="--><?//= $site_url ?><!--include/lib/jquery.datetimepicker.js"></script>-->
<?php //}?>

<!--    <script src="--><?//= $site_url ?><!--include/lib/jquery.sprintf.js"></script>-->
<!--    <script src="--><?//= $site_url ?><!--include/lib/jquery.cookie.js"></script>-->

<!--    <script src="--><?//= $site_url ?><!--include/lib/slick/slick.min.js"></script>-->

<!--    <script src="--><?//= $site_url ?><!--include/lib/jcarousel/jquery.jcarousel.min.js"></script>-->
<!--    <script src="--><?//= $site_url ?><!--include/lib/jqwidgets/gettheme.js"></script>-->
<!--    <script src="--><?//= $site_url ?><!--include/lib/jqwidgets/jqxcore.js"></script>-->
<!--    <script src="--><?//= $site_url ?><!--include/lib/jqwidgets/jqxwindow.js"></script>-->
<!--    <script src="--><?//= $site_url ?><!--include/lib/jqwidgets/jqxbuttons.js"></script>-->

<?php
if($polyfill != 1) {
    ?>
<!--    <script src="--><?//= $site_url ?><!--include/lib/imagesloaded.pkgd.min.js"></script>-->
    <?php
}
?>
<?php
$this->load->helper('my_url');
$signin_url = http2https() . site_url() . 'user/signin';
?>

    <script>
        var site_url = '<?= $site_url?>';
        //var signin_url = '<?//= $signin_url?>//';
        var server_date = '<?= date('Y-m-d');?>';
        var polyfill = <?= $polyfill?>;
        <?php
        if(isset($script_values) && is_array($script_values)) {
            foreach($script_values as $key => $value) {
                echo (sprintf('var %s = "%s";', $key, $value));
            }
        }
        ?>
    </script>

<!--    <script src="--><?//= $site_url ?><!--include/js/common.js"></script>-->
<!--    <script src="--><?//= $site_url ?><!--include/js/join.js"></script>-->
    <script>
        $(function () {
            //1. 본인인증
            $('.survey_header > span').css({'font-size' : '45px'});
            //설문제목
            $('.survey_title > p').css({'font-size' : '30px','margin-top':'60px'});
            //전화번호입력창
            $('#auth_adress').css({'font-size' : '30px','border':'1px solid #000000'});
            //확인단추
            $('.btn_auth_ok').css({'width' : '300px','height':'60px','font-size':'30px','margin-top':'30px'});

            //hwp를 변환한 파일을 포함한경우
            if($("#docKind").val() == "1") {
                console.log("dock is hwp file!");
            //2. 설문참여
                $('#enterSurvey > button').css({'width': '390px', 'height': '78px', 'font-size': '40px'});
            //3. 설문페지
                //선택항목에 대한 설정

                $('.survey-title-scope > p').css({'font-size': '45px', 'padding': '13px 26px'});
                $('#survey-comment-scope > p').css({'font-size': '32px', 'padding': '32px 26px 0 26px'});
                $('.question-index > h4').css({'font-size': '36px', 'line-height': '58px'});
                $('.question-index > label').css({'font-size': '32px'});
                $('.question-index > label > input').css({'width': '26px', 'height': '26px', 'margin-right': '20px'});
                //주관식에 대한 설정
                $('.example-input-text').css({'font-size':'24px','width': '580px', 'height': '65px', 'border': '1px solid #000000'});
                $('.example-input-text-1').css({'font-size':'24px','width': '480px', 'height': '65px', 'border': '1px solid #000000'});
                //만족도에 대한 설정
                $('.matrix-col-label').css({'font-size': '26px', 'font-weight': 'bold'});
                $('.example-fav').css({'font-size': '52px'});
                //구분기호
                $('.example-fav').css({'font-size': '52px'});
            }else{
                console.log("dock is pdf file!");
            //2. 설문참여
                $('#enterSurvey > button').css({'width' : '300px','height':'60px','font-size':'30px'});
                $('#enterAdvert > button').css({'width' : '100%','padding-top':'20px','padding-bottom': '20px','font-size':'45px'})
            //3. 설문페지
                //선택항목에 대한 설정

                $('.survey-title-scope > p').css({'font-size' : '35px','padding':'10px 20px'});
                $('#survey-comment-scope > p').css({'font-size' : '25px','padding':'25px 20px 0 20px'});
                $('.question-index > h4').css({'font-size' : '28px','line-height':'45px'});
                $('.question-index > label').css({'font-size' : '25px'});
                $('.question-index > label > input').css({'width' : '25px','height':'25px','margin-right':'15px'});
                //주관식에 대한 설정
                $('.example-input-text').css({'font-size':'24px','width' : '450px','height':'50px','border':'1px solid #000000'});
                $('.example-input-text-1').css({'font-size':'24px','width' : '350px','height':'50px','border':'1px solid #000000'});
                //만족도에 대한 설정
                $('.matrix-col-label').css({'font-size':'20px','font-weight':'bold'});
                $('.example-fav').css({'font-size':'40px'});
                //구분기호
                $('.example-fav').css({'font-size':'40px'});
            }
        });
    </script>

<?php foreach ($scripts as $item): ?>
    <script src="<?php echo $site_url . $item; ?>"></script>
<?php endforeach; ?>