/**
 * Author: KMC
 * Date: 10/6/15
 */

var myApp;
var g_interval = {};
var g_elapsed_time = 0;

function updateInterval() {
    $('#elapsed_time').text(g_elapsed_time + '초');
    g_elapsed_time++;
}

myApp = myApp || (function () {
    var progressDiv = $('<div class="modal" id="pleaseWaitDialog" data-backdrop="static" data-keyboard="false"><div class="modal-header"><h1 style="text-align: center; color: white; margin-top: 150px">잠시만 기다려주십시오...</h1></div><div class="modal-body"><div class="progress"><div class="progress-bar progress-bar-striped active" role="progressbar" aria-valuenow="45" aria-valuemin="0" aria-valuemax="100" style="width: 100%"></div></div></div></div>');
    var progressDiv2 = $('<div class="modal" id="progressDialog" data-backdrop="static" data-keyboard="false"><div class="modal-header"><h1 style="text-align: center; color: white; margin-top: 150px">잠시만 기다려주십시오... <span id="elapsed_time"></span></h1></div><div class="modal-body"><div class="progress"><div class="progress-bar" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%"></div></div></div></div>');
    return {
        showPleaseWait: function() {
            progressDiv.modal();
        },
        hidePleaseWait: function () {
            progressDiv.modal('hide');
        },
        showProgress: function () {
            g_elapsed_time = 0;
            g_interval = setInterval(updateInterval, 1000);

            progressDiv2.modal();
        },
        hideProgress: function () {
            progressDiv2.modal('hide');
            clearInterval(g_interval);
        },
        updateProgress: function (percent, text) {
            $('#progressDialog .progress-bar').css('width', percent + '%');
            $('#progressDialog .progress-bar').text(text);
        }
    };
})();

$(function () {
    $('.header-img').on('click', function () {
        location.href = site_url;
    });

    $(window).resize(function () {
        change_content_size();
    });
    change_content_size();
    init_my_modal();

    //개수가 0인 배지는 감춘다.
    $('.badge-zero-hide').each(function(a, b) {
        if ($(this).text() == 0)
            $(this).addClass('hidden');
        else
            $(this).removeClass('hidden');
    });
    $(document).ajaxStart(function(){
        $("#wait").css("display", "block");
    });
    $(document).ajaxComplete(function(){
        $("#wait").css("display", "none");
    });


    $(".hd_pops_reject").click(function() {
        var id = $(this).attr('class').split(' ');
        var ck_name = id[1];
        var exp_time = parseInt(id[2]);
        $("#hd_pop").css("display", "none");
        set_cookie('hd_pop', 'hd_pop', exp_time, "");
    });
    $('.hd_pops_close').click(function() {
       /* var idb = $(this).attr('class').split(' ');*/
        $('#hd_pop').css('display','none');
    });
    $("#hd").css("z-index", 1000);

    $('input[type="number"]').keydown(function(event) {
        if (event.keyCode === 13) {
            event.preventDefault();
        }
    });
    $('input[type="text"]').keydown(function(event) {
        if (event.keyCode === 13) {
            event.preventDefault();
        }
    });




});

function onImageView($key) {
    location.href = site_url + 'index/introduce_img?key=' + $key;
}
function onVideoPlay() {
}

function onUseGuideView(){
    alert("등록된 내용이 없습니다!");
}
function onSpamView(){
    alert("등록된 내용이 없습니다!");
}
function onPersonalInfoView(){
    alert("등록된 내용이 없습니다!");
}
function onNoEmailView(){
    alert("등록된 내용이 없습니다!");
}
function onQuestionServiceView(){
    alert("등록된 내용이 없습니다!");
}
function disable_autocomplete() {
    $('form[autocomplete="off"] input, input[autocomplete="off"]').each(function () {

        var input = this;
        var name = $(input).attr('name');
        var id = $(input).attr('id');

        $(input).removeAttr('name');
        $(input).removeAttr('id');

        setTimeout(function () {
            $(input).attr('name', name);
            $(input).attr('id', id);
        }, 100);
    });
}

