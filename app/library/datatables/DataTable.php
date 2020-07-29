<?php
/**
 * Copyright : https://github.com/m1ome/phalcon-datatables
 * Edit & Fix old version (phalcon 2.0.0) to (phalcon +4.0.0)
 * Original Authors : github@m1ome , github@abiosoft , github@duesentrieb26
 * Edited By : github@yassinrais  
 */
namespace SakuraPanel\Library\DataTable;

use SakuraPanel\Library\DataTable\Adapters\QueryBuilder;
use SakuraPanel\Library\DataTable\Adapters\ResultSet;
use SakuraPanel\Library\DataTable\Adapters\ArrayAdapter;
use Phalcon\Http\Response;

class DataTable  extends \ControllerBase{

  protected $options;
  protected $params;
  protected $response;
  public    $parser;
  public $ignoreUpperCase = false;

  public function onConstruct($options = []) {
    $default = [
      'limit'   => 20,
      'length'  => 50,
    ];

    $this->options = $options + $default;
    $this->parser = new ParamsParser();
    $this->parser->setLimit((int) $this->options['limit']);
  }

  public function getParams() {
    return $this->parser->getParams();
  }

  public function getResponse() {
    return !empty($this->response) ? $this->response : [];
  }

  public function sendResponse() {
    if ($this->di->has('view')) {
      $this->di->get('view')->disable();
    }

    $response = new Response();
    $response->setContentType('application/json', 'utf8');
    $response->setJsonContent($this->getResponse());
    $response->send();
  } 

  public function setIngoreUpperCase($bool = false)
  {
    $this->ignoreUpperCase = $bool;
  }

  public function fromBuilder($builder, $columns = []) {
    if (empty($columns)) {
      $columns = $builder->getColumns();
      $columns = (is_array($columns)) ? $columns : array_map('trim', explode(',', $columns));
    }

    $adapter = new QueryBuilder($this->options['length']);
    $adapter->ignoreUpperCase = $this->ignoreUpperCase;
    $adapter->setBuilder($builder);
    $adapter->setParser($this->parser);
    $adapter->setColumns($columns);
    $this->response = $adapter->getResponse();

    return $this;
  }

  public function fromResultSet($resultSet, $columns = []) {
    if(empty($columns) && $resultSet->count() > 0) {
      $columns = array_keys($resultSet->getFirst()->toArray());
      $resultSet->rewind();
    }

    $adapter = new ResultSet($this->options['length']);
    $adapter->setResultSet($resultSet);
    $adapter->setParser($this->parser);
    $adapter->setColumns($columns);
    $this->response = $adapter->getResponse();

    return $this;
  }

  public function fromArray($array, $columns = []) {
    if(empty($columns) && count($array) > 0) {
      $columns = array_keys(current($array));
    }

    $adapter = new ArrayAdapter($this->options['length']);
    $adapter->setArray($array);
    $adapter->setParser($this->parser);
    $adapter->setColumns($columns);
    $this->response = $adapter->getResponse();

    return $this;
  }


  public function addCustomColumn($name , $callback)
  {
    foreach ($this->response['data'] as $key => $data) {
      $this->response['data'][$key][$name] = $callback($key , $data);
    }

    return $this;
  }
}
