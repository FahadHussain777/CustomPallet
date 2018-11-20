define([
    'jquery',
    'jquery/ui'
], function($) {
    "use strict";

    $.widget('Ec.palletUpdate', {
        options: {
            'productOptions': null,
            'palletType': null
        },

        _init: function() {
            switch (this.options.palletType) {
                case '4wellPalette':
                    var face_shades = this.options.productOptions[2].value;
                    face_shades = face_shades.split(",");
                    break;
                case '6wellPalette':
                    var eye_shades = this.options.productOptions[1].value;
                    eye_shades = eye_shades.split(",");
                    break;
                case 'lgPalette':
                case 'MK07-1Nudes':
                    var eye_shades = this.options.productOptions[1].value;
                    eye_shades = eye_shades.split(",");
                    var face_shades = this.options.productOptions[2].value;
                    face_shades = face_shades.split(",");
                    break;
            }
            if(typeof eye_shades !== 'undefined'){
                $('div.pallet-image').find('div[id*="eye"]').each(function (index) {
                    var well_div = this;
                    var id = $(well_div).attr('id');
                    var swatch_image =".eye_swatch[option-label='"+$.trim(eye_shades[index])+"']";
                    var image_url = $(swatch_image).attr('option-tooltip-thumb');
                    $('input#'+id).val(eye_shades[index]);
                    $(well_div).addClass('dropped')
                        .css('background-image', 'url(' + image_url + ')');
                });
            }
            if(typeof face_shades !== 'undefined'){
                $('div.pallet-image').find('div[id*="face"]').each(function (index) {
                    var well_div = this;
                    var id = $(well_div).attr('id');
                    var swatch_image =".face_swatch[option-label='"+$.trim(face_shades[index])+"']";
                    var image_url = $(swatch_image).attr('option-tooltip-thumb');
                    $('input#'+id).val(face_shades[index]);
                    $(well_div).addClass('dropped')
                        .css('background-image', 'url(' +image_url+ ')');
                });
            }
        }

    });
    return $.Ec.palletUpdate;
});