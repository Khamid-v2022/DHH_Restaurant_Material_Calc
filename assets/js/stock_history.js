var table = null;
var popup_table = null;

$(function() { 
    $('#voorraadtelling_stock_modal').on('hidden.bs.modal', function() {
        $(this).find('form').trigger('reset');
    });
   
    $('.select-search').select2();
    $('.select-search[readonly]').select2({disabled:'readonly'});

    $.extend( $.fn.dataTable.defaults, {
        autoWidth: false,
        scrollX: true,
        // columnDefs: [{ 
        //     orderable: false,
        //     className: 'text-center'
        // }],
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

    // stock modal actions
    $("#m_statiegeld_omdoos").on("change", function(){
        $.post(SITE_URL + "voorraadtelling/get_statiegeld_info/" + $(this).val(), function(resp){
            resp = JSON.parse(resp);
            if(resp.status){
                $("#m_statiegeld_eenheid").val(resp.msg.price).trigger('change');
            }
        })
    }) 

    $("#m_statiegeld_los").on("change", function(){
        $.post(SITE_URL + "voorraadtelling/get_statiegeld_info/" + $(this).val(), function(resp){
            resp = JSON.parse(resp);
            if(resp.status){
                $("#m_statiegeld_eenheid2").val(resp.msg.price).trigger('change');
            }
        })
    }) 

    $("#m_statiegeld_eenheid, #m_aantal_omdozen, #m_statiegeld_eenheid2, #m_lege_omdozen, #m_losse_geteld, #m_lege_geteld, #m_prijs_kleinste_eenheid").on("change", function(){
        var omdoos_totaal = 0;
        omdoos_totaal = $("#m_aantal_omdozen").val() *  $("#m_statiegeld_eenheid").val() + $("#m_aantal_omdozen").val() * $("#m_inhoud_verpakking").val() * $("#m_statiegeld_eenheid2").val() + $("#m_lege_omdozen").val() * $("#m_statiegeld_eenheid").val();

        $("#m_omdoos_statie_totaal").val(parseFloat(omdoos_totaal).toFixed(4)).trigger("change");
        
        var flessen = 0;
        flessen = (parseFloat($("#m_losse_geteld").val()) + parseFloat($("#m_lege_geteld").val())) * $("#m_statiegeld_eenheid2").val();
        $("#m_statie_losse_flessen").val(parseFloat(flessen).toFixed(4)).trigger("change");

        var waarde1 = 0;
        waarde1 =  $("#m_aantal_omdozen").val() * $("#m_prijs_omdoos").val() +  (parseFloat($("#m_losse_geteld").val()) + parseFloat($("#m_lege_geteld").val())) * $("#m_prijs_kleinste_eenheid").val();

        $("#m_waarde_voorraad1").val(parseFloat(waarde1).toFixed(4)).trigger('change');
    })

    $("#m_omdoos_statie_totaal, #m_statie_losse_flessen").on('change', function(){
        let total = parseFloat($("#m_omdoos_statie_totaal").val()) + parseFloat($("#m_statie_losse_flessen").val());
        $("#m_total_statiegeld").val(parseFloat(total).toFixed(4)).trigger('change');
    })

    $("#m_total_statiegeld, #m_waarde_voorraad1").on('change', function(){
        let total = parseFloat($("#m_total_statiegeld").val()) + parseFloat($("#m_waarde_voorraad1").val());
        $("#m_waarde_voorraad2").val(parseFloat(total).toFixed(4));
    })

    $("#m_voorraadtelling_stock_form").submit(function(event) {
        /* stop form from submitting normally */
        event.preventDefault();

        if (!event.target.checkValidity()) {
            return false;
        }

        save_stock();
    });
});

var reload_table = function(){
    if(table)
        table.destroy();
    table = $('#main_table').DataTable({
        ajax: SITE_URL + 'voorraadtelling/get_stock_histories/' + $("#sel_id").val(),
        stateSave: true,
        columnDefs: [{
            width: 200,
            targets: [ 1 ]
        },{ 
            className: 'text-right',
            targets: [ 2, 3, 5, 6, 7, 8, 10, 11, 12, 13, 14, 15, 16, 17 ]
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

var delete_item = function(id){
    $.post(SITE_URL + 'voorraadtelling/delete_stock_history/' + id, function(resp){
        resp = JSON.parse(resp);
        if(resp.status){
            reload_table();
        }
    })
}


var add_stock_modal = function(){
    $("#m_s_sel_id").val($("#sel_id").val());
    $.post(SITE_URL + 'voorraadtelling/get_copy_info/' + $("#sel_id").val(), function(resp){
        resp = JSON.parse(resp);
        if(resp.status){
            let info = resp.msg;
            // from leveran.. table
            $("#m_inhoud_verpakking").val(info.inhoud_van);
            $("#m_prijs_omdoos").val(info.prijs_van);
            $("#m_statiegeld_omdoos").val(info.statiegeld_id).trigger("change");
            $("#m_statiegeld_eenheid").val(info.statiegeld_price);
            $("#m_prijs_kleinste_eenheid").val(info.prijs_per_eenheid).trigger("change");

            // initialize
            $("#m_statiegeld_los").val(0).trigger("change");
           
        }
    })
    

    $("#voorraadtelling_stock_modal").modal();
}


var save_stock = function(){
    let aantal_geteld =  parseFloat(($("#m_losse_geteld").val()?$("#m_losse_geteld").val():0)) + parseFloat(($("#m_lege_geteld").val()?$("#m_lege_geteld").val():0));
    let waarde_voorraad = $("#m_waarde_voorraad1").val();
    
    $.post(SITE_URL + 'voorraadtelling/save_copy_stock', 
        {
            leve_copy_id: $("#m_s_sel_id").val(),
            statiegeld_id: $("#m_statiegeld_omdoos").val(),
            statiegeld_price: $("#m_statiegeld_eenheid").val(),
            
            aantal_omdozen: $("#m_aantal_omdozen").val(),
            lege_omdozen: $("#m_lege_omdozen").val(),
            omdoos_statiegeld_totaal: $("#m_omdoos_statie_totaal").val(),
            statiegeld_los: $("#m_statiegeld_los").val(),
            statiegeld_eenheid2: $("#m_statiegeld_eenheid2").val(),
            losse_geteld: $("#m_losse_geteld").val(),
            lege_geteld: $("#m_lege_geteld").val(),
            statiegeld_losse_flessen: $("#m_statie_losse_flessen").val(),
            aantal_geteld: aantal_geteld,
            waarde_voorraad: waarde_voorraad,
            waarde_voorraad2: $("#m_waarde_voorraad2").val(),
            waard_statiegeld: $("#m_total_statiegeld").val(),
            total_voorraad_waarde: parseFloat($("#m_total_statiegeld").val()) + parseFloat(waarde_voorraad),
            
        }, 
        function(resp){
            resp = JSON.parse(resp);
            if(resp.status){
                 $("#voorraadtelling_stock_modal").modal('toggle');
                 reload_table();
            }
        })
}