var nStep = 1;
var converted_gojidoc_id = -1;
var em_ukey=-1;
var current_page = 1;
var total_count= 0;
var end_page = 1;
var page_per_count = 10;

$(function () {
    EditUpload.init();
});

function onGojiBytesLength() {
    var content = $('.goji_content_editor').val();
    var length = strlength(content);

    $('.goji_content_length').text(length);
    if(length > 69) {
        if(lms_flag ==0) {
            swal({title: '', text: '문자의 길이가 69 byte를 초과하였습니다.\n 70 byte 이하이면 SMS(단문),70 byte 이상이면 LMS(장문)으로 발송됩니다.',
                    confirmButtonText: '확인', allowOutsideClick: false, type: 'warning'},
                function(isConfirm) {});
        }
    }
}
function isKorean(value) {
    var numUnicode = value.charCodeAt(0);
    if (44031 <= numUnicode && numUnicode <= 55203 || 12593 <= numUnicode && numUnicode <= 12643) return true;
    return false;
}
//문자 하나씩 잘라서 한글 여부 체크해서 길이반환
function strlength(value) {
    var strlen = 0;
    var str;
    var len = value.length;
    var i;
    for (i = 0; i < len; i++) {
        str = value.substr(i, 1);
        if (isKorean(str)) {
            strlen = strlen + 2;
        } else {
            strlen = strlen + 1;
        }
    }
    return strlen;
}
//개별고지문서변환
function onGojiConvertClick() {

    if($('#gojidoc_converted_check').val() == 1)
    {
        swal({title: '', text: '이미 변환되여있습니다',
            confirmButtonText: '확인', allowOutsideClick: false, type: 'warning' }, function (isConfirm) {});
        return;
    }

    $.ajax({
        url: site_url + 'goji/convertGoji',
        type: 'POST',
        data: {
            file_name: $('#gojidoc_original_filename').val(),
            uploaded_file_name: $('#gojidoc_uploaded_filename').val()
        },
        error: function () {
            swal({title: '', text: '문서변환이 실패하였습니다!',
                    confirmButtonText: '확인', allowOutsideClick: false, type: 'error'},
                function(isConfirm) {});

            $('#gojidoc_converted_check').val("0");
        },
        success: function (ConvertedHtmlUrl) {
            if (ConvertedHtmlUrl == -1) {
                swal({title: '', text: '로그인후 사용이 가능합니다.',
                        confirmButtonText: '확인', allowOutsideClick: false, type: 'warning'},
                    function(isConfirm) {});
                return;
            }else if(ConvertedHtmlUrl == -2){
                swal({title: '', text: '문서변환이 실패하였습니다!',
                        confirmButtonText: '확인', allowOutsideClick: false, type: 'error'},
                    function(isConfirm) {});

                $('#gojidoc_converted_check').val("0");
                return;
            }

            let parseParam = jQuery.parseJSON(ConvertedHtmlUrl);

            $('#btnGojiDocPreview').removeAttr('disabled');

            let converted_file_url = "http://" + location.hostname + "/" + parseParam.file_path;

            converted_gojidoc_id = parseParam.gojidoc_id;

            $('#gojidoc_converted_file_url').val(converted_file_url);
            swal({title: '', text: '문서가 변환되었습니다',
                    confirmButtonText: '확인', allowOutsideClick: false, type: 'success'},
                function(isConfirm) {});
            $('#gojidoc_converted_check').val("1");

            $('#attached_content').html('');

            $('#attached_content').html("<iframe src='" + converted_file_url + "' " + "style='width:100%;height:100%'></iframe>");
            // bytesLength();
        }
    });
}
//개별고지자료를 봉사기에 올리기
function onUploadGojiVarClick(){

    if($('#gojivar_uploaded_check').val() == 1)
    {
        swal({title: '', text: '이미 업로드되었습니다.',
            confirmButtonText: '확인', allowOutsideClick: false, type: 'warning' }, function (isConfirm) {});
        return;
    }
    let PhoneNumberCheck = true;
    if($('#PhoneNumberCheck').is(':checked'))
        PhoneNumberCheck = true;
    else
        PhoneNumberCheck = false;
    $.ajax({
        url: site_url + 'goji/readGojiData',
        type: 'POST',
        data: {
            file_name           : $('#gojivar_original_filename').val(),
            uploaded_file_name  : $('#gojivar_uploaded_filename').val(),
            gojidoc_id          : converted_gojidoc_id,
            phone_check         : PhoneNumberCheck
        },
        error: function () {
            swal({title: '', text: '업로드가 실패하였습니다!',
                    confirmButtonText: '확인', allowOutsideClick: false, type: 'error'},
                function(isConfirm) {});

            $('#gojivar_uploaded_check').val("0");
        },
        success: function (ConvertedHtmlUrl) {

            if (ConvertedHtmlUrl == -1) {
                swal({
                        title: '', text: '업로드가 실패하였습니다!',
                        confirmButtonText: '확인', allowOutsideClick: false, type: 'error'
                    },
                    function (isConfirm) {
                    });
                $('#gojivar_uploaded_check').val("0");
                return;
            }
            $('#gojivar_uploaded_check').val("1");

            let parseParam = jQuery.parseJSON(ConvertedHtmlUrl);

            var sCheckResult = "<p>총 " + parseParam.total_count + "건중 " + parseParam.count + "건 등록가능합니다.</p>";

            if (parseParam.error_count > 0) {
                sCheckResult += "<p>등록불가능한 전화번호</p>";
                sCheckResult += "<p>(" + parseParam.error_mobile + ")</p>";
            }

            $('#gojivar_check').html(sCheckResult);
            $('#btnGojiVarPreview').removeAttr('disabled');

            //-------- 변수파일선택div숨기기 ----------

            $('.attached-gojiVar-container').hide();
            $('#btnNextArea').hide();

            $('.attached-gojiVar-registry-container').show();
        }
    });
}

