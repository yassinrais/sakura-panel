<?php
/**
 * Copyright : https://github.com/m1ome/phalcon-datatables
 * Edit & Fix old version (phalcon 2.0.0) to (phalcon +4.0.0)
 * Original Authors : github@m1ome , github@abiosoft , github@duesentrieb26
 * Edited By : github@yassinrais  
 */
namespace SakuraPanel\Library\DataTable\Adapters;
use Phalcon\Paginator\Adapter\QueryBuilder as PhalconQueryBuilder;

class QueryBuilder extends AdapterInterface{
  protected $builder;
  public $ignoreUpperCase = false;

  public function setBuilder($builder) {
    $this->builder = $builder;
  }

  public function getResponse() {

    $builder = new PhalconQueryBuilder([
      'builder' => $this->builder,
      'limit'   => 1,
      'page'    => 1,
    ]);
 
    $total = $builder->paginate();

      
    $this->bind('global_search', function($column, $search) {
      $this->builder->orWhere((!$this->ignoreUpperCase) ? "{$column} LIKE :key_{$column}:" : "UPPER({$column}) LIKE UPPER(:key_{$column}:)", ["key_{$column}" => "%{$search}%"]);
    });

    $this->bind('column_search', function($column, $search) {
      $this->builder->andWhere((!$this->ignoreUpperCase) ? "{$column} LIKE :key_{$column}:" : "UPPER({$column}) LIKE UPPER(:key_{$column}:)", ["key_{$column}" => "%{$search}%"]);
    });

    $this->bind('order', function($order) {
      if (!empty($order)) {
        $this->builder->orderBy(implode(', ', $order));
      }
    });

    $builder = new PhalconQueryBuilder([
      'builder' => $this->builder,
      'limit'   => $this->parser->getLimit(),
      'page'    => $this->parser->getPage(),
    ]);

    $filtered = $builder->paginate();

    return $this->formResponse([
      'total'     => $total->total_items,
      'filtered'  => $filtered->total_items,
      'data'      => $filtered->items->toArray(),
    ]);
  }
}
