var table = null;

$(function() { 

    $('#modal_handmatig').on('hidden.bs.modal', function() {
        $(this).find('form').trigger('reset');
    });
    
    $.extend( $.fn.dataTable.defaults, {
        autoWidth: false,
        scrollX:        true,
        columnDefs: [{ 
            orderable: false,
            className: 'text-center',
            width: 80,
            targets: [ 0 ]
        }, 
        { 
            className: 'text-right', 
            targets: [2, 3, 4] 
        }],
        order: [[ 1, "asc" ]],
        dom: '<"datatable-header"fl><"datatable-scroll"t><"datatable-footer"ip>',
        language: {
            search: '<span>Search:</span> _INPUT_',
            lengthMenu: '<span>rows per page:</span> _MENU_',
            paginate: { 'first': 'First', 'last': 'Last', 'next': '&rarr;', 'previous': '&larr;' }
        },
        drawCallback: function () {
            $(this).find('tbody tr').slice(-3).find('.dropdown, .btn-group').addClass('dropup');
        },
        preDrawCallback: function() {
            $(this).find('tbody tr').slice(-3).find('.dropdown, .btn-group').removeClass('dropup');
        }
    });

    reload_table();


    $("#handmatig_prijs_form").submit(function(event) {
        /* stop form from submitting normally */
        event.preventDefault();

        if (!event.target.checkValidity()) {
            return false;
        }
        save_manual_price();
    });
});

var reload_table = function(){
    if(table)
        table.destroy();
    table = $('.datatable-ajax').DataTable({
        ajax: SITE_URL + 'prijsafwijkingen/get_list',
        order: [[ 1, "asc" ]],
        stateSave: true,
    }).page.len(50).draw();

    // Add placeholder to the datatable filter option
    $('.dataTables_filter input[type=search]').attr('placeholder','Please enter the keyword');

    // Enable Select2 select for the length option
    $('.dataTables_length select').select2({
        minimumResultsForSearch: Infinity,
        width: 'auto'
    });

    setTimeout(function(){topScroll()}, 1000);
}

var topScroll = function(){
    var tableContainer = $(".dataTables_scrollBody");
    var table = $(".datatable-scroll table");

    var fakeContainer = $(".large-table-fake-top-scroll");
    var fakeDiv = $(".large-table-fake-top-scroll div");

    var tableWidth = table.width();
    fakeDiv.width(tableWidth);

    fakeContainer.scroll(function() {
        tableContainer.scrollLeft(fakeContainer.scrollLeft());
    });
    tableContainer.scroll(function() {
        fakeContainer.scrollLeft(tableContainer.scrollLeft());
    });
}


var approve = function(id, type){

    $.post(SITE_URL + 'prijsafwijkingen/approve/' + id + '/' + type, function(resp){
        resp = JSON.parse(resp);
        if(resp.status){
            reload_table();
        }
    })
}


var reject = function(id, type){
     $.post(SITE_URL + 'prijsafwijkingen/reject/' + id + '/' + type, function(resp){
        resp = JSON.parse(resp);
        if(resp.status){
            reload_table();
        }
    })
}


var manual = function(id, adive_price, type){
    $("#m_type").val(type);
    $("#m_sel_id").val(id);
    $("#m_handmatig_prijs").val(adive_price);
    $("#modal_handmatig").modal();
}

var save_manual_price = function(){
    $.post(SITE_URL + 'prijsafwijkingen/save_manual_price', 
        {
            id: $("#m_sel_id").val(),
            type: $("#m_type").val(),
            handmatig_prijs: $("#m_handmatig_prijs").val()
        }, 
        function(resp){
            resp = JSON.parse(resp);
            if(resp.status){
                reload_table();
                $("#modal_handmatig").modal('toggle');
            }
        })
}