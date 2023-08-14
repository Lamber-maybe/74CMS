<template>
  <div>
    <div class="bg">
      <h2 class="com_title_1">
          心动不如薪动<br />
          钱和情怀<br />
          诗和远方<br />
          我们都给你
        </h2>
        <div class=" com_info">

          <div class="com_box">
            <div class="com_img"><img :src="info.logo"></div>
            <h3 class="com_name">{{info.companyname}}</h3>
            <p class="job_num">
              <span class="color">{{info.jobnum}}个</span>职位正在招聘
            </p>
            <div class="com_line"></div>

            <div class="com_job_list">
              <div v-for="(item,index) in info.joblist" :key="index">
                <div class="com_job_item" v-if="index<3">
                  <div class="job_name">{{item.jobname}}</div>
                  <div class="salary color">{{item.wage_text}}</div>
                </div>
              </div>
            </div>

            <div class="com_share_code">
              <div class="share_text">
                <div class="share_text_1">长按识别二维码</div>
                <div class="share_text_2">快来加入我们吧！</div>
              </div>
              <div class="share_code">
                <img :src="qrcode" alt="">
              </div>
            </div>
          </div>

          <p class="qs_text">{{$store.state.config.sitename}}</p>
        </div>
  </div>
  </div>
</template>

<script>
export default {
  name: 'ShareCompanyOne',
  props: ['info', 'qrcode'],
  data () {
    return {
    }
  },
  mounted () {

  },
  methods: {
  }
}
</script>

<style lang="scss" scoped>
.bg {
  background: url(../../../assets/images/share/new/4.jpg) no-repeat top center / 100% 100%;
  width: 321px;
  height: 536px;
  position: relative;
}

.com_title_1 {
  color: #ffffff;
  font-size: 27px;
  position: absolute;
  left: 27.5px;
  top: 25px;
}

.com_info {
  width: 280px;
  height: 310px;
  position: absolute;
  bottom: 20px;
  left: 50%;
  transform: translateX(-50%);
}
.com_info .com_box {
  height: 290px;
  border-radius: 15px;
  background-color: #fff;
  position: relative;
  padding: 50px 0 0;
}
.com_box .com_img {
  width: 58px;
  height: 58px;
  border: 1px solid #ebebeb;
  position: absolute;
  left: 50%;
  top: -17.5px;
  transform: translateX(-50%);
  background-color: #fff;
  border-radius: 5px;
  text-align:center;
}
.com_box .com_img img {
  max-width: 50px;
  max-height: 50px;
  margin: 2px auto;
}

.com_box .com_name {
  text-align: center;
  color: #0f0f0f;
  font-size: 17px;
  margin-bottom: 7.5px;
}

.com_box .job_num {
  color: #999999;
  font-size: 13px;
  text-align: center;
  margin-bottom: 15px;
}

.com_box .color {
  color: #f97d07;
}

.com_box .com_line {
  width: 258px;
  border-bottom: 0.5px dashed #e6e6e6;
  margin: 0 auto;
}
.com_box .com_job_list {
  padding: 15px 0 0;
  margin: 0 19px;
  height:100px;
}
.com_job_list .com_job_item {
  display: flex;
  justify-content: space-between;
  font-size: 15px;
  align-items: center;
  margin: 0 0 7.5px 0;
}

.com_job_list .com_job_item .job_name {
  color: #444444;
}

.com_share_code {
  display: flex;
  justify-content: space-between;
  margin: 0 16px 0 12px;
}

.com_share_code .share_text {
  padding-left: 49px;
  background: url(../../../assets/images/share/new/5.png) no-repeat left center /
    37.5px 58.5px;
}
.com_share_code .share_text .share_text_1 {
  color: #2a2751;
  font-size: 15px;
  margin: 5px 0 10px 0;
}

.com_share_code .share_text .share_text_2 {
  color: #ffffff;
  font-size: 13px;
  width: 125.5px;
  height: 20px;
  line-height: 22.5px;
  text-align: center;
  background: url(../../../assets/images/share/new/6.png) no-repeat center center /
    125.5px 20px;
}

.com_share_code .share_code {
  width: 61.5px;
  height: 61.5px;
  position: relative;
}

.com_share_code .share_code img {
  position: absolute;
  left: 0;
  right: 0;
  bottom: 0;
  top: 0;
  margin: auto;
  max-width: 60px;
  max-height: 60px;
}

.com_info .qs_text {
  text-align: center;
  color: #ffffff;
  font-size: 13px;
  margin-top: 13px;
}

</style>
