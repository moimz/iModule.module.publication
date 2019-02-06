<?php
/**
 * 이 파일은 iModule 출판물관리모듈의 일부입니다. (https://www.imodules.io)
 *
 * 출판물관리모듈의 관리자 UI 를 구성한다.
 * 
 * @file /modules/publication/admin/index.php
 * @author Arzz (arzz@arzz.com)
 * @license GPLv3
 * @version 3.0.0
 * @modified 2019. 2. 6.
 */
if (defined('__IM__') == false) exit;
?>
<script>
Ext.onReady(function () { Ext.getCmp("iModuleAdminPanel").add(
	new Ext.TabPanel({
		id:"ModulePublication",
		border:false,
		tabPosition:"bottom",
		items:[
			new Ext.grid.Panel({
				id:"ModulePublicationArticleList",
				iconCls:"xi xi-paper",
				title:"출판물 관리",
				border:false,
				tbar:[
					new Ext.form.ComboBox({
						id:"ModulePublicationArticleCategory",
						store:new Ext.data.JsonStore({
							proxy:{
								type:"ajax",
								url:ENV.getProcessUrl("publication","@getCategories"),
								extraParams:{is_all:"true"},
								reader:{type:"json"}
							},
							remoteSort:false,
							sorters:[{property:"sort",direction:"ASC"}],
							fields:["idx","title"]
						}),
						width:140,
						autoLoadOnValue:true,
						editable:false,
						matchFieldWidth:false,
						listConfig:{
							minWidth:140
						},
						displayField:"title",
						valueField:"idx",
						value:"",
						listeners:{
							change:function(form,value) {
								Ext.getCmp("ModulePublicationArticleList").getStore().getProxy().setExtraParam("category",value);
								Ext.getCmp("ModulePublicationArticleList").getStore().loadPage(1);
							}
						}
					}),
					new Ext.form.TextField({
						id:"ModulePublicationArticleKeyword",
						width:150,
						emptyText:"검색어"
					}),
					new Ext.Button({
						iconCls:"mi mi-search",
						handler:function() {
							Ext.getCmp("ModulePublicationArticleList").getStore().getProxy().setExtraParam("keyword",Ext.getCmp("ModulePublicationArticleKeyword").getValue());
							Ext.getCmp("ModulePublicationArticleList").getStore().loadPage(1);
						}
					}),
					"-",
					new Ext.Button({
						text:"출판물 추가",
						iconCls:"mi mi-add",
						handler:function() {
							Publication.article.add();
						}
					}),
					new Ext.Button({
						text:"선택 출판물 삭제",
						iconCls:"mi mi-trash",
						handler:function() {
							Publication.article.delete();
						}
					})
				],
				store:new Ext.data.JsonStore({
					proxy:{
						type:"ajax",
						simpleSortMode:true,
						url:ENV.getProcessUrl("publication","@getArticles"),
						reader:{type:"json"}
					},
					remoteSort:false,
					sorters:[{property:"year",direction:"DESC"}],
					autoLoad:true,
					pageSize:50,
					fields:["idx","type","title",{name:"article",type:"int"},{name:"sort",type:"int"}],
					listeners:{
						load:function(store,records,success,e) {
							if (success == false) {
								if (e.getError()) {
									Ext.Msg.show({title:Admin.getText("alert/error"),msg:e.getError(),buttons:Ext.Msg.OK,icon:Ext.Msg.ERROR});
								} else {
									Ext.Msg.show({title:Admin.getText("alert/error"),msg:Admin.getErrorText("LOAD_DATA_FAILED"),buttons:Ext.Msg.OK,icon:Ext.Msg.ERROR});
								}
							}
						}
					}
				}),
				columns:[{
					text:"출판물명",
					minWidth:150,
					flex:1,
					dataIndex:"title",
					sortable:true,
					renderer:function(value,p,record) {
						var sHTML = "";
						if (record.data.file != null) {
							sHTML+= '<i style="display:inline-block; width:16px; height:16px; background:url('+record.data.file.icon+') no-repeat 0 50%; vertical-align:middle; float:left; background-size:contain; margin-right:5px;"></i>';
						}
						sHTML+= value;
						return sHTML;
					}
				},{
					text:"카테고리(유형)",
					width:180,
					dataIndex:"category",
					renderer:function(value,p,record) {
						return value+"("+Publication.getText("type/"+record.data.type)+")";
					}
				},{
					text:"페이지",
					width:100,
					dataIndex:"page"
				},{
					text:"저자",
					width:200,
					dataIndex:"author"
				},{
					text:"매체",
					width:200,
					dataIndex:"publisher"
				},{
					text:"연도",
					width:60,
					dataIndex:"year",
					sortable:true
				},{
					text:"매체코드",
					width:180,
					dataIndex:"publisher_code"
				}],
				selModel:new Ext.selection.CheckboxModel(),
				bbar:new Ext.PagingToolbar({
					store:null,
					displayInfo:false,
					items:[
						"->",
						{xtype:"tbtext",text:Admin.getText("text/grid_help")}
					],
					listeners:{
						beforerender:function(tool) {
							tool.bindStore(Ext.getCmp("ModulePublicationArticleList").getStore());
						}
					}
				}),
				listeners:{
					itemdblclick:function(grid,record) {
						Publication.article.add(record.data.idx);
					},
					itemcontextmenu:function(grid,record,item,index,e) {
						var menu = new Ext.menu.Menu();
						
						menu.add('<div class="x-menu-title">'+record.data.title+'</div>');
						
						menu.add({
							iconCls:"xi xi-form",
							text:"출판물 수정",
							handler:function() {
								Publication.article.add(record.data.idx);
							}
						});

						menu.add({
							iconCls:"mi mi-trash",
							text:"출판물 삭제",
							handler:function() {
								Publication.article.delete();
							}
						});
						
						e.stopEvent();
						menu.showAt(e.getXY());
					}
				}
			}),
			new Ext.grid.Panel({
				id:"ModulePublicationCategoryList",
				iconCls:"fa fa-sitemap",
				title:"카테고리 관리",
				border:false,
				tbar:[
					new Ext.Button({
						text:"카테고리 추가",
						iconCls:"mi mi-add",
						handler:function() {
							Publication.category.add();
						}
					}),
					new Ext.Button({
						text:"선택 카테고리 삭제",
						iconCls:"mi mi-trash",
						handler:function() {
							Publication.category.delete();
						}
					})
				],
				store:new Ext.data.JsonStore({
					proxy:{
						type:"ajax",
						simpleSortMode:true,
						url:ENV.getProcessUrl("publication","@getCategories"),
						reader:{type:"json"}
					},
					remoteSort:false,
					sorters:[{property:"sort",direction:"ASC"}],
					autoLoad:true,
					pageSize:0,
					fields:["idx","type","title",{name:"article",type:"int"},{name:"sort",type:"int"}],
					listeners:{
						load:function(store,records,success,e) {
							if (success == false) {
								if (e.getError()) {
									Ext.Msg.show({title:Admin.getText("alert/error"),msg:e.getError(),buttons:Ext.Msg.OK,icon:Ext.Msg.ERROR});
								} else {
									Ext.Msg.show({title:Admin.getText("alert/error"),msg:Admin.getErrorText("LOAD_DATA_FAILED"),buttons:Ext.Msg.OK,icon:Ext.Msg.ERROR});
								}
							}
						}
					}
				}),
				columns:[{
					text:"카테고리명",
					minWidth:150,
					flex:1,
					dataIndex:"title"
				},{
					text:"유형",
					width:150,
					dataIndex:"type",
					renderer:function(value) {
						return Publication.getText("type/"+value);
					}
				},{
					text:"등록수",
					width:100,
					dataIndex:"article",
					align:"right",
					renderer:function(value) {
						return Ext.util.Format.number(value,"0,000")+"개";
					}
				}],
				selModel:new Ext.selection.CheckboxModel(),
				bbar:[
					new Ext.Button({
						iconCls:"fa fa-caret-up",
						handler:function() {
							Admin.gridSort(Ext.getCmp("ModulePublicationCategoryList"),"sort","up");
							Admin.gridSave(Ext.getCmp("ModulePublicationCategoryList"),ENV.getProcessUrl("publication","@saveCategorySort"),500);
						}
					}),
					new Ext.Button({
						iconCls:"fa fa-caret-down",
						handler:function() {
							Admin.gridSort(Ext.getCmp("ModulePublicationCategoryList"),"sort","down");
							Admin.gridSave(Ext.getCmp("ModulePublicationCategoryList"),ENV.getProcessUrl("publication","@saveCategorySort"),500);
						}
					}),
					"-",
					new Ext.Button({
						iconCls:"x-tbar-loading",
						handler:function() {
							Ext.getCmp("ModulePublicationCategoryList").getStore().reload();
						}
					}),
					"->",
					{xtype:"tbtext",text:Admin.getText("text/grid_help")}
				],
				listeners:{
					itemdblclick:function(grid,record) {
						Publication.category.add(record.data.idx);
					},
					itemcontextmenu:function(grid,record,item,index,e) {
						var menu = new Ext.menu.Menu();
						
						menu.add('<div class="x-menu-title">'+record.data.title+'</div>');
						
						menu.add({
							iconCls:"xi xi-form",
							text:"카테고리 수정",
							handler:function() {
								Publication.category.add(record.data.idx);
							}
						});

						menu.add({
							iconCls:"mi mi-trash",
							text:"카테고리 삭제",
							handler:function() {
								Publication.category.delete();
							}
						});
						
						e.stopEvent();
						menu.showAt(e.getXY());
					}
				}
			}),
			new Ext.grid.Panel({
				id:"ModulePublicationPublisherList",
				iconCls:"xi xi-book-spread",
				title:"유형별 매체관리",
				border:false,
				tbar:[
					new Ext.form.ComboBox({
						store:new Ext.data.ArrayStore({
							fields:["display","value"],
							data:(function() {
								var datas = [];
								datas.push(["전체유형",""]);
								for (var value in Publication.getText("type")) {
									if (value == "THESIS") continue;
									datas.push([Publication.getText("type/"+value),value]);
								}
								return datas;
							})()
						}),
						width:150,
						displayField:"display",
						valueField:"value",
						value:"",
						listeners:{
							change:function(form,value) {
								Ext.getCmp("ModulePublicationPublisherList").getStore().getProxy().setExtraParam("type",value);
								Ext.getCmp("ModulePublicationPublisherList").getStore().loadPage(1);
							}
						}
					}),
					new Ext.form.TextField({
						id:"ModulePublicationPublisherKeyword",
						width:150,
						emptyText:"검색어"
					}),
					new Ext.Button({
						iconCls:"mi mi-search",
						handler:function() {
							Ext.getCmp("ModulePublicationPublisherList").getStore().getProxy().setExtraParam("keyword",Ext.getCmp("ModulePublicationPublisherKeyword").getValue());
							Ext.getCmp("ModulePublicationPublisherList").getStore().loadPage(1);
						}
					}),
					"-",
					new Ext.Button({
						text:"매체 추가",
						iconCls:"mi mi-add",
						handler:function() {
							Publication.publisher.add();
						}
					}),
					new Ext.Button({
						text:"선택 매체 삭제",
						iconCls:"mi mi-trash",
						handler:function() {
							Publication.publisher.delete();
						}
					})
				],
				store:new Ext.data.JsonStore({
					proxy:{
						type:"ajax",
						simpleSortMode:true,
						url:ENV.getProcessUrl("publication","@getPublishers"),
						reader:{type:"json"}
					},
					remoteSort:true,
					sorters:[{property:"title",direction:"ASC"}],
					autoLoad:true,
					pageSize:50,
					fields:["idx","type","title",{name:"article",type:"int"},{name:"sort",type:"int"}],
					listeners:{
						load:function(store,records,success,e) {
							if (success == false) {
								if (e.getError()) {
									Ext.Msg.show({title:Admin.getText("alert/error"),msg:e.getError(),buttons:Ext.Msg.OK,icon:Ext.Msg.ERROR});
								} else {
									Ext.Msg.show({title:Admin.getText("alert/error"),msg:Admin.getErrorText("LOAD_DATA_FAILED"),buttons:Ext.Msg.OK,icon:Ext.Msg.ERROR});
								}
							}
						}
					}
				}),
				columns:[{
					text:"유형",
					width:150,
					dataIndex:"type",
					renderer:function(value) {
						return Publication.getText("type/"+value);
					}
				},{
					text:"매체명",
					minWidth:150,
					flex:1,
					dataIndex:"title",
					renderer:function(value,p,record) {
						if (record.data.type == "CONFERENCE") {
							return value + "(" + moment(record.data.start_date).locale("ko").format("YYYY.MM.DD(dd)") + " ~ " + moment(record.data.end_date).locale("ko").format("YYYY.MM.DD(dd)") + " / " + record.data.city + ", " + Publication.getText("country/"+record.data.country)+")";
						} else {
							return value;
						}
					}
				},{
					text:"등록수",
					width:100,
					dataIndex:"article",
					align:"right",
					renderer:function(value) {
						return Ext.util.Format.number(value,"0,000")+"개";
					}
				},{
					text:"링크",
					width:200,
					dataIndex:"link",
					renderer:function(value) {
						return '<a href="'+value+'" target="_blank">'+value+'</a>';
					}
				}],
				selModel:new Ext.selection.CheckboxModel(),
				bbar:new Ext.PagingToolbar({
					store:null,
					displayInfo:false,
					items:[
						"->",
						{xtype:"tbtext",text:Admin.getText("text/grid_help")}
					],
					listeners:{
						beforerender:function(tool) {
							tool.bindStore(Ext.getCmp("ModulePublicationPublisherList").getStore());
						}
					}
				}),
				listeners:{
					itemdblclick:function(grid,record) {
						Publication.publisher.add(record.data.idx);
					},
					itemcontextmenu:function(grid,record,item,index,e) {
						var menu = new Ext.menu.Menu();
						
						menu.add('<div class="x-menu-title">'+record.data.title+'</div>');
						
						menu.add({
							iconCls:"xi xi-form",
							text:"매체 수정",
							handler:function() {
								Publication.publisher.add(record.data.idx);
							}
						});

						menu.add({
							iconCls:"mi mi-trash",
							text:"매체 삭제",
							handler:function() {
								Publication.publisher.delete();
							}
						});
						
						e.stopEvent();
						menu.showAt(e.getXY());
					}
				}
			})
		]
	})
); });
</script>
