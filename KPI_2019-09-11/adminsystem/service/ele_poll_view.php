<?php
define('G5_IS_SERVICE', true);
include_once('../common.php');
$pgMNo = 8;
$pgMNo1 = $ew;
//$total_cnt = $sc;
if($is_guest)  
alert('회원이시라면 로그인 후 이용해 보십시오.', 
    G5_BBS_URL.'/login.php?url='.urlencode(G5_URL.'/serv.php?m1='.$pgMNo.'&m2='.$pgMNo1));

include_once('../_head.php');
include_once('./epoll_func.php');    
          $res = sql_fetch("select * from epoll_master where eplm_ukey='{$ep}' and eplm_mbid='{$member['mb_no']}' ");

    if (!$res)   {
        alert('존재 하지 않는 문서입니다.',G5_URL);
    }    
    $eplm_ukey = $res['eplm_ukey'];
    $eplm_mbid = $res['eplm_mbid'];
    $m_title       = $res['eplm_title'];
    $eplm_qcnt  = $res['eplm_qcnt'];
    $polltype      = $res['eplm_gubn']; 
    $as_type     = $res['eplm_type']; 
    $info_bArr = get_detail_info_before($as_type);
    //echo $eplm_ukey.'/'.$eplm_mbid.'/'.$m_title.'/질문 수 : '.$eplm_qcnt.'/'.$polltype.'<br>';
    $totres = sql_fetch("select count(distinct epls_usms) as total_cnt from epoll_answer where epls_ukey = '{$ep}' and epls_usms is not null");   
    if (!$totres)   {
        $total_cnt = $sc;
    } else {
        $total_cnt = $totres['total_cnt'];
    }
?>
<div class="titlegroup">
<?php if ($pgMNo1 == 4) { ?>    
     <em>회신문서관리</em>    
<?} else { ?>         
     <em>설문조사결과</em>   
        <?}  ?>        
</div>
<!-- 휴대폰번호 -->
<div class="phonegroup">
<div class="phonegroupin">
<div class="phonegroupwrap">
<div id="sub_content_narrow">
    <input type="hidden" name="eplm_ukey" id="eplm_ukey" value='<?=$eplm_ukey?>'>           
    <div id="poll_make_pan">    
    <div class="btn_add01 btn_adds">        
        <?php if ($polltype == 1) { ?>
        <a href="/service/ele_poll_view_detail.php?ep=<?=$eplm_ukey?>&ew=<?=$pgMNo1?>&sc=<?=$total_cnt?>&page=<?=$page?>">Excel로 받기</a>        
        <a href="/service/ele_poll_no_answer.php?ep=<?=$eplm_ukey?>&ew=<?=$pgMNo1?>&sc=<?=$total_cnt?>&page=<?=$page?>">미응답전화번호</a>
        <?php } elseif ($polltype == 2) { ?>
        <a href="/service/ele_poll_view_detail2.php?ep=<?=$eplm_ukey?>&ew=<?=$pgMNo1?>&sc=<?=$total_cnt?>&page=<?=$page?>">Excel로 받기</a>        
        <?php } ?>
        <a href="/serv.php?m1=8&m2=<?=$pgMNo1?>&page=<?=$page?>">목록</a>
    </div>
        <div id="poll_m_title"><?=$m_title?></div></div>
