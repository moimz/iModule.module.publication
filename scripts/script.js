/**
 * 이 파일은 출판물관리모듈의 일부입니다. (https://www.imodule.kr)
 *
 * 출판물관리 UI 이벤트를 정의한다.
 * 
 * @file /modules/publication/scripts/script.js
 * @author Arzz (arzz@arzz.com)
 * @license MIT License
 * @version 3.0.0
 * @modified 2018. 5. 11.
 */
var Publication = {
	getUrl:function(view,idx) {
		return ENV.getUrl(null,null,view,idx);
	},
	list:{
		init:function() {
			var $form = $("#ModulePublicationListForm");
			
			$form.on("submit",function() {
				var mode = $("input[name=mode]",$form).val();
				var $code = $("select[name=code]",$form);
				var $keyword = $("input[name=keyword]",$form);
				if ($code.length == 1 && $code.val()) {
					$form.attr("action",Publication.getUrl("list",mode+"/"+$code.val()+"/1"));
					$("select[name=code]",$form).disable();
				}
				
				if ($keyword.val().length == 0) {
					$keyword.disable();
				}
				$("input[name=mode]",$form).disable();
			});
		}
	}
};