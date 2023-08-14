<template>
  <div>
    <div class="bg">
      <p class="com_title_1">{{$store.state.config.sitename}}</p>
        <h2 class="com_title_2">
          世界那么大<br />
          你不来<br />
          怎么知道你牛逼<br />
          有胆你就来
        </h2>
        <div class=" com_info">
          <div class="com_box">
            <span class="info_icon_1"></span>
            <span class="info_icon_2"></span>
            <div class="com_info_text">
              <p class="i_text_1 substring">{{info.companyname}}</p>
              <p class="i_text_2 substring">{{info.address}}</p>
            </div>
            <div class="line"></div>
            <div class="com_list">
              <div v-for="(item,index) in info.joblist" :key="index">
                <div class="com_item" v-if="index<2">
                  <p class="ite_text_1 substring">{{item.jobname}}</p>
                  <p class="ite_text_2 substring">{{item.wage_text}}</p>
                  <p class="ite_text_3 substring">
                    <span class="ite_3_item">{{item.nature_text}}</span>
                    <span class="ite_3_item">{{item.education_text}}</span>
                    <span class="ite_3_item">{{item.experience_text}}</span>
                  </p>
                </div>
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
        </div>
  </div>
  </div>
</template>

<script>
export default {
  name: 'ShareCompanyThree',
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
  background: url(../../../assets/images/share/new/9.jpg) no-repeat top center / 100% 100%;
  width: 321px;
  height: 536px;
  position: relative;
}

.com_title_1 {
  color: #ffffff;
  font-size: 15px;
  position: absolute;
  left: 23.5px;
  top: 20px;
}

.com_title_2 {
  color: #ffffff;
  font-size: 30px;
  position: absolute;
  left: 29.5px;
  top: 50px;
}

.com_info {
  width: 280px;
  height: 289px;
  position: absolute;
  bottom: 20px;
  left: 50%;
  transform: translateX(-50%);
}
.com_info .com_box {
  height: 195px;
  background: url(../../../assets/images/share/new/10.jpg) no-repeat center center / 330px 195px;
  margin-bottom: 16.5px;
}

.com_info .info_icon_1,
.com_info .info_icon_2 {
  width: 10px;
  height: 10px;
  border-radius: 50%;
  background: #1e3866;
  position: absolute;
  top: 79.5px;
}

.com_info .info_icon_1 {
  left: -5px;
}
.com_info .info_icon_2 {
  right: -5px;
}

.com_box .com_info_text {
  padding: 21px 18.5px 16.5px 17.5px;
}

.com_box .com_info_text .i_text_1 {
  color: #0f0f0f;
  font-size:17.5px;
  margin-bottom: 5px;
}

.com_box .com_info_text .i_text_2 {
  color: #999999;
  font-size: 12px;
}

.com_box .com_info_img {
  width: 57.5px;
  height: 57.5px;
  border: 1px solid #ebebeb;
  border-radius: 15px;
}

.com_info .line {
  width: 254.5px;
  border-bottom: 1px dotted #ccc;
  margin: 0 auto;
}
.com_box .com_list {
  display: flex;
  justify-content: space-between;
  margin: 15px 18px 0;
}
.com_list .com_item {
  width: 120px;
  margin-right: 10px;
}

.com_list .com_item .ite_text_1 {
  color: #0f0f0f;
  font-size: 15px;
  margin-bottom: 7.5px;
}
.com_list .com_item .ite_text_2 {
  color: #ff6000;
  margin-bottom:7.5px;
  font-size: 14px;
}
.com_list .com_item .ite_text_3 {
  color: #777777;
  font-size: 0;
}

.com_list .com_item .ite_text_3 .ite_3_item {
  font-size: 12px;
  margin-right: 11.5px;
  position: relative;
}
.com_list .com_item .ite_text_3 .ite_3_item::after {
  content: "";
  width: 3px;
  height: 3px;
  background-color: #c9c9c9;
  border-radius: 50%;
  position: absolute;
  right: -7.5px;
  top: 50%;
  transform: translateY(-50%);
}

.com_list .com_item .ite_text_3 .ite_3_item:last-child:after {
  display: none;
}

.com_info .share_code {
  width: 280px;
  height: 77.5px;
  display: flex;
  justify-content: space-between;
}
.share_code .share_text {
  width: 183.5px;
  color: #999999;
  font-size: 13px;
  background-color: #fff;
  border-radius: 7.5px;
  padding: 17.5px 12.5px;
  position: relative;
}

.share_code .share_text::before{
  content: '';
  border: 7.5px solid transparent;
  border-left: 7.5px solid #fff;
  position: absolute;
  top: 26.5px;
  right: -14px;
}

.share_code .share_text .s_text_1 {
  margin: 0rem 0 10px;
}

.share_code .share_img {
  width: 76.5px;
  height: 76.5px;
  border-radius: 7.5px;
  position: relative;
  background-color: #fff;
}
.share_code .share_img img {
  max-width: 60px;
  max-height: 60px;
  position: absolute;
  left: 0;
  right: 0;
  bottom: 0;
  top: 0;
  margin: auto;
}

.com_popup_box .res_btns {
  display: flex;
  justify-content: space-between;
  margin-top: 18.5px;
}
</style>