// modal dialog------------------------------------

function init_my_modal() {
    if ($('#myModalOk').modal) {
        $('#myModalOk').modal({
            show: false
        });
    }
    if ($('#myModalOkCancel').modal) {
        $('#myModalOkCancel').modal({
            show: false
        });
    }
    if ($('#modalProductInfo').modal) {
        $('#modalProductInfo').modal({
            show: false
        });
    }
}

function open_product_info_modal(obj) {

    var img_src = $(obj).parent().find('img').attr('src');
    var products_kind = $.trim($(obj).parent().find('.products-kind').text());
    var products_name = $.trim($(obj).parent().find('.products-name').html());
    var products_company = $.trim($(obj).parent().find('.products-company').text());
    var products_price = $.trim($(obj).parent().find('.products-price').text());
    var products_page = $.trim($(obj).parent().find('.products-page').text());
    var products_year = $.trim($(obj).parent().find('.products-year').text());
    var products_comment = $.trim($(obj).parent().find('.products-comment').html());
    var products_checknum = $.trim($(obj).parent().find('.products-checknum').text());

    if(products_page == 0)
        products_page = '';
    else
        products_page += '페지';
    if(products_year == 0)
        products_year = '';
    else
        products_year += '년 출판';

    $('#modalProductInfo .modal-body-img').attr('src', img_src);
    $('#modalProductInfo .modal-body .modal-body-products-kind').text(products_kind);
    $('#modalProductInfo .modal-body .modal-body-products-name').html(products_name);
    $('#modalProductInfo .modal-body .modal-body-products-company').text(products_company);
    $('#modalProductInfo .modal-body .modal-body-products-price').text(products_price);
    $('#modalProductInfo .modal-body .modal-body-products-page').text(products_page);
    $('#modalProductInfo .modal-body .modal-body-products-year').text(products_year);
    $('#modalProductInfo .modal-body .modal-body-products-comment').html(products_comment);
    $('#modalProductInfo .modal-body .modal-body-products-checknum').text(products_checknum);
    $('#modalProductInfo').modal('show');
}


/**
 * [open_my_modal 통보문현시]
 * @param  {[string]}   msg    [현시하려는 통보문]
 * @param  {Function} cb     [통보문현시후 실행할 콜백함수]
 * @param  {[int]}   status [확인단추색갈설정값, 1이면 danger로 설정]
 * @return {[void]}          [귀환값없음]
 */
function open_my_modal(msg, cb, status, link_cart) {
    msg = msg || "봉사기정비중입니다.";
    cb = cb || function () {
    };
    status = status || 0;
    link_cart = link_cart || false;

    $('#myModalOk').on('hidden.bs.modal', function (e) {
        cb();
    });

    $('#btn_show_cart').unbind('click');
    $('#btn_show_cart').click(function () {
        location.href = site_url + 'products/cart/list';
    });

    if(link_cart)
        $('#btn_show_cart').removeClass('hidden');
    else
        $('#btn_show_cart').addClass('hidden');

    $('#myModalOk .btn-ok').removeClass('btn-primary');
    $('#myModalOk .btn-ok').removeClass('btn-danger');

    if (status == 0) {
        $('#myModalOk .btn-ok').addClass('btn-primary');
    } else {
        $('#myModalOk .btn-ok').addClass('btn-danger');
    }

    $('#myModalOk .modal-body p').text(msg);
    $('#myModalOk').modal('show');
}
// modal dialog------------------------------------
function open_my_modal_2(title, content, param, fn, cb) {
    param = param || 0;
    title = title || "";
    content = content || "바구니에서 삭제하겠습니까?";
    cb = cb || function () {
    };

    $('#myModalOkCancel').modal('show');
    $('#myModalOkCancel .modal-body h4').html(title);
    $('#myModalOkCancel .modal-body p').html(content);
    $('#myModalOkCancel .btn-primary').unbind('click');
    $('#myModalOkCancel .btn-primary').click(function () {
        fn(param);
    });
}

