<!DOCTYPE html>
<html lang="nl">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>DHH</title>

	<!-- Global stylesheets -->
	<link href="<?=base_url()?>assets/plugin/css/icons/icomoon/styles.css" rel="stylesheet" type="text/css">
	<link href="<?=base_url()?>assets/plugin/css/icons/fontawesome/styles.min.css" rel="stylesheet" type="text/css">
	<link href="<?=base_url()?>assets/plugin/css/bootstrap.min.css" rel="stylesheet" type="text/css">
	<link href="<?=base_url()?>assets/plugin/css/core.min.css" rel="stylesheet" type="text/css">
	<link href="<?=base_url()?>assets/plugin/css/components.min.css" rel="stylesheet" type="text/css">
	<link href="<?=base_url()?>assets/plugin/css/colors.min.css" rel="stylesheet" type="text/css">
	<!-- /global stylesheets -->

	<!-- Core JS files -->
	<script type="text/javascript" src="<?=base_url()?>assets/plugin/js/plugins/loaders/pace.min.js"></script>
	<script type="text/javascript" src="<?=base_url()?>assets/plugin/js/core/libraries/jquery.min.js"></script>
	<script type="text/javascript" src="<?=base_url()?>assets/plugin/js/core/libraries/bootstrap.min.js"></script>
	<script type="text/javascript" src="<?=base_url()?>assets/plugin/js/plugins/loaders/blockui.min.js"></script>
	<!-- /core JS files -->

	<!-- Theme JS files -->
	<script type="text/javascript" src="<?=base_url()?>assets/plugin/js/plugins/notifications/sweet_alert.min.js"></script>
	<script type="text/javascript" src="<?=base_url()?>assets/plugin/js/plugins/forms/selects/select2.min.js"></script>
	<script type="text/javascript" src="<?=base_url()?>assets/plugin/js/plugins/ui/moment/moment.min.js"></script>
	<script type="text/javascript" src="<?=base_url()?>assets/plugin/js/plugins/pickers/daterangepicker.js"></script>

	<script type="text/javascript" src="<?=base_url()?>assets/plugin/js/plugins/tables/datatables/datatables.min.js"></script>

	<script type="text/javascript" src="<?=base_url()?>assets/plugin/js/core/app.min.js"></script>

	<script type="text/javascript">
		var SITE_URL = "<?=site_url()?>";
    	var BASE_URL = "<?=base_url()?>";
	</script>

	<!-- custom -->
	<script type="text/javascript" src="<?=base_url()?>assets/js/global.js"></script>
	<link href="<?=base_url()?>assets/css/main_layout.css" rel="stylesheet" type="text/css">
	<script type="text/javascript">
		$(function() {
			$('#change_password_modal').on('hidden.bs.modal', function() {
			    $(this).find('form').trigger('reset');
			});

			$('#change_profile_modal').on('hidden.bs.modal', function() {
			    $(this).find('form').trigger('reset');
			});
		});

		function change_admin_password(id){
			var old_pass = $("#old_pass").val();
			var new_pass = $("#new_pass").val();
			var confirm_pass = $("#confirm_pass").val();
			if(!old_pass){
				swal({
					title: "Please enter the currenct password",
		            text: "",
		            type: "warning"}, function(){
		            	setTimeout(function(){
		            		$("#old_pass").focus();
		            	}, 100);
		            });
				return;
			}

			if(!new_pass || !confirm_pass || new_pass != confirm_pass){
				swal({
					title: "Please check the inputed value",
		            text: "",
		            type: "warning"});
				return;
			}

			$.post(SITE_URL + 'login/update_password', 
				{
					id: id,
					old_pass: old_pass,
					new_pass: new_pass
				}, 
				function(resp){
					if(resp=="yes"){
						swal({
							title: "Updated",
				            text: "",
				            type: "success"},function(){
				            	$('#change_password_modal').modal('toggle');
				        });
					}else{
						swal({
							title: "Please check your current password",
				            text: "",
				            type: "warning"});
						return;
					}
			});
		}

		function change_admin_profile(id){
			var name = $("#m_profile_name").val();
			if(name == ""){
				swal({
					title: "Please enter the user name",
		            text: "",
		            type: "error"}, function(){
		            	setTimeout(function(){
		            		$("#m_profile_name").focus();
		            	}, 100);
		            });
				return;
			}

			var email = $("#m_email").val();
			if(email == ""){
				swal({
					title: "Please enter the email",
		            text: "",
		            type: "error"}, function(){
		            	setTimeout(function(){
		            		$("#m_email").focus();
		            	}, 100);
		            });
				return;
			}

			$.post(SITE_URL + 'login/update_profile', 
				{
					id: id,
					user_name: name,
					email: email
				}, 
				function(resp){
					if(resp=="yes"){
						swal({
							title: "Updated",
				            text: "",
				            type: "success"},function(){
				            	location.reload();
				        });
					}else if(resp=="no"){
						swal({
							title: "Please enter the another name",
				            text: "",
				            type: "error"});
					}
			});
		}

	</script>
