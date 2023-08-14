<template>
  <section>
    <section v-for="(item,index) in datalist.items" :key="index" data-tools="74cmszw2">
      <section style="padding:0px 0px 1em 0px;margin:1em 0px;">
        <section style="margin-bottom: -39px;margin-left:5px;transform: rotate(0deg);-webkit-transform: rotate(0deg);-moz-transform: rotate(0deg);-o-transform: rotate(0deg);">
          <section style="width:58px;height:58px;margin:20px 0px;background-color:#fefefe;border-radius:100%;margin-bottom:-35px;margin-left:-4px;" />
          <section style="margin-bottom:-48px;margin-left:4px; width:56px; height:28px; background-color:#fefefe;border-radius:30px 30px 0 0;border: 2px dashed #2dd489;border-bottom: none;transform: rotate(140deg);-webkit-transform: rotate(140deg);-moz-transform: rotate(140deg);-o-transform: rotate(140deg);" />
          <section style="width:45px;height:45px;background-color:#2dd489;border-radius:100%;font-size: 26px;color: #fff;text-align: center;line-height: 45px;transform: rotate(0deg);-webkit-transform: rotate(0deg);-moz-transform: rotate(0deg);-o-transform: rotate(0deg);">聘</section>
        </section>
        <section style="padding-left:0.5em;">
          <section style="border: 2px dashed #2dd489;border-radius:6px ;padding:0px 1em 1em 1em;">
            <section style="font-size: 18px;padding:0.7em 0px 20px 2.6em;font-weight: bold;"><strong>{{ item.jobname }}</strong></section>
            <section data-autoskip="1" style="font-size: 14px;text-align: justify;letter-spacing:1.5px;line-height: 1.75em;">
              <p style="white-space: pre-line;" v-html="item.content" />
              <p>工作地点：{{ item.address }}</p>
              <section v-if="link=='normal'">
                <p />
                <section style="text-align:center;" align="center"><img :src="handlerNormalQrcode(item)" alt="saf.png" data-ratio="1" data-w="360" width="99" height="99" style="max-inline-size: 100%; margin: 0px; padding: 0px; box-sizing: border-box !important; outline: none 0px !important; max-width: 100%; height: 90px; width: 90px;"></section>
                <section style="text-align:center;" align="center">长按识别获取联系方式</section>
                <p />
              </section>
              <section v-else-if="link=='weixin'">
                <p />
                <section align="center"><img :src="item.wxqrcode" data-ratio="1" data-w="360" width="99" height="99" style="width: 99px; height: 99px;"></section>
                <section style="text-align: center;font-size:14px;" align="center">长按识别了解更多信息</section>
                <p />
              </section>
              <section v-else>
                <p />
                <section v-if="link=='miniprogram'" style="text-align: center;">&gt;&gt;<a style="text-decoration:none" :data-miniprogram-appid="item.miniprogram_appid" :data-miniprogram-path="item.miniprogram_path" data-miniprogram-nickname="" data-miniprogram-type="text" data-miniprogram-servicetype="0"><span style="color: #2dd489;"><strong><span style="font-size: 15px;">点击申请职位</span></strong></span></a>&lt;&lt;</section>
                <section v-else style="text-align: center;">&gt;&gt;<a :href="item.jobshow_link" style="text-decoration:none;color: #2dd489;font-size: 15px;font-weight: bold;margin:0 auto">点击申请职位</a>&lt;&lt;</section>
                <p />
              </section>
            </section>
          </section>
        </section>
      </section>
    </section>
  </section>
</template>

<script>
import apiArr from '@/api'
export default {
  props: ['datalist', 'link'],
  data() {
    return {
    }
  },
  created() {
  },
  methods: {
    handlerNormalQrcode(item){
      return window.global.RequestBaseUrl + apiArr.showQrcode + '?url=' + item.jobshow_link
    }
  }
}
</script>
