$(document).ready(function(){

//Hide Show Menu
$("#signup").click(function(){
	$("#first").slideUp("slow", function(){
		$("#second").slideDown("slow");
	});
});

//Hide Show Menu
$("#signin").click(function(){
	$("#second").slideUp("slow", function(){
		$("#first").slideDown("slow");
	});
});

});