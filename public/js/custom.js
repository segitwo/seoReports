$(document).ready(function(){


    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('input[name="_token"]').val()
        }
    });
    
    checkReqVals();
    $("input, select").change(function(){
        checkReqVals();
    });
    
    $('select[name="siteid"]').select2({dropdownCssClass: 'show-select-search'});
    
    $("select[name='siteid']").change(function(){
        var se_ranking = $("select[name='siteid'] option:selected").data("se_ranking");
        $("input[name='se_ranking']").val(se_ranking);
        
        var sitename = $("select[name='siteid'] option:selected").data("name");
        $("input[name='sitename']").val(sitename);
        
        var siteid = $("select[name='siteid'] option:selected").data("id");
        $("input[name='id']").val(siteid);
        
        if($("select[name='siteid'] option:selected").data("lastupdate") != "" && $("select[name='siteid'] option:selected").data("lastupdate") != undefined){
            var lastupdate = new Date($("select[name='siteid'] option:selected").data("lastupdate"));
            $("input[name='lastUpdate']").val($.datepicker.formatDate('dd-mm-yy', lastupdate));
        } else {
            $("input[name='lastUpdate']").val("");
        }
        
    });
    
    $("select[name='period'], select[name='dop_work']").change(function(){
        var thisSelect = $(this);
        var period = thisSelect.find("option:selected").val();
        
    	$.ajax(thisSelect.data("action"), {
            data: {"period": period},
            type: "POST",
        }).done(function(data) {
            if(thisSelect.prop("name") == "period"){
                $("#autoTextPeriod").html(data);
                $("#autoTextPeriod input[type='radio']").radiocheck();
            } else {
                $("#autoTextDopWork").html(data);
                $("#autoTextDopWork input[type='radio']").radiocheck();
            }
            
        });
    });
    
    $("[name='date']").val($.datepicker.formatDate('dd-mm-yy', new Date()));
    
    
    $("[name='next']").click(function(){
        var button = $(this);
        button.button('loading');
        $('#regions').css({"opacity":0});
    	$.ajax($("[name='next']").data("link"), {
            data: $("#repForm").serializeArray(),
            type: $("#repForm").attr('method'),
        }).done(function(data) {
            button.button('reset');
            $('#regions').html(data).find(":checkbox").radiocheck();

            // jQuery UI Datepicker
            var datepickerSelector2 = $('[name="allpprevdate"]');
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

            $('#regions').animate({"opacity":1}, 1000);
            $('.table .toggle-all :checkbox').on('click', function () {
                var $this = $(this);
                var ch = $this.prop('checked');
                $this.closest('.table').find('tbody :checkbox').radiocheck(!ch ? 'uncheck' : 'check');
            });

            // Table: Add class row selected
            $('.table tbody :checkbox').on('change.radiocheck', function () {
                var $this = $(this);
                var check = $this.prop('checked');
                var checkboxes = $this.closest('.table').find('tbody :checkbox');
                var checkAll = checkboxes.length === checkboxes.filter(':checked').length;

                $this.closest('tr')[check ? 'addClass' : 'removeClass']('selected-row');
                $this.closest('.table').find('.toggle-all :checkbox').radiocheck(checkAll ? 'check' : 'uncheck');
            });
        });

    });
    
    $('#addModal').on('shown.bs.modal', function (e) {
        var button = $(e.relatedTarget);
        
        if(button.hasClass("changeSiteButton")){
            $('#siteInput').val(button.data('site'));
            $('#metrikaInput').val(button.data('metrika'));
            $('#allpInput').val(button.data('allp'));
            $('#siteId').val(button.data('siteid'));
            $('#mode').val("change");
        } else {
            $('#mode').val("create");
        }
        
    });
    
    $('#addModal').on('hide.bs.modal', function (e) {
        $('#siteInput').val("");
        $('#metrikaInput').val("");
        $('#allpInput').val("");
        $('#siteId').val("");
    });
    
    $('[data-toggle="confirmation"]').confirmation({
        onConfirm: function(e) {
            alert('You choosed ' + currency);
        }
    });
    
    $("#addModal form").validateIt({
        onsubmit: function(res){
            $('#addModal').modal('hide');
            $('#sitesTable tbody').html(res);
        }
    });
    
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

function checkReqVals(){
    if($("[name='siteid']").val() != "" && $("[name='date']").val() != "") {
        $("[name='next']").attr("disabled", false);
    } else {
        $("[name='next']").attr("disabled", true);
    }
}