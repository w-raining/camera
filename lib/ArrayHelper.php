<?php


class ArrayHelper {
	//本地路径绝对路径 转 web路径绝对路径
	public static function getPath($local){
		$root_dir=self::webRootPath();
		$path=preg_replace ("/\\\/" ,"/",$local);
		$root_dir=preg_replace ("/\\\/" ,"/",$root_dir);
		//return substr($path,strripos($path,$root_dir)+2);
		return str_replace($root_dir,"",$path);
	}

	//web路径绝对路径 转 本地路径绝对路径 
	public static function getLocalPath($web){
		$root_dir=self::webRootPath();
		return $root_dir.$web;
	}
	/**
	* 定义入口文件路径 
	* 相对本地路径
	*/
	public static function indexRootPath(){
		//当前文件的目录的 目录
		return dirname(dirname(__FILE__));
	}

	//web目录所在本地的路径
	public static function webRootPath(){
		return $_SERVER["CONTEXT_DOCUMENT_ROOT"];
	}
}