function hide_my_modal_2() {
    $('#myModalOkCancel').modal('hide');
}
/**
 * [number_format Format a number with grouped thousands]
 * @param  {[double]} number        [number to be formatted]
 * @param  {[string]} decimals      [number of decimals to be cut]
 * @param  {[string]} dec_point     [dot(".")]
 * @param  {[string]} thousands_sep [letter("," or " ")]
 * @return {[string]}               [number formatted]
 * @example
 * number_format(19356.3263, 2, ".", " ");
 *  -> 19 356.32
 */
function number_format(number, decimals, dec_point, thousands_sep) {
    if (!decimals)
        decimals = 0;
    if (!dec_point)
        dec_point = ".";
    if (!thousands_sep)
        thousands_sep = " ";
    if (isNaN(number)) {
        //alert('error number_format(): parameter is NaN');
        //return number;
        number = 0;
    }

    if (decimals == 0)
        number = Math.round(number);

    var integer = 0;
    var decimal = new String("");

    var formatted_number = "";
    var sign = "";
    if (number < 0) {
        sign = "-";
        number = -number;
    }
    integer = parseInt(Math.floor(number), 10);

    var str_integer = new String(integer.toString(10));
    var str_decimal = new String("");
    var str_new_integer = new String("");
    var str_new_decimal = new String("");

    if (number.toString(10).indexOf(".") != -1) {
        str_decimal = number.toString(10).substring(number.toString(10).indexOf(".") + 1);
    }

    for (var i = 0; i < str_integer.length; i++) {
        if (i % 3 == 0 && i > 0) {
            str_new_integer = thousands_sep + str_new_integer;
        }
        str_new_integer = str_integer.charAt(str_integer.length - i - 1) + str_new_integer;
    }
    str_new_integer = sign + str_new_integer;

    for (i = 0; i < str_decimal.length; i++) {
        if (i > decimals - 1) {
            if (parseInt(str_decimal.charAt(i), 10) > 4 && parseInt(str_new_decimal.charAt(i - 1), 10) < 9) {
                var nOriginNum = parseInt(str_new_decimal.charAt(i - 1), 10);
                if (nOriginNum < 9)
                    nOriginNum++;
                str_new_decimal = str_new_decimal.substring(0, i - 1) + nOriginNum.toString(10);
            }
            break;
        }
        str_new_decimal = str_new_decimal + str_decimal.charAt(i);
    }

    var formatedVal = new String("");

    if (decimals == 0)
        formatedVal = str_new_integer;
    if (str_decimal.length > 0)
        formatedVal = str_new_integer + dec_point + str_new_decimal;
    else if (decimals > 0)
        formatedVal = str_new_integer + ".";

    for (i = 0; i < decimals - str_decimal.length; i++) {
        formatedVal += '0';
    }

    return formatedVal;
}

function rankRenderer(instance, td, row, col, prop, value, cellProperties) {
    value = '<div class="ui-icon-arrow ui-icon-arrow-up" onclick="move_row_up(' + row + ')"></div>';
    value += '<div class="ui-icon-arrow ui-icon-arrow-down" onclick="move_row_down(' + row + ')"></div>';
    $(td).html(value);
    $(td).addClass('htCenter htMiddle');
}

function textCenterAlignRenderer(instance, td, row, col, prop, value, cellProperties) {
    value = value || "";
    $(td).text(value);
    $(td).addClass('htCenter htMiddle');
}

function numRightAlignRenderer(instance, td, row, col, prop, value, cellProperties) {
    value = value === null ? "" : value;
    // value = number_format(-19356.3263, 2, ".", " ");
    $(td).text(value);
    $(td).addClass('htRight htMiddle');
}

