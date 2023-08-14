import router from '@/router'
import { Toast } from 'vant'
export function handlerHttpError (res) {
  switch (res.code) {
    case 50001: // 非法token
      if (res.message) {
        Toast.fail(res.message)
      }
      router.push('/login')
      break
    default:
      if (res.message) {
        Toast.fail(res.message)
      }
      break
  }
}
