var list = [];

$(function() { 

    if($("#inkoopartikelen_array").html()){

        list = JSON.parse($("#inkoopartikelen_array").html());
        draw_item_table();
    }
	
    $('#add_modal').on('hidden.bs.modal', function() {
	    $(this).find('form').trigger('reset');
	});

    let val1 = ($("#marge1").val() * $("#sub_total1").val() + $("#marge2").val() * $("#sub_total2").val()) * $("#bezorgfee").val() * $("#btw_factor").val();
    if(val1){
        val1 = parseFloat(val1).toFixed(7);
        $("#advies_verkoopprijs").val(val1);
        $(".advies-verkoopprijs").html(val1);

    }

    if($("#btw_factor").val() > 0){
        let val2 = 0;
        if($("#handmatige_verkoopprijs").val() > 0){
            val2 = $("#handmatige_verkoopprijs").val() / $("#btw_factor").val() - parseFloat($("#totaal").html());
        }else{
            val2 = $("#advies_verkoopprijs").val() / $("#btw_factor").val() - parseFloat($("#totaal").html());
        }
        val2 = parseFloat(val2).toFixed(7);
        $("#verschillen_tussen").val(val2);
        $(".verschillen-tussen").html(val2);
    }else{
        $("#verschillen_tussen").val("");
        $(".verschillen-tussen").html("");
    }

    if($("#advies_verkoopprijs").val() > 0){
        const percentage = parseInt((1 - $("#handmatige_verkoopprijs").val() / $("#advies_verkoopprijs").val()) * 100);
        $("#procentuele_afwijking").val(percentage);
        $(".procentuele-afwijking").html(percentage);
    }
    
    $("#margegroepen_id1").on('change', function(){
        $(".margegroepen-id1").html($("#margegroepen_id1 :selected").text());

        if($(this).val() == "0"){
            $("#marge1").val("");
            $("#marge1").trigger('change');
        }else{
            $.post(SITE_URL + 'calculatie/get_margegroepen_info/' + $(this).val(), function(resp){
                resp = JSON.parse(resp);
                if(resp.status){
                    $("#marge1").val(resp.msg.marge);
                    $(".marge1").html(resp.msg.marge);
                    $("#marge1").trigger('change');
                }
            });
        }
    });

    $("#margegroepen_id2").on('change', function(){
        if($(this).val() == "0"){
            $("#marge2").val("");
            $("#marge2").trigger('change');
        }
        else{
            $.post(SITE_URL + 'calculatie/get_margegroepen_info/' + $(this).val(), function(resp){
                resp = JSON.parse(resp);
                if(resp.status){
                    $("#marge2").val(resp.msg.marge);
                    $("#marge2").trigger('change');
                }
            });
        }
    });
    $("#bezorging_id").on('change', function(){
        $.post(SITE_URL + 'calculatie/get_bezorging_info/' + $(this).val(), function(resp){
            resp = JSON.parse(resp);
            if(resp.status){
                $("#bezorgfee").val(resp.msg.bezorgfee);
                $("#bezorgfee").trigger('change');

                $(".bezorging_id").html($("#bezorging_id :selected").text());
                $(".bezorgfee").html(resp.msg.bezorgfee);
            }
        });
    });
    $("#btw_id").on('change', function(){
        $.post(SITE_URL + 'calculatie/get_btw_info/' + $(this).val(), function(resp){
            resp = JSON.parse(resp);
            if(resp.status){
                $("#btw_factor").val(resp.msg.btw_factor);
                $("#btw_factor").trigger('change');

                $(".btw-id").html($("#btw_id :selected").text());
                $(".btw-factor").html(resp.msg.btw_factor);
            }
        });
    });


    $("#marge1, #marge2, #sub_total1, #sub_total2, #bezorgfee, #btw_factor").on('change', function(){
        
        let val = ($("#marge1").val() * $("#sub_total1").val() + $("#marge2").val() * $("#sub_total2").val()) * $("#bezorgfee").val() * $("#btw_factor").val();
        
        val = parseFloat(val).toFixed(7);
        $("#advies_verkoopprijs").val(val);
        $("#advies_verkoopprijs").trigger('change');

        $(".advies-verkoopprijs").html(val);
       
    });

    $("#handmatige_verkoopprijs, #btw_factor, #advies_verkoopprijs, #totaal").on('change', function(){

        $(".handmatige-verkoopprijs").html($("#handmatige_verkoopprijs").val());
        let val2 = 0;
        if($("#handmatige_verkoopprijs").val() > 0){
            val2 = $("#handmatige_verkoopprijs").val() / $("#btw_factor").val() - parseFloat($("#totaal").html());
        }else{
            val2 = $("#advies_verkoopprijs").val() / $("#btw_factor").val() - parseFloat($("#totaal").html());
        }
        val2 = parseFloat(val2).toFixed(7);
        $("#verschillen_tussen").val(val2);
        $(".verschillen-tussen").html(val2);       

        if($("#advies_verkoopprijs").val() > 0){
            const percentage = parseInt((1 - $("#handmatige_verkoopprijs").val() / $("#advies_verkoopprijs").val()) * 100);
            $("#procentuele_afwijking").val(percentage);
            $(".procentuele-afwijking").html(percentage);
        }
    });


    $("#m_leverancierslijst_id").on('change', function(){
        $.post(SITE_URL + 'calculatie/get_leverancierslijst_info/' + $(this).val(), function(resp){
            resp = JSON.parse(resp);
            if(resp.status){
                $("#m_eenheden").val(resp.msg.eenheden);
                $("#m_prijs_per_eenheid").val(resp.msg.prijs_per_eenheid).trigger('change');
            }
        })
    });

    $("#m_benodigde_hoeveelheid, #m_prijs_per_eenheid").on('change', function(){
        const val = parseFloat($("#m_benodigde_hoeveelheid").val() * $("#m_prijs_per_eenheid").val()).toFixed(7);
        $("#m_kostprijs").val(val);
    });


    $("#eenheden_id").on("change", function(){
        $(".eenheden-id").html($("#eenheden_id :selected").text());

        $.post(SITE_URL + "calculatie_recepten/get_eenheden_info/" + $(this).val(), function(resp){
            resp = JSON.parse(resp);
            if(resp.status){
                $("#kleinste_id").val(resp.msg.kleinste_id).trigger('change');
            }
        })
    })


    $("#omzetgroepen_id").on('change', function(){
        $(".omzetgroepen-id").html($("#omzetgroepen_id :selected").text());
    })

    $("#inkoopcategorien_id").on('change', function(){
        $(".inkoopcategorien-id").html($("#inkoopcategorien_id :selected").text());
    })

    $("#kleinste_id").on('change', function(){
        $(".kleinste-id").html($("#kleinste_id :selected").text());
    })

    $("#verlies_procentages").on("change", function(){
        $(".verlies-procentages").html(("#verlies_procentages").val());
    })

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
    $("#margegroepen_id2").trigger("change");

});

