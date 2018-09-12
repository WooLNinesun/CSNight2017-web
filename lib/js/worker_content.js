function lightbox_beforeshow_1() {
	// load lightbox inside content
	var worker_id = BOX_SEL.parent().attr("class").slice(14);
	$("#pop_inside_text h2").text(worker_local[worker_id]['name']);
	$("#pop_img #small_img").css("background-image", "url(" + worker_local[worker_id]['small_img_url'] + ")");
	get_worker_detail(worker_id);

	// change font-size & border-radius
	$("#lightbox_1").css("border-radius", "0px");
	var transition_set = "font-size 0.3s ease, border-radius 0.3s ease, box-shadow 0.3s ease";
	$("#pop_inside_text h2").css({
		"-webkit-transition":	transition_set,
		"-moz-transition":		transition_set,
		"transition":			transition_set,
	});
	setTimeout(function(){
		$("#pop_inside_text h2").css({
			"font-size": "3.5em"
		});
	}, 50);
	if(nRow > 4) {
		setTimeout(function(){
			$("#pop_text").css({
				"overflow": "auto",
			});
		}, 300);
	} else {
		setTimeout(function(){
			$("#lightbox_1").css("overflow", "auto");
		}, 300);
	}
}
function lightbox_size_1() {
	// set width & height

	var winWidth = $(window).outerWidth();
	var winHeight = $(window).outerHeight();

	var new_width, new_height;
	if(nRow > 4) {
		new_height = winHeight * 0.8;
		if(new_height > 430) {
			new_height = 430;
		}
		new_width = new_height / 5 * 8;
		if(new_width > winWidth * 0.9) {
			new_width = winWidth * 0.9;
		}
	} else {
		new_height = winHeight * 0.85;
		new_width = new_height * 2 / 3;
		if(new_width > winWidth * 0.85) {
			new_width = winWidth * 0.85;
			new_height = new_width * 3 / 2;
		}
	}

	return {
		"width" :	new_width + "px",
		"height" :	new_height + "px",
		"left" :	(winWidth - new_width) / 2 + "px",
		"top" :		(winHeight - new_height) / 2 + "px"
	};
}

function lightbox_beforehide_1() {
	$("#pop_img #big_img").css("background-image", "");
}
function lightbox_afterhide_1() {
	$("#lightbox_1").css("border-radius", "5px");
	var transition_set = "";
	setTimeout(function(){
		$("#pop_inside_text h2").css({
			"font-size": "20px"
		});
	}, 50);
	setTimeout(function(){
		$("#pop_inside_text h2").css({
			"-webkit-transition":	transition_set,
			"-moz-transition":		transition_set,
			"transition":			transition_set,
		});
	}, 300);
	if(nRow > 4) {
		$("#pop_text").scrollTop(0);
		$("#pop_text").css({
			"overflow": "hidden",
		});
	} else {
		$("#lightbox_1").scrollTop(0);
		$("#lightbox_1").css("overflow", "hidden");
	}

	// RELOAD_ALL_WORKER
	setTimeout(function(){
		if(RELOAD_ALL_WORKER == 1) {
			var num_worker_local = worker_local.length;
			for(var i = 0; i < num_worker_local; ++i) {
				if(worker_local[i] !== undefined && worker_local[i]['img_url'] !== undefined) {
					delete worker_local[i]['img_url'];
				}
			}
			$(".inside_box").css("background-color", "white");
			$(".inside_box").css("color", "black");
			RELOAD_ALL_WORKER = 0;
		}
	}, 300);
}

$(window).resize(function(){
	if( $("#lightbox_1").css("display") == "block" ) {
		if(nRow > 4) {
			$("#pop_text").css("overflow", "auto");
		} else {
			$("#lightbox_1").css("overflow", "auto");
		}
	}
});