</head>

<body>
	<?php 
		if(isset($this->session->user_data)) {
	?>
	<!-- change password modal -->
	<div id="change_password_modal" class="modal fade">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h5 class="modal-title">Password</h5>
				</div>

				<form action="#" class="form-horizontal">
					<div class="modal-body">
						<div class="form-group has-feedback">
							<label class="control-label col-sm-3">Current password: </label>
							<div class="col-sm-9">
								<input type="password" placeholder="Please enter the current password" class="form-control" id="old_pass">
								<div class="form-control-feedback">
									<i class="icon-unlocked2 text-muted"></i>
								</div>
							</div>
						</div>

						<div class="form-group has-feedback">
							<label class="control-label col-sm-3">New password: </label>
							<div class="col-sm-9">
								<input type="password" placeholder="new password" class="form-control" id="new_pass">
								<div class="form-control-feedback">
									<i class="icon-lock text-muted"></i>
								</div>
							</div>
						</div>
						<div class="form-group has-feedback">
							<label class="control-label col-sm-3">Confirm password: </label>
							<div class="col-sm-9">
								<input type="password" placeholder="Confirm password" class="form-control" id="confirm_pass">
								<div class="form-control-feedback">
									<i class="icon-lock text-muted"></i>
								</div>
							</div>
						</div>
					</div>

					<div class="modal-footer text-center">
						<button type="button" class="btn btn-link" data-dismiss="modal">Cancel</button>					
						<button type="button" class="btn btn-primary" onclick="change_admin_password(<?=$this->session->user_data['id']?>)">Change <i class="icon-sync"></i></button>			
					</div>
				</form>
			</div>
		</div>
	</div>

	<div id="change_profile_modal" class="modal fade">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h5 class="modal-title">Profile</h5>
				</div>

				<form action="#" class="form-horizontal">
					<div class="modal-body">
						<div class="form-group has-feedback">
							<label class="control-label col-sm-3">User name: </label>
							<div class="col-sm-9">
								<input type="text" placeholder="User name" class="form-control" id="m_profile_name" value="<?=$this->session->user_data['user_name']?>" required>
								<div class="form-control-feedback">
									<i class="icon-user text-muted"></i>
								</div>
							</div>
						</div>
						<div class="form-group has-feedback">
							<label class="control-label col-sm-3">Email: </label>
							<div class="col-sm-9">
								<input type="email" placeholder="Email" class="form-control" id="m_email" value="<?=$this->session->user_data['email']?>" required>
								<div class="form-control-feedback">
									<i class="icon-envelop2 text-muted"></i>
								</div>
							</div>
						</div>
					</div>

					<div class="modal-footer text-center">
						<button type="button" class="btn btn-link" data-dismiss="modal">Cancel</button>
						<button type="button" class="btn btn-primary" onclick="change_admin_profile(<?=$this->session->user_data['id']?>)">Change <i class="icon-sync"></i></button>
					</div>
				</form>
			</div>
		</div>
	</div>
	<?php } ?>


	<?php if(isset($this->session->user_data)) { ?>
		<!-- Main navbar -->
		<div class="navbar navbar-inverse navbar-primary">
			<div class="navbar-header">
				<a class="navbar-brand" href="<?=site_url()?>"><img src="<?=base_url()?>assets/img/logo_main.png" alt=""></a>

				
				<ul class="nav navbar-nav pull-right visible-xs-block">
					<li><a data-toggle="collapse" data-target="#navbar-mobile"><i class="icon-tree5"></i></a></li>
				</ul>
			</div>
			
			<div class="navbar-collapse collapse" id="navbar-mobile">
				<?php if(isset($primary_menu)) {?>
					<ul class="nav navbar-nav">
						<li class="<?php if($primary_menu == 'Home') echo 'active'?>">
							<a href="<?=site_url()?>">
								<span><i class="icon-home2"></i></span>
							</a>
						</li>
						<li class="dropdown dropdown-user">					
							<a class="dropdown-toggle" data-toggle="dropdown">
								<span>VERKOOPPRODUCTEN</span>
								<i class="caret"></i>
							</a>

							<ul class="dropdown-menu dropdown-menu-right">
								<li class="dropdown <?php if($primary_menu == 'Calculatie Verkoopproducten') echo 'active'?>">
									<a href="<?=site_url()?>calculatie">
										<i class="icon-calculator2"></i>
										<span class="position-right">Calculatie</span>
									</a>
								</li>
								<li class="dropdown <?php if($primary_menu == 'Database verkoopproducten') echo 'active'?>">
									<a href="<?=site_url()?>cv">
										<i class="icon-database-time2"></i>
										<span class="position-right">Database verkoopproducten</span>
									</a>
								</li>
							</ul>
						</li>
						<li class="dropdown dropdown-user">					
							<a class="dropdown-toggle" data-toggle="dropdown">
								<span>RECEPTEN</span>
								<i class="caret"></i>
							</a>

							<ul class="dropdown-menu dropdown-menu-right">
								<li class="dropdown <?php if($primary_menu == 'Calculatie Recepten') echo 'active'?>">
									<a href="<?=site_url()?>calculatie_recepten">
										<i class="icon-calculator2"></i>
										<span class="position-right">Calculatie</span>
									</a>
								</li>
								<li class="dropdown <?php if($primary_menu == 'Database recepten') echo 'active'?>">
									<a href="<?=site_url()?>cr_db">
										<i class="icon-database-time2"></i>
										<span class="position-right">Database recepten</span>
									</a>
								</li>
							</ul>
						</li>

						<li class="dropdown dropdown-user">					
							<a class="dropdown-toggle" data-toggle="dropdown">
								<i class="icon-cog3"></i>
								<span  class="position-right">SETTINGS</span>
								<i class="caret"></i>
							</a>

							<ul class="dropdown-menu dropdown-menu-right">
								<li class="dropdown <?php if($primary_menu == 'Functions') echo 'active'?>">
									<a href="<?=site_url()?>functions">
										<i class="icon-command"></i>
										<span class="position-right">Functions</span>
									</a>
								</li>
								<li class="dropdown <?php if($primary_menu == 'Leverancierslijst') echo 'active'?>">
									<a href="<?=site_url()?>leverancierslijst">
										<i class="icon-file-text2"></i>
										<span class="position-right">Leverancierslijst</span>
									</a>
								</li>
								<li class="dropdown <?php if($primary_menu == 'Prijsafwijkingen') echo 'active'?>">
									<a href="<?=site_url()?>prijsafwijkingen">
										<i class="icon-percent"></i>
										<span class="position-right">Prijsafwijkingen</span>
									</a>
								</li>
								 
								<li class="dropdown <?php if($primary_menu == 'Users') echo 'active'?>">
									<a href="<?=site_url()?>users">
										<i class="icon-users"></i>
										<span class="position-right">Users</span>
									</a>
								</li>
							</ul>
						</li>
						<li class="<?php if($primary_menu == 'Voorraadtelling') echo 'active'?>">
							<a href="<?=site_url()?>voorraadtelling">
								<span>VOORRAADTELLING</span>
							</a>
						</li>
					</ul>
				<?php }?>
				<ul class="nav navbar-nav navbar-right">						
					<li class="dropdown dropdown-user">					
						<a class="dropdown-toggle" data-toggle="dropdown">
							<span style="font-size: 18px"><?=$this->session->user_data['user_name']?></span>
							<i class="caret"></i>
						</a>

						<ul class="dropdown-menu dropdown-menu-right">
							<li><a data-toggle="modal" data-target="#change_profile_modal"><i class="icon-profile"></i> Profile</a></li>
							<li><a data-toggle="modal" data-target="#change_password_modal"><i class="icon-lock5"></i> Change password</a></li>
							<li><a href="<?=site_url()?>login/sign_out"><i class="icon-switch2"></i> Logout</a></li>
						</ul>
					</li>
				</ul>
			</div>
		</div>
	<?php }?>
	
	<?php if(isset($this->session->user_data) && isset($primary_menu)) {?>
	<!-- Page container -->
	<div class="page-container">

		<!-- Page content -->
		<div class="page-content">
			<!-- Main content -->
			<div class="content-wrapper">
				<!-- Page header -->
				<div class="page-header">
					<div class="page-header-content">
						<div class="page-title">
							<h4><a href="javascript:history.back()"><i class="icon-arrow-left52 position-left"></i></a> <span class="text-semibold"><?=$primary_menu?></span></h4>
						</div>

					</div>					
				</div>
				<!-- /page header -->
				<?php }?>


