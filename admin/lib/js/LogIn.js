$(document).ready(function() {
	// event for when form submit 
	$('#form').submit(function() {
		// Stop the browser from submitting the form.
		event.preventDefault();
		// prepare ajax data
		var form_username = $("#form_username").val();
		var form_password = $("#form_password").val();
		// check blank is fill up and username is correct
		var check = (form_username == "" || form_password == "" );
		if ( check ) {
			response_msg('warning', '任意欄位不得空白', 'warning');
			return;
		}
		check = /^[a-zA-Z0-9]{1,50}$/.test(form_username);
		if( !check ) {	
			response_msg('warning', '帳號只能是英文+數字', 'warning');
			return;
		}
		// reset server response cookies;
		var time = Date.now();
		// ajax to insert adminuser data
		$.ajax({
			url: 'LogIn_ajax.php?t=' + time,
			type: 'POST',
			data: {
				mode: "add",
				username : form_username,
				password : md5(form_password)
			},
			dataType: 'json',
			cache: false,
			error: function(xhr) {
				response_msg( "error", "Ajax request 發生錯誤", "error" );
			},
			success: function(response) {
				if (response.mode == 'success') {
					$(location).attr('href', 'index.php')
				}
				response_msg(response.mode, response.msg, response.mode);
			}
		});
	})
});

function response_msg(title, msg, mode) {
	var box_class = '';
	switch(mode) {
		case 'success':
			box_class = 'medal-success';
			break;
		case 'warning':
			box_class = 'medal-warning';
			break;
		case 'error':
			title = '聯絡學術部！出現問題啦';
			box_class = 'medal-error';
			break;
	}
	bootbox.alert({
		title: title,
		message: msg,
		backdrop: true,
		className: box_class,
	});
}