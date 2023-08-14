import { Message } from 'element-ui'
import router from '@/router'
import store from '@/store'

export function checkRoleAuth(routePath, errMsg = '您的权限不足，请联系超级管理员。', param = {}) {
  const routeList = store.state.permission.routers
  const isAllow = routeTree(routeList, routePath)

  if (isAllow && isAllow.isAllow) {
    if (Object.keys(param).length > 0){
      for (const key in param){
        localStorage.setItem(key, param[key])
      }
    }
    router.push(routePath)
  } else {
    Message.error(errMsg)
  }
}

function routeTree(list, routePath) {
  const newList = [...list]

  for (const element of newList) {
    const menuItem = element
    const menuPath = menuItem.path
    // 路由相等时返回
    if (menuPath === routePath) {
      return { isAllow: true }
    }

    // 如果已经找到，中断递归和返回数据
    if (menuItem.children && menuItem.children.length) {
      // 再次递归时层级加1
      const targetMenu = routeTree(menuItem.children, routePath)
      // 判断是否已经匹配到，匹配到中断循环和递归并返回
      if (targetMenu && targetMenu.isAllow) return { isAllow: true }
    }
  }
}
