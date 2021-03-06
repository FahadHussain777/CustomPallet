require(['jquery','jquery/ui'],function($,ui) {
    $('.face_swatch').draggable({
        cancel: "a.ui-icon",
        revert: true,
        helper: "clone",
        cursor: "move",
        revertDuration: 0,
		start: function( event, ui ) {
            $(this).addClass("face-swatch-drg");
			var getAttr = $( this ).attr("option-label");
			$('.pallates-switcher .swatch-attribute-label').append( '<span class="clr-name">'+getAttr+'</span>' );
         },
		stop: function( event, ui) {
			$(this).removeClass("face-swatch-drg");
			$('.pallates-switcher .swatch-attribute-label .clr-name').remove();
		}
    });
    $("div.circle_face").droppable({
        accept: ".face_swatch",
        activeClass: "ui-state-highlight",
        drop: function( event, ui ) {
            // clone item to retain in original "list"
            var $item = ui.draggable.clone();
            $(this).addClass('dropped');
            var id = $(this).attr('id');            
            $('input#'+id).val($item.attr('option-label'));
            $(this).css("background-image","url("+ $item.attr('option-tooltip-thumb') +")");
        }
    });

    $('.eye_swatch').draggable({
        cancel: "a.ui-icon",
        revert: true,
        helper: "clone",
        cursor: "move",
        revertDuration: 0,
		start: function( event, ui ) {
            $(this).addClass("eye-swatch-drg");
			var getAttr = $( this ).attr("option-label");            
			$('.pallates-switcher .swatch-attribute-label').append( '<span class="clr-name">'+getAttr+'</span>' );
         },
		stop: function( event, ui) {
			$(this).removeClass("eye-swatch-drg");
			$('.pallates-switcher .swatch-attribute-label .clr-name').remove();
		}
    });
    $("div.circle_eye").droppable({
        accept: ".eye_swatch",
        activeClass: "ui-state-highlight",
        drop: function( event, ui ) {
            // clone item to retain in original "list"
            var $item = ui.draggable.clone();
            $(this).addClass('dropped');
            var id = $(this).attr('id');
            $('input#'+id).val($item.attr('option-label'));            
            $(this).css("background-image","url("+ $item.attr('option-tooltip-thumb') +")");
        }
    });

    /* Check whether all options are drop before add to cart*/
    $('#product-addtocart-button-clone').on('click',function () {
        var status = 1;
        $( "div.circle_face" ).each(function(index){
            if($(this).hasClass('dropped')) return true;
            status=0;
        });
        $( "div.circle_eye" ).each(function(index){
            if($(this).hasClass('dropped')) return true;
            status=0;
        });
        if(status){
            $('#product-addtocart-button').click();
        }
        else $("#pallet-error").show().delay(2500).fadeOut();
    });

    /* Check whether all options are drop before Update cart*/
    $('#product-updatecart-button-clone').on('click',function () {
        var status = 1;
        $( "div.circle_face" ).each(function(index){
            if($(this).hasClass('dropped')) return true;
            status=0;
        });
        $( "div.circle_eye" ).each(function(index){
            if($(this).hasClass('dropped')) return true;
            status=0;
        });
        if(status) {
            $('#product-updatecart-button').click();
        }
        else $("#pallet-error-update").show().delay(2500).fadeOut();
    });

    $('div[class^="circle_"]').mouseover(function (e) {
        if($(e.target).css('background-image')!= 'none'){
            $(e.target).find('img').css('visibility','visible');
            $(e.target).find('img').on('click',function () {
                $(this).parent().css('background-image','none');
                $(this).siblings('input').val('');
                $(this).parent().removeClass('dropped');
            });
        }
    });

    $('div[class^="circle_"]').mouseleave(function (e) {
        $(e.target).find('img').css('visibility','hidden');
    })
});