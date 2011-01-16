$(document).ready(function() {
		
	$("form :submit, form :button").hover(
		function() {
			$(this).animate({ backgroundColor : "#555", color: '#fff' }, 400)			
		},
		function() {
			$(this).animate({ backgroundColor : "#eee", color: '#555' }, 400);
	});
		
	
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
			console.log("pageimage");
			$("#domanual").hide(400);
			$("#choosefile").show(400);
		}
		else if($("#hpselect").val() == 'manual') {
		console.log("nam");
			$("#choosefile").hide(400);
			$("#domanual").show(400);
		}
	}
    $("select").change(changeHomepageOptions);
    changeHomepageOptions();
	
	

});