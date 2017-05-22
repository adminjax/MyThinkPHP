/**
 * 
 */
;
var Nav = {};
Nav = {
	init : function(){
		this.onClick();
		this.spread();
	},

	onClick : function(){
		jQuery(".onclick").click(function(){
			var flag = jQuery(this).next('ul').attr("class");
			if(flag.indexOf('active') > -1){
				jQuery(this).next('ul').removeClass('active');
			}else{
				jQuery(this).next('ul').addClass('active');
			}
		});
	},

	spread : function(){
		var page = jQuery('body>div').attr('id');
		jQuery('.nav').find('[href]').each(function(){
			var data = jQuery(this).attr('href');
			var url = window.location.href;
			
			if(url.indexOf(data) > -1){
				jQuery(this).parent('li').addClass('active');
				jQuery(this).parent().parent('ul').addClass('active');
			}

		});
	}


}

;
var Selection = {};
Selection = {
	switch : function(){
		jQuery(".switch>div").click(function(){
			var flag = jQuery(this).attr('class');
			if(flag.indexOf('active') > -1){
				return;
			}
			jQuery(".switch>div").removeClass('active');
			jQuery(".switch").next(".contents").find('div').removeClass('active');
			jQuery("."+flag).addClass('active');
		})
	},
	open : function(){
		jQuery('.open').click(function(){
			jQuery(this).toggleClass('close');
			jQuery(this).parent().children('ul').toggleClass('active');
		});
	},
	select : function(){
		jQuery('input').click(function(){
			
		});
	}
}

;
var Cart = {};
Cart = {
	init : function(){
		this.plus();
		this.reduce();
	},

	plus : function(){
		jQuery('.plus').clicl(function(){
			var num = jQuery('.goods-num').value();
		});
	},
	reduce : function(){

	}
}

;
var Action = {};
Action = {
	'id' : '',
	'url' : '',

	aclUser : function(id, url){
		this.id = id;
		this.url = url;
		this.userEdit();
		this.userDelete();
		this.setAcl();
	},	

	userEdit : function(){
		jQuery(this.id).find('.edit').click(function(){
			if(jQuery(this).attr('class').indexOf('save') < 0){
				jQuery(this).parent().parent().find('input').each(function(){
					jQuery(this).removeAttr('readonly');
					jQuery(this).addClass('edit');
				});

				jQuery(this).addClass('save');
				jQuery(this).empty();
				jQuery(this).html('<span>保存</span>');
			}else{
				var flag = confirm("是否保存！");
				if(flag){
					var uid = jQuery(this).parent().parent().find("[name='uid']").val();
					var realname = jQuery(this).parent().parent().find("[name='name']").val();
					var gender = jQuery(this).parent().parent().find("[name='gander']").val();
					var position = jQuery(this).parent().parent().find("[name='job']").val();
					var phone = jQuery(this).parent().parent().find("[name='phone']").val();
					var username = jQuery(this).parent().parent().find("[name='username']").val();
					var password = jQuery(this).parent().parent().find("[name='password']").val();

					jQuery.ajax({
						url: Action.url+'editUser',
						type:'POST',
						data:'uid='+uid+'&realname='+realname+'&gender='+gender+'&position='+position+'&phone='+phone+'&username='+username+'&password='+password,
						success:function(data){
							if(data < 0){
								window.location.reload();
								return;
							}
						}
					});
					jQuery(this).parent().parent().find('input').each(function(){
						jQuery(this).attr('readonly','true');
						jQuery(this).removeClass('edit');
					});
					jQuery(this).removeClass('save');
					jQuery(this).empty();
					jQuery(this).html('<span>编辑</span>');
				}else{
					jQuery(this).parent().parent().find('input').each(function(){
						jQuery(this).attr('readonly','true');
						jQuery(this).removeClass('edit');
					});
					jQuery(this).removeClass('save');
					jQuery(this).empty();
					jQuery(this).html('<span>编辑</span>');
				}
			}
		});
	},

	userDelete : function(){
		jQuery(this.id).find('.delete').click(function(){
			var uid = jQuery(this).parent().parent().find("[name='uid']").val();

			var flag = confirm("是否删除用户！");
			if(flag){
				jQuery.ajax({
					url: Action.url+'deleteUser',
					type:'POST',
					data:'uid='+uid,
					success:function(data){
						//console.log(data);
						if(data > 0){
							window.location.reload();
						}else{
							return;
						}
					}
				});
			}
			
		});
	},

	setAcl : function(){
		jQuery(this.id).find('.setAcl').click(function(){
			var uid = jQuery(this).parent().parent().find("[name='uid']").val();
			window.location.href = Action.url+'setAcl/uid/'+uid;
		});
	},

	userAdd : function(id){
		jQuery(id).click(function(){
			var template = jQuery('.template').html();
			template = '<tr class="add-user">'+template+'</tr>';
			jQuery('.data-table tbody').append(template);
		});
	},

	acl : function(id){
		jQuery(id).find('.edit').click(function(){
			jQuery(this).parent().parent().find('input').each(function(){
				jQuery(this).removeAttr('readonly');
				jQuery(this).addClass('edit');
			});

			return false;
		});

		
	},

	preview : function(id, content){
		jQuery(id).click(function(){
			var ue = UE.getEditor('container');
			var titleimg = jQuery('#imgl').val();
			var integ = jQuery('.inte').val();
			var headimg = jQuery('#imgls').val();
			var content =  ue.getContent();
			var host = window.location.host;

			jQuery('.pre-title-img').attr('src', 'http://'+host+'/'+headimg);
			jQuery('.desc').html(content);
		});
	}
};

