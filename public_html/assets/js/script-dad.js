var is_hover = false, is_click = false;
var save_elem = null;
var droppable_elem = null;
var option_elem = null,
	coefShift = 0;


$(document).ready(function() 
{
	// присвоим перемещение элеента
	setDAD();
	hideAddMenu();

	$(window).scroll(function(){
		setInterval( optionMove, 500 );
	});

	// Подсветка при наведении на элемент
	$("body").on("mouseover", "*[class|='alm-elem']", function() {
		// console.log($(this).attr('id'));
		if (!is_hover && save_elem == null)
		{
			is_hover = true;
			elemOverStyle($(this));
		}
	});
	// Снятие подсветки
	$("body").on("mouseout", "*[class|='alm-elem']", function() {
		if (is_hover && save_elem == null)
		{
			is_hover = false;
			elemNoneStyle();
		}
	});
	// Снятие подсветки
	$("#alm-option").on("mouseover", function() {
		elemOverStyle(option_elem);
	});
	// Выделить при нажатии
	$("body").on("mousedown", "*[class|='alm-elem']", function() {
		if (!is_click)
		{
			is_click = true;
			// сохранения видимости опций
			if (save_elem == null)
			{
                hideAll();
				elemClickStyle($(this));
			} else {
				// если кликнули второй раз тудаже то отменяем выделения
				if (save_elem.attr("id") == $(this).attr("id"))
				{
					// elemNoneStyle();
					// elemOverStyle($(this));
				} else {
					// иначе выделяем другой блок
                    hideAll();
					elemNoneStyle();
					elemOverStyle($(this));

				}
			}
			// скрыть все окна при клике на элементы
			// $('.alm-popup').hide();
			// выделенный элемент
			// option_elem = $(this);
		}
	});

	// Отпустить нажатия
	$("body").on("mouseup", "*[class|='alm-elem'], #alm-option .glyphicon", function() {
		is_click = false;
		
	});

	// перемещение опций
	$(".alm-popup").draggable();

	// При клике на опцию добавить 
	$("body").on("mousedown", "#alm-option .option-add", function() {
		is_click = true;
		$('#popup-add').css({
			'top': $(this).parent().offset().top,
			'left': $(this).parent().offset().left + 30
		});

		$('#popup-add').find(".add-elem").show();

		if (option_elem.hasClass('alm-elem-form')) {
			$('#popup-add').find(".add-elem[type-elem='form']").hide();
		} else {
			$('#popup-add').find(".add-elem[type-elem='input']").hide();
		}

		hideAll();
		$('#popup-add').show();
		elemClickStyle(option_elem);
	});
	// При нажатии кнопку добавить блок
	$("#popup-add .add-elem, .menu-add-elem .add-elem").on("mousedown", function() {
		postAddHtml(option_elem, $(this).attr('type-elem'));
	});

	// При клике на опцию копировать
	$("body").on("mousedown", "#alm-option .option-copy", function() {
		postCopyHtml(option_elem);
	});

	// общие настройки стилей
	$(".btn-optin-default").on("mousedown", function() {
		option_elem = $('#site-content');
		is_click = true;
		$('#popup-edit').css({
			'top': $(this).parent().offset().top + 55,
			'left': $(this).parent().offset().left
		});

		// выберем режим
		$("#opc-select-param").val("");
		// спрячим все опции
		$('#popup-edit-glav tr').hide();
		// скрыть все элементы меню
		$('#popup-edit .nav-tabs li').hide();
		$('#popup-edit .blk-selector-param').hide();

		// отобразим только нужные
		$('#popup-edit .opc-default').show();
		$('#popup-edit .nav-tabs li').eq(-1).click();

		$('#popup-edit .nav-tabs li').removeClass('active');
		$('#popup-edit .nav-tabs li.opc-default').eq(0).find('a').click();
		
		setOptionsParam(); // установим параметры элемента
		hideAll();
		$('#popup-edit').show();
		// убираем видимость опций у всех
		elemNoneStyle();
		// elemClickStyle(option_elem);
		save_elem = option_elem;
	});

	// При клике на опцию изменить 
	$("body").on("mousedown", "#alm-option .option-param", function() {
		is_click = true;
		$('#popup-edit').css({
			'top': $(this).parent().offset().top + 25,
			'left': $(this).parent().offset().left
		});

		// выберем режим
		$("#opc-select-param").val("");
		// спрячим все опции
		$('#popup-edit-glav tr').hide();
		$('#popup-edit-transition .opc-edit-style, #popup-edit-glav .opc-edit-style, #popup-edit-border .opc-edit-style, #popup-edit-border-radius .opc-edit-style, #popup-edit-shadow .opc-edit-style')
		.attr('edit-elem', "");
		$('#popup-edit .nav-tabs li').hide();
		$('#popup-edit .blk-selector-param').hide();

		$('#popup-edit .nav-tabs li').removeClass('active');
		$('#popup-edit .nav-tabs li').eq(0).find('a').click();

		// отобразим только нужные
		if (option_elem.hasClass('alm-elem-block-screen')) {
			$('#popup-edit .opc-block-screen').show();
		} else if (option_elem.hasClass('alm-elem-block-screen-content')) {
			$('#popup-edit .opc-block-content').show();
		} else if (option_elem.hasClass('alm-elem-block')) {
			$('#popup-edit .opc-block').show();
		} else if (option_elem.hasClass('alm-elem-text')) {
			$('#popup-edit .opc-text').show();
		} else if (option_elem.hasClass('alm-elem-menu')) {
			$('#popup-edit .opc-menu').show();
		} else if (option_elem.hasClass('alm-elem-form')) {
			$('#popup-edit .opc-form').show();
		} else if (option_elem.hasClass('alm-elem-input')) {
			$('#popup-edit .opc-form-input').show();
			$('#popup-edit-transition .opc-edit-style, #popup-edit-glav .opc-edit-style, #popup-edit-border .opc-edit-style, #popup-edit-border-radius .opc-edit-style, #popup-edit-shadow .opc-edit-style').attr('edit-elem', " input");
		}

		setOptionsParam(); // установим параметры элемента
		hideAll();
		$('#popup-edit').show();
		// убираем видимость опций у всех
		// elemNoneStyle();
		elemClickStyle(option_elem);
	});

	// Опция редактор текста
	$("body").on("mousedown", "#alm-option .option-edit", function() {
		is_click = true;
		$('#popup-edit-text').css({
			'top': $(this).parent().offset().top,
			'left': $(this).parent().offset().left + 30
		});
		// console.log($("#popup-edit-text textarea").html());
		tinymce.get('textarea-tiny').setContent(option_elem.find('.content-text').html());
		tinymce.get('textarea-tiny').execCommand('mceFullscreen',false,true);
		hideAll();
		$('#popup-edit-text').show();
		elemClickStyle(option_elem);
	});

	// кнопка минус - удалить элемент
	$("body").on("mousedown", "#alm-option .option-del", function() {
		postDeleteTag(option_elem);
		elemClickStyle(option_elem);
	});

	// При нажатии крестика
	$(".alm-popup .popup-close").on("mousedown", function() {
		$(this).parent().hide();
	});



	// Обработка параметров в эдиторе на события клик
	// колор пикер для редактирования
	$("#popup-edit .opc-edit-style[edit-color='color']").each(function(){
		setColorPicker($(this));
	});
	// убрать цвет
	$("#popup-edit .opc-style-clear").on("mousedown", function() {
		var elem = $(this).siblings(".opc-edit-style");
		elem.each(function(){
			deleteStyleTag(option_elem, $(this).attr('edit-style'), $(this).attr('edit-elem'), $(this).attr('edit-vendor'));
			setInputValue($(this));
		});
		postSaveCssSelector(option_elem);
	});

	//Выбираем режим hover, active и тд
	$("#opc-select-param").on("change", function() {
		// console.log($(this).val());
		setOptionsParam();
	});

	//главные
	$("#popup-edit .glyphicon-object-align-left").on("mousedown", function() {
		option_elem.css('left', 0);
		$("#popup-edit .cordX").val(0);
		postSaveStyle(option_elem);
	});
	$("#popup-edit .glyphicon-object-align-vertical").on("mousedown", function() {
		var newLeft = (option_elem.parent()[0].clientWidth/2-option_elem[0].offsetWidth/2);
		option_elem.css('left', newLeft + 'px');
		$("#popup-edit .cordX").val(newLeft);
		postSaveStyle(option_elem);
	});
	$("#popup-edit .glyphicon-object-align-right").on("mousedown", function() {
		var newLeft = (option_elem.parent()[0].clientWidth-option_elem[0].offsetWidth) -1;
		option_elem.css('left', newLeft + 'px');
		$("#popup-edit .cordX").val(newLeft);
		postSaveStyle(option_elem);
	});
	$("#popup-edit .glyphicon-object-align-top").on("mousedown", function() {
		option_elem.css('top', 0);
		$("#popup-edit .cordY").val(0);
		postSaveStyle(option_elem);
	});
	$("#popup-edit .glyphicon-object-align-horizontal").on("mousedown", function() {
		var newTop = (option_elem.parent()[0].clientHeight/2-option_elem[0].offsetHeight/2);
		option_elem.css('top', newTop + 'px');
		$("#popup-edit .cordY").val(newTop);
		postSaveStyle(option_elem);
	});
	$("#popup-edit .glyphicon-object-align-bottom").on("mousedown", function() {
		var newTop = (option_elem.parent()[0].clientHeight-option_elem[0].offsetHeight);
		option_elem.css('top', newTop + 'px');
		$("#popup-edit .cordY").val(newTop);
		postSaveStyle(option_elem);
	});	
	$("#popup-edit .glyphicon-resize-horizontal").on("mousedown", function() {
		// console.log({width: option_elem.outerWidth(), height: option_elem.outerHeight()});
		var originalSize = {width: option_elem.outerWidth(), height: option_elem.outerHeight()};
		option_elem.css('left', 0);
		option_elem.css('width', (option_elem.parent()[0].clientWidth) + 'px');
		$("#popup-edit .sizeW").val(option_elem.parent()[0].clientWidth);
		var size = {width: option_elem.outerWidth(), height: option_elem.outerHeight()};
		newPositionChildElem(option_elem, originalSize, size);
		postSaveStyle(option_elem);
	});
	$("#popup-edit .glyphicon-resize-vertical").on("mousedown", function() {
		var originalSize = {width: option_elem.outerWidth(), height: option_elem.outerHeight()};
		option_elem.css('top', 0);
		option_elem.css('height', (option_elem.parent()[0].clientHeight) + 'px');
		$("#popup-edit .sizeH").val(option_elem.parent()[0].clientHeight);
		var size = {width: option_elem.outerWidth(), height: option_elem.outerHeight()};
		newPositionChildElem(option_elem, originalSize, size);
		postSaveStyle(option_elem);
	});

	$("#popup-edit .cordX").on("change", function() {
		option_elem.css('left', $(this).val() + 'px');
		postSaveStyle(option_elem);
	});
	$("#popup-edit .cordY").on("change", function() {
		option_elem.css('top', $(this).val() + 'px');
		postSaveStyle(option_elem);
	});
	$("#popup-edit .sizeW").on("change", function() {
		var originalSize = {width: option_elem.outerWidth(), height: option_elem.outerHeight()};
		option_elem.css('width', $(this).val() + 'px');
		var size = {width: option_elem.outerWidth(), height: option_elem.outerHeight()};
		newPositionChildElem(option_elem, originalSize, size);
		postSaveStyle(option_elem);
	});
	$("#popup-edit .sizeH").on("change", function() {
		var originalSize = {width: option_elem.outerWidth(), height: option_elem.outerHeight()};
		option_elem.css('height', $(this).val() + 'px');
		var size = {width: option_elem.outerWidth(), height: option_elem.outerHeight()};
		newPositionChildElem(option_elem, originalSize, size);
		postSaveStyle(option_elem);
	});

	// изменение индекса у главного блока
	$("#popup-edit .numBlockScreen").on("change", function() {
		var index = $(this).val()-1;
		// console.log($('.alm-elem-block-screen').eq(-1).index());
		if (index < 0) index = 0;
		if (index > $('.alm-elem-block-screen').eq(-1).index()) index = $('.alm-elem-block-screen').eq(-1).index();
		if (option_elem.index() > $('.alm-elem-block-screen').eq(index).index()) {
			option_elem.insertBefore($('.alm-elem-block-screen')[index]);
		} else {
			option_elem.insertAfter($('.alm-elem-block-screen')[index]);
		}
		$(this).val(index + 1);
		optionMove();
		updateMenu();
		postSaveParent(option_elem);
	});
	// изенить имя главного блока
	$("#popup-edit .nameBlockScreen").on("change", function() {
		option_elem.attr('name-block', $(this).val());
		updateMenu();
		postSaveTagSelector(option_elem, 'name-block');
	});

	// присвоить значения в атрибутах
	$("#popup-edit .opc-edit-attr").on("change", function() {
		// если на элементах заморозка
		if ($(this).attr('edit-elem') == undefined) {
			option_elem.attr($(this).attr('edit-attr'), $(this).val());
			postSaveTagSelector(option_elem, $(this).attr('edit-attr'));
		}
		else {
			option_elem.find($(this).attr('edit-elem')).attr($(this).attr('edit-attr'), $(this).val());
			postSaveContent(option_elem);
		}
		updateMenu();		
	});

	// присвоить значения в инпуте и стилях
	$("#popup-edit .opc-edit-style").on("change", function() {
		// если на элементах заморозка
		isLockSetStyle($(this));
		setValueStyle($(this), $(this));
		$(this).siblings('.opc-style-clear').show();
		postSaveCssSelector(option_elem);
	});

});

