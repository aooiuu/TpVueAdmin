<h1 align="center">TpVueAdmin - PHP</h1>

![stars](https://img.shields.io/github/stars/aooiuu/TpVueAdmin) ![forks](https://img.shields.io/github/forks/aooiuu/TpVueAdmin) ![issues](https://img.shields.io/github/issues/aooiuu/TpVueAdmin) ![release](https://img.shields.io/github/release/aooiuu/TpVueAdmin)

> 基于 ThinkPhp5.0 、Vue 的前后端分离后台管理程序

## 🌐 地址

- 前端地址: [TpVueAdmin/tree/vue2](https://github.com/aooiuu/TpVueAdmin/tree/vue2)
- 后端地址: [TpVueAdmin/tree/thinkphp5.0](https://github.com/aooiuu/TpVueAdmin/tree/thinkphp5.0)

## 🎉 功能

- 基于 Auth 权限管理, 支持多角色、支持管理子级
- 前端动态可配置菜单, 根据URL自动加载页面
- 前端基于 vue-element-admin

## 🎈 预览

![1](https://user-images.githubusercontent.com/28108111/128628896-3e4ab157-abbf-4892-835e-f7d3e8a2b655.gif)

## 🛠 安装

```bash
# clone php 分支
git clone -b thinkphp5.0 git@github.com:aooiuu/TpVueAdmin.git TpVueAdminPHP

cd TpVueAdminPHP

# 安装依赖
composer

# 导入数据库
php think migrate:run

# 后台初始账号密码: admin 123456
```
