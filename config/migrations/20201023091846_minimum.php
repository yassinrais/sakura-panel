<?php

use Phinx\Db\Adapter\MysqlAdapter;

class Minimum extends Phinx\Migration\AbstractMigration
{
    public function change()
    {
        $this->execute("ALTER DATABASE CHARACTER SET 'utf8';");
        $this->execute("ALTER DATABASE COLLATE='utf8_bin';");
        $this->table('auth_security', [
                'id' => false,
                'primary_key' => ['id'],
                'engine' => 'MyISAM',
                'encoding' => 'utf8',
                'collation' => 'utf8_bin',
                'comment' => '',
                'row_format' => 'DYNAMIC',
            ])
            ->addColumn('id', 'integer', [
                'null' => false,
                'limit' => MysqlAdapter::INT_REGULAR,
                'identity' => 'enable',
            ])
            ->addColumn('suspend', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => '1',
                'after' => 'id',
            ])
            ->addColumn('attemps', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'after' => 'suspend',
            ])
            ->addColumn('last_attemps', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'after' => 'attemps',
            ])
            ->addColumn('banned', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => '1',
                'after' => 'last_attemps',
            ])
            ->addColumn('suspend_expire', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'after' => 'banned',
            ])
            ->addColumn('ip', 'string', [
                'null' => false,
                'limit' => 200,
                'collation' => 'utf8_bin',
                'encoding' => 'utf8',
                'after' => 'suspend_expire',
            ])
            ->create();
        $this->table('plugins', [
                'id' => false,
                'primary_key' => ['id'],
                'engine' => 'MyISAM',
                'encoding' => 'utf8',
                'collation' => 'utf8_bin',
                'comment' => '',
                'row_format' => 'DYNAMIC',
            ])
            ->addColumn('id', 'integer', [
                'null' => false,
                'limit' => MysqlAdapter::INT_REGULAR,
                'identity' => 'enable',
            ])
            ->addColumn('name', 'string', [
                'null' => false,
                'limit' => 255,
                'collation' => 'utf8_bin',
                'encoding' => 'utf8',
                'after' => 'id',
            ])
            ->addColumn('title', 'string', [
                'null' => false,
                'limit' => 255,
                'collation' => 'utf8_bin',
                'encoding' => 'utf8',
                'after' => 'name',
            ])
            ->addColumn('description', 'text', [
                'null' => true,
                'default' => null,
                'limit' => 65535,
                'collation' => 'utf8_bin',
                'encoding' => 'utf8',
                'after' => 'title',
            ])
            ->addColumn('author', 'string', [
                'null' => true,
                'default' => '\'Unknown\'',
                'limit' => 255,
                'collation' => 'utf8_bin',
                'encoding' => 'utf8',
                'after' => 'description',
            ])
            ->addColumn('version', 'string', [
                'null' => true,
                'default' => '\'v1.0.0\'',
                'limit' => 10,
                'collation' => 'utf8_bin',
                'encoding' => 'utf8',
                'after' => 'author',
            ])
            ->addColumn('image', 'text', [
                'null' => true,
                'default' => null,
                'limit' => 65535,
                'collation' => 'utf8_bin',
                'encoding' => 'utf8',
                'after' => 'version',
            ])
            ->addColumn('installed', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => '1',
                'after' => 'image',
            ])
            ->addColumn('status', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => '1',
                'after' => 'installed',
            ])
            ->addColumn('created_at', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => '255',
                'after' => 'status',
            ])
            ->addColumn('created_ip', 'string', [
                'null' => true,
                'default' => null,
                'limit' => 200,
                'collation' => 'utf8_bin',
                'encoding' => 'utf8',
                'after' => 'created_at',
            ])
            ->addColumn('updated_at', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => '255',
                'after' => 'created_ip',
            ])
            ->addColumn('updated_ip', 'string', [
                'null' => true,
                'default' => null,
                'limit' => 200,
                'collation' => 'utf8_bin',
                'encoding' => 'utf8',
                'after' => 'updated_at',
            ])
            ->create();
        $this->table('resources', [
                'id' => false,
                'primary_key' => ['id'],
                'engine' => 'MyISAM',
                'encoding' => 'utf8',
                'collation' => 'utf8_bin',
                'comment' => '',
                'row_format' => 'DYNAMIC',
            ])
            ->addColumn('id', 'string', [
                'null' => false,
                'limit' => 32,
                'collation' => 'utf8_bin',
                'encoding' => 'utf8',
            ])
            ->addColumn('name', 'string', [
                'null' => false,
                'limit' => 255,
                'collation' => 'utf8_bin',
                'encoding' => 'utf8',
                'after' => 'id',
            ])
            ->addColumn('description', 'text', [
                'null' => true,
                'default' => null,
                'limit' => 65535,
                'collation' => 'utf8_bin',
                'encoding' => 'utf8',
                'after' => 'name',
            ])
            ->addColumn('status', 'integer', [
                'null' => false,
                'default' => '1',
                'limit' => '2',
                'after' => 'description',
            ])
            ->create();
        $this->table('resources_accesses', [
                'id' => false,
                'primary_key' => ['id'],
                'engine' => 'MyISAM',
                'encoding' => 'utf8',
                'collation' => 'utf8_bin',
                'comment' => '',
                'row_format' => 'DYNAMIC',
            ])
            ->addColumn('id', 'integer', [
                'null' => false,
                'limit' => MysqlAdapter::INT_REGULAR,
                'identity' => 'enable',
            ])
            ->addColumn('resource_id', 'integer', [
                'null' => false,
                'limit' => MysqlAdapter::INT_REGULAR,
                'after' => 'id',
            ])
            ->addColumn('name', 'string', [
                'null' => false,
                'limit' => 50,
                'collation' => 'utf8_bin',
                'encoding' => 'utf8',
                'after' => 'resource_id',
            ])
            ->addColumn('description', 'text', [
                'null' => true,
                'default' => null,
                'limit' => 65535,
                'collation' => 'utf8_bin',
                'encoding' => 'utf8',
                'after' => 'name',
            ])
            ->create();
        $this->table('roles', [
                'id' => false,
                'primary_key' => ['id'],
                'engine' => 'MyISAM',
                'encoding' => 'utf8',
                'collation' => 'utf8_bin',
                'comment' => '',
                'row_format' => 'DYNAMIC',
            ])
            ->addColumn('id', 'integer', [
                'null' => false,
                'limit' => MysqlAdapter::INT_REGULAR,
                'identity' => 'enable',
            ])
            ->addColumn('name', 'string', [
                'null' => false,
                'limit' => 100,
                'collation' => 'utf8_bin',
                'encoding' => 'utf8',
                'after' => 'id',
            ])
            ->addColumn('title', 'string', [
                'null' => true,
                'default' => null,
                'limit' => 200,
                'collation' => 'utf8_bin',
                'encoding' => 'utf8',
                'after' => 'name',
            ])
            ->addColumn('description', 'text', [
                'null' => true,
                'default' => null,
                'limit' => 65535,
                'collation' => 'utf8_bin',
                'encoding' => 'utf8',
                'after' => 'title',
            ])
            ->addColumn('inherit', 'string', [
                'null' => true,
                'default' => null,
                'limit' => 100,
                'collation' => 'utf8_bin',
                'encoding' => 'utf8',
                'after' => 'description',
            ])
            ->addColumn('status', 'integer', [
                'null' => true,
                'default' => '1',
                'limit' => MysqlAdapter::INT_REGULAR,
                'after' => 'inherit',
            ])
            ->addColumn('created_at', 'integer', [
                'null' => true,
                'default' => null,
                'limit' => MysqlAdapter::INT_REGULAR,
                'after' => 'status',
            ])
            ->addColumn('created_ip', 'string', [
                'null' => true,
                'default' => null,
                'limit' => 255,
                'collation' => 'utf8_bin',
                'encoding' => 'utf8',
                'after' => 'created_at',
            ])
            ->addColumn('updated_at', 'integer', [
                'null' => true,
                'default' => null,
                'limit' => MysqlAdapter::INT_REGULAR,
                'after' => 'created_ip',
            ])
            ->addColumn('updated_ip', 'string', [
                'null' => true,
                'default' => null,
                'limit' => 255,
                'collation' => 'utf8_bin',
                'encoding' => 'utf8',
                'after' => 'updated_at',
            ])
            ->create();
        $this->table('role_permissions', [
                'id' => false,
                'primary_key' => ['role_id', 'resource_id', 'access_id'],
                'engine' => 'MyISAM',
                'encoding' => 'utf8',
                'collation' => 'utf8_bin',
                'comment' => '',
                'row_format' => 'FIXED',
            ])
            ->addColumn('role_id', 'integer', [
                'null' => false,
                'limit' => MysqlAdapter::INT_REGULAR,
                'identity' => 'enable',
            ])
            ->addColumn('resource_id', 'integer', [
                'null' => false,
                'limit' => MysqlAdapter::INT_REGULAR,
                'after' => 'role_id',
            ])
            ->addColumn('access_id', 'integer', [
                'null' => false,
                'limit' => MysqlAdapter::INT_REGULAR,
                'after' => 'resource_id',
            ])
            ->addColumn('allowed', 'integer', [
                'null' => false,
                'limit' => '3',
                'after' => 'access_id',
            ])
            ->addColumn('status', 'integer', [
                'null' => false,
                'default' => '1',
                'limit' => '2',
                'after' => 'allowed',
            ])
            ->create();
        $this->table('site_configs', [
                'id' => false,
                'primary_key' => ['id'],
                'engine' => 'MyISAM',
                'encoding' => 'utf8',
                'collation' => 'utf8_bin',
                'comment' => '',
                'row_format' => 'DYNAMIC',
            ])
            ->addColumn('id', 'integer', [
                'null' => false,
                'limit' => MysqlAdapter::INT_REGULAR,
                'identity' => 'enable',
            ])
            ->addColumn('key', 'text', [
                'null' => true,
                'default' => null,
                'limit' => MysqlAdapter::TEXT_MEDIUM,
                'collation' => 'utf8_bin',
                'encoding' => 'utf8',
                'after' => 'id',
            ])
            ->addColumn('val', 'text', [
                'null' => true,
                'default' => null,
                'limit' => MysqlAdapter::TEXT_MEDIUM,
                'collation' => 'utf8_bin',
                'encoding' => 'utf8',
                'after' => 'key',
            ])
            ->addColumn('type', 'string', [
                'null' => false,
                'default' => '\'string\'',
                'limit' => 100,
                'collation' => 'utf8_bin',
                'encoding' => 'utf8',
                'after' => 'val',
            ])
            ->addColumn('created_at', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'after' => 'type',
            ])
            ->addColumn('updated_at', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'after' => 'created_at',
            ])
            ->create();
        $this->table('users', [
                'id' => false,
                'primary_key' => ['id'],
                'engine' => 'MyISAM',
                'encoding' => 'utf8',
                'collation' => 'utf8_bin',
                'comment' => '',
                'row_format' => 'DYNAMIC',
            ])
            ->addColumn('id', 'integer', [
                'null' => false,
                'limit' => MysqlAdapter::INT_REGULAR,
                'identity' => 'enable',
            ])
            ->addColumn('username', 'string', [
                'null' => false,
                'limit' => 100,
                'collation' => 'utf8_bin',
                'encoding' => 'utf8',
                'after' => 'id',
            ])
            ->addColumn('email', 'string', [
                'null' => false,
                'limit' => 255,
                'collation' => 'utf8_bin',
                'encoding' => 'utf8',
                'after' => 'username',
            ])
            ->addColumn('password', 'string', [
                'null' => false,
                'limit' => 255,
                'collation' => 'utf8_bin',
                'encoding' => 'utf8',
                'after' => 'email',
            ])
            ->addColumn('role_name', 'string', [
                'null' => false,
                'default' => '\'guest\'',
                'limit' => 255,
                'collation' => 'utf8_bin',
                'encoding' => 'utf8',
                'after' => 'password',
            ])
            ->addColumn('avatar', 'text', [
                'null' => true,
                'default' => null,
                'limit' => 65535,
                'collation' => 'utf8_bin',
                'encoding' => 'utf8',
                'after' => 'role_name',
            ])
            ->addColumn('status', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => '2',
                'after' => 'avatar',
            ])
            ->addColumn('fullname', 'string', [
                'null' => true,
                'default' => '\'Unnamed\'',
                'limit' => 255,
                'collation' => 'utf8_bin',
                'encoding' => 'utf8',
                'after' => 'status',
            ])
            ->addColumn('created_at', 'integer', [
                'null' => true,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'after' => 'fullname',
            ])
            ->addColumn('created_ip', 'string', [
                'null' => true,
                'default' => null,
                'limit' => 100,
                'collation' => 'utf8_bin',
                'encoding' => 'utf8',
                'after' => 'created_at',
            ])
            ->addColumn('updated_at', 'integer', [
                'null' => true,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'after' => 'created_ip',
            ])
            ->addColumn('updated_ip', 'string', [
                'null' => true,
                'default' => null,
                'limit' => 100,
                'collation' => 'utf8_bin',
                'encoding' => 'utf8',
                'after' => 'updated_at',
            ])
            ->create();
        $this->table('users_sessions', [
                'id' => false,
                'primary_key' => ['id'],
                'engine' => 'MyISAM',
                'encoding' => 'utf8',
                'collation' => 'utf8_bin',
                'comment' => '',
                'row_format' => 'DYNAMIC',
            ])
            ->addColumn('id', 'integer', [
                'null' => false,
                'limit' => MysqlAdapter::INT_REGULAR,
                'identity' => 'enable',
            ])
            ->addColumn('user_id', 'integer', [
                'null' => false,
                'limit' => MysqlAdapter::INT_REGULAR,
                'after' => 'id',
            ])
            ->addColumn('session', 'text', [
                'null' => false,
                'limit' => MysqlAdapter::TEXT_MEDIUM,
                'collation' => 'utf8_bin',
                'encoding' => 'utf8',
                'after' => 'user_id',
            ])
            ->addColumn('expired_at', 'string', [
                'null' => true,
                'default' => null,
                'limit' => 255,
                'collation' => 'utf8_bin',
                'encoding' => 'utf8',
                'after' => 'session',
            ])
            ->addColumn('created_at', 'string', [
                'null' => true,
                'default' => null,
                'limit' => 255,
                'collation' => 'utf8_bin',
                'encoding' => 'utf8',
                'after' => 'expired_at',
            ])
            ->addColumn('created_ip', 'string', [
                'null' => true,
                'default' => null,
                'limit' => 255,
                'collation' => 'utf8_bin',
                'encoding' => 'utf8',
                'after' => 'created_at',
            ])
            ->create();
    }
}
