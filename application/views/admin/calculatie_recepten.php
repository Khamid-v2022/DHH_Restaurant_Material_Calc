<script src="<?=base_url()?>assets/plugin/js/css-to-pdf-master/xepOnline.jqPlugin.js"></script>
<script type="text/javascript" src="<?=base_url()?>assets/js/admin/calculatie_recepten.js"></script>

<style type="text/css">
	table .btn.btn-rounded {
		margin-left: 10px;
	}
	
	.table-wrap {
		overflow-x: auto;
	}

	legend .control-arrow {
		color: #fff;
	}

	.modal-dialog {
	    margin: 200px auto;
	}

	.col-main {
		width: 60%;
	}

	.col-value {
		width:  40%;
	}

	#print_pdf_wrapper {
		position: absolute;
		top: -100000px;
	}

	#print_pdf {
		font-size: 11px;
		font-weight: 300!important;
		max-width: 250mm!important;
	}
</style>

<div class="page-container">
	<div class="page-content">
		<div class="content-wrapper">
			<div class="content">
				<div class="row">
					<div class="col-lg-12">
						<input type="hidden" id="ticket_id" value="<?=empty($ticket) ? 0 : $ticket['id'] ?>">
						<input type="hidden" id="mode" value="<?=$mode ?>">
						<div class="panel panel-flat">

							<div class="panel-body">
								<div class="text-right mb-10">
									<button class="btn btn-success" type="button" onclick="save_pdf()"><i class="icon-printer position-left"></i>PDF</button>
								</div>
								<form action="#" id="document_form">
									<div class="row">
										<div id="inkoopartikelen_array" style="display: none;"><?=!empty($lists)?json_encode($lists):''?></div>
										<div class="table-wrap col-md-6 col-sm-8">
											<table class="table table-xs table-bordered table-striped mt-10">
												<thead>
													<tr class="info">
														<th class="text-center" style="width: 45%">Naam Recept</th>
														<th class="text-center" style="width: 25px">Recept ID</th>
														<th class="text-center" style="width: 30%">Inkoopcategorie</th>
													</tr>
												</thead>
												<tbody>	
													<tr>
														<td>
															<input type="text" class="form-control" id="recept_naam" value="<?=empty($ticket)?'':$ticket['recept_naam']?>" required>
														</td>
														<td>
															<input type="number" class="form-control" id="recept_id" value="<?=empty($ticket)?$recept_id:$ticket['recept_id']?>" readonly>
														</td>
														<td>
															<select class="select-search" id="inkoopcategorie_id" value="<?=empty($ticket)?0:$ticket['inkoopcategorie_id']?>">
																<option value="0"></option>
																<?php
																foreach($basic_inkoopcategoriens as $item){
																	
																	echo '<option value="' . $item['id'] . '" ';
																	if(!empty($ticket) && $ticket['inkoopcategorie_id'] == $item['id']){
																		echo 'selected';
																	}
																	echo '>' . $item['name'] . '</option>';
																}
																?>
															</select>
														</td>
													</tr>
												</tbody>
											</table>
										</div>
									</div>

									<fieldset>
										<legend class="text-semibold">
											<b>Inkoopartikelen</b>
											<button type="button" class="control-arrow btn btn-primary" onclick="addModal(1)"><i class="icon-plus2 mr-5" ></i>SELECTEER</button>
										</legend>
										<div class="table-wrap">
											<table class="table table-xs table-bordered table-striped mt-10">
												<thead>
													<tr class="info">
														<th class="text-center">Inkoopartikelen</th>
														<th class="text-center">Netto prijs kleinste hoeveelheid</th>
														<th class="text-center">Eenheid kleinste hoeveelheid</th>
														<th class="text-center">Benodigde hoeveelheid</th>
														<th class="text-center">Kostprijs inkoopartikelen</th>
														<th class="text-center">Action</th>
													</tr>
												</thead>
												<tbody id="inkoopartikelen_table_content">
												</tbody>
												
												<tfoot>
													<tr class="success">
														<td colspan="4" class="text-center">Kostprijs inkoopartikelen totaal</td>
														<td class="text-right" ><span id="sub_total1_for_print"></span><span style="display:none" id="sub_total1"></span></td>
													</tr>
												</tfoot>
											</table>
										</div>
										
									</fieldset>

									<fieldset class="mt-10">
										<legend class="text-semibold">
											<b>Disposables</b>
											<button type="button" class="control-arrow btn btn-info" onclick="addModal(2)"><i class="icon-plus2 mr-5" ></i>SELECTEER</button>	
										</legend>									
										
										
										<div class="table-wrap">
											<table class="table table-xs table-bordered table-striped mt-10">
												<thead>
													<tr class="info">
														<th class="text-center">Disposables</th>
														<th class="text-center">Netto prijs kleinste hoeveelheid</th>
														<th class="text-center">Eenheid kleinste hoeveelheid</th>
														<th class="text-center">Benodigde hoeveelheid</th>
														<th class="text-center">Kostprijs inkoopartikelen</th>
														<th class="text-center">Action</th>
													</tr>
												</thead>
												<tbody id="disposable_table_content">
												</tbody>
												
												<tfoot>
													<tr class="success">
														<td colspan="4" class="text-center">Kostprijs Disposables Totaal</td>
														<td class="text-right"><span id="sub_total2_for_print"></span><span style="display:none" id="sub_total2"></span></td>
													</tr>
												</tfoot>
											</table>
										</div>
										<div class="row">
											<div class="table-wrap col-md-4 col-sm-6 col-md-offset-8 col-sm-offset-6">
												<table class="table table-bordered mt-10 mb-20">
													<tr class="danger">
														<th class="text-center col-main"><b>Totaal ex. BTW</b></th>
														<th class="text-right col-value"><b><span id="totaal_for_print"></span></b><span id="totaal" style="display: none;"></span></th>
													</tr>
												</table>
											</div>
										</div>
										<div class="row">
											<div class="table-wrap col-md-4 col-sm-6 col-md-offset-8 col-sm-offset-6">
												<table class="table table-xs table-bordered table-striped mt-10">
													<thead>
														<tr>
															<th class="text-center info col-main">Totaal gewicht receptuur</th>
															<th class="text-center success col-value">Eenheid</th>
														</tr>
													</thead>
													<tbody>	
														<tr>
															<td><input type="number" class="form-control" step="any"  value="<?=empty($ticket)?'':$ticket['total_gewicht']?>" id="total_gewicht"></td>
															<td>
																<select class="select-search" id="eenheden_id" value="<?=empty($ticket)?0:$ticket['eenheden_id']?>">
																	<option value="0"></option>
																	<?php
																	foreach($basic_eenhedens as $item){
																		
																		echo '<option value="' . $item['id'] . '" ';
																		if(!empty($ticket) && $ticket['eenheden_id'] == $item['id']){
																			echo 'selected';
																		}
																		echo '>' . $item['name'] . '</option>';
																	}
																	?>
																</select>
															</td>
														</tr>
													</tbody>
												</table>
											</div>
										</div>
									</fieldset>
									<fieldset class="mt-10">
										<div class="table-wrap">
											<table class="table table-xs table-bordered table-striped mt-10">
												<thead>
													<tr class="info">
														<th class="text-center">Omzetgroep</th>
														<th class="text-center">Inkoopcategorie</th>
														<th class="text-center">Eenheid verpakking kg/liter/stuks</th>
														<th class="text-center">Eenheid kleinste hoeveelheid</th>
														<th class="text-center">Verlies in percentage</th>
													</tr>
												</thead>
												<tbody>	
													<tr>
														<td>
															<select class="select-search" id="omzetgroepen_id" value="<?=empty($ticket)?0:$ticket['omzetgroepen_id']?>">
																<option value="0"></option>
																<?php
																foreach($basic_omzetgroepens as $item){
																	
																	echo '<option value="' . $item['id'] . '" ';
																	if(!empty($ticket) && $ticket['omzetgroepen_id'] == $item['id']){
																		echo 'selected';
																	}
																	echo '>' . $item['name'] . '</option>';
																}
																?>
															</select>
														</td>
														<td>
															<select class="select-search inkoopcategorie-id" value="<?=empty($ticket)?0:$ticket['inkoopcategorie_id']?>" readonly>
																<option value="0"></option>
																<?php
																foreach($basic_inkoopcategoriens as $item){
																	
																	echo '<option value="' . $item['id'] . '" ';
																	if(!empty($ticket) && $ticket['inkoopcategorie_id'] == $item['id']){
																		echo 'selected';
																	}
																	echo '>' . $item['name'] . '</option>';
																}
																?>
															</select>
														</td>
														<td>
															<select class="select-search eenheden-id" value="<?=empty($ticket)?0:$ticket['eenheden_id']?>" readonly>
																<option value="0"></option>
																<?php
																foreach($basic_eenhedens as $item){
																	
																	echo '<option value="' . $item['id'] . '" ';
																	if(!empty($ticket) && $ticket['eenheden_id'] == $item['id']){
																		echo 'selected';
																	}
																	echo '>' . $item['name'] . '</option>';
																}
																?>
															</select>
														</td>
														<td>
															<select class="select-search" id="kleinste_id" value="<?=empty($ticket)?0:$ticket['kleinste_id']?>" readonly>
																<option value="0"></option>
																<?php
																foreach($basic_kleinstes as $item){
																	
																	echo '<option value="' . $item['id'] . '" ';
																	if(!empty($ticket) && $ticket['kleinste_id'] == $item['id']){
																		echo 'selected';
																	}
																	echo '>' . $item['name'] . '</option>';
																}
																?>
															</select>
														</td>
														<td>
															<input type="number" class="form-control" id="verlies_procentage" placeholder="%" value="<?=empty($ticket)?'':$ticket['verlies_procentage']?>">
														</td>
													</tr>
												</tbody>
											</table>
										</div>
										<div class="row">
											<div class="table-wrap col-md-8">
												<table class="table table-xs table-bordered table-striped mt-10">
													<thead>
														<tr class="info">
															<th class="text-center">Aantal verpakkingen in omdoos</th>
															<th class="text-center">Eenheid van verpakking</th>
															<th class="text-center">Bar / Keuken / Kantoor</th>
															<!-- <th class="text-center">BTW</th>
															<th class="text-center">BTW Factor</th> -->
														</tr>
													</thead>
													<tbody>	
														<tr>
															<td>
																<input type="number" class="form-control" id="aantal_verpakkingen" value="<?=empty($ticket)?'':$ticket['aantal_verpakkingen']?>">
															</td>
															<td>
																<select class="select-search" value="<?=empty($ticket)?0:$ticket['eenheid_id']?>" id="eenheid_id">
																	<option value="0"></option>
																	<?php
																	foreach($basic_eenheids as $item){
																		
																		echo '<option value="' . $item['id'] . '" ';
																		if(!empty($ticket) && $ticket['eenheid_id'] == $item['id']){
																			echo 'selected';
																		}
																		echo '>' . $item['name'] . '</option>';
																	}
																	?>
																</select>
															</td>
															<td>
																<select class="select-search" value="<?=empty($ticket)?0:$ticket['locatie_id']?>" id="locatie_id">
																	<option value="0"></option>
																	<?php
																	foreach($basic_locaties as $item){
																		
																		echo '<option value="' . $item['id'] . '" ';
																		if(!empty($ticket) && $ticket['locatie_id'] == $item['id']){
																			echo 'selected';
																		}
																		echo '>' . $item['name'] . '</option>';
																	}
																	?>
																</select>
															</td>
															<!-- <td>
																<select class="select-search" id="btw_id" value="<?=empty($ticket)?0:$ticket['btw_id']?>">
																	<option value="0"></option>
																	<?php
																	foreach($basic_btws as $item){
																		
																		echo '<option value="' . $item['id'] . '" ';
																		if(!empty($ticket) && $ticket['btw_id'] == $item['id']){
																			echo 'selected';
																		}
																		echo '>' . $item['btw'] . '%</option>';
																	}
																	?>
																</select>
															</td>
															<td>
																<input type="number" step="any" class="form-control" id="btw_factor" readonly>
															</td> -->
														</tr>
													</tbody>
												</table>
											</div>
										</div>
									</fieldset>
									<div class="mt-20">
										<?php 
										if(!empty($ticket)){
										?>
											<button type="button" class="btn bg-success-800" onclick="reset_ticket()"><i class="icon-reset position-left"></i>RESET</button>
										<?php }?>
										<button type="submit" class="btn btn-danger" style="float:right"><i class="icon-file-upload position-left"></i>Sla verkoopproduct calculatie op in database</button>
										
									</div>
								</form>
							</div>
						</div>



 						<div id="print_pdf_wrapper">	
							<div id="print_pdf" style="">
								<style type="text/css">
									#print_pdf table {
										border-collapse: collapse;
										/*max-width: 270mm!important;*/
										width: 100%;
									}
									#print_pdf table {
										background-color: #eee;
									}
									#print_pdf table th, td {
										border: 1px solid gray;
										word-break: break-word;
										padding:  3px 6px;
										font-weight: 300!important;
									}

									#print_pdf table td {
										background-color: white;
									}
								</style>
								<div class="row">
									<div class="col-sm-8">
										<table>
											<thead>
												<tr class="info">
													<th class="text-center" >Naam Recept</th>
													<th class="text-center">Recept ID</th>
													<th class="text-center">Inkoopcategorie</th>
												</tr>
											</thead>
											<tbody>	
												<tr>
													<td class="recept-naam"><?=empty($ticket)?'':$ticket['recept_naam']?>
													</td>
													<td><?=empty($ticket)?$recept_id:$ticket['recept_id']?>
													</td>
													<td class="inkoopcategorie-id-pdf">
														<?php
														foreach($basic_inkoopcategoriens as $item){
															
															if(!empty($ticket) && $ticket['inkoopcategorie_id'] == $item['id']){
																echo $item['name'];
																break;
															}
														}
														?>
													</td>
												</tr>
											</tbody>
										</table>
									</div>
								</div>

								<div class="row">
									<h3>Inkoopartikelen</h3>
										<div class="col-sm-12">
										<table>
											<thead>
												<tr>
													<th>Inkoopartikelen</th>
													<th>Netto prijs kleinste hoeveelheid</th>
													<th>Eenheid kleinste hoeveelheid</th>
													<th>Benodigde hoeveelheid</th>
													<th>Kostprijs inkoopartikelen</th>
												</tr>
											</thead>
											<tbody class="inkoopartikelen-table-content">

											</tbody>
											<tfoot>
												<tr class="success">
													<td colspan="4" class="text-center">Kostprijs inkoopartikelen totaal</td>
													<td class="text-right"><span class="sub-total1-for-print"></span></td>
												</tr>
											</tfoot>
										</table>
									</div>
								</div>
								
								<div class="row">
									<h3>Disposables</h3>
									<div class="col-sm-12">
										<table>
											<thead>
												<tr>
													<th>Disposables</th>
													<th>Netto prijs kleinste hoeveelheid</th>
													<th>Eenheid kleinste hoeveelheid</th>
													<th>Benodigde hoeveelheid</th>
													<th>Kostprijs inkoopartikelen</th>
												</tr>
											</thead>
											<tbody class="disposable-table-content">

											</tbody>
											<tfoot>
												<tr>
													<td colspan="4" class="text-center">Kostprijs inkoopartikelen totaal</td>
													<td class="text-right"><span class="sub-total2-for-print"></span></td>
												</tr>
											</tfoot>
										</table>
									</div>
								</div>


								<div class="row">
									<div class="table-wrap col-sm-6 col-sm-offset-6">
										<table class="mb-20">
											<tr>
												<th class="text-center col-main"><b>Totaal ex. BTW</b></th>
												<th class="text-right col-value"><b><span class="totaal-for-print"></span></b></th>
											</tr>
										</table>
									</div>
								</div>

								<div class="row">
									<div class="table-wrap col-sm-6 col-sm-offset-6">
										<table class="mb-20">
											<thead>
												<tr>
													<th>Totaal gewicht receptuur</th>
													<th>Eenheid</th>
												</tr>
											</thead>
											<tbody>
												<tr>
													<td class="text-right total-gewicht"><?=empty($ticket)?'':$ticket['total_gewicht']?></td>
													<td class="eenheden-id-pdf">
														<?php
														foreach($basic_eenhedens as $item){
															if(!empty($ticket) && $ticket['eenheden_id'] == $item['id']){
																echo $item['name'];
																break;
															}
														}
														?>
													</td>
													
												</tr>
											</tbody>
										</table>
									</div>
								</div>

								<div class="row">
									<div class="col-sm-12">
										<table class="mb-20">
											<thead>
												<tr class="info">
													<th class="text-center">Omzetgroep</th>
													<th class="text-center">Inkoopcategorie</th>
													<th class="text-center">Eenheid verpakking kg/liter/stuks</th>
													<th class="text-center">Eenheid kleinste hoeveelheid</th>
													<th class="text-center">Verlies in procentages</th>
												</tr>
											</thead>
											<tbody>	
												<tr>
													<td class="omzetgroepen-id">
														<?php
														foreach($basic_omzetgroepens as $item){
															if(!empty($ticket) && $ticket['omzetgroepen_id'] == $item['id']){
																echo $item['name'];
																break;
															}
														}
														?>
													</td>
													<td class="inkoopcategorie-id-pdf">
														<?php
															foreach($basic_inkoopcategoriens as $item){
																if(!empty($ticket) && $ticket['inkoopcategorie_id'] == $item['id']){
																	echo $item['name'];
																	break;
																}
															}
														?>
													</td>
													<td class="eenheden-id-pdf">
														<?php
															foreach($basic_eenhedens as $item){
																if(!empty($ticket) && $ticket['eenheden_id'] == $item['id']){
																	echo $item['name'];
																	break;
																}
															}
														?>
													</td>
													<td class="kleinste-id">
														<?php
															foreach($basic_kleinstes as $item){
																if(!empty($ticket) && $ticket['kleinste_id'] == $item['id']){
																	echo $item['name'];
																	break;
																}
															}
														?>
													</td>
													<td class="verlies-procentages text-right">
														<?=empty($ticket)?'':$ticket['verlies_procentage']?>
													</td>
												</tr>
											</tbody>
										</table>
									</div>
								</div>


								<div class="row">

									<div class="col-sm-8">
										<table >
											<thead>
												<tr class="info">
													<th class="text-center">Aantal verpakkingen in omdoos</th>
													<th class="text-center">Eenheid van verpakking</th>
													<th class="text-center">Bar / Keuken / Kantoor</th>
												</tr>
											</thead>
											<tbody>	
												<tr>
													<td class="aantal-verpakkingen">
														<?=empty($ticket)?'':$ticket['aantal_verpakkingen']?>
													</td>
													<td class="eenheid-id">
														<?php
														foreach($basic_eenheids as $item){
															if(!empty($ticket) && $ticket['eenheid_id'] == $item['id']){
																echo $item['name'];
																break;
															}
														}
														?>
													</td>
													<td class="locatie-id">
														<?php
														foreach($basic_locaties as $item){
															if(!empty($ticket) && $ticket['locatie_id'] == $item['id']){
																echo $item['name'];
															}
														}
														?>
													</td>
												</tr>
											</tbody>
										</table>
									</div>
								</div>



							</div>
						</div>


						
					</div>
				</div>	
			</div>
		</div>
	</div>
