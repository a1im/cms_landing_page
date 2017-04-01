$(document).ready(function() {
	$('.alm-elem-menu').ready(function(){
		updateMenu();
	});

	$(".alm-elem-menu ul.nav").on("click","a", function (event) {
		// console.log($(".alm-elem-menu")[0].offsetHeight);
		event.preventDefault();
		var id  = $(this).attr('href'),
			top = $(id).offset().top - $(".alm-elem-menu")[0].offsetHeight;
		$('body,html').animate({scrollTop: top}, 500);
	});

	// перемещение меню
	scrollMove($(window));
	$(window).scroll(function(){
		scrollMove($(this));
	});
});

function scrollMove(val) {
	var newTop = val.scrollTop() - $(".alm-elem-menu").parent()[0].offsetTop;
	if (newTop > 600) {
		$(".alm-elem-menu").stop(true).animate({'top': newTop}, 300);
	} else {
		$(".alm-elem-menu").stop(true).animate({'top': 0}, 300);
	}
}

function updateMenu() {
	var aStyle='', li_a = $('.alm-elem-menu ul.nav li a').eq(0);
	if (li_a.attr('style') != undefined) {
		aStyle = "style='" + li_a.attr('style') + "'";
	}
	// console.log($('.alm-elem-menu ul.nav li a').attr('style'));
	$('.alm-elem-menu ul.nav').html('');
	$('.alm-elem-block-screen').each(function() {
		var id = $(this).attr('id');
		var name = $(this).attr('name-block');
		if (name != undefined && name != '') {
			$('.alm-elem-menu ul.nav').append("<li role='presentation'><a href='#"+id+"' "+aStyle+">"+name+"</a></li>");
		}
		// console.log($(this).attr('name-block'));
	});
}