<?php   
    $qindex = 0;
    for ($idx=0;$idx<$eplm_qcnt;$idx++){
        $eplh_ilbh = $idx+1;    
$qst_qry_text = "select *,(case when (eplh_dup > 0) then  ".
    "(select sum(".
       "case when (epls_ilbh > 0) then (case when (epls_etxt = '') then 1 else 2 end)  else (case when (epls_etxt = '') then 0 else 1 end) end".
      ") from epoll_answer where epls_ukey = eplh_ukey and epls_ilbh =eplh_ilbh) else '{$total_cnt}' end) et_cnt ";
$qst_qry_text = $qst_qry_text."from epoll_question where eplh_ukey='{$eplm_ukey}' and eplh_ilbh = '{$eplh_ilbh}' "; 
                    $resq = sql_fetch($qst_qry_text);
        //echo "select *  from epoll_question where eplh_ukey='{$eplm_ukey}' and eplh_ilbh = '{$eplh_ilbh}' <br>";
                     if (!$resq['eplh_ilbh']) continue;
        $eplh_title = $resq['eplh_title'];
        $eplh_title = ($idx+1).'. '.$eplh_title;
        $eplh_acnt    = $resq['eplh_acnt'];
        $eplh_chk     = $resq['eplh_chk'];
        //echo $eplh_title.'/'.$eplh_acnt.'/'.$eplh_chk.'<br>';             
        $json_arr[$qindex]['qtitle'] = $eplh_title; 
        $dupCheck = $resq['eplh_dup'];   
        $rTotalcnt = $total_cnt;     
        if ($dupCheck > 1) { $rTotalcnt = $resq['et_cnt'];    }
?>      
    <div class="tbl_head01 tbl_wrap">
        <table >
            <thead>
            <tr>
            <th scope="col" colspan = "2"><?=$eplh_title?>
            </th>     
                                <th scope="col" colspan = "2" ><?php if($rTotalcnt> 0) { ?><button type="button" class="piegraph" onclick="ele_show_graph('<?=$qindex?>','p');"><span class="sound_only">파이그래프</span></button><button type="button" class="bargraph" id="btngragh<?=$qindex?>" onclick="ele_show_graph('<?=$qindex?>','b');"><span class="sound_only">막대그래프</span></button><?php } ?>총 응답 : <?=$rTotalcnt?> </th>
            </tr>
            </thead>
            <tbody class="question_ele">                            
<?php
$asIndex = 0;
        for($jdx=0;$jdx<$eplh_acnt;$jdx++){
            $epla_asbh = $jdx+1;
$sql_text = "select *, ";
$sql_text = $sql_text."(select count(epls_ukey) from epoll_answer ";
$sql_text = $sql_text."where epls_ukey = epla_ukey and epls_ilbh = epla_ilbh and epls_asbh = {$epla_asbh} and epls_usms is null) as d_respons_su,";
$sql_text = $sql_text."(select count(epls_ukey) from epoll_answer where epls_ukey = epla_ukey and epls_ilbh = epla_ilbh ";
$sql_text = $sql_text."and epls_etxt <> '' and epls_usms is null and ((epls_asbh is null) or (epls_asbh = '') or (epls_asbh < 1))) as d_gita ,";
$sql_text = $sql_text."(select count(distinct epls_usms) from epoll_answer where epls_ukey = epla_ukey and epls_ilbh = epla_ilbh ";
$sql_text = $sql_text."and epls_usms is not null and epls_asbh = {$epla_asbh} ) as respons_su,";
$sql_text = $sql_text."(select count(distinct epls_usms) from epoll_answer where epls_ukey = epla_ukey and epls_ilbh = epla_ilbh ";
$sql_text = $sql_text."and epls_usms is not null and epls_etxt <> '' and ((epls_asbh is null) or (epls_asbh = '') or (epls_asbh < 1))) as gita ";
$sql_text = $sql_text."from epoll_qahist where epla_ukey='{$eplm_ukey}' and epla_ilbh = '{$eplh_ilbh}' and epla_asbh = '{$epla_asbh}' ";

            $resa = sql_fetch($sql_text );
                                if (!$resa['epla_asbh']) continue;
            $epla_asbh = $epla_asbh;
            $epla_title = $resa['epla_title'];
        $Scnt     = $resa['d_respons_su'] +$resa['respons_su'];
        $SGcnt  = $resa['d_gita'] +$resa['gita'];       
        if ($total_cnt == 0) {
            $r1 = "0.0";
            $r2 = "0.0";            
        } else {
            $r1 = number_format(100*$Scnt /$total_cnt ,1);
            $r2 = number_format(100*$SGcnt /$total_cnt ,1);
        }
        $tmparr['atitle'] = $epla_title; 
        $tmparr['acount'] = $Scnt; 
        $json_arr[$qindex][$asIndex]['answer'] = $tmparr;
        $asIndex++;
?>
            <tr class='answerp'>
                <td class="td_num"><?=$epla_asbh.' ) '?></td>               
                <td class="td_subject"><?=$epla_title?></td>
                                            <td class="td_snum"><?=$Scnt?></td>
                                            <td class="td_num"><?=$r1?>%</td>                                            
            </tr>
                                <tr >
                                    <td colspan= "4" class="poll_result_graph">
                                            <span style="width:<?=$r1?>%"></span>
                                    </td>
                                </tr>            
<?php       
        if ($polltype == 1) { 
            $gita_qry_count = sql_fetch("select count(*) as total_cnt from epoll_answer where epls_ukey='{$eplm_ukey}' and epls_ilbh = '{$eplh_ilbh}' and epls_asbh  = '{$epla_asbh}' ");
            if ($gita_qry_count) {
                   $ccnt = $gita_qry_count['total_cnt'];
            } else {
                    $ccnt = 0;
            }            
            //if ($member['mb_no'] == 8) echo $ccnt." : select count(*) as total_cnt from epoll_answer where epls_ukey='{$eplm_ukey}' and epls_ilbh = '{$eplh_ilbh}' and epls_asbh  = '{$epla_asbh}'<br>";
            $gita_qry_text = "select * ";  
            $gita_qry_text = $gita_qry_text."from epoll_answer where epls_ukey='{$eplm_ukey}' and epls_ilbh = '{$eplh_ilbh}' and epls_asbh  = '{$epla_asbh}' "; 
            //if ($member['mb_no'] == 8) echo $gita_qry_text.'<br>'; 
            $gita_qry = sql_query($gita_qry_text);
            if ($ccnt < 6) {
                    while ($g_resq = sql_fetch_array($gita_qry)) 
                    {
 ?>
<tr >
                                    <td colspan= "4" class="answert" >
                                            <?php echo return_real_poll_info($info_bArr,$g_resq['epls_info']); ?>
                                    </td>
 </tr> 
<?php            }//while end
            } else {
                $answer_list = '';
                while ($g_resq = sql_fetch_array($gita_qry)) 
                {   
                        $answer_list .= return_real_poll_info($info_bArr,$g_resq['epls_info']).'<br>';
                }   
?>
<tr >
                                    <td colspan= "4" class="answert" >
                                        <div style="overflow-y: auto; height:50px;">
                                        <?php echo $answer_list ?>
                                        </div>
                                     </td>
</tr>                                      
<?php                         
            }
        }// if end
    } 
    if ($eplh_chk=='Y') {   
?> 
            <tr class="answert">   
                <td class="td_date" >기타의견</td>
                <td class="td_subject" ></td>
                                            <td class="td_snum"><?=$SGcnt?></td>                
                                           <td class="td_num"><?=$r2?>%</td>
            </tr>
                                <tr >
                                    <td colspan= "4" class="poll_result_graph">
                                            <span style="width:<?=$r2?>%"></span>
                                    </td>
                                </tr>      
<?php       

            $gita_qry_count = sql_fetch("select count(*) as total_cnt from epoll_answer where epls_ukey='{$eplm_ukey}' and epls_ilbh = '{$eplh_ilbh}' and epls_etxt <> '' and epls_etxt is not null ");
            if ($gita_qry_count) {
                   $ccnt = $gita_qry_count['total_cnt'];
            } else {
                    $ccnt = 0;
            }

/*            $tmparr['atitle'] = '기타 의견'; 
            $tmparr['acount'] = $ccnt; 
            $json_arr[$qindex][$asIndex]['answer'] = $tmparr;
            $asIndex++;
*/            
            $gita_qry_text = "select * ";  
            $gita_qry_text = $gita_qry_text."from epoll_answer where epls_ukey='{$eplm_ukey}' and epls_ilbh = '{$eplh_ilbh}' and epls_etxt <> '' and epls_etxt is not null "; 

            $gita_qry = sql_query($gita_qry_text);
            if ($ccnt > 5) {
                        $tmpText = '';
                        while ($g_resq = sql_fetch_array($gita_qry)) 
                        {
                                if ($polltype == 1) {   
                                    $tmpText .= return_real_poll_info($info_bArr,$g_resq['epls_info']).': '.$g_resq['epls_etxt'].'<br>';       
                                } else {
                                    $tmpText .= $g_resq['epls_etxt'].'<br>';
                                }                
                        }
?>
<tr >
                                    <td colspan= "4"  class="answert">
                                        <div style="overflow-y: auto; height:100px;">
                                            <?php echo $tmpText;?>
                                        </div>
                                    </td>
 </tr> 
<?php                        
           } else {// count < 6
            $tmpText = '';
            while ($g_resq = sql_fetch_array($gita_qry)) 
            {
                    if ($polltype == 1) {           
                            $tmpText = return_real_poll_info($info_bArr,$g_resq['epls_info']);
                    } else {
                        $tmpText = '';
                    }
 ?>
<tr >
                                    <td colspan= "4"  class="answert" >
                                            <?=$tmpText.$g_resq['epls_etxt']?>
                                    </td>
 </tr> 
<?php                     
            }
?>
<?php            
           }
}   ?>
            </tbody>            
        </table>
        <div class="line_dir"></div>
    </div>
<?php $qindex++;} ?>  
</div>
<div id="graph_pan"></div>
</div>
</div>
</div>
    <!--[if lte IE 8]><script language="javascript" type="text/javascript" src="<?php echo G5_JS_URL ?>/flot/excanvas.min.js"></script><![endif]-->
    <script language="javascript" type="text/javascript" src="<?php echo G5_JS_URL ?>/flot/jquery.flot.min.js"></script>
    <script language="javascript" type="text/javascript" src="<?php echo G5_JS_URL ?>/flot/jquery.flot.pie.min.js"></script>
    <script language="javascript" type="text/javascript" src="<?php echo G5_JS_URL ?>/flot/jquery.flot.categories.min.js"></script>

