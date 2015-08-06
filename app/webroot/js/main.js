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
		aHeaders.push($(this).html().trim());
	});
	
	$('.show-desktop tbody > tr').each(function(){
		var html = '';
		if ($('td', this).length > 1) {
			$('td', this).each(function(i){
				if (!$(this).hasClass('subheader')) {
					html+= (aHeaders[i] ? aHeaders[i] + ': ' : '') + $(this).html() + '<br/>';
				}
			});
			$('.show-mobile table > tbody').append('<tr class="grid-row"><td>' + html + '</td></tr>');
		} else {
			$('.show-mobile table > tbody').append('<tr class="grid-row"><td class="subheader">' + $('td', this).html() + '</td></tr>');
		}
	});
});

function showLoader() {
	$('#loader').show();
}

function hideLoader() {
	$('#loader').hide();
}
