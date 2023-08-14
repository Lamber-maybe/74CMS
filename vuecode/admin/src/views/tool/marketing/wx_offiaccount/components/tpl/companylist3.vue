<template>
  <section>
    <section v-for="(item,index) in datalist.items" :key="index" style="margin:10px auto;text-align: center;">
      <section style="display:flex;justify-content: center;align-items: center;">
        <section class="assistant" style="flex:1;height:1px;border-bottom: 2px dotted #cb313f;box-sizing: border-box;" />
        <section class="assistant" style="width:40px;height:9px;background-color: #cb313f;" />
        <section data-brushtype="text" style="text-align: center;font-size:16px;color:#333;letter-spacing:1.5px;padding:0px 10px;font-weight:bold;">{{ item.companyname }}</section>
        <section class="assistant" style="width:40px;height:9px;background-color: #cb313f;" />
        <section class="assistant" style="flex:1;height:1px;border-bottom: 2px dotted #cb313f;box-sizing: border-box;" />
      </section>
      <section class="assistant" style="flex:1;border: 2px dotted #cb313f;box-sizing: border-box;border-top:none;">
        <section data-autoskip="1" style="padding:1em;text-align: justify;font-size: 14px;letter-spacing: 1.5px;color:#333;margin-top:-10px;">
          <p>正在招聘：{{ item.job_text==''?'无':item.job_text }}
          </p>
          <p>联系人：{{ item.contact }}</p>
          <p>工作地点：{{ item.address }}</p>
          <p />
          <!-- 链接 -->
          <section v-if="link=='normal'">
            <p />
            <!-- 二维码 -->
            <section><img :src="handlerNormalQrcode(item)" style="width: 93px;  margin:0 auto; display:block;"></section><section style="text-align: center;" align="center">长按识别了解更多信息</section>
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
            <section v-if="link=='miniprogram'" style="color:#cb313f; text-align:center;font-weight:bold; font-size:15px;"><a style="color:#cb313f;text-decoration:none;" :data-miniprogram-appid="item.miniprogram_appid" :data-miniprogram-path="item.miniprogram_path" data-miniprogram-nickname="" data-miniprogram-type="text" data-miniprogram-servicetype="0">&gt;&gt;&gt;点击获取联系方式&lt;&lt;&lt;</a></section>
            <section v-else style="color:#cb313f; text-align:center;font-weight:bold; font-size:15px;"><a style="color:#cb313f;text-decoration:none;" :href="item.companyshow_link">&gt;&gt;&gt;点击获取联系方式&lt;&lt;&lt;</a></section>
            <p />
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
