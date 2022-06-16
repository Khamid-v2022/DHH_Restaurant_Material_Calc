<script type="text/javascript" src="<?=base_url()?>assets/js/functions.js"></script>

<!-- Content area -->
<div class="content">
	<div class="row">
		<div class="col-sm-6">
			<div class="row">
				<div class="col-sm-12">
					<div class="panel panel-flat">
						<div class="panel-heading">
							<h5 class="panel-title">Leveranciernaam</h5>
						</div>
						<div class="panel-body">
							<form class="form-horizontal">
								<div class="form-group">
									<label class="control-label col-sm-4">Leveranciernaam</label>
									<div class="col-sm-5">
										<input type="text" class="form-control" name="" id="leveranciernaam_name" value="<?=isset($leveranciernaam)?$leveranciernaam['name']:''?>">
									</div>
									<div class="col-sm-3">
										<button type='button' class='btn border-info text-info-600 btn-flat btn-icon' onclick='save_leveranciernaam()' title='save'>
											<i class='icon-floppy-disk'></i>
										</button>
									</div>
								</div>
							</form>
						</div>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-sm-12">
					<div class="panel panel-flat">
						<div class="panel-heading">
							<h5 class="panel-title">Eenheden</h5>
							<input type="hidden" class="table-name" value="eenheden">
							<div class="heading-elements">
								<button class="btn btn-sm btn-primary basic-item-add-btn" type="button"><i class="icon-plus2 position-left"></i>Toevoegen</button>
		                	</div>
						</div>
						<table class="table datatable-ajax" id="eenheden_table">
							<thead>
								<tr>
									<th>No</th>
									<th>Name</th>
									<th>Action</th>
								</tr>
							</thead>
							<tbody>	
							</tbody>
						</table>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-sm-12">
					<div class="panel panel-flat">
						<div class="panel-heading">
							<h5 class="panel-title">Leveranciers</h5>
							<input type="hidden" class="table-name" value="leveranciers">
							<div class="heading-elements">
								<button class="btn btn-sm btn-primary basic-item-add-btn" type="button"><i class="icon-plus2 position-left"></i>Toevoegen</button>
		                	</div>
						</div>
						<table class="table datatable-ajax" id="leveranciers_table">
							<thead>
								<tr>
									<th>No</th>
									<th>Name</th>
									<th>Action</th>
								</tr>
							</thead>
							<tbody>	
							</tbody>
						</table>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-sm-12">
					<div class="panel panel-flat">
						<div class="panel-heading">
							<h5 class="panel-title">Margegroepen</h5>
							<div class="heading-elements">
								<button class="btn btn-sm btn-primary" type="button" onclick="add_margegroepen_modal()"><i class="icon-plus2 position-left"></i>Toevoegen</button>
		                	</div>
						</div>
						<table class="table datatable-ajax" id="margegroepen_table">
							<thead>
								<tr>
									<th>No</th>
									<th>margegroepen</th>
									<th>Marge Vermenigvuldigingsfactor</th>
									<th>Action</th>
								</tr>
							</thead>
							<tbody>	
							</tbody>
						</table>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-sm-12">
					<div class="panel panel-flat">
						<div class="panel-heading">
							<h5 class="panel-title">BTW</h5>
							<div class="heading-elements">
								<button class="btn btn-sm btn-primary" type="button" onclick="add_btw_modal()"><i class="icon-plus2 position-left"></i>Toevoegen</button>
		                	</div>
						</div>
						<table class="table datatable-ajax" id="btw_table">
							<thead>
								<tr>
									<th>No</th>
									<th>BTW %</th>
									<th>BTW Factor</th>
									<th>Action</th>
								</tr>
							</thead>
							<tbody>	
							</tbody>
						</table>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-sm-12">
					<div class="panel panel-flat">
						<div class="panel-heading">
							<h5 class="panel-title">Bezorging</h5>
							<div class="heading-elements">
								<button class="btn btn-sm btn-primary" type="button" onclick="add_bezorging_modal()"><i class="icon-plus2 position-left"></i>Toevoegen</button>
		                	</div>
						</div>
						<table class="table datatable-ajax" id="bezorging_table">
							<thead>
								<tr>
									<th>No</th>
									<th>Bezorging</th>
									<th>Bezorgfee</th>
									<th>Action</th>
								</tr>
							</thead>
							<tbody>	
							</tbody>
						</table>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-sm-12">
					<div class="panel panel-flat">
						<div class="panel-heading">
							<h5 class="panel-title">Eenheid verpakkingen</h5>
							<input type="hidden" class="table-name" value="eenheid">
							<div class="heading-elements">
								<button class="btn btn-sm btn-primary basic-item-add-btn" type="button"><i class="icon-plus2 position-left"></i>Toevoegen</button>
		                	</div>
						</div>
						<table class="table datatable-ajax" id="eenheid_table">
							<thead>
								<tr>
									<th>No</th>
									<th>Name</th>
									<th>Action</th>
								</tr>
							</thead>
							<tbody>	
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>

		<div class="col-sm-6">
			<div class="row">
				<div class="col-sm-12">
					<div class="panel panel-flat">
						<div class="panel-heading">
							<h5 class="panel-title">Kleinste eenheden</h5>
							<input type="hidden" class="table-name" value="kleinste">
							<div class="heading-elements">
								<button class="btn btn-sm btn-primary basic-item-add-btn" type="button"><i class="icon-plus2 position-left"></i>Toevoegen</button>
		                	</div>
						</div>
						<table class="table datatable-ajax" id="kleinste_table">
							<thead>
								<tr>
									<th>No</th>
									<th>Name</th>
									<th>Action</th>
								</tr>
							</thead>
							<tbody>	
							</tbody>
						</table>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-sm-12">
					<div class="panel panel-flat">
						<div class="panel-heading">
							<h5 class="panel-title">Inkoopcategorien</h5>
							<input type="hidden" class="table-name" value="inkoopcategorien">
							<div class="heading-elements">
								<button class="btn btn-sm btn-primary basic-item-add-btn" type="button"><i class="icon-plus2 position-left"></i>Toevoegen</button>
		                	</div>
						</div>
						<table class="table datatable-ajax" id="inkoopcategorien_table">
							<thead>
								<tr>
									<th>No</th>
									<th>Name</th>
									<th>Action</th>
								</tr>
							</thead>
							<tbody>	
							</tbody>
						</table>
					</div>
				</div>
			</div>

			<div class="row">
				<div class="col-sm-12">
					<div class="panel panel-flat">
						<div class="panel-heading">
							<h5 class="panel-title">Verkoopgroepen</h5>
							<input type="hidden" class="table-name" value="verkoopgroepen">
							<div class="heading-elements">
								<button class="btn btn-sm btn-primary basic-item-add-btn" type="button"><i class="icon-plus2 position-left"></i>Toevoegen</button>
		                	</div>
						</div>
						<table class="table datatable-ajax" id="verkoopgroepen_table">
							<thead>
								<tr>
									<th>No</th>
									<th>Name</th>
									<th>Action</th>
								</tr>
							</thead>
							<tbody>	
							</tbody>
						</table>
					</div>
				</div>
			</div>

			<div class="row">
				<div class="col-sm-12">
					<div class="panel panel-flat">
						<div class="panel-heading">
							<h5 class="panel-title">Omzetgroepen</h5>
							<input type="hidden" class="table-name" value="omzetgroepen">
							<div class="heading-elements">
								<button class="btn btn-sm btn-primary basic-item-add-btn" type="button"><i class="icon-plus2 position-left"></i>Toevoegen</button>
		                	</div>
						</div>
						<table class="table datatable-ajax" id="omzetgroepen_table">
							<thead>
								<tr>
									<th>No</th>
									<th>Name</th>
									<th>Action</th>
								</tr>
							</thead>
							<tbody>	
							</tbody>
						</table>
					</div>
				</div>
			</div>

			<div class="row">
				<div class="col-sm-12">
					<div class="panel panel-flat">
						<div class="panel-heading">
							<h5 class="panel-title">Statiegeld</h5>
							<div class="heading-elements">
								<button class="btn btn-sm btn-primary" type="button" onclick="add_statiegeld_modal()"><i class="icon-plus2 position-left"></i>Toevoegen</button>
		                	</div>
						</div>
						<table class="table datatable-ajax" id="statiegeld_table">
							<thead>
								<tr>
									<th>No</th>
									<th>Statiegeld</th>
									<th>Price</th>
									<th>Action</th>
								</tr>
							</thead>
							<tbody>	
							</tbody>
						</table>
					</div>
				</div>
			</div>

			<div class="row">
				<div class="col-sm-12">
					<div class="panel panel-flat">
						<div class="panel-heading">
							<h5 class="panel-title">Locatie</h5>
							<input type="hidden" class="table-name" value="locatie">
							<div class="heading-elements">
								<button class="btn btn-sm btn-primary basic-item-add-btn" type="button"><i class="icon-plus2 position-left"></i>Toevoegen</button>
		                	</div>
						</div>
						<table class="table datatable-ajax" id="locatie_table">
							<thead>
								<tr>
									<th>No</th>
									<th>Name</th>
									<th>Action</th>
								</tr>
							</thead>
							<tbody>	
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>




	<div id="basic_modal" class="modal fade">
		<div class="modal-dialog modal-md">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h5 class="modal-title" id="basic_modal_title"></h5>
				</div>
				<input type="hidden" value="" id="m_action_type">
				<input type="hidden" value="" id="m_table_name">
				<input type="hidden" value="" id="m_sel_id">
				
				<form action="#" class="form-horizontal" id="m_basic_form">
					<div class="modal-body">
						<div class="form-group">
							<label class="control-label col-sm-3">name</label>
							<div class="col-sm-9">
								<input type="text" placeholder="name" class="form-control" id="m_basic_item_name" required>
							</div>
						</div>

						<!-- <div class="form-group">
							<label class="control-label col-sm-3">Phone #</label>
							<div class="col-sm-9">
								<input type="text" placeholder="+99-99-9999-9999" data-mask="+99-99-9999-9999" class="form-control">
								<span class="help-block">+99-99-9999-9999</span>
							</div>
						</div> -->

					</div>

					<div class="modal-footer">
						<button type="button" class="btn btn-link" data-dismiss="modal">Close</button>
						<button type="submit" class="btn btn-primary">TOEVOEGEN</button>
					</div>
				</form>
			</div>
		</div>
	</div>


	<div id="margegroepen_modal" class="modal fade">
		<div class="modal-dialog modal-md">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h5 class="modal-title"> Margegroepen <span class="m_modal_type"></span></h5>
				</div>
				<input type="hidden" value="" id="m_margegroepen_action_type">
				<input type="hidden" value="" id="m_margegroepen_sel_id">
				
				<form action="#" class="form-horizontal" id="m_margegroepen_form">
					<div class="modal-body">
						<div class="form-group">
							<label class="control-label col-sm-3">Margegroepen</label>
							<div class="col-sm-9">
								<input type="text" placeholder="Margegroepen" class="form-control" id="m_margegroepen" required>
							</div>
						</div>
						<div class="form-group">
							<label class="control-label col-sm-3">Marge Vermenigvuldigingsfactor</label>
							<div class="col-sm-9">
								<input type="number" placeholder="" class="form-control" id="m_marge" step="any" required>
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

	<div id="btw_modal" class="modal fade">
		<div class="modal-dialog modal-md">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h5 class="modal-title"> BTW <span class="m_modal_type"></span></h5>
				</div>
				<input type="hidden" value="" id="m_btw_action_type">
				<input type="hidden" value="" id="m_btw_sel_id">
				
				<form action="#" class="form-horizontal" id="m_btw_form">
					<div class="modal-body">
						<div class="form-group">
							<label class="control-label col-sm-3">BTW %</label>
							<div class="col-sm-9">
								<input type="number" placeholder="BTW" class="form-control" id="m_btw" required>
							</div>
						</div>
						<div class="form-group">
							<label class="control-label col-sm-3">BTW Factor</label>
							<div class="col-sm-9">
								<input type="number" placeholder="BTW Factor" class="form-control" id="m_btw_factor" step="any" required>
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

	<div id="bezorging_modal" class="modal fade">
		<div class="modal-dialog modal-md">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h5 class="modal-title"> Bezorging <span class="m_modal_type"></span></h5>
				</div>
				<input type="hidden" value="" id="m_bezorging_action_type">
				<input type="hidden" value="" id="m_bezorging_sel_id">
				
				<form action="#" class="form-horizontal" id="m_bezorging_form">
					<div class="modal-body">
						<div class="form-group">
							<label class="control-label col-sm-3">Bezorging</label>
							<div class="col-sm-9">
								<input type="text" placeholder="Bezorging" class="form-control" id="m_bezorging" required>
							</div>
						</div>
						<div class="form-group">
							<label class="control-label col-sm-3">Bezorgfee</label>
							<div class="col-sm-9">
								<input type="number" placeholder="Bezorgfee" class="form-control" id="m_bezorgfee" step="any" required>
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

	<div id="statiegeld_modal" class="modal fade">
		<div class="modal-dialog modal-md">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h5 class="modal-title"> Statiegeld <span class="m_modal_type"></span></h5>
				</div>
				<input type="hidden" value="" id="m_statiegeld_action_type">
				<input type="hidden" value="" id="m_statiegeld_sel_id">
				
				<form action="#" class="form-horizontal" id="m_statiegeld_form">
					<div class="modal-body">
						<div class="form-group">
							<label class="control-label col-sm-3">Statiegeld</label>
							<div class="col-sm-9">
								<input type="text" placeholder="Statiegeld" class="form-control" id="m_statiegeld" required>
							</div>
						</div>
						<div class="form-group">
							<label class="control-label col-sm-3">Price</label>
							<div class="col-sm-9">
								<input type="number" placeholder="Price" class="form-control" id="m_price" step="any" required>
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