var draw_item_table = function(){
    let inkoo_html = "";
    let dispo_html = "";

    let inkoo_total = 0;
    let dispo_total = 0;

    let inkoo_html_for_pdf = "";
    let dispo_html_for_pdf = "";

    let index = 0;
    for(item of list){
        let item_html = "";
        item_html += "<tr>";
        item_html += "<td>" + item.ink_dis_name + "</td>";
        item_html += "<td>" + item.netto_prijs + "</td>";
        item_html += "<td class='text-right'>" + item.eenheid_kleinste + "</td>";
        item_html += "<td class='text-right'>" + item.benodigde_hoeveelheid + "</td>";
        item_html += "<td class='text-right'>€  " + parseFloat(item.kostprijs).toFixed(7) + "</td>";

        let item_for_pdf = item_html; 
        item_for_pdf += "</tr>";

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
            inkoo_html_for_pdf += item_for_pdf;
            inkoo_total += parseFloat(item.kostprijs);
        }else if(item.type == '2'){
            dispo_html += item_html;
            dispo_html_for_pdf += item_for_pdf;
            dispo_total += parseFloat(item.kostprijs);
        }
        index++;
    }

    $("#inkoopartikelen_table_content").html(inkoo_html);
    $("#disposable_table_content").html(dispo_html);

    $(".inkoopartikelen-table-content").html(inkoo_html_for_pdf);
    $(".disposable-table-content").html(dispo_html_for_pdf);

    $(".sub-total1-for-print").html("€  " + parseFloat(inkoo_total).toFixed(7));
    $("#sub_total1").val(parseFloat(inkoo_total).toFixed(7));
    $("#sub_total1").trigger("change");

    $(".sub-total2-for-print").html("€  " + parseFloat(dispo_total).toFixed(7));
    $("#sub_total2").val(parseFloat(dispo_total).toFixed(7));
    $("#sub_total2").trigger("change");



    $(".totaal-for-print").html("€  " + parseFloat(inkoo_total + dispo_total).toFixed(7));
    $("#totaal").html(parseFloat(inkoo_total + dispo_total).toFixed(7));
    // $("#totaal").trigger("change");
}