function hideAll()
{
    if ($('#popup-edit').is(':visible')) {
        console.log('hide2');
        postSaveCssSelector(option_elem);
    }
	$('#popup-add').hide();
	$('#popup-edit-text').hide();
	$('#popup-edit').hide();
}

function setValueStyle(elem, elem2)
{
	if (elem.attr('edit-type') == 'radius') {
		var thisElem = elem2.parent().find('[edit-style='+elem2.attr('edit-style')+']');
		setValueStrStyle(elem, thisElem.eq(0).val() + "%" + thisElem.eq(1).val() + "%");
	} else if (elem.attr('edit-type') == 'shadow') {
		var thisElem = elem2.parent().parent().parent().find('[edit-style='+elem2.attr('edit-style')+']');
		if (thisElem.length == 5) {
			var x = parseInt(thisElem.eq(0).val()),
				y = parseInt(thisElem.eq(1).val()),
				radius = parseInt(thisElem.eq(2).val()),
				resize = parseInt(thisElem.eq(3).val()),
				color = thisElem.eq(4).css('backgroundColor');
			setValueStrStyle(elem, (x + 'px ' + y + 'px ' + radius + 'px ' + resize + 'px ' + color));
		}
	} else if (elem.attr('edit-type') == 'image-url') {
		setValueStrStyle(elem, 'url("'+elem2.val()+'")');
	} else if (elem.attr('edit-type') == 'color') {
		var thisElem = elem2.parent().parent().find('[edit-style='+elem2.attr('edit-style')+']');
		// console.log(thisElem.eq(1).val());
		var val1 = thisElem.eq(0).val(),
			val2 = thisElem.eq(1).val(),
		val1 = val1.replace(new RegExp('\\)'), ',' + val2 + ')');
		val1 = val1.replace(new RegExp('rgb'), 'rgba');

		setValueStrStyle(elem, val1);
	} else {
		setValueStrStyle(elem, elem2.val());
	}
}

