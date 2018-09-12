/* 
 * The two function make animated fade-in & fade-out.
 * But the two animations are more fluently than the ones in Jquery.
 */

// The initial OPACITY should be "0" !
// usage: fade_in( $("#lightbox_shadow"), "block", "0.4", "0.5s");
function fade_in(selector, display, opacity, during_time) {
	var transition_set = "opacity " + during_time + " ease";
	selector.css({
		"-webkit-transition":	transition_set,
		"-moz-transition":		transition_set,
		"transition":			transition_set,
	});
	selector.css("display", display).delay(0).queue(function(){
		$(this).css("opacity", opacity);
		$(this).dequeue();
	});
}

// usage: fade_out( $("#lightbox_shadow"), "0.5s");
function fade_out(selector, during_time) {
	var transition_set = "opacity " + during_time + " ease";
	selector.css({
		"-webkit-transition":	transition_set,
		"-moz-transition":		transition_set,
		"transition":			transition_set,
	});
	// selector.css("opacity", "0");
	selector.css("opacity", "0").delay(during_time).queue(function(){
		$(this).css("display", "none");
		$(this).dequeue();
	});
}