function move_row_up(row) {
    if (row == 0)
        return;

    g_hot.alter('insert_row', row + 1, 1);

    var above_data = g_hot.getDataAtRow(row - 1);
    var above_src_data = g_hot.getSourceDataAtRow(row - 1);
    // g_hot.setDataAtRowProp(row + 1, "ik_num", above_src_data["ik_num"]);
    for (key in above_src_data) {
        if (above_src_data[key] != null)
            g_hot.setDataAtRowProp(row + 1, key, above_src_data[key]);
    }
    // for(i=0; i<above_data.length; i++) {
    // 	if(above_data[i] != null)
    // 		g_hot.setDataAtCell(row + 1, i, above_data[i]);
    // }
    // g_hot.alter('remove_row', row-1);
    setTimeout(function () {
        g_hot.alter('remove_row', row - 1);
    }, 1);
    g_hot.selectCell(row - 1, g_hot.propToCol('rank'));
}

function move_row_down(row) {
    move_row_up(row + 1);
    g_hot.selectCell(row + 1, g_hot.propToCol('rank'));
}

function updateContextMenu(hot) {
    hot.updateSettings({
        contextMenu: {
            callback: function (key, options) {
                if (key === 'about') {
                    setTimeout(function () {
                        //timeout is used to make sure the menu collapsed before alert is shown
                        alert("This is a context menu with default and custom options mixed");
                    }, 100);
                }
            },
            items: {
                "row_above": {
                    name: '우에 한행추가',
                    disabled: function () {
                        return false;
                        //if first row, disable this option
                        return (hot.getSelected()[0] === 0)
                    }
                },
                "row_below": {
                    name: '아래에 한행추가'
                },
                "hsep1": "---------",
                "about": {
                    name: 'About this menu'
                }
            }
        }
    });
}

function change_content_size() {

        var win_height = $(window).height();
        var header_height = $('.header').height();
        var footer_height = $('.bottom-nav').height();

        var content_height = win_height - header_height - footer_height;

        if ($('#content').height() < content_height) {
            $('#content').css('min-height', content_height + 'px');
        }
        show_nav_footer();

}

function show_nav_footer() {
    $('#nav_footer').removeClass('hidden');
}

function notice_content(n_id) {
    var width = 800;
    var height = 500;
    var top = 100;
    var left = 100;
    window.open(site_url + 'notice/view/' + n_id, null, "resizable=yes,scrollbars=yes,top=" + top + ",left=" + left + ",height=" + height + ",width=" + width + ",toolbar=no,titlebar=no,menubar=no,location=no,address=no,addressbar=no;");
    return;
}

function polity_content( id ) {

    var width = 800;
    var height = 600;
    var top = 100;
    var left = 100;
    window.open(site_url + 'polity/pdfview?id=' + id, null, "resizable=yes,scrollbars=yes,top=" + top + ",left=" + left + ",height=" + height + ",width=" + width + ",toolbar=no,titlebar=no,menubar=no,location=no,address=no,addressbar=no;");
    return;
}

function cart_add(p_id) {
    $.post(site_url + 'sign/check',
    {
    },
    function(data, status){
        var obj = jQuery.parseJSON(data);
        if(obj.result != 1)
        {
            swal({
                    title: '알림', text: '사용자가입을 하여야 바구니담기를 할수 있습니다.',
                    confirmButtonText: '확인', allowOutsideClick: false, type: 'info'
                },
                function (isConfirm) {
                });
            return;
        }
        $.ajax({
            type: 'GET',
            dataType: 'jsonp',
            data: {p_id: p_id},
            url: site_url + 'products/cart/add',
            jsonp: 'km_jsonp',
            timeout: 5000,
            success: function (json) {
                if (json.status == 0) {
                    open_my_modal(json.msg, function () {
                        location.href = site_url;
                    });
                }
                if (json.status == 2) {
                    open_my_modal(json.msg, function () {}, 0, true);
                }
                if (json.status == 1) {
                    open_my_modal(json.msg, function () {}, 0, true);
                }
            },
            error: function (xhr, status, thrown) {
                alert(status);
            }
        });
    });
}

