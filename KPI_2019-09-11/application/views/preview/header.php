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
    <meta name="description" content="">
    <meta name="author" content="KMC">
    <meta name="viewport" content="width=360, initial-scale=0.6" />

    <title><?= $title ?></title>
    <script src="<?= $site_url ?>include/js/jquery.min.js"></script>
    <link href="<?= $site_url ?>include/lib/bootstrap-3.2.0/css/bootstrap.css" rel="stylesheet">
    <link href="<?= $site_url ?>include/plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet">
    <?php //}

    if (!isset( $isPdfView) || $isPdfView !== 1)
    {
        ?>
        <link href="<?= $site_url ?>include/css/common.css" rel="stylesheet">
        <link href="<?= $site_url ?>include/css/components.css" rel="stylesheet">
        <!--    <link rel="stylesheet" type="text/css" href="--><?//= $site_url ?><!--include/plugins/font-awesome/css/font-awesome.min.css"/>-->
        <link rel="stylesheet" href="<?= $site_url ?>include/plugins/font-awesome-v5.3.1/css/all.min.css" integrity="sha384-mzrmE5qonljUremFsqc01SB46JvROS7bZs3IO2EmfFsd15uHvIt+Y8vEf7N7fWAU" crossorigin="anonymous">
    <?php  }

    ?>
    <?php foreach ($styles as $item): ?>
        <link href="<?php echo $site_url . $item; ?>" rel="stylesheet">
    <?php endforeach; ?>

    <!-- Just for debugging purposes. Don't actually copy these 2 lines! -->
    <!--[if lt IE 9]>
<!--    <script src="--><?//=$site_url?><!--include/lib/bootstrap-3.2.0/assets/js/ie8-responsive-file-warning.js"></script>-->
<!--    <![endif]-->
<!--    <script src="--><?//= $site_url ?><!--include/lib/bootstrap-3.2.0/assets/js/ie-emulation-modes-warning.js"></script>-->
<!---->
<!--    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
<!--    <script src="--><?//= $site_url ?><!--include/lib/bootstrap-3.2.0/assets/js/ie10-viewport-bug-workaround.js"></script>-->
<!---->
<!--    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
<!--    <!--[if lt IE 9]>-->
<!--    <script src="--><?//=$site_url?><!--include/lib/oss/html5shiv/3.7.2/html5shiv.min.js"></script>-->
<!--    <script src="--><?//=$site_url?><!--include/lib/oss/respond/1.4.2/respond.min.js"></script>-->
<!--    <![endif]-->

</head>

<body style = "padding:0;margin:0">
<!--문서파일의 종류를 보관(1.hwp변환파일  2.pdf변환파일)-->
<input id="docKind" type="hidden" value="0">
<div class="">
