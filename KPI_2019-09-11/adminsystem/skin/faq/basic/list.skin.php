<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

// add_stylesheet('css 구문', 출력순서); 숫자가 작을 수록 먼저 출력됨
//add_stylesheet('<link rel="stylesheet" href="'.$faq_skin_url.'/style.css">', 0);

//faq미리보기 css설정
if (defined('G5_IS_ADMIN')) {
    echo '<link rel="stylesheet" href="'.G5_ADMIN_URL.'/css/admin.css">'.PHP_EOL;
    echo '<link rel="stylesheet" href="'.G5_ADMIN_URL.'/css/jquery-ui.css" >'.PHP_EOL;
    echo '<link type="text/css" href="'.G5_PLUGIN_URL.'/jquery-ui/style.css">'.PHP_EOL;

} else {
    $shop_css = '';
    if (defined('_SHOP_')) $shop_css = '_shop';
//    echo '<link rel="stylesheet" href="'.$faq_skin_url.'/style.css">'.PHP_EOL;
    echo '<link rel="stylesheet" href="'.G5_DOMAIN.'/skin/board/ele_skin_basic/style.css">'.PHP_EOL;
    echo '<link rel="stylesheet" href="'.G5_CSS_URL.'/'.(G5_IS_MOBILE?'mobile':'default').$shop_css.'.css">'.PHP_EOL;
    echo '<link rel="stylesheet" href="'.G5_CSS_URL.'/ser.css">'.PHP_EOL;
}
if ($admin_href)
    echo '<div class="faq_admin"><a href="'.$admin_href.'" class="btn_admin">FAQ 수정</a></div>';
?>

<!-- FAQ 시작 { -->
<?php
if ($himg_src)
    echo '<div id="faq_himg" class="faq_img"><img src="'.$himg_src.'" alt=""></div>';

// 상단 HTML
echo '<div id="faq_hhtml">'.stripslashes($fm['fm_head_html']).'</div>';
?>
<div id="faq_sch">
<form name="faq_search_form" method="get">
    <input type="hidden" name="fm_id" value="<?php echo $fm_id;?>">
<table width="100%" cellpadding="0" cellspacing="0" style="border:1px solid #d3d3d3;" bgcolor="#f5f5f5">
  <tr>
      <td height="50" align="left"><span style = "font-weight:bold;margin-left:20px;font-size:13px;">FAQ | 자주 물으시는 질문들입니다. 궁금사항은 먼저 검색해보세요</span>
<label for="stx" class="sound_only">검색어<strong class="sound_only"> 필수</strong></label>
<input type="text" name="stx" value="<?php echo $stx;?>" required id="stx" class="frm_input required" size="30" maxlength="15"style = "height:28px;margin-left: 18px;margin-right:5px">
<input type="submit" value="검색" class="btn_submit">                      
                            </td>        
    </tr>
</table>
</form>
</div>
<div id ="faq_nayo">
<?php
if( count($faq_master_list) ){
?>
<nav id="bo_cate">
    <h2>자주하시는질문 분류</h2>
    <ul id="bo_cate_ul" style = "margin-bottom:0">
        <?php
        foreach( $faq_master_list as $v ){
            $category_msg = '';
            $category_option = '';
            if($v['fm_id'] == $fm_id){ // 현재 선택된 카테고리라면
                $category_option = ' id="bo_cate_on"';
                $category_msg = '<span class="sound_only">열린 분류 </span>';
            }
        ?>
        <li><a href="<?php echo $category_href;?>?fm_id=<?php echo $v['fm_id'];?>" <?php echo $category_option;?> ><?php echo $category_msg.$v['fm_subject'];?></a></li>
        <?php
        }
        ?>
    </ul>
</nav>
<?php } ?>

<div id="faq_wrap" class="faq_<?php echo $fm_id; ?>">
    <?php // FAQ 내용
    if( count($faq_list) ){
    ?>
    <section id="faq_con">
        <h2><?php echo $g5['title']; ?> 목록</h2>
        <ol>
            <?php
            foreach($faq_list as $key=>$v){
                if(empty($v))
                    continue;
            ?>
            <li>
                <h3><a href="#none" onclick="return faq_open(this);"><?php echo conv_content($v['fa_subject'], 1); ?></a></h3>
                <div class="con_inner">
                    <?php echo conv_content($v['fa_content'], 1); ?>
<!--                    <div class="con_closer"><button type="button" class="closer_btn">닫기</button></div>-->
                </div>
            </li>
            <?php
            }
            ?>
        </ol>
    </section>
    <?php

    } else {
        if($stx){
            echo '<p class="empty_list" style = "margin-top:20px;text-align: center;">검색된 게시물이 없습니다.</p>';
        } else {
            echo '<div class="empty_list" style = "margin-top:20px;text-align: center">등록된 FAQ가 없습니다.';
            if($is_admin)
                echo '<br><a href="'.G5_ADMIN_URL.'/faqmasterlist.php">FAQ를 새로 등록하시려면 FAQ관리</a> 메뉴를 이용하십시오.';
            echo '</div>';
        }
    }
    ?>
</div>
</div><!--faq_nayo-->
<?php echo get_paging($page_rows, $page, $total_page, $_SERVER['PHP_SELF'].'?'.$qstr.'&amp;page='); ?>

<?php
// 하단 HTML
echo '<div id="faq_thtml">'.stripslashes($fm['fm_tail_html']).'</div>';

if ($timg_src)
    echo '<div id="faq_timg" class="faq_img"><img src="'.$timg_src.'" alt=""></div>';
?>
<!-- } FAQ 끝 -->

<?php
if ($admin_href)
    echo '<div class="faq_admin"><a href="'.$admin_href.'" class="btn_admin">FAQ 수정</a></div>';
?>
<script src="<?= Main_DOMAIN ?>include/lib/jquery/jquery-1.11.1.min.js"></script>
<script src="<?php echo G5_JS_URL; ?>/viewimageresize.js"></script>
<script>
$(function() {
    $(".closer_btn").on("click", function() {
        $(this).closest(".con_inner").slideToggle();
    });
});

function faq_open(el)
{
    var con = $(el).closest("li").find(".con_inner");

    if(con.is(":visible")) {
        con.slideUp();
    } else {
        $("#faq_con .con_inner:visible").css("display", "none");

        con.slideDown(
            function() {
                // 이미지 리사이즈
                // $con.viewimageresize2();
            }
        );
    }

    return false;
}
</script>