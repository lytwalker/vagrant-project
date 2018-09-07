(function($){ 
    
    $('document').ready(function(){       
    	//show details after click or map search
        window.showDetails = function(){
            if($('.formElements').not(':visible')){
                var top = $('header .container').height() + 12;
                var left = $('header .container h3').offset().left + 15;
                setTimeout(function(){
                    $('.formElements')
                    .css({'top': top, 'left': left})
                    .fadeIn('slow');
                }, 1000);            
            }        
        }
        
    });    
})(jQuery)