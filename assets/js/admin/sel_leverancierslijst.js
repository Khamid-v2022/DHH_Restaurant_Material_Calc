var table = null;
var divider_rate = 1;
$(function() {
    $('#voorraadtelling_modal').on('hidden.bs.modal', function() {
        $(this).find('form').trigger('reset');
    });
    $('.select-search').select2();
    $('.select-search[readonly]').select2({disabled:'readonly'});

    $.extend( $.fn.dataTable.defaults, {
        scrollX:        true,
        columnDefs: [{ 
            orderable: false,
            width: 80,
            targets: [ 0 ]
        }, 
        { 
            className: 'text-right', 
            targets: [5, 6, 7, 9, 10, 12, 14, 16, 17, 18, 19, 20, 21] 
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
        },
        // "lengthChange": false,
        "pageLength": 25
    });

    reload_table();
    
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

    $("#m_voorraadtelling_form").submit(function(event) {
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
    table = $('.datatable-ajax').DataTable({
        ajax: {
            url: SITE_URL + 'voorraadtelling/get_leverancierslijst/' + $("#sel_id").val(),
            complete: function(data){
                topScroll();
            }
        },
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

// var stock_modal = function(id){
//     $("#m_s_sel_id").val(id);
//     $.post(SITE_URL + 'voorraadtelling/get_copy_info/' + id, function(resp){
//         resp = JSON.parse(resp);
//         if(resp.status){
//             let info = resp.msg;
//             // from leveran.. table
//             $("#m_statiegeld_omdoos").val(info.statiegeld_id).trigger("change");
//             $("#m_statiegeld_eenheid").val(info.statiegeld_price);

//             // new fields
//             $("#m_statiegeld_los").val(info.statiegeld_los).trigger("change");
//             $("#m_statiegeld_eenheid2").val(info.statiegeld_eenheid2);
//             $("#m_aantal_omdozen").val(info.aantal_omdozen);
//             $("#m_lege_omdozen").val(info.lege_omdozen).trigger("change");
//             $("#m_omdoos_statie_totaal").val(info.omdoos_statiegeld_totaal).trigger("change");
//             $("#m_losse_geteld").val(info.losse_geteld);
//             $("#m_lege_geteld").val(info.lege_geteld).trigger("change");
            
//             // from leveran.. table
//             $("#m_inhoud_verpakking").val(info.inhoud_van);
//             $("#m_prijs_omdoos").val(info.prijs_van);
//             $("#m_prijs_kleinste_eenheid").val(info.prijs_per_eenheid).trigger("change");
            
//             // new fields
//             $("#m_statie_losse_flessen").val(info.statiegeld_losse_flessen).trigger("change");
//         }
//     })
    

//     $("#voorraadtelling_modal").modal();
// }

var stock_modal = function(id){
    $("#m_s_sel_id").val(id);
    $.post(SITE_URL + 'voorraadtelling/get_copy_info/' + id, function(resp){
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

    $("#voorraadtelling_modal").modal();
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
                 $("#voorraadtelling_modal").modal('toggle');
                 reload_table();
            }
        })
}