function readURL(input) {
	if ( input.files && input.files[0] ) {
		if ( input.files[0].type.match('image.*') ) {
			var reader = new FileReader();
			reader.onload = function(e) {
				$('#preview_image').attr('src', e.target.result);
			}
			reader.readAsDataURL(input.files[0]);
		} else {
			console.log('is not image mime type');
			return false;
		}
	} else {
		console.log('not isset files data or files API not supordet');
		return false;
	}
	return true;
}

$(document).ready(function() {
	$("button[name='preview']").click(function() {
			// $("input[name='name'").val();
			$("#preview").css({'display': 'block'});
			$("#preview").find(".media-heading").html($("input[name='name']").val()+" : "+$("input[name='email']").val());
			$("#preview").find(".message").html($("textarea[name='text']").val());
			if (!readURL($("input[name='avatar']")[0])) {
				$('#preview_image').attr('src', "/assets/image/avatar/noavatar.png");
			}
		});
});