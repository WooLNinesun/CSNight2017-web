/*
 * This javascript file does lightbox pop-out and pop-in effect.
 * And it require "jquery" & "fadeInOut.js" & "lightbox.css", you should link them in HTML file.
 *
 * Usage:
 * 		1. Add ".lightbox_click" & "data-lightbox='{number}'" into the object which you want it to trigger the lightbox effects.
 * 		2. Add a new div with ".lightbox_shadow" & "#lightbox_shadow_{number}" on the end.
 * 		3. Add a new div with ".lightbox_pop" and "#lightbox_{number}" on the end.
 * 		*  Notice: There can be MORE THAN ONE ".lightbox_pop" & ".lightbox_shadow"in one page,
 * 				and in that situation, you can specify different {number} to trigger different lightbox.
 * 		4. You have to add a FUNCTION whose name is "lightbox_size_{number}" javascript, which should return an object.
 * 		   And that object should specify the size ( width / height / left / top ) of the lightbox.
 * 		   For example,
 * 				function lightbox_size_1() {
 * 					return {
 * 						"width" :	"50%",
 * 						"height" :	"400px",
 * 						"left" :	"24.5%",
 * 						"top" :		($(window).height() - 400) / 2 + "px"
 * 						};
 * 				}
 *
 * 		*  [Additional setting]
 * 		1. Add ".lightbox_hide" into an object, then when it is clicked, the lightbox will hide.
 *
 * 		@author: YJC
 * 		@date: 2017/2/27
 */

function lightbox_zoomin(id) {
	var new_size = window["lightbox_size_" + id]();
	LIGHTBOX_SEL.css({
		"width" :	new_size.width,
		"height" :	new_size.height,
		"left" :	new_size.left,
		"top" :		new_size.top
	});
}

// TODO: change selector to selector_str
function lightbox_zoomout(selector) {
	var offset = selector.offset();
	var posX = offset.left - $(window).scrollLeft(); 
	var posY = offset.top - $(window).scrollTop();
	var ori_width = selector.outerWidth();
	var ori_height = selector.outerHeight();

	LIGHTBOX_SEL.css({
		"left" :	posX + "px",
		"top" :		posY + "px",
		"width" :	ori_width + "px",
		"height" :	ori_height + "px",
	});
}

var BOX_SEL;
var LIGHTBOX_SEL;

$('body').on('click', '.lightbox_click', function () {

	BOX_SEL = $(this);
	var id = BOX_SEL.attr("data-lightbox");
	if( $.isNumeric(id) ) {
		LIGHTBOX_SEL = $("#lightbox_" + id);

		$("body").css("overflow", "hidden");	// disable scroll
		var transition_set = "width 0.3s ease, height 0.3s ease, top 0.3s ease, left 0.3s ease";

		lightbox_zoomout(BOX_SEL);	// go to old position

		LIGHTBOX_SEL.css({
			"display" :	"block",
			"-webkit-transition":	transition_set,
			"-moz-transition":		transition_set,
			"transition":			transition_set,
		});

		var beforeshow = window["lightbox_beforeshow_" + id];
		if(typeof beforeshow !== 'undefined') {
			beforeshow();
		}

		setTimeout(function(){
			fade_in( $("#lightbox_shadow_" + id), "block", "0.4", "0.3s");
			lightbox_zoomin(id);	// go to new position
		}, 5);
	} else {
		console.log( "The lightbox_id: '" + id + "' is wrong !" );
	}
});

function lightbox_hide(id) {
	// this will run 'lightbox_beforehide_{id}' before the pop-out start to hide
	var beforehide = window["lightbox_beforehide_" + id];
	if(typeof beforehide !== 'undefined') {
		beforehide();
	}

	// fade_out( $("#lightbox_shadow_" + id), "0.3s");
	fade_out( $(".lightbox_shadow"), 300);
	lightbox_zoomout(BOX_SEL);
	setTimeout(function(){
		LIGHTBOX_SEL.css({
			"display" :	"none",
			"-webkit-transition":	"",
			"-moz-transition":		"",
			"transition":			"",
		});
	}, "300");
	$("body").css("overflow", "auto");	// enable scroll

	// this will run 'lightbox_afterhide_{id}' after the pop-out hide complete
	setTimeout(function(){
	var afterhide = window["lightbox_afterhide_" + id];
		if(typeof afterhide !== 'undefined') {
			afterhide();
		}
	}, "300");
}
$('body').on('click', '.lightbox_shadow', function () {
	lightbox_hide( $(this).attr("id").slice(16) );
});
$('body').on('click', '.lightbox_hide', function () {
	lightbox_hide();
});
