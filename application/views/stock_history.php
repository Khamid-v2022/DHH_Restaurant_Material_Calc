<script type="text/javascript" src="<?=base_url()?>assets/js/stock_history.js"></script>

<style type="text/css">
	
</style>
<!-- Content area -->
<div class="content">
	<!-- Main charts -->
	<div class="row">
		<div class="col-lg-12">
			<!-- Traffic sources -->
			<div class="panel panel-flat">
				<div class="panel-heading">
					<h5 class="panel-title"><?=$product_info['geef_productnaam']?></h5>
					<input type="hidden" id="sel_id" value="<?=$sel_id?>">
					<div class="heading-elements">
						<button class="btn btn-sm btn-primary" type="button" onclick="add_stock_modal()"><i class="icon-plus2 position-left"></i>Toevoegen</button>
                	</div>
				</div>
				
				<table class="table datatable-ajax" id="main_table" data-page-length='25'>
					<thead>
						<tr>
							<th>Action</th>
							<th>Register Date</th>
							<th>Inhoud verpakking</th>
							<th>Prijs omdoos</th>
							<th>Statiegeld omdoos</th>
							<th>Statiegeld eenheid</th>
							<th>Aantal omdozen</th>
							<th>Lege omdozen</th>
							<th>Omdoos statiegeld totaal</th>
							<th>Statiegeld los</th>
							<th>Statiegeld eenheid</th>
							<th>Losse geteld</th>
							<th>Lege geteld</th>
							<th>Statiegeld losse flessen</th>
							<th>Prijs kleinste eenheid</th>
							<th>Totaal Statiegeld</th>
							<th>Waarde voorraad<br/>(Excl statiegeld)</th>
							<th>Waarde voorraad<br/>(Incl statiegeld)</th>
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


	<div id="voorraadtelling_stock_modal" class="modal fade">
		<div class="modal-dialog modal-lg">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h5 class="modal-title"><span>Voorraadtelling</span></h5>
				</div>

				<input id="m_s_sel_id" type="hidden">
				
				<form action="#" class="" id="m_voorraadtelling_stock_form">
					<div class="modal-body">
						<div class="row">
							<div class="col-sm-6">
								<div class="form-group">
									<label>Inhoud verpakking</label>
									<input type="text" placeholder="Inhoud verpakking" class="form-control" id="m_inhoud_verpakking" readonly>
								</div>	
							</div>
							<div class="col-sm-6">
								<div class="form-group">
									<label>Prijs omdoos</label>
									<input type="text" placeholder="Inhoud verpakking" class="form-control" id="m_prijs_omdoos" readonly>
								</div>	
							</div>
						</div>
						<div class="row">
							<div class="col-sm-6">
								<div class="form-group">
									<label>Statiegeld omdoos</label>
									<select class="select-search" id="m_statiegeld_omdoos">
										<option value="0"></option>
										<?php 
										foreach($statiegelds as $item){
											echo '<option value="' . $item['id'] . '">' . $item['statiegeld'] . '</option>';
										}
										?>
									</select>
								</div>
							</div>
							<div class="col-sm-6">
								<div class="form-group">
									<label>Statiegeld eenheid</label>
									<input type="number" placeholder="Statiegeld eenheid" class="form-control" id="m_statiegeld_eenheid" step="any" readonly>
								</div>	
							</div>
						</div>

						<div class="row">
							<div class="col-sm-6">
								<div class="form-group">
									<label>Aantal omdozen</label>
									<input type="number" placeholder="Aantal omdozen" class="form-control" id="m_aantal_omdozen" min="0">
								</div>	
							</div>
							<div class="col-sm-6">
								<div class="form-group">
									<label>Lege omdozen</label>
									<input type="number" placeholder="Lege omdozen" class="form-control" id="m_lege_omdozen">
								</div>	
							</div>
						</div>
						<div class="form-group">
							<label>Omdoos statiegeld totaal</label>
							<div class="input-group">
								<span class="input-group-addon">€</span>
								<input type="number" placeholder="Omdoos statiegeld totaal" class="form-control" id="m_omdoos_statie_totaal" step="any" readonly>
							</div>							
						</div>	

						<div class="divider"></div>

						<div class="row">
							<div class="col-sm-6">
								<div class="form-group">
									<label>Statiegeld los</label>
									<select class="select-search" id="m_statiegeld_los">
										<option value="0"></option>
										<?php 
										foreach($statiegelds as $item){
											echo '<option value="' . $item['id'] . '">' . $item['statiegeld'] . '</option>';
										}
										?>
									</select>
								</div>
							</div>
							<div class="col-sm-6">
								<div class="form-group">
									<label>Statiegeld eenheid</label>
									<input type="number" placeholder="Statiegeld eenheid" class="form-control" id="m_statiegeld_eenheid2" step="any" readonly>
								</div>	
							</div>
						</div>

						<div class="row">
							<div class="col-sm-6">
								<div class="form-group">
									<label>Losse geteld</label>
									<input type="number" placeholder="Losse geteld" class="form-control" id="m_losse_geteld" required min="0">
								</div>	
							</div>
							<div class="col-sm-6">
								<div class="form-group">
									<label>Lege geteld</label>
									<input type="number" placeholder="Lege geteld" class="form-control" id="m_lege_geteld" required min="0">
								</div>	
							</div>
						</div>

						<div class="row">
							<div class="col-sm-6">
								<div class="form-group">
									<label>Statiegeld losse flessen</label>

									<div class="input-group">
										<span class="input-group-addon">€</span>
										<input type="number" placeholder="Statiegeld losse flessen" class="form-control" id="m_statie_losse_flessen" readonly>
									</div>
									
								</div>	
							</div>
							<div class="col-sm-6">
								<div class="form-group">
									<label>Prijs kleinste eenheid</label>
									<input type="number" placeholder="Lege geteld" class="form-control" id="m_prijs_kleinste_eenheid" required readonly>
								</div>	
							</div>
						</div>

						<div class="divider"></div>

						<div class="form-group">
							<label>Totaal Statiegeld</label>
							<div class="input-group">
								<span class="input-group-addon">€</span>
								<input type="number" placeholder="Totaal Statiegeld" class="form-control" id="m_total_statiegeld" readonly>
							</div>
						</div>	
						<div class="form-group">
							<label>Waarde voorraad (Excl statiegeld)</label>
							<div class="input-group">
								<span class="input-group-addon">€</span>
								<input type="number" placeholder="Waarde voorraad" class="form-control" id="m_waarde_voorraad1" readonly>
							</div>
						</div>
						<div class="form-group">
							<label>Waarde voorraad (Incl statiegeld)</label>
							<div class="input-group">
								<span class="input-group-addon">€</span>
								<input type="number" placeholder="Waarde voorraad" class="form-control" id="m_waarde_voorraad2" readonly>
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
