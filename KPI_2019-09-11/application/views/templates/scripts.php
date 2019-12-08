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


    <script src="<?= $site_url ?>include/lib/jquery/jquery-1.11.1.min.js"></script>
    <script src="<?= $site_url ?>include/lib/jquery-ui-1.11.0/jquery-ui.min.js"></script>
    <script src="<?= $site_url ?>include/lib/bootstrap-3.2.0/js/bootstrap.min.js"></script>

<?php if(isset($jquery_datetimepicker) && $jquery_datetimepicker == 1) {?>
    <script src="<?= $site_url ?>include/lib/jquery.datetimepicker.js"></script>
<?php }?>

    <script src="<?= $site_url ?>include/lib/jquery.sprintf.js"></script>
    <script src="<?= $site_url ?>include/lib/jquery.cookie.js"></script>

    <script src="<?= $site_url ?>include/lib/slick/slick.min.js"></script>

    <script src="<?= $site_url ?>include/lib/jcarousel/jquery.jcarousel.min.js"></script>
    <script src="<?= $site_url ?>include/lib/jqwidgets/gettheme.js"></script>
    <script src="<?= $site_url ?>include/lib/jqwidgets/jqxcore.js"></script>
    <script src="<?= $site_url ?>include/lib/jqwidgets/jqxwindow.js"></script>
    <script src="<?= $site_url ?>include/lib/jqwidgets/jqxbuttons.js"></script>

    <script src="<?= $site_url ?>include/plugins/fancybox-3.51/jquery.fancybox-3.5.1.js"></script>


<?php
if($polyfill != 1) {
    ?>
    <script src="<?= $site_url ?>include/lib/imagesloaded.pkgd.min.js"></script>
    <?php
}
?>
<?php
$this->load->helper('my_url');
$signin_url = http2https() . site_url() . 'user/signin';
?>

    <script>
        var site_url = '<?= $site_url?>';
        var signin_url = '<?= $signin_url?>';
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

    <script src="<?= $site_url ?>include/js/common.js"></script>
    <script src="<?= $site_url ?>include/js/join.js"></script>

<?php foreach ($scripts as $item): ?>
    <script src="<?php echo $site_url . $item; ?>"></script>
<?php endforeach; ?>