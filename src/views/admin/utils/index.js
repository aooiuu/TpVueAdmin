import { toTree, toTreeArr } from '@/utils/tree'
import { request } from '@/api/request'

export function getRules(id) {
  return request({
    url: 'admin/auth_group/roletree',
    method: 'GET',
    params: {
      id
    }
  })
}

export function buildRulesTree(data) {
  const newData = data.map(item => ({ ...item, ...{
    label: item.title || item.text
  }}))
  return toTree(newData, {
    id: 'id',
    pid: 'pid'
  })
}

export function buildPidTreeChildren(data) {
  if (data instanceof Array) {
    data.splice(0, 0, {
      value: data[0].pid || 0,
      label: '无'
    })
    data = data.map(item => buildPidTreeChildren(item))
  }
  if (data.children) {
    data.children = buildPidTreeChildren(data.children)
  }
  return data
}

export function buildGroupPidTree(data) {
  const newList = data.map(item => ({
    ...{
      value: item.id,
      label: item.name
    },
    ...item
  }))
  return toTreeArr(toTree(newList, {
    id: 'id',
    pid: 'pid'
  }))
}

export function buildRulePidTree(data) {
  const newList = data.map(item => ({
    ...{
      value: item.id,
      label: item.title
    },
    ...item
  }))
  return toTreeArr(toTree(newList, {
    id: 'id',
    pid: 'pid'
  }))
}
