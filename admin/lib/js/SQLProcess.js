$(document).ready(function() {
	// event for when form submit 
	$('#Rejudge').submit(function() {
		// Stop the browser from submitting the form.
		event.preventDefault();
		// prepare ajax data
		var form_Time = $("#form_Time").val();
		var form_QID = $("#form_QID").val();
		var form_Ans     = $("#form_Ans").val();
		var form_Right     = $("#form_Right").val();
		// check blank is fill up and username is correct
		var check = (form_Time == "" || form_QID == "" || form_Ans == "" || form_Right == "");
		if ( check ) {
			response_msg('warning', '任意欄位不得空白', 'warning');
			return;
		}
		// ajax to insert adminuser data
		$.ajax({
			url: 'ImPoRtAnTfIlE/Rejudge.php',
			type: 'GET',
			data: {
				Time  : form_Time,
				QID	  : form_QID,
				Ans	  : form_Ans,
				Right : form_Right,
			},
			dataType: 'text',
			cache: false,
			error: function(xhr) {
				response_msg( "error", "Ajax request 發生錯誤:"+ xhr, "error" );
			},
			success: function(response) {
				// response_msg('success', response, 'success');
			}
		});
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