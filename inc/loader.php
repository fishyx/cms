<?php
spl_autoload_register(array('Loader','load'));
class Loader{
    private static $loader = array(); 
    private static $paths = array();
    
    public static function getLoader(){ 
        if(!self::$loader){      
            self::$loader = include(dirname(__DIR__) . '\include\autoload.php');
        }
        return self::$loader;
    }
    
    public static function register ($class = null, $file = null) {
        
        if($class && is_file($file)) {
            // Force to lower case.
            $class = strtolower($class);
            self::$paths[$class] = $file;
        }
    }    
    public static function load( $class ) {  
        $class = strtolower($class); //force to lower case
        if (class_exists($class)) {
              return;
        }
        $cn = strtolower($class);
        if(isset(self::$paths[$cn])) {
            include(self::$paths[$cn]);
            return true;
        }
        return false;
    }    
} 
?>