var Selected = {};
Selected = {
	/*clicks : function(id){
		jQuery(id).find('div').click(function(){
			jQuery(this).toggleClass("active");
			var id = jQuery(this).find('img').attr('id');
			var ids = jQuery('.del-sub').val();
			var cla = jQuery(this).attr('class');
			if(cla.indexOf('active') > -1){
				ids = ids+','+id;
			}else{
				ids = ids.replace(','+id, '');
			}
			jQuery('.del-sub').attr('value', ids);

		});
	},*/
	click : function(){
		jQuery('.slider').find('div').click(function(){
			$('.slider').children('.active').removeClass("active");
			jQuery(this).addClass("active");
			var id = jQuery(this).find('img').attr('id');
			jQuery('.del-sub').attr('value', id);
		});
	},
}

;
var Preview = {};
Preview = {
	viewSub : function(id){
		jQuery('.view').click(function(){
			var datatime = $(this).attr('datatime');
			var headerImg = jQuery('#imgl').val();
			var hasChk = jQuery('.is-subject').is(':checked');
			var headerTitle = jQuery('.subtitle').val();
			var input_text = jQuery('.input-text').val();
			var anthor = jQuery('.anthor').val();
			var desc = jQuery('.desc').val();
			$(".content-preview .from").show();
			var currClass = jQuery('.switch').find('.active').attr('class');

			var time = parseFloat(datatime)*1000 ? parseFloat(datatime)*1000 : new Date().getTime();
			var new_time = DateTime.datatime(time);
			$(".box-3 .content .content-preview .time").text(new_time);
			$(".head-title").text(input_text);
			if(currClass.indexOf('image') > -1){
				var html = ue.getContent();
				jQuery('.sub-content').html(html);
				str = Preview.removeHTMLTag(html);
				jQuery('.briefing').text(str.substring(0, 12)+'...');
			}else if(currClass.indexOf('video') > -1){

				var video = jQuery('#ueditor_1').contents().find('body').html();
				var ms = jQuery('#imgword').val();				
				jQuery('.sub-content').html(video+ms);	
				str = Preview.removeHTMLTag(ms);
				jQuery('.briefing').text(str.substring(0, 12)+'...');
				//jQuery('.middle').remove();
			}

			if(hasChk){
				jQuery('.sub').css('display', 'block');
			}
			
			if (Preview.getLength(headerTitle) > 10) { 
		       var title = Preview.mbstr(headerTitle, 20); 
		    } 

		    jQuery('.middle .name').text(anthor);
			jQuery('.header-img').attr('src', headerImg);
			jQuery('.head-title').text(title);
			jQuery('.content-preview .top').text(headerTitle);
		});
	},

	removeHTMLTag : function(str){
        str = str.replace(/<\/?[^>]*>/g,''); //去除HTML tag
        str = str.replace(/[ | ]*\n/g,'\n'); //去除行尾空白
        str=str.replace(/ /ig,'');//去掉 
        return $.trim(str);
	},

	getLength : function(str){
		var realLength = 0;
		var len = str.length;

		for(var i = 0; i < len; i++){
			charCode = str.charCodeAt(i);
			if(charCode >= 0 && charCode <= 128){
				realLength += 1;
			}else{
				realLength += 2;
			}
		}

		return realLength;
	},

	mbstr : function(str, len){
		var str_length = 0;
		var str_len = 0;
		str_cut = new String();
		str_len = str.length;
		for(var i = 0; i < str_len; i++){
			a = str.charAt(i);
            str_length++;
            if (escape(a).length > 4) {
                //中文字符的长度经编码之后大于4  
                str_length++;
            }
            str_cut = str_cut.concat(a);
            if (str_length >= len) {
                str_cut = str_cut.concat("...");
                return str_cut;
            }
		}

		if (str_length < len) {
            return str;
        }
	},

	edit : function(id, url){
		jQuery(id).click(function(){
			var id = jQuery(this).find('img').attr('id');
			jQuery('.sub-id').attr('value', id);
			jQuery.ajax({
				type : 'GET',
				url : url,
				data : 'id='+id,
				success : function(data){
					if(data.created > data.modified){
						$(".view").attr('datatime',data.created);
					}else{
						$(".view").attr('datatime',data.modified);
					}
					jQuery('.formControls #img').attr('src', data.icon);
					jQuery('.formControls .select').attr('value', data.icon);
					if(data.is_sub == '1'){
						jQuery('.is-subject').attr("checked",true);
					}else{
						jQuery('.is-subject').attr("checked",false);
					}

					if(data.type == 3){
						var video = UE.getEditor('video');
						video.setContent('',false);
						$('input[name="video-url"]').val('');
						$('input[name="embed-url"]').val('');
						$('input[name="time"]').val('');
						$('input[name="size"]').val('');
						$('#imgword').val('');
						jQuery('.image').addClass('active');
						jQuery('.video').removeClass('active');
						jQuery("#ueditor_1").contents().find("body").empty();
						jQuery("#ueditor_2").contents().find("body").empty();

					}else if(data.type == 2){
						jQuery('.video').addClass('active');
						jQuery('.image').removeClass('active');
						jQuery("#ueditor_0").contents().find("body").empty();
					}

					var t_id = data.t_id.split(',');
					jQuery(".select-tag input").each(function(){
						var val = jQuery(this).val();
						if($.inArray(val,t_id) > -1){
							jQuery(this).attr("checked",true);
						}else{
							jQuery(this).attr("checked",false);
						}
					});  

					var ue = UE.getEditor('container');
					ue.setContent('',false);
					jQuery('.subtitle').attr('value', data.title);
					jQuery('.anthor').text(data.u_info);
					jQuery('.desc').text(data.description);
					html = Preview.html_decode(data.content);
					if(data.type == 3){
						var editor = UE.getEditor('container');
						editor.setContent(html);
					}else if(data.type == 2){
						var ext = Helper.getExt(data.video);
						if(ext == '.swf'){
							var str = '<embed type="application/x-shockwave-flash" class="edui-faked-video" pluginspage="http://www.macromedia.com/go/getflashplayer"' +
                    				  ' src="' +  data.video + '" width="100%" height="300" wmode="transparent" play="true" loop="false" menu="false" allowscriptaccess="never" allowfullscreen="true" >';
						}else if(ext == '.mp4'){
							var url = data.video.replace('http://'+location.host+'/' , '');
							url = url.replace('http://'+location.host+'/' , '');	
							var str = '<video class="edui-upload-video video-js vjs-default-skin video-js" controls preload width="300" height="200" src="' + url + '" data-setup="{}">' +
                     				  '<source src="' + url + '" type="video/mp4" /></video>';
						}

						var editor = UE.getEditor('video');
						editor.setContent(str);
						jQuery('#imgword').text(data.content);
						$('input[name="time"]').val(data.time);
						$('input[name="size"]').val(data.size);

					}
				}
			});
		});
	},

	html_decode : function(str){         
      	str = str.replace(/&amp;/g, '&'); 
      	str = str.replace(/&lt;/g, '<');
      	str = str.replace(/&gt;/g, '>');
      	str = str.replace(/&quot;/g, '"');  
      	str = str.replace(/&#039;/g, "'");  
      	str = str.replace(/&nbsp;/g, " ");  
      	return str;  
	}
}

;
var Helper = {};
Helper = {
	getExt : function(str){
		var point = str.lastIndexOf("."); 
     	var type = str.substr(point);

     	return type;
	}
}

;
var Show = {};
Show = {
	preview : function(cls, url){
		jQuery(cls).click(function(){
			var id = jQuery(this).find('.id').val();
			var active = jQuery(this).attr('active');
			jQuery.ajax({
				type : 'GET',
				url : url,
				data : {id:id,active:active},
				success : function(data){
					//console.log(data);
					$(".content-preview .from").show();
					for(var i in data){
						if(data[i].s_id > 0){
							jQuery('.title-preview img').attr('src', data[i].icon);
							jQuery('.head-title').text(data[i].title);
							var briefing = Preview.html_decode(data[i].content);
							briefing = Preview.mbstr(briefing, 20);
							jQuery('.content-preview .name').text(data[i].u_info);
							jQuery('.content-preview .time').text(DateTime.datatime(parseInt(data[i].created) * 1000));
							var content = Preview.html_decode(data[i].content);
							jQuery('.top').text(data[i].title);
							if(data[i].type == 3){
								jQuery('.briefing').text('');
								jQuery('.content-preview .bottom').html(content);
							}else if(data[i].type == 2){
								jQuery('.briefing').text(briefing.replace('<p>', ''));
								var ext = Helper.getExt(data[i].video);
								jQuery('.content-preview .name').text(data[i].u_info);
								if(ext == '.swf' || data[i].video.indexOf('.swf') > -1){
									var str = '<embed type="application/x-shockwave-flash" class="edui-faked-video" pluginspage="http://www.macromedia.com/go/getflashplayer"' +
                    				  ' src="' +  data[i].video + '" width="100%" height="200" wmode="transparent" play="true" loop="false" menu="false" allowscriptaccess="never" allowfullscreen="true" >';
								}else{
									var str = '<video class="edui-upload-video video-js vjs-default-skin video-js" controls preload width="100%" height="200" src="' + data[i].video + '" data-setup="{}">' +
                     				  '<source src="' + data[i].video + '" type="video/mp4" /></video>';
								}
								jQuery('.content-preview .bottom').html(str+content);
							}
						}
					}
				}
			});		
		});
	}
}


/**
 * [DateTime 时间转换]
 * @type {Object}
 */
var DateTime = {};
DateTime = {
	/**              
     * 时间戳转换日期              
     * @param <int> unixTime    待时间戳(秒)              
     * @param <bool> isFull    返回完整时间(Y-m-d 或者 Y-m-d H:i:s)              
     * @param <int>  timeZone   时区              
     */
    UnixToDate: function(unixTime, isFull, timeZone) {
		/*var isFull = true;
        if (typeof (timeZone) == 'number')
        {
            unixTime = parseInt(unixTime) + parseInt(timeZone) * 60 * 60;
        }
        var time = new Date(unixTime * 1000);
        var ymdhis = "";
        ymdhis += time.getUTCFullYear() + "-";
        ymdhis += (time.getUTCMonth()+1) + "-";
        ymdhis += time.getUTCDate();
        if (isFull === true)
        {
            ymdhis += " " + time.getUTCHours() + ":";
            ymdhis += time.getUTCMinutes() + ":";
            ymdhis += time.getUTCSeconds();
        }
        return ymdhis;*/
        var now = new Date(unixTime*1000);   
        var year = now.getFullYear();    
        var month = now.getMonth()+1;     
        var date = now.getDate();     
        var hour = now.getHours();     
        var minute = now.getMinutes();     
        var second = now.getSeconds();     
        return year+"-"+month+"-"+date+"   "+hour+":"+minute+":"+second; 
    },

    currTime : function(fmt){
    	var date = new Date();
    	var o = {   
		    "M+" : date.getMonth()+1,                 //月份   
		    "d+" : date.getDate(),                    //日   
		    "h+" : date.getHours(),                   //小时   
		    "m+" : date.getMinutes(),                 //分   
		    "s+" : date.getSeconds(),                 //秒   
		    "q+" : Math.floor((date.getMonth()+3)/3), //季度   
		    "S"  : date.getMilliseconds()             //毫秒   
		 };   
		 if(/(y+)/.test(fmt))   
		    fmt=fmt.replace(RegExp.$1, (date.getFullYear()+"").substr(4 - RegExp.$1.length));   
		 for(var k in o)   
		    if(new RegExp("("+ k +")").test(fmt))   
		  		fmt = fmt.replace(RegExp.$1, (RegExp.$1.length==1) ? (o[k]) : (("00"+ o[k]).substr((""+ o[k]).length)));   
		 return fmt;
    },

	datatime : function(datatime){
		return moment(datatime).format('YYYY-MM-DD HH:mm:ss');
	}

}

/**
 * [messageShow 返回信息显示]
 * @type {Object}
 */
var messageShow = {};
messageShow = {
	start : function(){
		setTimeout(this.action(), 3000);
	},

	action : function(){
		var flag = jQuery('#message').html();
		//if(flag.length > 0){
			//jQuery('#message').remove();
		//}
	}
}

;
var formCheck = {};
formCheck = {
	subject : function(id){
		var empty = '';
		var flag = true;
		var video = jQuery('.video-url').val();
		var word = UE.getEditor('container');
		word = word.getContent();

		jQuery(id).find('input').each(function(){
			//console.log(jQuery(this).attr('name'));
			if(jQuery(this).attr('name') == 'imgl'){
				if(jQuery(this).val() == ''){
					alert('请上传标题图片!');
					flag = false;
					return flag;
				}
			}
			if(jQuery(this).attr('name') == 'title'){
				if(jQuery(this).val() == ''){
					alert('请填写标题！');
					flag = false;
					return flag;
				}
			}

			if(video != ''){
				if(jQuery(this).attr('name') == 'time'){
					if(jQuery(this).val() == ''){
						alert('请填写视频时长！');
						flag = false;
						return flag;
					}else if(parseInt(jQuery(this).val()) <= 0){
						alert('请填写正确视频时长！');
						flag = false;
						return flag;
					}
				}
			}
		});

		if(flag !== false){
			if(word.length <= 0 && video.length <= 0){
				alert('请填写专题内容!');
				flag = false;
				return flag;
			}
		}

		if(flag !== false){
			jQuery(id).find('textarea').each(function(){
				//console.log(jQuery(this).attr('name'));
				if(video != ''){
					if(jQuery(this).attr('name') == 'imgword'){
						if(jQuery(this).val() == ''){
							alert('请填写视频简介!');
							flag = false;
							return flag;
						}
					}
				}
				if(jQuery(this).attr('name') == 'anthor'){
					if(jQuery(this).val() == ''){
						alert('请填写编辑者信息！');
						flag = false;
						return flag;
					}
				}
				if(jQuery(this).attr('name') == 'desc'){
					if(jQuery(this).val() == ''){
						alert('请填写提交说明！');
						flag = false;
						return flag;
					}
				}
			});
		}

		return flag;
	},

	team : function(id){
		jQuery(id).live('click', function(){
			jQuery('.teaminfo').each(function(){
				var val = jQuery(this).find('#imgl').val();
				if(!val){
					alert("请上传战队LOGO!");
					return false;
				}

				val = jQuery(this).find(".tname").val();
				if(!val){
					alert("请输入战队名称!");
					return false;
				}

				var val = jQuery(this).find(".tdecl").val();
				if(!val){
					alert("请输入战队宣言!");
					return false;
				}

				var val = jQuery(this).find(".tbrief").val();
				if(!val){
					alert("请输入战队简介!");
					return false;
				}

				var val = jQuery(this).find(".members").find(".clearP").html();
				if(!val){
					alert("请添加队员！");
					return false;
				}
			});
			jquery("#edit-form").submit();
			return false;
		});
	}

};
var check_all = {};
check_all = {
	check_all : function(id){
		$(id).unbind('change').bind('change',function(){
			if($(this).attr('checked') == 'checked')
				$(this).parent().parent().parent().find('tr.click input[type="checkbox"]').attr('checked',true);
			else
				$(this).parent().parent().parent().find('tr.click input[type="checkbox"]').attr('checked',false);
		});
	}
};

var search = {};
search = {
	searchF : function(url){
		jQuery('.friend').live('click',function(){
			var id = jQuery(this).find('.community_id').val();
			var type = jQuery(this).find('.community_type').val();

			jQuery.ajax({
				type:'GET',
				url:url,
				data:'id='+id+'&type='+type,
				success:function(data){
					//console.log(data);
					if(!data.urls){
						jQuery('.content-preview').html('<div class="friend">no data</div>');
						return false;
					}

					if(data.isVideo == false){
						var str = '<div class="friend">';
						for (var i = 0; i < data.urls.length; i++) {
							str = str + '<img	src="'+data.urls[i]+'"/>';
						}
						str = str + '</div>';
						jQuery('.content-preview').html(str);
					} else if(data.isVideo == true){
						var str = '<div class="friend">';

						var point = data.urls[0].lastIndexOf(".");
						var type = data.urls[0].substr(point);

						if(type == '.mp4'){
							str = str + '<video class="f-video" src="'+data.urls[0]+'" poster="'+data.urls[1]+'" controls></video>';
						}else{
							str = str + '<video class="f-video" src="'+data.urls[1]+'" poster="'+data.urls[0]+'" controls></video>';
						}

						str = str + '</div>';
						jQuery('.content-preview').html(str);
					}
				}
			})
		} );
	},
	searchN : function(url){
		jQuery('.nears').live('click',function(){
			var id = jQuery(this).find('.community_id').val();
			var type = jQuery(this).find('.community_type').val();

			jQuery.ajax({
				type:'GET',
				url:url,
				data:'id='+id+'&type='+type,
				success:function(data){
					//console.log(data);
					if(!data.urls){
						jQuery('.content-preview').html('<div class="friend">no data</div>');
						return false;
					}

					if(data.isVideo == false){
						var str = '<div class="friend">'
						for (var i = 0; i < data.urls.length; i++) {
							str = str + '<img	src="'+data.urls[i]+'"/>';
						}
						str = str + '</div>';
						jQuery('.content-preview').html(str);
					} else if(data.isVideo == true){
						var str = '<div class="friend">'

						var point = data.urls[0].lastIndexOf(".");
						var type = data.urls[0].substr(point);

						if(type == '.mp4'){
							str = str + '<video class="f-video" src="'+data.urls[0]+'" poster="'+data.urls[1]+'" controls></video>';
						}else{
							str = str + '<video class="f-video" src="'+data.urls[1]+'" poster="'+data.urls[0]+'" controls></video>';
						}

						str = str + '</div>';
						jQuery('.content-preview').html(str);
					}
				}
			})
		} );
	}
};