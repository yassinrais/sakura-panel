<?php
/**
 * Copyright : https://github.com/m1ome/phalcon-datatables
 * Edit & Fix old version (phalcon 2.0.0) to (phalcon +4.0.0)
 * Original Authors : github@m1ome , github@abiosoft , github@duesentrieb26
 * Edited By : github@yassinrais  
 */
namespace SakuraPanel\Library\DataTable;

 
class ParamsParser extends \ControllerBase {

  protected $params = [];
  protected $page   = 1;

  public function onConstruct() {

    $params = [
      'draw'    => null,
      'start'   => 1,
      'length'  => 1,
      'columns' => [],
      'search'  => [],
      'order'   => []
    ];

    $request = $this->di->get('request');
    $requestParams = $request->isPost() ? $request->getPost() : $request->getQuery();
    $this->params = (array)$requestParams + $params;
    $this->setPage();
  }

  public function getParams() {
    return $this->params;
  }

  public function setPage() {
    $this->page = (int)(floor($this->params['start'] / $this->params['length']) + 1);
  }
  public function setLimit($limit = 0) {
    $this->params['length']  = $limit;
  }

  public function getPage() {
    return $this->page;
  }

  public function getColumnsSearch() {
    return array_filter(array_map(function($item) {
      return (isset($item['search']['value']) && strlen($item['search']['value'])) ? $item : null;
    }, $this->params['columns']));
  }

  public function getSearchableColumns() {
    return array_filter(array_map(function($item) {
      return (isset($item['searchable']) && $item['searchable'] === "true") ? $item['data'] : null;
    }, $this->params['columns']));
  }

  public function getDraw() {
    return $this->params['draw'];
  }

  public function getLimit() {
    return $this->params['length'];
  }

  public function getOffset() {
    return $this->params['start'];
  }

  public function getColumns() {
    return $this->params['columns'];
  }

  public function getColumnById($id) {
    return isset($this->params['columns'][$id]['data']) ? $this->params['columns'][$id]['data'] : null;
  }

  public function getSearch() {
    return $this->params['search'];
  }

  public function getOrder() {
    return $this->params['order'];
  }

  public function getSearchValue() {
    return isset($this->params['search']['value']) ? $this->params['search']['value'] : '';
  }
}
