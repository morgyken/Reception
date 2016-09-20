/*
 * Collabmed Solutions Ltd
 * Project: iClinic
 * Author: Samuel Okoth <sodhiambo@collabmed.com>
 */
/* global CANCEL_URL, GET_SCHEDULE */
var active_tab = 0;
var active_category = 0;
var tables = null;
$('#new').click(function (e) {
    e.preventDefault();
    $('#newSchedule').modal('show');
});
$('#adds').click(function () {
    $('#newSchedule').modal('hide');
});
$(".prefetch").click(function () {
    active_tab = $(this).attr('sam-ajax');
    $("#all_cat").click();
});
function fetch_table() {
    $.ajax({
        url: GET_SCHEDULE,
        type: "GET",
        data: {'clinic': active_tab, 'category': active_category, 'start': $("#date1").val(), 'end': $('#date2').val()},
        success: function (data) {
            $('#appointments').html($(data)).fadeIn();
            if (tables)
            {
                tables.destroy();
            }
            try {
                tables = $('table').DataTable();
            } catch (e) {
            }
        }
    });
}
$(".categories").click(function () {
    active_category = $(this).attr('sam-ajax2');
    fetch_table();
});
$(document).ready(function () {
    $(".date").datepicker({minDate: 0, dateFormat: 'yy-mm-dd', yearRange: "1900:+0"});
    $('.time').timeAutocomplete({increment: 10, auto_value: false});
    $("#all").click();
});