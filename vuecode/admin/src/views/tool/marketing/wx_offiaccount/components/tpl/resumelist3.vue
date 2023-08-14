<template>
  <section>
    <section v-for="(item,index) in datalist.items" :key="index">
      <section style="padding:0;margin:0;display:block;box-sizing:border-box;margin-bottom:10px;">
        <section style="display:block;margin:0 auto -12px;padding:0;line-height:0;width:50px;box-sizing:border-box">
          <img src="https://ae01.alicdn.com/kf/H4568fafeedc04c0ba7888861af95d1e4R.png" style="width:100%;display:block;box-sizing:border-box" data-ratio="0.46473029045643155" data-w="241">
        </section>
        <section style="padding:0;display:block;margin:0;border:2px solid #590504;background-color:#fff;overflow:hidden;border-radius:9px;box-sizing:border-box">
          <section style="padding:0;display:block;margin:0;background-color:#fff;box-sizing:border-box;background-image:url(https://ae01.alicdn.com/kf/H62a3e409961a49d79cc524a3f8649f2b0.png);background-repeat:repeat-y;background-size:5px;background-position:bottom right">
            <section style="padding:0 5px 5px 0;display:block;margin:0;box-sizing:border-box;background-image:url(https://ae01.alicdn.com/kf/He26b2267baea433a89803cbc2a51a75et.png);background-repeat:repeat-x;background-size:28px;background-position:bottom left">
              <section style="padding:0;display:block;margin:0;border:2px solid #590504;border-top:0;border-left:0;background-color:#fff;overflow:hidden;box-sizing:border-box">
                <section style="color:#590504;border:0;margin:0;font-size:15px;padding:15px 10px 8px;line-height:30px;text-align:justify;box-sizing:border-box;-webkit-transform:rotate(0);transform: rotate(0deg);-webkit-transform: rotate(0deg);-moz-transform: rotate(0deg);-ms-transform: rotate(0deg);-o-transform: rotate(0deg);" class="">
                  <p style="padding:0;margin:0"><span style="caret-color: red; font-size: 18px; font-weight:bold;">{{ item.fullname }}</span><span>({{ item.sex_text }}，{{ item.age_text }}岁，{{ item.education_text }}，{{ item.experience_text }})</span></p>
                  <p><span style="caret-color: red;">在找 {{ item.intention_jobs }} 的工作</span></p>
                  <p><span style="caret-color: red;">想在 {{ item.intention_district }} 工作</span></p>
                  <section v-if="link=='normal'">
                    <p />
                    <section style="text-align:center;"><img :src="handlerNormalQrcode(item)" alt="saf.png" data-ratio="1" data-w="360" width="108" height="108" style="width: 90px; height: 90px;"></section><section style="text-align:center; font-size:15px;">长按识别查看简历</section>
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
                    <section v-if="link=='miniprogram'" style="text-align: center;" align="center"><a style="color: #590504; font-weight:bold; text-decoration:none;" :data-miniprogram-appid="item.miniprogram_appid" :data-miniprogram-path="item.miniprogram_path" data-miniprogram-nickname="" data-miniprogram-type="text" data-miniprogram-servicetype="0">&gt;&gt;点击了解更多信息&lt;&lt;</a></section>
                    <section v-else style="text-align: center;" align="center"><a :href="item.resumeshow_link" style="color: #590504; font-weight:bold; text-decoration:none;">&gt;&gt;点击了解更多信息&lt;&lt;</a></section>
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
      return window.global.RequestBaseUrl + apiArr.showQrcode + '?url=' + item.resumeshow_link
    }
  }
}
</script>
