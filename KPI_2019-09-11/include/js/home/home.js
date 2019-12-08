$(function () {
    if (msg_type == 'no_user') {
        open_my_modal('등록되지 않은 식별자입니다. 새로 등록한후 다시 가입하여주십시오.', function () {
            
        });
    }

    if (msg_type == 'already') {
        open_my_modal('그 식별자는 이미 등록되여있습니다.', function () {
            
        });
    }

    if (msg_type == 'signin') {
        open_my_modal('사용자가입을 하십시오.', function () {
            location.href=site_url+"join/login_view"
        });
    }
});

var UITree = function () {

    var handleAgSale = function () {

        $('#tree_ag_sale').jstree({
            "core" : {
                "themes" : {
                    "responsive": false
                },
                'data' : {
                    'url' : function (node) {
                        return site_url+'home/agency_tree_jsondata';
                    },
                    'data' : function (node) {
                        return { 'parent' : node.id };
                    }
                }
            },
            "types" : {
                "default" : {
                    "icon" : "fa fa-home icon-state-warning icon-lg"
                },
                "file" : {
                    "icon" : "fa fa-mobile icon-state-warning icon-lg"
                }
            },
            "plugins": ["types"]
        });
        
        $('#tree_ag_sale').on('select_node.jstree', function(e,data) {
            
            // $.post(site_url + '/home/agency_info',
            // {
            //     agency_id: parseInt(data.selected)
            // },
            // function(data, status){
            //     var obj = jQuery.parseJSON(data);
            //     if(obj.result == 0)
            //     {
            //         $('.modal-title', $('#agency_modal')).html(obj.agency_name);
            //         $('#agency_address', $('#agency_modal')).html(obj.agency_address);
            //         $('#agency_phone', $('#agency_modal')).html(obj.agency_phone);
            //         $('#agency_modal').modal('show');
            //     }
            // });
        });
        
    }    

    return {
        
        init: function () {

            handleAgSale();
        }

    };

}();

var UIModals = function () {

    var handleModals = function () {
        $("#news_modal").draggable({
            handle: ".modal-header"
        });
        $("#agency_modal").draggable({
            handle: ".modal-header"
        });
        $("#product_modal").draggable({
            handle: ".modal-header"
        });
    }

    return {
        //main function to initiate the module
        init: function () {
            handleModals();
        }

    };

}();

//현지지도일력
function go_polity() {
    window.open('http://10.30.27.100/index.php?strPageID=SF01_01_02&iMenuID=1');
}
//물음과 대답페지로 가기
function go_help() {
    location.href = site_url + 'help';
}
//기관소개페지로 가기
function go_ciast() {
    var width = 1200;
    var height = 550;
    var top = 100;
    var left = 100;
    window.open(site_url + 'introduction', null, "resizable=yes,scrollbars=yes,top=" + top + ",left=" + left + ",height=" + height + ",width=" + width + ",toolbar=no,titlebar=no,menubar=no,location=no,address=no,addressbar=no;");
    return;
}
//외부기관홈페지로 가기
function go_otherhomepage() {
    var width = 800;
    var height = 550;
    var top = 100;
    var left = 100;
    window.open(site_url + 'otherhomepage', null, "resizable=yes,scrollbars=yes,top=" + top + ",left=" + left + ",height=" + height + ",width=" + width + ",toolbar=no,titlebar=no,menubar=no,location=no,address=no,addressbar=no;");
    return;
}

function open_ads(a_id) {
    var width = 800;
    var height = 600;
    var top = 100;
    var left = 100;
    window.open(site_url + 'ads/view/' + a_id);
    return;
}

function show_product(p_id) {
    $.post(site_url + '/home/product_info',
    {
        p_id: p_id
    },
    function(data, status){
        var obj = jQuery.parseJSON(data);
        if(obj.result == 0)
        {
            $('#product_thumbnail', $('#product_modal')).attr("src", site_url + 'products/thumbnail/' + obj.p_thumbnail);
            $('#product_type', $('#product_modal')).html(obj.pp_name + ' ' + obj.pk_name);
            $('#product_title', $('#product_modal')).html(obj.p_name);
            $('#product_comment', $('#product_modal')).html(obj.p_comment);
            $('#product_left', $('#product_modal')).html(obj.pc_name);
            $('#product_right', $('#product_modal')).html(obj.p_right);
            $('#product_modal').modal('show');
        }
    });
    
}

function show_news(n_id) {
    $.post(site_url + '/home/news_info',
    {
        n_id: n_id
    },
    function(data, status){
        var obj = jQuery.parseJSON(data);
        if(obj.result == 0)
        {
            $('.modal-title', $('#news_modal')).html(obj.n_title);
            var content = obj.n_content;
            $('.scroller', $('#news-form')).html(content);
            $('#news_modal').modal('show');
        }
    });    
}

