//handle change event of browse button {
$('input#file').change(function(){
	var validated = true;
	var error_msg = '';
	if( $('input#file').val().length > 0 ) {
		var file_name = $('input#file').val();
		var file_name_parts = file_name.split('.');
		var file_name_size = file_name_parts.length;
		var last_elem = file_name_size-1;
		var ext = file_name_parts[last_elem];
		if( ext == 'jpeg' || ext == 'jpg' || ext == 'png' || ext == 'gif' || ext == 'bmp' ) {
			
		} else {
			error_msg = 'Please select valid file format';
			validated = false;
		}
	}
	if(validated==false) {
	    $('input#file').val('');
		alert(error_msg);
	} else {
		var curr_url = $('img#profile_img_id').attr('src');
		var add_remove_btn = false;
		if(curr_url == 'http://cj.ibnlive.in.com/wp-content/themes/cj-revamp/images/default_profile.jpg') {
			add_remove_btn = true;
		}		
		
		var options = { 
			target:     'img#profile_img_id', 
			url:        '../wp-profile-update-img.php', 
			success:    function(data) { 
				var d = new Date();
				var random = d.getTime();
				$('img#profile_img_id').attr('src',data+'?'+random);
				if(add_remove_btn){
					$('span#del_profile_pic_wrapper').html('<a style="color:#0088BA;font-weight:bold" id="del_profile_pic" href="javascript:void(0)">Remove Profile Pic</a>');
				}
				$('img#ajax_loader').fadeOut();
			} 
		}; 
		
		$('img#ajax_loader').fadeIn('fast');
		$('form#frm').ajaxForm(options).submit();
		$('form#frm').ajaxFormUnbind();
	}
}); //end handle change event of browse button }