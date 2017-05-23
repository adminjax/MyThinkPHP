/**
 * 数据验证
 */

//表单数据验证
;
var ValidForm = {};
ValidForm = {
	id : '', //选择器
	flag : false,
	//提示信息
	msg : {
		notNull : '请输入对应信息！',
		prompt2 : '提示信息2',
	},

	//正则
	dataType : {
		string : /^\w\d+_/,
		int : /^\d+$/,
	},

	//初始化
	init : function(selecor){
		this.id = jQuery(selecor);
		
		this.id.find('button').click(function(){
			ValidForm.valid(this.id);
			
			if(ValidForm.flag == false){
				return false;
			}else{
				jQuery(selecor).submit();
			}
		});
		
	},

	valid : function(){
		this.id.find('input').each(function(){
			var cla = jQuery(this).attr('class');

			if(cla.indexOf('need-valid') >= 0){
				var value = jQuery(this).attr('value');
				ValidForm.flag = ValidForm.isEmpty(value);
				
				if(ValidForm.flag == false){
					console.log(value);
					var msg = jQuery(this).attr('dataMsg');
					if(!msg){
						msg = ValidForm.msg.notNull;
					}
					Log.prompt(msg, jQuery(this));
				}
			}
		});
	},

	//是否为空
	isEmpty : function(value){
		if(value){
			return true
		}else{
			return false;
		}
	},
}



//日志
;
var Log = {};
Log = {
	//信息提示
	prompt : function(msg, selecor){
		if(!selecor.parent().find('.msg').text()){
			var html = '<span class="msg">'+msg+'</span>';
			selecor.parent().append(html);
		}
	}
}