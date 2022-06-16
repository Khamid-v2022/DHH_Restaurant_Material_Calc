$(function() { 

	$('#forgot_modal').on('hidden.bs.modal', function() {
	    $(this).find('form').trigger('reset');
	});

	$("#login_form").submit(function(event) {
	  	/* stop form from submitting normally */
	  	event.preventDefault();

	  	if (!event.target.checkValidity()) {
	    	return false;
	  	}
	  	login();
	});

	$("#m_form").submit(function(event) {
	  	/* stop form from submitting normally */
	  	event.preventDefault();

	  	if (!event.target.checkValidity()) {
	    	return false;
	  	}
	  	submit_forgot();
	});
});

function login(){
	$.post(SITE_URL + 'login/sign_in', 
		{
			email: $("#email").val(),
			user_pass: $("#user_pass").val()
		}, 
		function(resp) {
			if(resp == "yes"){
				location.href = SITE_URL + 'home';
				return;
			}else{
				swal({
                    title: "Wrong info!",
                    type: "error",
                    confirmButtonColor: "#2196F3"
                }, function(){
                    return;
                });
			}
		});
}


function submit_forgot(){
	$.post(SITE_URL + 'login/forgot_password', 
		{
			email: $("#m_forgot_email").val()
		}, 
		function(resp) {
			// console.log(resp);
			if(resp == 'no'){
				swal({
                    title: "You are not our member!",
                    type: "error",
                    confirmButtonColor: "#2196F3"
                }, function(){
                    $("#forgot_modal").modal('toggle');
                });
			}else if(resp == "ok"){
				swal({
                    title: "Please check your emailbox!",
                    type: "success",
                    confirmButtonColor: "#2196F3"
                }, function(){
                    $("#forgot_modal").modal('toggle');
                });
			}else if(resp == 'failed'){
				swal({
                    title: "Failed to send email. Please try again later",
                    type: "error",
                    confirmButtonColor: "#2196F3"
                }, function(){
                    $("#forgot_modal").modal('toggle');
                });
			}
	});
}