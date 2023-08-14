const getters = {
  sidebar: state => state.app.sidebar,
  device: state => state.app.device,
  token: state => state.user.token,
  avatar: state => state.user.avatar,
  name: state => state.user.username,
  rolename: state => state.user.rolename,
  access: state => state.user.access,
  sitename: state => state.config.sitename,
  addRouters: state => state.permission.addRouters
}
export default getters
