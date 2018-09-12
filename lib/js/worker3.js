/*** CLICK ***/
// function filter_click(id) {
$('body').on('click', '.filter', function () {
	// change active class
	if(! $(this).hasClass("active") ) {
		$(".filter[class ~= 'active']").removeClass("active");
		$(this).addClass("active");
	}
	var part_id = $(this).attr("id").slice(8);
	var thisid = $("#pop_part_id_" + part_id);
	if(! thisid.hasClass("active") ) {
		$(".pop_filter[class ~= 'active']").removeClass("active");
		thisid.addClass("active");
		$("#small_filter").text( thisid.text() );
	}
	// scroll to that section_id
	$("html, body").clearQueue().animate({ scrollTop: $("#section_" + part_id).offset().top - 70 }, 500);
});
$(".filter_folder").click(function(){
	// change active class
	if(! $(this).hasClass("active") ) {
		$(".filter_folder[class ~= 'active']").removeClass("active");
		$(this).addClass("active");
	}
	var catg_id = $(this).attr("id").slice(7);
	var thisid = $("#pop_folder_" + catg_id);
	if(! thisid.hasClass("active") ) {
		$(".pop_filter_folder[class ~= 'active']").removeClass("active");
		thisid.addClass("active");
	}

	if(catg_id == "") {
		// TODO: search
	} else {
		$("#content").empty();
		get_part(catg_id);		// get the ajax again
	}
});

/*** hover on outside_folder, than outside_filter will appear when in big screen ***/
function show_filter() {
	$("#outside_filter").addClass("outside_filter_fixed");
	$("#filter_ul").addClass("filter_ul_fixed");
	$("#filter_blank").css("height", "40.5px" );
}
function hide_filter() {
	$("#outside_filter").removeClass("outside_filter_fixed");
	$("#filter_ul").removeClass("filter_ul_fixed");
	$("#filter_blank").css("height", "0");
}
$("#outside_folder").mouseenter(function(){
    if ($(window).scrollTop() > 50 && nRow > 4) {
		show_filter();
	}
});
$("#outside_filter").mouseenter(function(){
    if ($(window).scrollTop() > 50 && nRow > 4) {
		show_filter();
	}
});
$("#outside_folder").mouseleave(function(){
	hide_filter();
});
$("#outside_filter").mouseleave(function(){
	hide_filter();
});

/*** generate nRow (the number of boxes of row) by the screen size ***/
function generate_nRow() {
	var winWidth  = $(window).outerWidth();
	if(winWidth < 400) {
		nRow = 2;
	} else if((400 <= winWidth) && (winWidth < 550)) {
		nRow = 3;
	} else if((550 <= winWidth) && (winWidth < 768)) {
		nRow = 4;
	} else if((768 <= winWidth) && (winWidth < 982)) {
		nRow = 5;
	} else {
		nRow = 6;
	}
}
function adjust_box_width() {
	generate_nRow();
	$(".box").css("width", 100 / nRow + "%");
	// $(".box").css("width", ($(".container").width() - 30) / nRow * 0.9 + "px");
}

// fix another navbar onto top when scroll down
function test_scroll() {
	if ($(window).scrollTop() > 50) {
		$('nav').removeClass('navbar-fixed-top');
		$('#outside_folder').addClass('outside_folder_fixed');
		$('#outside_folder').css({
			"background-color": "black",
			"color": "#CCCCCC"
		});
		$("#go_to_top").css("display", "inline-block");
	} else {
		$('nav').addClass('navbar-fixed-top');
		$('#outside_folder').removeClass('outside_folder_fixed');
		$('#outside_folder').css({
			"background-color": "",
			"color": ""
		});
		$("#outside_filter").removeClass("outside_filter_fixed");	// to fix "show_filter()" function when go to top
		$("#go_to_top").css("display", "none");
	}
}

/*** WINDOW RESIZE ***/
$(window).resize(function(){
	generate_nRow();
	if(nRow > 4) {
		adjust_box_width();
		$("#outside_folder").css("height", $("nav").css("height") );
		test_scroll();
		// lightbox-part
		if( $("#lightbox_shadow_0").css("display") == "block" ) {
			$("#lightbox_shadow_0").css("display", "none");
			$("#lightbox_0").css("display", "none");
			$("body").css("overflow", "auto");	// enable scroll
		}

		if(typeof BOX_SEL !== 'undefined') {
			var id = BOX_SEL.attr("data-lightbox");
			lightbox_zoomin(id);	// resize lightbox
		}
	}
});

/*** SCROLL ***/
$(window).scroll(function(){
	if(nRow > 4) {
		generate_nRow();
		test_scroll();
	}
});
$("#go_to_top").click(function() {
	$("html, body").clearQueue().animate({scrollTop: 0}, 500);
	// actually, below will be done by test_scroll() automatically if the cursor move a bit
	$("#outside_filter").removeClass("outside_filter_fixed");
	$("#filter_ul").css("background-color", "");
	$("#filter_blank").css("height", "0");
});

/*** INIT ***/
get_part(1);
var nRow;
generate_nRow();
