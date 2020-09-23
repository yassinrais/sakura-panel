<?php
namespace SakuraPanel\Library\Translator;

use Phalcon\Di\Injectable;
use Phalcon\Translate\Adapter\NativeArray;
use Phalcon\Translate\InterpolatorFactory;
use Phalcon\Translate\TranslateFactory;

class Locale extends Injectable
{
    protected $defaultLanguage = "en";
    protected $messages = [];

    function __construct(){

        $interpolator = new InterpolatorFactory();
        $this->factory      = new TranslateFactory($interpolator);


        $this->defaultLanguage = getenv('DEFAULT_LANGUAGE') ?? $this->defaultLanguage;

    }

    /**
     * @return NativeArray
     */
    public function getTranslator(): NativeArray
    {
        $translateFolder = $this->config->application->translateDir;
        $language = $this->getLanguage();

        // Ask browser what is the best language

        
        $translationFile = $translateFolder . $language . '.json';
        $defaultFile = $translateFolder . $this->defaultLanguage . '.json';


        if (true === file_exists($translationFile)) {
            $this->messages =  array_merge(
                                $this->messages , 
                                json_decode(file_get_contents($translationFile), true)
                            );
        }elseif (true === file_exists($defaultFile)){
            $this->messages =  array_merge(
                                $this->messages , 
                                json_decode(file_get_contents($defaultFile), true)
                            );
        }
        
        $instance = $this->factory->newInstance(
            'array',
            [
                'content' => $this->messages,
            ]
        );

        return $instance;
    }
    /** 
     * @return Language
     */
    public function getLanguage()
    {
        $l = $this->request->getBestLanguage() ?? $this->defaultLanguage;
    
        if (!preg_match("/[a-zA-Z]{2}-[a-zA-Z]{2}/" , $l)){
            $l = $this->defaultLanguage;
        }

        return $l;
    }

    /** 
     * Append Translates
     * @return self
     */
    public function addTranslations($messages){
        $this->messages = array_merge($this->messages , $messages);

        return $this;
    }

    public function _($key)
    {
        return parent::_($key);
    }
}