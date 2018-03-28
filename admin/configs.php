<?php
/**
 * 이 파일은 출판물관리모듈의 일부입니다. (https://www.imodule.kr)
 *
 * 출판물관리모듈의 모듈 환경설정 패널을 가져온다.
 * 
 * @file /modules/publication/admin/configs.php
 * @author Arzz (arzz@arzz.com)
 * @license GPLv3
 * @version 3.0.0
 * @modified 2018. 3. 18.
 */
if (defined('__IM__') == false) exit;
?>
<script>
new Ext.form.Panel({
	id:"ModuleConfigForm",
	border:false,
	bodyPadding:10,
	width:600,
	fieldDefaults:{labelAlign:"right",labelWidth:100,anchor:"100%",allowBlank:true},
	items:[
		new Ext.form.FieldSet({
			title:"기본설정",
			items:[
				Admin.templetField("템플릿","templet","publication",false)
			]
		})
	]
});
</script>