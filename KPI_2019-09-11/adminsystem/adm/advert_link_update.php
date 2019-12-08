<?php
$sub_menu = "200100";
include_once('./_common.php');

check_demo();



/*auth_check($auth[$sub_menu], 'w');*/

if ($_POST['act_button'] == "연결추가") {

    for ($i=0; $i<count($_POST['chk']); $i++)
    {
        // 실제 번호를 넘김
        $k = $_POST['chk'][$i];

        $link_mb_id = $_POST['mb_id'][$k];

        $sql = " select * from g5_member_link where mb_id='".$id."' and linked_mb_id='".$link_mb_id."'";
        $result=sql_fetch($sql);
        if(!$result['linked_mb_id']) {

            $sql = " insert into g5_member_link(mb_id,linked_mb_id) value('" . $id . "','" . $link_mb_id . "')";
            sql_query($sql);
            $sql = " select mb_nick from g5_member where mb_id='" . $link_mb_id . "'";
            $result_1 = sql_fetch($sql);
            $sql = " select mb_link_list from g5_member where mb_id='" . $id . "'";
            $result_2 = sql_fetch($sql);
            if (!$result_2['mb_link_list']) {
                $text_list = $result_1['mb_nick'];

            } else {
                $text_list = $result_2['mb_link_list'] . "," . $result_1['mb_nick'];
            }

            $sql = "update g5_member set mb_link_list ='" . $text_list . "' where mb_id='" . $id . "'";
            sql_query($sql);
            $sql = "update advert set is_connect =0 where id=".$id;
            sql_query($sql);
            if($advert_mb_level == 7){
                $sql = "select * from advert where mb_id ='".$id."' and  end_date >= SYSDATE() order by created_at desc";
                $result_3 = sql_fetch($sql);
                if($result_3['id']) {
                    $sql = "update advert set is_connect =1 where id=".$result_3['id'];
                    sql_query($sql);

                    $sql = "update advert set is_connect =0 where mb_id=".$link_mb_id;
                    sql_query($sql);

                    $sql = "select * from advert where mb_id ='".$link_mb_id."' and is_connect=1 and end_date >= SYSDATE() order by created_at desc";
                    $result_4 = sql_query($sql);
                      for ($j=2; $row=sql_fetch_array($result_4); $j++) {
                          $sql = "update advert set is_connect =1 where id=".$row['id'];
                          sql_query($sql);
                      }
                }

            } else {
                $sql = " select * from g5_member_link where linked_mb_id='".$id."'";
                $result_3=sql_fetch($sql);
                 if($result_3['mb_id']) {  // 자기를 연결한 부모가 있다면
                     $sql = "select * from advert where mb_id ='".$result_3['mb_id']."' and is_connect=1 ";
                     $result_4 = sql_fetch($sql);
                      if($result_4['id']) {//  부모배너가 없다면 배너 3개를 is_connect = 1 로 한다.
                          $sql = "select * from advert where mb_id ='".$id."' and end_date >= SYSDATE() order by created_at desc";
                          $result_5 = sql_query($sql);
                          for ($j=0; $row=sql_fetch_array($result_5); $j++) {
                              if($j == 3) {
                                  break;
                              } else {
                                  $sql = "update advert set is_connect =1 where id=".$row['id'];
                                  sql_query($sql);
                              }

                          }
                      } else {  //  부모배너가 있다면 배너 2개를 is_connect = 1 로 한다.
                          $sql = "select * from advert where mb_id ='".$id."' and end_date >= SYSDATE() order by created_at desc";
                          $result_5 = sql_query($sql);
                          for ($j=0; $row=sql_fetch_array($result_5); $j++) {
                              if($j == 2) {
                                  break;
                              } else {
                                  $sql = "update advert set is_connect =1 where id=".$row['id'];
                                  sql_query($sql);
                              }

                          }
                      }
                 } else {  // 없다면
                     $sql = "select * from advert where mb_id ='".$id."' and end_date >= SYSDATE() order by created_at desc";
                     $result_4 = sql_query($sql);
                     for ($j=0; $row=sql_fetch_array($result_4); $j++) {
                         if($j == 3) {
                             break;
                         } else {
                             $sql = "update advert set is_connect =1 where id=".$row['id'];
                             sql_query($sql);
                         }

                     }
                 }

            }
        } else {
            alert("이미 연결되여 있습니다.");
        }

    }

} else if ($_POST['act_button'] == "연결해제") {

    for ($i=0; $i<count($_POST['link_chk']); $i++)
    {
        // 실제 번호를 넘김
        $k = $_POST['link_chk'][$i];

        $link_mb_id = $_POST['link_mb_id'][$k];

        $sql = " delete from g5_member_link where mb_id='".$id."' and linked_mb_id='".$link_mb_id."'";
        sql_query($sql);
        $sql = " select mb_nick from g5_member where mb_id='" . $link_mb_id . "'";
        $result_1 = sql_fetch($sql);
        $sql = " select mb_link_list from g5_member where mb_id='" . $id . "'";
        $result_2 = sql_fetch($sql);
        if (!$result_2['mb_link_list']) {
            $text_list = "";
        } else {
            $link_list =  explode(",",$result_2['mb_link_list']);
            $text_list = "";
            foreach ($link_list as $link_item){
                if($link_item == $result_1['mb_nick']) {

                } else {
                    $text_list .= $link_item.",";
                }
            }
            $text_list = substr($text_list,0,strlen($text_list)-1);
        }

        $sql = "update g5_member set mb_link_list ='" . $text_list . "' where mb_id='" . $id . "'";
        sql_query($sql);


        if($advert_mb_level == 7){
            $sql = " select * from g5_member_link where mb_id='".$id."'";
            $result=sql_fetch($sql);
            if(!$result['mb_id']) {  // 연결된것이 하나도 없다면
                $sql = "update advert set is_connect =0 where mb_id='".$id."'";
                sql_query($sql);
                $sql = "update advert set is_connect =0 where mb_id='".$link_mb_id."'";
                sql_query($sql);

                $sql = "select * from advert where mb_id ='".$link_mb_id."' and start_date <=SYSDATE() and end_date >= SYSDATE() order by created_at desc";
                $result_1 = sql_query($sql);

                    for ($j=0; $row=sql_fetch_array($result_1); $j++) {
                        if($j == 3) {
                            break;
                        } else {
                            $sql = "update advert set is_connect =1 where id=".$row['id'];
                            sql_query($sql);
                        }
                    }

            }

        } else {
            $sql = " select * from g5_member_link where mb_id='".$id."'";
            $result=sql_fetch($sql);
            if(!$result['mb_id']) {  // 연결된것이 하나도 없다면
                $sql = "update advert set is_connect =0 where mb_id='".$id."'";
                sql_query($sql);
            }
        }

    }
}

if ($msg)
    //echo '<script> alert("'.$msg.'"); </script>';
    alert($msg);

goto_url('./advert_link.php?'.$qstr.'&amp;mb_id='.$id.'&amp;mb_level='.$advert_mb_level.'&amp;mb_nick='.$mb_nick);
?>