function setValueStrStyle(elem, value)
{
	var postfix = "";
	if (elem.attr('edit-postfix') != undefined && value != 0) postfix = elem.attr('edit-postfix');
	addStyleTag(elem.attr('edit-style'), value+postfix, elem.attr('edit-elem'), elem.attr('edit-vendor'));
}

function isLockSetStyle(elem)
{
	if (elem.siblings('.opc-lock').find('input')[0] != undefined
		&& elem.siblings('.opc-lock').find('input')[0].checked)
	{
		var el = elem;
		el.siblings('input').each(function() {
			// console.log(el.val());
			$(this).val(el.val());
			setValueStyle($(this), el);
			// если изменяем цвет
			if ($(this).attr('edit-color') == 'color') $(this).css("backgroundColor", el.val());
		});
	}
}

// присвоить значение input
function setInputValue(elem)
{
	var valueElem = getStyleTag(option_elem, elem, elem.attr('edit-style'), getStyleDefault(elem.attr('edit-style'), elem.attr('edit-elem')), elem.attr('edit-elem'), elem.attr('edit-vendor'));
	// console.log(elem.attr('edit-style') + " : " + valueElem);
	if (valueElem == undefined) return;
	if (elem.attr('edit-type') == 'int') {
		elem.val(parseInt(valueElem));
	} else if (elem.attr('edit-type') == 'radius') {
		elem = elem.parent().find('[edit-style='+elem.attr('edit-style')+']');
		elem.eq(0).val(parseInt(valueElem));
		var val = valueElem.replace(new RegExp('.*?%'), '');
		if (val == "") elem.eq(1).val(parseInt(valueElem));
		else elem.eq(1).val(parseInt(val));
	} else if (elem.attr('edit-type') == 'shadow') {
		var arrVal = valueElem.split(' ');
		var thisElem = elem.parent().parent().parent().find('[edit-style='+elem.attr('edit-style')+']');
		// console.log(valueElem);
		if (arrVal.length == 7 && thisElem.length == 5) {
			thisElem.eq(0).val(parseInt(arrVal[0]));
			thisElem.eq(1).val(parseInt(arrVal[1]));
			thisElem.eq(2).val(parseInt(arrVal[2]));
			thisElem.eq(3).val(parseInt(arrVal[3]));
			thisElem.eq(4).val(arrVal[4]+arrVal[5]+arrVal[6]);
		} else {
			thisElem.eq(0).val(0);
			thisElem.eq(1).val(0);
			thisElem.eq(2).val(0);
			thisElem.eq(3).val(0);
			thisElem.eq(4).val('rgb(255,255,255)');
			setValueStrStyle(thisElem, (0 + 'px ' + 0 + 'px ' + 0 + 'px ' + 0 + 'px rgb(255,255,255)'));
		}
	} else if (elem.attr('edit-type') == 'image-url') {
		valueElem = valueElem.replace(new RegExp('url\\("', 'igm'), '');
		valueElem = valueElem.replace(new RegExp('"\\)', 'igm'), '');
		elem.val(valueElem);
	} else if (elem.attr('edit-type') == 'color') {
		var thisElem = elem.parent().parent().find('[edit-style='+elem.attr('edit-style')+']');
		var arrVal = valueElem.split(',');
		if (arrVal.length == 4 && thisElem.length == 2) {
			arrVal[0] = arrVal[0].replace(new RegExp('rgba'), 'rgb');
			thisElem.eq(0).val(arrVal[0] + ',' + arrVal[1] + ',' + arrVal[2] + ')');
			thisElem.eq(1).val(arrVal[3].replace(new RegExp('\\)'), ''));
			// console.log(elem.attr('edit-vendor') + " : " + valueElem);
		} else {
			thisElem.eq(0).val('rgb(255,255,255)');
			thisElem.eq(1).val(1);
		}
		// console.log(elem.attr('edit-style') + " : " + valueElem);
	} else {
		if (elem.attr('edit-style') == 'font-family') {
			valueElem = valueElem.replace(new RegExp(", ", 'igm'), ',');
			valueElem = valueElem.replace(new RegExp('"', 'igm'), '');
		}
		// console.log(valueElem);
		elem.val(valueElem);
	}
	if (elem.attr('edit-color') == 'color') elem.css("backgroundColor", elem.val());
}

