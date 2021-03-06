<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 流年 <liu21st@gmail.com>
// +----------------------------------------------------------------------

// 应用公共文件

/**
 * 转大驼峰
 */
function camelize($uncamelizedWords, $separator = '_')
{
  $uncamelizedWords = $separator . str_replace($separator, " ", strtolower($uncamelizedWords));
  return ucwords(ltrim(str_replace(" ", "", ucwords($uncamelizedWords)), $separator));
}


/**
 * 生成随机字符串
 */
function randomStr($str = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ', $len = 6)
{
  return substr(str_shuffle(str_repeat($str, ceil($len / strlen($str)))), 0, $len);
}
