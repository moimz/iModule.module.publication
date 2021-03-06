<?php
/**
 * 이 파일은 iModule 출판물관리모듈의 일부입니다. (https://www.imodules.io)
 *
 * 출판물관리모듈의 모듈 환경설정 패널을 가져온다.
 * 
 * @file /modules/publication/admin/configs.php
 * @author Arzz (arzz@arzz.com)
 * @license GPLv3
 * @version 3.0.0
 * @modified 2018. 9. 27.
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
				Admin.templetField("템플릿","templet","module","publication",false),
				new Ext.form.ComboBox({
					fieldLabel:"저자그룹",
					name:"author_label",
					store:new Ext.data.JsonStore({
						proxy:{
							type:"ajax",
							url:ENV.getProcessUrl("member","@getLabels"),
							extraParams:{type:"all_label"},
							reader:{type:"json"}
						},
						autoLoad:true,
						remoteSort:false,
						fields:["idx","title"]
					}),
					autoLoadOnValue:true,
					editable:false,
					displayField:"title",
					valueField:"idx",
					value:0,
					listeners:{
						change:function(form,value) {
							form.getForm().findField("author_name").getStore().getProxy().setExtraParam("label",value);
							form.getForm().findField("author_name").getStore().reload();
						}
					},
					afterBodyEl:'<div class="x-form-help">저자를 검색할 기본 회원라벨을 선택할 수 있습니다.</div>'
				}),
				new Ext.form.ComboBox({
					fieldLabel:"저자명",
					name:"author_name",
					store:new Ext.data.JsonStore({
						proxy:{
							type:"ajax",
							url:ENV.getProcessUrl("member","@getSignUpFields"),
							extraParams:{label:0,is_default:"true",is_extra:"true"},
							reader:{type:"json"}
						},
						autoLoad:true,
						remoteSort:false,
						fields:["name","title"]
					}),
					autoLoadOnValue:true,
					editable:false,
					displayField:"title",
					valueField:"name",
					afterBodyEl:'<div class="x-form-help">저자명으로 표시할 회원정보필드를 선택하여 주십시오.</div>'
				}),
				new Ext.form.Checkbox({
					fieldLabel:"저자사진",
					name:"author_photo",
					uncheckedValue:"",
					boxLabel:"목록페이지에서 저자가 회원인 경우 회원사진을 표시합니다."
				})
			]
		})
	]
});
</script>