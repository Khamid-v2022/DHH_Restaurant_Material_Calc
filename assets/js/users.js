var table = null;
$(function() {
    $('#user_modal').on('hidden.bs.modal', function() {
        $(this).find('form').trigger('reset');
    });
    

    $.extend( $.fn.dataTable.defaults, {
        autoWidth: true,
        columnDefs: [{ 
            orderable: false,
            width: '200px',
            targets: [ 3 ],
            className: 'text-center', 
        }],
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
        ajax: SITE_URL + 'users/get_users',
        // "order": [[ 0, "asc" ]]
    }).page.len(50).draw();

    // Add placeholder to the datatable filter option
    $('.dataTables_filter input[type=search]').attr('placeholder','Please enter the keyword');

    // Enable Select2 select for the length option
    $('.dataTables_length select').select2({
        minimumResultsForSearch: Infinity,
        width: 'auto'
    });
}

function add_user_modal(){
    $(".action-type").html("Add");
    $("#m_action_type").val("add");

    $("#user_modal").modal();
}

function edit_user(user_name, email, role, id){
    $(".action-type").html("Edit");
    $("#m_action_type").val("edit");

    $("#m_sel_id").val(id);
    $("#m_user_name").val(user_name);
    $("#m_user_email").val(email);
    // $("#m_user_role").val(role);

    $("#user_modal").modal();
}

function save(){
    $(".icon-spinner10").css({"display": "inline-block"});
    $.post(SITE_URL + 'users/save_user', 
        {
            action_type: $("#m_action_type").val(),
            sel_id: $("#m_sel_id").val(),
            user_name: $("#m_user_name").val(),
            email: $("#m_user_email").val(),
            // role: $("#m_user_role").val()
        }, 
        function(resp){
        resp = JSON.parse(resp)
        if(resp.status){
            $(".icon-spinner10").css({"display": "none"});
            $("#user_modal").modal('toggle');
            reload_table();
        }else{
            swal({
                title: resp.msg,
                type: "error",
                confirmButtonColor: "#2196F3"
            }, function(){
                $(".icon-spinner10").css({"display": "none"});
                return;
            });
        }
    })
}

function delete_user(id){
    $.post(SITE_URL + 'users/delete_user/' + id, function(resp){
        resp = JSON.parse(resp)
        if(resp.status){
            reload_table();
        }
    })
}

function reset_password(id){
    $.post(SITE_URL + 'users/format_password/' + id, function(resp){
        resp = JSON.parse(resp)
        if(resp.status){
            if(resp.status){
                swal({
                    title: resp.msg,
                    type: "success",
                    confirmButtonColor: "#2196F3"
                }, function(){
                    return;
                });
            }
        }
    })
}