function justify_livebox() {
	if( $("body").css("overflow") == "hidden") {
		var height = $(window).height();
		var width  = $(window).width();
		if(width < height) {
			$("#livebox").css({"width": "90%", "margin":"0px 4.5% 0px 4.5%", "height": (height * 0.8) + "px", "top": (height * 0.1) + "px"});
			$("#livebox .livebox_pic").css({"display": "block", "height": (width * 0.6) + "px", "width": "100%"});
			$("#livebox .livebox_text").css({"display": "block", "height": (height*0.8 - width*0.6)*0.94 + "px", "width": "100%"});
		} else if(width < 768) {
			$("#livebox").css({"width": "90%", "margin":"0px 4.5% 0px 4.5%", "height": (width * 9 / 20) + "px", "top": (height - (width * 9 / 20)) / 2 + "px"});
			$("#livebox .livebox_pic").css({"display": "inline-block", "height": (width * 9 / 20) + "px", "width": "75%"});
			$("#livebox .livebox_text").css({"display": "inline-block", "height": "100%", "width": "25%"});
		} else {
			$("#livebox").css({"width": "85%", "margin":"0px 7.4% 0px 7.4%", "height": (width / 2 * 0.85) + "px", "top": (height - (width / 2 * 0.85)) / 2 + "px"});
			$("#livebox .livebox_pic").css({"display": "inline-block", "height": (width / 2 * 0.85) + "px", "width": "75%"});
			$("#livebox .livebox_text").css({"display": "inline-block", "height": "100%", "width": "25%"});
		}
	}
}

$(window).resize(function(){
	justify_livebox();
});

function opacity_after_queue(selector, opacity) {
	$(selector).css("opacity", opacity);
	$(selector).dequeue();
}
function hide_after_queue(selector) {
	$(selector).css("display", "none");
	$(selector).dequeue();
}
$(".banner").click(function(){
	$("#livebox + .livebox_shadow").css("display", "block").delay(0).queue(function(){opacity_after_queue(this, "0.5")});
	$("#livebox").css("display", "block").delay(0).queue(function(){opacity_after_queue(this, "1")});
	var id = $(this).attr("id").slice(7);
	$("#livebox .fill").attr("style", "background-image:url('" + part[id].img_url + "')");
	$("#livebox h2").html(part[id].caption);
	$("#livebox p").html(part[id].intro);
	$("body").css("overflow", "hidden");	// disable scroll
	justify_livebox();
});
$(".livebox_shadow").click(function(){
	$("#livebox").css("opacity", "0").delay(300).queue(function(){hide_after_queue(this)});
	$(this).css("opacity", "0").delay(300).queue(function(){hide_after_queue(this)});
	$("body").css("overflow", "auto");	// enable scroll
});
