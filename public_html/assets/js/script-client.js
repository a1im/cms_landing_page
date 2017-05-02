$(document).ready(function() 
{
	$('.alm-elem-menu').ready(function(){
		updateMenu();
	});

	$("body").on("click",".alm-elem-menu ul.nav a", function (event) {
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

	// отправка формы
	$('.alm-elem-form').on('submit', function() {
		ajaxSubmit($(this));
		return false;
	});
});

function scrollMove(val) 
{
	var menu = $('.alm-elem-menu');
	if (menu.length <= 0) return ;

	var newTop = val.scrollTop() - menu.parent()[0].offsetTop;
	if (newTop > 600) {
        menu.stop(true).animate({'top': newTop}, 300);
	} else {
        menu.stop(true).animate({'top': 0}, 300);
	}
}

function updateMenu() 
{
    var menu = $('.alm-elem-menu');
    menu.appendTo(menu.parent());
	var aStyle='', li_a = $('.alm-elem-menu ul.nav li a').eq(0);
	if (li_a.attr('style') !== undefined) {
		aStyle = "style='" + li_a.attr('style') + "'";
	}
	// console.log($('.alm-elem-menu ul.nav li a').attr('style'));
	$('.alm-elem-menu ul.nav').html('');
	$('.alm-elem-block-screen').each(function() {
		var id = $(this).attr('id');
		var name = $(this).attr('name-block');
		if (name !== undefined && name !== '') {
			$('.alm-elem-menu ul.nav').append("<li role='presentation'><a href='#"+id+"' "+aStyle+">"+name+"</a></li>");
		}
		// console.log($(this).attr('name-block'));
	});
	scrollMove($(window));
}

function ajaxSubmit(form) 
{
	// alert(1);
	//отправка сообщения
	var field = form.find('input'),
		arrFields = [],
		formTheme = "";

	if (form.attr('theme') !== undefined) formTheme = form.attr('theme');

	field.each(function() {
		var type = $(this).attr('type');
		if (type !== 'submit' && type !== 'reset' && type !== 'button') {
			var name = $(this).attr('name');
			if (name === undefined) name = "";
			else name += ": ";
			arrFields.push(name + $(this).val());
		}
	});

	var jsonFields = JSON.stringify(arrFields);
	// console.log(jsonFields);

	$.post('/send.php', {
			fields: jsonFields,
			theme: formTheme
		}
		,function(data) {
			form.trigger("reset");
			console.log(data);
		}
	)
	.then(function() {  })
	.done(function() {  })
	.fail(function() {  });
	// var error=0; 												// индекс ошибки
	// $(this).siblings(":input").each(function() {				// проверяем каждое поле в форме
	// 	for(var i=0; i < field.length; i++){ 					// если поле присутствует в списке обязательных
	// 		if($(this).attr("name") == field[i]){ 				//проверяем поле формы на пустоту
	// 			if(!$(this).val()){								// если в поле пустое
	// 				$(this).css('border', 'red 1px solid');		// устанавливаем рамку красного цвета
	// 				error=1;									// определяем индекс ошибки
	// 			}
	// 			else{
	// 				$(this).css('border', 'gray 1px solid');	// устанавливаем рамку обычного цвета
	// 			}
				
	// 		}               
	// 	}
	// });
	
	// if(error==0){ // если ошибок нет то отправляем данные
	// 	$.ajax({
	// 		type: 'POST',
	// 		url: 'send.php',
	// 		data: 'name='+$(this).siblings(".name").val()+'&phone='+$(this).siblings(".phone").val()+'',
	// 		success: function(data) {
	// 			var text = $("#mess_gddg").html();
	// 			var err_text = "Заявка отправлена!";
	// 			var blk = "<p class='info-ajax-mess' style='background: rgba(66,255,66,.9)'>"+err_text+"</p>";
				
	// 			$("#mess_gddg").html(text+blk);
				
	// 			setTimeout(function(){
	// 				var ggg = $("#mess_gddg").html();
	// 				ggg = ggg.replace(/<p [^]*?p>/, "");
	// 				$("#mess_gddg").html(ggg);
	// 			},1500);
				
	// 			$(".send-phone .name").val('');
	// 			$(".send-phone .phone").val('');
	// 		}
	// 	});
	// }
	// else {
	// 	var text = $("#mess_gddg").html();
	// 	if(error==1) var err_text = "Не все обязательные поля заполнены!";
	// 	var blk = "<p class='info-ajax-mess' style='background: rgba(255,66,66,.9)'>"+err_text+"</p>";
		
	// 	$("#mess_gddg").html(text+blk);
		
	// 	setTimeout(function(){
	// 		var ggg = $("#mess_gddg").html();
	// 		ggg = ggg.replace(/<p [^]*?p>/, "");
	// 		$("#mess_gddg").html(ggg);
	// 	},1500);
	// }
}