jQuery(function($){
    
    var row = $('#slide-row'), cell = $('.slide-cell'), interval, index = 1, length = cell.length;

    function size(){
        var width = row.width();
        var height = width / 4;
        row.height(height-3);
        cell.height(height-3);
    }
    function style(){ 
        if(length > 0){
            cell.hide();
            row.append('<div class="slide-nav slide-prev"></div>');   
            row.append('<div class="slide-nav slide-next"></div>');
            row.append('<div class="slide-shadow"></div>');         
        }   
    }
    function slides(speed){
        if(typeof speed === 'undefined'){
            speed=2000;
        };
        if(index == 0){
            index = length;
        }
        if(index > length){
            index = 1;
        }
        cell.fadeOut(speed);
        cell.filter(':nth-child(' + index  + ')').fadeIn(speed);
        index++;          
    }
    
    $(document).ready(function(){
        size();
        style();
        slides();
        interval = setInterval(slides, 6000);
    }); 
    
    $(window).resize(size);    
    
  
    $(document).on('click','.slide-prev',function(){  
        index = index - 2;
        slides(1000); 
    });

    $(document).on('click','.slide-next',function(){  
        slides(1000); 
    });     
 
    $(document).on( 'mouseenter','.slide-nav', function() {
        clearInterval(interval);
    }).on( 'mouseleave','.slide-nav', function() {
        interval = setInterval(slides, 6000);
    });
})    
 