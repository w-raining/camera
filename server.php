<?php
//echo json_encode(["server"=>$_SERVER,"file"=>$_FILES]);
//echo __DIR__; 
require("./lib/ImageCompression.php");
require("./lib/ArrayHelper.php");
require("./lib/UploadIMG.php");



if(!empty($_FILES["file"])){
	try{
		$res=new UploadIMG(["file"=>$_FILES["file"]]);
	}catch(\Exception $e){
		echo json_encode(["status"=>"1","error"=>$e->getMessage()]);
		die();
	}
	
	//$targetImg=__DIR__."/uploads/500_500/".date("Y/m/d/").$res->getName();
	//上传后自动生成500_500
	//目标图片目录
	$targetImg=str_replace("/original/","/autosize/",$res->savePath);
	$config=["baseImg"=>$res->savePath,"targetImg"=>$targetImg,"width"=>"500","height"=>"500"];
	//自动压缩
	$mod=ImageCompression::AutoReduce($config);

	echo json_encode(["path"=>ArrayHelper::getPath($targetImg),"name"=>$res->getName(),"status"=>"0"]);
}

