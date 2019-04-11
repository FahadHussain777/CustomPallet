define([
    'jquery'
], function($) {
    "use strict";

    $.widget('Ec.switcher', {

        _init: function() {
            var $widget = this;
            var widgets = $('.swatch-attribute-options.clearfix');
            if(widgets.length == 1 ){
            	var swatch = $widget.element.children('div:first-child');
            	if(swatch.hasClass('eye_swatch')){
            		var _switch = $('div.eye_swatch_switch');
            		_switch.addClass('active');
            	}
            	else{
            		var _switch = $('div.face_swatch_switch');
            		_switch.addClass('active');                
            	}
            }
            var swatch = $widget.element.children('div:first-child');            
            var label = $('label.label[for="select_'+swatch.attr('option-id')+'"]');
            if(swatch.hasClass('eye_swatch')){
                var opposite = 'face_swatch';
                var _switch = $('div.eye_swatch_switch');
                _switch.css('display','unset');
				//_switch.addClass('active');
                this._setEvent(label,_switch,opposite);
            }else if(swatch.hasClass('face_swatch')){
                var _switch = $('div.face_swatch_switch');
                var opposite = 'eye_swatch';
                _switch.css('display','unset');
                this._setEvent(label,_switch,opposite);
            }
            this._hideInitial();
        },

        _setEvent: function (label,_switch,opposite) {
            var widget = this;
            var oppositeswatch = $('div.swatch-attribute-options.clearfix').children('div.'+opposite+':first-child');
            var oppositelabel = $('label.label[for="select_'+oppositeswatch.attr('option-id')+'"]');
            var oppositOption = oppositeswatch.parent('div.swatch-attribute-options');
            var oppositeswitch = $('div.'+opposite+'_switch');
            _switch.on('click',function () {
                if(!$(this).hasClass('active')){
                    $(this).addClass('active');
                    label.show();
                    widget.element.show();
                    oppositOption.hide();
                    oppositelabel.hide();
                    oppositeswitch.removeClass('active');
                }
            });
        },

        // Hide one option category when both are there

        _hideInitial: function () {
            var _faceswitch = $('div.face_swatch_switch');
            var _eyeswitch = $('div.eye_swatch_switch');
            var eyeStatus = _faceswitch.css('display');
            var faceStatus = _eyeswitch.css('display');
            if((eyeStatus == 'inline' && faceStatus == 'inline')|| (eyeStatus == 'block' && faceStatus == 'block')){
                var eyeswatch = $('div.swatch-attribute-options.clearfix').children('div.eye_swatch:first-child');
                var eyelabel = $('label.label[for="select_'+eyeswatch.attr('option-id')+'"]');
                var eyeOption = eyeswatch.parent('div.swatch-attribute-options');
                eyelabel.hide();
                eyeOption.hide();
                _faceswitch.addClass('active');
            }
        }
    });
    return $.Ec.switcher;
});
