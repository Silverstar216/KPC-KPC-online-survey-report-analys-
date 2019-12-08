/**
 * Author: KMC
 * Date: 10/6/15
 */


$(function () {
    google.charts.load('current', {'packages':['corechart']});
     google.charts.setOnLoadCallback(drawChart);

    $('.reviewlog_datepicker').datetimepicker({
        lang: 'ko',
        timepicker: false,
        format: 'Y-m-d',
        formatDate: 'Y-m-d',
        scrollMonth: false,
        scrollTime: false,
        scrollInput: false,
        onSelectDate: function() {
            $(this).trigger('close.xdsoft');
        }
    });
    $("#review_noResponse").hide();
    $("#btn_mainDetail").hide();
    $('#reviewlog_search').click(function () {
        location.href = site_url + 'reviewlog?start_date=' + $('#reviewlog_start_date').val() + '&end_date=' + $('#reviewlog_end_date').val();
    });

    //원형통계

});

function drawChart() {
    $('.review_question_scope').each(function (index) {
        var n = index+1;
        var response_data = getResponseData(n);
        var aaa = response_data[0];
        if (response_data[1] == 0) {
            $(this).find('#circle_chart').html("<span style='text-align: center;display: block'>응답자가 없습니다</span>");
        } else {
            var options = {'title': "", 'width': 400, 'height': 400};

            var chart = new google.visualization.PieChart(document.getElementById('circle_chart_'+n));
            chart.draw(response_data[0], options);

            options = {
                'title': "",
                'width': 400,
                'height': 400,
                'hAxis.viewWindowMode': 'explicit',
                'vAxis.viewWindow.min': 0
            };

            chart = new google.visualization.ColumnChart(document.getElementById('line_chart_'+n));

            var data = new google.visualization.DataView(response_data[0]);

            chart.draw(data, options);
        }
    });
}
//input태그로부터 응답자수얻기
function getResponseData(index){
    var example_count = $("#tcq"+index).val();

    // var array_data = [['example','응답자비율', { role: 'style' }]];
    var array_data = [['example','응답자비율']];
    // var colors = ['#f54c62','#5d61da','#15b180','#d668c8','#c5ce4f','#e39b15','#15e372','#15c5e3','#fb68cdd9','#00c069d9'];
    var totalResponseCount = 0;

    for(var i = 1; i < Number(example_count) + 1; i ++){
        totalResponseCount += Number($("#cq"+index+"e"+i).val());
        // array_data.push([$("#tq"+index+"e"+i).val(),Number($("#cq"+index+"e"+i).val()),colors[Math.floor((Math.random() * 10) + 1)]]);
        array_data.push([$("#tq"+index+"e"+i).val(),Number($("#cq"+index+"e"+i).val())]);
    }

    var data = google.visualization.arrayToDataTable(array_data);
    var retData = [data,totalResponseCount];
    return retData;
}
//원형통계
function onShowPieChart(index) {
    var response_data = getResponseData(index);

    if(response_data[1] == 0){
        $('#chart_container').html("<span style='text-align: center;display: block'>응답자가 없습니다</span>");
    }else {
        var options = {'title': $("#tnq" + index).val(), 'width': 550, 'height': 400};

        var chart = new google.visualization.PieChart(document.getElementById('chart_container'));
        chart.draw(response_data[0], options);
    }

    $('#chartModel').modal('show');
}
//도표통계
function onShowBarChart(index) {
    var response_data = getResponseData(index);
    var aaa = response_data[0];
    console.log("responseData,",aaa);
    if(response_data[1] == 0){
        $('#chart_container').html("<span style='text-align: center;display: block'>응답자가 없습니다</span>");
    }else {
        var options = {'title': $("#tnq" + index).val(), 'width': 550, 'height': 400,'hAxis.viewWindowMode':'explicit','vAxis.viewWindow.min':0};

        var chart = new google.visualization.ColumnChart(document.getElementById('chart_container'));

        var data = new google.visualization.DataView(response_data[0]);
        // view.setColumns([0, 1,
        //     { calc: "stringify",
        //         sourceColumn: 1,
        //         type: "string",
        //         role: "annotation" },
        //     2]);
        chart.draw(data, options);
    }
    $('#chartModel').modal('show');
}

