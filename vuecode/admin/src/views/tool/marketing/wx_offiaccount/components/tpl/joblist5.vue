<template>
  <section>
    <section v-for="(item,index) in datalist.items" :key="index">
      <section style="max-width: 100%;text-align: center;margin:1em auto;">
        <section data-bcless="fadeout" data-bclessp="65%" style="border: 6px solid #feefed;" class="">
          <section class="assistant" style="display: flex;justify-content: space-between;align-items:flex-start;">
            <section style="width: 2em;height: 2em;border-left:3px solid #f8ada7;border-top:3px solid #f8ada7;margin:-3px 0px 0px -3px;" />
            <section style="width: 2em;height: 2em;border-right:3px solid #f8ada7;border-top:3px solid #f8ada7;margin:-3px -3px 0px 0px;" />
          </section>
          <section style="text-align:center;">
            <section style="margin-top: -1em; background:#f8ada7; color: #fff; font-size: 16px; letter-spacing:1.5px;line-height: 1.75em;padding: 2px 1.5em;display:inline-block">{{ item.jobname }}</section></section>
          <section style="font-size: 14px;text-align: justify;letter-spacing: 1.5px;line-height: 1.75em;padding: 0em 1.5em;margin-top:1.5em ;color: #3e3e3e;">
            <p>薪资待遇:{{ item.wage }}</p>
            <p>招聘要求:{{ item.education_text }} 、{{ item.experience_text }}</p>
            <p>岗位福利:{{ item.tag!=''?item.tag:'无' }}</p>
            <p>工作地址:{{ item.address }}</p>
            <section v-if="link=='normal'">
              <p />
              <section style="text-align: center;" align="center"><img :src="handlerNormalQrcode(item)" alt="saf.png" data-ratio="1" data-w="360" width="90" height="90" style="max-inline-size: 100%; margin: 0px; padding: 0px; box-sizing: border-box !important; outline: none 0px !important; max-width: 100%; height: 90px; width: 90px;"></section>
              <section style="text-align: center;" align="center">长按扫码申请职位</section>
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
              <section v-if="link=='miniprogram'" style="text-align: center;">&gt;&gt;<a style="text-decoration:none" :data-miniprogram-appid="item.miniprogram_appid" :data-miniprogram-path="item.miniprogram_path" data-miniprogram-nickname="" data-miniprogram-type="text" data-miniprogram-servicetype="0"><span style="color: #f8ada7;"><strong><span style="font-size: 15px;">点击申请职位</span></strong></span></a>&lt;&lt;</section>
              <section v-else style="text-align: center;">&gt;&gt;<a :href="item.jobshow_link" style="text-decoration:none"><span style="color: #f8ada7;"><strong><span style="font-size: 15px;">点击申请职位</span></strong></span></a>&lt;&lt;</section>
              <p />
            </section>
          </section>
          <section class="assistant" style="display: flex;justify-content: space-between;align-items:flex-end;">
            <section style="width: 2em;height: 2em;border-left:3px solid #f8ada7;border-bottom:3px solid #f8ada7;margin:0px 0px -3px -3px;" />
            <section style="width: 2em;height: 2em;border-right:3px solid #f8ada7;border-bottom:3px solid #f8ada7;margin:0px -3px -3px 0px;" />
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
