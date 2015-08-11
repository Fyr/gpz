$(document).ready(function(){
	hideLoader();
	
    $('.menu li a').click(function(){
        $(".header .menu li ul").stop().slideUp();
        if ( $(this).next().is('ul') ) {
            $(this).next('ul').stop().slideToggle();
        }
    });
    
    $(document).on('click touchstart', function(e) {
		if (!$.contains($(".header .menuDesktop").get(0), e.target)  ) {
			$(".header .menuDesktop li ul").stop().slideUp();
		}
        if (!$.contains($(".header .menuMobile").get(0), e.target)  ) {
			$(".header .menuMobile li ul").stop().slideUp();
		}
	});
	
	$('form.searchBlock').submit(function(){
		showLoader();
	});
	
	$('.showLoader').on('click touchstart', function(){
		window.scrollTo(0, 0);
		showLoader();
		if ($(this).prop('href')) {
			window.location.href = $(this).prop('href');
			return false;
		}
	});
	
	$('.popup-close').on('click', function(e){
		$.modal().close();
	});
	
	$('table.grid').wrap('<div class="show-desktop"/>');
	$('.show-desktop').parent().append('<div class="show-mobile"/>');
	$('.show-mobile').append('<table class="grid table-bordered shadow"><tbody></tbody></table>');
	
	var aHeaders = [];
	$('.show-desktop .first th').each(function(){
		var html = $(this).html().trim();
		if ($('a', this).length) {
			html = $('a', this).html().trim();
		}
		aHeaders.push(html);
	});
	
	$('.show-desktop tbody > tr').each(function(){
		var html = '', td;
		if ($('td', this).length > 1) {
			$('td', this).each(function(i){
				td = $(this).html().trim();
				if (!$(this).hasClass('subheader') && td) {
					if (aHeaders[i].indexOf('Изображение') > -1 || aHeaders[i].indexOf('Фото') > -1) {
						html = td + html;
					} else {
						html+= (aHeaders[i] ? '<span class="grid-unsortable">' + aHeaders[i] + '</span>: ' : '') + td + '<br/>';
					}
				}
			});
			$('.show-mobile table > tbody').append('<tr class="grid-row"><td>' + html + '</td></tr>');
		} else {
			$('.show-mobile table > tbody').append('<tr class="grid-row"><td class="subheader">' + $('td', this).html() + '</td></tr>');
		}
	});
});

String.prototype.trim = function() {
	return this.replace(/^\s*/, '').replace(/\s*$/, '');
}

function showLoader() {
	$('#loader').show();
}

function hideLoader() {
	$('#loader').hide();
}
