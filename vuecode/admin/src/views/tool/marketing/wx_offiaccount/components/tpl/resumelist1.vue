<template>
  <section>
    <section v-for="(item,index) in datalist.items" :key="index" style="padding:0;margin:0;display:block;box-sizing:border-box;margin-bottom:10px;">
      <section style="padding:0;margin:0;display:block;text-align:center;box-sizing:border-box" class="">
        <section style="padding:0;margin:0;display:inline-flex;flex-direction:column;box-sizing:border-box;align-items:flex-start;text-align:center">
          <section class="yead_bdc" style="display:inline-flex;margin:0;padding:0;line-height:0;width:100%;border:5px solid #5a4dea;border-radius:13px;height:13px;flex-shrink:0;flex-flow:column;-webkit-transform:rotate(0);box-sizing:border-box" />
          <section style="padding:0 8px;margin:-8px 0 0;display:inline-flex;z-index:1;box-sizing:border-box" class="">
            <section style="margin:0;padding:0;display:block;box-sizing:border-box">
              <section style="padding:0;display:block;margin:0;background-color:#fff;box-shadow:0 0 8px #ccc;border-bottom-left-radius:5px;border-bottom-right-radius:5px">
                <section style="padding:0;display:block;margin:0;box-sizing:border-box">
                  <section style="color:#333;margin:0;font-size:16px;padding:10px 15px;line-height:30px;text-align:justify;box-sizing:border-box;-webkit-transform:rotate(0)" class="">
                    <p style="padding:0;margin:0">{{ item.fullname }}({{ item.sex_text }}，{{ item.age_text }}岁，{{ item.education_text }}，{{ item.experience_text }})，想找&nbsp;<strong>{{ item.intention_jobs }} </strong>工作，想在&nbsp;<strong>{{ item.intention_district }} </strong>工作。</p>
                    <section v-if="link=='normal'">
                      <p />
                      <section style="text-align:center;"><img :src="handlerNormalQrcode(item)" alt="saf.png" data-ratio="1" data-w="360" width="108" height="108" style="width: 90px; height: 90px;"></section>
                      <section style="text-align:center; font-size:15px;">长按识别获取联系方式</section>
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
                      <section v-if="link=='miniprogram'" style=" text-align: center;"><a style="padding: 0px; margin: 0px; color:#5a4dea; text-decoration:none; display:inline-block;" align="center" :data-miniprogram-appid="item.miniprogram_appid" :data-miniprogram-path="item.miniprogram_path" data-miniprogram-nickname="" data-miniprogram-type="text" data-miniprogram-servicetype="0">&gt;&gt;点击查看个人信息&lt;&lt;</a></section>
                      <section v-else style=" text-align: center;"><a :href="item.resumeshow_link" style="padding: 0px; margin: 0px; color:#5a4dea; text-decoration:none; display:inline-block;" align="center">&gt;&gt;点击查看个人信息&lt;&lt;</a></section>
                      <p />
                    </section>
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
