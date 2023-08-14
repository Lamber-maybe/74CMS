<template>
  <section>
    <section v-for="(item,index) in datalist.items" :key="index">
      <section style="border: 0px currentColor; border-image: none;">
        <section style="padding: 10px;">
          <section style="width: 100%;" data-width="100%">
            <section style="width: 100%; display: flex; justify-content: flex-end; -webkit-justify-content: flex-end;" data-width="100%">
              <section style="width: 15px; height: 15px; border-top-color: #fdc010; border-right-color: #fdc010; border-top-width: 10px; border-right-width: 10px; border-top-style: solid; border-right-style: solid;" />
            </section>
            <section style="margin: -20px 5px; border: 1px solid #fdc010; border-image: none; text-align: justify;">
              <section style="padding: 14px;">
                <section style="text-align: center;">
                  <section style="width: auto; display: inline-block;">
                    <section style="padding: 3px; display: inline-block;">
                      <section style="padding: 0px 10px; font-size: 20px; display: flex; align-items: center; -webkit-align-items: center;transform: translateZ(10px);-webkit-transform: translateZ(10px);-moz-transform: translateZ(10px);-o-transform: translateZ(10px);">
                        <section style="border-radius: 50%; width: 8px; height: 8px; display: block; background-color: #fdc010;" />
                        <section style="padding: 5px 10px;"><section><strong>{{ item.jobname }}</strong></section></section>
                        <section style="border-radius: 50%; width: 8px; height: 8px; display: block; background-color: #fdc010;" />
                      </section>
                    </section>
                    <section style="padding: 5px 10px;">
                      <p style="margin: 0px;">{{ item.wage }}</p>
                    </section>
                    <section style="margin: 10px auto; padding: 3px 10px; width: auto; display: inline-block; background-color: #fdc010;"><p style="margin: 0px;">职位描述</p></section>
                    <section style="line-height: 30px;">
                      <p style="white-space: pre-line;" v-html="item.content" />
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
                        <section v-if="link=='miniprogram'" style="text-align: center;">&gt;&gt;<a style="text-decoration:none" :data-miniprogram-appid="item.miniprogram_appid" :data-miniprogram-path="item.miniprogram_path" data-miniprogram-nickname="" data-miniprogram-type="text" data-miniprogram-servicetype="0"><span style="color: #fdc010;"><strong><span style="font-size: 15px;">点击申请职位</span></strong></span></a>&lt;&lt;</section>
                        <section v-else style="text-align: center;">&gt;&gt;<a :href="item.jobshow_link" style="text-decoration:none"><span style="color: #fdc010;"><strong><span style="font-size: 15px;">点击申请职位</span></strong></span></a>&lt;&lt;</section>
                        <p />
                      </section>
                    </section>
                  </section>
                </section>
              </section>
            </section>
            <section style="width: 100%;" data-width="100%">
              <section style="width: 15px; height: 15px; border-bottom-color: #fdc010; border-left-color: #fdc010; border-bottom-width: 10px; border-left-width: 10px; border-bottom-style: solid; border-left-style: solid;" />
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