function set_last_location(url)
{
    $.cookie('last_history_location', url, {
        expires : 300,
        path: '/'
    });

    var aaa = $.cookie('last_history_location');
    console.log(aaa);
}

function go_back_to_last_location()
{
    var last_location = $.cookie('last_history_location');
    if (last_location != '' && last_location != undefined)
        location.href = last_location;
    else
        history_back();
}

function history_back()
{
    window.history.back();
}

function htmlentities(value) {
    var re = /</g;
    value = value.replace(re, "&lt;");

    re = /</g;
    value = value.replace(re, "&gt;");

    re = /"/g;
    value = value.replace(re, "&quot;");

    return value;
}

function get_date_diff_from_string(ds1, ds2) {
    return get_ms_from_string(ds2) - get_ms_from_string(ds1);
}

function get_ms_from_string(dateAsString)
{
    // var parts = dateAsString.match(/(\d{4})-(\d{2})-(\d{2}) (\d{2}):(\d{2}):(\d{2}).(\d{3})/);
    var parts = dateAsString.match(/(\d{4})-(\d{2})-(\d{2}) (\d{2}):(\d{2}):(\d{2})/);

    return new Date(parts[1],
        parts[2] - 1,
        parts[3],
        parts[4],
        parts[5],
        parts[6]).getTime();

    // return new Date(parts[1],
    //     parts[2] - 1,
    //     parts[3],
    //     parts[4],
    //     parts[5],
    //     parts[6],
    //     parts[7]).getTime();
}

var DateDiff = {
    inDays: function(d1, d2) {
        var t2 = d2.getTime();
        var t1 = d1.getTime();

        return parseInt((t2-t1)/(24*3600*1000));
    },

    inWeeks: function(d1, d2) {
        var t2 = d2.getTime();
        var t1 = d1.getTime();

        return parseInt((t2-t1)/(24*3600*1000*7));
    },

    inMonths: function(d1, d2) {
        var d1Y = d1.getFullYear();
        var d2Y = d2.getFullYear();
        var d1M = d1.getMonth();
        var d2M = d2.getMonth();

        return (d2M+12*d2Y)-(d1M+12*d1Y);
    },

    inYears: function(d1, d2) {
        return d2.getFullYear()-d1.getFullYear();
    }
}

// 쿠키 입력
function set_cookie(name, value, expirehours, domain)
{
    var today = new Date();
    today.setTime(today.getTime() + (60*60*1000*expirehours));
    document.cookie = name + "=" + escape( value ) + "; path=/; expires=" + today.toGMTString() + ";";
    if (domain) {
        document.cookie += "domain=" + domain + ";";
    }
}

// 쿠키 얻음
function get_cookie(name)
{
    var find_sw = false;
    var start, end;
    var i = 0;

    for (i=0; i<= document.cookie.length; i++)
    {
        start = i;
        end = start + name.length;

        if(document.cookie.substring(start, end) == name)
        {
            find_sw = true
            break
        }
    }

    if (find_sw == true)
    {
        start = end + 1;
        end = document.cookie.indexOf(";", start);

        if(end < start)
            end = document.cookie.length;

        return document.cookie.substring(start, end);
    }
    return "";
}

// 쿠키 지움
function delete_cookie(name)
{
    var today = new Date();

    today.setTime(today.getTime() - 1);
    var value = get_cookie(name);
    if(value != "")
        document.cookie = name + "=" + value + "; path=/; expires=" + today.toGMTString();
}

function paper_cal() {
    var count = $('#student_count').val();
    if(count =="") {
        alert("학생수를 입력해주세요")
    } else {
        var paper_count = Number(count)*15*12;
        var water_value = Number(paper_count)*10;
        var carbon_value = (Number(paper_count)*2.88).toFixed(1);
        $('#paper').html(paper_count);
        $('#water').html(water_value);
        $('#carbon').html(carbon_value);
    }
}