// 生成树
export function toTree(data, config) {
  const id = config && config.id ? config.id : 'id'
  const pid = config && config.pid ? config.pid : 'pid'
  data.forEach(function(item) {
    delete item.children
  })
  const map = {}
  data.forEach(function(item) {
    map[item[id]] = item
  })
  const val = []
  data.forEach(function(item) {
    const parent = map[item[pid]]
    if (parent) {
      (parent.children || (parent.children = [])).push(item)
    } else {
      val.push(item)
    }
  })
  return val
}
// 扁平化树
export function reverseTree(data) {
  const nData = [...data]
  const result = []
  const pushRes = function(item) {
    let children = []
    if (item.children) {
      children = item.children
      delete item.children
    }
    result.push(item)
    children.forEach(e => pushRes(e))
  }
  nData.forEach(e => pushRes(e))
  return result
}
