<?php

use think\migration\Migrator;
use Phinx\Db\Adapter\MysqlAdapter;

class Admin extends Migrator
{
    public function change()
    {

        $table = $this->table('admin', [
            'id' => false,
            'primary_key' => ['id'],
            'comment' => '管理员表',
            'encoding' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci'
        ]);
        $table
            ->addColumn('id', 'integer', [
                'limit' => 10,
                'null' => false,
                'identity' => true,
                'signed' => false,
                'comment' => 'ID',
            ])
            ->addColumn('username', 'string', [
                'limit' => 20,
                'null' => false,
                'default' => '',
                'comment' => '用户名',
            ])
            ->addColumn('nickname', 'string', [
                'limit' => 50,
                'null' => false,
                'default' => '',
                'comment' => '昵称',
            ])
            ->addColumn('password', 'string', [
                'limit' => 32,
                'null' => false,
                'default' => '',
                'comment' => '密码',
            ])
            ->addColumn('salt', 'string', [
                'limit' => 30,
                'null' => false,
                'default' => '',
                'comment' => '密码盐',
            ])
            ->addColumn('avatar', 'string', [
                'limit' => 255,
                'null' => false,
                'default' => '',
                'comment' => '头像',
            ])
            ->addColumn('logintime', 'integer', [
                'limit' => 10,
                'null' => true,
                'default' => null,
                'signed' => false,
                'comment' => '登录时间',
            ])
            ->addColumn('loginip', 'string', [
                'limit' => 50,
                'null' => false,
                'default' => '',
                'comment' => '登录IP',
            ])
            ->addColumn('createtime', 'integer', [
                'limit' => 10,
                'null' => true,
                'default' => null,
                'signed' => false,
                'comment' => '创建时间',
            ])
            ->addColumn('updatetime', 'integer', [
                'limit' => 10,
                'null' => true,
                'default' => null,
                'signed' => false,
                'comment' => '更新时间',
            ])
            ->addColumn('status', 'integer', [
                'limit' => MysqlAdapter::INT_TINY,
                'null' => false,
                'default' => 1,
                'signed' => false,
                'comment' => '状态',
            ])
            ->addIndex('status')
            ->create();

        $rows = [
            [
                'id' => 1,
                'username' => 'admin',
                'nickname' => 'admin',
                'password' => 'ce44d8f60617792ece0a3362561b4349',
                'salt' => 'A48Bv5',
                'createtime' => time(),
                'updatetime' => time(),
                'status' => 1
            ]
        ];
        $this->insert('admin', $rows);
    }
}
