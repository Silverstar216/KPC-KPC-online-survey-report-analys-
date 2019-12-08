/**
 * Author: KMC
 * Date: 10/6/15
 */


$(function () {
    $('.uselog_datepicker').datetimepicker({
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
    
    $('#uselog_search').click(function () {
        location.href = site_url + 'uselog?start_date=' + $('#uselog_start_date').val() + '&end_date=' + $('#uselog_end_date').val();
    });
});