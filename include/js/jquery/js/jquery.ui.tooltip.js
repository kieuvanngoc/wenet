/*
 * jQuery UI Tooltip @VERSION
 *
 * Copyright (c) 2009 AUTHORS.txt (http://jqueryui.com/about)
 * Dual licensed under the MIT (MIT-LICENSE.txt)
 * and GPL (GPL-LICENSE.txt) licenses.
 *
 * http://docs.jquery.com/UI/Tooltip
 *
 * Depends:
 *	jquery.ui.core.js
 *	jquery.ui.widget.js
 *  jquery.ui.position.js
 */
(function($) {

// role=application on body required for screenreaders to correctly interpret aria attributes
if( !$(document.body).is('[role]') ){
	$(document.body).attr('role','application');
} 

var increments = 0;

$.widget("ui.tooltip", {
	options: {
		tooltipClass: "ui-widget-content",
		content: function() {
			return $(this).attr("title");
		},
		position: {
			my: "left center",
			at: "right center",
			offset: "15 0"
		},
		delay: 150,
		connector: "unfilled", //filled, unfilled, none
		connectorPosition: "left middle"
	},	
	_init: function() {
		var self = this;
		this.tooltip = $("<div></div>")
			.attr("id", "ui-tooltip-" + increments++)
			.attr("role", "tooltip")
			.attr("aria-hidden", "true")
			.addClass("ui-tooltip ui-widget ui-corner-all")
			.addClass(this.options.tooltipClass)
			.appendTo(document.body)
			.hide();
		this.tooltipContent = $("<div></div>")
			.addClass("ui-tooltip-content")
			.appendTo(this.tooltip);
		if (this.options.connector != 'none') {
			var positions = this.options.connectorPosition.split(" ");
			this.tooltipConnector = $("<div></div>")
				.addClass('ui-tooltip-pointer')
				.addClass('ui-tooltip-pointer-edge-' + positions[0])
				.addClass('ui-tooltip-pointer-align-' + positions[1])
				.appendTo(this.tooltip);
			//this._fixConnectorColor(this.tooltipConnector, positions[0]);
			if (this.options.connector == 'unfilled') {
				this.tooltipConnectorInner = $("<div></div>")
					.addClass("ui-tooltip-pointer-inner")
					.appendTo(this.tooltipConnector);
				//this._fixInnerConnectorColor(this.tooltipConnectorInner, positions[0]);
			}
		}
		this.opacity = this.tooltip.css("opacity");
		this.element
			.bind("focus.tooltip mouseenter.tooltip", function(event) {
				self.open();
			})
			.bind("blur.tooltip mouseleave.tooltip", function(event) {
				self.close();
			});
	},
	
	enable: function() {
		this.options.disabled = false;
	},
	
	disable: function() {
		this.options.disabled = true;
	},
	
	destroy: function() {
		this.tooltip.remove();
		$.Widget.prototype.destroy.apply(this, arguments);
	},
	
	widget: function() {
		return this.tooltip;
	},
	
	open: function() {
		var target = this.element;
		// already visible? possible when both focus and mouseover events occur
		if (this.current && this.current[0] == target[0])
			return;
		var self = this;
		this.current = target;
		this.currentTitle = target.attr("title");
		var content = this.options.content.call(target[0], function(response) {
			// ignore async responses that come in after the tooltip is already hidden
			if (self.current == target)
				self._show(target, response);
		});
		if (content) {
			self._show(target, content);
		}
	},
	
	_show: function(target, content) {
		if (!content)
			return;
		
		target.attr("title", "");
		
		if (this.options.disabled)
			return;
		
		this.tooltipContent.html(content);
		this.tooltip.css({
			top: 0,
			left: 0
		}).position($.extend(this.options.position, {
			of: target
		}));
		
		this.tooltip.attr("aria-hidden", "false");
		target.attr("aria-describedby", this.tooltip.attr("id"));
		
		//here for themeroller, pherhaps better in _init fun?
		this._fixConnectorColor();
		this._fixInnerConnectorColor();

		//ie fix?
		this.tooltip.delay(this.options.delay).show(0);
		/*if (this.tooltip.is(":animated"))
			this.tooltip.stop().delay(this.options.delay).show(0).fadeTo("normal", this.opacity);
		else if	(this.tooltip.is(':visible')) 
			this.tooltip.delay(this.options.delay).fadeTo("normal", this.opacity) 
		else
			this.tooltip.delay(this.options.delay).fadeIn();*/

		this._trigger( "open" );
	},
	
	close: function() {
		if (!this.current)
			return;
		
		var current = this.current.attr("title", this.currentTitle);
		this.current = null;
		
		if (this.options.disabled)
			return;
		
		current.removeAttr("aria-describedby");
		this.tooltip.attr("aria-hidden", "true");
		
		//ie fix?
		this.tooltip.stop().hide()
		/*if (this.tooltip.is(':animated'))
				this.tooltip.stop().fadeTo("normal", 0);
			else
				this.tooltip.stop().fadeOut();*/
		
		this._trigger( "close" );
	},	
	
	_fixConnectorColor: function(edge) {
		borderColor = this.tooltipConnector.parent().css('borderTopColor');
		if(this.tooltipConnector) {
			switch( this.options.connectorPosition.split(" ")[0] ) {
				case 'left':
				this.tooltipConnector.css('border-right-color', borderColor);
				break;
				case 'right':
				this.tooltipConnector.css('border-left-color', borderColor);
				break;
				case 'top':
				this.tooltipConnector.css('border-bottom-color', borderColor);
				break;
				case 'bottom':
				this.tooltipConnector.css('border-top-color', borderColor);
				break;
			}
		}
	},	
	
	_fixInnerConnectorColor: function(edge) {
		if(this.tooltipConnectorInner) {
			bColor = this.tooltipConnectorInner.parents('.ui-tooltip').css('backgroundColor');
			switch( this.options.connectorPosition.split(" ")[0] ) {
				case 'left':
				var bWidth = this.tooltipConnectorInner.css('border-right-width');
				this.tooltipConnectorInner.css('border-right', bWidth+' solid '+bColor);
				break;
				case 'right':
				var bWidth = this.tooltipConnectorInner.css('border-left-width');
				this.tooltipConnectorInner.css('border-left', bWidth+' solid '+bColor);
				break;
				case 'top':
				var bWidth = this.tooltipConnectorInner.css('border-bottom-width');
				this.tooltipConnectorInner.css('border-bottom', bWidth+' solid '+bColor);
				break;
				case 'bottom':
				var bWidth = this.tooltipConnectorInner.css('borderTopWidth');
				this.tooltipConnectorInner.css('border-top', bWidth+' solid '+bColor);
				break;
			}
		}
	}
	
});

})(jQuery);