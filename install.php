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
 * Install
 */
class Install 
{
    const SLEEP_TIME = 0;
    protected $configs = [];

	protected $cache_dirs = [
		'cache/',
		'cache/plugins/',
		'cache/shared/',
		'cache/global/',
		'cache/security/',
		'cache/sessions/',
        'cache/views/',
		'.phalcon/',
	];
	function __construct()
	{
	}

	public function run()
	{
        $tests = ['checkPhalcon','createCacheFolders','createEnvFile','checkDatabaseEnv','migrationDatabase'];
        foreach ($tests as $name) {
            if (method_exists($this, $name)) {
                if (!$this->{$name}()){
                    Console::bell();
                    Console::line();
                    exit(Console::print("Please check that you have the minimum requirement !"));
                }
            }
        }
	}
 

	private function createCacheFolders()
	{
		Console::line();
		Console::log('[CACHE] Create cache dirs ...' , "cyan");
		/**
		 * Create Cache Dirs
		 */
        $tests = [];
		foreach ($this->cache_dirs as $dir){
			if (!is_dir($dir)) {
                $tests[] = $mkdir = mkdir($dir , 0775 , true);
                if ( $mkdir ) {
					Console::print(Console::yellow($dir) . Console::green(" > Created !  ") , "CACHE" );
				}else{
					Console::print(Console::yellow($dir) . Console::red(" > Creation Failed!") , "CACHE" );
				}
			}else{
				Console::print(Console::yellow($dir) . Console::purple(" > already exists !") , "CACHE" );
			}
		}
        sleep($this::SLEEP_TIME);

        return !in_array(false, $tests);
	}

	private function checkPhalcon(){
		Console::print("Check if Phalcon::4.0.3 extension !" ,"EXTENSION","yellow");
	   

       if (class_exists("\Phalcon\Version")) {
            $v = explode(".", \Phalcon\Version::get().".");

            if ("{$v[0]}.{$v[1]}" == "4.0") {

                Console::print("The Phalcon Extension version 4.0 exists !","EXTENSION","green");
                sleep($this::SLEEP_TIME);

                return true;
            }   

       }

       Console::print("The Phalcon Extension PHP does not exists ! ","EXTENSION","red");
       return false;
    }

	private function createEnvFile()
	{
		Console::line();
		$envex = ".env.example";
		$env = ".env";
    
        $this->configs = [];

		if (file_exists($envex)) {
			
			$envContent = file_get_contents($envex);
			if (file_exists($env)) {
                $env_configs_file = explode("\n", str_replace("\r", "", file_get_contents($env)));
                foreach ($env_configs_file as $line) {
                    $c = explode("=", $line."=");

                    $k = $c[0];
                    $v = $c[1];

                    if (empty($k)) continue;
                    $v = str_split($v);
                    $closet = ($v[count($v)-1].$v[0] === '""') ? true : false;

                    $this->configs[$k] = $closet ? substr($c[1], 1 , count($v)-2): $c[1];

                }
				Console::print(Console::yellow($env) . Console::red(" file already exists !") ,"CONFIGS");

				echo Console::cyan("[CONFIGS] ").Console::yellow("Do you confirm to rewrite the file [N/y] ?");
                $rewrite = readline();
				if (!in_array(strtoupper($rewrite), ["Y","YES","OK","CONFIRM"]) || $rewrite === "") {
					Console::print(Console::bold("Skip"). Console::yellow(" Rewriting file $env") ,"CONFIGS" , "red");
                    return file_exists($env);
				}
			}

            $env_lines = explode("\n", str_replace("\n\n", "\n", str_replace("\r", "", $envContent)));
            $configs_lines = [];

            Console::print("Rewriting file $env" ,"CONFIGS","yellow");
            foreach ($env_lines as $line) {
                $c = explode("=", $line."=");

                $k = $c[0];
                $v = $c[1];

                if (empty($k)) continue;

                $v = str_split($v);
                $closet = ($v[count($v)-1].$v[0] === '""') ? true : false;
                $val =  $closet ? substr($c[1], 1 , count($v)-2): $c[1];

                if (!empty($this->configs[$k])) 
                    $val = $this->configs[$k];

                echo Console::cyan("[CONFIGS] : ") .Console::purple($k) . " -> Value [".Console::cyan($val)."] : ";
                $rd = readline();

                $this->configs[$k] = $rd !== "" ? $rd : $val;
                
                if ($rd !== "") $val = $rd;

                if (preg_match('/[^a-z_\-0-9]/i', $val)) 
                    $val = '"'.str_replace("\"", '\"', $rd).'"';
                
                $configs_lines[] = "$k=$val";
            }
			
            Console::print("Create `$env` file ... Ctrl + C to cancel ! ","CONFIGS","yellow");
            sleep($this::SLEEP_TIME); // to give user the control to cancel the cli

            $env_final_content = "";
            if (file_put_contents($env, implode("\n", $configs_lines))){
                Console::print("The `$env` file was created successfully !","CONFIGS","green");
            }else{
                Console::print("There is a problem creating `$env` file ! ","CONFIGS","red");
                return false;
            }

			// read if exists or not
		}else{
			Console::print(".env.example file was not found  !" ,"CONFIGS","red");
		}


        return file_exists($env);
	}

    public function checkDatabaseEnv()
    {
        Console::line();
        $dbName = $this->configs['DB_NAME'] ?? null;
        $dbHost = $this->configs['DB_HOST'] ?? null;
        $dbUser = $this->configs['DB_USER'] ?? null;
        $dbPass = $this->configs['DB_PASS'] ?? null;
        
        Console::print("Check Database information ($dbName,$dbHost,$dbUser)  ...","DATABASE","cyan");

        try {
            $dbConnection = @new mysqli($dbHost , $dbUser , $dbPass , $dbName);
                
            if (!$dbConnection->connect_error) 
                return true;
            else
                Console::print(Console::bold((string) $dbConnection->connect_error),"DATABASE","yellow");

        } catch (Exception $e) {
            Console::print(Console::bold($e->getMessage()),"DATABASE","yellow");
        }
        Console::print("Incorrect database information !","DATABASE","red");
        return !$dbConnection->connect_error;
    }

    public function migrationDatabase()
    {
        Console::print("Migration the models tables to Database  ..." ,"MIGRATION","cyan");
        Console::print("Execute : `phalcon migration run` ...","MIGRATION","yellow");

        ob_start();
        system("phalcon migration run");
        $ex = ob_get_contents();
        ob_clean();

        $ex = strtolower(preg_replace('/\s\s+/', ' ',$ex));

        return strpos($ex, "up to date");
    }

}


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
        Console::log(Console::cyan("[{$type}] : ") . Console::{$color}("$msg"));
    }
}
/**
 * Run installer 
 */
$installer = new Install();

$installer->run();


