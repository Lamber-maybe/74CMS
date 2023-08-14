import { getConfigCache } from '@/api/configuration'
import { setConfig, removeConfig } from '@/utils/config'

const getDefaultState = () => {
  return {
    sitename: ''
  }
}

const state = getDefaultState()
const mutations = {
  RESET_CONFIG: (state) => {
    Object.assign(state, getDefaultState())
  },
  SET_CONFIG: (state, config) => {
    Object.assign(state, config)
  }
}

const actions = {
  // user login
  getConfigInfo({ commit, state }) {
    return new Promise((resolve, reject) => {
      getConfigCache().then(response => {
        const { data } = response
        commit('SET_CONFIG', data)
        setConfig(data)
        resolve()
      }).catch(error => {
        reject(error)
      })
    })
  },

  // remove token
  resetConfiginfo({ commit }) {
    return new Promise(resolve => {
      removeConfig() // must remove  token  first
      commit('RESET_CONFIG')
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

