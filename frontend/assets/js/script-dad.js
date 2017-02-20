var is_hover = false, is_click = false, is_click_options = false;
var save_elem = null;

$(document).ready(function() {
	
	$(".alm-container").sortable({
		appendTo: 'body',
		cursor:'move', 
		opacity:0.5,
		items: "> .alm-sortable",
		// connectToSortable: ".alm-container",
		snapMode: "both",
        snapTolerance: 50,
        helper: 'clone',
		start: function(event, ui) {
			var width = ui.item.width();
			var height = ui.item.height();
			ui.placeholder.css({display: "block", border: '2px dotted #6666ff', width: width, height: height, visibility: ''});	
			// $(".container").each(function(){
			// 	if ($(this).find("> [alm='sortable']").length === 0) {
			// 		$(this).append(item_temp(ui.item));
			// 	}
			// 	$(".alm-screen-block").sortable('refresh');
			// });
			// $(".alm-screen-block").sortable('refresh');
		},
		stop: function(event, ui) {
			// $(".sort-del").remove();
			// $(".alm-screen-block").sortable('refresh');
		},
		change: function(event, ui) {
			// var width = ui.item.width();
			// var height = ui.item.height();
			// ui.placeholder.css({dissplay: "block", border: '2px dotted #6666ff', width: width, height: height, visibility: ''});	
		},
		update: function(event, ui) {
			// $(".sort-del").remove();
			// $(".alm-screen-block").sortable('refresh');
			var new_index = ui.item.index() + 1;
			var new_parent = ui.item.parent().attr('id');

			// console.log(new_index);
			$.post('http://frontend.mvc.loc/editor/site/updateTagIndex', { 
				id_tag: ui.item.attr('id'),
				id_site: $("#id-site").html(), 
				index_tag: new_index, 
				parent_tag: new_parent }, function(data) {
				// console.log(data);
			});
		},
	});

	$("*[class|='alm-elem']").draggable({
		delay: 100,
		snap: true,
        snapMode: "both",
        snapTolerance: 5,
        grid: [5,5],
        stop: function(ev, ui) {
			postSaveStyle($(this));
			
			// var newTop = ui.helper.offset().top - ui.helper.parent().offset().top;
			// var newLeft = ui.helper.offset().left - ui.helper.parent().offset().left;
			// ui.helper.css({'top': newTop, 'left': newLeft});
			// console.log(ui.helper.offset().top);
			postSaveParent($(this));
        },
        drag: function(ev, ui) {
        	// вычисляем абсолютные координаты по относительным
			ui.position.top = ui.offset.top - $(this).parent().offset().top;
			ui.position.left = ui.offset.left - $(this).parent().offset().left;
        },
        stack: ".alm-elem-block, .alm-elem-text",
	});
	// Удалим с главных блоков
	$(".alm-elem-block-screen").draggable("destroy");
	// принимающие элементы
	$("*[class^='alm-elem-block']").droppable({
		drop: function(ev, ui) {
			$(this).append(ui.draggable);
			// вычисляем абсолютные координаты по относительным
			var newTop = ui.offset.top - $(this).offset().top;
			var newLeft = ui.offset.left - $(this).offset().left;
			ui.helper.css({'top': newTop, 'left': newLeft});
			// console.log("drop");
			// postSaveParent(ui.draggable);
		},
        over: function(ev, ui) {
        	// console.log("over");
        	$(this).append(ui.draggable);
        },
        out: function(ev, ui) {
        	$(this).append(ui.draggable);
        },
    });
	$(".alm-elem-block").resizable({
		handles: "n, e, s, w, ne, se, sw, nw",
		grid: [2,2],
		stop: function(ev, ui) {
			postSaveStyle($(this));
        },
	});
	$(".alm-elem-block-screen").resizable({
		handles: "n, s",
		resize: function() {
			$(this).css({top:0,left:0,bottom:0,right:0})
		},
        stop: function(ev, ui) {
			postSaveStyle($(this));
        },
	});

	// Подсветка при наведении на элемент
	$("*[class|='alm-elem']").on("mouseover", function() {
		// console.log($(this).attr('id'));
		if (!is_hover && !is_click_options)
		{
			is_hover = true;
			elemOverStyle($(this));
		}
	});
	// Снятие подсветки
	$("*[class|='alm-elem']").on("mouseout", function() {
		if (is_hover && !is_click_options)
		{
			is_hover = false;
			elemNoneStyle($(this));
		}
	});
	// Выделить при нажатии
	$("*[class|='alm-elem']").on("mousedown", function() {
		if (!is_click)
		{
			is_click = true;
			is_click_options = !is_click_options;
			// сохранения видимости опций
			if (is_click_options)
			{
				save_elem = $(this);
				elemClickStyle($(this));
			} else {
				// если кликнули второй раз тудаже то отменяем выделения
				if (save_elem.attr("id") == $(this).attr("id"))
				{
					save_elem = null;
					elemNoneStyle($(this));
					elemOverStyle($(this));
				} else {
					// иначе выделяем другой блок
					save_elem = $(this);
					is_click_options = true;
					// убираем видимость опций у всех
					elemNoneStyle($("*[class|='alm-elem']"));
					elemClickStyle($(this));
				}
			}
		}
	});
	// Отпустить нажатия
	$("*[class|='alm-elem']").on("mouseup", function() {
		is_click = false;
	});
	// При клике на опцию
	$(".alm-option").on("mousedown", function() {
		is_click = true;
	});
});

function elemOverStyle(elem)
{
	elem.children(".alm-option").css({"display": "block"});
	elem.addClass("elem-style-over");
}

function elemNoneStyle(elem)
{
	elem.children(".alm-option").css({"display": "none"});
	elem.removeClass("elem-style-over");
	elem.removeClass("elem-style-onclick");
}

function elemClickStyle(elem)
{
	elem.children(".alm-option").css({"display": "block"});
	elem.addClass("elem-style-onclick");
}

function postSaveStyle(elem)
{
	$("#loader").show();
	$.post(location.origin + '/editor/site/updateTagStyle', { 
			id_tag: elem.attr('id'),
			id_site: $("#id-site").html(), 
			style: elem[0].style.cssText 
		}
	)
	.done(function() { $("#loader").hide(); })
	.fail(function() { $("#loader").hide(); });
}

function postSaveParent(elem)
{
	$.post('http://frontend.mvc.loc/editor/site/updateTagIndex', { 
			id_tag: elem.attr('id'),
			id_site: $("#id-site").html(), 
			index_tag: elem.index() + 1, 
			parent_tag: elem.parent().attr('id') 
		},
		function(data) {console.log(data);}
	)
	.done(function() { $("#loader").hide(); })
	.fail(function() { $("#loader").hide(); });
}