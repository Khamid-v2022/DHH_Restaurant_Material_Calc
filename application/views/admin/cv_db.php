<script type="text/javascript" src="<?=base_url()?>assets/plugin/js/plugins/tables/datatables/datatables.min.js"></script>
<script type="text/javascript" src="<?=base_url()?>assets/js/admin/cv_db.js"></script>

<style type="text/css">
	.datatable-scroll {
	    /*width: 100%;*/
	}
	.large-table-fake-top-scroll {
	  overflow-x: scroll;
	  overflow-y: auto;
	}

	.large-table-fake-top-scroll div {
		font-size:1px;
		line-height:1px;
	}


	/*.dataTables_scrollHeadInner, .table.datatable-ajax.dataTable.no-footer{
		width: 100%!important;
	}*/

</style>
<!-- Content area -->
<div class="content">
	<!-- Main charts -->
	<div class="row">
		<div class="col-lg-12">
			<!-- Traffic sources -->
			<div class="panel panel-flat">
				<div class="panel-heading">
					<h5 class="panel-title">Database verkoopproducten</h5>
					<input type="hidden" class="table-name" value="eenheden">
					<div class="heading-elements">
						<button class="btn btn-sm btn-primary" type="button" onclick="new_calculate()"><i class="icon-plus2 position-left"></i>Toevoegen</button>
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
							<th>Leveranciersnaam</th>
							<th>Inkoopcategorie</th>
							<th>Eenheid verpakking</th>
							<th>Verkoopprijs ex btw</th>
							<th>Eenheid kleinste</th>
							<th>Suggestie prijs</th>
							<th>Verkoop prijs</th>
							<th>BTW</th>
							<th>Factor</th>
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