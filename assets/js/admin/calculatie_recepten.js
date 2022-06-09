var list = [];

$(function() { 

    if($("#inkoopartikelen_array").html()){

        list = JSON.parse($("#inkoopartikelen_array").html());
        console.log(list);
        draw_item_table();
    }
	
    $('#add_modal').on('hidden.bs.modal', function() {
	    $(this).find('form').trigger('reset');
	});
    
   
    $("#m_leverancierslijst_id").on('change', function(){
        $.post(SITE_URL + 'calculatie_recepten/get_leverancierslijst_info/' + $(this).val(), function(resp){
            resp = JSON.parse(resp);
            if(resp.status){
                // $("#m_eenheden").val(resp.msg.eenheden);
                $("#m_eenheden").val(resp.msg.kleinste);
                $("#m_prijs_per_eenheid").val(resp.msg.prijs_per_eenheid).trigger('change');
            }
        })
    });

    $("#m_benodigde_hoeveelheid, #m_prijs_per_eenheid").on('change', function(){
        const val = parseFloat($("#m_benodigde_hoeveelheid").val() * $("#m_prijs_per_eenheid").val()).toFixed(7);
        $("#m_kostprijs").val(val);
    });

    $("#modal_form").submit(function(event) {
        /* stop form from submitting normally */
        event.preventDefault();

        if (!event.target.checkValidity()) {
            return false;
        }
        save_item();
    });

    $("#document_form").submit(function(event) {
        /* stop form from submitting normally */
        event.preventDefault();

        if (!event.target.checkValidity()) {
            return false;
        }
        save_ticket();
    });

    $('.select-search').select2();

    $('.select-search[readonly]').select2({disabled:'readonly'});

    $("#eenheden_id").on('change', function(){

        $(".eenheden-id-pdf").html($("#eenheden_id :selected").text());

        $(".eenheden-id").val($(this).val()).trigger('change');
        $.post(SITE_URL + "calculatie_recepten/get_eenheden_info/" + $(this).val(), function(resp){
            resp = JSON.parse(resp);
            if(resp.status){
                $("#kleinste_id").val(resp.msg.kleinste_id).trigger('change');
            }
        })
    })

    $("#inkoopcategorie_id").on('change', function(){
        $(".inkoopcategorie-id").val($(this).val()).trigger('change');

        $(".inkoopcategorie-id-pdf").html($("#inkoopcategorie_id :selected").text());
        
    })

    $("#omzetgroepen_id").on('change', function(){
        $(".omzetgroepen-id").html($("#omzetgroepen_id :selected").text());
    })

    $("#kleinste_id").on('change', function(){
        $(".kleinste-id").html($("#kleinste_id :selected").text());
    })

    $("#eenheid_id").on('change', function(){
        $(".eenheid-id").html($("#eenheid_id :selected").text());
    })

    $("#locatie_id").on('change', function(){
        $(".locatie-id").html($("#locatie_id :selected").text());
    })

    $("#recept_naam").on('change', function(){
        $(".recept-naam").html($(this).val());
    })

    $("#verlies_procentage").on('change', function(){
        $(".verlies-procentages").html($(this).val());
    })

    $("#total_gewicht").on('change', function(){
        $(".total-gewicht").html($(this).val());
    })

    $("#aantal_verpakkingen").on('change', function(){
        $(".aantal-verpakkingen").html($(this).val());
    })

});

var draw_item_table = function(){
    let inkoo_html = "";
    let dispo_html = "";

    let inkoo_html_pdf = "";
    let dispo_html_pdf = "";

    let inkoo_total = 0;
    let dispo_total = 0;

    let index = 0;
    for(item of list){
        let item_html = "";
        item_html += "<tr>";
        item_html += "<td>" + item.ink_dis_name + "</td>";
        item_html += "<td>" + item.netto_prijs + "</td>";
        item_html += "<td class='text-right'>" + item.eenheid_kleinste + "</td>";
        item_html += "<td class='text-right'>" + item.benodigde + "</td>";
        item_html += "<td class='text-right'>€  " + parseFloat(item.kostprijs).toFixed(7) + "</td>";
        let item_html_pdf = item_html;
        item_html_pdf += "</tr>";

        item_html += "<td class='text-center'>";
        if(item.type == '1'){
            item_html += "<button type='button' class='btn border-info text-info-600 btn-flat btn-icon position-right' onclick='edit_item(" + index + ")' title='edit'><i class='icon-pencil'></i></button>";
        }else if(item.type == '2'){
            item_html += "<button type='button' class='btn border-info text-info-600 btn-flat btn-icon position-right' onclick='edit_item(" + index + ")' title='edit'><i class='icon-pencil'></i></button>";
        }
        item_html += "<button type='button' class='btn border-warning text-warning-600 btn-flat btn-icon position-right' onclick='delete_item(" + index + ")' title='delete'><i class='icon-bin'></i></button>";
        item_html += "</td>";
        item_html += "</tr>";
        if(item.type == '1'){
            inkoo_html += item_html;
            inkoo_html_pdf += item_html_pdf;

            inkoo_total += parseFloat(item.kostprijs);
        }else if(item.type == '2'){
            dispo_html += item_html;
            dispo_html_pdf += item_html_pdf;

            dispo_total += parseFloat(item.kostprijs);
        }
        index++;
    }

    $("#inkoopartikelen_table_content").html(inkoo_html);
    $(".inkoopartikelen-table-content").html(inkoo_html_pdf);

    $("#disposable_table_content").html(dispo_html);
    $(".disposable-table-content").html(dispo_html_pdf);

    $("#sub_total1_for_print").html("€  " + parseFloat(inkoo_total).toFixed(7));
    $(".sub-total1-for-print").html("€  " + parseFloat(inkoo_total).toFixed(7));
    $("#sub_total1").html(parseFloat(inkoo_total).toFixed(7));

    $("#sub_total2_for_print").html("€  " + parseFloat(dispo_total).toFixed(7));
    $(".sub-total2-for-print").html("€  " + parseFloat(inkoo_total).toFixed(7));
    $("#sub_total2").html(parseFloat(dispo_total).toFixed(7));

    $("#totaal_for_print").html("€  " + parseFloat(inkoo_total + dispo_total).toFixed(7));
    $(".totaal-for-print").html("€  " + parseFloat(inkoo_total + dispo_total).toFixed(7));
    $("#totaal").html(parseFloat(inkoo_total + dispo_total).toFixed(7));
}


