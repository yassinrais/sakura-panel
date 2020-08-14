<?php 

use Phalcon\Db\Column;
use Phalcon\Db\Index;
use Phalcon\Db\Reference;
use Phalcon\Migrations\Mvc\Model\Migration;

/**
 * Class PluginsMigration_102
 */
class PluginsMigration_102 extends Migration
{
    /**
     * Define the table structure
     *
     * @return void
     */
    public function morph()
    {
        $this->morphTable('plugins', [
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
                        'name',
                        [
                            'type' => Column::TYPE_VARCHAR,
                            'notNull' => true,
                            'size' => 255,
                            'after' => 'id'
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
                        'description',
                        [
                            'type' => Column::TYPE_TEXT,
                            'size' => 1,
                            'after' => 'title'
                        ]
                    ),
                    new Column(
                        'author',
                        [
                            'type' => Column::TYPE_VARCHAR,
                            'default' => "Unknown",
                            'size' => 255,
                            'after' => 'description'
                        ]
                    ),
                    new Column(
                        'version',
                        [
                            'type' => Column::TYPE_VARCHAR,
                            'default' => "v1.0.0",
                            'size' => 10,
                            'after' => 'author'
                        ]
                    ),
                    new Column(
                        'image',
                        [
                            'type' => Column::TYPE_TEXT,
                            'size' => 1,
                            'after' => 'version'
                        ]
                    ),
                    new Column(
                        'status',
                        [
                            'type' => Column::TYPE_INTEGER,
                            'default' => "0",
                            'notNull' => true,
                            'size' => 1,
                            'after' => 'image'
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
                    'AUTO_INCREMENT' => '19',
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