<SCRIPT TYPE="text/javascript">


    var question_arr = [];
    var answer_title_arr = [];
    var answer_data_arr = [];
<?php 
        for($idx=0;$idx<count($json_arr);$idx++){
?>
            question_arr[<?=$idx?>] = "<?=$json_arr[$idx]['qtitle']?>";
            answer_title_arr[<?=$idx?>] = [];
            answer_data_arr[<?=$idx?>] = [];
<?php            
            for ($jdx=0;$jdx<count($json_arr[$idx])-1;$jdx++){
?>                            
                answer_title_arr[<?=$idx?>][<?=$jdx?>] = "<?=$json_arr[$idx][$jdx]['answer']['atitle']?>";
                answer_data_arr[<?=$idx?>][<?=$jdx?>] = "<?=$json_arr[$idx][$jdx]['answer']['acount']?>";
<?php                
            }
        }
?>


function getPosition(element) {
var xPosition = 0;
    var yPosition = 0;
  
    while(element) {
        xPosition += (element.offsetLeft - element.scrollLeft + element.clientLeft);
        yPosition += (element.offsetTop - element.scrollTop + element.clientTop);
        element = element.offsetParent;
    }
    return { x: xPosition-470, y: yPosition-20 };
}

function ele_show_graph(qindex,gtype){
    ele_hide_graph();
    if (question_arr. length <= 0) {return;}
    var graphHtml = '<div style="border:2px solid #ccc;">'+
                                    '<div>'+
                                            '<div class="btn_add01 btn_adds"><a href="javascript:;" onclick="ele_hide_graph();">닫기</a></div>'+
                                    '</div>'+
                                    '<div id="chartTitle">'+question_arr[qindex]+'</div>'+
                                    '<div id="chartDisplay"></div>'+
                            '</div>';
    var graphWin = $(graphHtml).attr('id', 'elettergraphWin');

    var wonObj = document.querySelector("#btngragh"+qindex); 
    var winPos = getPosition(wonObj);
    graphWin.appendTo('#graph_pan');   
    graphWin.css({'position':'absolute','left':winPos.x, 'top':winPos.y, 'width':'500px', 'height':'600px', 'text-align':'center','z-index':'9999','background':'#fff','padding':'15px'});        
    $('#chartDisplay').css({'width':'100%', 'height':'90%','overflow-y':'auto'});        
    $('#chartTitle').css({'width':'100%', 'height':'50px',"font-size":"1.2em","font-weight":"bold"});        
    //$('#chartDisplay').html(chtml);
    if (gtype=='p'){
        pie_chart(qindex);
    } else {
        bar_chart(qindex);
    }
}

