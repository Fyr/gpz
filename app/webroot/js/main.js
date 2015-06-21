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
});

function showLoader() {
	$('#loader').show();
}

function hideLoader() {
	$('#loader').hide();
}