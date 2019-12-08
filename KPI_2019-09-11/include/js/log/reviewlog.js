/**
 * Author: KMC
 * Date: 10/6/15
 */

var current_page = 1;
var total_count= 0;
var end_page = 1;
var page_per_count = 10;

function isValidDate(d) {
    return d instanceof Date && !isNaN(d);
}

Date.prototype.yyyymmdd = function() {
    var mm = this.getMonth() + 1; // getMonth() is zero-based
    var dd = this.getDate();
  
    return [this.getFullYear(),
            (mm>9 ? '' : '0') + mm,
            (dd>9 ? '' : '0') + dd
           ].join('-');
};


$(function () {

    $('.reviewlog_start_datepicker').on('input',function(e){
        strdate = $('.reviewlog_start_datepicker').val();
        if (strdate.length >= 8) {
            strnewdate = strdate.substr(0,4) + "-" + strdate.substr(4,2) + "-" + strdate.substr(6, 2);
            var newdate = new Date(strnewdate);
            if (isValidDate(newdate))
                $('.reviewlog_start_datepicker').val(strnewdate);
        }        
    });

    $('.reviewlog_end_datepicker').on('input',function(e){
        strdate = $('.reviewlog_end_datepicker').val();
        if (strdate.length >= 8) {
            strnewdate = strdate.substr(0,4) + "-" + strdate.substr(4,2) + "-" + strdate.substr(6, 2);
            var newdate = new Date(strnewdate);
            if (isValidDate(newdate))
                $('.reviewlog_end_datepicker').val(strnewdate);
        }        
    });

    $('.reviewlog_start_datepicker').datetimepicker({
        lang: 'ko',
        timepicker: false,
        format: 'Y-m-d',
        maxDate:new Date(),
        formatDate: 'Y-m-d',
        scrollMonth: false,
        scrollTime: false,
        scrollInput: false,
        onSelectDate: function() {
            $(this).trigger('close.xdsoft');
        }
    });

    $('.reviewlog_end_datepicker').datetimepicker({
        lang: 'ko',
        timepicker: false,
        format: 'Y-m-d',
        formatDate: 'Y-m-d',
        maxDate:new Date(),
        scrollMonth: false,
        scrollTime: false,
        scrollInput: false,
        onSelectDate: function() {
            $(this).trigger('close.xdsoft');
        }
    });


    $('#reviewlog_search').click(function () {
        location.href = site_url + 'reviewlog?start_date=' + $('#reviewlog_start_date').val() + '&end_date=' + $('#reviewlog_end_date').val();
    });

    get_reviewlog_list(0);
});

function onDetailClick(notice_id, survey_flag) {
    location.href = site_url + 'reviewlogdetail?survey_flag=' + survey_flag + '&start_date=' + $('#reviewlog_start_date').val() + '&end_date=' + $('#reviewlog_end_date').val() + '&notice_id=' + notice_id;
}
function onExcelClick() {
    location.href = site_url + 'reviewlog/download_excel';
}

function change_page_per_count(){
    page_per_count = $('#page_per_count option:selected').val();
    get_reviewlog_list(0);
}
function go_page(page){

    current_page =page;
    get_reviewlog_list(1);

}
function  get_reviewlog_list(flag) {
    var start_date = $('#reviewlog_start_date').val();
    var end_date = $('#reviewlog_end_date').val();
    var survey_flag = $('#survey_flag').val();
    if(flag == 0) {
        current_page = 1;
    }
    var param="st="+start_date+"&et=" + end_date+"&page="+current_page+"&count="+page_per_count+"&survey_flag="+survey_flag;
    $.ajax({
        url: site_url + "reviewlog/get_reviewlog_list",
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

    $('#totalcnt_1').html(total_count);

    if(Number(total_count) > 0){
        $('.serv_t').text('총 개수 '+total_count+' 개');
        $('.serv_t').css('color','#000');
    }else {
        $('.serv_t').text('검색결과 없습니다.');//phonenumberCont
        $('.serv_t').css('color','#e02222');
    }


}

function delete_reviewlog() {
    var selected_review_id = $("input[name='reviews_option']:checked").val();

    if(selected_review_id === undefined)
    {
        swal({title: '', text: '삭제할 설문결과을 선택하세요!',
                confirmButtonText: '확인', allowOutsideClick: false, type: 'warning'},
            function(isConfirm) {});
        return;
    }
    swal({
            title: '', text: '정말 삭제하시겠습니까?',
            allowOutsideClick: false,
            showConfirmButton: true,
            showCancelButton: true,
            confirmButtonClass: 'btn-danger',
            cancelButtonClass: 'btn-default',
            closeOnConfirm: true,
            closeOnCancel: true,
            confirmButtonText: '예',
            cancelButtonText: '아니오',
            type: 'warning'
        },
        function (isConfirm) {
            if(isConfirm) {
                $.ajax({
                    url: site_url + "reviewlog/delete_review",
                    cache: false,
                    timeout: 10000,
                    data: {
                        selected_review_id: selected_review_id
                    },
                    type: 'post',
                    success: function (data) {
                        if (data !== 'err') {
                            swal({
                                    title: '', text: '삭제성공',
                                    confirmButtonText: '확인', allowOutsideClick: false, type: 'success'
                                },
                                function (isConfirm) {
                                });
                            selected = [];
                            get_reviewlog_list(1);

                        }
                    },
                    error: function (xhr, ajaxOptions, thrownError) {
                        swal({
                                title: '', text: xhr.status,
                                confirmButtonText: '확인', allowOutsideClick: false, type: 'error'
                            },
                            function (isConfirm) {
                            });
                    }
                });

            }


        });
}