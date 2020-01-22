<?php

namespace app\admin\controller;

class Admin extends Base
{

    /**
     * @var \app\common\model\Admin
     */
    protected $model = null;
    protected $noNeedRight = [];
    public function _initialize()
    {
        parent::_initialize();
        $this->model = model('Admin');
    }

    public function index()
    {
        $authGroup = model('AuthGroup')->column('name', 'id');
        $authGroupAccess = $this->model->with('authGroupAccess')->select();
        $result = [
            'total' => $this->model->count(),
            'rows' => $authGroupAccess,
        ];

        return $this->result($result, 0);
    }

    /**
     * 添加管理员
     * @param string username
     * @param string password
     */
    public function add()
    {
        $params = $this->request->param();

        $result = $this->model->validate('Admin.add')->save($params);
        if ($result === false) {
            return $this->result($result, 2, $this->model->getError());
        }
        return $this->result($params, 0, $result);

    }

    public function del()
    {
        $id = $this->request->param('id');
        $result = $this->model->where('id', '>', $id)->delete();

    }
}
