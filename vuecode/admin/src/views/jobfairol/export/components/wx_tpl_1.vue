<template>
  <div id="J_tem_box" class="app-container">
    <!-- 模板1 -->
    <section v-for="(item,index) in company" :key="index" style="margin: 10px 0px; text-align: center;">
      <section style="border: 1px solid rgb(239, 112, 96); padding: 0px 3px;">
        <section style="border-left: 1px solid rgb(239, 112, 96); border-right: 1px solid rgb(239, 112, 96);">
          <section :style="`background-image: url(${url}); background-position: 0% 0%; background-repeat: repeat;padding:15px;`" class="">
            <section style="background-color: #ef7060; border-radius: 30px; padding: 3px 20px; transform: rotateZ(0deg); color: #ffffff; font-size: 17px; display: inline-block;">
              <p style="letter-spacing: 2px;">
                {{ item.companyname }}
              </p>
            </section>
            <p style="margin: 7px 0; text-align:left; letter-spacing: 2px;" align="left">
              <span style="font-size: 15px; letter-spacing: 2px;">共有{{ item.jobs_count }}个在招职位：</span>
            </p>
            <section v-for="(v,k) in item.jobs" :key="k" style="margin-top: 10px; color: #ef7060;">
              <section style="text-align:left; letter-spacing: 2px;" align="left"><span style="font-size: 15px; letter-spacing: 2px;">{{ k+1 }}{{ v.jobname }}</span><span style="font-size: 15px; letter-spacing: 2px;">（{{ v.education_text }}</span><span style="font-size: 15px; letter-spacing: 2px;">，{{ v.experience_text }}，</span><span v-if="v.negotiable == 1" style="font-size: 15px; letter-spacing: 2px;">面议）</span><span v-if="v.negotiable == 0" style="font-size: 15px; letter-spacing: 2px;">{{ v.minwage/1000 }}k-{{ v.minwage/1000 }}k/月）</span></section>
            </section>
            <p style="clear:both;" />
            <section style="font-size: 15px; letter-spacing: 2px;"><img :src="link == 'mobile' ? item.mobile_qrcode : item.wx_qrcode" style="width: 108px; height: 108px;"></section>
            <section style="font-size: 15px; letter-spacing: 2px; text-align:center;">长按识别二维码了解更多</section>
          </section>
        </section>
      </section>
    </section>
  </div>
</template>
<script>
export default {
  props: ['company', 'link'],
  data () {
    return {
      url: this.$store.state.config.sitedomain + this.$store.state.config.sitedir + 'upload/resource/jobfair/wx_tpl_1.png'
    }
  }
}
</script>
<style scoped>
    .app-container{width: 432px;height: 560px;border: 1px solid #d9d9d9;padding: 15px;overflow-y: auto;}
</style>
