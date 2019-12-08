$(function () {

    if ($('#lost_validation_error').val() != " " && $('#lost_validation_error').val() != undefined) {
        alert($('#lost_validation_error').val().replace(/<\/?[^>]+(>|$)/g, ""));
    }
    if ($('#lost_error').val() != "" && $('#lost_error').val() != undefined) {
        alert($('#lost_error').val().replace(/<\/?[^>]+(>|$)/g, ""));
    }
    refresh();
    $(".login_password").keydown(function (e) {
        if (e.keyCode === 13) {
            e.preventDefault();
            onLoginClick();
        }
        /*if (e.keyCode == 13) onLoginClick();*/
    });

});

function onJoinClick() {
    if ($('.cb_join1').prop('checked') == false) {
        alert('회원가입약관의 내용에 동의하셔야 회원가입 하실 수 있습니다.');
    } else if ($('.cb_join2').prop('checked') == false) {
        alert('개인정보처리방침안내의 내용에 동의하셔야 회원가입 하실 수 있습니다.');
    } else {
        location.href = site_url + 'join/edit_profile';
    }
}


function onCancelClick() {
    location.href = site_url + 'index';
}

function go_main() {
    location.href = site_url + 'index';
}



// header로그인에서 가입
function onLoginClick() {
    var valide = true;
    localStorage.clear();
    uid = $('.login_uid').val();
    password = $('.login_password').val();
    
    var saveAccount = 0;
    $("input:checkbox[name=saveAccount]:checked").each(function () {
        saveAccount = 1;
    });

    if (uid == '') {
        alert('사용자아이디를 입력해 주세요');
        valide = false;
    } else if (password == '') {
        alert('비밀번호를 입력해 주세요');
        valide = false;
    }
    if(valide) {
        localStorage.setItem('uid', uid);
        localStorage.setItem('password', password);
        $.ajax({
            url: site_url + 'join/login',
            type: 'POST',
            data: {
                uid: uid,
                password: password, 
                saveAccount: saveAccount, 
            },
            error: function () {

            },
            success: function (data) {                
                //게시판용session을 생성하고 index페지로 가기
                if (Number(data) > 0) {
                    $.ajax({
                        url: site_url + 'adminsystem/boardSession.php', // kind : 0 ------ 세션설정
                        type: 'POST',
                        data: {
                            kind: 0,
                            mb_id: localStorage.getItem("uid"),
                            mb_password: localStorage.getItem("password"),
                            mb_login: "login"
                        },
                        error: function () {
                        },
                        success: function (data) {
                            location.href = site_url + 'index';
                        }
                    });
                } else if (data == "-2") {
                    alert('당신의 아이디는 차단되였습니다. 관리자에게 문의하여 주십시요');
                } else {
                    swal({title: '', text: '아이디 혹은 비밀번호가 맞지 않습니다.',
                            confirmButtonText: '확인', allowOutsideClick: false, type: 'error'},
                        function(isConfirm) {});
                }
            }
        });
    }
}

//로그인창에서 가입
function login_click() {
    var valide = true;
    localStorage.clear();
    uid = $('#username').val();
    password = $('#password').val();
    if (uid == '') {
        alert('사용자아이디를 입력해 주세요');
        valide = false;
    }else if (password == '') {
        alert('암호를 입력해 주세요');
        valide = false;
    }
    if(valide) {
        localStorage.setItem('uid', uid);
        localStorage.setItem('password', password);
        $.ajax({
            url: site_url + 'join/login',
            type: 'POST',
            data: {
                uid: uid,
                password: password
            },
            error: function () {

            },
            success: function (data) {
                //게시판용session을 생성하고 index페지로 가기
                if (Number(data) > 0) {
                    $.ajax({
                        url: site_url + 'adminsystem/boardSession.php', // kind : 0 ------ 세션설정
                        type: 'POST',
                        data: {
                            kind: 0,
                            mb_id: localStorage.getItem("uid"),
                            mb_password: localStorage.getItem("password"),
                            mb_login: "login"
                        },
                        error: function () {
                        },
                        success: function (data) {
                            location.href = site_url + 'index';
                        }
                    });
                } else if (data == "-2") {
                    alert('당신의 아이디는 차단되였습니다. 관리자에게 문의하여 주십시요');
                } else {
                    swal({title: '', text: '아이디 혹은 비밀번호가 맞지 않습니다.',
                            confirmButtonText: '확인', allowOutsideClick: false, type: 'error'},
                        function(isConfirm) {});
                }
            }
        });
    }
}


