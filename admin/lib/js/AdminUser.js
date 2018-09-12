$(document).ready(function() {
	// event for action - del click
	$('#table tbody tr td #del_data').click(function() {
		// Stop the browser from href the web.
		event.preventDefault();
		// init
		var this_row = $(this).parents('tr');
		var GetText = function(selector) { return this_row.children(selector).text(); };
		// confirm to del
		bootbox.confirm({
			title: "確定刪除資料？",
			size: "small",
			message: "即將刪除："+GetText('#table_UserName') + " - " + GetText('#table_Name') + "的資料",
			backdrop: true,
			buttons: {
				confirm: {
					label: '確認刪除！',
					className: 'btn-success'
				},
				cancel: {
				    label: '取消',
				    className: 'btn-danger'
				}
    		},
			callback: function(result){
				if( result ) {
					// prepare ajax data
					var del_username = GetText('#table_UserName');
					var del_name = GetText('#table_Name');
					// reset server response cookies;
					var time = Date.now();
					// ajax to del adminuser data
					$.ajax({
						url: 'AdminUser_ajax.php?t=' + time,
						type: 'POST',
						data: {
							mode: "del",
							UserName: del_username,
							Name: del_name,
						},
						dataType: 'json',
						cache: false,
						error: function(xhr) {
							response_msg( "error", "Ajax request 發生錯誤", "error" );
						},
						success: function(response) {
							if (response.mode == 'success') {
								this_row.remove();
							} else {
								response_msg(response.mode, response.msg, response.mode);
							}
						}
					});
				}
			}
		});
	});
	// event for when form submit 
	$('#form').submit(function() {
		// Stop the browser from submitting the form.
		event.preventDefault();
		// prepare ajax data
		var form_username = $("#form_username").val();
		var form_password = $("#form_password").val();
		var form_name     = $("#form_name").val();
		// check blank is fill up and username is correct
		var check = (form_username == "" || form_password == "" || form_name == "");
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
			url: 'AdminUser_ajax.php?t=' + time,
			type: 'POST',
			data: {
				mode: "add",
				username : form_username,
				password : md5(form_password),
				name	 : form_name,
			},
			dataType: 'json',
			cache: false,
			error: function(xhr) {
				response_msg( "error", "Ajax request 發生錯誤", "error" );
			},
			success: function(response) {
				if (response.mode == 'success') {
					location.reload();
				}
				response_msg(response.mode, response.msg, response.mode);
			}
		});
	});
	// event for form collapse button click
	$('button.btn-info').click(function() {
		$("div.form-box").collapse('toggle');
	});
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

// search table data, keyup function
$("#search_input").keyup(function() {
	var filter, table_rows;
	filter = $("#search_input").val().toLowerCase();
	//selector for rows
	table_rows = $("#table_data").children("tr");
	
	if(filter != "") {
		var UserName, Name, Time;
		var found = false;
		table_rows.each(function(){
			UserName = $(this).children("#table_UserName").text().toLowerCase();
			Name     = $(this).children("#table_Name").text().toLowerCase();
			Time     = $(this).children("#table_Time").text().toLowerCase();

			if( (UserName.search(filter) != -1) || (Name .search(filter) != -1) || (Time.search(filter) != -1)) {
				$(this).css("display", "");
				found = true;
			} else {
				$(this).css("display", "none");
			}
		});
		if ( found == true ) {
			$("#NoResult").css("display", "none");
		} else {
			$("#NoResult").css("display", "");
		}
	} else {
		table_rows.each(function(){
			$(this).css("display", "");
		});
		$("#NoResult").css("display", "none");
	}
});