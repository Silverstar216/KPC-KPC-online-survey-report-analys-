<?php
        if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가 
        $tmp_table = '';
        if (isset($temptable)){
            if ($temptable != '') {
              $tmp_table = 'tmp_';
            }
        }
        $Re_res = sql_fetch("select * from epoll_".$tmp_table."master where eplm_ukey='{$vcn}' ");
        if ($Re_res)   {
              $re_eplm_ukey = $Re_res['eplm_ukey'];
              $re_eplm_mbid = $Re_res['eplm_mbid'];
              $re_m_title       = $Re_res['eplm_title'];
              $re_eplm_qcnt  = $Re_res['eplm_qcnt'];
              $re_polltype      = $Re_res['eplm_gubn']; 
              $re_as_type     = $Re_res['eplm_type'];       

              if ($tmp_table == 'tmp_'){
                  $re_eplm_public    = $Re_res['eplm_public'];       
                    if ($re_eplm_public == 'Y') {
                  ?>
                      $('#save_tmp').attr('checked',true);
                  <?php }
              }                
?>
$('#m_title').attr('value','<?=$re_m_title?>');
$("#as_type > option[value=<?=$re_as_type ?>]").attr("selected", "ture");
<?php              
              for ($idx=0;$idx<$re_eplm_qcnt;$idx++){
                      $re_eplh_ilbh = $idx+1;    
                      $resq = sql_fetch("select *  from epoll_".$tmp_table."question where eplh_ukey='{$re_eplm_ukey}' and eplh_ilbh = '{$re_eplh_ilbh}' ");
                      //echo "select *  from epoll_question where eplh_ukey='{$eplm_ukey}' and eplh_ilbh = '{$eplh_ilbh}' <br>";
                      if (!$resq['eplh_ilbh']) continue;
                      $eplh_title  = $resq['eplh_title'];
                      $eplh_acnt = $resq['eplh_acnt'];
                      $eplh_chk  = $resq['eplh_chk'];    
                      $eplh_dup  = $resq['eplh_dup'];    
?>
        add_question();  
        ReSet_ID();
        var poll_pan_obj = $('.pollpan').eq(<?=$idx?>);        
        $('.quest_title').eq(<?=$idx?>).attr('value','<?=$eplh_title?>')
        if ('<?=$eplh_chk?>' == 'Y') {
              $('.extra_txt').eq(<?=$idx?>).attr('checked',true);
        } else {
              $('.extra_txt').eq(<?=$idx?>).attr('checked',false);
              if ('<?=$eplh_chk?>' == 'O'){
                  $('.no_answer').eq(<?=$idx?>).attr('checked',true);              
              } else {
                  $('.no_answer').eq(<?=$idx?>).attr('checked',false);
             }              
        }        
        $('.extra_id').eq(<?=$idx?>).attr('value','<?=$eplh_chk?>');       
        if ('<?=$eplh_dup?>' > '1') {
              $('.dup_class').eq(<?=$idx?>).attr('checked',true);
              $('.dup_id').eq(<?=$idx?>).attr('value','Y');
        } else {
              $('.dup_class').eq(<?=$idx?>).attr('checked',false);
              $('.dup_id').eq(<?=$idx?>).attr('value','N');
        }   
<?php                          
                          for($jdx=0;$jdx<$eplh_acnt;$jdx++){
                            $re_epla_asbh = $jdx+1;
                            $resa = sql_fetch("select * from epoll_".$tmp_table."qahist where epla_ukey='{$re_eplm_ukey}' and epla_ilbh = '{$re_eplh_ilbh}' and epla_asbh = '{$re_epla_asbh}' ");
                            if (!$resa['epla_asbh']) continue;
                            $epla_asbh = $epla_asbh.' ) ';
                            $epla_title = $resa['epla_title'];
?>
        add_answer(poll_pan_obj);
        ReSet_Answer(poll_pan_obj);
        poll_pan_obj.children('.pollsel').eq(<?=$jdx?>).children('.answer_title').first().attr('value','<?=$epla_title?>');
<?php                            
                            }                          
                }
        }
?>