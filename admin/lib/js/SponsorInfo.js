$(document).ready(function() {
	// event for action - del click
    $('table tbody tr td #del_data').click(function() {
    	// Stop the browser from href the web.
		event.preventDefault();
		//init
		var this_row = $(this).parents('tr');
		var GetText = function(selector) { return this_row.children(selector).text(); };
		// confirm to del
		bootbox.confirm({
			title: "確定刪除資料？",
			size: "small",
			message: "即將刪除：" + GetText('#table_name') + " 的資料",
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
					var del_id = GetText('#table_sponsor_id');
					var del_name = GetText('#table_name');
					// reset server response cookies;
					var time = Date.now();
					// ajax to del adminuser data
					$.ajax({
						url: 'SponsorInfo_ajax.php?t=' + time,
						type: 'POST',
						data: {
							mode: "del",
							sponsor_id:  del_id,
							name:  del_name,
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
	// event for action - edit click
    $('table tbody tr td #edit_data').click(function() {
		// Stop the browser from href the web.
    	event.preventDefault();
		//init
		var this_row = $(this).parents('tr');
		var GetText = function(selector) { return this_row.children(selector).text(); };
		var GetAttr = function(selector, attr) { return this_row.children(selector).attr(attr); };
		// prepare ajax data
		var sponsor_id  = GetText('#table_sponsor_id');
		// reset server response cookies;
		var time = Date.now();
		// ajax to edit part data
		$.ajax({
			url: 'SponsorInfo_ajax.php?t=' + time,
			type: 'POST',
			data: {
				mode: 'search',
				sponsor_id:  sponsor_id,
			},
			dataType: 'json',
			cache: false,
			error: function(xhr) {
				response_msg( "error", "Ajax request 發生錯誤", "error" );
			},
			success: function(response) {
				if (response.mode == 'success') {
					// fill form value
					$("#form_sponsor_id").val(GetText('#table_sponsor_id')); 
					$("#form_phone").val(GetText('#table_phone'));
					$("#form_work_time").val(GetText('#table_work_time'));
					$("#form_name").val(GetText('#table_name'));
					$("#form_site").val(GetAttr('#table_site','title'));
					$("#form_intro").val(response.msg);
					$("#form_address").val(GetAttr('#table_address','title'));
					$("#form_img_url").val(GetAttr('#table_img_url','title'));
					// collapse show form-box
					$("div.form-box").collapse('show');
					// scroll to form
 					$('body').scrollTop('div.form-box');
				}
			}
		});
    });
	// event for when form submit 
    $('#form').submit(function() {
		// Stop the browser from submitting the form.
		event.preventDefault();
		// prepare ajax data
		var form_sponsor_id = $("#form_sponsor_id").val();
		var form_phone      = $("#form_phone").val();
		var form_work_time  = $("#form_work_time").val();
		var form_name       = $("#form_name").val();
		var form_address    = $("#form_address").val();
		var form_intro      = $("#form_intro").val();
		var form_site       = $("#form_site").val();
		var form_img_url    = $("#form_img_url").val();
		// check blank is fill up
		var check = ( form_name == '');
		if ( check ) {
			response_msg('warning', '名稱不得空白', 'warning');
			return;
		} else if (false) {
			respone_msg('warning', '還沒想到要驗證啥錯誤', 'warning');
			return;
		}
		// if img_url is '', assign placehold.it url
		if(form_img_url == '') {
			form_img_url = "https://placehold.it/600x400?text=" + form_name;
		}
		// reset server response cookies;
		var time = Date.now();
		// ajax to insert part data
		$.ajax({
			url: 'SponsorInfo_ajax.php?t='+time,
			type: 'GET',
			data: {
				mode	   : (form_sponsor_id == 'a')? "add":"update",
				sponsor_id : form_sponsor_id,
				phone      : form_phone,
				work_time  : form_work_time,
				name       : form_name,
				address    : form_address,
				intro      : form_intro,
				site       : form_site,
				img_url    : form_img_url,
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
	})
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