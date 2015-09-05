function closeOpenPanel() {
	var selector = $('.catalogPage .leftSide');
	var handle = $('.catalogPage .handle');

	if ( ! selector.hasClass('closed') ) {
		selector.addClass('closed');
		handle.text("«");
		handle.addClass('closed');
	}
	else {
		selector.removeClass('closed');
		handle.text("»");
		handle.removeClass('closed');
	}
}

$(document).ready(function(){
	$('.catalogPage').prepend('<span class="handle">»</span>');
	
	$('.handle').click ( function() {
		closeOpenPanel();
	});
	
	$(window).scroll ( function() {
		
		var panel = $('.catalogPage .leftSide');
		var handle = $('.catalogPage .handle');
		
		var scrolled = $(this).scrollTop();
		
		//var handleTop = handle.offset().top;
		var handleHeight = handle.height();
		
		//var panelTop = panel.offset().top;
		var panelHeight = panel.height();
		
		if (  scrolled + handleHeight >  panelHeight ) {
			handle.css({'position':'absolute','top': panelHeight - handleHeight  });
		}
		else if ( scrolled < 0 ) {
			handle.css({'position':'absolute','top': 0 });
		}
		else {
			handle.css({'top': 'auto', 'position':'fixed'});
		}
		
	});
});