function setOptionsParam()
{
	// установим параметры элемента
	$("#popup-edit .cordX").val(option_elem[0].offsetLeft);
	$("#popup-edit .cordY").val(option_elem[0].offsetTop);
	$("#popup-edit .sizeW").val(option_elem[0].offsetWidth);
	$("#popup-edit .sizeH").val(option_elem[0].offsetHeight);
	$("#popup-edit .numBlockScreen").val(option_elem.index() + 1);
	$("#popup-edit .nameBlockScreen").val(option_elem.attr('name-block'));

	// общий случай
	$("#popup-edit .opc-edit-attr").each(function(){
		// console.log(option_elem.find($(this).attr('edit-elem')).attr('type'));		
		if ($(this).attr('edit-elem') == undefined) {
			$(this).val(option_elem.attr($(this).attr('edit-attr')));
		}
		else {
			$(this).val(option_elem.find($(this).attr('edit-elem')).attr($(this).attr('edit-attr')));
		}
	});

	// общий случай
	$("#popup-edit .opc-edit-style").each(function(){
		setInputValue($(this));
	});

	// заморозить если все элеенты равны
	$("#popup-edit .opc-lock").each(function(){
		var check = true;
		var el = $(this).siblings('input').eq(0);
		el.siblings('input').each(function(){
			if (el.val() != $(this).val()) {
				check = false;
			}
		});
		if (check) $(this).find('input')[0].checked = true;
		else $(this).find('input')[0].checked = false;
	});

}

