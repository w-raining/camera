/**
 *  获取实例后调用
 *	obj.config({"main":"","thumb":""});
 */
+function($){
	'use strict';
	$.Thumb=new Thumb;
	function Thumb(){
		//外部全局的数据 代表上传图片后服务器返回的数据
		this.globalData=null;
		//声明存放切图坐标的容器
		this.cutpic=null;
		//上传文件的文件对象
		this.objFile=null;
		//是否已经上传过了
		this.already=null;

		//服务器上传图片地址
		this.uploadServer;
		//服务器处理图片地址
		this.processServer;

		//定义外部上传图片容器
		this.$main;
		//定义外部显示缩略图容器
		this.$thumb;
		//定义指定的input[type=file]对象
		this.$input;

		//定义进度条对象
		this.$progress;
		//上传图片后 剪裁图像后上传参数按钮
		this.$upDataButtom;
		//上传图片的按钮
		this.$upImageButtom;
		//声明大图对象
		this.$big_img;
		//声明小图的对象
		this.$min_img;

		this.display = function (config){

			this.$main=$(config.main);

			this.$thumb=$(config.thumb);

			this.$input=$(config.input);

			/*后台服务器地址 必填*/
			this.uploadServer=config.uploadServer;
			this.processServer=config.processServer;

			this.run();
		}

		this.run=function(){
			var outer=this;
			outer.$input.each(function(){
				$(this).css({"display":"none"});
				$(this).wrap("<div></div>");
				//定义容器
				var filebox=$(this).parent();
				//预定义上传按钮提示
				var html=$(this).attr("title")===undefined?"点击此处上传":$(this).attr("title");
				//内部自己创建的按钮
				var btn=$('<button class="btn btn-info mr10">'+html+'</button>');
				//赋值给提交头像按钮
				outer.$upDataButtom=$('<button class="btn btn-success mr10">确认提交头像</button>');
				//赋值进度条
				outer.$progress=$('<div><progress  value="0" min="0" max="100">0</progress></div>');

				//绑定上传数据
				outer.$upDataButtom.bind("click",function(){
					outer.uploadInfo();
				})

				//绑定文件选择的点击事件
				btn.bind("click",function(){
					$(this).prev()[0].click();
				})
				//自动上传
				$(this).bind("change",function(){
					// js 获取文件对象 并赋值到objFile
					outer.objFile = $(this)[0].files[0]; 
					//自动提交
					filebox.append(outer.$upDataButtom);

					outer.getAndSubmit();
				})
				//console.log(filebox);
				filebox.append(btn);
			
			})
		}
		


		/*
		$("#file").bind("click",function(){
			$(this).prev()[0].click();

		})*/

		//生成一个上传图片将原来的input[file]按钮隐藏
		// $upImageButtom.bind("click",function(){
		// 	 getAndSubmit();
		// })
		//获取文件对象并且上传
		this.getAndSubmit =function(){
			var outer=this;
			var formData = new FormData();
			//console.log(fileObj);
			formData.append("file", outer.objFile); // 文件对象

			$.ajax({  
	            url:outer.uploadServer,  
	            type: "POST",
	            dataType: "json",
	            data: formData,  
				processData: false,   // 如果data使用的是 FormData对象 则不需要转换data数据
	            contentType: false,   // 如果data使用的是 FormData对象 则不需要自动添加头信息
	            xhr: function(){        //这是关键  获取原生的xhr对象  做以前做的所有事情  
	                var xhr = jQuery.ajaxSettings.xhr();  
	                xhr.upload.onload = function (){  
	                    //alert('finish downloading')  
	                }  
	                xhr.upload.onprogress = function (e) {  
	                    if (e.lengthComputable) {
				　　　　　　var complete = (e.loaded / e.total * 100 | 0);
				　　　　　　outer.$progress.find("progress").val(complete);
							if(complete=100){
								outer.$progress.remove();
							}
							//console.log($progress.val());
			　　　　  	}
	                }  
	                return xhr;
	            },  
	            success:function(data){  
	            	outer.globalData =data;
	            	outer.init();
	            },
	            error:function(){  
	            	console.log("服务器错误");
	            	alert("请检查您上传的文件是否是jpg,png,gif格式");
	            }  
        	})  
		}

		//上传数据方法
		this.uploadInfo=function(){
			var outer=this;
			//var formData = new FormData();
			//formData.append("url",globalData.path);
			//formData.append("cut",{a:10,b:20} ); // 文件对象
			if(outer.globalData === null){
				alert("请先上传")
				return;
			}
			if(outer.cutpic === null){
				alert("请先截取头像")
				return;
			}
			if(outer.already === 1){
				alert("你已经截取过这个头像了,重新选取下吧~")
				return;
			}
			//向坐标中插入处理图片的地址
    		outer.cutpic.url=outer.globalData.path;
			$.ajax({
				url:outer.processServer,
				type:"POST",
				dataType:"json",
				data:outer.cutpic,
				//processData: false,   // 如果data使用的是 FormData对象 则不需要转换data数据
	            //contentType: false,   // 如果data使用的是 FormData对象 则不需要自动添加头信息
				success:function(data){  
					outer.already=1;
	            	alert("头像修改成功！重新登录后生效");
	            	//$upDataButtom.unbind();
	            },
	            error:function(){  
	            	//console.log("服务器错误");
	            	alert("服务器发生错误,请刷新后重试！");
	            }  
			})
		}
		//构造
		this.init = function(){
			var outer=this;
			//获取全局
			var data=outer.globalData;

        	outer.$big_img=$("<img src='"+data.path+"' />");
        	outer.$min_img=$("<img src='"+data.path+"' />");
        	outer.$big_img.load(function(){
        		var realWidth = this.width;
        		var realHeight = this.height;
        		//console.log("宽"+realWidth+"高"+realHeight);
    			outer.$main.css({"width":realWidth+"px"});
    			outer.$main.css({"height":realHeight+"px"});
        	})

        	//console.log($main);
        	//将图片装入容器
            outer.$main.html(outer.$big_img);
            outer.$thumb.html(outer.$min_img);

            outer.$big_img.Jcrop({
            	minSize:[50,50],//最小尺寸
            	aspectRatio:1,//长宽比
            	onChange:function(e){
            		//调用外部函数
            		outer.funChage(e);
            	},
            }); 
		}
		/**
		* 通过Jcrop的onChange方法
		* 修改内部数据
		*/
		this.funChage=function (e){
			var outer=this;
    		// console.log("相距源点x："+e.x);
    		// console.log("相距源点y："+e.y);
    		// console.log("宽度w："+e.w);
    		// console.log("高度y："+e.h);
    		// 重置already
    		outer.already=null;
    		var bigpic={
    			"height":outer.$big_img.height(),
    			"width":outer.$big_img.width()
    		};
    		var smallpic={
    			"height":outer.$thumb.height(),
    			"width":outer.$thumb.width()
    		};
    		outer.cutpic={
    			"height":e.h,
    			"width":e.w,
    			"left":e.x,
    			"top":e.y,
    		};
    		//动态的切图大小和原图的比例
    		var bprop_h = bigpic.height/outer.cutpic.height;
    		//动态的切图大小和原图的比例
    		var bprop_w = bigpic.width/outer.cutpic.width;
    		//原图和小图的宽比例
    		var wp=smallpic.width/bigpic.width;
    		//原图和小图的长比例
    		var hp=smallpic.height/bigpic.height;
    		//小图的变化相对于大图
    		outer.$min_img.css({
    			"position":"absolute",
    			"width":smallpic.width * bprop_w+'px',
    			"height":smallpic.height * bprop_h+'px',
    			"left":- outer.cutpic.left * bprop_w * wp+"px",
    			"top":- outer.cutpic.top * bprop_h * hp+"px",
    		});

    	}
	} 
}(jQuery)

    	
