<?php

use think\migration\Migrator;
use Phinx\Db\Adapter\MysqlAdapter;

class AuthGroup extends Migrator
{

    public function change()
    {

        $table = $this->table('auth_group', [
            'id' => false,
            'primary_key' => ['id'],
            'comment' => '分组表',
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
            ->addColumn('pid', 'integer', [
                'limit' => 10,
                'null' => false,
                'default' => 0,
                'signed' => false,
                'comment' => '父组别',
            ])
            ->addColumn('name', 'string', [
                'limit' => 100,
                'null' => false,
                'default' => '',
                'comment' => '组名',
            ])
            ->addColumn('rules', 'text', [
                'limit' => 100,
                'null' => false,
                'default' => null,
                'comment' => '规则ID',
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
                'pid' => 0,
                'name' => '超级管理员',
                'rules' => '*',
                'createtime' => time(),
                'updatetime' => time(),
                'status' => 1
            ]
        ];
        $this->insert('auth_group', $rows);
    }
}
