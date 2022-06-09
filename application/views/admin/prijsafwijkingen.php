<script type="text/javascript" src="<?=base_url()?>assets/plugin/js/plugins/tables/datatables/datatables.min.js"></script>
<script type="text/javascript" src="<?=base_url()?>assets/js/admin/prijsafwijkingen.js"></script>

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
					<h5 class="panel-title">Prijsafwijkingen</h5>
					<div class="heading-elements">
						
                	</div>
				</div>

				<div class="large-table-fake-top-scroll">
				  	<div>&nbsp;</div>
				</div>
				
				<table class="table datatable-ajax" id="data_table">
					<thead>
						<tr>
							<th>Actie</th>
							<th>Naam</th>
							<th>Verkoopprijs ex btw</th>
							<th>Suggestieprijs</th>
							<th>Wijziging</th>
							<th>Type</th>
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



	<div id="modal_handmatig" class="modal fade" tabindex="-1">
		<div class="modal-dialog">
			<div class="modal-content text-center">
				<div class="modal-header">
					<h5 class="modal-title">HANDMATIG PRIJS</h5>
				</div>

				<input type="hidden" id="m_type">
				<input type="hidden" id="m_sel_id">
				<form class="form-inline" id="handmatig_prijs_form">
					<div class="modal-body">
						<div class="form-group">
							<label>Handmatig prijs: </label>
							<input type="number" placeholder="Jaar" class="form-control"  required step="any" id="m_handmatig_prijs">
						</div>
					</div>

					<div class="modal-footer text-center">
						<button type="button" class="btn btn-link" data-dismiss="modal">Close</button>
						<button type="submit" class="btn btn-primary">TOEVOEGEN <i class="icon-drawer-in"></i></button>
					</div>
				</form>
			</div>
		</div>
	</div>




