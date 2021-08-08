-- --------------------------------------------------------
-- 主机:                           127.0.0.1
-- 服务器版本:                        5.7.33 - MySQL Community Server (GPL)
-- 服务器操作系统:                      Win64
-- HeidiSQL 版本:                  11.2.0.6213
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

-- 正在导出表  tp_vue_admin.admin 的数据：~4 rows (大约)
DELETE FROM `admin`;
/*!40000 ALTER TABLE `admin` DISABLE KEYS */;
INSERT INTO `admin` (`id`, `username`, `nickname`, `password`, `salt`, `avatar`, `logintime`, `loginip`, `createtime`, `updatetime`, `token`, `status`) VALUES
	(1, 'admin', 'aaa', 'ce44d8f60617792ece0a3362561b4349', 'A48Bv5', '', NULL, NULL, 1579234824, 1628397913, '', 1),
	(37, 'user1', 'user1', 'ce44d8f60617792ece0a3362561b4349', 'A48Bv5', '', NULL, NULL, NULL, NULL, '', 1),
	(47, 'user2', 'user2', 'ce44d8f60617792ece0a3362561b4349', 'A48Bv5', '', NULL, NULL, 1579766959, 1579766959, '', 1),
	(48, 'user3', 'user3', 'ce44d8f60617792ece0a3362561b4349', 'A48Bv5', '', NULL, NULL, 1579766974, 1628394317, '', 1);
/*!40000 ALTER TABLE `admin` ENABLE KEYS */;

-- 正在导出表  tp_vue_admin.auth_group 的数据：~8 rows (大约)
DELETE FROM `auth_group`;
/*!40000 ALTER TABLE `auth_group` DISABLE KEYS */;
INSERT INTO `auth_group` (`id`, `pid`, `name`, `rules`, `createtime`, `updatetime`, `status`) VALUES
	(6, 0, '超级管理员', '*', NULL, 1580102057, 1),
	(7, 6, '测试管理员', '125,133,134,135,136,137,138', NULL, 1579686353, 1),
	(10, 7, '小小管理员', '133,134,135,136,137,138', 1579517241, 1579517241, 1),
	(11, 10, '小小小管理员', '134,138', 1579518026, 1579518026, 1),
	(12, 11, '小小小小管理员', '134', 1579518043, 1579518043, 1),
	(13, 6, '管理员B', '125,127,128,129,130,131,133,134,135,136,137,138,140,141,142,143,144', 1579756522, 1628415873, 1),
	(14, 12, '小小小小小管理员', '134', 1579760774, 1579760774, 1);
/*!40000 ALTER TABLE `auth_group` ENABLE KEYS */;

-- 正在导出表  tp_vue_admin.auth_group_access 的数据：~5 rows (大约)
DELETE FROM `auth_group_access`;
/*!40000 ALTER TABLE `auth_group_access` DISABLE KEYS */;
INSERT INTO `auth_group_access` (`uid`, `group_id`) VALUES
	(1, 6),
	(37, 7),
	(37, 11),
	(47, 10),
	(48, 14);
/*!40000 ALTER TABLE `auth_group_access` ENABLE KEYS */;

-- 正在导出表  tp_vue_admin.auth_rule 的数据：~21 rows (大约)
DELETE FROM `auth_rule`;
/*!40000 ALTER TABLE `auth_rule` DISABLE KEYS */;
INSERT INTO `auth_rule` (`id`, `type`, `pid`, `name`, `title`, `icon`, `condition`, `remark`, `ismenu`, `createtime`, `updatetime`, `weigh`, `status`) VALUES
	(125, 'menu', 0, 'admin', '权限管理', 'tree', '', '权限管理备注', 1, 1970, 1628319078, 0, 1),
	(127, 'menu', 125, 'admin/rule', '菜单规则', '', '', '', 1, 1579418298, 1628319138, 0, 1),
	(128, 'menu', 127, 'admin/rule/index', '查看', '', '', '', 0, 1970, 1579679593, 0, 1),
	(129, 'menu', 127, 'admin/rule/edit', '编辑', '', '', '', 0, 1579420108, 1579420108, 0, 1),
	(130, 'menu', 127, 'admin/rule/add', '添加', '', '', '', 0, 1579420117, 1579420117, 0, 1),
	(131, 'menu', 127, 'admin/rule/del', '删除', '', '', '', 0, 1579420123, 1579420123, 0, 1),
	(133, 'menu', 125, 'admin/auth_group', '角色组', '', '', '', 1, 1579423395, 1579423395, 0, 1),
	(134, 'menu', 133, 'admin/auth_group/index', '查看', '', '', '', 0, 1579423412, 1579423412, 0, 1),
	(135, 'menu', 133, 'admin/auth_group/add', '添加', '', '', '', 0, 1579423424, 1579423424, 0, 1),
	(136, 'menu', 133, 'admin/auth_group/edit', '编辑', '', '', '', 0, 1579423430, 1579423430, 0, 1),
	(137, 'menu', 133, 'admin/auth_group/del', '删除', '', '', '', 0, 1579423436, 1579423436, 0, 1),
	(138, 'menu', 133, 'admin/auth_group/roletree', '路由', '', '', '', 0, 1579423436, 1579423436, 0, 1),
	(140, 'menu', 125, 'admin/admin', '管理员管理', '', '', '', 1, 1579519276, 1579519276, 0, 1),
	(141, 'menu', 140, 'admin/admin/index', '查看', '', '', '', 0, 1579519321, 1579519321, 0, 1),
	(142, 'menu', 140, 'admin/admin/edit', '编辑', '', '', '', 0, 1579519327, 1579519327, 0, 1),
	(143, 'menu', 140, 'admin/admin/add', '添加', '', '', '', 0, 1579519335, 1579519335, 0, 1),
	(144, 'menu', 140, 'admin/admin/del', '删除', '', '', '', 0, 1579519340, 1579519340, 0, 1),
	(147, 'menu', 0, 'test', '测试菜单', '', '', '', 1, 1579845079, 1579845207, 0, 1),
	(148, 'menu', 147, 'test/test1', '测试菜单1', '', '', '', 1, 1579845179, 1579845179, 0, 1),
	(149, 'menu', 148, 'url-info/url', '测试菜单2', '', '', '', 1, 1579846107, 1580101897, 0, 1),
	(150, 'menu', 149, 'admin/url_info/index', '查看', '', '', '', 0, 1580102049, 1580102049, 0, 1);
/*!40000 ALTER TABLE `auth_rule` ENABLE KEYS */;

/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
