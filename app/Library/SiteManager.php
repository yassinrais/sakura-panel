<?php 

namespace Sakura\Library;

use Sakura\Controllers\ControllerBase;

use \Sakura\Models\App\{
	SiteConfigs
};


/**
 * SiteManager
 */
class SiteManager extends ControllerBase
{
	public $settings = [];
	public $create = true;


	public function initialize()
	{
		$settings = new SiteConfigs();
		$list = $settings::find([
			'columns' => 'key, val',
			'cache' => [
	            'key'      => 'model-site-configs',
	        ]
	    ])->toArray();
		foreach ($list as $row) {
			$this->settings[$row['key']] = $row['val'] ?? null;
		}
	}

	public function get($key,$value='')
	{
		return $this::getKey($key,$value) ?: ($value ?: null);
	}

   public function getAll()
   {
	   	return $this->settings;
   }


	public function set($key,$value=null,$model=null)
	{
		$settings = $model ?: new SiteConfigs();
		$settings->key = $key;	
		$settings->val = $value;
		return $settings->save();	
	}

	public function getKey($key,$value='')
	{
		return in_array($key, $this->settings) ? $this->settings[$key] : $this::getCreateKey($key,$value);
	}

	public function getCreateKey($key, $value='')
	{
		$settings = new SiteConfigs();

		$keyVal = $settings::findFirst([
			' key = ?0 ',
			'bind'=>[
				$key
			]
		]); 

		if (!$keyVal && $this->create) {
			$this::set($key,$value,$settings);
		}else $value = $keyVal->val ?: $value;


		return $value;
	}


	public function theme()
	{
		return (object) [
			'name'=>'default',
			'assets'=>'assets/default/',
			'theme'=>'theme/'
		];
   }


   public function getBaseUrl()
   {
   	return $this->config->application->baseUrl;
   }

   	public function setConfigs($configs)
   	{
    	foreach ((object) $configs as $key => $value) {
    	    $this->settings[trim($key)]=  $value;
	   	}
    }
}