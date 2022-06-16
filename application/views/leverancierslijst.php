<script type="text/javascript" src="<?=base_url()?>assets/js/leverancierslijst.js"></script>

<style type="text/css">
	.datatable-scroll {
	    width: 100%;
	}
	
	.large-table-fake-top-scroll {
	  overflow-x: scroll;
	  overflow-y: auto;
	}

	.large-table-fake-top-scroll div {
		font-size:1px;
		line-height:1px;
	}

	.custom-sweet button.cancel {
		background-color: #F4511E;
		color: white;
	}

</style>
<!-- Content area -->
<div class="content">
	<!-- Main charts -->
	<div class="row">
		<div class="col-lg-12">
			<!-- Traffic sources -->
			<div class="panel panel-flat">
				<div class="panel-heading">
					<h5 class="panel-title">Benodigde informatie voor voorraad telling</h5>
					<input type="hidden" class="table-name" value="eenheden">
					<div class="heading-elements">
						<button class="btn btn-sm btn-primary" type="button" onclick="add_info_modal()"><i class="icon-plus2 position-left"></i>Toevoegen</button>
						<button class="btn btn-sm btn-success" type="button" data-toggle="modal" data-target="#modal_download"><i class=" icon-file-excel position-left"></i>Download to XLS</button>
						<!-- <button class="btn btn-sm btn-error" type="button" onclick="upload_data_from_excel()"><i class=" icon-file-excel position-left"></i>Upload to XLS</button> -->
                	</div>
				</div>

				<div class="large-table-fake-top-scroll">
				  	<div>&nbsp;</div>
				</div>
				
				<table class="table datatable-ajax" id="data_table" data-page-length='25'>
					<thead>
						<tr>
							<th>Actie</th>
							<th>Productnaam</th>
							<th>Leveranciersnaam</th>
							<th>Bar/Keuken/Kantoor</th>
							<th>Inkoopcategorie</th>
							<th>Artikelnummer leverancier</th>
							<th>Prijs van omdoos</th>
							<th>Aantal verpakkingen in omdoos</th>
							<th>Eenheid van verpakking</th>
							<th>Statiegeld groep</th>
							<th>Inhoud van verpakking</th>
							<th>Eenheid KG/L/Stuks</th>
							<th>Prijs per eenheid in verpakking</th>
							<th>Kleinste eenheid</th>
							<th>Netto Stuks prijs</th>
							<th>Verpakking</th>
							<th>Statiegeld</th>
						</tr>
					</thead>
					<tbody id="table_content">	
					</tbody>
				</table>
			</div>
			<!-- /traffic sources -->
		</div>
	</div>
	<!-- /main charts -->

	<div id="info_modal" class="modal fade">
		<div class="modal-dialog modal-lg">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h5 class="modal-title" id="modal_title">Benodigde informatie voor voorraad telling <span class="action-type"> Toevoegen</span></h5>
				</div>
				<input type="hidden" value="" id="m_action_type">
				<input type="hidden" value="" id="m_sel_id">
				<form action="#" class="form-horizontal" id="m_form">
					<div class="modal-body">
						<div class="form-group">
							<div class="row">
								<div class="col-sm-3">
									<label>Productnaam</label>
									<input type="text" placeholder="Productnaam" class="form-control" required id="m_productnaam">
								</div>

								<div class="col-sm-3">
									<label>Leveranciersnaam</label>
									<select class="select-search" id="m_leveranciers_id">
										<?php 
										foreach($leveranciers as $item){
											echo '<option value="' . $item['id'] . '">' . $item['name'] . '</option>';
										}
										?>
									</select>
								</div>
								<div class="col-sm-3">
									<label>Bar/Keuken/Kantoor</label>
									<select class="select-search" id="m_locatie_id">
										<?php 
										foreach($locaties as $item){
											echo '<option value="' . $item['id'] . '">' . $item['name'] . '</option>';
										}
										?>
									</select>
								</div>

								<div class="col-sm-3">
									<label>Inkoopcategorie</label>
									<select class="select-search" id="m_inkoopcategorien_id">
										<?php 
										foreach($inkoopcategoriens as $item){
											echo '<option value="' . $item['id'] . '">' . $item['name'] . '</option>';
										}
										?>
									</select>
								</div>
							</div>
						</div>
						<div class="form-group">
							<div class="row">
								<div class="col-sm-3">
									<label>Artikelnummer leverancier</label>
									<input type="number" placeholder="Artikelnummer leverancier" class="form-control" id="m_artikelnummer">
								</div>

								<div class="col-sm-3">
									<label>Prijs van omdoos</label>
									<input type="number" placeholder="Prijs van omdoos" class="form-control" step="any" id="m_prijs_van" required>
								</div>
														
								<div class="col-sm-3">
									<label>Aantal verpakkingen in omdoos</label>
									<input type="number" placeholder="Aantal verpakkingen in omdoos" class="form-control" id="m_aantal_verpakkingen" required>
								</div>
								<div class="col-sm-3">
									<label>Eenheid van verpakking</label>
									<select class="select-search" id="m_eenheid_id">
										<?php 
										foreach($eenheids as $item){
											echo '<option value="' . $item['id'] . '">' . $item['name'] . '</option>';
										}
										?>
									</select>
									<!-- <span class="help-block">name@domain.com</span> -->
								</div>
							</div>
						</div>
						<div class="form-group">
							<div class="row">
								
								<div class="col-sm-3">
									<label>Inhoud van verpakking</label>
									<input type="number" placeholder="Inhoud van verpakking" class="form-control" step="any" id="m_inhoud_van" required>
								</div>
								<div class="col-sm-3">
									<label>Eenheid KG/L/Stuks</label>
									<select class="select-search" id="m_eenheden_id">
										<?php 
										foreach($eenhedens as $item){
											echo '<option value="' . $item['id'] . '">' . $item['name'] . '</option>';
										}
										?>
									</select>
								</div>
								<div class="col-sm-3">
									<label>Prijs per eenheid in verpakking</label>
									<input type="number" placeholder="Prijs per eenheid in verpakking" class="form-control" step="any" id="m_prijs_per_eenheid" readonly>
								</div>
								<div class="col-sm-3">
									<label>Kleinste eenheid</label>
									<select class="select-search" id="m_kleinste_eenheid_id" readonly>
										<?php 
										foreach($kleinstes as $item){
											echo '<option value="' . $item['id'] . '">' . $item['name'] . '</option>';
										}
										?>
									</select>
								</div>
							</div>
						</div>
						<div class="form-group">
							<div class="row">
								
								<div class="col-sm-3">
									<label>Statiegeld groep</label>
									<input type="number" placeholder="Statiegeld groep" class="form-control" id="m_prijs_per" readonly>
								</div>
								
								<div class="col-sm-3">
									<label>Statiegeld</label>
									<input type="number" placeholder="Statiegeld" class="form-control" step="any" readonly id="m_statiegeld_price">
								</div>
								<div class="col-sm-3">
									<label>Statiegeld verpakking</label>
									<select class="select-search" onchange="change_statiegeld()" id="m_statiegeld_id">
										<?php 
										foreach($statiegelds as $item){
											echo '<option value="' . $item['id'] . '">' . $item['statiegeld'] . '</option>';
										}
										?>
									</select>
								</div>

								<!-- <div class="col-sm-3">
									<label>Netto Stuks prijs</label>
									<input type="number" placeholder="Netto Stuks prijs" class="form-control" step="any" id="m_netto_stuks_prijs" readonly>
								</div>	 -->			
							</div>
						</div>
					</div>

					<div class="modal-footer">
						<button type="button" class="btn btn-link" data-dismiss="modal">Close</button>
						<button type="submit" class="btn btn-primary">TOEVOEGEN</button>
					</div>
				</form>
			</div>
		</div>
	</div>

	<div id="modal_download" class="modal fade" tabindex="-1">
		<div class="modal-dialog">
			<div class="modal-content text-center">
				<div class="modal-header">
					<h5 class="modal-title">Excel Download</h5>
				</div>

				<form action="<?=site_url()?>leverancierslijst/excel" method="post" class="form-inline" id="download_form">
					<div class="modal-body">
						<div class="form-group has-feedback">
							<label>Jaar: </label>
							<input type="number" placeholder="Jaar" class="form-control" name="m_year" min="2010" max="2050" required value="<?=date('Y')?>">
							<div class="form-control-feedback">
								<i class="icon-calendar52 text-muted"></i>
							</div>
						</div>
					</div>

					<div class="modal-footer text-center">
						<button type="submit" class="btn btn-primary">Download <i class="icon-drawer-in"></i></button>
					</div>
				</form>
			</div>
		</div>
	</div>