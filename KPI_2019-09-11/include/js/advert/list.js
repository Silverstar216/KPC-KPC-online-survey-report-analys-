/**
 * User: KMC
 * Date: 10/08/2018
 * Time: 9:29 PM
 */
var ngst='all';
var stval='';
var st='all';
var selected = [];
var current_page = 1;
var total_count= 0;
var end_page = 1;
var page_per_count = 10;
var view_flag = 0;
$(function () {
    getAdvertList(0);
});



function change_page_per_count(){
    page_per_count = $('#page_per_count option:selected').val();
    getAdvertList(0);
}
function go_page(page){

    current_page =page;
    getAdvertList(1);

}


function addpageEventlisner() {
    total_count = $('#gtotal').val();
    if(total_count != undefined && total_count != 0) {
        $('.blog-pagination').html( Paging(total_count,page_per_count,current_page));


    } else {
        $('.blog-pagination').html('');
    }
    if(Number(total_count) > 0){
        $('.serv_t').text('총 갯수 '+total_count+' 개');
        $('.serv_t').css('color','#000');
    }else {
        $('.serv_t').text('등록된 홍보가 없습니다.');//phonenumberCont
        $('.serv_t').css('color','#e02222');
    }

    $('#chkall').click(function(){
        if ($(this).is(':checked'))
        {
            $("input:checkbox").prop('checked',true);
            $('input:checked').each(function() {
                var chkid=$(this).attr('id');
                if(chkid!="chkall")
                {
                    //selected.push($(this).attr('id'));
                    if(selected.length > 0)
                    {
                        for(var i=0;i<selected.length;i++)
                        {
                            //var gid=parseInt(selected[i]);
                            var id = selected[i];
                            if(chkid !== id)
                            {
                                selected.push(chkid);
                                break;
                            }
                        }
                    }
                    else
                    {
                        selected.push(chkid);
                    }
                }

            });
        }
        else
        {
            $("input:checkbox").prop('checked',false);
            selected = [];
        }

    });
    $('input:checkbox').click(function(){
        var chkid=$(this).attr('id');
        if(chkid=="chkall")
            return;
        $('#chkall').prop('checked',false);

        if ($(this).is(':checked'))
        {
            selected.push($(this).attr('id'));
        }
        else
        {
            var temp = [];
            for(var i = 0; i < selected.length; i++)
            {
                //var gid=parseInt(selected[i]);
                var id = selected[i];
                if(chkid == id)
                {
                    temp.push(id);
                }
            }
            selected = [];
            selected = temp;
            temp = [];
        }
    });

}
function advert_update(id) {
    swal({
            title: '', text: '수정을 하시면 노출수와 접속수가 초기화됩니다. 그래도 수정하시겠습니까?',
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
                location.href = site_url+'advert?advert_id='+id;
            }
        });


}

function getAdvertList(flag){
    if(flag == 0) {
        current_page = 1;
    }

    var stval = $.trim($('#st_val').val());




    $.ajax({
        url: site_url + 'advert/getAdvertList',
        cache:false,
        timeout : 10000,
        dataType:'html',
        type: 'POST',
        data: {
            stval: stval,
            page: current_page,
            page_per_count: page_per_count
        },
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

//설문에 대한 선택삭제를 진행한다.
function advert_delete() {
    var clen = selected.length;
    if(clen==0)
    {
        swal({title: '', text: '삭제할 설문을 체크하세요!',
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
                    url: site_url + "advert/delete_advert",
                    cache: false,
                    timeout: 10000,
                    data: {
                        selected: selected
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
                            getAdvertList(1);

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
