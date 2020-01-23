import axios from 'axios'
import { MessageBox, Message } from 'element-ui'
import store from '@/store'
import { getToken } from '@/utils/auth'

// create an axios instance
const service = axios.create({
  baseURL: process.env.VUE_APP_BASE_API, // url = base url + request url
  // withCredentials: true, // send cookies when cross-domain requests
  timeout: 5000 // request timeout
})

// request interceptor
service.interceptors.request.use(
  config => {
    // do something before request is sent

    if (store.getters.token) {
      // let each request carry token
      // ['X-Token'] is a custom headers key
      // please modify it according to the actual situation
      config.headers['X-Token'] = getToken()
    }
    return config
  },
  error => {
    // do something with request error
    console.log(error) // for debug
    return Promise.reject(error)
  }
)

// response interceptor
service.interceptors.response.use(
  /**
   * If you want to get http information such as headers or status
   * Please return  response => response
  */

  /**
   * Determine the request status by custom code
   * Here is just an example
   * You can also judge the status by HTTP Status Code
   */
  response => {
    console.table(response.data ? response.data : response)
    const res = response.data
    // TODO: 登录失效状态码
    if (res.code === 202 && !response.request.responseURL.includes('logout')) {
      MessageBox.confirm('登录已失效.请重新登录', {
        confirmButtonText: '重新登录',
        cancelButtonText: '取消',
        type: 'warning'
      }).then(() => {
        store.dispatch('user/resetToken').then(() => {
          location.reload()
        })
      })
      return Promise.resolve(new Error(res.message || 'Error'))
    } else if (res.code === 203) {
      Message({
        message: res.msg || 'Error',
        type: 'error',
        duration: 3 * 1000
      })
    } else {
      return res
    }
  },
  error => {
    console.log('err:' + error.response) // for debug
    let message = '数据异常'
    if (typeof error.response !== 'undefined' && typeof error.response.status !== 'undefined') {
      switch (error.response.status) {
        case 422:
          message = '数据异常, 错误码: ' + error.response.data.msg
          break
        case 404:
        default:
          message = '数据异常, 错误码: ' + error.response.status
          break
      }
    }
    Message({
      message: message,
      type: 'error',
      duration: 3 * 1000
    })
    return Promise.reject(error)
  }
)

export default service
