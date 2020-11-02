<?php
namespace Sakura\Library\Datatables\Adapters;

use Sakura\Library\Datatables\ParamsParser;

abstract class AdapterInterface {

  protected $parser  = null;
  protected $columns = [];
  protected $length  = 30;

  public function __construct($length) {
    $this->length = $length;
  }

  abstract public function getResponse();

  public function setParser(ParamsParser $parser) {
    $this->parser = $parser;
  }

  public function setColumns(array $columns) {
    foreach ($columns as $i => $column) {
      if (is_array($column)) {
        $columnName = $column[0];
        $columns[$i] = [$columnName];
        if (!isset($column['alias'])) {
          $pos = strpos($column, '.');
          if ($pos !== false) {
            $columns[$i]['alias'] = substr($column,  $pos + 1);
          }
        } else {
          $columns[$i]['alias'] = $column['alias'];
        }
      } else {
        $colArray = explode(" as ", $column);
        $column = $colArray[0];
        $columns[$i] = [$column];
        if (isset($colArray[1])) {
          $columns[$i]['alias'] = $colArray[1];
        } else {
          $pos = strpos($column, '.');
          if ($pos !== false) {
            $columns[$i]['alias'] = substr($column,  $pos + 1);
          }
        }
      }
    }

    $this->columns = $columns;
  }

  public function getColumns() {
    return $this->columns;
  }

  public function columnExists($column, $getAlias = false) {
    $col = null;
    if (isset($this->columns) && is_array($this->columns)) {
      foreach ($this->columns as $columnDefinition) {
        if (is_array($columnDefinition)) {
          if ($columnDefinition[0] != $column) {
            if (isset($columnDefinition['alias']) && $columnDefinition['alias'] == $column) {
              if ($getAlias) {
                $col = $columnDefinition['alias'];
              } else {
                $col = $columnDefinition[0];
              }
              break;
            }
          } else {
            if ($getAlias && isset($columnDefinition['alias'])) {
                $col = $columnDefinition['alias'];
            } else {
              $col = $columnDefinition[0];
            }
            break;
          }
        } elseif ($column == $columnDefinition) {
          $col = $column;
          break;
        }
      }
    }
    
    return $col;
  }

  public function getParser() {
    return $this->parser;
  }

  public function formResponse($options) {
    $defaults = [
      'total'     => 0,
      'filtered'  => 0,
      'data'      => []
    ];
    $options += $defaults;

    $response = [];
    $response['draw'] = $this->parser->getDraw();
    $response['recordsTotal'] = $options['total'];
    $response['recordsFiltered'] = $options['filtered'];

    if (count($options['data'])) {
      foreach($options['data'] as $item) {
        if (isset($item['id'])) {
          $item['DT_RowId'] = $item['id'];
        }

        $response['data'][] = $item;
      }
    } else {
      $response['data'] = [];
    }

    return $response;
  }

  public function sanitaze($string) {
    return mb_substr($string, 0, $this->length);
  }

  public function bind($case, $getAlias, $closure) {
    switch($case) {
      case "global_search":
        $search = $this->parser->getSearchValue();
        if (!mb_strlen($search)) return;

        foreach ($this->parser->getSearchableColumns() as $column) {
          $col = $this->columnExists($column, $getAlias);
          if (is_null($col)) continue;
          $closure($col, $this->sanitaze($search));
        }
        break;
      case "column_search":
        $columnSearch = $this->parser->getColumnsSearch();
        if (!$columnSearch) return;

        foreach ($columnSearch as $key => $column) {
          $col = $this->columnExists($column['data'], $getAlias);
          if (is_null($col))  continue;
          $closure($col, $this->sanitaze($column['search']['value']));
        }
        break;
      case "order":
        $order = $this->parser->getOrder();
        if (!$order) return;

        $orderArray = [];

        foreach($order as $orderBy) {
          if (!isset($orderBy['dir']) || !isset($orderBy['column'])) continue;
          $orderDir = $orderBy['dir'];

          $column = $this->parser->getColumnById($orderBy['column']);
          $col = $this->columnExists($column, $getAlias);
          if (is_null($col)) continue;

          $orderArray[] = "{$col} {$orderDir}";
        }

        $closure($orderArray);
        break;
      default:
        throw new \Exception('Unknown bind type');
    }

  }

}