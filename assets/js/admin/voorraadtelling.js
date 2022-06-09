var table = null;
var popup_table = null;

$(function() { 
    $('#voorraadtelling_add_modal').on('hidden.bs.modal', function() {
        $(this).find('form').trigger('reset');
    });
   
    $('.select-search').select2();
    $('.select-search[readonly]').select2({disabled:'readonly'});

    $.extend( $.fn.dataTable.defaults, {
        autoWidth: false,
        columnDefs: [{ 
            orderable: false,
            className: 'text-center'
        }],
        // order: [[ 1, "asc" ]],
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
        },
        // "lengthChange": false,
        // "pageLength": 25
    });

    reload_table();  

    $("#voorraadtelling_add_modal").submit(function(event) {
        /* stop form from submitting normally */
        event.preventDefault();

        if (!event.target.checkValidity()) {
            return false;
        }
        save_voorraadtelling();
    });

    $("#m_locatie").on("change", function(){
        reload_popup_table();
    })
});

var reload_table = function(){
    if(table)
        table.destroy();
    table = $('#main_table').DataTable({
        ajax: SITE_URL + 'voorraadtelling/get_list',
        stateSave: true,
        columnDefs: [{
            width: 200,
            targets: [ 1 ]
        }],
    });

    // Add placeholder to the datatable filter option
    $('.dataTables_filter input[type=search]').attr('placeholder','Please enter the keyword');

    // Enable Select2 select for the length option
    $('.dataTables_length select').select2({
        minimumResultsForSearch: Infinity,
        width: 'auto'
    });
}

var reload_popup_table = function(){
    if(popup_table)
        popup_table.destroy();
    popup_table = $('#popup_table').DataTable({
        ajax: SITE_URL + 'voorraadtelling/get_leverancierslijst_by_locatie/' + $("#m_start_sel_id").val() + '/' + $("#m_locatie").val(),
    });

    // Add placeholder to the datatable filter option
    $('.dataTables_filter input[type=search]').attr('placeholder','Please enter the keyword');

    // Enable Select2 select for the length option
    $('.dataTables_length select').select2({
        minimumResultsForSearch: Infinity,
        width: 'auto'
    });

}

var add_info_modal = function(){
    $("#m_sel_id").val("");
    $("#m_action_type").val("add");
    $("#voorraadtelling_add_modal").modal();
}

var edit_item = function(id, name){
    $("#m_sel_id").val(id);
    $("#m_naam").val(name);
    $("#m_action_type").val("edit");
    $("#voorraadtelling_add_modal").modal();
}

var save_voorraadtelling = function(){
    $.post(SITE_URL + 'voorraadtelling/update_info', 
        {
            action_type:  $("#m_action_type").val(),
            sel_id: $("#m_sel_id").val(),
            name: $("#m_naam").val()
        }, function(resp){
            resp = JSON.parse(resp);
            if(resp.status){
                $('#voorraadtelling_add_modal').modal('toggle');
                reload_table();
            }else{
                swal({
                    title: resp.msg,
                    type: "warning",
                    confirmButtonColor: "#2196F3"
                }, function(){
                    $('#voorraadtelling_add_modal').modal('toggle');
                });
            }
    })
}

var delete_item = function(id){
    $.post(SITE_URL + 'voorraadtelling/delete_item/' + id, function(resp){
        resp = JSON.parse(resp);
        if(resp.status){
            reload_table();
        }
    })
}

var excel_download = function(id){
    location.href = SITE_URL + 'voorraadtelling/excel/' + id;
}

var start_item = function(id){
    $("#m_start_sel_id").val(id);
    reload_popup_table();
    $("#start_modal").modal();
}