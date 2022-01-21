<?php

use think\migration\Migrator;

class AuthGroupAccess extends Migrator
{
    public function change()
    {

        $table = $this->table('auth_group_access', [
            'id' => false,
            'comment' => '权限分组表',
            'encoding' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci'
        ]);
        $table
            ->addColumn('uid', 'integer', [
                'limit' => 10,
                'null' => false,
                'default' => 0,
                'signed' => false,
                'comment' => '会员ID',
            ])
            ->addColumn('group_id', 'integer', [
                'limit' => 10,
                'null' => false,
                'default' => 0,
                'signed' => false,
                'comment' => '级别ID',
            ])
            ->addIndex(['uid', 'group_id'], ['unique' => true])
            ->addIndex('uid')
            ->addIndex('group_id')
            ->create();

        $rows = [
            [
                'uid' => 1,
                'group_id' => 1,
            ]
        ];
        $this->insert('auth_group_access', $rows);
    }
}
