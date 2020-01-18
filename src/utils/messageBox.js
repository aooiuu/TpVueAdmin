import { MessageBox, Message } from 'element-ui'

export const confirm = async function(title) {
  let result = false
  try {
    await MessageBox.confirm(title, '提示', {
      confirmButtonText: '确定',
      cancelButtonText: '取消',
      type: 'warning'
    })
    result = true
  } catch (error) {
    Message({
      type: 'info',
      message: '已取消删除'
    })
  }
  return result
}
