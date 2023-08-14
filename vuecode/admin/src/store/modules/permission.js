import store from '@/store'
import { constantRoutes } from '@/router'
import userRouter from '@/router/modules/user'
import businessRouter from '@/router/modules/business'
import contentRouter from '@/router/modules/content'
import mobileRouter from '@/router/modules/mobile'
import statRouter from '@/router/modules/stat'
import applicationRouter from '@/router/modules/application'
import toolRouter from '@/router/modules/tool'
import systemRouter from '@/router/modules/system'

const state = {
  routers: constantRoutes,
  addRouters: []
}

const mutations = {
  SET_ROUTERS: (state, routers) => {
    state.addRouters = routers
    state.routers = constantRoutes.concat(routers)
  }
}

function routeTree (list, accessArr) {
  const newlist = [...list]
  for (let i = 0; i < newlist.length; i++) {
    const res = Object.assign({}, newlist[i])
    if (res.meta.access !== undefined && !accessArr.includes(res.meta.access)) {
      newlist.splice(i, 1)
      i--
    } else {
      if (res.children && res.children.length > 0) {
        res.children = routeTree(res.children, accessArr)
        newlist[i] = res
      }
    }
  }
  return newlist
}
const actions = {
  GenerateRoutes ({ commit }, data) {
    return new Promise((resolve) => {
      let menuList = [
        {
          ...userRouter
        },
        {
          ...businessRouter
        },
        {
          ...contentRouter
        },
        {
          ...mobileRouter
        },
        {
          ...statRouter
        },
        {
          ...toolRouter
        },
        {
          ...applicationRouter
        },
        {
          ...systemRouter
        }
      ]
      if (store.state.user.access != 'all') {
        const accessArr = store.state.user.access.harfCheckedKeys.concat(store.state.user.access.checkedKeys)
        if (accessArr !== undefined) {
          menuList = routeTree(menuList, accessArr)
        }
      }
      menuList.push({
        path: '*',
        redirect: '/404',
        meta: {},
        hidden: true
      })
      commit('SET_ROUTERS', menuList)
      resolve()
    })
  }
}

export default {
  namespaced: true,
  state,
  mutations,
  actions
}
