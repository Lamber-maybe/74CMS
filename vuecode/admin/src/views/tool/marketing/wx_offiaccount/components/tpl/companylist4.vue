<template>
  <section>
    <section v-for="(item,index) in datalist.items" :key="index" style="max-width:100%;margin-bottom:25px;">
      <section style="margin:10px auto;text-align: center;">
        <section style="display:inline-block;">
          <!-- https://ae01.alicdn.com/kf/H1dedb4ad353b4f048d39e48306e50802J.png -->
          <section :style="`background-repeat:no-repeat;background-size:4px;background-position:left;padding-left:4px;`">
            <section data-brushtype="text" style="background-color:#f4f6ff;color:#333;letter-spacing:1.5px;font-size: 16px;text-align: center;padding:5px 1em;font-weight: bold;"><section style="font-size: 18px;">{{ item.companyname }}</section></section>
          </section>
        </section>
      </section>
      <section style="margin:10px auto;">
        <section class="assistant" style="width:120px;margin:0px auto;transform: rotate(0deg);-webkit-transform: rotate(0deg);-moz-transform: rotate(0deg);-o-transform: rotate(0deg);">
          <!-- https://ae01.alicdn.com/kf/H0b80cd6f66ab4d7e9577f923c6f60023m.png -->
          <section><img class="assistant" style="width:100%;display:block;" :src="url" data-w="236" data-width="100%"></section>
        </section>
        <section style="margin-top:-17px;box-shadow:2px 2px 0px #e4e4e4;background-color:#fcfdff;overflow:hidden;color:#333;padding:5px;">
          <section style="padding:1em;border:2px dashed #3859fe;">
            <section v-for="(jobitem,ind) in item.joblist" :key="ind">
              <section v-if="link=='miniprogram'"><a style="color:#ffa900;text-decoration:none;" :data-miniprogram-appid="jobitem.miniprogram_appid" :data-miniprogram-path="jobitem.miniprogram_path" data-miniprogram-nickname="" data-miniprogram-type="text" data-miniprogram-servicetype="0"><p style="text-align:justify;font-size: 14px;letter-spacing: 1.5px;line-height: 1.75em;color: #333;" align="justify"><span style="font-size: 16px;"><strong>{{ jobitem.jobname }}</strong></span>&nbsp; {{ jobitem.wage }}</p><p style="text-align:justify;font-size: 14px;letter-spacing: 1.5px;line-height: 1.75em;color: #333;" align="justify">招{{ jobitem.amount }} | {{ jobitem.education_text }} | {{ jobitem.experience_text }}</p></a></section>
              <section v-else><a style="color:#ffa900;text-decoration:none;" :href="jobitem.jobshow_link"><p style="text-align:justify;font-size: 14px;letter-spacing: 1.5px;line-height: 1.75em;color: #333;" align="justify"><span style="font-size: 16px;"><strong>{{ jobitem.jobname }}</strong></span>&nbsp;{{ jobitem.wage }}</p><p style="text-align:justify;font-size: 14px;letter-spacing: 1.5px;line-height: 1.75em;color: #333;" align="justify">招{{ jobitem.amount }} | {{ jobitem.education_text }} | {{ jobitem.experience_text }}</p></a></section>
              <hr>
            </section>
            <p />
            <p style="text-align:justify;font-size: 14px;letter-spacing: 1.5px;line-height: 1.75em;color: #333;" align="justify"><strong>企业福利：</strong>{{ item.tag==''?'无':item.tag }}</p>
            <p style="text-align:justify;font-size: 14px;letter-spacing: 1.5px;line-height: 1.75em;color: #333;" align="justify"><strong>联系人：</strong>{{ item.contact }}</p>
            <p style="text-align:justify;font-size: 14px;letter-spacing: 1.5px;line-height: 1.75em;color: #333;" align="justify"><strong>企业地址：</strong>{{ item.address }}</p>
            <section v-if="link=='normal'">
              <p />
              <!-- 二维码 -->
              <section><img :src="handlerNormalQrcode(item)" data-ratio="1" data-w="430" width="124" height="124" style="width: 124px; height: 124px; display:block; margin:0 auto;"></section><section style="text-align: center; font-size: 14px; letter-spacing: 1.5px; line-height: 1.75em; color: #333333;" align="center">识别二维码立即投递简历</section>
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
              <section v-if="link=='miniprogram'" style="color:#3859fe; text-align:center;font-weight:bold; font-size:15px;"><a style="color:#3859fe;text-decoration:none;" :data-miniprogram-appid="item.miniprogram_appid" :data-miniprogram-path="item.miniprogram_path" data-miniprogram-nickname="" data-miniprogram-type="text" data-miniprogram-servicetype="0">&gt;&gt;&gt;点击获取联系方式&lt;&lt;&lt;</a></section>
              <section v-else style="color:#3859fe; text-align:center;font-weight:bold; font-size:15px;"><a style="color:#3859fe;text-decoration:none;" :href="item.companyshow_link">&gt;&gt;&gt;点击获取联系方式&lt;&lt;&lt;</a></section>
              <p />
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
      url: this.$store.state.config.sitedomain + this.$store.state.config.sitedir + 'upload/resource/marketing/companylist4-1.png'
    }
  },
  created() {
  },
  methods: {
    handlerNormalQrcode(item){
      return window.global.RequestBaseUrl + apiArr.showQrcode + '?url=' + item.companyshow_link
    }
  }
}
</script>
