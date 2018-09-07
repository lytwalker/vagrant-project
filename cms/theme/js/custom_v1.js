//overlay
function showAlert(title, message, link)
{
    $('.overlay h6').text(title);
    $('.overlay .message').html(message);
    $('.overlay td').css('height', $('.overlay').height());
    $('.overlay').fadeIn();
    
    if(link){
    	$('.overlay .ok').text("Delete");
    	
    	$('.overlay .cancel').click(function () {
	        $('.overlay').hide();
	    });
    	
    	$('.overlay .ok').click(function () {
	        window.location.href = link;
	    });
    	
    }else{
    	$('.overlay .cancel').hide();
    	 $('.overlay').click(function () {
	        $(this).hide();
	    });
    }
   
}

$("document").ready(function(){
	$(".action a.delete").click(function(e){
		e.preventDefault();
		var $_this = $(this);
		showAlert("", "Do you really want to <strong>delete "+ $_this.attr('data-name') + "</strong>?", $_this.attr('href'));
	});
	
	
	$("td.decomissioned span").click(function(){
		var $_this =$(this);
		window.location.href = "change_base_status.php?id="+ $_this.parent().attr("data-id") +"&status="+$_this.attr("class");
	});
	
	
	//data tables
	$("table").DataTable();
});