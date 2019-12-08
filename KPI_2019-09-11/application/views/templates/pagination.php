<?php
/**
 * Author: KMC
 * Date: 10/6/15
 */

$site_url = site_url();
$total_page;
$cur_page;
$prev_page = $cur_page - 1;
if ($prev_page < 1)
    $prev_page = 1;
$next_page = $cur_page + 1;
if ($next_page > $total_page)
    $next_page = $total_page;

$class_first = '';
$class_prev = '';
$class_next = '';
$class_last = '';

if ($cur_page == 1) {
    $class_first = 'disabled';
    $class_prev = 'disabled';
}

if ($cur_page == $total_page) {
    $class_next = 'disabled';
    $class_last = 'disabled';
}

$page_counts = 9;
$arr_pages = array();

$start_page = $cur_page - (int)($page_counts / 2);
$end_page = $cur_page + (int)($page_counts / 2);
if ($start_page < 1) {
    $start_page = 1;
    if ($page_counts < $total_page)
        $end_page = $page_counts;
    else
        $end_page = $total_page;
}
if ($end_page > $total_page) {
    $end_page = $total_page;
    if ($page_counts < $total_page)
        $start_page = $total_page - $page_counts + 1;
    else
        $start_page = 1;
}

$page = $start_page;

$class_pagination = '';
if($total_page == 1)
    $class_pagination = 'hidden';

?>
var data = "<ul class='list-unstyled'><input type='hidden' id='page' value='"+selected_page+"' >";
    data +="<li><a href='javascript:page_prev()'><i class='fa fa-angle-left'></i></a></li>";
    for(i=1; i< page_count; i++){
    data+="<li id='page_"+i+"'><a  href='javascript:go_page("+i+")'>"+i+"</a></li>";
    }
    data+="<li><a  href='javascript:page_next()'><i class='fa fa-angle-right'></i></a></li></ul>";
$('.blog-pagination').html(data);

$('#page_'+selected_page).addClass('active');

<ul class="pagination pagination-sm <?=$class_pagination?>">
    <li class="<?= $class_first ?>"><a href="javascript:go_page(1)">처음</a></li>
    <li class="<?= $class_prev ?>"><a href="javascript:go_page(<?= $prev_page ?>)">이전</a></li>
    <?php
    for ($page = $start_page; $page <= $end_page; $page++) {
        $active = '';
        if($page == $cur_page)
            $active = 'active';
        echo(sprintf('<li class="%2$s"><a href="javascript:go_page(%1$d)">%1$d</a></li>', $page, $active));
    }
    ?>
    <li class="<?= $class_next ?>"><a href="javascript:go_page(<?= $next_page ?>)">다음</a></li>
    <li class="<?= $class_last ?>"><a href="javascript:go_page(<?= $total_page ?>)">마감</a></li>
</ul>