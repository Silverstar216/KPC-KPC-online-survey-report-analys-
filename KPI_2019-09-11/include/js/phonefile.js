var g_uploader;
var max_file_size = 100;

$(function () {
    if ($('#user_level').val() == "" || $('#user_level').val() == "test") {
        $('#pick_file').click(function () {return false;});
        $('#uploader_filelist').click(function () {return false;});
    }

    NumberUpload.init();


    
});

var NumberUpload = function () {

    var handleFile = function() {

        g_uploader = new plupload.Uploader({

            runtimes : 'html5,flash,silverlight,html4',
            multi_selection : false,

            browse_button : document.getElementById('pick_file_area_phone'),
            container: document.getElementById('file_container'),

            url : site_url + 'phone/upload_file',
            drag_and_drop: true,
            drop_element: $('#uploader_filelist')[0],
           
            unique_names : true,

            init: {
                PostInit: function() {
                    $('#uploader_filelist').on('click', '.added-files .remove', function(){
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
                        $('#uploader_filelist').html("");
                        $('#uploader_filelist').append('<div class="alert alert-warning added-files" id="uploaded_file_' + file.id + '">' + file.name + '(' + plupload.formatSize(file.size) + ') <span class="status label label-info"></span></div>');

                        // 미리 보기 단추 비활성화

                        $('#save').removeAttr('disabled');

                        // 화일선택후 자동으로 업로드
                        g_uploader.start();
                        myApp.showProgress();
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
                    if(err.message==="화일 확장자 오류.") {
                        swal({title: '', text: " xlsx 확장자로 저장하여 업로드 하십시오.\n" +
                                "※ 주의) 암호가 걸려있는 파일은 처리되지 않습니다!!!(암호 해제후 업로드)",
                                confirmButtonText: '확인', allowOutsideClick: false, type: 'error'},
                            function(isConfirm) {});
                    }else {
                        swal({title: '', text: err.message,
                                confirmButtonText: '확인', allowOutsideClick: false, type: 'error'},
                            function(isConfirm) {});
                    }

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
    if (obj.status == 'OK') {
        if(obj.file_name.indexOf('xls') === -1 && obj.file_name.indexOf('xlsx') === -1){

            swal({title: '', text: "엑셀파일만 업로드할수 있습니다.",
                    confirmButtonText: '확인', allowOutsideClick: false, type: 'error'},
                function(isConfirm) {});

            while(g_uploader.files.length > 0) {
                g_uploader.removeFile(g_uploader.files[0].id);
            }
            //g_uploader.disableBrowse(false);
            $('#uploader_filelist').html("선택된 파일이 없음");
            $('#attached_file_name').val('');
            $('#attached_origin_file_name').val('');

        } else {
            // 화일 올리적재 성공
            // 미리 보기 단추 활성화
            $('#save').removeAttr('disabled');
            $('#attached_file_name').val(obj.file_name);
            $('#attached_origin_file_name').val(obj.origin_file_name);
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
        $('#attached_file_name').val('');
        $('#attached_origin_file_name').val('');
    }
    return;
}



function post() {
    var groups = $('#groups').val();
     if(groups == null || groups == 'all')
     {
          swal({title: '', text: '그룹을 선택하세요.',
                    confirmButtonText: '확인', allowOutsideClick: false, type: 'error'},
                    function(isConfirm) { });
          return;
     }

    var attached_file_name = $('#attached_file_name').val();
    var attached_origin_file_name = $('#attached_origin_file_name').val();

    var params = {};
    params.attached_file_name = attached_file_name;
    params.attached_origin_file_name = attached_origin_file_name;
    params.groups = groups;
    var valid = true;
    myApp.showProgress();
    myApp.updateProgress(100, '전화번호삽입중');
    $.ajax({
        url: site_url + 'phone/post',
        cache: false,
        timeout:500000,
        dataType: 'text',
        data: params,
        type: 'POST',

        success: function (data) {
            myApp.hideProgress();
            myApp.updateProgress(0, '');

                $('#attached_file_name').val('');
                $('#attached_origin_file_name').val('');
                $('.added-files').remove();
                $('.dropzone-file-area').html('선택된 파일없음');
                while(g_uploader.files.length > 0) {
                    g_uploader.removeFile(g_uploader.files[0].id);
                }
            $('#save').prop('disabled', true);

                var msg = data.split('@');
                    swal({
                            title: '', text: msg[0]+'\n'+msg[1],

                            confirmButtonText: '확인', allowOutsideClick: false, type: 'success'
                        },
                        function (isConfirm) {
                        });

        },
        error: function (xhr, ajaxOptions, thrownError) {
            myApp.hideProgress();
            myApp.updateProgress(0, '');
            $('#attached_file_name').val('');
            $('#attached_origin_file_name').val('');
            $('.added-files').remove();
            $('.dropzone-file-area').html('선택된 파일없음');
            while(g_uploader.files.length > 0) {
                g_uploader.removeFile(g_uploader.files[0].id);
            }
            $('#save').prop('disabled', true);
            swal({
                    title: '', text: xhr.status,
                    confirmButtonText: '실패', allowOutsideClick: false, type: 'error'
                },
                function (isConfirm) {

                });

        }
    });
    

}
