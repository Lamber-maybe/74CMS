<template>
  <section>
    <section v-for="(item,index) in datalist.items" :key="index" style="text-align: center; margin: 20px 10px; box-sizing: border-box;">
      <section style="box-shadow: #bcd1a9 0px 0px 8px;  background: #fefefe; padding: 8px; box-sizing: border-box; width:99%;margin-top:5px;">
        <section style="border-radius: 10px; border-width: 1px; border-style: dashed; border-color: #accc96;padding: 15px ">
          <section style="width:80%;margin:10px auto;">
            <section style="text-align:center;">
              <section style="text-align:center;margin-top:-10px;font-size:16px;letter-spacing:2px;color:#3f3f3f;display: inline-block;border-bottom:1px dashed #87949d;padding:0px 0px 4px 5px;">{{ item.intention_jobs }}</section>
              <section style="width:5em;margin:0px auto;"><img class="assistant" style="width:5em;display: block;" src="https://ae01.alicdn.com/kf/H5fdcc0691cea4000a51bda9dca19cef96.png" data-ratio="0.3208955223880597" data-w="134"></section>
            </section>
          </section>
          <p style="text-align:left;font-size: 14px;  color: #3e7f33;letter-spacing: 2px;" align="left"><span style="caret-color: red;">基本信息：{{ item.sex_text }}，{{ item.age_text }}岁，{{ item.education_text }}，{{ item.experience_text }}</span></p>
          <p style="text-align:left;font-size: 14px;  color: #3e7f33;letter-spacing: 2px;" align="left">求职状态：{{ item.current_text }}</p>
          <p style="text-align:left;font-size: 14px;  color: #3e7f33;letter-spacing: 2px;" align="left">意向地区：{{ item.intention_district }}</p>
          <p style="text-align:left;font-size: 14px;  color: #3e7f33;letter-spacing: 2px;" align="left">{{ item.specialty }}</p>
          <section v-if="link=='normal'">
            <p />
            <section style="text-align:center;"><img :src="handlerNormalQrcode(item)" alt="saf.png" data-ratio="1" data-w="360" width="108" height="108" style="width: 90px; height: 90px;"></section>
            <section style="caret-color: red;text-align:center; font-size:15px;">长按识别查看简历</section>
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
            <section v-if="link=='miniprogram'" style="text-align:center; font-size:14px;  letter-spacing: 2px;"><a style="text-decoration: none;color: rgb(62, 127, 51);font-weight:bold;" :data-miniprogram-appid="item.miniprogram_appid" :data-miniprogram-path="item.miniprogram_path" data-miniprogram-nickname="" data-miniprogram-type="text" data-miniprogram-servicetype="0">&gt;&gt;点击获取联系方式&lt;&lt;</a></section>
            <section v-else style="text-align:center; font-size:14px;  letter-spacing: 2px;"><a :href="item.resumeshow_link" style="text-decoration: none;color: rgb(62, 127, 51);font-weight:bold;">&gt;&gt;点击获取联系方式&lt;&lt;</a></section>
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
      return window.global.RequestBaseUrl + apiArr.showQrcode + '?url=' + item.resumeshow_link
    }
  }
}
</script>
