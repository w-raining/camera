<?php
//需要依赖arrayheper
require_once("ArrayHelper.php");
/**
* 可传参数:
*	$config=[
*		"file"=>"",
*		"size"=>"5",
*		"type"=>["jpg","gif","png"],
*	];
*
*/
class UploadIMG{
	//$_FILES 参数
	public $file;
	//int 	单位M
	public $size = 5;
	//array 允许的图片格式
	public $type = ["jpg","gif","png"];
	//string 保存路径
	public $savePath;
	//文件后缀-------------
	public $ext;
  /**
    * error 错误
	* name 原始名
	* size 字节
	* tmp_name 临时文件名
	* type 传输类型
	*/
	public function __construct($properties){
		static::configure($this,$properties);
		//运行
		$this->run();
	}
	//执行动作
	public function run(){
		//验证参数
		$this->verify();
		return $this->save();
	}
	
	//验证参数
	public function verify(){
		//验证file格式是否正确
		$this->varify_file();

		//验证mime类型是否匹配
		$this->verify_mime();
		
		//验证后缀
		$this->verify_ext();
		
		//确保路径正确
        $this->check_dir($this->savePath);
		
	}


	//上传的文件类型判断
	private function verify_mime(){
		$this->file["type"];
		$type = self::mimetype();
		if(isset($type[$this->file["type"]])){
			foreach($this->type as $v){
				if(in_array($v,$type[$this->file["type"]])){
					return true;
				}
			}
			throw new \Exception("文件类型非法！");
		}else{
			throw new \Exception("非法的mime类型！");
		}
	}

	//保存目录确保
	private function check_dir() {
		//如果路径为空
		if(empty($this->savePath)){
			$this->savePath=ArrayHelper::indexRootPath()."/uploads/temp/original/".date("Y/m/d/").md5_file($this->file['tmp_name']).".".$this->ext;
		}
		
        $dir = dirname($this->savePath);
        if (!is_dir($dir)) {
            if (!mkdir($dir, 0777, true)) {
				throw new \Exception("图片保存目录 $dir 无法创建！");
            }
        }
        return true;
    }

    //验证file
    private function varify_file(){
    	if(empty($this->file)){
    		throw new \Exception("file参数,为空!");
    	}
    	//是否上传错误
		if($this->file["error"]!=0){
			throw new \Exception(self::uploadError[$this->file["error"]]);
		}
		//上传大小是否超出
		if($this->size*1024*1024 < $this->file["size"]){
			throw new \Exception("文件超出了限定大小".$this->size."M");
		}

    }

	//验证后缀
	private function verify_ext() {
        $ext = explode('.', $this->file['name']);
        $ext = strtolower(end($ext));
		//判断是否符合后缀
		if(!in_array($ext,$this->type)){
			throw new \Exception("文件后缀非法！");
		}
		$this->ext=$ext;
    }

    //保存文件
	private function save(){
		//移动临时文件
		if(move_uploaded_file($this->file['tmp_name'],$this->savePath)){
			return $this->savePath;
		}else{
			throw new \Exception("文件移动错误！");
		}
	}



	public function getName(){
		//获取生成的文件名
		return substr($this->savePath,strripos($this->savePath,"/")+1);
	}


	public function getUploads(){
		//获取生成uploads所在的文件目录
		$path=preg_replace ("/\\\/" ,"/",$this->savePath);
		//return $path;
		return substr($path,strripos($path,"WWW/")+3);
	}




	//mime类型
	public static function mimetype(){
		return [
			"image/jpeg"=>["jpe","jpeg","jpg","jpz"],
			"image/png"=>["png","pnz"],
			"image/gif"=>["gif","ifm"],
		];
	}

	//错误信息
	public static function uploadError(){
        return 
        [
            '1' => '上传的文件超过了 php.ini 中 upload_max_filesize 选项限制的值。',
            '2' => '上传文件的大小超过了 HTML 表单中 MAX_FILEsize 选项指定的值。',
            '3' => '文件只有部分被上传',
            '4' => '没有文件被上传',
        ];
    }

    //自动加载
    public static function configure($object, $properties)
    {
        foreach ($properties as $name => $value) {
            $object->$name = $value;
        }
        return $object;
    }
	
}