function optionMove()
{
	$("#alm-option .glyphicon").hide();
	// отобразим только нужные
	$('#alm-option .option-param').show();
	$('#alm-option .option-del').show();
	if (option_elem != null) {
		if (option_elem.hasClass('alm-elem-block-screen-content')) {
			$('#alm-option .option-add').show();
			$('#alm-option .option-del').hide();
		} else if (option_elem.hasClass('alm-elem-block')) {
			$('#alm-option .option-add').show();
			$('#alm-option .option-copy').show();
		} else if (option_elem.hasClass('alm-elem-text')) {
			$('#alm-option .option-edit').show();
			$('#alm-option .option-copy').show();
		} else if (option_elem.hasClass('alm-elem-menu')) {
			// $('#alm-option .option-add').show();
		} else if (option_elem.hasClass('alm-elem-form')) {
			$('#alm-option .option-add').show();
			$('#alm-option .option-copy').show();
		} else if (option_elem.hasClass('alm-elem-input')) {
			$('#alm-option .option-copy').show();
		}

		$("#alm-option").css({top: (option_elem.offset().top-22) + 'px', left: option_elem.offset().left + 'px'});
	}
}

function elemOverStyle(elem)
{
	option_elem = elem;
	coefShift = 0;
	optionMove();
	$("#alm-option").show();
	$("#alm-option .glyphicon").addClass("elem-style-over");
}

function elemNoneStyle()
{
	save_elem = null;
	$("#alm-option").hide();
	$("#alm-option .glyphicon").removeClass("elem-style-over");
	$("#alm-option .glyphicon").removeClass("elem-style-onclick");
}

function elemClickStyle(elem)
{
	option_elem = elem;
	coefShift = 0;
	save_elem = elem;
	optionMove();
	$("#alm-option").show();
	$("#alm-option .glyphicon").addClass("elem-style-onclick");
}

function postSaveStyle(elem)
{
	postSaveTagSelector(elem, 'style');
}

function postSaveContent(elem)
{
	// console.log(elem);
	$("#loader").show();
	$.post(location.origin + '/editor/site/updateTagContent', { 
			id_tag: elem.attr('id'),
			id_site: $("#id-site").html(),
			content: elem.find('.content-text').html()
		}
		// ,function(data) {console.log(data);}
	)
	.done(function() { $("#loader").hide(); })
	.fail(function() { $("#loader").hide(); });
}

function postSaveParent(elem)
{
    var id = parseInt(elem.parent().attr('id'));
    id = isNaN(id)?0:id;
    console.log(id);

	$("#loader").show();
	$.post(location.origin+'/editor/site/updateTagIndex', { 
			id_tag: elem.attr('id'),
			id_site: $("#id-site").html(), 
			index_tag: elem.index() + 1, 
			parent_tag: id
		}
		,function(data) {console.log(data);}
	)
	.done(function() { $("#loader").hide(); })
	.fail(function() { $("#loader").hide(); });
}

function postSaveTagSelector(elem, selector)
{
	$("#loader").show();
	$.post(location.origin + '/editor/site/updateTagSelector/' + selector, { 
			id_tag: elem.attr('id'),
			id_site: $("#id-site").html(), 
			value: elem.attr(selector)
		}
		// ,function(data) {console.log(data);}
	)
	.done(function() { $("#loader").hide(); })
	.fail(function() { $("#loader").hide(); });
}

function postSaveCssSelector(elem)
{
	var styles_send = getFindAttr('style', 'id-tag', elem.attr('id'));
	if (styles_send != null)
    {
	    console.log('save');
		$("#loader").show();
		$.post(location.origin + '/editor/site/updateCssSelector/', { 
				id_tag: elem.attr('id'),
				id_site: $("#id-site").html(),
				styles: styles_send.html()
			}
			,function(data) {console.log(data);}
		)
		.then(function() { $("#loader").hide(); })
		.done(function() { $("#loader").hide(); })
		.fail(function() { $("#loader").hide(); });
	}
}

