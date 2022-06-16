var eenheden_table = null;
var kleinste_table = null;
var leveranciers_table = null;
var inkoopcategorien_table = null;
var margegroepen_table = null;
var verkoopgroepen_table = null;
var btw_table = null;
var omzetgroepen_table = null;
var bezorging_table = null;
var statiegeld_table = null;
var eenheid_table = null;
var locatie_table = null;

$(function() { 
    $('.daterange-basic').daterangepicker({
        applyClass: 'bg-slate-600',
        cancelClass: 'btn-default'
    });

	$('#basic_modal, #margegroepen_modal, #btw_modal, #bezorging_modal, #statiegeld_modal').on('hidden.bs.modal', function() {
	    $(this).find('form').trigger('reset');
	});

	$.extend( $.fn.dataTable.defaults, {
        autoWidth: true,
        columnDefs: [{
            width: '30px',
            targets: [0]
        },{
            orderable: false,
            width: '150px',
            targets: [2]
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

    $("#m_basic_form").submit(function(event) {
        event.preventDefault();

        if (!event.target.checkValidity()) {
            return false;
        }

        submit_basic();
    });

    $("#m_margegroepen_form").submit(function(event) {
        event.preventDefault();

        if (!event.target.checkValidity()) {
            return false;
        }

        submit_margegroepen();
    });

    $("#m_btw_form").submit(function(event) {
        event.preventDefault();

        if (!event.target.checkValidity()) {
            return false;
        }

        submit_btw();
    });

    $("#m_bezorging_form").submit(function(event) {
        event.preventDefault();

        if (!event.target.checkValidity()) {
            return false;
        }

        submit_bezorging();
    });

    $("#m_statiegeld_form").submit(function(event) {
        event.preventDefault();

        if (!event.target.checkValidity()) {
            return false;
        }

        submit_statiegeld();
    });
    


    $(".basic-item-add-btn").on('click', function(){
        const title = $(this).parents('.panel-heading').find(".panel-title").html();
        $("#basic_modal_title").html(title + " Add");
        $("#m_action_type").val("add");

        const table_name = $(this).parents('.panel-heading').find(".table-name").val();
        $("#m_table_name").val(table_name);
        $("#basic_modal").modal();
        setTimeout(function(){
            $('#m_basic_item_name').focus();
        }, 500);
    });

    reload_table('eenheden');
    reload_table('kleinste');
    reload_table('leveranciers');
    reload_table('inkoopcategorien');
    load_margegroepen_table();
    reload_table('verkoopgroepen');
    load_btw_table();
    reload_table('omzetgroepen');
    load_bezorging_table();
    load_statiegeld_table();
    reload_table('eenheid');
    reload_table('locatie');
});

function reload_table(param){
    switch(param){
        case 'eenheden':
            load_eenheden_table();
            break;
        case 'kleinste':
            load_kleinste_table();
            break;
        case 'leveranciers':
            load_leveranciers_table();
            break;
        case 'inkoopcategorien':
            load_inkoopcategorien_table();
            break;
        case 'verkoopgroepen':
            load_verkoopgroepen_table();
            break;
        case 'omzetgroepen':
            load_omzetgroepen_table();
            break;
        case 'eenheid':
            load_eenheid_table();
            break;
        case 'locatie':
            load_locatie_table();
            break;
    }
}

var load_eenheden_table = function(){
    if(eenheden_table)
        eenheden_table.destroy();

    eenheden_table = $('#eenheden_table').DataTable({
        "ajax":{
            "url": SITE_URL + 'functions/get_eenhedens'
        },        
        "order": [[ 0, "asc" ]]
    });

    // Add placeholder to the datatable filter option
    $('.dataTables_filter input[type=search]').attr('placeholder','Please enter the keyword');

    // Enable Select2 select for the length option
    $('.dataTables_length select').select2({
        minimumResultsForSearch: Infinity,
        width: 'auto'
    });
}

var load_kleinste_table = function(){
    if(kleinste_table)
        kleinste_table.destroy();

    kleinste_table = $('#kleinste_table').DataTable({
        "ajax":{
            "url": SITE_URL + 'functions/get_kleinste'
        },        
        "order": [[ 0, "asc" ]]
    });

    // Add placeholder to the datatable filter option
    $('.dataTables_filter input[type=search]').attr('placeholder','Please enter the keyword');

    // Enable Select2 select for the length option
    $('.dataTables_length select').select2({
        minimumResultsForSearch: Infinity,
        width: 'auto'
    });
}

var load_leveranciers_table = function(){
    if(leveranciers_table)
        leveranciers_table.destroy();

    leveranciers_table = $('#leveranciers_table').DataTable({
        "ajax":{
            "url": SITE_URL + 'functions/get_leveranciers'
        },        
        "order": [[ 0, "asc" ]]
    });

    // Add placeholder to the datatable filter option
    $('.dataTables_filter input[type=search]').attr('placeholder','Please enter the keyword');

    // Enable Select2 select for the length option
    $('.dataTables_length select').select2({
        minimumResultsForSearch: Infinity,
        width: 'auto'
    });
}

var load_inkoopcategorien_table = function(){
    if(inkoopcategorien_table)
        inkoopcategorien_table.destroy();

    inkoopcategorien_table = $('#inkoopcategorien_table').DataTable({
        "ajax":{
            "url": SITE_URL + 'functions/get_inkoopcategorien'
        },        
        "order": [[ 0, "asc" ]]
    });

    // Add placeholder to the datatable filter option
    $('.dataTables_filter input[type=search]').attr('placeholder','Please enter the keyword');

    // Enable Select2 select for the length option
    $('.dataTables_length select').select2({
        minimumResultsForSearch: Infinity,
        width: 'auto'
    });
}

var load_margegroepen_table = function(){
    if(margegroepen_table)
        margegroepen_table.destroy();

    margegroepen_table = $('#margegroepen_table').DataTable({
        "ajax":{
            "url": SITE_URL + 'functions/get_margegroepen'
        },        
        "order": [[ 0, "asc" ]],
        "columnDefs": [{
            width: '30px',
            targets: [0]
        },{
            orderable: false,
            width: '150px',
            targets: [3]
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

var load_verkoopgroepen_table = function(){
    if(verkoopgroepen_table)
        verkoopgroepen_table.destroy();

    verkoopgroepen_table = $('#verkoopgroepen_table').DataTable({
        "ajax":{
            "url": SITE_URL + 'functions/get_verkoopgroepen'
        },        
        "order": [[ 0, "asc" ]]
    });

    // Add placeholder to the datatable filter option
    $('.dataTables_filter input[type=search]').attr('placeholder','Please enter the keyword');

    // Enable Select2 select for the length option
    $('.dataTables_length select').select2({
        minimumResultsForSearch: Infinity,
        width: 'auto'
    });
}

var load_btw_table = function(){
    if(btw_table)
        btw_table.destroy();

    btw_table = $('#btw_table').DataTable({
        "ajax":{
            "url": SITE_URL + 'functions/get_btw'
        },        
        "order": [[ 0, "asc" ]],
        "columnDefs": [{
            width: '30px',
            targets: [0]
        },{
            orderable: false,
            width: '150px',
            targets: [3]
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

var load_omzetgroepen_table = function(){
    if(omzetgroepen_table)
        omzetgroepen_table.destroy();

    omzetgroepen_table = $('#omzetgroepen_table').DataTable({
        "ajax":{
            "url": SITE_URL + 'functions/get_omzetgroepen'
        },        
        "order": [[ 0, "asc" ]]
    });

    // Add placeholder to the datatable filter option
    $('.dataTables_filter input[type=search]').attr('placeholder','Please enter the keyword');

    // Enable Select2 select for the length option
    $('.dataTables_length select').select2({
        minimumResultsForSearch: Infinity,
        width: 'auto'
    });
}

var load_bezorging_table = function(){
    if(bezorging_table)
        bezorging_table.destroy();

    bezorging_table = $('#bezorging_table').DataTable({
        "ajax":{
            "url": SITE_URL + 'functions/get_bezorging'
        },        
        "order": [[ 0, "asc" ]],
        "columnDefs": [{
            width: '30px',
            targets: [0]
        },{
            orderable: false,
            width: '150px',
            targets: [3]
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

var load_statiegeld_table = function(){
    if(statiegeld_table)
        statiegeld_table.destroy();

    statiegeld_table = $('#statiegeld_table').DataTable({
        "ajax":{
            "url": SITE_URL + 'functions/get_statiegeld'
        },        
        "order": [[ 0, "asc" ]],
        "columnDefs": [{
            width: '30px',
            targets: [0]
        },{
            className: 'text-right',
            targets: [2]
        },{
            orderable: false,
            width: '150px',
            className: 'text-center',
            targets: [3]
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

var load_eenheid_table = function(){
    if(eenheid_table)
        eenheid_table.destroy();

    eenheid_table = $('#eenheid_table').DataTable({
        "ajax":{
            "url": SITE_URL + 'functions/get_eenheid'
        },        
        "order": [[ 0, "asc" ]]
    });

    // Add placeholder to the datatable filter option
    $('.dataTables_filter input[type=search]').attr('placeholder','Please enter the keyword');

    // Enable Select2 select for the length option
    $('.dataTables_length select').select2({
        minimumResultsForSearch: Infinity,
        width: 'auto'
    });
}


var load_locatie_table = function(){
    if(locatie_table)
        locatie_table.destroy();

    locatie_table = $('#locatie_table').DataTable({
        "ajax":{
            "url": SITE_URL + 'functions/get_locatie'
        },        
        "order": [[ 0, "asc" ]]
    });

    // Add placeholder to the datatable filter option
    $('.dataTables_filter input[type=search]').attr('placeholder','Please enter the keyword');

    // Enable Select2 select for the length option
    $('.dataTables_length select').select2({
        minimumResultsForSearch: Infinity,
        width: 'auto'
    });
}




function edit_basic_item(table_name, item_name, id){
    $("#basic_modal_title").html(table_name + " Edit");

    $("#m_action_type").val("edit");
    $("#m_table_name").val(table_name);
    $("#m_sel_id").val(id);

    $("#m_basic_item_name").val(item_name);

    $("#basic_modal").modal();
    setTimeout(function(){
        $('#m_basic_item_name').focus();
    }, 500);
}

function submit_basic(){
    $.post(SITE_URL + 'functions/submit_basic_info', 
        {
            name: $("#m_basic_item_name").val(),
            table_name: $("#m_table_name").val(),
            action_type: $("#m_action_type").val(),
            sel_id: $("#m_sel_id").val()
        }, 
        function(resp){
            resp = JSON.parse(resp);
            if(resp.status){
                // swal({
                //     title: resp.msg,
                //     type: "success",
                //     confirmButtonColor: "#2196F3"
                // }, function(){
                    $('#basic_modal').modal('toggle');
                    reload_table($("#m_table_name").val());
                // });
            }
            else{
                swal({
                    title: resp.msg,
                    type: "warning",
                    confirmButtonColor: "#2196F3"
                }, function(){
                    $('#basic_modal').modal('toggle');
                });
            }
    })
}

function delete_basic_item(table_name, id){
    // swal({
    //     title: "Are you sure?",
    //     text: "Do you want to delete?",
    //     type: "warning",
    //     showCancelButton: true,
    //     closeOnConfirm: false
    // },
    // function() {
        $.post(SITE_URL + 'functions/delete_basic_info', 
            {
                table_name: table_name,
                sel_id: id
            }, 
            function(resp) {
                resp = JSON.parse(resp)
                if(resp.status){
                    // swal({
                    //     title: resp.msg,
                    //     type: "success",
                    //     confirmButtonColor: "#2196F3"
                    // }, function(){
                        reload_table(table_name);
                    // });
                }
            });
    // });
}



function add_margegroepen_modal(){
    $(".m_modal_type").html(" Add");
    $("#m_margegroepen_action_type").val("add");

    $("#margegroepen_modal").modal();
    setTimeout(function(){
        $('#m_margegroepen').focus();
    }, 500);
}

function submit_margegroepen(){
    $.post(SITE_URL + 'functions/submit_margegroepen', 
        {
            margegroepen: $("#m_margegroepen").val(),
            marge: $("#m_marge").val(),
            action_type: $("#m_margegroepen_action_type").val(),
            sel_id: $("#m_margegroepen_sel_id").val()
        }, 
        function(resp){
            resp = JSON.parse(resp);
            if(resp.status){
                // swal({
                //     title: resp.msg,
                //     type: "success",
                //     confirmButtonColor: "#2196F3"
                // }, function(){
                    $('#margegroepen_modal').modal('toggle');
                    load_margegroepen_table();
                // });
            }
            else{
                swal({
                    title: resp.msg,
                    type: "warning",
                    confirmButtonColor: "#2196F3"
                }, function(){
                    $('#margegroepen_modal').modal('toggle');
                });
            }
    })
}

function edit_margegroepen(margegroepen, marge, id){
    $(".m_modal_type").html(" Edit");
    $("#m_margegroepen_action_type").val("edit");

    $("#m_margegroepen_sel_id").val(id);
    $("#m_margegroepen").val(margegroepen);
    $("#m_marge").val(marge);

    $("#margegroepen_modal").modal();
    setTimeout(function(){
        $('#m_marge').focus();
    }, 500);
}

function delete_margegroepen(id){
    // swal({
    //     title: "Are you sure?",
    //     text: "Do you want to delete?",
    //     type: "warning",
    //     showCancelButton: true,
    //     closeOnConfirm: false
    // },
    // function() {
        $.post(SITE_URL + 'functions/delete_margegroepen', 
            {
                sel_id: id
            }, 
            function(resp) {
                resp = JSON.parse(resp)
                if(resp.status){
                    // swal({
                    //     title: resp.msg,
                    //     type: "success",
                    //     confirmButtonColor: "#2196F3"
                    // }, function(){
                        load_margegroepen_table();
                    // });
                }
            });
    // });
}



function add_btw_modal(){
    $(".m_modal_type").html(" Add");
    $("#m_btw_action_type").val("add");

    $("#btw_modal").modal();
    setTimeout(function(){
        $('#m_btw').focus();
    }, 500);
}

function submit_btw(){
    $.post(SITE_URL + 'functions/submit_btw', 
        {
            btw: $("#m_btw").val(),
            btw_factor: $("#m_btw_factor").val(),
            action_type: $("#m_btw_action_type").val(),
            sel_id: $("#m_btw_sel_id").val()
        }, 
        function(resp){
            resp = JSON.parse(resp);
            if(resp.status){
                // swal({
                //     title: resp.msg,
                //     type: "success",
                //     confirmButtonColor: "#2196F3"
                // }, function(){
                    $('#btw_modal').modal('toggle');
                    load_btw_table();
                // });
            }
            else{
                swal({
                    title: resp.msg,
                    type: "warning",
                    confirmButtonColor: "#2196F3"
                }, function(){
                    $('#btw_modal').modal('toggle');
                });
            }
    })
}

function edit_btw(btw, btw_factor, id){
    $(".m_modal_type").html(" Edit");
    $("#m_btw_action_type").val("edit");
    $("#m_btw_sel_id").val(id);
    
    $("#m_btw").val(btw);
    $("#m_btw_factor").val(btw_factor);

    $("#btw_modal").modal();
    setTimeout(function(){
        $('#m_btw_factor').focus();
    }, 500);
}

function delete_btw(id){
    // swal({
    //     title: "Are you sure?",
    //     text: "Do you want to delete?",
    //     type: "warning",
    //     showCancelButton: true,
    //     closeOnConfirm: false
    // },
    // function() {
        $.post(SITE_URL + 'functions/delete_btw', 
            {
                sel_id: id
            }, 
            function(resp) {
                resp = JSON.parse(resp)
                if(resp.status){
                    // swal({
                    //     title: resp.msg,
                    //     type: "success",
                    //     confirmButtonColor: "#2196F3"
                    // }, function(){
                        load_btw_table();
                    // });
                }
            });
    // });
}



function add_bezorging_modal(){
    $(".m_modal_type").html(" Add");
    $("#m_bezorging_action_type").val("add");

    $("#bezorging_modal").modal();
    setTimeout(function(){
        $('#m_bezorging').focus();
    }, 500);
}

function submit_bezorging(){
    $.post(SITE_URL + 'functions/submit_bezorging', 
        {
            bezorging: $("#m_bezorging").val(),
            bezorgfee: $("#m_bezorgfee").val(),
            action_type: $("#m_bezorging_action_type").val(),
            sel_id: $("#m_bezorging_sel_id").val()
        }, 
        function(resp){
            resp = JSON.parse(resp);
            if(resp.status){
                // swal({
                //     title: resp.msg,
                //     type: "success",
                //     confirmButtonColor: "#2196F3"
                // }, function(){
                    $('#bezorging_modal').modal('toggle');
                    load_bezorging_table();
                // });
            }
            else{
                swal({
                    title: resp.msg,
                    type: "warning",
                    confirmButtonColor: "#2196F3"
                }, function(){
                    $('#bezorging_modal').modal('toggle');
                });
            }
    })
}

function edit_bezorging(bezorging, bezorgfee, id){
    $(".m_modal_type").html(" Edit");
    $("#m_bezorging_action_type").val("edit");
    $("#m_bezorging_sel_id").val(id);
    
    $("#m_bezorging").val(bezorging);
    $("#m_bezorgfee").val(bezorgfee);

    $("#bezorging_modal").modal();
    setTimeout(function(){
        $('#m_bezorgfee').focus();
    }, 500);
}

function delete_bezorging(id){
    // swal({
    //     title: "Are you sure?",
    //     text: "Do you want to delete?",
    //     type: "warning",
    //     showCancelButton: true,
    //     closeOnConfirm: false
    // },
    // function() {
        $.post(SITE_URL + 'functions/delete_bezorging', 
            {
                sel_id: id
            }, 
            function(resp) {
                resp = JSON.parse(resp)
                if(resp.status){
                    // swal({
                    //     title: resp.msg,
                    //     type: "success",
                    //     confirmButtonColor: "#2196F3"
                    // }, function(){
                        load_bezorging_table();
                    // });
                }
            });
    // });
}


function add_statiegeld_modal(){
    $(".m_modal_type").html(" Add");
    $("#m_statiegeld_action_type").val("add");

    $("#statiegeld_modal").modal();
    setTimeout(function(){
        $('#m_statiegeld').focus();
    }, 500);
}

function submit_statiegeld(){
    $.post(SITE_URL + 'functions/submit_statiegeld', 
        {
            statiegeld: $("#m_statiegeld").val(),
            price: $("#m_price").val(),
            action_type: $("#m_statiegeld_action_type").val(),
            sel_id: $("#m_statiegeld_sel_id").val()
        }, 
        function(resp){
            resp = JSON.parse(resp);
            if(resp.status){
                // swal({
                //     title: resp.msg,
                //     type: "success",
                //     confirmButtonColor: "#2196F3"
                // }, function(){
                    $('#statiegeld_modal').modal('toggle');
                    load_statiegeld_table();
                // });
            }
            else{
                swal({
                    title: resp.msg,
                    type: "warning",
                    confirmButtonColor: "#2196F3"
                }, function(){
                    $('#statiegeld_modal').modal('toggle');
                });
            }
    })
}

function edit_statiegeld(statiegeld, price, id){
    $(".m_modal_type").html(" Edit");
    $("#m_statiegeld_action_type").val("edit");
    $("#m_statiegeld_sel_id").val(id);
    
    $("#m_statiegeld").val(statiegeld);
    $("#m_price").val(price);

    $("#statiegeld_modal").modal();
    setTimeout(function(){
        $('#m_price').focus();
    }, 500);
}

function delete_statiegeld(id){
    // swal({
    //     title: "Are you sure?",
    //     text: "Do you want to delete?",
    //     type: "warning",
    //     showCancelButton: true,
    //     closeOnConfirm: false
    // },
    // function() {
        $.post(SITE_URL + 'functions/delete_statiegeld', 
            {
                sel_id: id
            }, 
            function(resp) {
                resp = JSON.parse(resp)
                if(resp.status){
                    // swal({
                    //     title: resp.msg,
                    //     type: "success",
                    //     confirmButtonColor: "#2196F3"
                    // }, function(){
                        load_statiegeld_table();
                    // });
                }
            });
    // });
}

function save_leveranciernaam(){
    var leveranciernaam_name = $("#leveranciernaam_name").val();
    if(leveranciernaam_name == ""){
        swal({
            title: "Please fill the leveranciernaam",
            type: "warning",
            confirmButtonColor: "#2196F3"
        });
        return
    }

    $.post(SITE_URL + 'functions/update_leveranciernaam', {name: leveranciernaam_name}, function(resp){
        resp = JSON.parse(resp);
        if(resp.status){
            swal({
                title: resp.msg,
                type: "success",
                confirmButtonColor: "#2196F3"
            }, function(){
            });
        }
    })
}