var addModal = function(type){
    $("#m_type").val(type);
    $("#m_action_type").val('add');
    $(".title-type").html("SELECTEER");
    if(type == 1){
        $(".title-name").html("Inkoopartikelen");
    }else{
        $(".title-name").html("Disposables");
    }
    $("#m_leverancierslijst_id").trigger('change');
    $("#add_modal").modal();
}

var edit_item = function(index){
    $(".title-type").html("Edit");
    $("#m_type").val(list[index].type);
    $("#m_action_type").val('edit');
    $("#m_sel_id").val(index);
    if(list[index].type == '1'){
        $(".title-name").html("Inkoopartikelen");
    }else{
        $(".title-name").html("Disposables");
    }

    $("#m_leverancierslijst_id").val(list[index].leverancierslijst_id).trigger('change');
    $("#m_benodigde_hoeveelheid").val(list[index].benodigde);
    $("#add_modal").modal();
}

var delete_item = function(index){
    list.splice(index, 1);
    draw_item_table();
}

var save_item = function(){
    if($("#m_action_type").val() == 'add'){
        var item = {
            recepten_ticket_id: $("#ticket_id").val(),
            leverancierslijst_id: $("#m_leverancierslijst_id").val(),
            netto_prijs: $("#m_eenheden").val(),
            eenheid_kleinste: $("#m_prijs_per_eenheid").val(),
            benodigde: $("#m_benodigde_hoeveelheid").val(),
            kostprijs: $("#m_kostprijs").val(), 
            type: $("#m_type").val(),
            ink_dis_name: $("#m_leverancierslijst_id :selected").text()
        }
        list.push(item);
    }else if($("#m_action_type").val() == 'edit'){
        let index = $("#m_sel_id").val();

        list[index].leverancierslijst_id = $("#m_leverancierslijst_id").val();
        list[index].netto_prijs = $("#m_eenheden").val();
        list[index].eenheid_kleinste = $("#m_prijs_per_eenheid").val();
        list[index].benodigde = $("#m_benodigde_hoeveelheid").val();
        list[index].kostprijs = $("#m_kostprijs").val();
        list[index].type = $("#m_type").val();
        list[index].ink_dis_name = $("#m_leverancierslijst_id :selected").text();
    }

    draw_item_table();
    $('#add_modal').modal('toggle');
}

var save_ticket = function(){
    $.post(SITE_URL + 'calculatie_recepten/save_ticket',
        {
            mode: $("#mode").val(),
            id: $("#ticket_id").val(),
            recept_naam: $("#recept_naam").val(),
            recept_id: $("#recept_id").val(),
            inkoopcategorie_id: $("#inkoopcategorie_id").val(),
            sub_total1: $("#sub_total1").html(),
            sub_total2: $("#sub_total2").html(),
            total: $("#totaal").html(),
            total_gewicht: $("#total_gewicht").val(),
            eenheden_id: $("#eenheden_id").val(),
            omzetgroepen_id: $("#omzetgroepen_id").val(),
            kleinste_id: $("#kleinste_id").val(),
            verlies_procentage: $("#verlies_procentage").val(),
            aantal_verpakkingen: $("#aantal_verpakkingen").val(),
            eenheid_id: $("#eenheid_id").val(),
            locatie_id: $("#locatie_id").val(), 
            item_list: list
        },
        function(resp) {
            resp = JSON.parse(resp)
            if(resp.status){
                // swal({
                //     title: resp.msg,
                //     type: "success",
                //     confirmButtonColor: "#2196F3"
                // }, function(){
                    location.href = SITE_URL + 'calculatie_recepten';
                // });
            }
        });
}

// delete selected ticket
var reset_ticket = function(){
    location.href = SITE_URL + 'calculatie_recepten';
}


var save_pdf = function(){
    return xepOnline.Formatter.Format('print_pdf');
}