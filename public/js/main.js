$(document).ready(function(){
// jQuery UI Datepicker
    var datepickerSelector2 = $('.datapicker');
    datepickerSelector2.datepicker({
        showOtherMonths: true,
        selectOtherMonths: true,
        dateFormat: "dd-mm-yy",
        yearRange: '-1:+1',
        changeMonth: true,
        changeYear: true,
        maxDate: new Date()
    }).prev().on('click', function (e) {
        e && e.preventDefault();
        datepickerSelector2.focus().blur();
    });

    $.extend($.datepicker, {_checkOffset:function(inst,offset,isFixed){return offset}});

    // Now let's align datepicker with the prepend button
    datepickerSelector2.datepicker('widget').css({'margin-left': -datepickerSelector2.prev('.input-group-btn').find('.btn').outerWidth()});
});