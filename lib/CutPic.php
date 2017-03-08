<?php
//需要依赖arrayheper
//require_once("ArrayHelper.php");

//需要依赖 ImageCompression
class CutPic{
	//第一次手动剪裁配置
	public $FConfig;
	//之后的2次缩放配置
	public $CConfig=[
		"width"=>100,
		"height"=>100
	];

	public $ImageCompression;

	public static function Cut($configure){
		return new self($configure);
	}

	public function __construct($properties){
		static::configure($this,$properties);
		$this->init();
	}
	//构造运行
	public function init(){
		$this->verify();
		//初始的素材所在
		$this->FConfig["baseImg"]=self::get_document_root().$this->FConfig["baseImg"];
		//初剪的头像 存放地址
		$this->FConfig["targetImg"]=str_replace("/autosize/","/usercut/",$this->FConfig["baseImg"]);
		//初剪地址
		$this->CConfig["baseImg"]= $this->FConfig["targetImg"];
		//最后生成的头像地址
		$this->CConfig["targetImg"]= ArrayHelper::indexRootPath()."/uploads/thumb/".date("Y/m/d/His").rand(100000,999999).".".self::getExt($this->CConfig["baseImg"]);
		//运行
		$this->run();
	}

	public function run(){
		//设置默认初剪的属性
		\ImageCompression::Manual($this->FConfig);

		//设置缩放或者放大~
		$this->ImageCompression=\ImageCompression::SpAutoReduce($this->CConfig);
	}


	//验证模块
	public function verify(){
		//验证IC参数
		// if(!$this->IC instanceof ImageCompression){
		// 	throw new \Exception(get_class($this->IC)."不是ImageCompression 的实例");
		// }
		//验证FConfig参数是否存在
		static::is_set(static::F_Attribute() ,$this->FConfig);
		//验证CConfig参数是否存在
		static::is_set(static::C_Attribute() ,$this->CConfig);

	}

	//验证参数是否存在
	public static function is_set($varr,$conf){
		foreach ($varr as $v) {
			if(!array_key_exists($v, $conf)){
				throw new \Exception("缺少参数:".$v);
			}
		}
	}
	//必填参数
	public static function F_Attribute(){
		return [
			"baseImg",
			"width",
			"height",
		//	"type",
			"offset_x",
			"offset_y"
		];
	}

	public static function C_Attribute(){
		return [
		//	"baseImg",
			"width",
			"height",
		//	"type",
		];
	}

	//获取后缀
	public static function getExt($path) {
		$ext = explode('.', $path);
		return strtolower(end($ext));
    }

	public static function get_document_root(){
		return $_SERVER["CONTEXT_DOCUMENT_ROOT"];
	}
	
	//自动加载
    public static function configure($object, $properties){
        foreach ($properties as $name => $value) {
            $object->$name = $value;
        }
        return $object;
    }
	
}