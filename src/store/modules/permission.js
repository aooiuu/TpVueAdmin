import { constantRoutes } from '@/router'
import { request } from '@/api/request'
import { toTree } from '@/utils/tree'

/**
 * Use meta.role to determine if the current user has permission
 * @param roles
 * @param route
 */
function hasPermission(roles, route) {
  if (route.meta && route.meta.roles) {
    return roles.some(role => route.meta.roles.includes(role))
  } else {
    return true
  }
}

/**
 * Filter asynchronous routing tables by recursion
 * @param routes asyncRoutes
 * @param roles
 */
export function filterAsyncRoutes(routes, roles) {
  const res = []
  routes.forEach(route => {
    const tmp = { ...route }
    if (hasPermission(roles, tmp)) {
      if (tmp.children) {
        tmp.children = filterAsyncRoutes(tmp.children, roles)
      }
      res.push(tmp)
    }
  })

  return res
}
// 把后端的 component 文本，转换成 组件
export function getAsyncComponent(_item) {
  const item = {}
  _item.path && (item.path = '/' + _item.path)
  _item.name && (item.name = _item.name)
  _item.meta && (item.meta = _item.meta)
  _item.redirect && (item.redirect = _item.redirect)
  _item.component && (item.component = _item.component)
  if (_item.children) {
    item.children = _item.children
    item.alwaysShow = true
  }
   // 如果有子级菜单
  let _component = 'layout'
  if (item.children) {
    item.children = item.children.map(children => getAsyncComponent(children))
  } else {
     _component = item.component
  }
  // 为了命名规范，把 `_` 路径映射到 `-`
  _component = _component.replace(/_/g, '-')
  item.component = () => import(`@/views/${_component}`)
  return item
}
// 从后端获取路由
export function getAsyncRoutes() {
  return request({
    url: 'admin/rule/asyncRoutes'
  })
}

const state = {
  routes: [],
  addRoutes: []
}

const mutations = {
  SET_ROUTES: (state, routes) => {
    state.addRoutes = routes
    state.routes = constantRoutes.concat(routes)
  }
}

const actions = {
  generateRoutes({ commit }, roles) {
    return new Promise(async(resolve, reject) => {
      let asyncRoutes = []
      try {
        const asyncRoutesTree = toTree((await getAsyncRoutes()).data)
        asyncRoutes = asyncRoutesTree.map(item => getAsyncComponent(item))
        // asyncRoutes = (await import('@/router')).asyncRoutes
        asyncRoutes.push({ path: '*', redirect: '/404', hidden: true })
      } catch (error) {
        reject(error)
      }
      // 路由转换
      let accessedRoutes
      if (roles.includes('admin')) {
        accessedRoutes = asyncRoutes || []
      } else {
        accessedRoutes = filterAsyncRoutes(asyncRoutes, roles)
      }
      commit('SET_ROUTES', accessedRoutes)
      resolve(accessedRoutes)
    })
  }
}

export default {
  namespaced: true,
  state,
  mutations,
  actions
}
