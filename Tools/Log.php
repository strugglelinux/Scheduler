<?php
namespace Scheduler\Tools;

/**
 * Created by PhpStorm.
 * User: yuliu
 * Date: 17/8/24
 * Time: 下午2:49
 */
class Log {

    const INFO = 'INFO';
    const WARN = 'WARN';
    const ERROR = 'ERROR';
    const NOTIC = 'NOTIC';
    const DEBUG = 'DEBUG';
    const MaxFileSize = 1024 * 1024 * 5;//5M
    private static $dir = './Log';
    private static $isShow = false;

    public static function init(&$foregroundColors,&$backgroundColors) {
        $foregroundColors['black'] = '0;30';
        $foregroundColors['dark_gray'] = '1;30';
        $foregroundColors['blue'] = '0;34';
        $foregroundColors['light_blue'] = '1;34';
        $foregroundColors['green'] = '0;32';
        $foregroundColors['light_green'] = '1;32';
        $foregroundColors['cyan'] = '0;36';
        $foregroundColors['light_cyan'] = '1;36';
        $foregroundColors['red'] = '0;31';
        $foregroundColors['light_red'] = '1;31';
        $foregroundColors['purple'] = '0;35';
        $foregroundColors['light_purple'] = '1;35';
        $foregroundColors['brown'] = '0;33';
        $foregroundColors['yellow'] = '1;33';
        $foregroundColors['light_gray'] = '0;37';
        $foregroundColors['white'] = '1;37';
        $backgroundColors['black'] = '40';
        $backgroundColors['red'] = '41';
        $backgroundColors['green'] = '42';
        $backgroundColors['yellow'] = '43';
        $backgroundColors['blue'] = '44';
        $backgroundColors['magenta'] = '45';
        $backgroundColors['cyan'] = '46';
        $backgroundColors['light_gray'] = '47';
    }

    public static function getColoredString($string, $foregroundColor = null, $backgroundColor = null) {
        $string = str_replace("\n",'',$string);
        $foregroundColors = array();
        $backgroundColors = array();
        self::init($foregroundColors, $backgroundColors);
        $coloredString = "";
        // Check if given foreground color found
        if (isset($foregroundColors[$foregroundColor])) {
            $coloredString .= "\033[" . $foregroundColors[$foregroundColor] . "m";
        }
        // Check if given background color found
        if (isset($backgroundColors[$backgroundColor])) {
            $coloredString .= "\033[" . $backgroundColors[$backgroundColor] . "m";
        }
        // Add string and end coloring
        $coloredString .=  $string . "\033[0m \n";
        return $coloredString;
    }

    public static function setDir($dir){
        self::$dir = rtrim(str_replace('\\','/',$dir),'/');
        if(!is_dir(self::$dir)){
            mkdir(self::$dir,777);
        }
    }

    public static function setIsShow($show){
        self::$isShow = $show;
    }

    private static function write($level,$content){
        $content = date('Y-m-d H:i:s',time()).' ['. $level .'] ' . $content;
        if(self::$isShow){
            $foregroundColors = array();
            $backgroundColors = array();
            self::init($foregroundColors,$backgroundColors);
            switch($level){
                case self::INFO :
                    echo self::getColoredString($content,$foregroundColors['green'],$backgroundColors['cyan']);
                    break;
                case self::WARN :
                    echo self::getColoredString($content,$foregroundColors['yellow'],$backgroundColors['cyan']);
                    break;
                case self::ERROR :
                    echo self::getColoredString($content,$foregroundColors['red'],$backgroundColors['cyan']);
                    break;
                case self::DEBUG:
                    echo self::getColoredString($content,$foregroundColors['blue'],$backgroundColors['cyan']);
                    break;
                default:
                    echo self::getColoredString($content,$foregroundColors['black'],$backgroundColors['cyan']);
                    break;
            }
            return ;
        }
        $file = self::$dir.'/'.date('Y-m-d').'.log';
        if(!file_exists($file)){
            file_put_contents($file,$content,FILE_APPEND);
        }else{
            if(filesize($file) >= self::MaxFileSize){
                $file = self::$dir.'/'.date('Y-m-d').rand(1000,9999).'.log';
            }
            file_put_contents($file,$content,FILE_APPEND);
        }
    }
    public static function debug($msg){
        self::write(self::DEBUG,$msg);
    }

    public static function info($msg){
        self::write(self::INFO,$msg);
    }

    public static function warn($msg){
        self::write(self::WARN,$msg);
    }

    public static function error($msg){
        self::write(self::ERROR,$msg);
    }

}
