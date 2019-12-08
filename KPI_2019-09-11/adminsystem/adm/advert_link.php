<?php
    $sub_menu = "200830";
    include_once('./_common.php');

    auth_check($auth[$sub_menu], 'w');

    $token = get_token();
    $mb = get_member($mb_id);
    $mb_link_list = get_member_link($mb_id);
    $link_where = "";
    $result_link = null;
    $mb_link_array = array();
                   for ($j=0; $link=sql_fetch_array($mb_link_list); $j++) {
                       $mb_link_array[] =$link['linked_mb_id'];
                       $link_where .= "'".$link['linked_mb_id']."',";
                   }
                   if(!empty($link_where)) {
                       $link_where = substr($link_where, 0, strlen($link_where) - 1);
                       $sql_link = "select * from {$g5['member_table']} where mb_id in (" . $link_where . ")";
                       $result_link = sql_query($sql_link);
                   }
    $sql_common = " from {$g5['member_table']} ";
    if($mb_level ==5) {
        if(!empty($link_where)) {
            $sql_search = " where (1) and mb_level=2   and  mb_leave_date='' and mb_intercept_date='' and mb_id not in (" . $link_where . ") ";
        } else {
            $sql_search = " where (1) and mb_level=2   and  mb_leave_date='' and mb_intercept_date='' ";
        }
    } else {
        if(!empty($link_where)) {
            $sql_search = " where (1) and mb_level=5  and  mb_leave_date='' and mb_intercept_date='' and mb_id not in (" . $link_where . ") ";
        } else {
            $sql_search = " where (1) and mb_level=5  and  mb_leave_date='' and mb_intercept_date='' ";
        }
    }
    if ($stx) {
        $sql_search .= " and ( ";
        switch ($sfl) {
            case 'mb_point' :
                $sql_search .= " ({$sfl} >= '{$stx}') ";
                break;
            case 'mb_level' :
                $sql_search .= " ({$sfl} = '{$stx}') ";
                break;
            case 'mb_nick' :
                $sql_search .= " ({$sfl} like '%{$stx}%') ";
                break;
            case 'mb_tel' :
            case 'mb_hp' :
                $sql_search .= " ({$sfl} like '%{$stx}') ";
                break;
            default :
                $sql_search .= " ({$sfl} like '{$stx}%') ";
                break;
        }
        $sql_search .= " ) ";
    }

    /*
    if($ss) {
        if ($ss == 7) $secret_link = true;
        else $secret_link = false;
    } else {
        $secret_link = false;
    }
    */
    $secret_link = true;

    if (!$sst) {
        $sst = "mb_datetime";
        $sod = "desc";
    }

    $sql_order = " order by {$sst} {$sod} ";

    $sql = " select count(*) as cnt {$sql_common} {$sql_search} {$sql_order} ";
    $row = sql_fetch($sql);
    $total_count = $row['cnt'];

    $rows = $config['cf_page_rows'];
    $total_page  = ceil($total_count / $rows);  // 전체 페이지 계산
    if ($page < 1) $page = 1; // 페이지가 없으면 첫 페이지 (1 페이지)
    $from_record = ($page - 1) * $rows; // 시작 열을 구함
    /*
    // 탈퇴회원수
    $sql = " select count(*) as cnt {$sql_common} {$sql_search} and mb_leave_date <> '' {$sql_order} ";
    $row = sql_fetch($sql);
    $leave_count = $row['cnt'];

    // 차단회원수
    $sql = " select count(*) as cnt {$sql_common} {$sql_search} and mb_intercept_date <> '' {$sql_order} ";
    $row = sql_fetch($sql);
    $intercept_count = $row['cnt'];*/

    $listall = '<a href="'.$_SERVER['PHP_SELF'].'?mb_id='.$mb_id.'&amp;mb_level='.$mb_level.'&amp;mb_nick='.$mb_nick.'" class="ov_listall">전체목록</a>';

    $g5['title'] = '광고단위연결 ----- '.'<label style="color:#ff0000;">'.$mb_nick.'</lable>';
    include_once('./admin.head.php');

    $sql = " select * {$sql_common} {$sql_search} {$sql_order} limit {$from_record}, {$rows} ";
    $result = sql_query($sql);

    $colspan = 16;
    ?>

    <div class="local_ov01 local_ov">
        <?php echo $listall ?>
        총 광고단위 회원수 <?php echo number_format($total_count+sizeof($mb_link_array)) ?>명중
        <label style="color: #ff0000"> 연결 <?php echo sizeof($mb_link_array); ?>명</label>
    </div>

    <form id="fsearch" name="fsearch" class="local_sch01 local_sch" method="get" style="min-width: 50%;display: inline-block;">

        <label for="sfl" class="sound_only">검색대상</label>
        <select name="sfl" id="sfl">
            <option value="mb_nick"<?php echo get_selected($_GET['sfl'], "mb_nick"); ?>>기관명</option>
            <option value="mb_name"<?php echo get_selected($_GET['sfl'], "mb_name"); ?>>이름</option>
            <option value="mb_id"<?php echo get_selected($_GET['sfl'], "mb_id"); ?>>회원아이디</option>
        </select>

        <input type="text" name="stx" value="<?php echo $stx ?>" id="stx" class="frm_input">
        <input type="hidden" name="mb_id" value="<?php echo $mb_id ?>">
        <input type="hidden" name="token" value="<?php echo $token ?>">
        <input type="hidden" name="mb_nick" value="<?php echo $mb_nick ?>">
        <input type="hidden" name="mb_level" value="<?php echo $mb_level ?>">
        <input type="submit" class="btn_submit" value="검색">

    </form>