function signout() {
    $.ajax({
        url: site_url + 'adminsystem/boardSession.php',//1 : 세션설정해제
        type: 'POST',
        data: {
            kind : 1,
            mb_login: "logout"
        },
        error: function () {
        },
        success: function (data) {
            location.href = site_url + 'join/logout'
        }
    });

}

function openAdminPage() {
    /*swal({title: '', text: '작업중입니다...',
            confirmButtonText: '확인', allowOutsideClick: false, type: 'error'},
        function(isConfirm) {});*/
    /*location.href = site_url + 'adminsystem/adm/index.php';*/
    $.ajax({
        url: site_url + 'adminsystem/bbs/login_check.php',
        type: 'POST',
        data: {
            mb_id: localStorage.getItem("uid"),
            mb_password: localStorage.getItem("password"),
            mb_login: "login"
        },
        error: function () {

        },
        success: function (data) {
            if (data == "1") {
                location.href = site_url + 'adminsystem/adm/index.php';
            }
        }
    });
}

function refresh() {

    $.ajax({
        url: site_url + 'join/refresh_captcha',

        error: function () {

        },
        success: function (data) {
            $('#captcha_area').html("");
            $('#captcha_area').html(data);
        }
    });
}

function check_uid() {
    var uid = $('#join_uid').val();
    var pattern = /(^[a-zA-Z0-9\_]+$)/;
    if (uid == '') {
        alert('사용자아이디를 입력해 주세요');


    } else if (!pattern.test(uid)) {
        alert('영문 또는 영문, 숫자조합으로 6자 이상으로 등록하십시요');


    } else if (uid.length < 6) {

        alert('영문 또는 영문, 숫자조합으로 6자 이상으로 등록하십시요');

    } else {

        $.ajax({
            url: site_url + 'join/check_user_id',
            type: 'POST',
            data: {
                join_uid: $('#join_uid').val(),

            },
            error: function () {

            },
            success: function (data) {
                if (data == -1) {
                    alert('그 아이디는 지금 사용중입니다. 다른 아이디를 입력하여주세요');

                } else {
                    alert('증복되는 아이디가 없습니다.');
                }
            }
        });
    }
}

function lost_captcha_refresh() {
    location.href = site_url + 'join/pass_lost';
}

