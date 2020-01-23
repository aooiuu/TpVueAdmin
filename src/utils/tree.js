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

/**
 * 生成一维数组
 * @param data toTree()
 */
export function toTreeArr(data, config) {
  const text = config && config.text ? config.text : { key: 'text', value: 'name' }
  const result = []
  /**
   *
   * @param item
   * @param index 当前children索引
   * @param total children数量
   * @param itemprefix 前缀
   * @param noItemprefix 用于过滤定级分组前缀
   */
  const pushRes = function(item, index = 0, total = 0, itemprefix = '', noItemprefix = false) {
    let children = []
    let k = '' // itemprefix
    if (item.children) {
      children = item.children
      delete item.children
    }
    if (index === total) {
      // 节点是最后一个
      item[text.key] = !noItemprefix ? itemprefix + '└' : ''
      k = !noItemprefix ? ' ' : ''
    } else {
        // 节点不是最后一个
      item[text.key] = itemprefix + '├'
      k = '│'
    }
    result.push(item)
    children.forEach((e, index) => pushRes(e, index + 1, children.length, itemprefix + k + ' '))
  }

  data.forEach((e, index) => pushRes(e, index + 1, data.length, '', data.length === 1))
  return result
}
