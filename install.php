<?php 
declare(strict_types=1);
/**
 *
 *
 *  SakuraPanel >> neutrapp.com/page/sakura
 * 			   	>> Project Installation
 * 
 */


/**
 * CLI Colors
 */
class Console {
 
    static $foreground_colors = array(
        'bold'         => '1',    'dim'          => '2',
        'black'        => '0;30', 'dark_gray'    => '1;30',
        'blue'         => '0;34', 'light_blue'   => '1;34',
        'green'        => '0;32', 'light_green'  => '1;32',
        'cyan'         => '0;36', 'light_cyan'   => '1;36',
        'red'          => '0;31', 'light_red'    => '1;31',
        'purple'       => '0;35', 'light_purple' => '1;35',
        'brown'        => '0;33', 'yellow'       => '1;33',
        'light_gray'   => '0;37', 'white'        => '1;37',
        'normal'       => '0;39',
    );
    
    static $background_colors = array(
        'black'        => '40',   'red'          => '41',
        'green'        => '42',   'yellow'       => '43',
        'blue'         => '44',   'magenta'      => '45',
        'cyan'         => '46',   'light_gray'   => '47',
    );
 
    static $options = array(
        'underline'    => '4',    'blink'         => '5', 
        'reverse'      => '7',    'hidden'        => '8',
    );

    static $EOF = "\n";

    /**
     * Logs a string to console.
     * @param  string  $str        Input String
     * @param  string  $color      Text Color
     * @param  boolean $newline    Append EOF?
     * @param  [type]  $background Background Color
     * @return [type]              Formatted output
     */
    public static function log($str = '', $color = 'normal', $newline = true, $background_color = null)
    {
        if( is_bool($color) )
        {
            $newline = $color;
            $color   = 'normal';
        }
        elseif( is_string($color) && is_string($newline) )
        {
            $background_color = $newline;
            $newline          = true;
        }
        $str = $newline ? $str . self::$EOF : $str;

        echo self::$color($str, $background_color);
    }
    
    /**
     * Anything below this point (and its related variables):
     * Colored CLI Output is: (C) Jesse Donat
     * https://gist.github.com/donatj/1315354
     * -------------------------------------------------------------
     */
    
    /**
     * Catches static calls (Wildcard)
     * @param  string $foreground_color Text Color
     * @param  array  $args             Options
     * @return string                   Colored string
     */
    public static function __callStatic($foreground_color, $args)
    {
        $string         = $args[0];
        $colored_string = "";
 
        // Check if given foreground color found
        if( isset(self::$foreground_colors[$foreground_color]) ) {
            $colored_string .= "\033[" . self::$foreground_colors[$foreground_color] . "m";
        }
        else{
            die( $foreground_color . ' not a valid color');
        }
        
        array_shift($args);

        foreach( $args as $option ){
            // Check if given background color found
            if(isset(self::$background_colors[$option])) {
                $colored_string .= "\033[" . self::$background_colors[$option] . "m";
            }
            elseif(isset(self::$options[$option])) {
                $colored_string .= "\033[" . self::$options[$option] . "m";
            }
        }
        
        // Add string and end coloring
        $colored_string .= $string . "\033[0m";
        
        return $colored_string;
        
    }
 
    /**
     * Plays a bell sound in console (if available)
     * @param  integer $count Bell play count
     * @return string         Bell play string
     */
    public static function bell($count = 1) {
        echo str_repeat("\007", $count);
    }
 
    /**
     * Print a line
     * @param  integer $count Bell play count
     * @return string         Bell play string
     */
    public static function line($count = 30) {
        echo str_repeat("-", $count)."\n";
    }
 	

	public static function print($msg , $type = "GENERAL" , $color = "yellow")
	{
		Console::log(Console::cyan("[{$type}]") . Console::{$color}(" : $msg"));
	}
}
/**
 * Install
 */
class Install 
{
	protected $cache_dirs = [
		'cache/',
		'cache/plugins/',
		'cache/shared/',
		'cache/global/',
		'cache/security/',
		'cache/sessions/',
		'cache/views/',
	];
	function __construct()
	{
	}

	public function run()
	{
		$this->createCacheFolders();
		$this->createEnvFile();
	}
 

	private function createCacheFolders()
	{
		Console::line();
		Console::log('[CACHE] Create cache dirs ...' , "cyan");
		/**
		 * Create Cache Dirs
		 */
		sleep(1);
		foreach ($this->cache_dirs as $dir){
			if (!is_dir($dir)) {
				if ( mkdir($dir , 0775 , true) ) {
					Console::print("Create $dir Done !  " , "CACHE" , "green");
				}else{
					Console::print("Create $dir Failed!  " , "CACHE" , "red");
				}
			}else{
				Console::print("$dir already existed !  " , "CACHE" , "purple");
			}
		}

		Console::bell();
	}

	private function checkPhalcon(){
		Console::print("Check if Phalcon::4.0.3 extension !" ,"CONFIGS","red");
	}

	private function createEnvFile()
	{
		Console::line();
		$envex = ".env.example";
		$env = ".env";

		if (file_exists($envex)) {
			
			$envContent = file_get_contents($envex);

			if (file_exists($env)) {
				Console::print("$env file already existed !" ,"CONFIGS","red");

				Console::print(Console::yellow("Do you confirm to rewrite the file ? ","CONFIGS"));
				$rewrite = readline();
				if (!in_array(strtoupper($rewrite), ["Y","YES","OK","CONFIRM"])) {
					Console::print(Console::bold("Skip"). Console::yellow(" Rewriting file $env") ,"CONFIGS" , "red");
					return;
				}
			}
			Console::print("Rewriting file $env" ,"CONFIGS","yellow");
			sleep(2); // to give user the control to cancel the cli

			// read if existed

		}else{
			Console::print(".env.example file was nout founded  !" ,"CONFIGS","red");
		}
	}

}


/**
 * Run installer 
 */
$installer = new Install();

$installer->run();