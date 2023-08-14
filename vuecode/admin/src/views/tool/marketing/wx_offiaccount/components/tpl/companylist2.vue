<template>
  <section>
    <section v-for="(item,index) in datalist.items" :key="index" style="max-width: 100%;margin-bottom:10px;">
      <section style="width:65px;height:65px;background:rgb(254,254,254);transform: rotate(0);margin-bottom: -56px;">
        <section style="width: 8px;height: 8px;border-radius:100%;background:#f7d749;float: right;margin-top:6px;" />
        <section data-brushtype="text" style="text-align: left;color: #333;font-size:24px;letter-spacing:3px;font-weight: bold;">诚聘</section>
        <section data-brushtype="text" style="text-align: left;color: #333;font-size:12px;font-weight: bold;">JOIN US</section>
        <section style="width: 8px;height: 8px;border-radius:100%;background:#f7d749;margin-top:5px;margin-left:-1px;" />
      </section>
      <section data-bgless="spin" data-bglessp="120" data-bgopacity="1%" style="padding-bottom:5px;border-radius:8px;background-color:#ffeea4;">
        <section style="border: 2px solid #f7d749;padding: 10px 0px 20px 0px;background-color:rgb(255,255,255);border-radius:8px;margin-top: -10px;margin-right:4px;">
          <section style="text-align: center;margin-top:15px;">
            <section data-brushtype="text" style="display:inline-block;background:#f7d749;padding: 6px 1em;font-size: 16px;color: #333;letter-spacing: 1.5px;">{{ item.companyname }}</section>
          </section>
          <section data-autoskip="1" style="padding:0px 1.5em;text-align: justify;font-size: 14px;color: #333;letter-spacing: 1.5px;margin:12px auto;line-height: 1.75em;" class="">
            <p style="color: #ffa900; font-size: 15px;">
              <section v-for="(jobitem,ind) in item.joblist" :key="ind" style="font-size: 14px; caret-color: red; color: #ffa900;text-decoration: none; text-align: center; display:block;">
                <section v-if="link=='miniprogram'" style=" font-size: 15px;"><a style="color:#ffa900;text-decoration:none;" :data-miniprogram-appid="jobitem.miniprogram_appid" :data-miniprogram-path="jobitem.miniprogram_path" data-miniprogram-nickname="" href="" data-miniprogram-type="text" data-miniprogram-servicetype="0">{{ jobitem.jobname }}&nbsp;:{{ jobitem.wage }}</a></section>
                <section v-else style=" font-size: 15px;"><a style="color:#ffa900;text-decoration:none;" :href="jobitem.jobshow_link">{{ jobitem.jobname }}&nbsp;:&nbsp;{{ jobitem.wage }}</a></section>
              </section>
            </p>
            <section style="padding-bottom: 10px; color: #666666; font-family: &quot;microsoft yahei&quot;, 宋体; font-size: 15px; letter-spacing: 0.544px; text-align: justify; white-space: normal; line-height: 2;">公司福利：{{ item.tag==''?'无':item.tag }}</section>
            <section style="padding-bottom: 10px;color: #666666; font-family: &quot;microsoft yahei&quot;, 宋体; font-size: 15px; letter-spacing: 0.544px; text-align: justify;">公司地址：{{ item.address }}</section>
            <!-- 链接 -->
            <!-- 二维码 -->
            <section v-if="link=='normal'">
              <p />
              <section><img :src="handlerNormalQrcode(item)" style="width: 93px;  margin:0 auto; display:block;"></section><section style="text-align: center;font-size:14px;" align="center">长按识别了解更多信息</section>
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
              <section v-if="link=='miniprogram'" style="color:#ffa900; text-align:center;font-weight:bold; font-size:15px;"><a style="color:#ffa900;text-decoration:none;" :data-miniprogram-appid="item.miniprogram_appid" :data-miniprogram-path="item.miniprogram_path" data-miniprogram-nickname="" data-miniprogram-type="text" data-miniprogram-servicetype="0">&gt;&gt;&gt;点击获取联系方式&lt;&lt;&lt;</a></section>
              <section v-else style="color:#ffa900; text-align:center;font-weight:bold; font-size:15px;"><a style="color:#ffa900;text-decoration:none;" :href="item.companyshow_link">&gt;&gt;&gt;点击获取联系方式&lt;&lt;&lt;</a></section>
              <p />
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
      return window.global.RequestBaseUrl + apiArr.showQrcode + '?url=' + item.companyshow_link
    }
  }
}
</script>