<div class="btn_confirm01 btn_confirm" style="    float: right;">

    <a href="./advert_list.php">목록</a>
</div>
    <!--
    <?php /*if ($is_admin == 'super') { */?>
    <div class="btn_add01 btn_add">
        <a href="./member_form.php" id="member_add">회원추가</a>
    </div>
    --><?php /*} */?>

    <form name="fmemberlist" id="fmemberlist" action="./advert_link_update.php?id=<?php echo $mb_id ?>&amp;advert_mb_level=<?php echo $mb_level ?>&amp;mb_nick=<?php echo $mb_nick ?>" onsubmit="return fmemberlist_submit(this);" method="post">
        <input type="hidden" name="sst" value="<?php echo $sst ?>">
        <input type="hidden" name="sod" value="<?php echo $sod ?>">
        <input type="hidden" name="sfl" value="<?php echo $sfl ?>">
        <input type="hidden" name="stx" value="<?php echo $stx ?>">
        <input type="hidden" name="page" value="<?php echo $page ?>">
        <input type="hidden" name="w" value="<?php echo $w ?>">
        <input type="hidden" name="mb_id" value="<?php echo $mb_id ?>">
        <input type="hidden" name="token" value="<?php echo $token ?>">
        <input type="hidden" name="mb_nick" value="<?php echo $mb_nick ?>">
        <input type="hidden" name="mb_level" value="<?php echo $mb_level ?>">
        <div class="btn_list01 btn_list" style="text-align: center;font-size: 24px; color: #1259e0;">
            연결목록
        </div>
        <div class="tbl_head02 tbl_wrap">
            <table id="myTable">
                <caption><?php echo $g5['title']; ?> 목록</caption>
                <thead>
                <tr>
                    <th hidden>아이디</th>
                    <th scope="col" id="mb_list_chk" style="width:5%">
                        <label for="chkall" class="sound_only">회원 전체</label>

                    </th>
                    <th style="width:20%" scope="col" id="mb_list_id">아이디</a></th>
                    <th style="width:20%; text-align: center;" scope="col" id="mb_list_name">이름</a></th>
                    <th style="width:10%" scope="col" id="mb_list_auth">상태/권한</a></th>
                    <th style="width:40%" scope="col" id="mb_list_nick">기관명</a></th>



                </tr>
                </thead>
                <tbody>
                <?php
                for ($i=0; $row=sql_fetch_array($result_link); $i++) {
                    $bg = 'bg'.($i%2);

                    ?>
                    <tr class="<?php echo $bg; ?>">

                        <td style="text-align: center;" headers="mb_list_chk" class="td_chk" >
                            <input type="hidden" name="link_mb_id[<?php echo $i ?>]" value="<?php echo $row['mb_id'] ?>" id="mb_id_<?php echo $i ?>">
                            <label for="chk_<?php echo $i; ?>" class="sound_only"><?php echo $row['mb_name']; ?> <?php echo $row['mb_nick']; ?>님</label>
                            <input type="checkbox" name="link_chk[]" value="<?php echo $i ?>" id="link_chk_<?php echo $i ?>">
                        </td>
                        <td style="text-align: center;" headers="mb_list_id"  class="td_name sv_use"><?php echo $row['mb_id'] ?></td>
                        <td style="text-align: center;" headers="mb_list_name" class="td_mbname"><?php echo $row['mb_name']; ?></td>
                        <td headers="mb_list_auth" class="td_mbstat">
                            <?php

                             echo "정상";
                            ?>
                            <?php echo get_member_level_select("mb_level[$i]", 1, $member['mb_level'], $row['mb_level']) ?>
                        </td>
                        <td style="text-align: center;" headers="mb_list_nick" class="td_name sv_use"><div><?php echo $row['mb_nick'] ?></div></td>


                    </tr>

                    <?php
                }
                if ($i == 0)
                    echo "<tr><td colspan=\"".$colspan."\" class=\"empty_table\">자료가 없습니다.</td></tr>";
                ?>
                </tbody>
            </table>
        </div>
        <div class="btn_confirm01 btn_confirm"  >
            <input type="submit" name="act_button" class="btn_submit" value="연결해제" onclick="document.pressed=this.value">

        </div>


        <div class="btn_list01 btn_list" style="text-align: center;    margin-top: 100px; font-size: 24px; color: #1259e0;">
           추가목록
        </div>
        <div class="tbl_head02 tbl_wrap">
            <table id="myTable">
                <caption><?php echo $g5['title']; ?> 목록</caption>
                <thead>
                <tr>

                    <th scope="col" id="mb_list_chk" style="width:5%">
                        <label for="chkall" class="sound_only">회원 전체</label>

                    </th>
                    <th style="width:20%" scope="col" id="mb_list_id">아이디</a></th>
                    <th style="width:20%; text-align: center;" scope="col" id="mb_list_name">이름</a></th>
                    <th style="width:10%" scope="col" id="mb_list_auth">상태/권한</a></th>
                    <th style="width:40%" scope="col" id="mb_list_nick">기관명</a></th>



                </tr>
                </thead>
                <tbody>
                <?php
                for ($i=0; $row=sql_fetch_array($result); $i++) {

                    $bg = 'bg'.($i%2);

                    ?>
                    <tr class="<?php echo $bg; ?>">

                        <td style="text-align: center;" headers="mb_list_chk" class="td_chk" >
                            <input type="hidden" name="mb_id[<?php echo $i ?>]" value="<?php echo $row['mb_id'] ?>" id="mb_id_<?php echo $i ?>">
                            <label for="chk_<?php echo $i; ?>" class="sound_only"><?php echo $row['mb_name']; ?> <?php echo $row['mb_nick']; ?>님</label>
                            <input type="checkbox" name="chk[]"  value="<?php echo $i ?>" id="chk_<?php echo $i ?>">
                        </td>
                        <td style="text-align: center;" headers="mb_list_id"  class="td_name sv_use"><?php echo $row['mb_id'] ?></td>
                        <td style="text-align: center;" headers="mb_list_name" class="td_mbname"><?php echo $row['mb_name']; ?></td>
                        <td headers="mb_list_auth" class="td_mbstat">
                            <?php
                             echo "정상";
                            ?>
                            <?php echo get_member_level_select("mb_level[$i]", 1, $member['mb_level'], $row['mb_level']) ?>
                        </td>
                        <td style="text-align: center;" headers="mb_list_nick" class="td_name sv_use"><div><?php echo $row['mb_nick'] ?></div></td>


                    </tr>

                    <?php
                }
                if ($i == 0)
                    echo "<tr><td colspan=\"".$colspan."\" class=\"empty_table\">자료가 없습니다.</td></tr>";
                ?>
                </tbody>
            </table>
        </div>
        <div class="btn_confirm01 btn_confirm"  >
            <input type="submit" name="act_button" class="btn_submit" value="연결추가" onclick="document.pressed=this.value">

        </div>


    </form>

    <?php echo get_paging(G5_IS_MOBILE ? $config['cf_mobile_pages'] : $config['cf_write_pages'], $page, $total_page, '?'.$qstr.'&amp;mb_id='.$mb_id.'&amp;mb_level='.$mb_level.'&amp;mb_nick='.$mb_nick.'page='); ?>

    <script>
       /* $(function () {
            var table, rows, switching, i, x, y, shouldSwitch;
            table = document.getElementById("myTable");
            switching = true;
            /!* Make a loop that will continue until
            no switching has been done: *!/
            while (switching) {
                // Start by saying: no switching is done:
                switching = false;
                rows = table.rows;
                /!* Loop through all table rows (except the
                first, which contains table headers): *!/
                for (i = 1; i < (rows.length - 1); i++) {
                    // Start by saying there should be no switching:
                    shouldSwitch = false;
                    /!* Get the two elements you want to compare,
                    one from current row and one from the next: *!/
                    x = rows[i].getElementsByTagName("TD")[0];
                    y = rows[i + 1].getElementsByTagName("TD")[0];
                    // Check if the two rows should switch place:
                    if (x.innerHTML.toLowerCase() < y.innerHTML.toLowerCase()) {
                        // If so, mark as a switch and break the loop:
                        shouldSwitch = true;
                        break;
                    }
                }
                if (shouldSwitch) {
                    /!* If a switch has been marked, make the switch
                    and mark that a switch has been done: *!/
                    rows[i].parentNode.insertBefore(rows[i + 1], rows[i]);
                    switching = true;
                }
            }
        });*/
        function fmemberlist_submit(f)
        {
            if(document.pressed=="연결추가"){
                if (!is_checked("chk[]")) {
                    alert(document.pressed+" 하실 항목을 하나 이상 선택하세요.");
                    return false;
                }
            }


            if(document.pressed == "연결해제") {
                if(!confirm("연결을 정말 삭제하시겠습니까?")) {
                    return false;
                }else {
                    if (!is_checked("link_chk[]")) {
                        alert(document.pressed+" 하실 항목을 하나 이상 선택하세요.");
                        return false;
                    }
                }
            }

            return true;
        }
    </script>

    <?php
    include_once ('./admin.tail.php');
    ?>