function pie_chart(qindex){
        var placeholder = $("#chartDisplay");    
            var data = [],
            series = answer_title_arr[qindex].length;
            for (var i = 0; i < series; i++) {
                data[i] = {
                    label: answer_title_arr[qindex][i],
                    data: answer_data_arr[qindex][i]
                }
            }    
            placeholder.unbind();
            $.plot(placeholder, data, {
                series: {
                    pie: { 
                        show: true,
                        radius: 1,
                        label: {
                            show: true,
                            radius: 3/4,
                            formatter: labelFormatter,
                            background: { 
                                opacity: 0.5,
                                color: "#000"
                            }
                        }
                    }
                },
                legend: {
                    show: true
                }
            });
}

function bar_chart(qindex){
        var placeholder = $("#chartDisplay");    

        var data = new Array();
        placeholder.unbind();    
        series = answer_title_arr[qindex].length;
        for (var i = 0; i < series; i++) {
            data[i] = new Array();
            data[i][0] = answer_title_arr[qindex][i];
            data[i][1] = answer_data_arr[qindex][i];
        }    

        //    
        //var data = [ ["January", 10], ["February", 8], ["March", 4], ["April", 13], ["May", 17], ["June", 9] ];
        $.plot(placeholder, [ data ], {
            series: {
                bars: {
                    show: true,
                    barWidth: 0.6,
                     fill: 0.5,
                    align: "center"
                }
            },
            xaxis: {
                mode: "categories",
                tickLength: 0.2
            }
        });

        $("<div id='tooltip'></div>").css({
            position: "absolute",
            display: "none",
            border: "1px solid #fdd",
            padding: "2px",
            "background-color": "#fee",
            opacity: 0.80
        }).appendTo("#chartDisplay");

        $("#placeholder").bind("plothover", function (event, pos, item) {
                if (item) {
                    var x = item.datapoint[0].toFixed(2),
                        y = item.datapoint[1].toFixed(2);

                    $("#tooltip").html(item.series.label + " of " + x + " = " + y)
                        .css({top: item.pageY+5, left: item.pageX+5})
                        .fadeIn(200);
                } else {
                    $("#tooltip").hide();
                }
        });        
}
function ele_hide_graph(){
    if ($('#elettergraphWin')) $('#elettergraphWin').remove();
}    
//" +data[series]+"명 n.toFixed(2
function labelFormatter(label, series) {
    return "<div style='font-size:8pt; text-align:center; padding:2px; color:white;'>" + label + "<br/>"+series.data[0][1]+"명 "+ (series.percent).toFixed(1) + "%</div>";
}
</SCRIPT>
<?php
include_once('../_tail.php');
?>