function onToReviewListClick(survey_flag, parent) {
    if (survey_flag == 1)
        location.href = site_url + 'reviewlog/showresult/public?' + parent ;
    else
        location.href = site_url + 'reviewlog/showresult/advanced?' + parent ;
}

function onShowNoResponseClick(){
    // $("#btn_noResponse").hide();
    $("#review_noResponse").show();
    $("#btn_mainDetail").show();
    $("#review_mainDetail").hide();
}

function onShowMainDetailClick(){
    // $("#btn_noResponse").show();
    $("#review_noResponse").hide();
    $("#btn_mainDetail").hide();
    $("#review_mainDetail").show();
}

function onExcelClick() {
    // if(!$("#btn_noResponse").is(':visible'))
    //     location.href = site_url + 'reviewlogdetail/download_excel?kind=1'; //미응답자이면
    // else
        location.href = site_url + 'reviewlogdetail/download_excel?kind=0';  //설문통계이면
}

function onSendToTeacher() {

}

function onResend(){
    var notice_id = $("#message_noticeId").val();
    var kind = 0;
    if ($('#message_content').val().length > 90)
        kind = 1;
    var attached = 0;
    var mobiles = [];

    $('.noResponseArea tbody tr .noResponseNumber').each(function (index) {
        mobiles[index] = $(this).text().replace(/-/g,'').replace(/\s/g, '');
    });
    // $('.mobile_list_table tbody tr .mobile_number_item').each(function (index) {
    //     mobiles[index] = $(this).text().replace(/-/g,'');
    // });
    var groups = [];
    send_message(notice_id, 1, kind, attached, mobiles, groups,$('#message_content').val(),getDateString(new Date(), "y-M-d h:m:s"),$('#message_calling_number').val());
}
function getDateString(date, format) {
    var months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
        getPaddedComp = function(comp) {
            return ((parseInt(comp) < 10) ? ('0' + comp) : comp)
        },
        formattedDate = format,
        o = {
            "y+": date.getFullYear(), // year
            "M+": date.getMonth(), //month
            "d+": getPaddedComp(date.getDate()), //day
            "h+": getPaddedComp((date.getHours() > 12) ? date.getHours() % 12 : date.getHours()), //hour
            "H+": getPaddedComp(date.getHours()), //hour
            "m+": getPaddedComp(date.getMinutes()), //minute
            "s+": getPaddedComp(date.getSeconds()), //second
            "S+": getPaddedComp(date.getMilliseconds()), //millisecond,
            "b+": (date.getHours() >= 12) ? 'PM' : 'AM'
        };

    for (var k in o) {
        if (new RegExp("(" + k + ")").test(format)) {
            formattedDate = formattedDate.replace(RegExp.$1, o[k]);
        }
    }
    return formattedDate;
}
function send_message(notice_id, type, kind, attached, mobiles, groups,content,start_time,calling_number) {
    $.ajax({
        url: site_url + 'notice/save_mobiles',
        cache: false,
        timeout: 10000,
        data: {
            object_id: notice_id,
            type: type,
            kind: kind,
            content: content,
            attached: attached,
            calling_number: calling_number,
            start_time: start_time,
            mobiles: JSON.stringify(mobiles),
            groups: JSON.stringify(groups),
        },
        type: 'POST',
        dataType: "json",
        success: function (data) {

            jQuery.parseJSON(JSON.stringify(data));

            if (data.status == 0) {
                swal({
                        title: '', text: data.msg,
                        confirmButtonText: '확인', allowOutsideClick: false, type: 'success'
                    },
                    function (isConfirm) {
                        location.href = site_url + 'notice';
                    });
            }else {

                swal({
                    title:  data.msg, text: data.number_phones,
                    confirmButtonText: '확인', allowOutsideClick: false, type: 'error'
                }, function (isConfirm) {
                });
            }
        }
    });
}