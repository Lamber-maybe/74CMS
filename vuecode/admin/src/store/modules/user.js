import {
  login,
  logout,
  getInfo,
  loginScan
} from '@/api/user'
import md5 from 'js-md5'
import {
  getToken,
  setToken,
  removeToken,
  getAccess,
  setAccess,
  getAccessExport,
  setAccessExport,
  getAccessDelete,
  setAccessDelete,
  getAccessSetService,
  setAccessSetService,
  removeAccess,
  removeAccessExport,
  removeAccessDelete,
  removeAccessSetService
} from '@/utils/auth'
import {
  resetRouter
} from '@/router'

const getDefaultState = () => {
  return {
    id: '',
    token: getToken(),
    username: '',
    rolename: '',
    avatar: '',
    access: getAccess(),
    access_export: getAccessExport(),
    access_delete: getAccessDelete(),
    access_set_service: getAccessSetService()
  }
}

const state = getDefaultState()

const mutations = {
  SET_ID(state, id) {
    state.id = id
  },
  RESET_STATE: (state) => {
    Object.assign(state, getDefaultState())
  },
  SET_TOKEN: (state, token) => {
    state.token = token
  },
  SET_USERNAME: (state, username) => {
    state.username = username
  },
  SET_ROLENAME: (state, rolename) => {
    state.rolename = rolename
  },
  SET_ACCESS: (state, access) => {
    state.access = access
  },
  SET_ACCESS_EXPORT: (state, access) => {
    state.access_export = access
  },
  SET_ACCESS_DELETE: (state, access) => {
    state.access_delete = access
  },
  SET_ACCESS_SET_SERVICE: (state, access) => {
    state.access_set_service = access
  },
  SET_AVATAR: (state, avatar) => {
    state.avatar = avatar
  }
}

const actions = {
  // user login
  login({
    commit
  }, userInfo) {
    const {
      username,
      password,
      code,
      secret_str
    } = userInfo
    return new Promise((resolve, reject) => {
      login({
        username: username.trim(),
        password: md5(password),
        code: code,
        secret_str: secret_str
      }).then(response => {
        const {
          data
        } = response
        commit('SET_TOKEN', data.token)
        commit('SET_ACCESS', data.access)
        commit('SET_ACCESS_EXPORT', data.access_export)
        commit('SET_ACCESS_DELETE', data.access_delete)
        commit('SET_ACCESS_SET_SERVICE', data.access_set_service)
        setToken(data.token)
        setAccess(data.access)
        setAccessExport(data.access_export)
        setAccessDelete(data.access_delete)
        setAccessSetService(data.access_set_service)
        resolve()
      }).catch(error => {
        reject(error)
      })
    })
  },
  scan({
    commit
  }, scan_token) {
    return new Promise((resolve, reject) => {
      loginScan({
        scan_token
      }).then(response => {
        if (response.data.pass == 1) {
          commit('SET_TOKEN', response.data.info.token)
          commit('SET_ACCESS', response.data.info.access)
          commit('SET_ACCESS_EXPORT', response.data.info.access_export)
          commit('SET_ACCESS_DELETE', response.data.info.access_delete)
          commit('SET_ACCESS_SET_SERVICE', response.data.info.access_set_service)
          setToken(response.data.info.token)
          setAccess(response.data.info.access)
          setAccessExport(response.data.info.access_export)
          setAccessDelete(response.data.info.access_delete)
          setAccessSetService(response.data.info.access_set_service)
          resolve()
        }
      }).catch(error => {
        reject(error)
      })
    })
  },

  // get user info
  getInfo({
    commit,
    state
  }) {
    return new Promise((resolve, reject) => {
      getInfo(state.token).then(response => {
        const {
          data
        } = response

        if (!data) {
          reject('Verification failed, please Login again.')
        }

        const {
          id,
          username,
          rolename,
          avatar,
          access,
          access_export,
          access_delete,
          access_set_service
        } = data
        commit('SET_ID', id)
        commit('SET_USERNAME', username)
        commit('SET_ROLENAME', rolename)
        commit('SET_AVATAR', avatar)
        commit('SET_ACCESS', access)
        commit('SET_ACCESS_EXPORT', access_export)
        commit('SET_ACCESS_DELETE', access_delete)
        commit('SET_ACCESS_SET_SERVICE', access_set_service)
        resolve(data)
      }).catch(error => {
        reject(error)
      })
    })
  },

  // user logout
  logout({
    commit,
    state
  }) {
    return new Promise((resolve, reject) => {
      logout(state.token).then(() => {
        removeToken() // must remove  token  first
        removeAccess()
        removeAccessExport()
        removeAccessDelete()
        removeAccessSetService()
        resetRouter()
        commit('RESET_STATE')
        resolve()
      }).catch(error => {
        reject(error)
      })
    })
  },

  // remove token
  resetToken({
    commit
  }) {
    return new Promise(resolve => {
      removeToken() // must remove  token  first
      removeAccess()
      removeAccessExport()
      removeAccessDelete()
      removeAccessSetService()
      commit('RESET_STATE')
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
