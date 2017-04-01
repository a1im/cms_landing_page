$(document).ready(function() {
	tinymce.init({
		selector: '#textarea-tiny',
		language: 'ru',
		theme: 'modern',
		plugins: [
		'save advlist autolink lists link image charmap preview hr anchor pagebreak',
		'searchreplace wordcount visualblocks visualchars code fullscreen',
		'insertdatetime media nonbreaking save table contextmenu',
		'emoticons template paste textcolor colorpicker textpattern imagetools codesample toc',
		''
		],
		// toolbar1: 'save undo redo | insert | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image',
		// toolbar2: 'fullscreen preview media | forecolor backcolor emoticons | codesample',
		// toolbar3: 'code formatselect fontselect fontsizeselect removeformat',
		toolbar1: "mybutton save fullscreen | bold italic underline strikethrough | alignleft aligncenter alignright alignjustify | styleselect formatselect fontselect fontsizeselect",
		toolbar2: "cut copy paste | searchreplace | bullist numlist | outdent indent blockquote | undo redo | link unlink anchor image editimage media code | insertdatetime preview | forecolor backcolor",
		toolbar3: "table tableprops tablecellprops tablerowprops | hr removeformat | subscript superscript | charmap emoticons | ltr rtl | spellchecker | visualchars visualblocks nonbreaking template pagebreak restoredraft",
		fontsize_formats: '8px 10px 12px 14px 18px 20px 22px 24px 26px 30px 36px 50px 70px 100px',
		// image_prepend_url: "http://frontend.mvc.loc/assets/image/avatar/",
		image_list: [
			{title: 'image1', value: 'noavatar.png'},
			{title: 'image2', value: 'sdagsd.png'}
		],
		image_advtab: true,
		imagetools_cors_hosts: ['frontend.mvc.loc', 'www.tinymce.com', 'codepen.io'],
		imagetools_toolbar: "editimage imageoptions",
		image_title: true,
		automatic_uploads: true,
		file_picker_types: 'image', 
		images_upload_url: 'http://frontend.mvc.loc/editor/site/uploadfile',
		menubar: false,
		content_css: [
		'http://frontend.mvc.loc/assets/css/bootstrap.min.css',
		'http://frontend.mvc.loc/assets/css/mytinymce.css'
		],
		save_onsavecallback: function (e) { 
			option_elem.find('.content-text').html(tinymce.get('textarea-tiny').getContent());
			if (option_elem != null)
			{
				postSaveContent(option_elem);
			}
		},
		// после init вроде
		init_instance_callback: function (editor) {

		},
		// до init
		setup: function(editor) {
			editor.addButton('mybutton', {
				text: "Закрыть",
				tooltip: "Закрыть редактор",
				// icon: 'insertdatetime',
				// image: 'http://p.yusukekamiyamane.com/icons/search/fugue/icons/calendar-blue.png',
				onclick: function () {
					tinymce.get('textarea-tiny').execCommand('mceFullscreen',false,false);
					option_elem.find('.content-text').html(tinymce.get('textarea-tiny').getContent());
					option_elem.css({width:'',height:''});
					if (option_elem != null)
					{
						postSaveContent(option_elem);
					}
					$('#popup-edit-text').hide();
				}
			});
			editor.on('init', function (e) {
				
			});
		},
		// загрузка картинки
		file_picker_callback: function(cb, value, meta) {
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
	});

	// tinymce.init({
	// 	selector: '.alm-elem-text .content-text',
	// 	language: 'ru',
	// 	plugins: [
	// 	'save advlist autolink lists link image charmap print preview anchor',
	// 	'searchreplace visualblocks code fullscreen',
	// 	'insertdatetime media table contextmenu paste'
	// 	],
	// 	toolbar: 'save | insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image',
	// 	inline: true,
	// 	resize: true,
	// 	save_onsavecallback: function () { 
	// 		if (option_elem != null)
	// 		{
	// 			postSaveContent(option_elem);
	// 		}
	// 	}
	// });
});