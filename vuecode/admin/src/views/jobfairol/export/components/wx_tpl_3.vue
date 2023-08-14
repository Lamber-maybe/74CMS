<template>
  <div id="J_tem_box" class="app-container">
    <!-- 模板3 -->
    <section v-for="(item,index) in company" :key="index" style="margin: 10px 0px; text-align: center;">
      <section style="border: 3px solid #d3f0f0; padding: 10px; text-align: center;">
        <section style="border-bottom:solid 1px #b4dfdf;display:inline-block;padding:2px 30px 10px 30px;"><section style="color: #3a8a8a; letter-spacing: 2px; text-align:center;"><strong>{{ item.companyname }}</strong></section></section>
        <section style="width:40px;height:4px;background-color:#b4dfdf;margin:-2px auto 0px;" />
        <section style="margin:10px 5px 10px;">
          <p style="text-align: left; letter-spacing: 2px;">
            <span style="font-size: 14px;">该企业共{{ item.jobs_count }}个在招职位：</span>
          </p>
          <section v-for="(v,k) in item.jobs" :key="k" style="margin-top: 10px;">
            <section style="text-align:left; letter-spacing: 2px;" align="left"><span style="float: left; font-size: 15px; letter-spacing: 2px;">{{ k+1 }}、</span><span style="font-size: 15px; letter-spacing: 2px;">{{ v.jobname }}，<span v-if="v.negotiable == 1" style="font-size: 15px; letter-spacing: 2px;">面议，</span><span v-if="v.negotiable == 0" style="font-size: 15px; letter-spacing: 2px;">{{ v.minwage/1000 }}k-{{ v.minwage/1000 }}k/月，</span></span><span style="font-size: 15px; letter-spacing: 2px;">{{ v.age_text }}，</span><span style="font-size: 15px; letter-spacing: 2px;">{{ v.education_text }}</span></section>
          </section>
          <p style="clear:both;" />
          <section style="font-size: 15px; letter-spacing: 2px;"><img :src="link == 'mobile' ? item.mobile_qrcode : item.wx_qrcode" style="width: 108px; height: 108px;"></section>
          <section style="font-size: 15px; letter-spacing: 2px; text-align:center;">长按识别二维码了解更多</section>
        </section>
      </section>
    </section>
  </div>
</template>
<script>
export default {
  props: ['company', 'link']
}
</script>
<style scoped>
    .app-container{width: 432px;height: 560px;border: 1px solid #d9d9d9;padding: 15px;overflow-y: auto;}
</style>
