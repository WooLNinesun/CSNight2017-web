/* this javascript file contains only ajax part */

var worker_local = [];
var hrm_local = [];
var part_local = [];

function load_worker_detail(worker) {
	$("#pop_inside_text h2").text(worker['name']);
	$("#pop_inside_text h3").text(worker['self_part']);

	// 'self_intro' part
	if( worker['self_intro'] == "" ) {
		$("#pop_inside_text p").css("display", "none");
	} else {
		$("#pop_inside_text p").css("display", "block");
		$("#pop_inside_text .underline").html(worker['self_intro']);
	}

	// 'img_url' part & have been click
	setTimeout(function(){
		$("#pop_img #big_img").css("background-image", "url('" + worker['img_url'] + "')");
		$(".box>.worker_id_" + worker['worker_id']).css("background-color", "#EEEEEE");
		$(".box>.worker_id_" + worker['worker_id']).css("color", "#888888");
	}, 300);
}
var RELOAD_ALL_WORKER = 0;
function get_worker_detail(worker_id) {
	if(typeof worker_local[worker_id]['img_url'] !== 'undefined') {
		load_worker_detail(worker_local[worker_id]);
	} else {
		$.ajax({
			url: 'worker_ajax.php',
			type: 'GET',	// what way to send data, another is GET
			data: {
				"worker_id": worker_id,
				"detail": "yes"
			},
			dataType: 'json',
			cache: false,	// close browser cache
			error: function(xhr) { console.log(xhr); },
			success: function(response) {
				if (response.mode == 'success') {
					var worker = $.parseJSON(response.worker);

					worker_local[worker_id]['worker_id']= worker_id;
					worker_local[worker_id]['name']		= worker['name'];
					worker_local[worker_id]['img_url']	= worker['img_url'];
					worker_local[worker_id]['self_part']= worker['self_part'].replace(/\n/g, "、");

					// process 'self_intro' part
					if(typeof worker['self_intro'] !== 'object') {
						worker_local[worker_id]['self_intro'] = worker['self_intro'].replace(/\n/g, "<br />");
					} else {
						worker_local[worker_id]['self_intro'] = "";
					}

					// process 'reload_all_worker' part
					if(typeof worker['reload_all_worker'] !== 'undefined') {
						delete worker['reload_all_worker'];
						RELOAD_ALL_WORKER = 1;
					}

					load_worker_detail(worker_local[worker_id]);
				} else {
					console.log(xhr);
				}
				// alert(response.msg);
			}
		});
	}
}
function load_worker(selector_str, worker) {
	jQuery('<div/>', {
		"class": "worker_img",
		"style": "background-image:url('" + worker['small_img_url'] + "')",
		"title": worker['name'] + "的照片"
	}).appendTo(selector_str);

	jQuery('<div/>', { class: "text" }).appendTo(selector_str);
	jQuery('<h3/>', { text: worker['name'] }).appendTo(selector_str + " .text");

	if( typeof worker['worker_id'] !== 'undefined' ) {
		$(".box>.worker_id_" + worker['worker_id']).css("background-color", "#EEEEEE");
		$(".box>.worker_id_" + worker['worker_id']).css("color", "#888888");
	}

	adjust_box_width();
}
function get_worker(selector_str, worker_id) {
	if( typeof worker_local[worker_id] !== 'undefined' ) {
		load_worker(selector_str, worker_local[worker_id]);
	} else {
		$.ajax({
			url: 'worker_ajax.php',
			type: 'GET',	// what way to send data, another is GET
			data: { "worker_id": worker_id },
			dataType: 'json',
			cache: false,	// close browser cache
			error: function(xhr) { console.log(xhr); },
			success: function(response) {
				if (response.mode == 'success') {
					var worker = $.parseJSON(response.worker);
					// worker_local[worker_id] = { small_img_url: worker['small_img_url'], name: worker['name'] };
					worker_local[worker_id] = worker;
					load_worker(selector_str, worker_local[worker_id]);
				} else {
					console.log(xhr);
				}
				// alert(response.msg);
			},
		});
	}
}
function load_box(part_id, hrm) {
	var section_str = "#section_" + part_id;

	var num_hrm = hrm.length;
	for(var i = 0; i < num_hrm; ++i) {
		var worker_id = hrm[i];
		jQuery('<div/>', { class: "box worker_id_" + worker_id }).appendTo(section_str + " .outside_box");
		jQuery('<div/>', {
			class: "lightbox_click inside_box worker_id_" + worker_id,
			"data-lightbox": 1
		}).appendTo(section_str + " .worker_id_" + worker_id);
		var selector_str = section_str + " .box .worker_id_" + worker_id;
		get_worker(selector_str, worker_id);
	}
}
function get_hrm(part_id) {
	if( typeof hrm_local[part_id] !== 'undefined' ) {
		load_box(part_id, hrm_local[part_id]);
	} else {
		$.ajax({
			url: 'worker_ajax.php',
			type: 'GET',	// what way to send data, another is GET
			data: { "part_id": part_id },
			dataType: 'json',
			cache: false,	// close browser cache
			error: function(xhr) { console.log(xhr); },
			success: function(response) {
				if (response.mode == 'success') {
					var hrm = $.parseJSON(response.hrm);

					// change hrm priority members to intiger
					for(var i = 0; i < num_hrm; ++i) {
						hrm[i]['priority'] = parseInt( hrm[i]['priority'] );
					}

					// sort the hrm
					hrm.sort(function(a, b){
						if(a['priority'] == 0) {
							return 1;
						} else if(b['priority'] == 0) {
							return -1;
						} else {
							return a['priority'] - b['priority'];
						}
					});

					// remove the same worker_id
					hrm_local[part_id] = [];
					var workerId_added = [];
					var num_hrm = hrm.length;
					for(var i = 0; i < num_hrm; ++i) {
						var worker_id = hrm[i]['worker_id'];
						if(workerId_added[worker_id] != 1) {
							workerId_added[worker_id] = 1;
							hrm_local[part_id].push(worker_id);
						}
					}

					load_box(part_id, hrm_local[part_id]);
				} else {
					console.log(xhr);
				}
				// alert(response.msg);
			},
		});
	}
}
function load_section(catg_id, part) {
	// Make from section to outside_box
	var num_part = part.length;
	for(var i = 0; i < num_part; ++i) {
		var part_id = part[i]['part_id'];
		jQuery('<div/>', { id: "section_" + part_id, class: "section", }).appendTo("#content");
		var section_str = "#section_" + part_id;
		jQuery('<div/>', { class: "cover", style: "background-image:url('" + part[i].img_url + "')" }).appendTo(section_str);
		jQuery('<div/>', { class: "container" }).appendTo(section_str);
		jQuery('<h2/>', { html: "<span class='catg'>" + $("#folder_" + catg_id).text() + " # </span>" + part[i].caption }).appendTo(section_str + " .container");
		jQuery('<div/>', { class: "outside_box" }).appendTo(section_str + " .container");
		get_hrm(part_id);
	}
}
function load_pop_filter(part) {
	var pop_filter_ul = $("#pop_filter_ul");
	var small_filter = $("#small_filter");
	pop_filter_ul.empty();
	var num = part.length;
	if(num == 0) {
		small_filter.html( "&nbsp;" );
	} else {
		small_filter.text( part[0].caption );
	}
	for(var i = 0; i < num; ++i) {
		jQuery('<li/>', {
			id: "pop_part_id_" + part[i].part_id,
			class: "pop_filter",
			text: part[i].caption
		}).appendTo("#pop_filter_ul");
	}
	if(num != 0) {
		$("#pop_part_id_" + part[0].part_id).addClass("active");
	}
}
var FIRST_RUN = 1;
function load_filter(part) {
	var filter_ul = $("#filter_ul");
	filter_ul.empty();
	var num = part.length;
	for(var i = 0; i < num; ++i) {
		jQuery('<li/>', {
			id: "part_id_" + part[i].part_id,
			class: "filter",
			text: part[i].caption
		}).appendTo("#filter_ul");
	}
	if(num != 0) {
		$("#part_id_" + part[0].part_id).addClass("active");
	}
	if(FIRST_RUN == 1 && nRow <= 4) {
		$("#small_all_filter").trigger("click");
		FIRST_RUN = 0;
	}
}
function get_part(catg_id) {
	if( typeof part_local[catg_id] !== 'undefined' ) {
		load_filter(part_local[catg_id]);
		load_pop_filter(part_local[catg_id]);
		load_section(catg_id, part_local[catg_id]);
		if(nRow <= 4) {
			$("#small_all_filter").trigger("click");
		}
	} else {
		$.ajax({
			url: 'worker_ajax.php',
			type: 'GET',	// what way to send data, another is GET
			data: { "catg_id": catg_id },
			dataType: 'json',
			cache: false,	// close browser cache
			error: function(xhr) { console.log(xhr); },
			success: function(response) {
				if (response.mode == 'success') {
					var part = $.parseJSON(response.part);

					part_local[catg_id] = part;
					// var num_part = part.length;
					// for(var i = 0; i < num_part; ++i) {
						// part_local[catg_id][i] = part[i];
					// }

					load_filter(part_local[catg_id]);
					load_pop_filter(part_local[catg_id]);
					load_section(catg_id, part_local[catg_id]);
					if(nRow <= 4) {
						$("#small_all_filter").trigger("click");
					}
				} else {
					console.log(xhr);
				}
				// alert(response.msg);
			},
		});
	}
}
