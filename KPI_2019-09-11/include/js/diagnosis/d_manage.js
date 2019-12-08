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
    $('#diagnosis_begindate').on('input',function(e){
        strdate = $('#diagnosis_begindate').val();
        if (strdate.length >= 8) {
            strnewdate = strdate.substr(0,4) + "-" + strdate.substr(4,2) + "-" + strdate.substr(6, 2);
            var newdate = new Date(strnewdate);
            if (isValidDate(newdate))
                $('#diagnosis_begindate').val(strnewdate);
        }        
    });

    $('#diagnosis_enddate').on('input',function(e){
        strdate = $('#diagnosis_enddate').val();
        if (strdate.length >= 8) {
            strnewdate = strdate.substr(0,4) + "-" + strdate.substr(4,2) + "-" + strdate.substr(6, 2);
            var newdate = new Date(strnewdate);
            if (isValidDate(newdate))
                $('#diagnosis_enddate').val(strnewdate);
        }        
    });

    $('#diagnosis_begindate').datetimepicker({
        lang: 'ko',
        format: 'Y-m-d',
        formatDate: 'Y-m-d',
        timepicker: false,
        scrollMonth: false,
        scrollTime: false,
        scrollInput: false,

        onSelectDate: function() {
            $(this).trigger('close.xdsoft');
        }
    });

    $('#diagnosis_enddate').datetimepicker({
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
    
    view_flag = $('#view_flag').val();
    getDiagnosisList(0,10);


    /* $( "#groups" ).change(function() {
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
 */
});

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

function change_page_per_count(){
    page_per_count = $('#page_per_count option:selected').val();
    getDiagnosisList(0);
}
function go_page(page){

    current_page =page;
    getDiagnosisList(1);
}


function addpageEventlisner() {
    total_count = $('#my_item_total_count').val();
    if(total_count != undefined && total_count != 0) {
        $('.blog-pagination').html( Paging(total_count,page_per_count,current_page));


    } else {
        $('.blog-pagination').html('');
    }
    if(Number(total_count) > 0){
        $('.serv_t').text('총 갯수 '+total_count+' 개');
        $('.serv_t').css('color','#000');
    }else {
        $('.serv_t').text('등록된 설문이 없습니다.');//phonenumberCont
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
    $(".change_survey_Img").click(function(){
        var diagnosis_id=$(this).attr('id');
        var diagnosis_excel_id = $(this).attr('diagnosis_excel_id');
        location.href = site_url+'diagnosis/view?diagnosis_id='+diagnosis_id+'&diagnosis_excel_id='+diagnosis_excel_id;
    });
}


function getDiagnosisList(flag){
    if(flag == 0) {
        current_page = 1;
    }

    var begindate = $.trim($('#diagnosis_begindate').val());
    var enddate = $.trim($('#diagnosis_enddate').val());
    var admin = $.trim($('#diagnosis_admin').val());
    var group = $.trim($('#diagnosis_groupname').val());
    var tool = $.trim($('#diagnosis_tool').val());
    var team = $.trim($('#diagnosis_team').val());
    var customer = $.trim($('#diagnosis_customer').val());
    var education = $.trim($('#diagnosis_education').val());
    var education_count = $.trim($('#diagnosis_count').val());
    var name = $.trim($('#diagnosis_name').val());
    var executename = $.trim($('#diagnosis_executename').val());
    var prev_survey_id = $.trim($('#prev_survey_id').val());

    $.ajax({
        url: site_url + 'diagnosis/get_my_diagnosises_list',
        cache:false,
        timeout : 10000,
        dataType:'html',
        type: 'POST',
        data: {
            view_flag: view_flag,
            begindate: begindate,
            enddate: enddate,
            admin: admin,
            group: group,
            tool: tool,
            team: team,
            customer: customer,
            education: education,
            education_count: education_count,
            name: name,
            executename: executename,
            prev_survey_id:prev_survey_id,
            page: current_page,
            page_per_count: page_per_count,
        },
        success: function(data) {
            if (data !== 'err') {
                $('#grouplistDiv').html(data);
                $('#my_item_total_count_1').html($('#my_item_total_count').val());
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

// 내설문에 대한 공개를 진행한다.
function survey_public() {
    var clen = selected.length;
    if(clen==0)
    {
        swal({title: '', text: '공개할 설문을 체크하세요!',
                confirmButtonText: '확인', allowOutsideClick: false, type: 'warning'},
            function(isConfirm) {});
        return;
    }
    swal({
            title: '', text: '정말 공개하시겠습니까?',
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
                    url: site_url + "diagnosis/public_survey",
                    cache: false,
                    timeout: 10000,

                    data: {
                        selected: selected
                    },
                    type: 'post',
                    success: function (data) {
                        if (data !== 'err') {
                            swal({
                                    title: '', text: '당신의 설문이 공개되였습니다.',
                                    confirmButtonText: '확인', allowOutsideClick: false, type: 'success'
                                },
                                function (isConfirm) {
                                });
                            selected = [];
                            getDiagnosisList(1);
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

//설문에 대한 선택삭제를 진행한다.
function survey_delete() {
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
                    url: site_url + "diagnosis/delete_survey",
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
                            getDiagnosisList(1);

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

function onUploadDiagnosisExcel()
{
    swal({
        title: '', text: '정말 업로드하시겠습니까?',
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
            var form = $('#upload_erp')[0];
            var formData = new FormData(form);
            formData.append("erp_excel", $("#excel_diagnosis_file")[0].files[0]);

            $.ajax({
                url: site_url + "diagnosis/upload_diagnosis_excel",
                cache: false,
                timeout: 10000,
                processData: false,
                contentType: false,
                data: formData,
                type: 'post',
                success: function (data) {
                    if (data == 'invalid extension') {
                        swal({
                                title: '', text: '엑셀 (*.xlsx) 파일을 업로드하실수 있습니다.',
                                confirmButtonText: '확인', allowOutsideClick: false, type: 'error'
                            },
                            function (isConfirm) {
                            });
                    }
                    else if (data == 'error') {
                        swal({
                            title: '', text: '엑셀 (*.xlsx) 파일을 업로드 오류가 발생하였습니다.',
                            confirmButtonText: '확인', allowOutsideClick: false, type: 'error'
                        },
                        function (isConfirm) {
                        });
                    }
                    else {
                        swal({
                            title: '', text: '성공적으로 업로드 되었습니다.',
                            confirmButtonText: '확인', allowOutsideClick: false, type: 'success'
                        },
                        function (isConfirm) {                            
                        });

                        getDiagnosisList(0);
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