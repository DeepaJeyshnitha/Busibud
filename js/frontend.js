const manage_html = {
	make_html:function(){
		// var ajax_url='/./Mock_test_1/php/backend.php?XDEBUG_SESSION_START=sublime.xdebug';
		var ajax_url='php/backend.php';
		var ajax_data={};
		var table_div_id='#main_div';
		var input_value='';
	@@ -11,4 +12,4 @@ const manage_html = {
	}
}
let manage_html_obj = Object.create(manage_html);
manage_html_obj.make_html();
