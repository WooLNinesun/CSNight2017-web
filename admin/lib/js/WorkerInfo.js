$(document).ready(function() {
	// event for action - del click
	$('table tbody tr td #del_data').click(function() {
	   	// Stop the browser from href the web.
		event.preventDefault();
		// init	
		var this_row = $(this).parents('tr');
		var GetText = function(selector) { return this_row.children(selector).text(); };
		// confirm to del
		bootbox.confirm({
			title: "確定刪除資料？",
			size: "small",
			message: "即將刪除：" + GetText('#table_caption'),
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
					var del_id = GetText("#table_worker_id");
					var del_name = GetText("#table_name");
					// reset server response cookies;
					var time = Date.now();
					// ajax to del adminuser data
					$.ajax({
						url: 'WorkerInfo_ajax.php?t='+time,
						type: 'POST',
						data: {
							mode: "del",
							worker_id:  del_id,
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
		// init		
		var this_row = $(this).parents('tr');
		var GetText = function(selector) { return this_row.children(selector).text(); };
		var GetAttr = function(selector, attr) { return this_row.children(selector).attr(attr); };
		// prepare ajax data
		var worker_id  = GetText('#table_worker_id');
		// reset server response cookies;
		var time = Date.now();
		// ajax to edit worker data
		$.ajax({
			url: 'WorkerInfo_ajax.php?t='+time,
			type: 'POST',
			data: {
				mode: 'search_detail',
				worker_id: worker_id,
			},
			dataType: 'json',
			cache: false,
			error: function(xhr) {
				response_msg( "error", "Ajax request 發生錯誤", "error" );
			},
			success: function(response) {
				if (response.mode == 'success') {
					// fill form value
					$("#form_worker_id").val( GetText('#table_worker_id') );
					$("#form_name").val( GetText('#table_name') );
					$("#form_part").val( GetText('#table_part') );
					$("#form_self_intro").val( response.msg );
					$("#form_img_url").val( GetAttr('#table_img_url','title') );
					$("#form_small_img_url").val( GetAttr('#table_small_img_url','title') );
					// collapse show form-box
					$("div.form-box").collapse('show');
					// scroll to form
					$('body').scrollTop('div.form-box');
				} else {
					response_msg(response.mode, response.msg, response.mode);
				}
			}
		});
	});
	// event for action - part click
	$('table tbody tr td #part_data').click(function() {
		// Stop the browser from href the web.
		event.preventDefault();
		//init
		var this_row = $(this).parents('tr');
		var GetText = function(selector) { return this_row.children(selector).text(); };
		// prepare ajax data
		var search_worker_id = GetText("#table_worker_id");
		// reset server response cookies;
		var time = Date.now();
		// ajax to search part data
		$.ajax({
			url: 'WorkerInfo_ajax.php?t='+time,
			type: 'POST',
			data: {
				mode: "search_part",
				worker_id:  search_worker_id,
			},
			dataType: 'json',
			cache: false,
			error: function(xhr) {
				response_msg( "error", "Ajax request 發生錯誤", "error" );		
			},
			success: function(response) {
				if (response.mode == 'success') {
					var title = GetText('#table_name')+"的 部門 和 表演 ！";
					response_msg(title, response.msg, response.mode);
				} else {
					response_msg(response.mode, response.msg, response.mode);
				}
			}
		});
	});
	// event for when form submit 
	$('#form').submit(function() {
		// Stop the browser from submitting the form.
		event.preventDefault();
 		// prepare ajax data
		var form_worker_id  = $("#form_worker_id").val();
		var form_name  = $("#form_name").val();
		var form_part  = $("#form_part").val();
		var form_self_intro  = $("#form_self_intro").val();
		var form_img_url  = $("#form_img_url").val();
		var form_small_img_url  = $("#form_small_img_url").val();
		// check blank is fill up
		var check = (form_name == '');
		if ( check ) {
			response_msg('warning', '名字不得空白', 'warning');
			return;
		}
		// if img_url is '', assign placehold.it url
		if(form_img_url == '') { form_img_url = "https://placehold.it/600x400?text=" + form_name; }
		if(form_small_img_url == '') { form_small_img_url = "https://placehold.it/300x200?text=" + form_name; }
		// reset server response cookies;
		var time = Date.now();
		// ajax to update worker data
		$.ajax({
			url: 'WorkerInfo_ajax.php?t='+time,
			type: 'GET',
			data: {
				mode: (form_worker_id == 'a')? "add":"update",
				worker_id:  form_worker_id,
				name:  form_name,
				part: form_part,
				self_intro:    form_self_intro,
				img_url:  form_img_url,
				small_img_url:  form_small_img_url,
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
// search table data, keyup function
$("#search_input").keyup(function() {
	var filter, table_rows;
	filter = $("#search_input").val().toLowerCase();
	
	table_rows = $("#worker_data").children("tr");
	
	if(filter != "") {
		var WorkerID, WorkerName;
		var found = false;
		table_rows.each(function(){
			WorkerID = $(this).children("#table_worker_id").text().toLowerCase();
			WorkerName = $(this).children("#table_name").text().toLowerCase();

			if( (WorkerName.search(filter) != -1) || (WorkerID.search(filter) != -1) ) {
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