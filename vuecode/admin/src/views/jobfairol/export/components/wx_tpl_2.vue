<template>
  <div id="J_tem_box" class="app-container">
    <!-- 模板2 -->
    <section v-for="(item,index) in company" :key="index" style="margin: 10px 0px; text-align: center;">
      <section style="background-color:#fff0ec;padding:13px;border-radius:10px;">
        <section style="border:dashed 2px #ffc8b7;border-radius:10px;padding:15px;background-color:#fefefe;">
          <section style="background-color:#fff0ec;padding:4px 18px;display:inline-block;border-radius:30px;color:#ff2400;">
            <section style="letter-spacing: 2px;">{{ item.companyname }}</section>
          </section>
          <section style="color:#ff2400;margin-top:10px;">
            <section style="text-align:left; letter-spacing: 2px;" align="left">
              <strong><span style="float:left;font-size: 15px; letter-spacing: 2px;">岗位：</span></strong>
              <section v-for="(v,k) in item.jobs" :key="k" style="float:left;text-align:left; letter-spacing: 2px;" align="left"><span style="font-size: 15px; letter-spacing: 2px;">{{ v.jobname }}</span><span>&nbsp; &nbsp;</span></section>
            </section>
            <p style="clear:both;" />
            <section style="font-size: 15px; letter-spacing: 2px;"><img :src="link == 'mobile' ? item.mobile_qrcode : item.wx_qrcode" style="width: 108px; height: 108px;"></section>
            <section style="color: #ff2400; font-size: 15px; letter-spacing: 2px; text-align:center;">长按识别二维码了解更多</section>
          </section>
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