function postDeleteTag(elem)
{
	$("<div title='Удалить элементы'><p>Удалить этот элемент и все вложенные в него элементы?</p></div>").dialog({
		resizable:false,
		modal:true,
		buttons:{
			"Удалить элементы": function(){
				$("#loader").show();
				$.post(location.origin+'/editor/site/deleteTag', { 
						id_tag: elem.attr('id'),
						id_site: $("#id-site").html(),
					}
					,function(data) {
						elemNoneStyle();
						var styles = getFindAttr('style', 'id-tag', elem.attr('id'));
						if (styles != null) styles.remove();
						elem.remove();
						updateMenu();
						hideAddMenu();
					}
				)
				.done(function() { $("#loader").hide(); })
				.fail(function() { $("#loader").hide(); });
				$(this).remove();
			},
			"Отмена": function(){
				$(this).remove();
			}	
		}
	});
}

function postAddHtml(elem, typename)
{
	$("#loader").show();
	var parentId;
	switch(typename) {
	case 'main-menu':
	case 'screen-block':
		elem = $('#site-content');
		parentId = 0;
		break;
	default: 
		parentId = elem.attr('id');
	}
	$.post(location.origin+'/editor/site/addhtml/'+$("#id-site").html()+'/'+typename+'/'+parentId, {

		}
		,function(data) {
			// console.log(data);
			var elemData = $(data);
			elemData.appendTo(elem);
			setDAD();
			updateMenu();
			hideAddMenu();
		}
	)
	.then(function() { $("#loader").hide(); })
	.done(function() { $("#loader").hide(); })
	.fail(function() { $("#loader").hide(); });
}

function postCopyHtml(elem)
{
	$("#loader").show();
	$.post(location.origin+'/editor/site/copyTag', { 
			id_tag: elem.attr('id'),
			id_site: $("#id-site").html(),
		}
		,function(data) {
			// console.log(data);
			var elemData = $(data);
			coefShift += 10;
			var newTop = elem.position().top + coefShift;
			var newLeft = elem.position().left + coefShift;
			elemData.css({top: newTop, left: newLeft});
			elemData.appendTo(elem.parent());
			copyStyle(elem, elemData);
			setDAD();
			updateMenu();
			hideAddMenu();
		}
	)
	.then(function() { $("#loader").hide(); })
	.done(function() { $("#loader").hide(); })
	.fail(function() { $("#loader").hide(); });
}

function almDraggable(elem)
{
	// перетаскивание
	elem.draggable({
		// delay: 100,
		snap: true,
        snapMode: "both",
        snapTolerance: 5,
        grid: [5,5],
		start: function() {
			// console.log("start");
			droppable_elem = null;
		},
        stop: function(ev, ui) {
        	optionMove();
			postSaveStyle($(this));
			postSaveParent($(this));
        },
        drag: function(ev, ui) {
        	optionMove();
        	// вычисляем абсолютные координаты по относительным
			ui.position.top = ui.offset.top - $(this).parent().offset().top;
			ui.position.left = ui.offset.left - $(this).parent().offset().left;
        },
        stack: elem,
        cancel: "#alm-option"
	});
	// // Удалим с главных блоков
	// $(".alm-elem-block-screen").draggable("destroy");
}

function almDroppable(elem)
{
	// принимающие элементы
	elem.droppable({
		accept: "*[class|='alm-elem']",
		drop: function(ev, ui) {
        	ui.draggable.attr('parent', $(this).attr('id'));
        	// if (droppable_elem != null ) console.log(droppable_elem.find($(this)).length);
        	// если в предыдущем элементе находим текущий то тда добавляем
        	if (droppable_elem == null || droppable_elem.find($(this)).length > 0
        		|| $(this).hasClass('alm-elem-menu')) {
        		droppable_elem = $(this);
        		$(this).append(ui.draggable);
				// вычисляем абсолютные координаты по относительным
				var newTop = (ui.offset.top - $(this).offset().top);
				// newTop = (newTop/$(this).outerHeight())*100;
				var newLeft = (ui.offset.left - $(this).offset().left);
				// newLeft = (newLeft/$(this).outerWidth())*100;
				ui.helper.css({'top': newTop, 'left': newLeft});
				// console.log((newTop/$(this).outerHeight())*100);
        	}
		},
        over: function(ev, ui) {
        	// console.log("OVER " + $(this).attr('id'));
        	// console.log($(this));
        	$(this).append(ui.draggable);
        },
        out: function(ev, ui) {
        	// console.log($(this));
        	// console.log("out " + $(this).attr('id'));
        	$(this).append(ui.draggable);
        }, 
    });
}

function newPositionChildElem(elem, originalSize, size)
{
	elem.find("[class|='alm-elem']").each(function() {
		var posLeft = ($(this).position().left + $(this).outerWidth()/2)/originalSize.width,
			posTop = ($(this).position().top + $(this).outerHeight()/2)/originalSize.height;
		var newLeft = posLeft*size.width - $(this).outerWidth()/2,
			newTop = posTop*size.height - $(this).outerHeight()/2;
		$(this).css({'left': newLeft, 'top': newTop});
		postSaveStyle($(this));
		// console.log(newPos);
	});
}

