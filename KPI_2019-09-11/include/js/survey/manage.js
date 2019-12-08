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
var survey_flag = 0;
var stval;
var survey_begindate;
var survey_enddate;
var survey_admin;
var survey_groupname;
var survey_team;
var survey_course;
var survey_name;
var menu;
var submenu;
var excel_student_file;

$(function () {        

    $('#survey_begindate').on('input',function(e){
        strdate = $('#survey_begindate').val();
        if (strdate.length >= 8) {
            strnewdate = strdate.substr(0,4) + "-" + strdate.substr(4,2) + "-" + strdate.substr(6, 2);
            var newdate = new Date(strnewdate);
            if (isValidDate(newdate))
                $('#survey_begindate').val(strnewdate);
        }        
    });

    $('#survey_enddate').on('input',function(e){
        strdate = $('#survey_enddate').val();
        if (strdate.length >= 8) {
            strnewdate = strdate.substr(0,4) + "-" + strdate.substr(4,2) + "-" + strdate.substr(6, 2);
            var newdate = new Date(strnewdate);
            if (isValidDate(newdate))
                $('#survey_enddate').val(strnewdate);
        }        
    });

    $('#survey_begindate').datetimepicker({
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

    $('#survey_enddate').datetimepicker({
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
    if (view_flag == 4)
        getEducationScheduleList(0);
    else
        getSurveyList(0);                 
});


function change_page_per_count(){
    page_per_count = $('#page_per_count option:selected').val();
    if (view_flag == 4)
        getEducationScheduleList(0);
    else
        getSurveyList(0);
}

function go_page(page) {
    current_page =page;
    if (view_flag == 4)
        getEducationScheduleList(1);
    else
        getSurveyList(1);
}

function addpageEventlisner() {
    $('input[name=excel_students_files]').on('change', function(e){
        excel_student_file = e.target.files[0].name;
    });

    total_count = $('#my_item_total_count').val();    

    if(total_count != undefined && total_count != 0) {
        $('.blog-pagination').html(Paging(total_count,page_per_count,current_page));
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
        var thisid = $(this).attr('id');
        var attached = $(this).attr('role');  
        var education_id = $(this).attr('education_id');  
        
        survey_flag = $('#survey_flag').val();        
        prev_education_id = $('#prev_education_id').val();        
        education_title = $('#education_title').val();        
        survey_start_date = $('#survey_start_date').val();        
        survey_end_date = $('#survey_end_date').val();      

        sms_available = $('#sms_available').val();
        education_course = $('#education_course').val();
        education_customer = $('#education_customer').val();
        education_teacher = $('#education_teacher').val();

        if (education_title === undefined) {
            var go_url = site_url + 'survey/view?survey_flag=' + survey_flag + 
                        '&survey_id=' + thisid + 
                        '&education_id=' + education_id + 
                        '&newflag=' + view_flag +
                        '&sms_available=' + sms_available +
                        '&education_course=' + education_course +
                        '&education_customer=' + education_customer +
                        '&education_teacher=' + education_teacher;

            location.href = go_url + '&attached=' + attached;
        }
        else {
            if (prev_education_id == "") {
                var go_url = site_url + 'survey/view?survey_flag=' + survey_flag + 
                        '&survey_id=' + thisid + 
                        '&education_id=' + education_id + 
                        '&newflag=' + view_flag;
                location.href = go_url + '&attached=' + attached + 
                        '&education_title=' + education_title + 
                        '&survey_start_date=' + survey_start_date + 
                        '&survey_end_date=' + survey_end_date +    
                        '&sms_available=' + sms_available +
                        '&education_course=' + education_course +
                        '&education_customer=' + education_customer +
                        '&education_teacher=' + education_teacher;

            } else {
                var go_url = site_url + 'survey/view?survey_flag=' + survey_flag + 
                        '&survey_id=' + thisid + 
                        '&education_id=' + prev_education_id + 
                        '&newflag=' + view_flag;
                location.href = go_url + '&attached=' + attached + 
                        '&education_title=' + education_title + 
                        '&survey_start_date=' + survey_start_date + 
                        '&survey_end_date=' + survey_end_date + 
                        '&sms_available=' + sms_available +
                        '&education_course=' + education_course +
                        '&education_customer=' + education_customer +
                        '&education_teacher=' + education_teacher;

            }
        }
    });
}

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

function getSurveyList(flag){
    if(flag == 0) {
        current_page = 1;
    }

    view_flag = $('#view_flag').val();
    survey_flag = $('#survey_flag').val();
    stval = $.trim($('#st_val').val());

    survey_begindate = new Date($('#survey_begindate').val());    
    survey_enddate = new Date($('#survey_enddate').val());
    survey_admin = $('#survey_admin').val();
    survey_groupname = $('#survey_groupname').val();
    survey_team = $('#survey_team').val();
    survey_course = $('#survey_course').val();
    survey_name = $('#survey_name').val();
    survey_customer = $('#survey_customer').val();

    if (isValidDate(survey_begindate) == false)
        survey_begindate = new Date('01/01/1900 00:00');

    if (isValidDate(survey_enddate) == false)
        survey_enddate = new Date('01/01/2090 00:00');

    if ((survey_begindate > survey_enddate)) {
        swal({title: '입력오류', text: '조회기간을 다시 설정해주세요.', 
            confirmButtonText: '확인', allowOutsideClick: false, type: 'error'},
            function(isConfirm) {});        
    }
    else {
        survey_begindate = survey_begindate.yyyymmdd();
        survey_enddate = survey_enddate.yyyymmdd();

        $.ajax({
            url: site_url + 'survey/get_my_surveys_list',
            cache:false,
            timeout : 10000,
            dataType:'html',
            type: 'POST',
            data: {
                stval: stval,
                page: current_page,
                page_per_count: page_per_count,
                view_flag: view_flag,
                survey_flag: survey_flag,
    
                survey_begindate: survey_begindate,
                survey_enddate: survey_enddate,
                survey_admin: survey_admin,
                survey_groupname: survey_groupname,
                survey_team: survey_team,
                survey_course: survey_course,
                survey_name: survey_name,
                survey_customer: survey_customer,
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
}
function onSearchEducationSecheduleList(){
    $('#is_landing').val("0");
    getEducationScheduleList(0);
}
function getEducationScheduleList(flag){
    if(flag == 0) {
        current_page = 1;
    }

    survey_begindate = new Date($('#survey_begindate').val());
    survey_enddate = new Date($('#survey_enddate').val());
    
    if (isValidDate(survey_begindate) == false)
        survey_begindate = new Date('01/01/1900 00:00');

    if (isValidDate(survey_enddate) == false)
        survey_enddate = new Date('01/01/2090 00:00');

    if ((survey_begindate > survey_enddate)) {
        swal({title: '입력오류', text: '조회기간을 다시 설정해주세요.', 
            confirmButtonText: '확인', allowOutsideClick: false, type: 'error'},
            function(isConfirm) {});        
    }
    else {
        survey_begindate = survey_begindate.yyyymmdd();
        survey_enddate = survey_enddate.yyyymmdd();
        survey_course = $('#survey_course').val();
        survey_admin = $('#survey_admin').val();
        survey_job = $('#survey_job').val();
        survey_groupname = $('#survey_groupname').val();
        survey_flag  = $('#survey_flag').val();
        education_type = $('#education_type').val();
        survey_customer  = $('#survey_customer').val();
        survey_count  = $('#survey_count').val();
        is_landing = $('#is_landing').val();

        $.ajax({
            url: site_url + 'survey/get_my_educations_list',
            cache:false,
            timeout : 10000,
            dataType:'html',
            type: 'POST',
            data: {
                page: current_page,
                page_per_count: page_per_count,
                survey_begindate: survey_begindate,
                survey_enddate: survey_enddate,
                survey_course: survey_course,
                survey_admin: survey_admin,
                survey_groupname:survey_groupname,
                survey_flag: survey_flag,
                survey_job: survey_job,
                survey_customer: survey_customer,
                survey_count: survey_count,
                education_type: education_type,
                is_landing: is_landing,
            },
            success: function(data) {
                if (data !== 'err') {
                    $('#grouplistDiv').html(data);
                    $('#my_item_total_count_1').html($('#my_item_total_count').val());
                    addpageEventlisner();

                    // $table = $('#import_survey_table');
                    // if ($table != null) {
                    //     var $tds = $table.find('td');
                    //     if($tds != null && $tds.length > 3) {
                    //         $('#survey_groupname').val($tds[1].innerHTML);
                    //     }
                    // }   
                }
                else {
                    swal({title: '', text: '담당자를 찾을수 없 습니다.',
                    confirmButtonText: '확인', allowOutsideClick: false, type: 'error'},
                    function(isConfirm) {});   

                    $('#grouplistDiv').html('');
                    $('#my_item_total_count_1').html('0');
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
                    url: site_url + "survey/public_survey",
                    cache: false,
                    timeout: 10000,

                    data: {
                        selected: selected
                    },
                    type: 'post',
                    success: function (data) {
                        if (data !== 'err') {
                            swal({
                                    title: '', text: '당신의 설문이 공개되었습니다.',
                                    confirmButtonText: '확인', allowOutsideClick: false, type: 'success'
                                },
                                function (isConfirm) {
                                });
                            selected = [];
                            getSurveyList(1);
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
                    url: site_url + "survey/delete_survey",
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
                            getSurveyList(1);

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

function survey_show_public() {
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
                    url: site_url + "survey/show_public_survey",
                    cache: false,
                    timeout: 10000,
                    data: {
                        selected: selected
                    },
                    type: 'post',
                    success: function (data) {
                        if (data !== 'err') {
                            swal({
                                    title: '', text: '공개성공',
                                    confirmButtonText: '확인', allowOutsideClick: false, type: 'success'
                                },
                                function (isConfirm) {
                                });
                            selected = [];
                            getSurveyList(1);

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

//선택된 교육과정에 해당한 설문을 보낸다.
function onSendSurvey(survey_id, newflag, attached)
{
    survey_flag = $('#survey_flag').val();
    if (survey_id == 0) {
        var education_id = $('input[name=education_id]:checked').val();    
        if (education_id !== undefined) 
            location.href = site_url + 'survey/view?survey_flag='+survey_flag+'&education_id='+education_id;    
        else
            swal({
                title: '', text: '설문할 교육과정이 선택되지 않았습니다.',
                confirmButtonText: '확인', allowOutsideClick: false, type: 'error'
            },
            function (isConfirm) {
            });
    }
    else {
        var education_id = $('input[name=education_id]:checked').val();     
        if (education_id !== undefined) 
            location.href = site_url + 'survey/view?survey_flag='+survey_flag+'&education_id='+education_id+'&survey_id='+survey_id+'&newflag='+newflag+'&attached='+attached;    
        else
            swal({
                title: '', text: '설문할 교육과정이 선택되지 않았습니다.',
                confirmButtonText: '확인', allowOutsideClick: false, type: 'error'
            },
            function (isConfirm) {
            });
    }
}

//선택없이 신규설문을 보낸다.
function onCreateNewSurvey()
{
    location.href = site_url + 'survey/view?survey_flag=0&sms_available=1';
}

function onUploadEducationsExcel()
{    
    if ($("#excel_education_file")[0].files.length == 0) {
        swal({
            title: '', text: '업로드할 파일을 선택하세요.',
            allowOutsideClick: false,
            showConfirmButton: true,
            confirmButtonClass: 'btn-danger',
            cancelButtonClass: 'btn-default',
            type: 'error'
        },
        function (isConfirm) {
        });
        return;
    }

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
            formData.append("erp_excel", $("#excel_education_file")[0].files[0]);

            $.ajax({
                url: site_url + "survey/upload_educations_excel",
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

                        getEducationScheduleList(0);
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