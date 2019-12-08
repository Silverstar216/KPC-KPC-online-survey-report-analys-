//add chkd
var ngst='all';
var stval='';
var st='all';
var selected = [];
var current_page = 1;
var total_count= 0;
var end_page = 1;
var page_per_count = 10;
function changeUsermobile()
{
    var groups = $('.sltgroup').val();
    if(groups==null || groups=='')
    {
        swal({title: '', text: "그룹을 선택하세요!",
                confirmButtonText: '확인', allowOutsideClick: false, type: 'warning'},
            function(isConfirm) {});
        return;
    }
    var bkname = $.trim($('#bk_name').val());
    var bkhp = $.trim($('#bk_hp').val());
    if(!checkPhoneNumber(bkhp)){
        swal({title: '', text: "휴대폰번호형식이 정확치 않습니다!\n(예:010YYYYZZZZ,011YYYZZZZ)",
                confirmButtonText: '확인', allowOutsideClick: false, type: 'warning'},
            function(isConfirm) {});
        return;
    }
   /* var address_num = $.trim($('#num').val());*/
    var bkmemo = $.trim($('#bk_memo').val());
    var mid = $('#mobileid').val();
    var gid = $('#groupid').val();

    var params = {};

    params.groups = groups;
    params.username = bkname;
    params.mobile = bkhp;
   /* params.address_num = address_num;*/
    params.memo = bkmemo;
    params.mid = mid;
    params.gid = gid;
    $.post(site_url + 'phone/setMobileUser',
        params,
        function(data, status) {
            if (data != 'err') {
                swal({
                        title: '', text:'성공',
                        confirmButtonText: '확인', allowOutsideClick: false, type: 'success'
                    },
                    function (isConfirm) {
                    });
                $('.container-mod').dialog('close');
                $('.container-mod').remove();
                getPhoneNumberList(0,page_per_count);
                /*location.href = site_url+"phone/Phonenumber";*/

            }
            //location.href = site_url + 'phonefile';
        });

}
function hideUsermobile(){

    $('.container-mod').dialog('close');
    $('.container-mod').remove();
}

function searchBtnClick(obj_id)
{
    getPhoneNumberList(0,page_per_count);
}

//그룹에 사용자추가부분
function book_submit()
{
    var sval = $('.chosen-select').val();
    if(sval==null || sval=='')
    {
        swal({title: '', text: "그룹을 선택하세요!",
                confirmButtonText: '확인', allowOutsideClick: false, type: 'warning'},
            function(isConfirm) {});
        return;
    }
    var bkname = $.trim($('#bk_name').val());
    var bkhp = $.trim($('#bk_hp').val());
    if($.isNumeric(bkhp)===false)
    {
        swal({title: '', text: "휴대폰번호를 정확히 입력하세요!\n 입력형식:0123456789",
                confirmButtonText: '확인', allowOutsideClick: false, type: 'warning'},
            function(isConfirm) {});
        return;
    }
 /*   var address_num = $.trim($('#address_num').val());*/
    var bkmemo = $.trim($('#bk_memo').val());
    var para="groups="+sval+"&username="+bkname+"&mobile="+bkhp+"&memo="+bkmemo;

    $.ajax({
        url: site_url + "phone/setMobileUsr",
        cache:false,
        timeout : 10000,
        type: 'POST',
        data: {
            groups: sval,
            username: bkname,
            mobile:bkhp,
            memo:bkmemo

        },
        success: function(data) {
            if (data == 'err') {
                swal({title: '', text: "등록오류!\n 다시 시도하세요!",
                        confirmButtonText: '확인', allowOutsideClick: false, type: 'error'},
                    function(isConfirm) {});

            } else {
                $('.chosen-select').val('');
                $(".chosen-select").trigger("chosen:updated");
                $('#bk_name').val('');
                $('#bk_hp').val('');
               /* $('#address_num').val('');*/
                $('#bk_memo').val('');
                swal({title: '', text: "정확히 등록되었습니다!",
                        confirmButtonText: '확인', allowOutsideClick: false, type: 'success'},
                    function(isConfirm) {});

            }
        },
        error: function (xhr, ajaxOptions, thrownError) {
            swal({title: '', text: xhr.status,
                    confirmButtonText: '확인', allowOutsideClick: false, type: 'error'},
                function(isConfirm) {});

        }
    });
}