//검사된 개별고지변수들을 자료기지에 등록
function onRegistryGojiVar(){
    $.ajax({
        url: site_url + 'goji/registryGojiVar',
        type: 'POST',
        error: function () {
            swal({title: '', text: '등록이 실패하였습니다!',
                    confirmButtonText: '확인', allowOutsideClick: false, type: 'error'},
                function(isConfirm) {});
            $('#gojivar_registry_check').val("0");
        },
        success: function (result) {
            nStep ++;
            if(result == -1){
                swal({title: '', text: '로그인후 시도하십시오.',
                        confirmButtonText: '확인', allowOutsideClick: false, type: 'warning'},
                    function(isConfirm) {});
                return;
            }else if (result == -2) {
                swal({
                        title: '', text: '등록이 실패하였습니다!!',
                        confirmButtonText: '확인', allowOutsideClick: false, type: 'error'
                    },
                    function (isConfirm) {
                    });
                $('#gojivar_registry_check').val("0");
                return;
            }

            $('#gojivar_registry_check').val("1");

            let parseParam = jQuery.parseJSON(result);

            var sRegistryResult = "<a>총 " + parseParam.row_count + "건의 휴대폰번호 및 데이터등록을 완료하였습니다.</a>";
            $('#registry_result').html(sRegistryResult);

        // -------- 대역번수에 em_ukey값을 설정 ----------
            em_ukey = parseParam.em_ukey;

        //-------- 고지변수미리보기테블만들기 -----------
            var tableHtml = "<table class='goji_preview_table'><thead><tr>";
            tableHtml += "<th style = 'width:100px;'>이름</th><th style = 'width:130px;'>수신번호</th>";

            for(var index = 1; index <= parseParam.column_count; index++){
                tableHtml += "<th style='width:100px;'>항목" + index + "</th>";
            }
            tableHtml += "</tr><tbody>";
            for(var nRow = 0; nRow < parseParam.row_count; nRow++){

                tableHtml += "<tr>";
                tableHtml += "<td style = 'width:100px;'>" + parseParam.goji_data[nRow].name + "</td>";
                tableHtml += "<td style = 'width:130px;'>" + parseParam.goji_data[nRow].hp + "</td>";
                var lstVar = parseParam.goji_data[nRow].var.split("|");
                for(var nCol = 0; nCol < parseParam.column_count; nCol++){
                    if(nCol < lstVar.length)
                        tableHtml += "<td style='width:100px;'>" + lstVar[nCol] + "</td>";
                    else
                        tableHtml += "<td style='width:100px;'></td>";
                }
                tableHtml += "</tr>";
            }

            tableHtml += "</tbody></table>";

            $("#gojivar_content").html(tableHtml);

        //-------- 고지변수문서선택div숨기기 ----------

            $('.attached-gojiVar-registry-container').hide();

            $('#btnNextArea').show();
            $('.attached-gojiVar-preview-container').show();

        }
    });
}

