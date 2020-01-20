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