function deleteGroupUser()
{
    var clen = selected.length;
    if(clen==0)
    {
        swal({title: '', text: '삭제할 그룹을 체크하세요!',
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
            confirmButtonText: '삭제',
            cancelButtonText: '취소',
            type: 'warning'
        },
        function (isConfirm) {
            if(isConfirm) {
                $.ajax({
                    url: site_url + "phone/deletemobile",
                    cache: false,
                    timeout: 10000,
                    data: {
                        selected: selected


                    },
                    type: 'post',
                    success: function (data) {
                        if (data == 'ok') {
                            swal({
                                    title: '', text: '삭제성공',
                                    confirmButtonText: '확인', allowOutsideClick: false, type: 'success'
                                },
                                function (isConfirm) {
                                });
                            selected = [];
                            getPhoneNumberList(1,page_per_count);
                        } else {
                            swal({
                                    title: '', text: data,
                                    confirmButtonText: '확인', allowOutsideClick: false, type: 'error'
                                },
                                function (isConfirm) {
                                });
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
function addGroupUser()
{
    if($('#user_level').val()=="") {
        alert("회원가입하여야 가능합니다.");
        location.href=site_url+"join/login_view"

        return;
    }
    if($('#user_level').val()=="test") {
        if($('#totalcnt').val()=="1"){
            alert("회원가입하여야 가능합니다.");
            location.href=site_url+"join/login_view"

            return;
        }
    }
    var addname = $.trim($('#addname').val());
    var phonenum = $.trim($('#phonenum').val());
    var sval = $('#groups').val();
  /*  var address_num = $.trim($('#address_num').val());*/
    var addmemo = $.trim($('#addmemo').val());

    /*if(address_num =='메모')
    {
        address_num='';
    }*/
    if(addmemo =='메모')
    {
        addmemo='';
    }
    if(sval =='all')
    {
        swal({title: '', text: "그릅을 선택하세요!",
                confirmButtonText: '확인', allowOutsideClick: false, type: 'warning'},
            function(isConfirm) {});
        return;
    }
    if(addname ==''|| addname=='이름')
    {
        swal({title: '', text: "이름을 정확히 입력하세요!",
                confirmButtonText: '확인', allowOutsideClick: false, type: 'warning'},
            function(isConfirm) {});
        return;
    }

   /* if($.isNumeric(phonenum)===false)
    {
        swal({title: '', text: "휴대폰번호를 정확히 입력하세요!",
                confirmButtonText: '확인', allowOutsideClick: false, type: 'warning'},
            function(isConfirm) {});
        return;
    }*/
    if(!checkPhoneNumber(phonenum)){
        swal({title: '', text: "휴대폰번호형식이 정확치 않습니다!\n(예:010YYYYZZZZ,011YYYZZZZ)",
                confirmButtonText: '확인', allowOutsideClick: false, type: 'warning'},
            function(isConfirm) {});
        return;
    }
    var para="groups="+sval+"&username="+addname+"&mobile="+phonenum+"&memo="+addmemo;

    $.ajax({
        url: site_url + "phone/addMobileUsr",
        cache:false,
        timeout : 10000,
        type: 'POST',
        data: {
            groups: sval,
            username: addname,
            mobile:phonenum,
            memo:addmemo

        },
        success: function(data) {
            if (data == 'err') {
                swal({title: '', text: "추가오류!\n 다시 시도하세요!",
                        confirmButtonText: '확인', allowOutsideClick: false, type: 'error'},
                    function(isConfirm) {});

            } else if(data=='ok'){

                swal({
                        title: '', text:'추가성공',
                        confirmButtonText: '확인', allowOutsideClick: false, type: 'success'
                    },
                    function (isConfirm) {
                    });
                $("#agroups option:eq(0)").prop("selected", true);
                $('#addname').val('');
                $('#phonenum').val('');
               /* $('#address_num').val('');*/
                $('#addmemo').val('');
                getPhoneNumberList(0,page_per_count);
            } else {
                swal({title: '', text: "추가오류!\n 그 전화번호는 이미 "+data+"에 등록되여있습니다.",
                        confirmButtonText: '확인', allowOutsideClick: false, type: 'error'},
                    function(isConfirm) {});
            }
        },
        error: function (xhr, ajaxOptions, thrownError) {
            swal({title: '', text: xhr.status,
                    confirmButtonText: '확인', allowOutsideClick: false, type: 'error'},
                function(isConfirm) {});

        }
    });
}

function change_page_per_count(){
    page_per_count = $('#page_per_count option:selected').val();
    getPhoneNumberList(0,page_per_count);
}
function go_page(page){

    current_page =page;
   getPhoneNumberList(1,page_per_count);

}

function addpageEventlisner1()
{
    total_count = $('#totalcnt').val();
    if(total_count != undefined && total_count != 0) {
        $('.blog-pagination').html( Paging(total_count,page_per_count,current_page));
    } else {
        $('.blog-pagination').html('');
    }
    if(Number(total_count) > 0){
        $('.serv_t').text('총 갯수 '+total_count+' 개');
        $('.serv_t').css('color','#000');
    }else {
        $('.serv_t').text('등록된 번호가 없습니다.');//phonenumberCont
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


    $(".phonecursorImg").click(function(){
        var thisid=$(this).attr('id');
        var pre = thisid.split('_')[0];
        var id=thisid.split('_')[1];
        var gid = $(this).attr('role');
        var param="mid="+id+"&gid="+gid;
        if(pre =='c')
        {

            $.ajax({
                url: site_url + "phone/tochangemobile",
                cache:false,
                timeout : 10000,
                dataType:'html',
                data:param,
                type:'get',
                success: function(data) {
                    if (data !== 'err') {
                        //getPhoneNumberList();
                        /*var $newdiv1 = $( "<div id='object1' style='border:0px solid #aaa;width:1000px;height:635px;'></div>" );
    */
                        $( "body" ).append(data);
                        /*/**!/$newdiv1.html(data);*/
                        $('.container-mod').dialog({
                            title:'사용자정보',
                            modal: true,
                            width: '700',
                            height: '530'
                        });
                    }
                },
                error: function (xhr, ajaxOptions, thrownError) {
                    swal({title: '', text: xhr.status,
                            confirmButtonText: '확인', allowOutsideClick: false, type: 'error'},
                        function(isConfirm) {});
                }
            });
        }
        else if(pre=='d')
        {
            var param="m="+id+"&n="+gid;
            location.href = site_url + 'notice?'+param;
           /* swal({
                    title: '', text: '정말 삭제하시겠습니까?',
                    allowOutsideClick: false,
                    showConfirmButton: true,
                    showCancelButton: true,
                    confirmButtonClass: 'btn-danger',
                    cancelButtonClass: 'btn-default',
                    closeOnConfirm: false,
                    closeOnCancel: true,
                    confirmButtonText: '삭제',
                    cancelButtonText: '취소',
                    type: 'warning'
                },
                function (isConfirm) {
                    if(isConfirm) {
                        $.ajax({
                            url: site_url + "phone/deletemobile",
                            cache: false,
                            timeout: 10000,
                            dataType: 'text',
                            data: param,
                            type: 'get',
                            success: function (data) {
                                if (data !== 'err') {
                                    getPhoneNumberList();
                                    swal({title: '', text: '삭제성공',
                                            confirmButtonText: '확인', allowOutsideClick: false, type: 'success'},
                                        function(isConfirm) {});

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

                });*/
        }

    });


}
//형식오류검사
function checkPhoneNumber(phoneNumber){
    //유선번호목록
    var phoneFilterList = [
        //이동통신전화번호
        ['010',7],['011',7],['016',7],['017',7],['018',7],['019',7],
        ['010',8],['011',8],['016',8],['017',8],['018',8],['019',8],
    ];
    var checkResult = false;
    $.each(phoneFilterList, function (index, value) {
        if(phoneNumber.substr(0,value[0].length) == value[0] && phoneNumber.length == value[0].length + Number(value[1])){
            checkResult = true;
            return false;
        };
    });
    return checkResult;
}
function getPhoneNumberList(flag,page_per_count)
{
    if(flag == 0) {
        current_page = 1;
    }

    var stval = $.trim($('#st_val').val());
    var st = $('#st').val();
    var ngst = $('#groups').val();

    var param="st="+st+"&ngst="+ngst+"&stval="+stval+"&page="+current_page+"&count="+page_per_count;
    $.ajax({
        url: site_url + "phone/getphonenumberList",
        cache:false,
        timeout : 10000,
        dataType:'html',
        type: 'POST',
        data: {
            st: st,
            ngst: ngst,
            stval:stval,
            page:current_page,
            count:page_per_count
        },
        success: function(data) {
            if (data !== 'err') {
                $('#grouplistDiv').html(data);
                addpageEventlisner1();
            }
        },
        error: function (xhr, ajaxOptions, thrownError) {
            swal({title: '', text: xhr.status,
                    confirmButtonText: '확인', allowOutsideClick: false, type: 'error'},
                function(isConfirm) {});
        }
    });
}

//========================document.reay==========================================//
$(function () {



    getPhoneNumberList(0,10);


    $( "#groups" ).change(function() {
        getPhoneNumberList(0,10);
    });

    $("#st").change(function(){
        st=$(this).val();
        if(st=="all"){
            $('#st_val').val('');
            $('#hstval').val('');
            stval='';
        }
    });


});