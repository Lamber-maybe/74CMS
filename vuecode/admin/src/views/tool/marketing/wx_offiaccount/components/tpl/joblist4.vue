<template>
  <section>
    <section v-for="(item,index) in datalist.items" :key="index">
      <section style="margin-bottom:10px;">
        <section style="padding:0;margin: 0 0 -30px;display:block;text-align: center;box-sizing:border-box;">
          <section style="margin:0;padding:0;display: inline-block;box-sizing:border-box;">
            <!-- https://ae01.alicdn.com/kf/He26c8e0df2594815809c1e927893d41ei.png -->
            <section :style="`padding: 0;display: block;margin:0;width: 100%;background: url(${url});background-repeat: no-repeat;background-size: 100% 100%;height: 100%;line-height: 0;box-sizing:border-box;`">
              <section style="padding:0;display:block;margin:0;line-height: 0;box-sizing:border-box;">
                <section style="display:block;color: #fff;margin:0;font-size:16px;padding: 0 17px 0 20px;letter-spacing:2px;line-height: 30px;text-align:justify;box-sizing:border-box;-webkit-transform:rotate(0);" class=""><section style="padding:0;margin:0">{{ item.jobname }}</section>
                </section>
              </section>
            </section>
          </section>
        </section>
        <section style="padding:5px;display:block;margin:0;box-shadow: 0 0 5px #ccc;background-color: #00a0ec;border-radius:14px;box-sizing:border-box;" class="">
          <section style="padding: 25px 0 0;display:block;margin:0;background-color:#fff;border-radius:10px;box-sizing:border-box;" class="">
            <section style="color:#333;margin:0;font-size:15px;padding:8px 10px;line-height:30px;text-align:justify;box-sizing:border-box;-webkit-transform:rotate(0)" class="">
              <p style="padding:0;margin:0">岗位薪资：{{ item.wage }}</p>
              <p style="padding:0;margin:0">招聘要求：{{ item.education_text }} 、{{ item.experience_text }}</p>
              <p style="padding:0;margin:0">岗位福利：{{ item.tag!=''?item.tag:'无' }}</p>
              <p style="padding:0;margin:0">工作地址：{{ item.address }}</p>
              <section v-if="link=='normal'">
                <p />
                <section style="text-align:center;"><img :src="handlerNormalQrcode(item)" alt="saf.png" data-ratio="1" data-w="360" width="108" height="108" style="width: 90px; height: 90px;"></section>
                <section style="caret-color: red;text-align:center;">长按识别获取联系方式</section>
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
                <section v-if="link=='miniprogram'" style="text-align: center;">&gt;&gt;<a style="text-decoration:none" :data-miniprogram-appid="item.miniprogram_appid" :data-miniprogram-path="item.miniprogram_path" data-miniprogram-nickname="" data-miniprogram-type="text" data-miniprogram-servicetype="0"><span style="color: #00a0ec;"><strong><span style="font-size: 15px;">点击申请职位</span></strong></span></a>&lt;&lt;</section>
                <section v-else style="text-align: center;">&gt;&gt;<a :href="item.jobshow_link" style="text-decoration:none"><span style="color: #00a0ec;"><strong><span style="font-size: 15px;">点击申请职位</span></strong></span></a>&lt;&lt;</section>
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
      url: this.$store.state.config.sitedomain + this.$store.state.config.sitedir + 'upload/resource/marketing/joblist4-1.png'
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