function NextStep(){
    //다음단계이행가능성 검사
    switch(nStep){
        case 1: //고지양식파일선택검사단계
            if($('#gojidoc_uploaded_filename').val() == ""){
                swal({title: '', text: '개별고지문서를 선택해야 합니다.',
                        confirmButtonText: '확인', allowOutsideClick: false, type: 'warning'},
                    function(isConfirm) {});
                return;
            }
            if($('#gojidoc_converted_check').val() != 1){
                swal({title: '', text: '개별고지문서를 변환해야 합니다',
                        confirmButtonText: '확인', allowOutsideClick: false, type: 'warning'},
                    function(isConfirm) {});
                return;
            }
            nStep ++;
            break;
        case 2://고지변수파일선택검사단계
            if($('#gojivar_uploaded_filename').val() == ""){
                swal({title: '', text: '고지변수파일를 선택해야 합니다.',
                        confirmButtonText: '확인', allowOutsideClick: false, type: 'warning'},
                    function(isConfirm) {});
                return;
            }
            if($('#gojivar_uploaded_check').val() != 1){
                swal({title: '', text: '고지변수파일을 올리적재해야 합니다.',
                        confirmButtonText: '확인', allowOutsideClick: false, type: 'warning'},
                    function(isConfirm) {});
                return;
            }
            nStep ++;
            break;
        case 3://sms전송가능성검사단계
            if( $('#gojivar_registry_check').val() != 1 || em_ukey == -1 || em_ukey == null){
                swal({title: '', text: '고지변수를 등록해야 합니다.',
                        confirmButtonText: '확인', allowOutsideClick: false, type: 'warning'},
                    function(isConfirm) {});
                return;
            }

            nStep ++;
            break;
    }
    //이행후 결과동작구현
    switch(nStep){
        case 1: //고지양식파일선택단계
            break;
        case 2: //고지변수파일선택단계
            EditUpload.init();
            $('.attached-gojiDoc-container').hide();
            $('.attached-gojiVar-container').show();
            break;
        case 3: //고지변수등록/미리보기단계
            break;
        case 4: //sms전송으로 넘는단계
            location.href = site_url + 'notice?em_ukey=' + em_ukey + '&goji=1';
            break;
    }
}