</div>

<div id="add_modal" class="modal fade">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h5 class="modal-title"><span class="title-type">SELECTEER</span> <span class="title-name"></span></h5>
			</div>
			<form action="#" class="form-horizontal" id="modal_form">
				<input type="hidden" value="0" id="m_type">
				<input type="hidden" value="add" id="m_action_type">
				<input type="hidden" value="0" id="m_sel_id">
				<div class="modal-body">
					<div class="form-group">
						<label class="control-label col-sm-3"><span class="title-name"></span>: </label>
						<div class="col-sm-9">
							<select class="select-search" id="m_leverancierslijst_id">
								<?php 
								foreach($leverancierslijsts as $item){
									echo '<option value="' . $item['id'] . '">' . $item['geef_productnaam'] . '</option>';
								}
								?>
							</select>
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-sm-3">Netto prijs kleinste hoeveelheid: </label>
						<div class="col-sm-9">
							<input type="text" class="form-control" readonly id="m_eenheden">
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-sm-3">Eenheid kleinste hoeveelheid: </label>
						<div class="col-sm-9">
							<input type="text" class="form-control" readonly id="m_prijs_per_eenheid">
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-sm-3">Benodigde hoeveelheid: </label>
						<div class="col-sm-9">
							<input type="number" class="form-control" min="0" id="m_benodigde_hoeveelheid" required>
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-sm-3">Kostprijs inkoopartikelen: </label>
						<div class="col-sm-9">
							<input type="number" class="form-control" readonly step="any" id="m_kostprijs">
						</div>
					</div>
				</div>

				<div class="modal-footer">
					<button type="button" class="btn btn-link" data-dismiss="modal">Close</button>
					<button type="submit" class="btn btn-success">TOEVOEGEN</button>
				</div>
			</form>
		</div>
	</div>
</div>