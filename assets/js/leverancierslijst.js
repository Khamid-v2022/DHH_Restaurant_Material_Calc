var table = null;
var divider_rate = 1;
$(function() { 
	$('#info_modal').on('hidden.bs.modal', function() {
	    $(this).find('form').trigger('reset');
        $("#m_leveranciers_id").trigger('change');
        $("#m_locatie_id").trigger('change');
        $("#m_inkoopcategorien_id").trigger('change');
        $("#m_eenheid_id").trigger('change');
        $("#m_eenheden_id").trigger('change');
        $("#m_statiegeld_id").trigger('change');
	});
    $('#modal_download').on('hidden.bs.modal', function() {
        $(this).find('form').trigger('reset');
    });
    $('.select-search').select2();
    $('.select-search[readonly]').select2({disabled:'readonly'});

    $.extend( $.fn.dataTable.defaults, {
        scrollX:        true,
        columnDefs: [{ 
            orderable: false,
            className: 'text-center',
            width: 80,
            targets: [ 0 ]
        }, 
        { 
            className: 'text-right', 
            targets: [5, 6, 7, 9, 10, 12, 14, 16] 
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

    $("#m_form").submit(function(event) {
        /* stop form from submitting normally */
        event.preventDefault();

        if (!event.target.checkValidity()) {
            return false;
        }
        save();
    });
    

    $("#download_form").submit(function(event) {
        $('#modal_download').modal('toggle');
    });

    $("#m_prijs_van, #m_aantal_verpakkingen").on("change", function(){
        if($("#m_prijs_van").val() > 0 && $("#m_aantal_verpakkingen").val() > 0){
            const val = parseFloat($("#m_prijs_van").val() / $("#m_aantal_verpakkingen").val()).toFixed(7);
            $("#m_prijs_per").val(val);
        }else{
            $("#m_prijs_per").val($("#m_prijs_van").val());
        }
        $("#m_prijs_per").trigger('change');

        // if($("#m_prijs_per").val() > 0 && $("#m_aantal_verpakkingen").val() > 0){
        //     const val = parseFloat($("#m_prijs_per").val() * $("#m_aantal_verpakkingen").val()).toFixed(7);
        //     $("#m_netto_stuks_prijs").val(val);
        // }
    })

    $("#m_prijs_per, #m_inhoud_van, #m_kleinste_eenheid_id").on("change", function(){
        if($("#m_prijs_per").val() > 0 && $("#m_inhoud_van").val() > 0){
            
            const val = parseFloat(($("#m_prijs_per").val() / $("#m_inhoud_van").val()) / divider_rate).toFixed(7);
            $("#m_prijs_per_eenheid").val(val);
        }else{
            $("#m_prijs_per_eenheid").val("");
        }
    })

    $("#m_eenheden_id").on("change", function(){
        $.post(SITE_URL + "calculatie_recepten/get_eenheden_info/" + $(this).val(), function(resp){
            resp = JSON.parse(resp);
            if(resp.status){
                divider_rate = resp.msg.division_rate;
                $("#m_kleinste_eenheid_id").val(resp.msg.kleinste_id).trigger('change');
            }
        })
    })
    $("#m_eenheden_id").trigger('change');


    
});

var reload_table = function(){
    if(table)
        table.destroy();
    table = $('.datatable-ajax').DataTable({
        ajax: SITE_URL + 'leverancierslijst/get_list',
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

var add_info_modal = function(){
    $("#m_action_type").val("add");
    $(".action-type").html("Add");

    set_initial_info();
    $("#info_modal").modal();
}


function change_statiegeld(){
    const id = $("#m_statiegeld_id").val();
    $.post(SITE_URL + 'leverancierslijst/get_statiegeld_info/' + id, function(resp){
        resp = JSON.parse(resp);
        if(resp.status){
            $("#m_statiegeld_price").val(resp.msg.price);
        }
    })
}

function set_initial_info(){
    $("#m_prijs_van").val(1).trigger('change');
    const id = $("#m_statiegeld_id").val();
    $.post(SITE_URL + 'leverancierslijst/get_initial_info', 
        {
            id: id
        }, 
        function(resp){
        resp = JSON.parse(resp);
        if(resp.status){
            $("#m_statiegeld_price").val(resp.msg.price);
            // $("#m_artikelnummer").val(resp.msg.max_number);
        }
    })
}


var edit_info = function(id){
    $("#m_action_type").val("edit");
    $(".action-type").html("Edit");
    $("#m_sel_id").val(id);
	$.post(SITE_URL + 'leverancierslijst/get_info/' + id, function(resp){
		resp = JSON.parse(resp);
		if(resp.status){
			const item = resp.msg;
            $("#m_productnaam").val(item.geef_productnaam);
            $("#m_leveranciers_id").val(item.leveranciers_id).trigger('change');
            $("#m_locatie_id").val(item.locatie_id).trigger('change');
            $("#m_inkoopcategorien_id").val(item.inkoopcategorien_id).trigger('change');
            $("#m_artikelnummer").val(item.artikelnummer);
			$("#m_prijs_van").val(item.prijs_van);
            $("#m_aantal_verpakkingen").val(item.aantal_verpakkingen);
            $("#m_eenheid_id").val(item.eenheid_id).trigger('change');
            $("#m_prijs_per").val(item.prijs_per);
            $("#m_inhoud_van").val(item.inhoud_van);
            $("#m_eenheden_id").val(item.eenheden_id).trigger('change');
            $("#m_prijs_per_eenheid").val(item.prijs_per_eenheid);
            $("#m_kleinste_eenheid_id").val(item.kleinste_eenheid_id).trigger('change');
            // $("#m_netto_stuks_prijs").val(item.netto_stuks_prijs);
            $("#m_statiegeld_id").val(item.statiegeld_id).trigger('change');
            $("#m_statiegeld_price").val(item.statiegeld_price);

            $("#info_modal").modal();
		}
	});
}

var copy_info = function(id){
    $("#m_action_type").val("copy");
    $(".action-type").html("Copy");
    // $("#m_sel_id").val(id);
    $.post(SITE_URL + 'leverancierslijst/get_copy_info/' + id, function(resp){
        resp = JSON.parse(resp);
        if(resp.status){
            const item = resp.msg;
            $("#m_productnaam").val(item.geef_productnaam);
            $("#m_leveranciers_id").val(item.leveranciers_id).trigger('change');
            $("#m_locatie_id").val(item.locatie_id).trigger('change');
            $("#m_inkoopcategorien_id").val(item.inkoopcategorien_id).trigger('change');
            // $("#m_artikelnummer").val(item.artikelnummer);
            $("#m_prijs_van").val(item.prijs_van);
            $("#m_aantal_verpakkingen").val(item.aantal_verpakkingen);
            $("#m_eenheid_id").val(item.eenheid_id).trigger('change');
            $("#m_prijs_per").val(item.prijs_per);
            $("#m_inhoud_van").val(item.inhoud_van);
            $("#m_eenheden_id").val(item.eenheden_id).trigger('change');
            $("#m_prijs_per_eenheid").val(item.prijs_per_eenheid);
            $("#m_kleinste_eenheid_id").val(item.kleinste_eenheid_id).trigger('change');
            // $("#m_netto_stuks_prijs").val(item.netto_stuks_prijs);
            $("#m_statiegeld_id").val(item.statiegeld_id).trigger('change');
            $("#m_statiegeld_price").val(item.statiegeld_price);

            $("#info_modal").modal();
        }
    });
}

var delete_info = function(id){
    swal({
        title: "Weet je het zeker?",
        text: "Het product wordt gebruikt in de volgende verkoopproducten en zal daar uit de calculatie verdwijnen?",
        type: "warning",
        showCancelButton: true,
        closeOnConfirm: false,
        confirmButtonText: "Ja",
        cancelButtonText: "Nee",
        cancelButtonColor: "#F4511E",
        customClass: "custom-sweet"
    },
    function() {
        $.post(SITE_URL + 'leverancierslijst/delete_info/' + id,
            function(resp) {
                resp = JSON.parse(resp)
                if(resp.status){
                    // swal({
                    //     title: resp.msg,
                    //     type: "success",
                    //     confirmButtonColor: "#2196F3"
                    // }, function(){
                        reload_table();
                        swal.close()
                    // });
                }
            });
    });
}

var save = function(){
	$.post(SITE_URL + 'leverancierslijst/update_info', 
		{
			action_type: $("#m_action_type").val(),
            sel_id: $("#m_sel_id").val(),
            geef_productnaam: $("#m_productnaam").val(),
            leveranciers_id: $("#m_leveranciers_id").val(),
            locatie_id: $("#m_locatie_id").val(),
            inkoopcategorien_id: $("#m_inkoopcategorien_id").val(),
            artikelnummer: $("#m_artikelnummer").val(),
            prijs_van: $("#m_prijs_van").val(),
            aantal_verpakkingen: $("#m_aantal_verpakkingen").val(),
            eenheid_id: $("#m_eenheid_id").val(),
            prijs_per: $("#m_prijs_per").val(),
            inhoud_van: $("#m_inhoud_van").val(),
            eenheden_id: $("#m_eenheden_id").val(),
            prijs_per_eenheid: $("#m_prijs_per_eenheid").val(),
            kleinste_eenheid_id: $("#m_kleinste_eenheid_id").val(),
            // netto_stuks_prijs: $("#m_netto_stuks_prijs").val(),
            statiegeld_id: $("#m_statiegeld_id").val(),
            statiegeld_price: $("#m_statiegeld_price").val(),
		}, 
		function(resp){
			resp = JSON.parse(resp);
			if(resp.status){
                
				// swal({
		  //           title: resp.msg,
		  //           type: "success",
		  //           confirmButtonColor: "#2196F3"
		  //       }, function(){
		        	$('#info_modal').modal('toggle');
		        	reload_table();
		        // });
			}
	});
}


// upload data from Excel file
function upload_data_from_excel(){
    $.post(SITE_URL + 'leverancierslijst/upload_data_from_excel', function(resp){
        console.log(resp);
    })
}