var EditUpload = function () {

    var handleFile = function() {

        g_uploader = new plupload.Uploader({

            runtimes : 'html5,flash,silverlight,html4',
            multi_selection : false,

            browse_button : (nStep == 1 ? document.getElementById('gojidoc_pick_file_area') : document.getElementById('gojivar_pick_file_area')),
            container: (nStep == 1 ? document.getElementById('gojidoc_file_container'): document.getElementById('gojivar_file_container')),

            url : site_url + 'survey/upload_file',
            drag_and_drop: true,
            drop_element: $('#gojiDoc_uploader_filelist')[0],
            //
            // filters : {
            //     max_file_size : max_file_size + 'mb',
            //     // mime_types: [
            //     //     { title : "Hwp files", extensions : "hwp" },
            //     //     // { title : "Doc files", extensions : "doc,docx" },
            //     //     { title : "HTML files", extensions : "htm,html" },
            //     //     { title : "PDF files", extensions : "pdf" }
            //     // ]
            // },

            unique_names : true,

            init: {

                PostInit: function() {

                    $('#gojiDoc_uploader_filelist').on('click', '.added-files .remove', function(){
                        var uploaded_file_id = $(this).parent('.added-files').attr("id");
                        uploaded_file_id = uploaded_file_id.substr('uploaded_file_'.length);
                        g_uploader.removeFile(uploaded_file_id);
                        $(this).parent('.added-files').remove();
                    });

                    $('#gojiVar_uploader_filelist').on('click', '.added-files .remove', function(){
                        var uploaded_file_id = $(this).parent('.added-files').attr("id");
                        uploaded_file_id = uploaded_file_id.substr('uploaded_file_'.length);
                        g_uploader.removeFile(uploaded_file_id);
                        $(this).parent('.added-files').remove();
                    });
                },

                FilesAdded: function(up, files) {
                    while(g_uploader.files.length > 1) {
                        g_uploader.removeFile(g_uploader.files[0].id);
                    }

                    if (files.length > 0) {
                        var file = files[0];

                        if(nStep == 1){

                            $('#gojiDoc_uploader_filelist').html("");
                            $('#gojiDoc_uploader_filelist').append('<div class="alert alert-warning added-files" id="uploaded_file_' + file.id + '">' + file.name + '(' + plupload.formatSize(file.size) + ') <span class="status label label-info"></span></div>');

                            // 미리 보기 단추 비활성화
                            $('#btnGojiDocPreview').prop('disabled', true);
                            // 변환단추 활성화
                            $('#btnGojiDocConvert').removeAttr('disabled');
                        }else if(nStep == 2){
                            gojiVar_upload_filename = file;
                            $('#gojiVar_uploader_filelist').html("");
                            $('#gojiVar_uploader_filelist').append('<div class="alert alert-warning added-files" id="uploaded_file_' + file.id + '">' + file.name + '(' + plupload.formatSize(file.size) + ') <span class="status label label-info"></span></div>');

                            // 미리 보기 단추 비활성화
                            $('#btnGojiVarPreview').prop('disabled', true);
                            // 올리적재단추 활성화
                            $('#btnGojiVarUpload').removeAttr('disabled');
                        }

                        // 화일선택후 자동으로 업로드
                        g_uploader.start();
                        myApp.showProgress();
                    }
                },

                FilesRemoved: function() {
                    if (g_uploader.files.length == 0) {

                    }
                },

                UploadProgress: function(up, file) {
                    var loaded = file.loaded;
                    var loaded_unit = 'KB';
                    var size = file.size;
                    var size_unit = 'KB';

                    if (file.loaded > 1024 * 1024 * 1024) {
                        loaded = file.loaded / (1024 * 1024 * 1024);
                        loaded_unit = 'GB';
                    }
                    else if (file.loaded > 1024 * 1024) {
                        loaded = file.loaded / (1024 * 1024);
                        loaded_unit = 'MB';
                    }
                    else if (file.loaded > 1024) {
                        loaded = (file.loaded / 1024);
                        loaded_unit = 'KB';
                    }

                    if (file.size > 1024 * 1024 * 1024) {
                        size = file.size / (1024 * 1024 * 1024);
                        size_unit = 'GB';
                    }
                    else if (file.size > 1024 * 1024) {
                        size = file.size / (1024 * 1024);
                        size_unit = 'MB';
                    }
                    else if (file.size > 1024) {
                        size = (file.size / 1024);
                        size_unit = 'KB';
                    }

                    loaded = Math.floor(loaded * 100) / 100;
                    size = Math.floor(size * 100) / 100;

                    var label = file.percent + '% (' + loaded + loaded_unit + ')';

                    myApp.updateProgress(file.percent, label);
                },

                FileUploaded: function(up, file, response) {
                    afterUploaded(response.response);
                    myApp.hideProgress();
                    myApp.updateProgress(0, '');
                },

                Error: function(up, err) {
                    myApp.hideProgress();
                    myApp.updateProgress(0, '');
                    console.log(err);
                    swal({title: '', text: err.message,
                            confirmButtonText: '확인', allowOutsideClick: false, type: 'error'},
                        function(isConfirm) {});
                }
            }
        });

        g_uploader.init();
    }

    return {
        init: function () {
            handleFile();
        }
    };

}();


