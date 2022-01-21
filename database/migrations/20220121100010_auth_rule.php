<?php

use think\migration\Migrator;
use Phinx\Db\Adapter\MysqlAdapter;

class AuthRule extends Migrator
{

    public function change()
    {

        $table = $this->table('auth_rule', [
            'id' => false,
            'primary_key' => ['id'],
            'comment' => '节点表',
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
            ->addColumn('type', MysqlAdapter::PHINX_TYPE_ENUM, [
                'values' => 'menu,file',
                'limit' => 10,
                'null' => false,
                'default' => 'file',
                'comment' => 'menu为菜单,file为权限节点',
            ])
            ->addColumn('pid', 'integer', [
                'limit' => 10,
                'null' => false,
                'default' => 0,
                'signed' => false,
                'comment' => '父ID',
            ])
            ->addColumn('name', 'string', [
                'limit' => 100,
                'null' => false,
                'default' => '',
                'comment' => '规则名称',
            ])
            ->addColumn('title', 'string', [
                'limit' => 50,
                'null' => false,
                'default' => '',
                'comment' => '规则名称',
            ])
            ->addColumn('icon', 'string', [
                'limit' => 50,
                'null' => false,
                'default' => '',
                'comment' => '图标',
            ])
            ->addColumn('condition', 'string', [
                'limit' => 255,
                'null' => false,
                'default' => '',
                'comment' => '条件',
            ])
            ->addColumn('remark', 'string', [
                'limit' => 255,
                'null' => false,
                'default' => '',
                'comment' => '备注',
            ])
            ->addColumn('ismenu', 'integer', [
                'limit' => MysqlAdapter::INT_TINY,
                'null' => false,
                'default' => 0,
                'signed' => false,
                'comment' => '是否为菜单',
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
            ->addColumn('weigh', 'integer', [
                'limit' => 10,
                'null' => false,
                'default' => 0,
                'signed' => false,
                'comment' => '权重',
            ])
            ->addColumn('status', 'integer', [
                'limit' => MysqlAdapter::INT_TINY,
                'null' => false,
                'default' => 1,
                'signed' => false,
                'comment' => '状态',
            ])
            ->addIndex('pid')
            ->addIndex('weigh')
            ->addIndex('name', ['unique' => true])
            ->create();

        $time = time();
        $this->execute("INSERT INTO `auth_rule` (`id`, `type`, `pid`, `name`, `title`, `icon`, `condition`, `remark`, `ismenu`, `createtime`, `updatetime`, `weigh`, `status`) VALUES
        (1, 'menu', 0, 'admin', '权限管理', 'tree', '', '权限管理备注', 1, $time, $time, 0, 1),
        (2, 'menu', 1, 'admin/rule', '菜单规则', '', '', '', 1, $time, $time, 0, 1),
        (3, 'menu', 2, 'admin/rule/index', '查看', '', '', '', 0, $time, $time, 0, 1),
        (4, 'menu', 2, 'admin/rule/edit', '编辑', '', '', '', 0, $time, $time, 0, 1),
        (5, 'menu', 2, 'admin/rule/add', '添加', '', '', '', 0, $time, $time, 0, 1),
        (6, 'menu', 2, 'admin/rule/del', '删除', '', '', '', 0, $time, $time, 0, 1),
        (7, 'menu', 1, 'admin/auth_group', '角色组', '', '', '', 1, $time, $time, 0, 1),
        (8, 'menu', 7, 'admin/auth_group/index', '查看', '', '', '', 0, $time, $time, 0, 1),
        (9, 'menu', 7, 'admin/auth_group/add', '添加', '', '', '', 0, $time, $time, 0, 1),
        (10, 'menu', 7, 'admin/auth_group/edit', '编辑', '', '', '', 0, $time, $time, 0, 1),
        (11, 'menu', 7, 'admin/auth_group/del', '删除', '', '', '', 0, $time, $time, 0, 1),
        (12, 'menu', 7, 'admin/auth_group/roletree', '路由', '', '', '', 0, $time, $time, 0, 1),
        (13, 'menu', 1, 'admin/admin', '管理员管理', '', '', '', 1, $time, $time, 0, 1),
        (14, 'menu', 13, 'admin/admin/index', '查看', '', '', '', 0, $time, $time, 0, 1),
        (15, 'menu', 13, 'admin/admin/edit', '编辑', '', '', '', 0, $time, $time, 0, 1),
        (16, 'menu', 13, 'admin/admin/add', '添加', '', '', '', 0, $time, $time, 0, 1),
        (17, 'menu', 13, 'admin/admin/del', '删除', '', '', '', 0, $time, $time, 0, 1);");
    }
}
