/**
 * Author: KMC
 * Date: 10/6/15
 */

var current_page = 1;
var total_count= 0;
var end_page = 1;
var page_per_count = 10;
$(function () {
    $('.reservelog_datepicker').datetimepicker({
        lang: 'ko',
        timepicker: false,
        format: 'Y-m-d',
        minDate:new Date(),
        formatDate: 'Y-m-d',
        scrollMonth: false,
        scrollTime: false,
        scrollInput: false,
        onSelectDate: function() {
            $(this).trigger('close.xdsoft');
        }
    });


    $('#reservelog_search').click(function () {
        location.href = site_url + 'reservelog?start_date=' + $('#reservelog_start_date').val() + '&end_date=' + $('#reservelog_end_date').val();
    });
    get_reservelog_list(0);
});
function change_page_per_count(){
    page_per_count = $('#page_per_count option:selected').val();
    get_reservelog_list(0);
}
function go_page(page){

    current_page =page;
    get_reservelog_list(1);

}

function  get_reservelog_list(flag) {
    var start_date = $('#reservelog_start_date').val();
    var end_date = $('#reservelog_end_date').val();
    
    if(flag == 0) {
        current_page = 1;
    }


    var param="st="+start_date+"&et=" + end_date+"&page="+current_page+"&count="+page_per_count;
    $.ajax({
        url: site_url + "reservelog/getReserveList",
        cache:false,
        timeout : 10000,
        dataType:'html',
        data:param,
        type:'get',
        success: function(data) {
            if (data !== 'err') {
                $('#grouplistDiv').html(data);
                addpageEventlisner();
            }
        },
        error: function (xhr, ajaxOptions, thrownError) {
            swal({title: '', text: xhr.status,
                    confirmButtonText: '확인', allowOutsideClick: false, type: 'error'},
                function(isConfirm) {});
        }
    });
}

function addpageEventlisner() {

    total_count = $('#totalcnt').val();
    if(total_count != undefined && total_count != 0) {
        $('.blog-pagination').html( Paging(total_count,page_per_count,current_page));


    }

    if(Number(total_count) > 0){
        $('.serv_t').text('총 개수 '+total_count+' 개');
        $('.serv_t').css('color','#000');
    }else {
        $('.serv_t').text('검색결과 없습니다.');//phonenumberCont
        $('.serv_t').css('color','#e02222');
    }

    $('.notice_datepicker').datetimepicker({
        lang: 'ko',
        step:5,
        format: 'Y-m-d H:i',
        formatDate: 'Y-m-d',
    });

}
function onSendClick() {
    location.href = site_url + 'sendlog';
}

function onEditClick(notice_id,index) {
    var aa ='#reserve_date'+index;
    var update_time=$('#reserve_date'+index).val();
    var before_time=$('#before_date'+index).val();
    var year = update_time.substring(0, 4);
    var month = update_time.substring(5, 7) - 1;
    var day = update_time.substring(8, 10);
    var hour = update_time.substring(11, 13);
    var minute = update_time.substring(14, 16);
    var real_time = new Date(year, month, day, hour, minute, 0,0);
    var before_year = before_time.substring(0, 4);
    var before_month = before_time.substring(5, 7) - 1;
    var before_day = before_time.substring(8, 10);
    var before_hour = before_time.substring(11, 13);
    var before_minute = before_time.substring(14, 16);
    var before_real_time = new Date(before_year, before_month, before_day, before_hour, before_minute,0,0);
    var now = new Date();
    var forward_day = 60 * 5 * 1000;
    if(before_real_time.getTime() > now.getTime() + forward_day) {
        if(real_time.getTime() >= now.getTime() + forward_day){
            $.ajax({
                url: site_url + 'reservelog/setReserveTime',
                type: 'POST',
                data: {
                    notice_id: notice_id,
                    start_time: update_time
                },
                error: function() {
                    $('#reserve_date'+index).val(before_time);
                    swal({
                        title: '', text: '예약설정오류',
                        confirmButtonText: '실패', allowOutsideClick: false, type: 'error'
                    }, function (isConfirm) {
                    });
                },
                success: function(data) {
                    $(this).find('#before_date'+index).val(update_time);
                    swal({
                        title: '', text: '예약시간이 성공적으로 변경되었습니다.',
                        confirmButtonText: '확인', allowOutsideClick: false, type: 'success'
                    }, function (isConfirm) {
                    });
                }
            });

        }else {
            $('#reserve_date'+index).val(before_time);
            swal({
                title: '', text: '예약시간은 현재시간으로부터 5분이후로만 설정이 가능합니다.',
                confirmButtonText: '확인', allowOutsideClick: false, type: 'error'
            }, function (isConfirm) {
            });

        }
    } else {
        $('#reserve_date'+index).val(before_time);
        swal({
            title: '', text: '예약시간 5분전부터는 예약시간변경이 불가능합니다.',
            confirmButtonText: '확인', allowOutsideClick: false, type: 'error'
        }, function (isConfirm) {
        });

    }


}
function onClickedCal () {
    $('.notice_datepicker').trigger("click");
    //$('.notice_datepicker').datepicker('show');
}
function onDeleteClick(notice_id,index) {
    swal({
            title: '', text: '예약을 취소하겠습니까?',
            allowOutsideClick: false,
            showConfirmButton: true,
            showCancelButton: true,
            confirmButtonClass: 'btn-danger',
            cancelButtonClass: 'btn-default',
            closeOnConfirm: false,
            closeOnCancel: true,
            confirmButtonText: '예',
            cancelButtonText: '아니오',
            type: 'warning'
        },
        function (isConfirm) {
            if(isConfirm) {
                var before_time = $('#before_date' + index).val();

                var before_year = before_time.substring(0, 4);
                var before_month = before_time.substring(5, 7) - 1;
                var before_day = before_time.substring(8, 10);
                var before_hour = before_time.substring(11, 13);
                var before_minute = before_time.substring(14, 16);
                var before_real_time = new Date(before_year, before_month, before_day, before_hour, before_minute, 0, 0);
                var now = new Date();
                var forward_day = 60 * 5 * 1000;
                if (before_real_time.getTime() > now.getTime() + forward_day) {

                    $.ajax({
                        url: site_url + 'reservelog/delMessage',
                        type: 'POST',
                        data: {
                            notice_id: notice_id,
                        },
                        error: function () {
                            swal({
                                title: '', text: '삭제오류',
                                confirmButtonText: '확인', allowOutsideClick: false, type: 'error'
                            }, function (isConfirm) {
                            });
                        },
                        success: function (data) {
                            swal({
                                title: '', text: '삭제 성공.',
                                confirmButtonText: '확인', allowOutsideClick: false, type: 'success'
                            }, function (isConfirm) {
                            });
                            location.href = site_url + 'reservelog?start_date=' + $('#reservelog_start_date').val() + '&end_date=' + $('#reservelog_end_date').val();
                        }
                    });
                } else {

                    swal({
                        title: '', text: '예약시간 5분전부터는 삭제가 불가능합니다.',
                        confirmButtonText: '확인', allowOutsideClick: false, type: 'error'
                    }, function (isConfirm) {
                    });

                }
            }

        });
}

