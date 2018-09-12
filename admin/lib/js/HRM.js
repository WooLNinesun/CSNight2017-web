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
			message: "即將刪除："+GetText('#table_WorkerName')+" - "+GetText('#table_PartName')+"的資料",
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
					var del_id = GetText('#table_ID');
					var del_WorkerName = GetText('#table_WorkerName');
					var del_PartName = GetText('#table_PartName');
					// reset server response cookies;
					var time = Date.now();
					// ajax to del adminuser data
					$.ajax({
						url: 'HRM_ajax.php?t='+time,
						type: 'POST',
						data: {
							mode: "del",
							ID:  del_id,
							WorkerName: del_WorkerName,
							PartName: del_PartName,
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
		var form_WorkerID = $("#form_WorkerID").val();
		var form_PartID = $("#form_PartID").val();
		var form_Priority = $("#form_Priority").val();
		// check blank is fill up
		var check = (form_WorkerID == "" || form_PartID ==  null || form_Priority == "");
		if ( check ) {
			response_msg('warning', '任意欄位不得空白', 'warning');
			return;
		}
		// reset server response cookies;
		var time = Date.now();
		// ajax to insert worker data
		$.ajax({
			url: 'HRM_ajax.php?t='+time,
			type: 'POST',
			data: {
				mode: "add",
				WorkerID:  form_WorkerID,
				PartID:  form_PartID,
				Priority: form_Priority,
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
	//selector for rows
	table_rows = $("#HRM_data").children("tr");
	
	if(filter != "") {
		var WorkerName, PartName, Priority;
		var found = false;
		table_rows.each(function(){
			WorkerName = $(this).children("#table_WorkerName").text().toLowerCase();
			PartName = $(this).children("#table_PartName").text().toLowerCase();
			Priority = $(this).children("#table_Priority").text().toLowerCase();

			if( (WorkerName.search(filter) != -1) || (PartName.search(filter) != -1) || (Priority.search(filter) != -1)) {
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