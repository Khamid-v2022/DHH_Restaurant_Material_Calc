var table = null;
$(function() {
    $('.select-search').select2();

    $.extend( $.fn.dataTable.defaults, {
        autoWidth: false,
        scrollX:   true,
        columnDefs: [{ 
            orderable: false,
            targets: [ 0 ],
            className: 'text-center',
        }, 
        { 
            className: 'text-right', 
            targets: [4, 6, 7, 8, 9] 
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

    $("#m_form").submit(function(event) {
        /* stop form from submitting normally */
        event.preventDefault();

        if (!event.target.checkValidity()) {
            return false;
        }
        save();
    });
});




var reload_table = function(){
    if(table)
        table.destroy();
    table = $('.datatable-ajax').DataTable({
        ajax: SITE_URL + 'cv/get_list',
        order: [[ 1, "asc" ]]
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


function topScroll(){

    var tableContainer = $(".dataTables_scrollBody");
    var table_scroll = $(".datatable-scroll .table");

    var fakeContainer = $(".large-table-fake-top-scroll");
    var fakeDiv = $(".large-table-fake-top-scroll div");

    var tableWidth = table_scroll.width();
    
    fakeDiv.width(tableWidth);

    fakeContainer.scroll(function() {
        tableContainer.scrollLeft(fakeContainer.scrollLeft());
    });
    tableContainer.scroll(function() {
        fakeContainer.scrollLeft(tableContainer.scrollLeft());
    });
}

function new_calculate(){
    //empty ticket create
    $.post(SITE_URL + 'calculatie/create_ticket_ajax', function(resp){
        resp = JSON.parse(resp);
        if(resp.status){
            location.href = SITE_URL + 'calculatie/edit_ticket/' + resp.msg;
        }
    })
}

function edit_ticket(id){
    location.href = SITE_URL + 'calculatie/edit_ticket/' + id;
}

function delete_ticket(id){
    // swal({
    //     title: "Are you sure?",
    //     text: "Do you want to delete it?",
    //     type: "warning",
    //     showCancelButton: true,
    //     closeOnConfirm: false
    // },
    // function() {
        $.post(SITE_URL + 'calculatie/delete_ticket/' + id, function(resp){
            resp = JSON.parse(resp)
            if(resp.status){
                // swal({
                //     title: resp.msg,
                //     type: "success",
                //     confirmButtonColor: "#2196F3"
                // }, function(){
                    location.reload();
                // });
            }
         })
    // });
}

function copy_ticket(id){
    // swal({
    //     title: "Are you sure?",
    //     text: "Do you want to copy it?",
    //     type: "warning",
    //     showCancelButton: true,
    //     closeOnConfirm: false
    // },
    // function() {
        location.href = SITE_URL + 'calculatie/copy_ticket/' + id;
    // });
}