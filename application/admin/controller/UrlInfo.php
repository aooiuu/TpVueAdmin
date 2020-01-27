<?php

namespace app\admin\controller;

class UrlInfo extends Base
{
    // 不需要鉴权的方法
    protected $noNeedRight = [];
    protected $searchFields = 'title';

    public function _initialize()
    {
        parent::_initialize();
        $this->model = model('UrlInfo');
    }
    public function index()
    {
        list($where, $sort, $order, $offset, $limit) = $this->buildparams();
        $total = $this->model
            ->where($where)
            ->order($sort, $order)
            ->count();
        $rows = $this->model
            ->where($where)
            ->order($sort, $order)
            ->limit($offset, $limit)
            ->select();
        $result = [
            'total' => $total,
            'rows' => $rows,
        ];
        return $this->result($result, 0);
    }
}
