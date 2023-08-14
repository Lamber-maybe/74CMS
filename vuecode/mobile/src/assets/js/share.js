import wx from 'weixin-js-sdk'
import { isWeiXin, strReplace } from '@/utils/index.js'
import http from '@/utils/http'
import api from '@/api'
import store from '@/store'
function initshare (config, data) {
  let appId = config.appId
  let timestamp = config.timestamp
  let nonceStr = config.nonceStr
  let signature = config.signature
  let title = data.title
  let desc = data.desc
  let link = data.link
  let imgUrl = data.imgUrl
  wx.config({
    debug: false, // 开启调试模式,调用的所有api的返回值会在客户端alert出来，若要查看传入的参数，可以在pc端打开，参数信息会通过log打出，仅在pc端时才会打印。
    appId, // 必填，公众号的唯一标识
    timestamp, // 必填，生成签名的时间戳
    nonceStr, // 必填，生成签名的随机串
    signature, // 必填，签名
    jsApiList: ['updateAppMessageShareData', 'updateTimelineShareData'] // 必填，需要使用的JS接口列表
  })
  wx.ready(function () {
    // 自定义“分享给朋友”及“分享到QQ”按钮的分享内容
    wx.updateAppMessageShareData({
      title, // 分享标题
      desc, // 分享描述
      link, // 分享链接，该链接域名或路径必须与当前页面对应的公众号JS安全域名一致
      imgUrl, // 分享图标
      success: function () {
        // 设置成功
      }
    })
    // 自定义“分享到朋友圈”及“分享到QQ空间”按钮的分享内容
    wx.ready(function () { // 需在用户可能点击分享按钮前就先调用
      wx.updateTimelineShareData({
        title, // 分享标题
        link, // 分享链接，该链接域名或路径必须与当前页面对应的公众号JS安全域名一致
        imgUrl, // 分享图标
        success: function () {
          // 设置成功
        }
      })
    })
    wx.error(function (res) {
      // config信息验证失败会执行error函数，如签名过期导致验证失败，具体错误信息可以打开config的debug模式查看，也可以在返回的res参数中查看，对于SPA可以在这里更新签名。
    })
  })
}
function wxshare (wechatShareInfo, page, url) {
  if (!isWeiXin()) {
    return false
  }
  http
    .get(api.wechat_share_config, { url: url, alias: page })
    .then(res => {
      let shareConfig = {
        appId: res.data.config.appId,
        nonceStr: res.data.config.nonceStr,
        timestamp: res.data.config.timestamp,
        signature: res.data.config.signature
      }
      let shareData = {
        title: '',
        desc: '',
        link: url,
        imgUrl: ''
      }
      if (res.data.data !== null) {
        shareData = {
          title: res.data.data.content,
          desc: res.data.data.explain,
          link: url,
          imgUrl: ''
        }
        let paramsArr = JSON.parse(res.data.data.params)
        shareData.title = shareData.title.replace(/{sitename}/g, store.state.config.sitename)
        shareData.title = shareData.title.replace(/{sitedomain}/g, store.state.config.sitedomain + store.state.config.sitedir)
        shareData.desc = shareData.desc.replace(/{sitename}/g, store.state.config.sitename)
        shareData.desc = shareData.desc.replace(/{sitedomain}/g, store.state.config.sitedomain + store.state.config.sitedir)
        paramsArr.forEach(element => {
          if (wechatShareInfo[element.value] !== undefined) {
            shareData.title = strReplace(shareData.title, '{' + element.value + '}', wechatShareInfo[element.value])
            shareData.desc = strReplace(shareData.desc, '{' + element.value + '}', wechatShareInfo[element.value])
          }
        })
        if (res.data.data.img === 'self') {
          shareData.imgUrl = wechatShareInfo.imgUrl
        } else {
          shareData.imgUrl = store.state.config.square_logo
        }
      }
      console.log(shareData)
      initshare(shareConfig, shareData)
    })
    .catch(() => { })
}
export default wxshare
