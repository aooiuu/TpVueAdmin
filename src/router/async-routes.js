import Layout from '@/layout'

/**
 * asyncRoutes
 * the routes that need to be dynamically loaded based on user roles
 */
export const asyncRoutes = [
  {
    path: '/auth',
    name: 'auth',
    meta: { title: '权限管理' },
    redirect: { name: 'general-config' },
    component: Layout,
    children: [
      {
        path: 'admin',
        name: 'auth_admin',
        component: () => import('@/views/auth/admin'),
        meta: { title: '管理员管理' }
      },
      {
        path: 'group',
        name: 'auth_group',
        component: () => import('@/views/auth/group'),
        meta: { title: '角色组' }
      },
      {
        path: 'rule',
        name: 'auth_rule',
        component: () => import('@/views/auth/rule'),
        meta: { title: '菜单规则' }
      }
    ]
  },
  // 404 page must be placed at the end !!!
  { path: '*', redirect: '/404', hidden: true }
]
