<script type="text/javascript" src="<?=base_url()?>assets/js/admin/companies.js"></script>

<style type="text/css">
	.datatable-scroll {
	    width: 100%;
	    overflow-x: scroll;
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
					<h5 class="panel-title">Users</h5>
					<input type="hidden" class="table-name" value="eenheden">
					<div class="heading-elements">
						<button class="btn btn-sm btn-primary" type="button" onclick="add_user_modal()"><i class="icon-plus2 position-left"></i>Toevoegen</button>
                	</div>
				</div>
				
				<table class="table datatable-ajax" id="data_table">
					<thead>
						<tr>
							<th>Company Name</th>
							<th>User Name</th>
							<th>Email</th>
							<th>Tel</th>
							<th>Total Users</th>
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

	<div id="user_modal" class="modal fade">
		<div class="modal-dialog modal-lg">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h5 class="modal-title"><span class="action-type"></span> User</h5>
				</div>
				<input type="hidden" value="" id="m_sel_id">
				<input type="hidden" value="" id="m_action_type">

				<form action="#" class="form-horizontal" id="m_form">
					<div class="modal-body">
						<div class="form-group">
							<label>Company Name*</label>
							<input class="form-control" type="text" id="m_company_name" placeholder="Company name" required>
						</div>
						<div class="form-group">
							<label>Profile Name*</label>
							<input class="form-control" type="text" id="m_user_name" placeholder="User name" required>
						</div>
						<div class="form-group">
							<label>Email*</label>
							<input type="email" placeholder="Email" class="form-control" id="m_user_email" required>
						</div>	
						<span class="help-block">Password will set to Admin123456! by default</span>	
						<div class="form-group">
							<label>Tel</label>
							<input class="form-control" type="text" id="m_phone_num" placeholder="Phone Number">
						</div>
					</div>

					<div class="modal-footer">
						<button type="button" class="btn btn-link" data-dismiss="modal">Close</button>
						<button type="submit" class="btn btn-primary">TOEVOEGEN <i class="icon-spinner10 spinner position-right" style="display:none"></i></button>
					</div>
				</form>
			</div>
		</div>
	</div>