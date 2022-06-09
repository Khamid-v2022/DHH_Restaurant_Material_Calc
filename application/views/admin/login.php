<script type="text/javascript" src="<?=base_url()?>assets/js/admin/login.js"></script>
<!-- Page container -->

<div class="page-container login-container">
	<div class="page-content">
		<div class="content-wrapper">

				<!-- Content area -->
				<div class="content">

					<!-- Simple login form -->
					<form action="#" id="login_form">
						<div class="panel panel-body login-form">
							<div class="text-center mb-20">

								<img class="login-logo" src="<?=base_url()?>assets/img/logo_main.png">
								<!-- <h5 class="content-group" style="font-size: 31px">Login <small class="display-block"></small></h5> -->
							</div>

							<div class="form-group has-feedback has-feedback-left">
								<input type="email" class="form-control" placeholder="Email" name="email" id="email" required autocomplete="off">
								<div class="form-control-feedback">
									<i class="icon-envelop2 text-muted"></i>
								</div>
							</div>

							<div class="form-group has-feedback has-feedback-left">
								<input type="password" class="form-control" placeholder="password" name="user_pass" id="user_pass" required autocomplete="off">
								<div class="form-control-feedback">
									<i class="icon-lock2 text-muted"></i>
								</div>
							</div>

							<div class="form-group">
								<button type="submit" class="btn btn-primary btn-block">Login <i class="icon-circle-right2 position-right"></i></button>
							</div>
							<div class="text-center">
								<a href="#" data-toggle="modal" data-target="#forgot_modal">Forgot password?</a>
							</div>
						</div>
					</form>
					<!-- /simple login form -->
		



	<div id="forgot_modal" class="modal fade">
		<div class="modal-dialog modal-sm">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h5 class="modal-title" id="modal_title">Recovery Password</h5>
				</div>
				<form action="#" class="form-horizontal" id="m_form">
					<div class="modal-body">
						<div class="text-center">
							<div class="icon-object border-warning text-warning"><i class="icon-spinner11"></i></div>
							<h5 class="content-group">Password recovery <small class="display-block">We'll send you instructions in email</small></h5>
						</div>
						<div class="form-group has-feedback" style="margin-left: 0; margin-right: 0;">
							<input type="email" id="m_forgot_email" class="form-control" placeholder="Your email" required>
							<div class="form-control-feedback">
								<i class="icon-mail5 text-muted"></i>
							</div>
						</div>

						<button type="submit" class="btn bg-blue btn-block">Reset password <i class="icon-arrow-right14 position-right"></i></button>
					</div>
				</form>
			</div>
		</div>
	</div>