function onConfirmClick() {
    var uid = $('#join_uid').val();
    var company = $('#join_company').val();
    var password = $('#join_password').val();
    var password_confirm = $('#join_password_confirm').val();
    var name = $('#join_name').val();

    var phone = $('#join_phone').val();
    var mobile = $('#join_mobile').val();
    var fax = $('#join_fax').val();
    var email = $('#join_email').val();
    var captcha = $('#join_captcha').val();
    valid = true;
    var pattern = /(^[a-zA-Z0-9\_]+$)/;
    if (uid == '') {
        $("#join_uid").css("border", "1.5px solid #d74646");
        $("#join_uid").attr("placeholder", "사용자아이디를 입력해 주세요");
        valid = false;
    } else if (!pattern.test(uid)) {

        $("#join_uid").css("border", "1.5px solid #d74646");
        $("#join_uid").val("");
        $("#join_uid").attr("placeholder", "영문 또는 영문, 숫자조합으로 6자 이상으로 등록하십시요");
        valid = false;
    } else if (uid.length < 6) {

        $("#join_uid").val("");
        $("#join_uid").css("border", "1.5px solid #d74646");
        $("#join_uid").attr("placeholder", "영문 또는 영문, 숫자조합으로 6자 이상으로 등록하십시요");
        valid = false;
    } else {
        $("#join_uid").css("border", "1px solid #ccc");
    }

    if (password == '') {
        $("#join_password").css("border", "1.5px solid #d74646");
        $("#join_password").attr("placeholder", "비밀번호를 입력해 주세요");
        valid = false;
    } else {
        var re = /^(?=.*[A-Za-z])(?=.*\d)(?=.*[$@$!%*#?&])[A-Za-z\d$@$!%*#?&]{8,}$/;
        if (!re.test(password)) {
            $("#join_password").css("border", "1.5px solid #d74646");
            $("#join_password").val("");
            $("#join_password").attr("placeholder", "최소 8자리에 숫자,문자,특수문자 각각 1개이상 포함하세요");
            valid = false;
        } else
            $("#join_password").css("border", "1px solid #ccc");
    }
    if (password_confirm != password) {
        $("#join_password_confirm").css("border", "1.5px solid #d74646");
        $("#join_password_confirm").val("");
        $("#join_password_confirm").attr("placeholder", "비밀번호확인이 틀립니다");
        valid = false;
    } else
        $("#join_password_confirm").css("border", "1px solid #ccc");
    if (company == '') {
        $("#join_company").css("border", "1.5px solid #d74646");
        $("#join_company").attr("placeholder", "기관명을 입력하여 주세요");
        valid = false;
    } else
        $("#join_company").css("border", "1px solid #ccc");
    if (name == '') {
        $("#join_name").css("border", "1.5px solid #d74646");
        $("#join_name").attr("placeholder", "담당자명을 입력해 주세요");
        valid = false;
    } else
        $("#join_name").css("border", "1px solid #ccc");

    if (mobile == '') {
        $("#join_mobile").css("border", "1.5px solid #d74646");
        $("#join_mobile").attr("placeholder", "휴대폰번호를 입력해 주세요");
        valid = false;
    } else
        $("#join_mobile").css("border", "1px solid #ccc");
    if (!checkPhoneNumber(mobile)) {
        $("#join_mobile").css("border", "1.5px solid #d74646");
        $("#join_mobile").val("");
        valid = false;
        $("#join_mobile").attr("placeholder", "정확한 휴대폰번호를 입력해 주세요");
    }
    if (email == '') {
        $("#join_email").css("border", "1.5px solid #d74646");
        $("#join_email").attr("placeholder", "이메일을 입력해 주세요");
        valid = false;
    } else {
        var re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
        if (!re.test(email)) {
            $("#join_email").css("border", "1.5px solid #d74646");
            $("#join_email").val("");
            $("#join_email").attr("placeholder", "올바른 이메일 주소를 입력해 주세요");
            valid = false;
        } else
            $("#join_email").css("border", "1px solid #ccc");
    }
    if (captcha == '') {
        $("#join_captcha").css("border", "1.5px solid #d74646");
        $("#join_captcha").attr("placeholder", "자동복사방지숫자를 입력해 주세요");
        valid = false;
    } else
        $("#join_captcha").css("border", "1px solid #ccc");
    var join_mobile_verify = $("#join_mobile_verify").val();
    var is_mobile_verify = $("#is_mobile_verify").val();
    if(is_mobile_verify =="1" && join_mobile_verify == ''){
        $("#join_mobile_verify").val("");
        $("#join_mobile_verify").css("border", "1.5px solid #d74646");
        $("#join_mobile_verify").attr("placeholder", "휴대폰번호 인증코드를 입력해 주세요");
        valid = false;
    } else {
        $("#join_mobile_verify").css("border", "1px solid #ccc");
    }
    if (valid) {
        $.ajax({
            url: site_url + 'join/join',
            type: 'POST',
            data: {
                join_uid: uid,
                join_password: password,
                join_name: name,
                join_company: company,
                join_phone: phone,
                join_mobile: mobile,
                join_fax: fax,
                join_email: email,
                join_captcha: captcha,
                join_mobile_verify:join_mobile_verify
            },
            error: function () {

            },
            success: function (data) {
                if (Number(data) > 0) {

                            location.href = site_url + 'join/register_result';

                } else if (data == -1) {
                    $("#join_uid").css("border", "1.5px solid #d74646");
                    $("#join_uid").val("");
                    $("#join_uid").attr("placeholder", "중복된 아이디입니다");
                    valid = false;
                } else if (data == "-2") {
                    $("#join_captcha").css("border", "1.5px solid #d74646");
                    $("#join_captcha").val("");
                    $("#join_captcha").attr("placeholder", "그림의 숫자를 정확히 입력해주세요");
                    valid = false;
                } else if (data == "-3") {
                    $("#join_email").css("border", "1.5px solid #d74646");
                    $("#join_email").val("");
                    $("#join_email").attr("placeholder", "중복된 이메일입니다");
                    valid = false;
                } else if(data =="-4"){
                    $("#join_mobile_verify").val("");
                    $("#join_mobile_verify").css("border", "1.5px solid #d74646");
                    $("#join_mobile_verify").attr("placeholder", "휴대폰번호인증코드를 입력해 주세요");
                    $("#is_mobile_verify").val("0");
                } else if(data =="-6"){
                    $("#join_mobile").val("");
                    $("#join_mobile").css("border", "1.5px solid #d74646");
                    $("#join_mobile").attr("placeholder", "사용자이름과 휴대폰번호가 이미 등록되여있습니다. 다른이름 혹은 다른 번호를 입력해주세요");
                    $("#is_mobile_verify").val("0");
                } else {
                    alert('보존실패하였습니다.');
                }
            }
        });
    }


}

function checkPhoneNumber(phoneNumber) {
    //유선번호목록
    var phoneFilterList = [
        //이동통신전화번호
        ['010',7],['011',7],['016',7],['017',7],['018',7],['019',7],
        ['010',8],['011',8],['016',8],['017',8],['018',8],['019',8],
    ];
    var checkResult = false;
    $.each(phoneFilterList, function (index, value) {
        if (phoneNumber.substr(0, value[0].length) == value[0] && phoneNumber.length == value[0].length + Number(value[1])) {
            checkResult = true;
            return false;
        }
        ;
    });
    return checkResult;
}


// 영문자와 숫자 그리고 _ 검사
function wrestAlNum_(fld) {
    if (!wrestTrim(fld)) return;

    var pattern = /(^[a-zA-Z0-9\_]+$)/;

    if (!pattern.test(fld.value)) {
        if (wrestFld == null) {
            wrestMsg = wrestItemname(fld) + " : 영문, 숫자, _ 가 아닙니다.\n";
            wrestFld = fld;
        }
    }
}

function send_verify_code() {
    var mobile = $("#join_mobile").val();
   var valid = true;
    if (mobile == '') {
        $("#join_mobile").css("border", "1.5px solid #d74646");
        $("#join_mobile").attr("placeholder", "휴대폰번호를 입력해 주세요");
        valid = false;
    } else
        $("#join_mobile").css("border", "1px solid #ccc");
    if (!checkPhoneNumber(mobile)) {
        $("#join_mobile").css("border", "1.5px solid #d74646");
        $("#join_mobile").val("");
        valid = false;
        $("#join_mobile").attr("placeholder", "정확한 휴대폰번호를 입력해 주세요");
    }
    if (valid) {
        $.ajax({
            url: site_url + 'join/mobile_verify',
            type: 'POST',
            data: {
                join_mobile: mobile
            },
            error: function () {

            },
            success: function (data) {
                if (Number(data) > 0) {

                    alert('인증코드를 정확히 전송하였습니다. 인증코드를 입력해 주세요');


                }else {
                    alert('전송실패.');
                }
            }
        });
    }

}

function mobile_verify() {

    var verify_code = $("#join_mobile_verify").val();
    $.ajax({
        url: site_url + 'join/confirm_verify',
        type: 'POST',
        data: {
            verify_code: verify_code
        },
        error: function () {

        },
        success: function (data) {
            if (Number(data) > 0) {
                $(".ok").css('display','block');
                $(".no").css('display','none');
                $("#is_mobile_verify").val("1");

            } else {
                $(".ok").css('display','none');
                $(".no").css('display','block');
                $("#is_mobile_verify").val("0");
            }
        }
    });

}
function onPassfind() {
    var lost_mb_name =  $('#lost_mb_name').val();
    if (lost_mb_name == '') {
        $("#lost_mb_name").css("border", "1.5px solid #d74646");
        $("#lost_mb_name").attr("placeholder", "사용자아이디를 입력해 주세요");
        valid = false;
    }
    var lost_mobile = $('#lost_mobile').val();
    if (lost_mobile == '') {
        $("#lost_mobile").css("border", "1.5px solid #d74646");
        $("#lost_mobile").attr("placeholder", "휴대폰번호를 입력해 주세요");
        valid = false;
    } else
        $("#lost_mobile").css("border", "1px solid #ccc");
    if (!checkPhoneNumber(lost_mobile)) {
        $("#lost_mobile").css("border", "1.5px solid #d74646");
        $("#lost_mobile").val("");
        valid = false;
        $("#lost_mobile").attr("placeholder", "정확한 휴대폰번호를 입력해 주세요");
    }
    $.ajax({
        url: site_url + 'join/pass_find',
        type: 'POST',
        data: {
            lost_mobile: lost_mobile,
            lost_mb_name: lost_mb_name
        },
        error: function () {

        },
        success: function (data) {
            if (Number(data) > 0) {
                alert(lost_mobile+"휴대폰번호로 회원아이디와 비밀번호를 찾을수 있는 문자가 발송 되었습니다.")
                location.href = site_url+"index";

            } else {
                alert("회원이 아닙니다.")
            }
        }
    });
}

function onUpdateClick() {
    var uid = $('#join_uid').val();
    var company = $('#join_company').val();
    var password = $('#join_password').val();
    var password_confirm = $('#join_password_confirm').val();
    var name = $('#join_name').val();

    var phone = $('#join_phone').val();
    var mobile = $('#join_mobile').val();
    var fax = $('#join_fax').val();
    var email = $('#join_email').val();
    var captcha = $('#join_captcha').val();
    valid = true;
    var pattern = /(^[a-zA-Z0-9\_]+$)/;


    if (password == '') {
        $("#join_password").css("border", "1.5px solid #d74646");
        $("#join_password").attr("placeholder", "비밀번호를 입력해 주세요");
        valid = false;
    }
    if (password_confirm != password) {
        $("#join_password_confirm").css("border", "1.5px solid #d74646");
        $("#join_password_confirm").val("");
        $("#join_password_confirm").attr("placeholder", "비밀번호확인이 틀립니다");
        valid = false;
    } else
        $("#join_password_confirm").css("border", "1px solid #ccc");
    if (company == '') {
        $("#join_company").css("border", "1.5px solid #d74646");
        $("#join_company").attr("placeholder", "기관명을 입력하여 주세요");
        valid = false;
    } else
        $("#join_company").css("border", "1px solid #ccc");
    if (name == '') {
        $("#join_name").css("border", "1.5px solid #d74646");
        $("#join_name").attr("placeholder", "담당자명을 입력해 주세요");
        valid = false;
    } else
        $("#join_name").css("border", "1px solid #ccc");

    if (mobile == '') {
        $("#join_mobile").css("border", "1.5px solid #d74646");
        $("#join_mobile").attr("placeholder", "휴대폰번호를 입력해 주세요");
        valid = false;
    } else
        $("#join_mobile").css("border", "1px solid #ccc");

    if (!checkPhoneNumber(mobile)) {
        $("#join_mobile").css("border", "1.5px solid #d74646");
        $("#join_mobile").val("");
        valid = false;
        $("#join_mobile").attr("placeholder", "정확한 휴대폰번호를 입력해 주세요");
    }
    if (email == '') {
        $("#join_email").css("border", "1.5px solid #d74646");
        $("#join_email").attr("placeholder", "이메일을 입력해 주세요");
        valid = false;
    } else {
        var re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
        if (!re.test(email)) {
            $("#join_email").css("border", "1.5px solid #d74646");
            $("#join_email").val("");
            $("#join_email").attr("placeholder", "올바른 이메일 주소를 입력해 주세요");
            valid = false;
        } else
            $("#join_email").css("border", "1px solid #ccc");
    }
    if (captcha == '') {
        $("#join_captcha").css("border", "1.5px solid #d74646");
        $("#join_captcha").attr("placeholder", "자동복사방지숫자를 입력해 주세요");
        valid = false;
    } else
        $("#join_captcha").css("border", "1px solid #ccc");
    var join_mobile_verify = $("#join_mobile_verify").val();
    var is_mobile_verify = $("#is_mobile_verify").val();
    if(is_mobile_verify == "1" && join_mobile_verify == '') {
        $("#join_mobile_verify").val("");
        $("#join_mobile_verify").css("border", "1.5px solid #d74646");
        $("#join_mobile_verify").attr("placeholder", "휴대폰번호 인증코드를 입력해 주세요");
        valid = false;
    } else {
        $("#join_mobile_verify").css("border", "1px solid #ccc");
    }

    if (valid) {
        
        $.ajax({
            url: site_url + 'join/member_update',
            type: 'POST',
            data: {
                join_uid: uid,
                join_password: password,
                join_name: name,
                join_company: company,
                join_phone: phone,
                join_mobile: mobile,
                join_fax: fax,
                join_email: email,
                join_captcha: captcha,
                join_mobile_verify: join_mobile_verify,
            },
            error: function () {

            },
            success: function (data) {
                if (Number(data) > 0) {
                    swal({title: '', text: '성공적으로 수정되었습니다. 다시 로그인해주세요.',
                        confirmButtonText: '확인', allowOutsideClick: false, type: 'success'},
                        function() {signout();});                                                             
                } else if (data == -1) {
                    $("#join_uid").css("border", "1.5px solid #d74646");
                    $("#join_uid").val("");
                    $("#join_uid").attr("placeholder", "가입하지않은 회원입니다.");
                    valid = false;
                } else if (data == "-2") {
                    $("#join_captcha").css("border", "1.5px solid #d74646");
                    $("#join_captcha").val("");
                    $("#join_captcha").attr("placeholder", "그림의 숫자를 정확히 입력해주세요");
                    valid = false;
                } else if (data == "-3") {
                    $("#join_email").css("border", "1.5px solid #d74646");
                    $("#join_email").val("");
                    $("#join_email").attr("placeholder", "중복된 이메일입니다");
                    valid = false;
                } else if (data == "-4") {
                    $("#join_password").val("");
                    $("#join_password").css("border", "1.5px solid #d74646");
                    $("#join_password").attr("placeholder", "비밀번호를 정확히 입력해주세요");
                    $("#join_password_confirm").val("");
                } else if (data == "-5") {
                    $("#join_mobile_verify").val("");
                    $("#join_mobile_verify").css("border", "1.5px solid #d74646");
                    $("#join_mobile_verify").attr("placeholder", "인증코드를 정확히 입력해주세요");
                } else {
                    swal({title: '', text: '회원정보수정시 오류가 발생하였습니다.',
                    confirmButtonText: '확인', allowOutsideClick: false, type: 'error'},
                    function() {});  
                }
            }
        });
    }

}

function onPassChangeClick() {
    var uid = $('#join_uid').val();

    var password = $('#join_password').val();
    var new_password_confirm = $('#new_join_password_confirm').val();
    var new_password = $('#new_join_password').val();

    var captcha = $('#join_captcha').val();
    valid = true;
    var pattern = /(^[a-zA-Z0-9\_]+$)/;


    if (password == '') {
        $("#join_password").css("border", "1.5px solid #d74646");
        $("#join_password").attr("placeholder", "낡은 비밀번호를 입력해 주세요");
        valid = false;
    }

    if (new_password == '') {
        $("#new_join_password").css("border", "1.5px solid #d74646");
        $("#new_join_password").attr("placeholder", "새 비밀번호를 입력해 주세요");
        valid = false;
    } else {

        var re = /^(?=.*[A-Za-z])(?=.*\d)(?=.*[$@$!%*#?&])[A-Za-z\d$@$!%*#?&]{8,}$/;
        if (!re.test(new_password)) {
            $("#new_join_password").css("border", "1.5px solid #d74646");
            $("#new_join_password").val("");
            $("#new_join_password").attr("placeholder", "최소 8자리에 숫자,문자,특수문자 각각 1개이상 포함하세요");
            valid = false;
        } else
            $("#new_join_password").css("border", "1px solid #ccc");
    }
    if (new_password_confirm != new_password) {
        $("#new_join_password_confirm").css("border", "1.5px solid #d74646");
        $("#new_join_password_confirm").val("");
        $("#new_join_password_confirm").attr("placeholder", "비밀번호확인이 틀립니다");
        valid = false;
    } else
        $("#new_join_password_confirm").css("border", "1px solid #ccc");


    if (captcha == '') {
        $("#join_captcha").css("border", "1.5px solid #d74646");
        $("#join_captcha").attr("placeholder", "자동복사방지숫자를 입력해 주세요");
        valid = false;
    } else
        $("#join_captcha").css("border", "1px solid #ccc");

    if (valid) {
        $.ajax({
            url: site_url + 'join/change_password',
            type: 'POST',
            data: {
                join_uid: uid,
                join_password: password,
                new_join_password: new_password,
                join_captcha: captcha
            },
            error: function () {

            },
            success: function (data) {
                if (Number(data) > 0) {                                        
                    swal({title: '', text: '비밀번호가 성공적으로 변경되었습니다. 다시 로그인해주세요.',
                        confirmButtonText: '확인', allowOutsideClick: false, type: 'success'},
                        function() {signout();});        
                } else if (data == -1) {
                    $("#join_uid").css("border", "1.5px solid #d74646");
                    $("#join_uid").val("");
                    $("#join_uid").attr("placeholder", "가입하지 않은 회원입니다.");
                    valid = false;
                } else if (data == "-2") {
                    $("#join_captcha").css("border", "1.5px solid #d74646");
                    $("#join_captcha").val("");
                    $("#join_captcha").attr("placeholder", "그림의 숫자를 정확히 입력해주세요");
                    valid = false;
                } else if (data == "-3") {
                    $("#join_email").css("border", "1.5px solid #d74646");
                    $("#join_email").val("");
                    $("#join_email").attr("placeholder", "중복된 이메일입니다");
                    valid = false;
                } else if (data == "-4") {
                    $("#join_password").val("");
                    $("#join_password").css("border", "1.5px solid #d74646");
                    $("#join_password").attr("placeholder", "비밀번호를 정확히 입력해주세요");

                } else {
                    swal({title: '', text: '비밀번호변경에서 오류가 발생하였습니다.',
                        confirmButtonText: '확인', allowOutsideClick: false, type: 'success'},
                        function() {});        
                }
            }
        });
    }


}
function onLost_PassChangeClick(mb_id,pass) {

        $.ajax({
            url: site_url + 'join/password_lost_certify',
            type: 'POST',
            data: {
                mb_id: mb_id,
                join_password: pass

            },
            error: function () {

            },
            success: function (data) {
                if (Number(data) > 0) {
                    alert('비밀번호가 성공적으로 변경됐습니다. 다시 로그인 하십시요.');
                    location.href=site_url+"index";
                } else  {
                    alert('비밀번호변경이 실패하였습니다. 비밀번호를 다시 발급받기 바랍니다.');
                    location.href=site_url+"index";
                }
            }
        });

}