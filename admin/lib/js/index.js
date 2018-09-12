$("#search_input").keyup(function() {
	var filter, table_rows;
	filter = $("#search_input").val().toLowerCase();
	
	table_rows = $("#table_data").children("tr");

	if(filter != "") {
		var Time, UserName, Action, IP;
		var found = false;
		table_rows.each(function(){
			Time     = $(this).children("#time").text().toLowerCase();
			UserName = $(this).children("#username").text().toLowerCase();
			Action   = $(this).children("#action").text().toLowerCase();
			IP       = $(this).children("#IP").text().toLowerCase();

			if( (Time.search(filter) != -1) || (UserName.search(filter) != -1) || (Action.search(filter) != -1) || (IP.search(filter) != -1) ) {
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