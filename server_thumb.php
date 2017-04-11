<?php

//echo json_encode(["server"=>$_SERVER,"file"=>$_FILES]);

//echo __DIR__; 
require("./lib/ArrayHelper.php");
require("./lib/ImageCompression.php");
require("./lib/CutPic.php");

if(!empty($_POST)){
	//再生成对应头像
	$config=[
		"FConfig"=>[
			"baseImg"=>$_POST["url"],
			//"targetImg"=>"./u/2.jpg",
			"width"=>$_POST["width"],
			"height"=>$_POST["height"],
			"offset_x"=>$_POST["left"],
			"offset_y"=>$_POST["top"],
		],
		"CConfig"=>[
			"width"=>"100",
			"height"=>"100",
		]
	];

	$result=CutPic::Cut($config);
	$LocalPath=$result->ImageCompression->targetImg;
	$path=ArrayHelper::getPath($LocalPath);
	echo json_encode(["status"=>"0","path"=>$path]);
}
