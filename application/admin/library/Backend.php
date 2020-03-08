<?php

/**
 * CURD
 */

namespace app\admin\library;

trait Backend
{
  /**
   * 查看
   */
  public function index()
  {
    list($where, $sort, $order, $offset, $limit) = $this->buildparams();
    $total = $this->model
      ->where($where)
      ->order($sort, $order)
      ->count();
    $list = $this->model
      ->where($where)
      ->order($sort, $order)
      ->limit($offset, $limit)
      ->select();
    $list = collection($list)->toArray();
    $result = array("total" => $total, "rows" => $list);
    $this->result($result, 0);
  }
  /**
   * 回收站
   */
  public function recyclebin()
  {
    $this->result([], 0);
  }
  /**
   * 添加
   */
  public function add()
  {
    $this->result([], 0);
  }
  /**
   * 编辑
   */
  public function edit()
  {
    $this->result([], 0);
  }
  /**
   * 删除
   */
  public function del()
  {
    $this->result([], 0);
  }
  /**
   * 真实删除
   */
  public function destroy()
  {
    $this->result([], 0);
  }
  /**
   * 还原
   */
  public function derestore()
  {
    $this->result([], 0);
  }
}
