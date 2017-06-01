# jQ.thumb
一个依赖jq&amp;jcrop,ajax上传剪裁头像的插件
# jQ.thumb使用方法
```
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>文件提交</title>
	<!--jcrop组件-->
	<link rel="stylesheet" href="plugins/Jcrop-0.9.12/css/jquery.Jcrop.css">
	<link rel="stylesheet" href="plugins/bootstrap-3.3.7/css/bootstrap.css">
	<!-- 可选的 Bootstrap 主题文件（一般不用引入） 
	<link rel="stylesheet" href="plugins/bootstrap-3.3.7/css/bootstrap-theme.min.css" >
	-->
	<link rel="stylesheet" href="css/style.css">
</head>
<body>
<div class="container-fluid">
	<div class="row">
		<div class="col-sm-12">
			<div class="ibox-content">
				<ul class="nav nav-tabs" >
					<li class="active" ><a href="javascript:;">本地上传</a></li>
				</ul>
				<div class="m-t m-b">
					<input type="file" name="file" id="upinput" />
					<div class="image_dispaly">
						<div id="thumb_main" ><p>上传图片预览</p></div>
						<div class="smpic" style="width: 180px;height: 180px"><p>头像预览</p></div>
						<div class="smpic" style="width: 90px;height: 90px"><p>头像预览</p></div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
</body>
<script src="../jquery/jquery-2.2.4.js"></script>
<!--用于截图的Jcrop组件-->
<script src="plugins/Jcrop-0.9.12/js/jquery.Jcrop.js"></script>
<!--bootstrap 相关效果js可不用
<script src="plugins/bootstrap-3.3.7/js/bootstrap.js"></script>
-->
<script src="js/jquery.thumb.js"></script>
<script type="text/javascript">
$.Thumb.display({
	main:"#thumb_main",//上传图预览
	thumb:".smpic",//缩略图
	input:"#upinput",//input type="file" 的对象
	upImageName:"file",//上传表单名
	uploadServer:"./server.php",//处理上传的服务器
	processServer:"./server_thumb.php",//处理剪裁服务器
	upload_success:function(outer,data){
		outer.globalData = data;//将接受到的参数赋值给globalData
		return true;//返回真
	},
	process_success:function(outer,data){
		if(data.status==="0"){//处理成功后的回调信息处理
			alert("头像修改成功！");
		}else{
			alert("出错了！"+data.error);
		}
	}
});

</script>
</html>
```
