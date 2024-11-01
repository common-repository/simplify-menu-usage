(function($){
    'use strict';
    
    var $document = $(document);
    
    function toggleInfoSection(elm){
        elm.toggleClass('open');
        var parent = elm.closest('.simplify-menu-usage.accordion-wrapper');
        if(elm.hasClass('open')){
            var scrollHeight = parent.find('.simplify-menu-usage.content-wrapper').prop('scrollHeight');
            parent.find('.simplify-menu-usage.content-wrapper').height(scrollHeight); 
        }else{
             parent.find('.simplify-menu-usage.content-wrapper').height(0); 
         }
    }
    
    $document.ready(function(){
        $('.downwards-insert-description').on('click', function(){
            toggleInfoSection($(this));
        });
    });
    
})(jQuery);

