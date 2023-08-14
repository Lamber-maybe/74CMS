import {
  login,
  logout,
  getInfo
} from '@/api/user'
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
  removeAccess,
  removeAccessExport,
  removeAccessDelete
} from '@/utils/auth'
import {
  resetRouter
} from '@/router'

const getDefaultState = () => {
  return {
    token: getToken(),
    username: '',
    rolename: '',
    avatar: '',
    access: getAccess(),
    access_export: getAccessExport(),
    access_delete: getAccessDelete()
  }
}

const state = getDefaultState()

const mutations = {
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
        password: password,
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
        setToken(data.token)
        setAccess(data.access)
        setAccessExport(data.access_export)
        setAccessDelete(data.access_delete)
        resolve()
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
          username,
          rolename,
          avatar,
          access,
          access_export,
          access_delete
        } = data

        commit('SET_USERNAME', username)
        commit('SET_ROLENAME', rolename)
        commit('SET_AVATAR', avatar)
        commit('SET_ACCESS', access)
        commit('SET_ACCESS_EXPORT', access_export)
        commit('SET_ACCESS_DELETE', access_delete)
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
