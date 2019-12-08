<?php
/**
 * Author: KMC
 * Date: 10/6/15
 */

$site_url = site_url();
$polyfill = false;
if ($this->agent->is_polyfill()) {
    $polyfill = true;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="KMC">

    <title><?= $title ?></title>
    <link rel="icon" href="data:;base64,iVBORw0KGgo=">
    <link href="<?= $site_url ?>include/lib/bootstrap-3.2.0/css/bootstrap.css" rel="stylesheet">
    <link href="<?= $site_url ?>include/lib/jquery-ui-1.11.0/jquery-ui.min.css" rel="stylesheet">

    <link href="<?= $site_url ?>include/lib/slick/slick.css" rel="stylesheet">
    <link href="<?= $site_url ?>include/lib/slick/slick-theme.css" rel="stylesheet">

    <link href="<?= $site_url ?>include/lib/jcarousel/tango/skin.css" rel="stylesheet">

    <?php
    if ($polyfill) { ?>
        <link href="<?= $site_url ?>include/css/polyfill.css" rel="stylesheet">
        <link href="<?= $site_url ?>include/css/polyfill-glyph.css" rel="stylesheet">
    <?php }

    if(isset($jquery_datetimepicker) && $jquery_datetimepicker == 1) {?>
        <link href="<?= $site_url ?>include/lib/jquery.datetimepicker.css" rel="stylesheet">
    <?php }

    if (!isset( $isPdfView) || $isPdfView !== 1)
    {
    ?>

    <link href="<?= $site_url ?>include/css/common.css" rel="stylesheet">
    <link href="<?= $site_url ?>include/css/components.css" rel="stylesheet">
        <link rel="stylesheet" type="text/css" href="<?= $site_url ?>include/plugins/fancybox-3.51/jquery.fancybox-3.5.1.css" media="screen" />
    <link rel="stylesheet" href="<?= $site_url ?>include/plugins/font-awesome-v5.3.1/css/all.min.css" integrity="sha384-mzrmE5qonljUremFsqc01SB46JvROS7bZs3IO2EmfFsd15uHvIt+Y8vEf7N7fWAU" crossorigin="anonymous">
    <?php  }

    ?>
    <?php foreach ($styles as $item): ?>
        <link href="<?php echo $site_url . $item; ?>" rel="stylesheet">
    <?php endforeach; ?>


    <script src="<?= $site_url ?>include/lib/bootstrap-3.2.0/assets/js/ie-emulation-modes-warning.js"></script>

    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <script src="<?= $site_url ?>include/lib/bootstrap-3.2.0/assets/js/ie10-viewport-bug-workaround.js"></script>


    <style>
        input{
            border-width:1px;
            border-style: groove;
        }
        #myVideo {
            position: fixed;
            right: 0;
            bottom: 0;
            min-width: 100%;
            min-height: 100%;
        }
        #site_background{
            position:fixed;
            top:0;
            bottom: 0;
            min-width:100%;
            min-height:100%;
            background-image:url(<?=$site_url?>images/line_pattern.png);
        }
    </style>

</head>

<body>


    <div id="site_background"> </div>
<!--    <script>-->
<!--    var video = document.getElementById("myVideo");-->
<!--    var btn = document.getElementById("myBtn");-->
<!---->
<!--    function myFunction() {-->
<!--        if (video.paused) {-->
<!--            video.play();-->
<!--            btn.innerHTML = "Pause";-->
<!--        } else {-->
<!--            video.pause();-->
<!--            btn.innerHTML = "Play";-->
<!--        }-->
<!--    }-->
<!--</script>-->

    <div class="">
        <div class="header">
            <div class="top-header top-header-main">
                <div class="container">
                    <div class="h_top">
                        <h1 style="width:30%;"><a href="<?= $site_url ?>"><img src="<?=$site_url?>images/kpc_logo.png" class="logo-img"></a></h1>                     
                        <div class="h_right" style="width: 50%; text-align: right;">                            
                        <?php
                            if (is_signed())
                            {
                                $user_name = get_session_user_name();
                                $user_nick = get_session_user_nick();                                    
                            ?>
                            <label style="color: blue; padding-right: 10px;"><?=$user_name?>&nbsp;님</label>
                        <?php
                            if (get_session_user_level()==="10")
                            {
                        ?>
                            <button class="btn btn-default btn-sm" onclick="openAdminPage()" style="opacity:1">관리자모드</button>
                        <?php
                            }
                        ?>
                            <button class="btn btn-default btn-sm" onclick="signout()">로그아웃</button>
                        </div>                                           
                        <?php
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>
