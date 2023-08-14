<template>
  <section>
    <section v-for="(item,index) in datalist.items" :key="index" style="margin:10px 0px;">
      <section style="border:dashed 2px #ff6e55;padding:15px 15px;border-radius:10px" class="">
        <p style="text-align: left; letter-spacing: 2px; transform: rotateZ(0deg);" align="left"><span style="font-size: 16px;"><strong><span style="letter-spacing: 2px; color: #ff6e55; transform: rotateZ(0deg);">{{ item.fullname }}</span></strong><span style="letter-spacing: 2px; color: #FF6E55; transform: rotateZ(0deg); font-size: 14px;">(<span style="transform: rotateZ(0deg); color: #FF6E55; font-family: 微软雅黑, &quot;Microsoft YaHei&quot;, Arial, sans-serif; letter-spacing: 2px; text-align: left; caret-color: #FF0000; display: inline !important;">{{ item.sex_text }}，{{ item.age_text }}岁，{{ item.education_text }}，{{ item.experience_text }}</span>)</span></span></p>
        <p style="text-align:left;letter-spacing: 2px;transform: rotatez(0deg)" align="left"><span style="font-size: 15px; letter-spacing: 2px; color: #ff6e55;transform: rotatez(0deg);">想找 <strong>{{ item.intention_jobs }}</strong> 工作</span></p>
        <p style="text-align:left;letter-spacing: 2px;transform: rotatez(0deg)" align="left"><span style="font-size: 15px; letter-spacing: 2px; color: #ff6e55;transform: rotatez(0deg);">想在 <strong>{{ item.intention_district }}</strong> 工作</span></p>
        <section v-if="link=='normal'">
          <p />
          <section style="text-align:center;"><img :src="handlerNormalQrcode(item)" alt="saf.png" data-ratio="1" data-w="360" width="108" height="108" style="width: 90px; height: 90px;"></section>
          <section style="caret-color: red;text-align:center; font-size:15px;">长按识别查看简历</section>
          <p />
        </section>
        <section v-else>
          <p />
          <section v-if="link=='miniprogram'" style="text-align:center; letter-spacing: 2px; transform: rotateZ(0deg); color: #ff6e55; font-weight: bold;font-size:15px;" align="center"><a style="font-size: 15px; font-weight:bold; text-decoration:none;color:#ff6e55" :data-miniprogram-appid="item.miniprogram_appid" :data-miniprogram-path="item.miniprogram_path" data-miniprogram-nickname="" data-miniprogram-type="text" data-miniprogram-servicetype="0">&gt;&gt;点击了解更多信息&lt;&lt;</a></section>
          <section v-else-if="link=='weixin'">
            <p />
            <section align="center"><img :src="item.wxqrcode" data-ratio="1" data-w="360" width="99" height="99" style="width: 99px; height: 99px;"></section>
            <section style="text-align: center;font-size:14px;" align="center">长按识别了解更多信息</section>
            <p />
          </section>
          <section v-else style="text-align:center; letter-spacing: 2px; transform: rotateZ(0deg); color: #ff6e55; font-weight: bold;font-size:15px;" align="center"><a :href="item.resumeshow_link" style="font-size: 15px; font-weight:bold; text-decoration:none;color:#ff6e55">&gt;&gt;点击了解更多信息&lt;&lt;</a></section>
        </section>
        <p />
      </section>
    </section>
    <p style="width:2em;margin-left:auto;margin-top: -38px;margin-right:3px;">
      <img :src="url" alt="" style="width: 2em; height: auto;" data-ratio="1.125" data-w="48">
    </p>
  </section>
</template>

<script>
import apiArr from '@/api'
export default {
  props: ['datalist', 'link'],
  data() {
    return {
      url: this.$store.state.config.sitedomain + this.$store.state.config.sitedir + 'upload/resource/marketing/resumelist5-1.png'
    }
  },
  created() {
  },
  methods: {
    handlerNormalQrcode(item){
      return window.global.RequestBaseUrl + apiArr.showQrcode + '?url=' + item.resumeshow_link
    }
  }
}
</script>