function almRessizeAll(elem)
{
	// изменение размера
	elem.resizable({
		handles: "n, e, s, w, ne, se, sw, nw",
		grid: [2,2],
		start: function(ev, ui) {

		},
		stop: function(ev, ui) {
			// console.log(ui);
			newPositionChildElem(ui.element, ui.originalSize, ui.size);
			postSaveStyle($(this));
        },
	});
}

function almRessizeS(elem)
{
	elem.resizable({
		handles: "s",
		resize: function() {
			$(this).css({top:0,left:0,bottom:0,right:0})
		},
        stop: function(ev, ui) {
        	option_elem = ui.element;
        	// console.log($('#popup-edit-glav [edit-style="height"]'));
        	ui.element.css({'height':''});
			setValueStrStyle($('#popup-edit-glav [edit-style="height"]'), ui.size.height);
			postSaveCssSelector(option_elem);
        },
	});
}

function almRessizeEW(elem)
{
	elem.resizable({
		handles: "e, w",
		resize: function() {
			$(this).css({top:0,left:0,bottom:0,right:0})
		},
        stop: function(ev, ui) {
        	option_elem = ui.element;
        	// console.log($('#popup-edit-glav [edit-style="width"]'));
        	ui.element.css({'width':''});
			setValueStrStyle($('#popup-edit-glav [edit-style="width"]'), ui.size.width);
			postSaveCssSelector(option_elem);
        },
	});
}

function colorPickerOnShow(colpkr) {
	$(colpkr).fadeIn(200);
	return false;
}

function colorPickerOnHide(colpkr) {
	// postSaveStyle(option_elem);
	postSaveCssSelector(option_elem);
	$(colpkr).fadeOut(200);
	return false;
}

function colorPickerOnSubmit(hsb, hex, rgb, el) {
	$(el).ColorPickerHide();
	postSaveCssSelector(option_elem);
}

function setColorPicker(elem)
{
	elem.ColorPicker({
		color: '#0000ff',
		onBeforeShow: function () {
			$(this).ColorPickerSetColor(this.value);
		},
		onShow: colorPickerOnShow,
		onHide: colorPickerOnHide,
		onSubmit: colorPickerOnSubmit,
		onChange: function (hsb, hex, rgb) {
			var color = 'rgb('+rgb.r+','+rgb.g+','+rgb.b+')';
			// color = '#'+hex;
			elem.val(color);
			elem.css("backgroundColor", color);
			elem.siblings('.opc-style-clear').show();
			isLockSetStyle(elem);
			setValueStyle(elem, elem);
			// console.log($(this));
		}
	});
}

function getSelector(findTag, vendorOpt)
{
	var id =  option_elem.attr('id');
	// console.log(findTag);
	if (findTag == undefined) findTag = "";
	if (vendorOpt == undefined) vendorOpt = "";
	return "\[id='" + id + "'\]" + findTag + $("#opc-select-param").val() + vendorOpt + "{";
}

// добавить стиль в стили тега, findTag - дочерний тег
function addStyleTag(style, value, findTag, vendorOpt)
{
	var id =  option_elem.attr('id');
	// if (isNaN(id)) id = 0;
	
	var styles = getFindAttr('style', 'id-tag', id);

	// $("#opc-select-param").val();
	// если еще нету стилей для тега
	if (styles == null) {
		$('body').append("<style id-tag='" + id + "'></style>");
		styles = getFindAttr('style', 'id-tag', id);
		// $('body').append("<style id-tag='" + id + "'>*[id='" + id + "']{" + style + ":" + value + ";}</style>");
	}
	// console.log(styles);
	var str = styles.html();
	var selector = getSelector(findTag, vendorOpt);
	// если електор найден
	if ((new RegExp("\\"+selector)).test(str)) {
		// выбиаем селектор который нужно менять
		var strMiddle = str.substr(str.indexOf(selector)-1, str.length);
		var strStart = str.substring(0, str.indexOf(selector)-1);
		var strEnd = strMiddle.substring(strMiddle.indexOf("}")+1, strMiddle.length);
		strMiddle = strMiddle.substring(0, strMiddle.indexOf("}") + 1);
		// если есть стиль
		if ((new RegExp(";"+style+".*?;")).test(strMiddle)) {
			strMiddle = strMiddle.replace(new RegExp(";"+style+'.*?;'), ";" + style + ":" + value + ";");
		} else if ((new RegExp("{"+style+".*?;")).test(strMiddle)) {
			strMiddle = strMiddle.replace(new RegExp("{"+style+'.*?;'), "{" + style + ":" + value + ";");
		} else {
			// добавим в конец
			strMiddle = strMiddle.replace(/}/, style + ":" + value + ";}");
		}
		styles.html(strStart + strMiddle + strEnd);
		// console.log(strMiddle);
	} else {
		// console.log(str);
		styles.append("*"+selector + style + ":" + value + ";}");
	}
}

// найти селектор с атрибутом и вернуть его
function getFindAttr(selector, par, val)
{
	var result = null;
	$(selector).each(function(){
		if ($(this).attr(par) == val) {
			result = $(this);
			return;
		}
	});
	return result;
}

