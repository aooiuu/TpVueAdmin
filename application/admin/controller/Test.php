<?php

namespace app\admin\controller;

class Test extends Base
{
  protected $model = null;

  public function _initialize()
  {
    parent::_initialize();
    $this->model = model('app\common\model\Admin');
  }
}
