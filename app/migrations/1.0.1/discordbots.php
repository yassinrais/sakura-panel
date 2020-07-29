<?php 

use Phalcon\Db\Column;
use Phalcon\Db\Index;
use Phalcon\Db\Reference;
use Phalcon\Migrations\Mvc\Model\Migration;

/**
 * Class DiscordbotsMigration_101
 */
class DiscordbotsMigration_101 extends Migration
{
    /**
     * Define the table structure
     *
     * @return void
     */
    public function morph()
    {
        $this->morphTable('discordbots', [
                'columns' => [
                    new Column(
                        'id',
                        [
                            'type' => Column::TYPE_INTEGER,
                            'notNull' => true,
                            'autoIncrement' => true,
                            'size' => 11,
                            'first' => true
                        ]
                    ),
                    new Column(
                        'user_id',
                        [
                            'type' => Column::TYPE_INTEGER,
                            'notNull' => true,
                            'size' => 11,
                            'after' => 'id'
                        ]
                    ),
                    new Column(
                        'name',
                        [
                            'type' => Column::TYPE_VARCHAR,
                            'notNull' => true,
                            'size' => 255,
                            'after' => 'user_id'
                        ]
                    ),
                    new Column(
                        'title',
                        [
                            'type' => Column::TYPE_VARCHAR,
                            'notNull' => true,
                            'size' => 255,
                            'after' => 'name'
                        ]
                    ),
                    new Column(
                        'note',
                        [
                            'type' => Column::TYPE_TEXT,
                            'size' => 1,
                            'after' => 'title'
                        ]
                    ),
                    new Column(
                        'hostname',
                        [
                            'type' => Column::TYPE_VARCHAR,
                            'size' => 500,
                            'after' => 'note'
                        ]
                    ),
                    new Column(
                        'ip',
                        [
                            'type' => Column::TYPE_VARCHAR,
                            'notNull' => true,
                            'size' => 200,
                            'after' => 'hostname'
                        ]
                    ),
                    new Column(
                        'port',
                        [
                            'type' => Column::TYPE_INTEGER,
                            'notNull' => true,
                            'size' => 10,
                            'after' => 'ip'
                        ]
                    ),
                    new Column(
                        'status',
                        [
                            'type' => Column::TYPE_INTEGER,
                            'default' => "0",
                            'notNull' => true,
                            'size' => 1,
                            'after' => 'port'
                        ]
                    ),
                    new Column(
                        'created_at',
                        [
                            'type' => Column::TYPE_INTEGER,
                            'default' => "0",
                            'notNull' => true,
                            'size' => 255,
                            'after' => 'status'
                        ]
                    ),
                    new Column(
                        'created_ip',
                        [
                            'type' => Column::TYPE_VARCHAR,
                            'size' => 200,
                            'after' => 'created_at'
                        ]
                    ),
                    new Column(
                        'updated_at',
                        [
                            'type' => Column::TYPE_INTEGER,
                            'default' => "0",
                            'notNull' => true,
                            'size' => 255,
                            'after' => 'created_ip'
                        ]
                    ),
                    new Column(
                        'updated_ip',
                        [
                            'type' => Column::TYPE_VARCHAR,
                            'size' => 200,
                            'after' => 'updated_at'
                        ]
                    )
                ],
                'indexes' => [
                    new Index('PRIMARY', ['id'], 'PRIMARY')
                ],
                'options' => [
                    'TABLE_TYPE' => 'BASE TABLE',
                    'AUTO_INCREMENT' => '3',
                    'ENGINE' => 'MyISAM',
                    'TABLE_COLLATION' => 'utf8_bin'
                ],
            ]
        );
    }

    /**
     * Run the migrations
     *
     * @return void
     */
    public function up()
    {

    }

    /**
     * Reverse the migrations
     *
     * @return void
     */
    public function down()
    {

    }

}
