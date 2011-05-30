$(document).ready(function() {

	/* I know this is criminal; pulling in the whole 
	   jQuery lib just for hide and show... 
	   ... but I'm lazy */
	$(".showinfo").click(function() {
				
		if($(this).next().is(':visible')) {
			$(this).next().hide(400);
		}
		else {
			$(this).next().show(400);
		}
	});
	
	
});