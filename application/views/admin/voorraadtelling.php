<script type="text/javascript" src="<?=base_url()?>assets/plugin/js/plugins/tables/datatables/datatables.min.js"></script>
<script type="text/javascript" src="<?=base_url()?>assets/js/admin/voorraadtelling.js"></script>

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
					<h5 class="panel-title">Voorraadtelling</h5>
					<div class="heading-elements">
						<button class="btn btn-sm btn-primary" type="button" onclick="add_info_modal()"><i class="icon-plus2 position-left"></i>Toevoegen</button>
                	</div>
				</div>
				
				<table class="table datatable-ajax" id="main_table" data-page-length='25'>
					<thead>
						<tr>
							<th>Naam</th>
							<th>Action</th>
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


	<div id="voorraadtelling_add_modal" class="modal fade">
		<div class="modal-dialog modal-lg">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h5 class="modal-title"><span>Nieuwe voorraadtelling</span></h5>
				</div>

				<input id="m_sel_id" type="hidden">
				<input id="m_action_type" type="hidden">
				
				<form action="#" class="form-horizontal" id="m_add_form">
					<div class="modal-body">
						<div class="form-group">
							<label class="control-label col-sm-3">Naam</label>
							<div class="col-sm-9">
								<input type="text" placeholder="Naam" class="form-control" id="m_naam" required>
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

	<div id="start_modal" class="modal fade">
		<div class="modal-dialog modal-lg">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h5 class="modal-title"><span>Leverancierslijst</span></h5>
				</div>
				
				<input type="hidden" id="m_start_sel_id">
				<form action="#" class="form-horizontal" id="m_start_form">
					<div class="modal-body">
						<div class="form-group">
							<label class="control-label col-sm-3">Locatie</label>
							<div class="col-sm-9">
								<select class="select-search" id="m_locatie">
									<?php 
									foreach($locaties as $item){
										echo '<option value="' . $item['id'] . '">' . $item['name'] . '</option>';
									}
									?>
								</select>
							</div>
						</div>
						<div class="row">
							<table class="table datatable-ajax" id="popup_table">
								<thead>
									<tr>
										<th>No</th>
										<th>Product Naam</th>
									</tr>
								</thead>
								<tbody>	
								</tbody>
							</table>
						</div>
					</div>

					<div class="modal-footer">
						<button type="button" class="btn btn-link" data-dismiss="modal">Close</button>
					</div>
				</form>
			</div>
		</div>
	</div>
