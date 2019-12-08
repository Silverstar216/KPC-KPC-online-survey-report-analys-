/**
 * Author: KMC
 * Date: 10/6/15
 */

var current_page = 1;
var total_count= 0;
var end_page = 1;
var page_per_count = 10;
$(function () {
    $('.sendlogdetail_datepicker').datetimepicker({
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
    
    $('#sendlogdetail_search').click(function () {
        get_sendlogdetail_list(0);
    });
    get_sendlogdetail_list(0);
});

function onParentClick($parent) {
    location.href = site_url + 'sendlog?' + $parent;
}

function change_page_per_count(){
    page_per_count = $('#page_per_count option:selected').val();
    get_sendlogdetail_list(0);
}
function go_page(page){

    current_page =page;
    get_sendlogdetail_list(1);

}
function  get_sendlogdetail_list(flag) {
    var notice_id = $('#notice_id').val();
    var mobile = $('#sendlogdetail_mobile').val();
    if(flag == 0) {
        current_page = 1;
    }


    var param="ni="+notice_id+"&m=" + mobile+"&page="+current_page+"&count="+page_per_count;
    $.ajax({
        url: site_url + "sendlogdetail/getSendDetailList",
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

function onDetailDownloadClick() {
    location.href = site_url + 'sendlogdetail/download_excel';
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