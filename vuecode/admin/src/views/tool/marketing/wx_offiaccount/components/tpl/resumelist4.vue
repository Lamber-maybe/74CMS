<template>
  <section>
    <section v-for="(item,index) in datalist.items" :key="index" style="padding:0;margin:0;display:block;box-sizing:border-box;margin-bottom:15px">
      <section style="padding:0;display:block;border:1px solid #e86670;border-top:4px solid #e86670;margin:0;border-radius:5px;">
        <section style="padding:0;display:block;margin:0;background-color:rgba(255,255,255,.4);box-sizing:border-box">
          <section style="color: rgb(232, 102, 112); margin: 0px; padding: 8px 10px; line-height: 30px; text-align: justify; box-sizing: border-box; text-shadow: rgb(255, 255, 255) 1px 1px 1px; transform: rotate(0deg);" class="">
            <section style="text-align:center;font-size: 16px; padding: 0px; margin: 0px; font-weight:bold;" align="center">{{ item.fullname }}</section>
            <section style="font-size: 15px;text-align:center;font-size: 16px;">{{ item.sex_text }}，{{ item.age_text }}岁，{{ item.education_text }}学历，{{ item.experience_text }}</section>
            <section style="font-size: 15px;">想找 <strong>{{ item.intention_jobs }}</strong> 的工作</section>
            <section style="font-size: 15px;">想在 <strong>{{ item.intention_district }}</strong>工作</section>
            <section v-if="link=='normal'">
              <p />
              <section style="text-align:center;"><img :src="handlerNormalQrcode(item)" alt="saf.png" data-ratio="1" data-w="360" width="108" height="108" style="width: 90px; height: 90px;"></section>
              <section style="text-align:center; font-size:15px;">长按识别查看简历</section>
              <p />
            </section>
            <section v-else>
              <p />
              <section v-if="link=='miniprogram'" style="text-align:center;padding: 0px; margin: 0px;" align="center"><a style="font-size: 15px; font-weight:bold; color:#e86670; text-decoration:none;" :data-miniprogram-appid="item.miniprogram_appid" :data-miniprogram-path="item.miniprogram_path" data-miniprogram-nickname="" data-miniprogram-type="text" data-miniprogram-servicetype="0">&gt;&gt;点击获取简历联系方式&lt;&lt;</a></section>
              <section v-else-if="link=='weixin'">
                <p />
                <section align="center"><img :src="item.wxqrcode" data-ratio="1" data-w="360" width="99" height="99" style="width: 99px; height: 99px;"></section>
                <section style="text-align: center;font-size:14px;" align="center">长按识别了解更多信息</section>
                <p />
              </section>
              <section v-else style="text-align:center;padding: 0px; margin: 0px;" align="center"><a :href="item.resumeshow_link" style="font-size: 15px; font-weight:bold; color:#e86670; text-decoration:none;">&gt;&gt;点击获取简历联系方式&lt;&lt;</a></section>
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
      return window.global.RequestBaseUrl + apiArr.showQrcode + '?url=' + item.resumeshow_link
    }
  }
}
</script>
