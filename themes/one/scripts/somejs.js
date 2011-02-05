$(document).ready(function() {

	$(".showinfo").click(function() {
				
		if($(this).next().is(':visible')) {
			$(this).next().hide(400);
		}
		else {
			$(this).next().show(400);
		}
	});
	
	console.log("aa");
	
});