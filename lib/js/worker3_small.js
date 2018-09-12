/*** CLICK ***/
// function pop_filter_click(id) {
$('body').on('click', '.pop_filter', function () {
	// change active class
	if(! $(this).hasClass("active") ) {
		$(".pop_filter[class ~= 'active']").removeClass("active");
		$(this).addClass("active");
		$("#small_filter").text( $(this).text() );
	}
	var id = $(this).attr("id").slice(12);
	var thisid = $("#part_id_" + id);
	if(! thisid.hasClass("active") ) {
		$(".filter[class ~= 'active']").removeClass("active");
		thisid.addClass("active");
	}
	// scroll to that section_id
	$("html, body").clearQueue().animate({ scrollTop: $("#section_" + id).offset().top - 70 }, 500);
	// and then hide lightbox
	setTimeout(function(){ lightbox_hide(); }, 500);
});

var NOW_CATG_ID = 1;
$(".pop_filter_folder").click(function(){
	// change active class
	if(! $(this).hasClass("active") ) {
		$(".pop_filter_folder[class ~= 'active']").removeClass("active");
		$(this).addClass("active");
		$("#small_folder").text( $(this).text() );
	}
	var catg_id = $(this).attr("id").slice(11);
	NOW_CATG_ID = catg_id;
	var thisid = $("#folder_" + catg_id);
	if(! thisid.hasClass("active") ) {
		$(".filter_folder[class ~= 'active']").removeClass("active");
		thisid.addClass("active");
	}

	if(catg_id == "") {
		// TODO: search
	} else {
		// console.log( catg_id );
		$("#content").empty();
		get_part(catg_id);		// get the ajax again
	}
});

// fix another navbar onto top when scroll down
function test_scroll_small() {
	var winScroll = $(window).scrollTop();
	if (winScroll) {
		$('nav').removeClass('navbar-fixed-top');
		$("#small_all_filter").addClass("small_all_fixed");
		$("#pop_go_to_top").css("display", "block");
	} else {
		$('nav').addClass('navbar-fixed-top');
		$("#small_all_filter").removeClass("small_all_fixed");
		$("#pop_go_to_top").css("display", "none");
	}

	var num_part = part_local[NOW_CATG_ID].length;
	for(var i = 0; i < num_part; ++i) {
		part_id = part_local[NOW_CATG_ID][i].part_id;
		if( winScroll < $("#section_" + part_id).offset().top + $("#section_" + part_id).outerHeight() ) {
			$("#small_filter").text( part_local[NOW_CATG_ID][i].caption );
			break;
		}
	}
}

/*** WINDOW RESIZE ***/
$(window).resize(function(){
	generate_nRow();
	if(nRow <= 4) {
		adjust_box_width();
		test_scroll_small();

		if(typeof BOX_SEL !== 'undefined') {
			var id = BOX_SEL.attr("data-lightbox");
			lightbox_zoomin(id);	// resize lightbox
		}
	}
});

/*** SCROLL ***/
$(window).scroll(function(){
	generate_nRow();
	if(nRow <= 4) {
		test_scroll_small();
	}
});
$("#pop_go_to_top").click(function() {
	$("html, body").clearQueue().animate({ scrollTop: 0}, 500);
	setTimeout(function(){ lightbox_hide(); }, 500);
});

/*** LIGHTBOX ***/
function lightbox_size_0() {
	// before pop-out, set overflow to be hidden
	$("#lightbox_0>#pop_folder").css("overflow", "hidden");
	$("#lightbox_0>#pop_filter").css("overflow", "hidden");

	// after pop-out, let overflow be initial
	setTimeout(function(){
		$("#lightbox_0>#pop_folder").css("overflow", "");
		$("#lightbox_0>#pop_filter").css("overflow", "");
	}, 300);

	// calculate window's width
	var winWidth = $(window).outerWidth();
	var winHeight = $(window).outerHeight();

	var width = winWidth * 0.8;
	width = (width < 400)? width: 400;

	// set up actual pop-out height
	var num1 = $("#pop_folder li").length;
	if ($(window).scrollTop() <= 50) { num1 -= 1; }
	var num2 = $("#pop_filter li").length;

	var height = (num1 > num2)? num1: num2;
	height *= $("#pop_filter li").outerHeight();
	height += 40;

	if(height > winHeight * 0.9) {
		height = winHeight * 0.9;
		console.log(height + ", " + winHeight );
	}
	
	return {
		"width" :	width + "px",
		"height" :	height + "px",
		"left" :	($(window).outerWidth() - width) / 2 + "px",
		"top" :		($(window).outerHeight() - height) / 2 + "px"
	};
}
function lightbox_beforehide_0() {
	$("#lightbox_0>#pop_folder").css("overflow", "hidden");
	$("#lightbox_0>#pop_filter").css("overflow", "hidden");
}
function lightbox_afterhide_0() {
	adjust_box_width();
	$("#lightbox_0>#pop_folder").css("overflow", "");
	$("#lightbox_0>#pop_filter").css("overflow", "");
}
