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
			message: "即將刪除：" + GetText('#table_caption') + " 的資料",
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
					var del_id = GetText('#table_part_id');
					var del_caption = GetText('#table_caption');
					// reset server response cookies;
					var time = Date.now();
					// ajax to del part data
					$.ajax({
						url: 'PartInfo_ajax.php?t=' + time,
						type: 'POST',
						data: {
							mode: "del",
							part_id:  del_id,
							caption:  del_caption,
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
		var part_id  = GetText('#table_part_id');
		// reset server response cookies;
		var time = Date.now();
		// ajax to edit part data
		$.ajax({
			url: 'PartInfo_ajax.php?t=' + time,
			type: 'POST',
			data: {
				mode: 'search',
				part_id:  part_id,
			},
			dataType: 'json',
			cache: false,
			error: function(xhr) {
				response_msg( "error", "Ajax request 發生錯誤", "error" );
			},
			success: function(response) {
				if (response.mode == 'success') {
					// fill form value
					$("#form_part_id").val(GetText('#table_part_id')); 
					$("#form_order_id").val(GetText('#table_order_id'));
					$("#form_catg_id").val(GetAttr('#table_catg_name', 'CatgID'));
					$("#form_caption").val(GetText('#table_caption'));
					$("#form_intro").val(response.msg);
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
		var form_part_id  = $("#form_part_id").val(); 
		var form_order_id = $("#form_order_id").val();
		var form_catg_id  = $("#form_catg_id").val();
		var form_caption  = $("#form_caption").val();
		var form_intro    = $("#form_intro").val();
		var form_img_url  = $("#form_img_url").val();
		// check blank is fill up
		var check = ( form_order_id == '' || form_caption == '' || form_catg_id == null);
		if ( check ) {
			response_msg('warning', '任意欄位不得空白', 'warning');
			return;
		} else if ( isNaN(form_order_id) ) {
			response_msg('warning', '表演順序欄位請輸入數字，非表演部門輸入數字 0 。', 'warning');
			return;
		}
		// if img_url is '', assign placehold.it url
		if(form_img_url == '') {
			form_img_url = "https://placehold.it/600x400?text=" + form_caption;
		}
		// reset server response cookies;
		var time = Date.now();
		// ajax to insert part data
		$.ajax({
			url: 'PartInfo_ajax.php?t=' + time,
			type: 'GET',
			data: {
				mode: (form_part_id == 'a')? "add":"update",
				part_id:  form_part_id,
				order_id: form_order_id,
				catg_id: form_catg_id,
				caption:  form_caption,
				intro:    form_intro,
				img_url:  form_img_url,
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