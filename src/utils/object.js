/**
 *
 * @param {Object} obj
 * @param {Array} keys
 */
 export function filterObj(obj, keys) {
  const result = {}
  Object
  .keys(obj)
  .filter(e => keys.includes(e))
  .forEach(e => { result[e] = obj[e] })
  return result
}
