<?php
namespace SakuraPanel\Library\Datatables;

use SakuraPanel\Library\Datatables\Adapters\QueryBuilder;
use SakuraPanel\Library\Datatables\Adapters\ResultSet;
use SakuraPanel\Library\Datatables\Adapters\ArrayAdapter;
use Phalcon\Http\Response;

class DataTable extends \Phalcon\Di\Injectable
{

    protected $options;
    protected $params;
    protected $response;  
    protected $ignoreUpperCase = false;

    /**
     *
     * @var ParamsParser
     */
    public $parser;


    public function setOptions($options = []) {
      $default = [
        'limit'   => 20,
        'length'  => 50,
      ];

      $this->options = $options + $default;

        $this->parser = new ParamsParser($this->options['limit']);
      
    }

    public function setIngoreUpperCase($bool = false)
    {
      $this->ignoreUpperCase = $bool;
    }


    public function addCustomColumn($name , $callback)
    {
      foreach ($this->response['data'] as $key => $data) {
        $this->response['data'][$key][$name] = $callback($key , $data);
      }

      return $this;
    }

    public function __construct($options = [])
    {
        $default = [
            'limit' => 20,
            'length' => 50,
        ];
        $this->setOptions($options);
        $this->options = $options + $default;
    }

    public function getParams()
    {
        return $this->parser->getParams();
    }

    public function getResponse()
    {
        return !empty($this->response) ? $this->response : [];
    }
    
    public function exportResponse($type = 'Excel')
    {
        $data = $this->getResponse()['data'];

        $headings = array();

        foreach($data[0] as $key => $value)
        {
            $headings[$key] = $key;
        }

        array_unshift($data, $headings);

        unset($headings);

        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $spreadsheet->getActiveSheet()->fromArray($data, Null, 'A1');

        $totalCols = $spreadsheet->getActiveSheet()->getHighestColumn();
        $totalRows = $spreadsheet->getActiveSheet()->getHighestRow();
        
        // In Case we have 'DT_RowId' in our data, then remove last column and re-calculate columns
        if(array_key_exists('DT_RowId', $data[0]))
        {
            $spreadsheet->getActiveSheet()->removeColumn($totalCols);
            $totalCols = $spreadsheet->getActiveSheet()->getHighestColumn();
        }

        // Default Styles
        $styleHeader = [
            'font' => [
                'bold' => true,
            ],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
            ],
        ];
        $styleCells = [

            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                ],
            ]
        ];
        $spreadsheet->getActiveSheet()->getStyle("A1:{$totalCols}1")->applyFromArray($styleHeader);
        $spreadsheet->getActiveSheet()->getStyle("A1:{$totalCols}{$totalRows}")->applyFromArray($styleCells);

        $fileName = "Export_" . date('m-d-Y');

        switch ($type) {
            case 'PDF':
                header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
                header('Content-Disposition: attachment;filename="'. $fileName .'.pdf"');
                header('Cache-Control: max-age=0');

                $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Mpdf');
                $writer->save('php://output');
                break;
            case 'Excel':
            default:
                header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
                header('Content-Disposition: attachment;filename="'. $fileName .'.xlsx"');
                header('Cache-Control: max-age=0');

                $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');
                $writer->save('php://output');
                break;
        }
    }

    public function sendResponse()
    {
        if ($this->di->has('view')) {
            $this->di->get('view')->disable();
        }

        $response = new Response();
        $response->setContentType('application/json', 'utf8');
        $response->setJsonContent($this->getResponse());
        $response->send();
    }

    public function fromBuilder($builder, $columns = [])
    {
        if (empty($columns)) {
            $columns = $builder->getColumns();
            $columns = (is_array($columns)) ? $columns : array_map('trim', explode(',', $columns));
        }

        $adapter = new QueryBuilder($this->options['length']);
        $adapter->setIngoreUpperCase($this->ignoreUpperCase);
        $adapter->setBuilder($builder);
        $adapter->setParser($this->parser);
        $adapter->setColumns($columns);
        $this->response = $adapter->getResponse();


        return $this;
    }

    public function fromResultSet($resultSet, $columns = [])
    {
        if (empty($columns) && $resultSet->count() > 0) {
            $columns = array_keys($resultSet->getFirst()->toArray());
            $resultSet->rewind();
        }

        $adapter = new ResultSet($this->options['length']);
        $adapter->setIngoreUpperCase($this->ignoreUpperCase);
        $adapter->setResultSet($resultSet);
        $adapter->setParser($this->parser);
        $adapter->setColumns($columns);
        $this->response = $adapter->getResponse();

        return $this;
    }

    public function fromArray($array, $columns = [])
    {
        if (empty($columns) && count($array) > 0) {
            $columns = array_keys(current($array));
        }

        $adapter = new ArrayAdapter($this->options['length']);
        $adapter->setArray($array);
        $adapter->setParser($this->parser);
        $adapter->setColumns($columns);
        $this->response = $adapter->getResponse();

        return $this;
    }

}