var addModal = function(type){
    $(".title-type").html("SELECTEER");
    $("#m_action_type").val('add');
    $("#m_type").val(type);
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
    $("#m_benodigde_hoeveelheid").val(list[index].benodigde_hoeveelheid);
    $("#add_modal").modal();
}

var delete_item = function(index){
    list.splice(index, 1);
    draw_item_table();
}

var save_item = function(){
    if($("#m_action_type").val() == 'add'){
        var item = {
            ticket_id: $("#ticket_id").val(),
            leverancierslijst_id: $("#m_leverancierslijst_id").val(),
            netto_prijs: $("#m_eenheden").val(),
            eenheid_kleinste: $("#m_prijs_per_eenheid").val(),
            benodigde_hoeveelheid: $("#m_benodigde_hoeveelheid").val(),
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
        list[index].benodigde_hoeveelheid = $("#m_benodigde_hoeveelheid").val();
        list[index].kostprijs = $("#m_kostprijs").val();
        list[index].type = $("#m_type").val();
        list[index].ink_dis_name = $("#m_leverancierslijst_id :selected").text();
    }

    draw_item_table();
    $('#add_modal').modal('toggle');
}

var save_ticket = function(){
    $.post(SITE_URL + 'calculatie/save_ticket',
        {
            id: $("#ticket_id").val(),
            name: $("#name").val(),
            sub_total1: $("#sub_total1").val(),
            margegroepen_id1: $("#margegroepen_id1").val(),
            sub_total2: $("#sub_total2").val(),
            total: $("#totaal").html(),
            margegroepen_id2: $("#margegroepen_id2").val(),
            bezorging_id: $("#bezorging_id").val(),
            btw_id: $("#btw_id").val(),
            advies_verkoopprijs: $("#advies_verkoopprijs").val(),
            handmatige_verkoopprijs: $("#handmatige_verkoopprijs").val(),
            verschillen_tussen: $("#verschillen_tussen").val(),
            procentuele_afwijking: $("#procentuele_afwijking").val(),
            omzetgroepen_id: $("#omzetgroepen_id").val(),
            inkoopcategorien_id: $("#inkoopcategorien_id").val(),
            eenheden_id: $("#eenheden_id").val(),
            kleinste_id: $("#kleinste_id").val(),
            verlies_procentages: $("#verlies_procentages").val(),
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
                    // location.href = SITE_URL + 'cv';
                    // location.reload();
                    location.href = SITE_URL + 'calculatie';
                // });
            }
        });
}

var reset_ticket = function(){
    location.href = SITE_URL + 'calculatie';
}


var save_pdf = function(){
    return xepOnline.Formatter.Format('print_pdf');
    // const element = document.getElementById('form_content');
    // // Choose the element and save the PDF for our user.
    // html2pdf()
    //     .set({ html2canvas: { scale: 1 } })
    //     .from(element)
    //     .save();
}