function journal_content(s_id) {
    $.post(site_url + 'sign/check',
    {
    },
    function(data, status){
        var obj = jQuery.parseJSON(data);
        if(obj.result != 1)
        {
            swal({
                    title: '알림', text: '사용자가입을 하여야 열람할수 있습니다.',
                    confirmButtonText: '확인', allowOutsideClick: false, type: 'info'
                },
                function (isConfirm) {
                });
            return;
        }
        check_pay(s_id, 0);        
    });
}

function scitech_content(s_id) {
    $.post(site_url + 'sign/check',
    {
    },
    function(data, status){
        var obj = jQuery.parseJSON(data);
        if(obj.result != 1)
        {
            swal({
                    title: '알림', text: '사용자가입을 하여야 열람할수 있습니다.',
                    confirmButtonText: '확인', allowOutsideClick: false, type: 'info'
                },
                function (isConfirm) {
                });
            return;
        }
        check_pay(s_id, 2);
    });
}

function check_pay(s_id, data_class) {
    $.ajax({
        type: 'GET',
        dataType: 'jsonp',
        data: {s_id: s_id, data_class: data_class},
        url: site_url + 'payment/scitech_check',
        jsonp: 'km_jsonp',
        timeout: 8000,
        success: function (json) {

            var price = parseInt(json.data.price);
            var balance = parseInt(json.data.balance);
            if (price == 0) {
                swal({title: '', text: '무료자료입니다.',
                        confirmButtonText: '확인', allowOutsideClick: false, type: 'success'},
                    function(isConfirm) {
                        content(s_id, data_class);
                    });
            }
            else if (json.status == 0) {
                // 미구입

                var price_f = number_format(price, 0, '.', ' ');
                var balance_f = number_format(balance, 0, '.', ' ');

                $('#data_price').text(price_f);
                $('#card_balance').text(balance_f);

                $('#msg_low_balance').addClass('hidden');
                $('#msg_pay_confirm').addClass('hidden');

                // 잔고부족인 경우
                if (price > balance) {
                    $('#msg_low_balance').removeClass('hidden');
                    open_my_modal_pay_kind(function () {
                        location.href = site_url + 'account/input';
                    });
                }
                else {
                    $('#msg_pay_confirm').removeClass('hidden');
                    open_my_modal_pay_kind(function () {
                        hide_my_modal_pay_kind();
                        content(s_id, data_class);
                        pay_data(s_id, data_class);
                    });
                }

                return;

            } else if (json.status == 1) {
                // 구입됨
                swal({title: '', text: price + '원으로 이미 구입한 자료를 열람합니다.',
                        confirmButtonText: '확인', allowOutsideClick: false, type: 'success'},
                    function(isConfirm) {
                        content(s_id, data_class);
                    });
            } else if (json.status == 2) {
                swal({title: '', text: price + '원의 자료를 무료로 열람합니다.',
                        confirmButtonText: '확인', allowOutsideClick: false, type: 'success'},
                    function(isConfirm) {
                        content(s_id, data_class);
                    });
            } else {
                open_my_modal(json.msg);
            }
        },
        error: function (xhr, status, thrown) {
            alert(status);
        }
    });
}

function pay_data(s_id, data_class) {
    $.ajax({
        type: 'GET',
        dataType: 'jsonp',
        data: {s_id: s_id, data_class: data_class},
        url: site_url + 'payment/scitech_pay',
        jsonp: 'km_jsonp',
        timeout: 8000,
        success: function (json) {
            if (json.status == 0) {

            }
            else if (json.status == 1) {
            }
            else {

            }
        },
        error: function (xhr, status, thrown) {
            alert(status);
        }
    });
}

function content(s_id, data_class) {
    var width = 800;
    var height = 500;
    var top = 100;
    var left = 100;
    var url;
    if(data_class == 0)
        url = site_url + 'journal/pdfview?s_id=' + s_id;
    else if(data_class == 2)
        url = site_url + 'scitech/view?sd_id=' + s_id;
    //$('#print_frame').attr('src', url);
    window.open(url, null, "resizable=yes,scrollbars=yes,top=" + top + ",left=" + left + ",height=" + height + ",width=" + width + ",toolbar=no,titlebar=no,menubar=no,location=no,address=no,addressbar=no;");
    return;
}

// modal dialog------------------------------------
function open_my_modal_pay_kind(fn) {
    $('#modalPayScitech').modal('show');
    $('#modalPayScitech .btn-primary').unbind('click');
    $('#modalPayScitech .btn-primary').click(function () {
        fn();
    });
}

function hide_my_modal_pay_kind() {
    $('#modalPayScitech').modal('hide');
}
