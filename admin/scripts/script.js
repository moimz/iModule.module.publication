/**
 * 이 파일은 출판물관리모듈의 일부입니다. (https://www.imodule.kr)
 *
 * 출판물관리모듈의 관리자 UI 를 구성한다.
 * 
 * @file /modules/publication/admin/index.php
 * @author Arzz (arzz@arzz.com)
 * @license MIT License
 * @version 3.0.0
 * @modified 2018. 5. 11.
 */
var Publication = {
	/**
	 * 출판물 관리
	 */
	article:{
		add:function(idx) {
			new Ext.Window({
				id:"ModulePublicationArticleAddWindow",
				title:(idx ? "출판물 수정" : "출판물 추가"),
				width:700,
				modal:true,
				autoScroll:true,
				border:false,
				items:[
					new Ext.form.Panel({
						id:"ModulePublicationArticleAddForm",
						border:false,
						bodyPadding:"10 10 5 10",
						fieldDefaults:{labelAlign:"right",labelWidth:80,anchor:"100%",allowBlank:false},
						items:[
							new Ext.form.Hidden({
								name:"idx"
							}),
							new Ext.form.FieldSet({
								title:"카테고리 선택",
								items:[
									new Ext.form.ComboBox({
										fieldLabel:"카테고리",
										name:"category",
										store:new Ext.data.JsonStore({
											proxy:{
												type:"ajax",
												url:ENV.getProcessUrl("publication","@getCategories"),
												reader:{type:"json"}
											},
											autoLoad:true,
											remoteSort:false,
											fields:["idx","title"],
											listeners:{
												load:function(store) {
													if (idx === undefined && store.getCount() > 0) {
														Ext.getCmp("ModulePublicationArticleAddForm").getForm().findField("category").setValue(store.getAt(0).get("idx"));
													}
												}
											}
										}),
										autoLoadOnValue:true,
										editable:false,
										displayField:"title",
										valueField:"idx",
										afterBodyEl:'<div class="x-form-help">카테고리에 따라 입력폼이 변경됩니다.</div>',
										listeners:{
											change:function(form,value) {
												var index = form.getStore().findExact("idx",value);
												var data = form.getStore().getAt(index).data;
												
												Ext.getCmp("ModulePublicationArticleAddForm-PAPER").hide();
												Ext.getCmp("ModulePublicationArticleAddForm-PAPER").disable();
												
												Ext.getCmp("ModulePublicationArticleAddForm-THESIS").hide();
												Ext.getCmp("ModulePublicationArticleAddForm-THESIS").disable();
												
												Ext.getCmp("ModulePublicationArticleAddForm-CONFERENCE").hide();
												Ext.getCmp("ModulePublicationArticleAddForm-CONFERENCE").disable();
												
												Ext.getCmp("ModulePublicationArticleAddForm-PATENT").hide();
												Ext.getCmp("ModulePublicationArticleAddForm-PATENT").disable();
												
												Ext.getCmp("ModulePublicationArticleAddForm-BOOK").hide();
												Ext.getCmp("ModulePublicationArticleAddForm-BOOK").disable();
												
												Ext.getCmp("ModulePublicationArticleAddForm-MEDIA").hide();
												Ext.getCmp("ModulePublicationArticleAddForm-MEDIA").disable();
												
												Ext.getCmp("ModulePublicationArticleAddForm-BOOK").hide();
												Ext.getCmp("ModulePublicationArticleAddForm-BOOK").disable();
												
												Ext.getCmp("ModulePublicationArticleAddForm-"+data.type).show();
												Ext.getCmp("ModulePublicationArticleAddForm-"+data.type).enable();
												
												if (value == "BOOK") {
													Ext.getCmp("ModulePublicationArticleAddAttachment").setTitle("표지이미지");
												} else {
													Ext.getCmp("ModulePublicationArticleAddAttachment").setTitle("첨부파일");
												}
											}
										}
									})
								]
							}),
							new Ext.form.FieldSet({
								id:"ModulePublicationArticleAddForm-PAPER",
								title:"논문정보 입력",
								items:[
									new Ext.form.TextField({
										fieldLabel:"논문명",
										name:"paper_title"
									}),
									new Ext.form.FieldContainer({
										fieldLabel:"저널",
										layout:"hbox",
										items:[
											new Ext.form.ComboBox({
												name:"paper_publisher",
												store:new Ext.data.JsonStore({
													proxy:{
														type:"ajax",
														url:ENV.getProcessUrl("publication","@getPublishers"),
														extraParams:{type:"PAPER"},
														reader:{type:"json"}
													},
													autoLoad:true,
													pageSize:0,
													remoteSort:false,
													sorters:[{property:"title",direction:"ASC"}],
													fields:["idx","title"]
												}),
												flex:1,
												autoLoadOnValue:true,
												editable:false,
												displayField:"title",
												valueField:"idx"
											}),
											new Ext.Button({
												iconCls:"mi mi-add",
												text:"저널추가",
												style:{marginLeft:"5px"},
												handler:function() {
													Publication.publisher.add("PAPER");
												}
											})
										]
									}),
									new Ext.form.FieldContainer({
										layout:"hbox",
										items:[
											new Ext.form.TextField({
												fieldLabel:"연도",
												name:"paper_year",
												value:moment().format("YYYY"),
												flex:1
											}),
											new Ext.form.TextField({
												fieldLabel:"권",
												name:"paper_volume_no",
												flex:1
											}),
											new Ext.form.TextField({
												fieldLabel:"호",
												name:"paper_issue_no",
												flex:1
											}),
											new Ext.form.TextField({
												fieldLabel:"페이지",
												name:"paper_page_no",
												flex:1,
												emptyText:"10-15"
											})
										]
									}),
									new Ext.form.TextArea({
										fieldLabel:"개요",
										name:"paper_abstract",
										height:150
									}),
									new Ext.form.TextField({
										fieldLabel:"DOI",
										name:"paper_link",
										allowBlank:true
									}),
									new Ext.form.TextField({
										fieldLabel:"키워드",
										name:"paper_keyword",
										allowBlank:true,
										emptyText:"키워드는 세미콜론(;)으로 구분하여 입력하여 주십시오."
									})
								]
							}),
							new Ext.form.FieldSet({
								id:"ModulePublicationArticleAddForm-THESIS",
								title:"논문정보 입력",
								items:[
									new Ext.form.TextField({
										fieldLabel:"논문명",
										name:"thesis_title"
									}),
									new Ext.form.FieldContainer({
										layout:"hbox",
										items:[
											new Ext.form.ComboBox({
												fieldLabel:"구분",
												name:"thesis_type",
												store:new Ext.data.ArrayStore({
													fields:["display","value"],
													data:[["Ph.D","Ph.D"],["MS","MS"]]
												}),
												displayField:"display",
												valueField:"value",
												value:"Ph.D",
												flex:1
											}),
											new Ext.form.TextField({
												fieldLabel:"연도",
												name:"thesis_year",
												value:moment().format("YYYY"),
												flex:1
											}),
											new Ext.form.ComboBox({
												fieldLabel:"학기(월)",
												name:"thesis_month",
												store:new Ext.data.ArrayStore({
													fields:["display","value"],
													data:[["February","2"],["August","8"]]
												}),
												displayField:"display",
												valueField:"value",
												value:"2",
												flex:1
											})
										]
									})
								]
							}),
							new Ext.form.FieldSet({
								id:"ModulePublicationArticleAddForm-CONFERENCE",
								title:"컨퍼런스 입력",
								items:[
									new Ext.form.TextField({
										fieldLabel:"발표제목",
										name:"conference_title"
									}),
									new Ext.form.FieldContainer({
										fieldLabel:"컨퍼런스명",
										layout:"hbox",
										items:[
											new Ext.form.ComboBox({
												name:"conference_publisher",
												store:new Ext.data.JsonStore({
													proxy:{
														type:"ajax",
														url:ENV.getProcessUrl("publication","@getPublishers"),
														extraParams:{type:"CONFERENCE"},
														reader:{type:"json"}
													},
													autoLoad:true,
													pageSize:0,
													remoteSort:false,
													sorters:[{property:"title",direction:"ASC"}],
													fields:["idx","title"]
												}),
												flex:1,
												autoLoadOnValue:true,
												editable:false,
												displayField:"title",
												valueField:"idx"
											}),
											new Ext.Button({
												iconCls:"mi mi-add",
												text:"컨퍼런스추가",
												style:{marginLeft:"5px"},
												handler:function() {
													Publication.publisher.add("CONFERENCE");
												}
											})
										]
									}),
									new Ext.form.FieldContainer({
										fieldLabel:"구분",
										layout:"hbox",
										items:[
											new Ext.form.ComboBox({
												name:"conference_type",
												store:new Ext.data.ArrayStore({
													fields:["display","value"],
													data:[["Oral","Oral"],["Poster","Poster"]]
												}),
												displayField:"display",
												valueField:"value",
												value:"Oral",
												width:100
											})
										]
									})
								]
							}),
							new Ext.form.FieldSet({
								id:"ModulePublicationArticleAddForm-PATENT",
								title:"특허정보 입력",
								items:[
									new Ext.form.TextField({
										fieldLabel:"특허제목",
										name:"patent_title"
									}),
									new Ext.form.FieldContainer({
										layout:"hbox",
										items:[
											new Ext.form.ComboBox({
												fieldLabel:"구분",
												name:"patent_type",
												store:new Ext.data.ArrayStore({
													fields:["display","value"],
													data:[["출원",1],["등록",2]]
												}),
												displayField:"display",
												valueField:"value",
												value:1,
												width:200,
												listeners:{
													change:function(form,value) {
														if (value == 1) {
															form.getForm().findField("patent_date").setFieldLabel("출원일");
															form.getForm().findField("patent_no").setFieldLabel("출원번호");
														} else {
															form.getForm().findField("patent_date").setFieldLabel("등록일");
															form.getForm().findField("patent_no").setFieldLabel("등록번호");
														}
													}
												}
											}),
											new Ext.form.DateField({
												fieldLabel:"출원일",
												name:"patent_date",
												format:"Y-m-d",
												value:moment().format("YYYY-MM-DD"),
												width:200
											}),
											new Ext.form.TextField({
												fieldLabel:"출원번호",
												name:"patent_no",
												flex:1
											})
										]
									})
								]
							}),
							new Ext.form.FieldSet({
								id:"ModulePublicationArticleAddForm-BOOK",
								title:"도서정보 입력",
								disabled:true,
								hidden:true,
								items:[
									new Ext.form.TextField({
										fieldLabel:"도서명",
										name:"book_title"
									}),
									new Ext.form.FieldContainer({
										fieldLabel:"출판사",
										layout:"hbox",
										items:[
											new Ext.form.ComboBox({
												name:"book_publisher",
												store:new Ext.data.JsonStore({
													proxy:{
														type:"ajax",
														url:ENV.getProcessUrl("publication","@getPublishers"),
														extraParams:{type:"BOOK"},
														reader:{type:"json"}
													},
													autoLoad:true,
													pageSize:0,
													remoteSort:false,
													sorters:[{property:"title",direction:"ASC"}],
													fields:["idx","title"]
												}),
												flex:1,
												autoLoadOnValue:true,
												editable:false,
												displayField:"title",
												valueField:"idx"
											}),
											new Ext.Button({
												iconCls:"mi mi-add",
												text:"출판사추가",
												style:{marginLeft:"5px"},
												handler:function() {
													Publication.publisher.add("BOOK");
												}
											})
										]
									}),
									new Ext.form.FieldContainer({
										layout:"hbox",
										items:[
											new Ext.form.TextField({
												fieldLabel:"출판년도",
												name:"book_year",
												value:moment().format("YYYY"),
												flex:1
											}),
											new Ext.form.TextField({
												fieldLabel:"ISBN",
												name:"book_page_no",
												flex:3
											})
										]
									}),
									new Ext.form.TextArea({
										fieldLabel:"목차",
										name:"book_abstract",
										height:150
									}),
									new Ext.form.TextField({
										fieldLabel:"도서링크",
										name:"book_link",
										allowBlank:true
									})
								]
							}),
							new Ext.form.FieldSet({
								id:"ModulePublicationArticleAddForm-MEDIA",
								title:"미디어 입력",
								items:[
									new Ext.form.TextField({
										fieldLabel:"기사/방송명",
										name:"media_title"
									}),
									new Ext.form.FieldContainer({
										fieldLabel:"언론매체",
										layout:"hbox",
										items:[
											new Ext.form.ComboBox({
												name:"media_publisher",
												store:new Ext.data.JsonStore({
													proxy:{
														type:"ajax",
														url:ENV.getProcessUrl("publication","@getPublishers"),
														extraParams:{type:"MEDIA"},
														reader:{type:"json"}
													},
													autoLoad:true,
													pageSize:0,
													remoteSort:false,
													sorters:[{property:"title",direction:"ASC"}],
													fields:["idx","title"]
												}),
												flex:1,
												autoLoadOnValue:true,
												editable:false,
												displayField:"title",
												valueField:"idx"
											}),
											new Ext.Button({
												iconCls:"mi mi-add",
												text:"언론매체추가",
												style:{marginLeft:"5px"},
												handler:function() {
													Publication.publisher.add("MEDIA");
												}
											})
										]
									}),
									new Ext.form.DateField({
										fieldLabel:"기사게시일",
										name:"media_date",
										format:"Y-m-d",
										value:moment().format("YYYY-MM-DD")
									}),
									new Ext.form.TextField({
										fieldLabel:"기사링크",
										name:"media_link",
										allowBlank:true
									})
								]
							}),
							new Ext.form.FieldSet({
								id:"ModulePublicationArticleAddForm-AUTHOR",
								title:"저자",
								items:[
									new Ext.form.Hidden({
										name:"author"
									}),
									new Ext.Panel({
										margin:"0 0 -1 0",
										html:'<div class="x-box-default">저자가 회원일 경우 회원검색을 통해 저자를 추가하면, 회원 프로필 연동 및 검색에 연동됩니다.<br>저자가 회원이 아닌 경우 저자추가 버튼을 클릭하여 수기로 등록할 수 있습니다.</div>'
									}),
									new Ext.grid.Panel({
										id:"ModulePublicationArticleAddFormAuthorList",
										border:true,
										minHeight:200,
										step:0,
										tbar:[
											new Ext.Button({
												text:"회원검색으로 저자추가",
												iconCls:"mi mi-search",
												handler:function() {
													Publication.author.search();
												}
											}),
											new Ext.Button({
												text:"저자추가(수기)",
												iconCls:"mi mi-add",
												handler:function() {
													Publication.author.add();
												}
											}),
											new Ext.Button({
												text:"선택 저자 삭제",
												iconCls:"mi mi-trash",
												handler:function() {
													var selected = Ext.getCmp("ModulePublicationArticleAddFormAuthorList").getSelectionModel().getSelection();
													if (selected.length == 0) {
														Ext.Msg.show({title:Admin.getText("alert/error"),msg:"삭제할 저자를 선택하여 주십시오.",buttons:Ext.Msg.OK,icon:Ext.Msg.ERROR});
														return;
													}
													Ext.getCmp("ModulePublicationArticleAddFormAuthorList").getStore().remove(selected);
												}
											})
										],
										store:new Ext.data.ArrayStore({
											fields:["midx","name",{name:"sort",type:"int"}],
											data:[],
											sorters:[{property:"sort",direction:"ASC"}],
										}),
										columns:[{
											text:"저자명",
											minWidth:150,
											flex:1,
											dataIndex:"name",
											renderer:function(value,p,record) {
												var sHTML = "";
												if (record.data.midx > 0) sHTML+= '<span style="color:red;">[회원]</span>';
												sHTML+= value;
												
												return sHTML;
											}
										},{
											dataIndex:"sort",
											hidden:true,
											hidable:false
										}],
										selModel:new Ext.selection.CheckboxModel(),
										bbar:[
											new Ext.Button({
												iconCls:"fa fa-caret-up",
												handler:function() {
													Admin.gridSort(Ext.getCmp("ModulePublicationArticleAddFormAuthorList"),"sort","up");
												}
											}),
											new Ext.Button({
												iconCls:"fa fa-caret-down",
												handler:function() {
													Admin.gridSort(Ext.getCmp("ModulePublicationArticleAddFormAuthorList"),"sort","down");
												}
											}),
											"->",
											{xtype:"tbtext",text:"더블클릭 : 저자수정 / 마우스우클릭 : 상세메뉴"}
										],
										listeners:{
											itemdblclick:function(grid,record,p,index) {
												grid.getSelectionModel().select(record);
												Publication.author.add(index);
											},
											itemcontextmenu:function(grid,record,item,index,e) {
												var menu = new Ext.menu.Menu();
												
												grid.getSelectionModel().select(record);
												
												menu.add('<div class="x-menu-title">'+record.data.name+'</div>');
												
												menu.add({
													text:"저자수정",
													iconCls:"xi xi-form",
													handler:function() {
														if (record.data.midx > 0) {
															Publication.author.search(index);
														} else {
															Publication.author.add(index);
														}
													}
												});
												
												menu.add({
													text:"저자삭제",
													iconCls:"xi xi-trash",
													handler:function() {
														grid.getStore().remove(record);
													}
												});
												
												e.stopEvent();
												menu.showAt(e.getXY());
											}
										}
									})
								]
							}),
							new Ext.form.FieldSet({
								id:"ModulePublicationArticleAddAttachment",
								title:"첨부파일",
								items:[
									new Ext.form.FieldContainer({
										layout:{type:"vbox",align:"stretch"},
										style:{marginBottom:0},
										items:[
											new Ext.form.FileUploadField({
												name:"file",
												buttonText:"첨부파일선택",
												allowBlank:true,
												style:{marginBottom:0}
											}),
											new Ext.form.Checkbox({
												boxLabel:"첨부된 파일 삭제하기",
												name:"file_delete",
												hidden:true,
												style:{marginBottom:0}
											})
										]
									})
								]
							})
						]
					})
				],
				buttons:[
					new Ext.Button({
						text:Admin.getText("button/confirm"),
						handler:function() {
							var author = Admin.grid(Ext.getCmp("ModulePublicationArticleAddFormAuthorList"),["midx","name"]);
							Ext.getCmp("ModulePublicationArticleAddForm").getForm().findField("author").setValue(JSON.stringify(author));
							
							Ext.getCmp("ModulePublicationArticleAddForm").getForm().submit({
								url:ENV.getProcessUrl("publication","@saveArticle"),
								submitEmptyText:false,
								waitTitle:Admin.getText("action/wait"),
								waitMsg:Admin.getText("action/saving"),
								success:function(form,action) {
									Ext.Msg.show({title:Admin.getText("alert/info"),msg:Admin.getText("action/saved"),buttons:Ext.Msg.OK,icon:Ext.Msg.INFO,fn:function() {
										Ext.getCmp("ModulePublicationArticleList").getStore().reload();
										Ext.getCmp("ModulePublicationArticleAddWindow").close();
									}});
								},
								failure:function(form,action) {
									if (action.result) {
										if (action.result.message) {
											Ext.Msg.show({title:Admin.getText("alert/error"),msg:action.result.message,buttons:Ext.Msg.OK,icon:Ext.Msg.ERROR});
										} else {
											Ext.Msg.show({title:Admin.getText("alert/error"),msg:Admin.getErrorText("DATA_SAVE_FAILED"),buttons:Ext.Msg.OK,icon:Ext.Msg.ERROR});
										}
									} else {
										Ext.Msg.show({title:Admin.getText("alert/error"),msg:Admin.getErrorText("INVALID_FORM_DATA"),buttons:Ext.Msg.OK,icon:Ext.Msg.ERROR});
									}
								}
							});
						}
					}),
					new Ext.Button({
						text:Admin.getText("button/cancel"),
						handler:function() {
							Ext.getCmp("ModulePublicationArticleAddWindow").close();
						}
					})
				],
				listeners:{
					show:function() {
						if (idx) {
							Ext.getCmp("ModulePublicationArticleAddForm").getForm().load({
								url:ENV.getProcessUrl("publication","@getArticle"),
								params:{idx:idx},
								waitTitle:Admin.getText("action/wait"),
								waitMsg:Admin.getText("action/loading"),
								success:function(form,action) {
									var author = JSON.parse(form.findField("author").getValue());
									Ext.getCmp("ModulePublicationArticleAddFormAuthorList").getStore().add(author);
									
									if (action.result.data.file != null) {
										Ext.getCmp("ModulePublicationArticleAddForm").getForm().findField("file_delete").setBoxLabel(action.result.data.file.name+ "("+iModule.getFileSize(action.result.data.file.size)+") 삭제");
										Ext.getCmp("ModulePublicationArticleAddForm").getForm().findField("file_delete").show();
									}
								},
								failure:function(form,action) {
									if (action.result && action.result.message) {
										Ext.Msg.show({title:Admin.getText("alert/error"),msg:action.result.message,buttons:Ext.Msg.OK,icon:Ext.Msg.ERROR});
									} else {
										Ext.Msg.show({title:Admin.getText("alert/error"),msg:Admin.getErrorText("DATA_LOAD_FAILED"),buttons:Ext.Msg.OK,icon:Ext.Msg.ERROR});
									}
									Ext.getCmp("ModulePublicationArticleAddWindow").close();
								}
							});
						}
					}
				}
			}).show();
		},
		delete:function() {
			var selected = Ext.getCmp("ModulePublicationArticleList").getSelectionModel().getSelection();
			if (selected.length == 0) {
				Ext.Msg.show({title:Admin.getText("alert/error"),msg:"삭제할 데이터를 선택하여 주십시오.",buttons:Ext.Msg.OK,icon:Ext.Msg.ERROR});
				return;
			}
			
			var idxes = [];
			for (var i=0, loop=selected.length;i<loop;i++) {
				idxes.push(selected[i].get("idx"));
			}
			
			Ext.Msg.show({title:Admin.getText("alert/info"),msg:"선택한 데이터를 정말 삭제하시겠습니까?",buttons:Ext.Msg.OKCANCEL,icon:Ext.Msg.QUESTION,fn:function(button) {
				if (button == "ok") {
					Ext.Msg.wait(Admin.getText("action/working"),Admin.getText("action/wait"));
					$.send(ENV.getProcessUrl("publication","@deleteArticle"),{idx:idxes.join(",")},function(result) {
						if (result.success == true) {
							Ext.Msg.show({title:Admin.getText("alert/info"),msg:Admin.getText("action/worked"),buttons:Ext.Msg.OK,icon:Ext.Msg.INFO,fn:function() {
								Ext.getCmp("ModulePublicationArticleList").getStore().reload();
							}});
						}
					});
				}
			}});
		}
	},
	/**
	 * 저자관리
	 */
	author:{
		insert:function(data,index) {
			var store = Ext.getCmp("ModulePublicationArticleAddFormAuthorList").getStore();
			
			if (data.midx != 0) {
				if (store.findExact("midx",data.midx) !== -1) {
					Ext.Msg.show({title:Admin.getText("alert/error"),msg:"이미 추가되어 있는 저자입니다.",buttons:Ext.Msg.OK,icon:Ext.Msg.ERROR});
					return;
				}
			} else {
				if (store.findExact("name",data.name) !== -1) {
					Ext.Msg.show({title:Admin.getText("alert/error"),msg:"이미 추가되어 있는 저자입니다.",buttons:Ext.Msg.OK,icon:Ext.Msg.ERROR});
					return;
				}
			}
			
			if (index === undefined) {
				data.sort = store.getCount();
				store.add(data);
			} else {
				store.getAt(index).set(data);
			}
			
			if (Ext.getCmp("ModulePublicationAuthorSearchWindow")) Ext.getCmp("ModulePublicationAuthorSearchWindow").close();
			if (Ext.getCmp("ModulePublicationAuthorAddWindow")) Ext.getCmp("ModulePublicationAuthorAddWindow").close();
		},
		add:function(index) {
			if (index !== undefined) {
				var value = Ext.getCmp("ModulePublicationArticleAddFormAuthorList").getStore().getAt(index).get("name");
			} else {
				value = null;
			}
			
			new Ext.Window({
				id:"ModulePublicationAuthorAddWindow",
				title:"저자추가",
				width:400,
				modal:true,
				autoScroll:true,
				border:false,
				items:[
					new Ext.form.Panel({
						id:"ModulePublicationAuthorAddForm",
						border:false,
						bodyPadding:"10 10 5 10",
						fieldDefaults:{labelAlign:"right",labelWidth:80,anchor:"100%",allowBlank:false},
						items:[
							new Ext.form.TextField({
								name:"name",
								value:value,
								emptyText:"저자"
							})
						]
					})
				],
				buttons:[
					new Ext.Button({
						text:Admin.getText("button/confirm"),
						handler:function() {
							if (Ext.getCmp("ModulePublicationAuthorAddForm").isValid() == true) {
								Publication.author.insert({midx:0,name:Ext.getCmp("ModulePublicationAuthorAddForm").getForm().findField("name").getValue()},index);
							}
						}
					}),
					new Ext.Button({
						text:Admin.getText("button/cancel"),
						handler:function() {
							Ext.getCmp("ModulePublicationAuthorAddWindow").close();
						}
					})
				]
			}).show();
		},
		search:function(index) {
			new Ext.Window({
				id:"ModulePublicationAuthorSearchWindow",
				title:"저자검색",
				width:600,
				height:400,
				modal:true,
				autoScroll:true,
				border:false,
				layout:"fit",
				items:[
					new Ext.grid.Panel({
						id:"ModulePublicationAuthorSearchList",
						border:false,
						tbar:[
							new Ext.form.TextField({
								id:"ModulePublicationAuthorSearchKeyword",
								emptyText:"이름/닉네임",
								enableKeyEvents:true,
								width:140,
								listeners:{
									keyup:function(form,e) {
										if (e.keyCode == 13) {
											Ext.getCmp("ModulePublicationAuthorSearchList").getStore().getProxy().setExtraParam("keyword",Ext.getCmp("ModulePublicationAuthorSearchKeyword").getValue());
											Ext.getCmp("ModulePublicationAuthorSearchList").getStore().loadPage(1);
										}
									}
								}
							}),
							new Ext.Button({
								iconCls:"mi mi-search",
								handler:function() {
									Ext.getCmp("ModulePublicationAuthorSearchList").getStore().getProxy().setExtraParam("keyword",Ext.getCmp("ModulePublicationAuthorSearchKeyword").getValue());
									Ext.getCmp("ModulePublicationAuthorSearchList").getStore().loadPage(1);
								}
							})
						],
						store:new Ext.data.JsonStore({
							proxy:{
								type:"ajax",
								simpleSortMode:true,
								url:ENV.getProcessUrl("publication","@getAuthors"),
								reader:{type:"json"}
							},
							remoteSort:true,
							sorters:[{property:"idx",direction:"ASC"}],
							autoLoad:true,
							pageSize:50,
							fields:["idx","name","email","count"],
							listeners:{
								load:function(store,records,success,e) {
									if (success == false) {
										if (e.getError()) {
											Ext.Msg.show({title:Admin.getText("alert/error"),msg:e.getError(),buttons:Ext.Msg.OK,icon:Ext.Msg.ERROR})
										} else {
											Ext.Msg.show({title:Admin.getText("alert/error"),msg:Admin.getErrorText("LOAD_DATA_FAILED"),buttons:Ext.Msg.OK,icon:Ext.Msg.ERROR})
										}
									}
								}
							}
						}),
						columns:[{
							text:"이름",
							minWidth:100,
							flex:1,
							dataIndex:"name"
						},{
							text:"이메일주소",
							width:200,
							dataIndex:"email"
						},{
							text:"등록수",
							width:80,
							dataIndex:"count",
							align:"right",
							renderer:function(value) {
								return Ext.util.Format.number(value,"0,000")+"건";
							}
						}],
						selModel:new Ext.selection.CheckboxModel({mode:"SINGLE"}),
						bbar:new Ext.PagingToolbar({
							store:null,
							displayInfo:false,
							listeners:{
								beforerender:function(tool) {
									tool.bindStore(Ext.getCmp("ModulePublicationAuthorSearchList").getStore());
								}
							}
						}),
						listeners:{
							itemdblclick:function(grid,record) {
								Publication.author.insert({midx:record.data.idx,name:record.data.name},index);
							}
						}
					})
				],
				buttons:[
					{xtype:"tbtext",text:"대상을 더블클릭하거나 선택 후 확인버튼을 클릭하여 주십시오."},
					"->",
					new Ext.Button({
						text:Admin.getText("button/confirm"),
						handler:function() {
							if (Ext.getCmp("ModulePublicationAuthorSearchList").getSelectionModel().getSelection().length == 0) {
								Ext.Msg.show({title:Admin.getText("alert/error"),msg:"선택된 회원이 없습니다.",buttons:Ext.Msg.OK,icon:Ext.Msg.ERROR})
							} else {
								var data = Ext.getCmp("ModulePublicationAuthorSearchList").getSelectionModel().getSelection().pop().data;
								Publication.author.insert({midx:data.idx,name:data.name},index);
							}
						}
					}),
					new Ext.Button({
						text:Admin.getText("button/cancel"),
						handler:function() {
							Ext.getCmp("ModulePublicationAuthorSearchWindow").close();
						}
					})
				]
			}).show();
		}
	},
	/**
	 * 카테고리 관리
	 */
	category:{
		add:function(idx) {
			new Ext.Window({
				id:"ModulePublicationCategoryAddWindow",
				title:(idx ? "카테고리 수정" : "카테고리 추가"),
				width:500,
				modal:true,
				autoScroll:true,
				border:false,
				items:[
					new Ext.form.Panel({
						id:"ModulePublicationCategoryAddForm",
						border:false,
						bodyPadding:"10 10 5 10",
						fieldDefaults:{labelAlign:"right",labelWidth:80,anchor:"100%",allowBlank:false},
						items:[
							new Ext.form.Hidden({
								name:"idx"
							}),
							new Ext.form.TextField({
								fieldLabel:"카테고리명",
								name:"title"
							}),
							new Ext.form.ComboBox({
								fieldLabel:"카테고리 유형",
								name:"type",
								store:new Ext.data.ArrayStore({
									fields:["display","value"],
									data:(function() {
										var datas = [];
										for (var value in Publication.getText("type")) {
											datas.push([Publication.getText("type/"+value),value]);
										}
										return datas;
									})()
								}),
								displayField:"display",
								valueField:"value",
								value:"PAPER"
							})
						]
					})
				],
				buttons:[
					new Ext.Button({
						text:Admin.getText("button/confirm"),
						handler:function() {
							Ext.getCmp("ModulePublicationCategoryAddForm").getForm().submit({
								url:ENV.getProcessUrl("publication","@saveCategory"),
								submitEmptyText:false,
								waitTitle:Admin.getText("action/wait"),
								waitMsg:Admin.getText("action/saving"),
								success:function(form,action) {
									Ext.Msg.show({title:Admin.getText("alert/info"),msg:Admin.getText("action/saved"),buttons:Ext.Msg.OK,icon:Ext.Msg.INFO,fn:function() {
										Ext.getCmp("ModulePublicationCategoryList").getStore().reload();
										Ext.getCmp("ModulePublicationArticleCategory").getStore().reload();
										Ext.getCmp("ModulePublicationCategoryAddWindow").close();
									}});
								},
								failure:function(form,action) {
									if (action.result) {
										if (action.result.message) {
											Ext.Msg.show({title:Admin.getText("alert/error"),msg:action.result.message,buttons:Ext.Msg.OK,icon:Ext.Msg.ERROR});
										} else {
											Ext.Msg.show({title:Admin.getText("alert/error"),msg:Admin.getErrorText("DATA_SAVE_FAILED"),buttons:Ext.Msg.OK,icon:Ext.Msg.ERROR});
										}
									} else {
										Ext.Msg.show({title:Admin.getText("alert/error"),msg:Admin.getErrorText("INVALID_FORM_DATA"),buttons:Ext.Msg.OK,icon:Ext.Msg.ERROR});
									}
								}
							});
						}
					}),
					new Ext.Button({
						text:Admin.getText("button/cancel"),
						handler:function() {
							Ext.getCmp("ModulePublicationCategoryAddWindow").close();
						}
					})
				],
				listeners:{
					show:function() {
						if (idx) {
							Ext.getCmp("ModulePublicationCategoryAddForm").getForm().load({
								url:ENV.getProcessUrl("publication","@getCategory"),
								params:{idx:idx},
								waitTitle:Admin.getText("action/wait"),
								waitMsg:Admin.getText("action/loading"),
								success:function(form,action) {
									
								},
								failure:function(form,action) {
									if (action.result && action.result.message) {
										Ext.Msg.show({title:Admin.getText("alert/error"),msg:action.result.message,buttons:Ext.Msg.OK,icon:Ext.Msg.ERROR});
									} else {
										Ext.Msg.show({title:Admin.getText("alert/error"),msg:Admin.getErrorText("DATA_LOAD_FAILED"),buttons:Ext.Msg.OK,icon:Ext.Msg.ERROR});
									}
									Ext.getCmp("ModulePublicationCategoryAddWindow").close();
								}
							});
						}
					}
				}
			}).show();
		},
		delete:function() {
			var selected = Ext.getCmp("ModulePublicationCategoryList").getSelectionModel().getSelection();
			if (selected.length == 0) {
				Ext.Msg.show({title:Admin.getText("alert/error"),msg:"삭제할 카테고리를 선택하여 주십시오.",buttons:Ext.Msg.OK,icon:Ext.Msg.ERROR});
				return;
			}
			
			var idxes = [];
			for (var i=0, loop=selected.length;i<loop;i++) {
				idxes.push(selected[i].get("idx"));
			}
			
			Ext.Msg.show({title:Admin.getText("alert/info"),msg:"선택하신 카테고리를 정말 삭제하시겠습니까?<br>해당 카테고리에 포함된 모든 데이터가 함께 삭제됩니다.",buttons:Ext.Msg.OKCANCEL,icon:Ext.Msg.QUESTION,fn:function(button) {
				if (button == "ok") {
					Ext.Msg.wait(Admin.getText("action/working"),Admin.getText("action/wait"));
					$.send(ENV.getProcessUrl("publication","@deleteCategory"),{idx:idxes.join(",")},function(result) {
						if (result.success == true) {
							Ext.Msg.show({title:Admin.getText("alert/info"),msg:Admin.getText("action/worked"),buttons:Ext.Msg.OK,icon:Ext.Msg.INFO,fn:function() {
								Ext.getCmp("ModulePublicationCategoryList").getStore().reload();
							}});
						}
					});
				}
			}});
		}
	},
	/**
	 * 매체 관리
	 */
	publisher:{
		add:function(idx) {
			var type;
			
			if (idx !== undefined) {
				if (typeof idx == "string") {
					type = idx;
					idx = undefined;
				} else {
					type = undefined;
				}
			} else {
				type = undefined;
			}
			
			new Ext.Window({
				id:"ModulePublicationPublisherAddWindow",
				title:(idx ? "매체 수정" : "매체 추가"),
				width:500,
				modal:true,
				autoScroll:true,
				border:false,
				items:[
					new Ext.form.Panel({
						id:"ModulePublicationPublisherAddForm",
						border:false,
						bodyPadding:"10 10 5 10",
						fieldDefaults:{labelAlign:"right",labelWidth:80,anchor:"100%",allowBlank:false},
						items:[
							new Ext.form.Hidden({
								name:"idx"
							}),
							new Ext.form.ComboBox({
								fieldLabel:"매체유형",
								name:"type",
								store:new Ext.data.ArrayStore({
									fields:["display","value"],
									data:(function() {
										var datas = [];
										for (var value in Publication.getText("type")) {
											if (value == "THESIS") continue;
											datas.push([Publication.getText("type/"+value),value]);
										}
										return datas;
									})()
								}),
								displayField:"display",
								valueField:"value",
								listeners:{
									change:function(form,value) {
										var title = {PAPER:"저널명",CONFERENCE:"컨퍼런스명",BOOK:"출판사명"};
										
										if (value == "PAPER") {
											form.getForm().findField("isbn").show();
										} else {
											form.getForm().findField("isbn").hide();
										}
										
										if (value == "CONFERENCE") {
											form.getForm().findField("supervision").setDisabled(false).show();
											form.getForm().findField("country").ownerCt.setDisabled(false).show();
											form.getForm().findField("start_date").ownerCt.setDisabled(false).show();
										} else {
											form.getForm().findField("supervision").setDisabled(true).hide();
											form.getForm().findField("country").ownerCt.setDisabled(true).hide();
											form.getForm().findField("start_date").ownerCt.setDisabled(true).hide();
										}
										
										form.getForm().findField("title").setFieldLabel(title[value] ? title[value] : "매체명");
									}
								}
							}),
							new Ext.form.TextField({
								fieldLabel:"매체명",
								name:"title"
							}),
							new Ext.form.TextField({
								fieldLabel:"주관",
								name:"supervision"
							}),
							new Ext.form.FieldContainer({
								layout:"hbox",
								fieldLabel:"개최국가/도시",
								items:[
									new Ext.form.ComboBox({
										name:"country",
										store:new Ext.data.ArrayStore({
											fields:["display","value"],
											data:(function() {
												var datas = [];
												for (var value in Publication.getText("country")) {
													datas.push([Publication.getText("country/"+value),value]);
												}
												return datas;
											})()
										}),
										displayField:"display",
										valueField:"value",
										flex:1
									}),
									new Ext.form.DisplayField({
										value:"/",
										width:40,
										style:{textAlign:"center"}
									}),
									new Ext.form.TextField({
										name:"city",
										flex:1,
										emptyText:"도시"
									})
								]
							}),
							new Ext.form.FieldContainer({
								fieldLabel:"개최기간",
								layout:"hbox",
								items:[
									new Ext.form.DateField({
										name:"start_date",
										format:"Y-m-d",
										flex:1
									}),
									new Ext.form.DisplayField({
										value:"~",
										width:40,
										style:{textAlign:"center"}
									}),
									new Ext.form.DateField({
										name:"end_date",
										format:"Y-m-d",
										flex:1
									})
								]
							}),
							new Ext.form.TextField({
								fieldLabel:"ISBN",
								name:"isbn",
								allowBlank:true
							}),
							new Ext.form.TextField({
								fieldLabel:"관련주소",
								name:"link",
								emptyText:"http://",
								allowBlank:true,
								afterBodyEl:'<div class="x-form-help">http:// 또는 https:// 를 포함한 전체 URL 을 입력하여 주십시오.</div>'
							})
						]
					})
				],
				buttons:[
					new Ext.Button({
						text:Admin.getText("button/confirm"),
						handler:function() {
							Ext.getCmp("ModulePublicationPublisherAddForm").getForm().submit({
								url:ENV.getProcessUrl("publication","@savePublisher"),
								submitEmptyText:false,
								waitTitle:Admin.getText("action/wait"),
								waitMsg:Admin.getText("action/saving"),
								success:function(form,action) {
									Ext.Msg.show({title:Admin.getText("alert/info"),msg:Admin.getText("action/saved"),buttons:Ext.Msg.OK,icon:Ext.Msg.INFO,fn:function() {
										Ext.getCmp("ModulePublicationPublisherList").getStore().reload();
										
										if (Ext.getCmp("ModulePublicationArticleAddForm")) {
											var addForm = Ext.getCmp("ModulePublicationArticleAddForm").getForm();
											addForm.findField("paper_publisher").getStore().reload();
											addForm.findField("conference_publisher").getStore().reload();
											addForm.findField("book_publisher").getStore().reload();
											addForm.findField("media_publisher").getStore().reload();
										}
										Ext.getCmp("ModulePublicationPublisherAddWindow").close();
									}});
								},
								failure:function(form,action) {
									if (action.result) {
										if (action.result.message) {
											Ext.Msg.show({title:Admin.getText("alert/error"),msg:action.result.message,buttons:Ext.Msg.OK,icon:Ext.Msg.ERROR});
										} else {
											Ext.Msg.show({title:Admin.getText("alert/error"),msg:Admin.getErrorText("DATA_SAVE_FAILED"),buttons:Ext.Msg.OK,icon:Ext.Msg.ERROR});
										}
									} else {
										Ext.Msg.show({title:Admin.getText("alert/error"),msg:Admin.getErrorText("INVALID_FORM_DATA"),buttons:Ext.Msg.OK,icon:Ext.Msg.ERROR});
									}
								}
							});
						}
					}),
					new Ext.Button({
						text:Admin.getText("button/cancel"),
						handler:function() {
							Ext.getCmp("ModulePublicationPublisherAddWindow").close();
						}
					})
				],
				listeners:{
					show:function() {
						if (idx) {
							Ext.getCmp("ModulePublicationPublisherAddForm").getForm().load({
								url:ENV.getProcessUrl("publication","@getPublisher"),
								params:{idx:idx},
								waitTitle:Admin.getText("action/wait"),
								waitMsg:Admin.getText("action/loading"),
								success:function(form,action) {
									
								},
								failure:function(form,action) {
									if (action.result && action.result.message) {
										Ext.Msg.show({title:Admin.getText("alert/error"),msg:action.result.message,buttons:Ext.Msg.OK,icon:Ext.Msg.ERROR});
									} else {
										Ext.Msg.show({title:Admin.getText("alert/error"),msg:Admin.getErrorText("DATA_LOAD_FAILED"),buttons:Ext.Msg.OK,icon:Ext.Msg.ERROR});
									}
									Ext.getCmp("ModulePublicationPublisherAddWindow").close();
								}
							});
						} else {
							Ext.getCmp("ModulePublicationPublisherAddForm").getForm().findField("type").setValue(type ? type : "PAPER");
						}
					}
				}
			}).show();
		}
	}
};