<template>
  <section>
    <section v-for="(item,index) in datalist.items" :key="index">
      <section>
        <section style="padding:5px;padding-top:2em;color: #333;">
          <section style="margin-bottom: -3.6em;margin-left: -6px;transform: rotate(0deg);-webkit-transform: rotate(0deg);-moz-transform: rotate(0deg);-o-transform: rotate(0deg);">
            <section style="display: flex;justify-content: flex-start;align-items: center;">
              <section style="border-top-left-radius:5px; display: inline-block;background: #fcd520;height: 30px;padding: 0px 0.5em 0px 1em;letter-spacing: 1.5px;line-height: 30px;">{{ index+1 }} - <span class="autonum" data-original-title="" title="">{{ item.companyname }}</span></section>
              <section class="assistant" style="width:0px; height:0px; border-top:solid 30px transparent; border-left:solid 20px #fcd520;" />
              <section class="assistant" style="width:0px; height:0px; border-top:solid 30px #fcd520; border-right:solid 20px transparent;margin-left: -20px;" />
            </section>
            <section data-bdless="spin" data-bdlessp="280" data-bdopacity="10%" style="width:0px; height:0px; border-bottom:solid 6px transparent; border-right:solid 6px #e9ae20;" />
          </section>
          <section style="background: #ffffff; border-radius:20px;padding:0.4em;box-shadow: 0px 0px 15px #d3d3d3;">
            <section style="border: #fcd520 solid 1px;padding:3.4em 1em 1em 1em;border-radius:15px;">
              <section style="text-align: center;">
                <section data-autoskip="1" style="text-align: justify;line-height: 1.75em;letter-spacing: 1.5px;font-size: 14px;">
                  <section v-for="(jobitem,ind) in item.joblist" :key="ind">
                    <section v-if="ind<2">
                      <section v-if="link=='miniprogram'"><a style="color:#333;text-decoration:none;" :data-miniprogram-appid="jobitem.miniprogram_appid" :data-miniprogram-path="jobitem.miniprogram_path" data-miniprogram-nickname="" data-miniprogram-type="text" data-miniprogram-servicetype="0"><p /><p><strong><span style="font-size: 18px;">{{ jobitem.jobname }} <span style="font-size: 14px;" /></span></strong><span style="font-size: 18px;"><span style="font-size: 14px;">[招{{ jobitem.amount }}] <strong>{{ jobitem.wage }}</strong></span></span></p><p style="white-space: pre-line;" v-html="jobitem.content" /></a></section>
                      <section v-else><a style="color:#333;text-decoration:none;" :href="jobitem.jobshow_link"><p /><p><strong><span style="font-size: 18px;">{{ jobitem.jobname }} <span style="font-size: 14px;" /></span></strong><span style="font-size: 18px;"><span style="font-size: 14px;">[招{{ jobitem.amount }}] <strong>{{ jobitem.wage }}</strong></span></span></p><p style="white-space: pre-line;" v-html="jobitem.content" /></a></section>
                      <hr>
                    </section>
                  </section>
                  <section v-if="item.joblist.length>2">
                    <p />
                    <p>该企业更多职位：</p>
                    <section v-for="(jobitem,ind) in item.joblist" :key="ind">
                      <section v-if="ind>2">
                        <p v-if="link=='miniprogram'"><a style="color:#333;text-decoration:none;" :data-miniprogram-appid="jobitem.miniprogram_appid" :data-miniprogram-path="jobitem.miniprogram_path" data-miniprogram-nickname="" data-miniprogram-type="text" data-miniprogram-servicetype="0">{{ jobitem.jobname }}({{ jobitem.wage }})</a></p>
                        <p v-else><a style="color:#333;text-decoration:none;" :href="jobitem.jobshow_link">{{ jobitem.jobname }}({{ jobitem.wage }})</a></p>
                      </section>
                    </section>
                  </section>
                  <p />
                  <p>企业福利：{{ item.tag==''?'无':item.tag }}</p>
                  <p>联系人：{{ item.contact }}</p>
                  <p>联系地址：{{ item.address }}</p>
                  <section v-if="link=='normal'">
                    <p />
                    <section style="text-align:center;" align="center"><img :src="handlerNormalQrcode(item)" alt="saf.png" data-ratio="1" data-w="360" width="99" height="99" style="width: 99px; height: 99px;"></section><section style="text-align:center;" align="center">长按识别获取联系方式</section>
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
                    <section v-if="link=='miniprogram'" style="color:rgb(252, 213, 32); text-align:center;font-weight:bold; font-size:15px;"><a style="color:rgb(252, 213, 32);text-decoration:none;" :data-miniprogram-appid="item.miniprogram_appid" :data-miniprogram-path="item.miniprogram_path" data-miniprogram-nickname="" data-miniprogram-type="text" data-miniprogram-servicetype="0">&gt;&gt;&gt;点击获取联系方式&lt;&lt;&lt;</a></section>
                    <section v-else style="color:rgb(252, 213, 32); text-align:center;font-weight:bold; font-size:15px;"><a style="color:rgb(252, 213, 32);text-decoration:none;" :href="item.companyshow_link">&gt;&gt;&gt;点击获取联系方式&lt;&lt;&lt;</a></section>
                    <p />
                  </section>
                </section>
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
      return window.global.RequestBaseUrl + apiArr.showQrcode + '?url=' + item.companyshow_link
    }
  }
}
</script>