// 첨부문서 올리적재 끝난후
function afterUploaded(data) {
    var obj = jQuery.parseJSON(data);
    $('#gojidoc_converted_check').val("0");
    if(nStep == 1) {
        $('#btnGojiDocPreview').prop('disabled', true);
        $('#gojidoc_converted_check').val("0");
    }else {
        $('#btnGojiVarPreview').prop('disabled', true);
        $('#gojivar_uploaded_check').val("0");
    }
    if (obj.status == 'OK') {
        var extCheck = true;
        if(nStep == 2 && obj.file_name.indexOf('xls') === -1 && obj.file_name.indexOf('xlsx') === -1) {
            swal({
                    title: '', text: "엑셀파일만 업로드할수 있습니다.",
                    confirmButtonText: '확인', allowOutsideClick: false, type: 'warning'
                },
                function (isConfirm) {});
            extCheck = false;
        }else if(nStep == 1 && obj.file_name.indexOf('hwp') === -1 && obj.file_name.indexOf('pdf') === -1 && obj.file_name.indexOf('doc') === -1 && obj.file_name.indexOf('html') === -1){
            swal({title: '', text: "hwp,pdf,doc,html 문서만 포함할수있습니다.",
                    confirmButtonText: '확인', allowOutsideClick: false, type: 'warning'},
                function(isConfirm) {});
            extCheck = false;
        }

        if(!extCheck){
            while(g_uploader.files.length > 0) {
                g_uploader.removeFile(g_uploader.files[0].id);
            }
            //g_uploader.disableBrowse(false);
            if(nStep == 1) {
                $('#gojidoc_uploaded_filename').val('');
                $('#gojidoc_original_filename').val('');
                $('#gojiDoc_uploader_filelist').html("선택된 파일이 없음");
                $('#btnGojiDocConvert').prop('disabled', true);
            }else if(nStep == 2){
                $('#gojivar_uploaded_filename').val('');
                $('#gojivar_original_filename').val('');
                $('#gojiVar_uploader_filelist').html("선택된 파일이 없음");
                $('#btnGojiVarUpload').prop('disabled', true);
            }
        } else {
            // 화일 올리적재 성공
            // 미리 보기 단추 활성화
            if(nStep == 1) {
                $('#btnGojiDocConvert').removeAttr('disabled');
                $('#gojidoc_uploaded_filename').val(obj.file_name);
                $('#gojidoc_original_filename').val(obj.origin_file_name);
            }else if(nStep == 2){
                $('#btnGojiVarUpload').removeAttr('disabled');
                $('#gojivar_uploaded_filename').val(obj.file_name);
                $('#gojivar_original_filename').val(obj.origin_file_name);
            }
        }
    }
    else {

        while(g_uploader.files.length > 0) {
            g_uploader.removeFile(g_uploader.files[0].id);
        }
        //g_uploader.disableBrowse(false);
        swal({title: '', text: obj.msg,
                confirmButtonText: '확인', allowOutsideClick: false, type: 'error'},
            function(isConfirm) {});
        if(nStep == 1) {
            $('#gojidoc_uploaded_filename').val('');
            $('#gojidoc_original_filename').val('');
        }else if(nStep == 2){
            $('#gojivar_uploaded_filename').val('');
            $('#gojivar_original_filename').val('');
        }
    }
    return;
}

//고지변수미리보기에서 <되돌이> 단추를 눌렀을때
function onShowDocumentArea(){
    $('#documentArea').show();
    $('#preview_area').hide();
    $('#gojivar_preview_area').hide();
}
//고지변수미리보기대화창 현시
function onPreviewGojiVar() {
    $('#documentArea').hide();
    $('#gojivar_preview_area').show();
}
//고지문서에서 <미리보기> 단추를 눌렀을때 처리
function onGojiShowClick() {

    $('#documentArea').hide();
    $('#preview_area').show();
}
//미리보기에서 <되돌이> 단추를 눌렀을때
function onShowDocumentArea(){
    $('#documentArea').show();
    $('#preview_area').hide();
}
//고지양식목록보기사건처리
$("#show_gojiList").click(function() {
    getGojiList(0);
});
//고지양식목록얻기
function getGojiList(flag){

    if(flag == 0) {
        current_page = 1;
    }
    var doc_name =  $.trim($("#docName").val());
    var type = $('#goji_type').val();

    $.ajax({
        url: site_url + "goji/getGojiList",
        cache:false,
        timeout : 10000,
        dataType:'html',
        type: 'POST',
        data: {
            type: type,
            doc_name: doc_name,
            page:current_page,
        },
        success: function(data) {
            if (data == -1) {
                swal({title: '', text: '로그하십시오.',
                        confirmButtonText: '확인', allowOutsideClick: false, type: 'warning'},
                    function(isConfirm) {});
                $('#questions_modal').modal('hide');
                return ;
            }

            if (data !== 'err') {
                $('#goji_list').html(data);
                addpageEventlisner();
                $('#questions_modal').modal('show');
            }
        },
        error: function (xhr, ajaxOptions, thrownError) {
            $('#questions_modal').modal('hide');
            swal({title: '', text: xhr.status,
                    confirmButtonText: '확인', allowOutsideClick: false, type: 'error'},
                function(isConfirm) {});
        }
    });
}
//검색단추사건처리
function SearchBtnClick(){
    getGojiList(0);
}
//고지문서<삭제>처리
function onRemoveDoc(edoc_ukey){
    $.ajax({
        url: site_url + "goji/removeDoc",
        cache:false,
        timeout : 10000,
        type: 'POST',
        data: {
            edoc_ukey:edoc_ukey,
        },
        success: function(data) {
            if (data == -1) {
                swal({title: '', text: '로그하십시오.',
                        confirmButtonText: '확인', allowOutsideClick: false, type: 'warning'},
                    function(isConfirm) {});
                $('#questions_modal').modal('hide');
                return ;
            }if (data == 0) {
                $('#questions_modal').modal('hide');
                swal({title: '', text: '삭제실패',
                        confirmButtonText: '확인', allowOutsideClick: false, type: 'error'},
                    function(isConfirm) {});
                return ;
            }

            if (data == 1) {
                getGojiList(0);
            }
        },
        error: function (xhr, ajaxOptions, thrownError) {
            $('#questions_modal').modal('hide');
            swal({title: '', text: xhr.status,
                    confirmButtonText: '확인', allowOutsideClick: false, type: 'error'},
                function(isConfirm) {});
        }
    });
}
//고지문서<사용>처리
function onUseDoc(edoc_ukey){
    $.ajax({
        url: site_url + "goji/useDoc",
        cache:false,
        timeout : 10000,
        type: 'POST',
        data: {
            edoc_ukey:edoc_ukey,
        },
        success: function(data) {
            $('#questions_modal').modal('hide');

            if (data == -1) {
                swal({title: '', text: '로그하십시오.',
                        confirmButtonText: '확인', allowOutsideClick: false, type: 'warning'},
                    function(isConfirm) {});
                return ;
            }

            let parseParam = jQuery.parseJSON(data);

            converted_gojidoc_id = parseParam.gojidoc_id;

            nStep ++;

            EditUpload.init();
            $('.attached-gojiDoc-container').hide();
            $('.attached-gojiVar-container').show();
        },
        error: function (xhr, ajaxOptions, thrownError) {
            $('#questions_modal').modal('hide');
            swal({title: '', text: xhr.status,
                    confirmButtonText: '확인', allowOutsideClick: false, type: 'error'},
                function(isConfirm) {});
        }
    });
}

