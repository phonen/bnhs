<?php
namespace Common\Lib;
/**
 * 音频转码类
 * author universe.h 2017.10.28
 */
class Ffmpeg {
    protected $ffConfig;
    protected $ffmpeg;

    /**
     * 初始化配置
     * Ffmpeg constructor.
     * @param $ffmpeg
     */
    public function __construct($ffmpeg)
    {
        $this->ffmpeg = $ffmpeg;
        $fileNameExt = basename($this->ffmpeg['file']);
        $fileInfo = explode('.',$fileNameExt);
        $path = dirname($this->ffmpeg['file']);
        $this->ffConfig=[
            'filePath' => $path.'/',   //文件路径
            'fileName' => $fileInfo[0],                      //不带后缀文件名
            'fileExt' => $fileInfo[1],                   //带后缀文件名
            'fileNameExt' => $fileNameExt,                   //带后缀文件名
            'webmExt' => 'webm',                              //新生成解密的临时文件后缀
            'pcmExt' => 'pcm',    
           'wavExt' => 'wav', 
           'mp3Ext' => 'mp3',//转码后临时文件pcm后缀
        ];
    }

    public function audioTotext(){
        //文件不存在
        if(!file_exists($this->ffmpeg['file'])){
            return false;
        }
        $str = file_get_contents($this->ffmpeg['file']);//将整个文件内容读入到一个字符串中
        //不带后缀的文件
        $newFile = $this->ffConfig['filePath'].$this->ffConfig['fileName'];
//        var_dump($newFile);
        $ext = $this->ffConfig['mp3Ext'];

        if(strstr($str,'data:audio')){
            $file = str_replace("data:audio/webm;base64,", '', $str);
            $file = base64_decode($file);
            //生成临时文件
            file_put_contents($newFile.'.'. $this->ffConfig['webmExt'], $file);

        }else{
            //执行脚本silk-v3转码
           // $cmd ="sh ./silk-v3/converter.sh ../".$this->ffmpeg['file'].' '.$this->ffConfig['webmExt'];
           // $res = shell_exec($cmd);
            	$cmd ="ffmpeg -y  -i {$newFile}.$ext  -acodec pcm_s16le -f s16le -ac 1 -ar 16000 {$newFile}.{$this->ffConfig['pcmExt']}";
          $res = shell_exec($cmd);
          $cmd ="sh ./silk-v3/converter.sh ../".$this->ffmpeg['file'].' '.$this->ffConfig['mp3Ext'];
            $res = shell_exec($cmd);
            if(!strstr($res,'[OK]')){
                 return false;
            }
        }

//        var_dump(file_get_contents($this->ffmpeg['file']));
        //执行脚本转码并返回转码信息
        //$cmd ="ffmpeg -y  -i {$newFile}.$ext  -acodec pcm_s16le -f s16le -ac 1 -ar 16000 {$newFile}.{$this->ffConfig['pcmExt']}";
      	$cmd ="ffmpeg -y  -i {$newFile}.$ext  -acodec pcm_s16le -f s16le -ac 1 -ar 16000 {$newFile}.{$this->ffConfig['pcmExt']}";
        $res = shell_exec($cmd);
      
       //	$cmd ="ffmpeg -y  -i {$newFile}.$ext  -acodec pcm_s16le -f s16le -ac 1 -ar 16000 {$newFile}.{$this->ffConfig['mp3Ext']}";
       // $res = shell_exec($cmd);
        unlink($newFile.$this->ffConfig['webmExt']);
       //unlink($newFile.$this->ffConfig['mp3Ext']);
       unlink($newFile.$this->ffConfig['pcmExt']);
        //命令执行失败的情况
     

        preg_match("/size=(.*?) time=(.*?) bitrate= (.*?)kbits\/s /", $res, $matches);
        return ['vedio'=>$newFile.'.'.$this->ffConfig['pcmExt'],'vedioInfo'=>$matches]?:[];
      
      
      
    }
}