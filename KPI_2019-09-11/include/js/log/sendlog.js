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

    $('.sendlog_start_datepicker').on('input',function(e){
        strdate = $('.sendlog_start_datepicker').val();
        if (strdate.length >= 8) {
            strnewdate = strdate.substr(0,4) + "-" + strdate.substr(4,2) + "-" + strdate.substr(6, 2);
            var newdate = new Date(strnewdate);
            if (isValidDate(newdate))
                $('.sendlog_start_datepicker').val(strnewdate);
        }        
    });

    $('.sendlog_end_datepicker').on('input',function(e){
        strdate = $('.sendlog_end_datepicker').val();
        if (strdate.length >= 8) {
            strnewdate = strdate.substr(0,4) + "-" + strdate.substr(4,2) + "-" + strdate.substr(6, 2);
            var newdate = new Date(strnewdate);
            if (isValidDate(newdate))
                $('.sendlog_end_datepicker').val(strnewdate);
        }        
    });

    $('.sendlog_start_datepicker').datetimepicker({
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
    $('.sendlog_end_datepicker').datetimepicker({
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
    
    $('#sendlog_search').click(function () {
        location.href = site_url + 'sendlog?start_date=' + $('#sendlog_start_date').val() + '&end_date=' + $('#sendlog_end_date').val();
    });
    get_sendlog_list(0);
});

function onReserveClick() {
    location.href = site_url + 'reservelog?start_date=' + $('#sendlog_start_date').val() + '&end_date=' + $('#sendlog_end_date').val();
}

function onDetailClick(notice_id) {
    location.href = site_url + 'sendlogdetail?parent_start_date=' + $('#sendlog_start_date').val() + '&parent_end_date=' + $('#sendlog_end_date').val() + '&notice_id=' + notice_id;
}
function onDownloadClick() {
    location.href = site_url + 'sendlog/download_excel';
}

function change_page_per_count(){
    page_per_count = $('#page_per_count option:selected').val();
    get_sendlog_list(0);
}
function go_page(page){

    current_page =page;
    get_sendlog_list(1);

}
function  get_sendlog_list(flag) {
    var start_date = $('#sendlog_start_date').val();
    var end_date = $('#sendlog_end_date').val();
    if(flag == 0) {
        current_page = 1;
    }

    var param="st="+start_date+"&et=" + end_date+"&page="+current_page+"&count="+page_per_count;
    $.ajax({
        url: site_url + "sendlog/getSendList",
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


}