// чек стиля у элемента
function isStyleTag(elem, style, findTag, vendorOpt)
{
	var id =  option_elem.attr('id');
	var styles = getFindAttr('style', 'id-tag', id);
	if (styles == null) {
		return false;
	} else {
		var str = styles.html();
		var selector = getSelector(findTag, vendorOpt);
		var strVal = str.substr(str.indexOf(selector)-1, str.length);
		strVal = strVal.substring(0, strVal.indexOf("}") + 1);
		if ((new RegExp("[{;]"+style+".*?;")).test(strVal)) {
			var ind = strVal.indexOf("{"+style);
			if (ind < 0) ind = strVal.indexOf(";"+style);
			strVal = strVal.substr(ind, strVal.length);
			strVal = strVal.substr(strVal.indexOf(":") + 1, strVal.length);
			strVal = strVal.substring(0, strVal.indexOf(";"));
			return strVal;
		} else return false;
	}
}

function getStyleDefault(style, findTag)
{
	if (findTag == "" || findTag == undefined || findTag == null) return option_elem.css(style);
	else return option_elem.find(findTag).css(style);
}

// если в style[id_tag] нету данного стиля то присвоим defaultVal
function getStyleTag(elem, elemStyle, style, defaultVal, findTag, vendorOpt)
{
	if ((res = isStyleTag(elem, style, findTag, vendorOpt)) !== false) {
		elemStyle.siblings('.opc-style-clear').show();
		return res;
	} else {
		elemStyle.siblings('.opc-style-clear').hide();
		return defaultVal;
	}
}

function deleteStyleTag(elem, style, findTag, vendorOpt)
{
	var id =  option_elem.attr('id');
	var styles = getFindAttr('style', 'id-tag', id);
	if (styles == null) {
		return false;
	}
	var str = styles.html();
	var selector = getSelector(findTag, vendorOpt);
	// если електор найден
	if ((new RegExp("\\"+selector)).test(str)) {
		// выбиаем селектор который нужно менять
		var strMiddle = str.substr(str.indexOf(selector)-1, str.length);
		var strStart = str.substring(0, str.indexOf(selector)-1);
		var strEnd = strMiddle.substring(strMiddle.indexOf("}")+1, strMiddle.length);
		strMiddle = strMiddle.substring(0, strMiddle.indexOf("}") + 1);
		// если есть стиль
		if ((new RegExp(";"+style+".*?;")).test(strMiddle)) {
			strMiddle = strMiddle.replace(new RegExp(";"+style+'.*?;'), ";");
		} else if ((new RegExp("{"+style+".*?;")).test(strMiddle)) {
			strMiddle = strMiddle.replace(new RegExp("{"+style+'.*?;'), "{");
		} else {
			return false;
		}
		styles.html(strStart + strMiddle + strEnd);
	}
	return true;
}

function copyStyle(elem, elem2)
{
	var styles = getFindAttr('style', 'id-tag', elem.attr('id'));
	if (styles!= null && styles.length > 0) {
		var str = styles.html();
		str = str.replace(new RegExp("id='"+elem.attr('id'), 'igm'), "id='"+elem2.attr('id'));
		$('body').append("<style id-tag='" + elem2.attr('id') + "'>"+str+"</style>");
	}
}

// присвоим взаимодействие элементов
function setDAD() 
{
	almDraggable($(".alm-elem-block, .alm-elem-text, .alm-elem-form, .alm-elem-input"));
	$('.alm-elem-input').each(function() {
		$(this).draggable("option", "containment", $(this).parent());
	});
	almDroppable($(".alm-elem-block-screen-content, .alm-elem-block, .alm-elem-menu, .alm-elem-form"));
	almRessizeAll($(".alm-elem-block, .alm-elem-form, .alm-elem-input"));
	almRessizeS($(".alm-elem-block-screen"));
	almRessizeEW($(".alm-elem-block-screen-content"));
}

// уберем кнопку добавить меню если оно есть
function hideAddMenu()
{
	// console.log($('.alm-elem-menu').length);
	if ($('.alm-elem-menu').length > 0) $('.menu-add-elem .add-main-menu').hide();
	else $('.menu-add-elem .add-main-menu').show();
}

function fileLoad()
{
	var input = document.createElement('input');
	input.setAttribute('type', 'file');
	input.setAttribute('accept', 'image/*');

	// Note: In modern browsers input[type="file"] is functional without 
	// even adding it to the DOM, but that might not be the case in some older
	// or quirky browsers like IE, so you might want to add it to the DOM
	// just in case, and visually hide it. And do not forget do remove it
	// once you do not need it anymore.

	input.onchange = function() {
		var file = this.files[0];

		// Note: Now we need to register the blob in TinyMCEs image blob
		// registry. In the next release this part hopefully won't be
		// necessary, as we are looking to handle it internally.
		var id = 'blobid' + (new Date()).getTime();
		var blobCache = tinymce.activeEditor.editorUpload.blobCache;
		var blobInfo = blobCache.create(id, file);
		blobCache.add(blobInfo);

		// call the callback and populate the Title field with the file name
		cb(blobInfo.blobUri(), { title: file.name });
	};

	input.click();
}