//------------- 고지양식목록 페지절환처리부분 ---------------
function go_page(page){
    current_page = page;
    getGojiList(1);
}
function addpageEventlisner()
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
        $('.serv_t').text('문서가 없습니다.');
        $('.serv_t').css('color','#e02222');
    }

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
                        $( "body" ).append(data);
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
        }
    });
}
//절환tab생성처리
function Paging(total_count,  pageSize, cur_page) {

    mod_page = 0;
    total_page= 0;

    total_page = parseInt(parseInt(total_count)/parseInt(pageSize));
    mod_page = parseInt(total_count) % parseInt(pageSize);
    if(mod_page !=0) {
        total_page = total_page +1;
    }

    if(cur_page > total_page)
        cur_page=total_page;
    prev_page = parseInt(cur_page) - 1;
    if (prev_page < 1)
        prev_page = 1;
    next_page = parseInt(cur_page) + 1;
    if (next_page > total_page)
        next_page = total_page;

    class_first = '';
    class_prev = '';
    class_next = '';
    class_last = '';

    page_per_count_statue = "display: inline-block;";
    if(total_count < 11) {
        class_first = 'disabled';
        class_prev = 'disabled';
        class_next = 'disabled';
        class_last = 'disabled';
        page_per_count_statue = "display: none;";
    }

    page_counts = 6;
    var html = "";

    start_page = cur_page - (parseInt)(page_counts / 2);
    end_page = cur_page + (parseInt)(page_counts / 2);
    if (start_page < 1) {
        start_page = 1;
        if (page_counts < total_page)
            end_page = page_counts;
        else
            end_page = total_page;
    }
    if (end_page > total_page) {
        end_page = total_page;
        if (page_counts < total_page)
            start_page = total_page - page_counts + 1;
        else
            start_page = 1;
    }

    page = start_page;
    if(total_page ==1){
        end_page = 0;
    }
    class_pagination = '';
    if(total_page == 1)
        class_pagination = 'hidden';

    html+='<ul class="list-unstyled" style="display: inline-block;">';
    html+='<li class="'+class_first+'"><a href="javascript:go_page(1)">❮❮</a></li>';
    html+='<li class="'+class_prev+'"><a href="javascript:go_page('+ prev_page+')">❮</a></li>';
    for (page = start_page; page <= end_page; page++) {
        active = '';
        if (page == cur_page)
            active = 'active';
        html+='<li class="'+active+'"><a href="javascript:go_page('+page+')">'+page+'</a></li>';
    }
    html+='<li class="'+class_next+'"><a href="javascript:go_page('+ next_page+')">❯</a></li>';
    html+='<li class="'+class_last+'"><a href="javascript:go_page('+ total_page+')">❯❯</a></li>';
    html+='</ul>';
    html+='<div style="'+page_per_count_statue+' margin-left: 30px;font-size: 14px;">';
    html+='</div>';
    return html;
}
