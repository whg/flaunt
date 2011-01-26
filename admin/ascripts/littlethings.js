$(document).ready(function() {
	
	//just something we could do with css....
	$("form :submit, form :button").hover(				
		function() {
			$(this).css('backgroundColor', '#444');
			$(this).css('color', '#eee');
			$(this).css('borderColor', '#444');
		},
		function() {
			$(this).css('backgroundColor', '#eee');
			$(this).css('color', '#444');
			$(this).css('borderColor', '#ddd #bbb #999');
		}
	);
		
	
	//show children of menu...	
	$("#edit, .expand").click(function() {
		if($(this).next().is(':visible')) {
			$(this).next().hide(400);
		}
		else {
			$(this).next().show(400);
		}
	});
	
	function changeHomepageOptions() {
		if($("#hpselect").val() == 'page' || $("#hpselect").val() == 'image') {
			$("#domanual").hide(400);
			$("#choosefile").show(400);
		}
		else if($("#hpselect").val() == 'manual') {
			$("#choosefile").hide(400);
			$("#domanual").show(400);
		}
	}
    $("select").change(changeHomepageOptions);
	
	$(function() {
		$("#menusorter").sortable();
		$("#menusorter").disableSelection();
	});
	
	$("#menusorter").mouseleave(function(){
		$("input:hidden").remove();
		var no = 1;
		$(this).children().each(function(){
			$('<input>').attr({
			    type: 'hidden',
			    name: no++,
			    value: $(this).html()
			}).appendTo('form');

		});
	});
	
/*
	$("#menusorter").mouseleave(function(){
		var no = 1;
		$(this).children().each(function(){
			console.log("aaa");
			var na = $(this).html();
			if(no == $("input").attr("name")) {
				$(this).attr("value", na);
			}
			no++;
		
		});
	});
*/
	
	
	

});