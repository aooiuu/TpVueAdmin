import Cookies from 'js-cookie'

const TokenKey = 'Admin-Token'

export function getToken() {
  return Cookies.get(TokenKey)
}

export function setToken(token) {
  if (token) {
    return Cookies.set(TokenKey, token)
  }
  return false
}

export function removeToken() {
  return Cookies.remove(TokenKey)
}
