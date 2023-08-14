<template>
  <section>
    <section v-for="(item,index) in datalist.items" :key="index" style="max-width:100%;">
      <section style="margin: 0px; padding: 0px; color: #333333; font-family: mp-quote, -apple-system-font, BlinkMacSystemFont, &quot;Helvetica Neue&quot;, &quot;PingFang SC&quot;, &quot;Hiragino Sans GB&quot;, &quot;Microsoft YaHei UI&quot;, &quot;Microsoft YaHei&quot;, Arial, sans-serif; font-size: 17px;       text-align: justify;         display: flex; justify-content: center; align-items: center;">
        <section style="margin: 0px; padding: 3px 7px; display: inline-block; background: #395afe; border-radius: 6px; font-weight: bold;">
          <section style="margin: 0px; padding: 0px; color: #ffffff;">{{ index+1 }}</section>
        </section>
        <section style="margin: 6px 0px 0px 5px; padding: 0px; display: inline-block;">
          <section style="margin: 0px; padding: 0px; font-weight: bold; font-size: 16px; letter-spacing: 2px; line-height: 1.75em; text-align: center; color: #3f3f3f;">{{ item.companyname }}</section>
          <section style="margin: 0px; padding: 0px;">
            <section style="margin: 0px; padding: 0px; width: 3em; border-bottom: 2px solid #3f3f3f; float: right; height: 2px;">
              <br style="margin: 0px; padding: 0px;">
            </section>
            <section data-width="100%" style="margin: 3px 0px 0px; padding: 0px; clear: both; width: 100%; border-bottom: 2px solid #3f3f3f; float: right; height: 2px;">
              <br style="margin: 0px; padding: 0px;">
            </section>
          </section>
        </section>
      </section>
      <section style="margin: 10px auto; padding: 0px; color: #333333; font-family: mp-quote, -apple-system-font, BlinkMacSystemFont, &quot;Helvetica Neue&quot;, &quot;PingFang SC&quot;, &quot;Hiragino Sans GB&quot;, &quot;Microsoft YaHei UI&quot;, &quot;Microsoft YaHei&quot;, Arial, sans-serif; font-size: 17px;       text-align: justify;        ">
        <section style="margin: 0px; padding: 1em; background-color: #fefefe; border-top: 1px dashed #4b68fc; border-right: 1px dashed #4b68fc; border-left: 1px dashed #4b68fc; border-bottom: none; box-sizing: border-box;">
          <section data-autoskip="1" style="margin: 0px; padding: 0px; text-align: justify; line-height: 1.75em; letter-spacing: 1.5px; font-size: 14px; color: #232d62;">
            <p style="margin: 0px; padding: 0px; clear: both;">招聘：{{ item.job_text==''?'无':item.job_text }}</p>
            <p style="margin: 0px; padding: 0px; clear: both;"><span style="margin: 0px; padding: 0px; color: #232d62; font-family: 微软雅黑, &quot;Microsoft YaHei&quot;, Arial, sans-serif; font-size: 14px; letter-spacing: 1.5px; text-align: justify; caret-color: #ff0000; background-color: #fefefe; display: inline !important;">联系人：</span>{{ item.contact }}&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;&nbsp;</p>
            <p style="margin: 0px; padding: 0px; clear: both;"><span style="margin: 0px; padding: 0px; color: #232d62; font-family: 微软雅黑, &quot;Microsoft YaHei&quot;, Arial, sans-serif; font-size: 14px; letter-spacing: 1.5px; text-align: justify; caret-color: #ff0000; background-color: #fefefe; display: inline !important;">地址：</span>{{ item.address }} &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;</p>
          </section>
          <!-- 链接 -->
          <section v-if="link=='normal'">
            <p />
            <section><img :src="handlerNormalQrcode(item)" style="width: 93px;  margin:0 auto; display:block;"></section>
            <section style="text-align: center;font-size:14px;" align="center">长按识别了解更多信息</section>
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
            <section style="margin: 0px; padding: 10px 0px; text-align: center; box-sizing: border-box;">
              <section style="margin: 0px; padding: 0px; align-items: center; display: -webkit-flex; justify-content: center;">
                <section style="margin: 0px; padding: 0px; color: #a0c6ff; font-size: 12px;">
                  ◀
                </section>
                <section style="margin: 0px 6px; padding: 0px; display: inline-block; background: #6ca1f2; color: #ffffff;">
                  <section v-if="link=='miniprogram'" data-brushtype="text" style="margin: 0px; padding: 0px 0.8em; font-size: 16px; text-align: justify; letter-spacing: 1.5px; line-height: 1.75em; box-sizing: border-box;"><a style="color:#fff;text-decoration:none;" :data-miniprogram-appid="item.miniprogram_appid" :data-miniprogram-path="item.miniprogram_path" data-miniprogram-nickname="" data-miniprogram-type="text" data-miniprogram-servicetype="0">去看详情、联系电话</a></section>
                  <section v-else data-brushtype="text"><a :href="item.companyshow_link" style="color:#fff;text-decoration: none;margin: 0px; padding: 0px 0.8em; font-size: 16px; text-align: justify; letter-spacing: 1.5px; line-height: 1.75em; box-sizing: border-box;">去看详情、联系电话</a></section>
                </section>
                <section style="margin: 0px; padding: 0px; color: #a0c6ff; font-size: 12px;">
                  ▶
                </section>
              </section>
            </section>
            <p />
          </section>
        </section>
        <section style="margin: 0px; padding: 0px;">
          <img data-ratio="0.006666666666666667" src="https://ae01.alicdn.com/kf/Hfa8b280f240843e1a8f7e2fc9a22a948H.png" data-type="gif" data-w="600" style="margin: 0px; padding: 0px; max-width: 100%; width: 100%; display: block; height: auto !important;">
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
