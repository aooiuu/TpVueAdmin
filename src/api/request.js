import { default as baseRequest } from '@/utils/request'

export const request = function(param = {}) {
  return baseRequest(param)
}
