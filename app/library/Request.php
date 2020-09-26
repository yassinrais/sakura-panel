<?php 


namespace SakuraPanel\Library;

use Phalcon\Http\Request as PhalconRequest;


class Request extends PhalconRequest{


    public function getClientAddress(bool $trustForwardedHeader = NULL){
        return parent::getClientAddress(getenv("TRUST_FORWARDED_HEADER") == "true");
    }

}