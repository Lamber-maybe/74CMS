<template>
  <div>
    <div class="bg">
      <h2 class="com_title_1">
          寻找<br />
          发光发亮的你<br />
          我们求贤若渴<br />
          我们给你一个梦想的舞台
        </h2>
        <div class="com_info">

          <div class="com_box">
            <div class="com_info_text">
              <p class="i_text_1 substring">{{info.companyname}}</p>
              <p class="i_text_2 substring">{{info.address}}</p>
            </div>
            <div class="com_info_img"><img :src="info.logo"></div>
          </div>

          <div class="line"></div>

          <div class="com_job_list">
            <div v-for="(item,index) in info.joblist" :key="index">
              <div class="com_job_item" v-if="index<2">
                <div class="item_text_1 substring">{{item.jobname}}</div>
                <div class="item_text_2 substring">{{item.wage_text}}</div>
                <div class="item_text_3 substring">{{item.district_text}}</div>
              </div>
            </div>
          </div>

          <div class="share_code">
            <div class="share_text">
              <p class="s_text_1">长按识别二维码</p>
              <p class="s_text_2">立即查看公司更新信息</p>
            </div>
            <div class="share_img">
                <img :src="qrcode" alt="">
            </div>
          </div>
          <p class="qs_text">{{$store.state.config.sitename}}</p>
        </div>
      </div>
  </div>
</template>

<script>
export default {
  name: 'ShareCompanyTwo',
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
  background: url(../../../assets/images/share/new/8.jpg) no-repeat top center / 100% 100%;
  width: 321px;
  height: 536px;
  position: relative;
}

.com_title_1 {
  color: #ffffff;
  font-size: 22px;
  position: absolute;
  left: 29.5px;
  top: 27.5px;
  line-height: 35px;
}

.com_info {
  width: 280px;
  min-height:325px;
  position: absolute;
  bottom: 22.5px;
  left: 50%;
  transform: translateX(-50%);
  background: url(../../../assets/images/share/new/7.png) no-repeat top center / 280px 100%;
}
.com_info .com_box {
  display: flex;
  justify-content: space-between;
  padding: 15px 17.5px 17.5px 19px;
}

.com_box .com_info_text .i_text_1{
  color: #0f0f0f;
  font-size: 17.5px;
  margin: 5px 0 8.5px;
  max-width: 160px;
}

.com_box .com_info_text .i_text_2{
  color: #0f0f0f;
  max-width: 160px;
  font-size: 13px;
}

.com_box .com_info_img {
  width: 58px;
  height: 58px;
  // border: 0.5px solid #ebebeb;
  position: relative;
  // border-radius: 15px;
}

.com_box .com_info_img img{
  max-width: 56px;
  max-height: 56px;
  margin:auto;
}

.com_info .line {
  width: 259px;
  border-bottom: 5px dotted #ccc;
  margin: 0 auto;
}

.com_info .com_job_list {
  display: flex;
  justify-content: space-between;
  flex-wrap: wrap;
  margin: 15px 18.5px;
}

.com_job_list .com_job_item {
  width: 115px;
}
.com_job_list .com_job_item .item_text_1{
  color:#0f0f0f ;
  font-size: 15px;
  max-width: 110px;
}
.com_job_list .com_job_item .item_text_2{
  color:#ff6000 ;
  font-size: 14px;
  max-width: 110px;
  margin: 5px 0;
}
.com_job_list .com_job_item .item_text_3{
  color:#777777 ;
  max-width: 110px;
  font-size: 12px;
}

.com_info .share_code{
  width: 243.5px;
  height: 77.5px;
  display: flex;
  border: 1px solid #e5e5e5;
  justify-content:space-between;
  border-radius: 15px;
  margin: 0 auto 12.5px;
  padding: 7.5px 9px 7.5px 12.5px;
}
.share_code .share_text{
  width: 150px;
  color:#8d8d8d ;
  font-size: 13px;
}
.share_code .share_text .s_text_1{
  margin: 10px 0;
}

.share_code .share_img{
  width: 61px;
  height: 61px;
  position: relative;
}
.share_code .share_img img{
  max-width: 60px;
  max-height: 60px;
  position: absolute;
  left: 0;
  right: 0;
  bottom: 0;
  top: 0;
  margin: auto;
}

.com_info .qs_text{
  text-align: center;
  color: #d1d1d1;
  font-size:13px;
}
</style>
