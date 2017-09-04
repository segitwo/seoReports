/**
 * Created by Admin on 01.09.2017.
 */
$( function() {
    $( "#sortable" ).sortable({
        revert: true,
        stop: function(event, ui) {
            storeSortIndex();
        }
    });
    $( "#draggable li" ).draggable({
        connectToSortable: "#sortable",
        helper: "clone",
        revert: "invalid",

        stop: function(event, ui) {

            var currentBlock = $(ui.helper['0']);
            var prevBlock = $(ui.helper.prevObject['0']);
            if(currentBlock.parent()['0'] !== prevBlock.parent()['0']){
                prevBlock.draggable('option', 'disabled', true);

                storeSortIndex();
            }

        }
    });
    $( "ul, li" ).disableSelection();

    $('body').on('click', ".removeBlock", function () {
        var name = $(this).closest('li').data('name');
        $(this).closest('li').remove();

        $('[data-name="' + name + '"]').draggable('option', 'disabled', false);
    });

} );

function storeSortIndex(){
    $('#sortable li').each(function () {
        var index = $(this).index();
        $(this).find('input[name="sortIndex"]').val(index);
        $('#sortable li input').attr('disabled', false);
    });
}