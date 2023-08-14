<template>
  <section>
    <section v-for="(item,index) in datalist.items" :key="index">
      <section style="font-size:16px;">
        <section style="padding:0;display:block;border:0;box-sizing:border-box;margin-bottom:10px">
          <section style="padding:0;display:flex;flex-direction:column;border:0;box-sizing:border-box">
            <section style="padding: 0px; margin: 0px 5px 8px 0px; background-color: rgb(238, 238, 238); border-radius: 6px; box-sizing: border-box; text-align: justify;" class="">
              <section style="padding:0;display:inline-block;margin:0;border-radius:4px;box-sizing:content-box;transform: translate(5px, 5px);-webkit-transform: translate(5px, 5px);-moz-transform: translate(5px, 5px);-ms-transform: translate(5px, 5px);-o-transform: translate(5px, 5px);" class="">
                <section class="yead_bgc" style="color: #ffffff; background-color: #da2811; font-weight: 700; margin: 0px 8px 0px 0px; font-size: 14px; padding: 0px 7px; border-radius: 4px; line-height: 20px; vertical-align: middle; text-align: justify; box-sizing: border-box; display: inline-block;transform: translate(0px);-webkit-transform: translate(0px);-moz-transform: translate(0px);-ms-transform: translate(0px);-o-transform: translate(0px);"><p style="padding:0;margin:0">名企直招</p>
                </section>
                <section style="color:#333;margin:0;font-size:16px;display:inline-block;font-weight:700;padding:0;line-height:25px;vertical-align:middle;text-align:justify;box-sizing:border-box;transform: translate(0px);-webkit-transform: translate(0px);-moz-transform: translate(0px);-ms-transform: translate(0px);-o-transform: translate(0px);" class=""><p style="padding:0;margin:0">{{ item.jobname }}</p></section>
              </section>
              <section style="padding:0;display:block;margin:0;background-color:#fff;border:1px solid #eee;border-radius:4px;box-sizing:content-box;transform: translate(5px, 8px);-webkit-transform: translate(5px, 8px);-moz-transform: translate(5px, 8px);width:97.5%;-ms-transform: translate(5px, 8px);-o-transform: translate(5px, 8px);" class="">
                <section style="color:#333;margin:0;font-size:15px;padding:8px 10px;line-height:30px;text-align:justify;box-sizing:border-box;transform: translate(0px);-webkit-transform: translate(0px);-moz-transform: translate(0px);-ms-transform: translate(0px);-o-transform: translate(0px);" class="">
                  <section style="color: #da2811; font-size: 18px; caret-color: red;text-align:center;font-weight:bold;">{{ item.wage }}</section>
                  <p><strong>福利待遇：</strong>{{ item.tag!=''?item.tag:'无' }}</p>
                  <p><strong style="caret-color: red;"><span class="pos_area_span pos_address">工作地址：</span></strong>{{ item.address }}</p>
                  <p><strong>职位描述：</strong></p>
                  <p style="white-space: pre-line;" v-html="item.content" />
                  <section v-if="link=='normal'">
                    <p />
                    <section style="text-align:center;" align="center"><img :src="handlerNormalQrcode(item)" alt="saf.png" data-ratio="1" data-w="360" width="108" height="108" style="width: 90px; height: 90px;"></section>
                    <section style="caret-color: red;text-align:center;">长按识别立即投递简历</section>
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
                    <section v-if="link=='miniprogram'" style="text-align: center;">&gt;&gt;<a style="text-decoration:none" :data-miniprogram-appid="item.miniprogram_appid" :data-miniprogram-path="item.miniprogram_path" data-miniprogram-nickname="" data-miniprogram-type="text" data-miniprogram-servicetype="0"><span style="color: #da2811;"><strong><span style="font-size: 15px;">点击申请职位</span></strong></span></a>&lt;&lt;</section>
                    <section v-else style="text-align: center;">&gt;&gt;<a :href="item.jobshow_link" style="text-decoration:none"><span style="color: #da2811;"><strong><span style="font-size: 15px;">点击申请职位</span></strong></span></a>&lt;&lt;</section>
                    <p />
                  </section>
                </section>
              </section>
            </section>
          </section>
        </section>
      </section>
      <p />
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
