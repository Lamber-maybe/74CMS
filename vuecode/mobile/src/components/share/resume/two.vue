<template>
  <div>
    <div class="bg">
      <div class="res_icon">
          <span></span>
          <span></span>
          <span></span>
        </div>
        <h2 class="res_title_2">
          给我一个机会<br />
          必会还您一个惊喜<br />
          给我一缕春风<br />
          送给你的将是整个春天
        </h2>
        <img src="../../../assets/images/share/new/23.png" alt="" class="res_plane">
        <div class="info">
          <span class="info_icon_1"></span>
          <span class="info_icon_2"></span>
          <div class="user_info">
            <div class="user_img">
              <img :src="info.photo" alt="">
            </div>
            <div class="user_name substring">{{info.fullname}}</div>
            <div class="oth_info substring">
              <span class="oth_item">{{info.age}}岁</span>
              <span class="oth_item">{{info.education}}</span>
              <span class="oth_item">{{info.experience}}</span>
            </div>
            <div class="intent">求职意向</div>
            <div class="job_demand">
              <p class="demand_1">
                想找<span class="demand_color">{{info.intention_jobs}}</span>的工作
              </p>
              <p class="demand_2">
                想在<span class="demand_color">{{info.intention_district}}</span>工作
              </p>
            </div>
          </div>
          <div class="share_code">
            <div class="share_text_1">长按识别二维码</div>
            <div class="share_text_2">立即查看我的简历信息</div>
            <div class="code_img">
              <img :src="qrcode" alt="">
            </div>
            <p class="share_text_3">{{$store.state.config.sitename}}</p>
          </div>
        </div>
      </div>
  </div>
</template>

<script>
export default {
  name: 'ShareResumeTwo',
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
  background: url(../../../assets/images/share/new/22.jpg) no-repeat top center / 100% 100%;
  width: 321px;
  height: 536px;
  position: relative;
}

.res_icon{
  position: absolute;
  right: 14px;
  top: 7px;
}
.res_icon span{
  display: block;
  width:3.5px;
  height: 3.5px;
  background-color: #fff;
  margin: 5px auto 0;
  border-radius: 50%;
}

.res_title_2 {
  color: #ffffff;
  font-size: 25px;
  position: absolute;
  left: 32.5px;
  top: 40px;
  line-height: 37.5px;
}
.res_plane{
  width: 60px;
  height: 60px;
  position: absolute;
  top: 185px;
  left: 225px;
}

.info {
  width: 278.5px;
  height: 292.5px;
  background-color: #fff;
  position: absolute;
  bottom: 20px;
  left: 50%;
  transform: translateX(-50%);
  border-radius: 15px ;
}

.info .info_icon_1,
.info .info_icon_2{
  width: 10px;
  height: 10px;
  border-radius: 50%;
  background: #0e0b40;
  position: absolute;
  top: 194px;
}

.info .info_icon_1{
  left: -5px;
}
.info .info_icon_2{
  right: -5px;
}

.info .user_info{
  margin: 0 10px;
  border-bottom: 1.5px dashed #e6e6e6;
  position: relative;
  padding:17px 0 20px 0;
}

.user_info .user_img{
  width: 60px;
  height: 60px;
  border-radius: 50%;
  position: absolute;
  left: 0;
  top:17px;
}

.user_info .user_img img{
  position:absolute;
  left: 0;
  right: 0;
  top: 0;
  bottom: 0;
  margin: auto;
  max-width: 60px;
  max-height: 60px;
  border-radius: 50%;
}

.user_info .user_name {
  color: #0f0f0f;
  font-size: 17px;
  font-weight: bold;
  margin: 5px 0 7.5px 75px;
}

.user_info .oth_info {
  color: #999999;
  font-size: 0;
  margin-left: 75px;
}

.user_info .oth_info .oth_item {
  font-size: 13px;
  margin-right: 13.5px;
  position: relative;
}

.user_info .oth_info .oth_item::before{
  content: '';
  width: 3.5px;
  height: 3.5px;
  border-radius: 50%;
  background-color: #c9c9c9;
  position: absolute;
  right: -7.5px;
  top: 50%;
  transform: translateY(-50%);
}

.user_info .oth_info .oth_item:last-child:before{
  display: none;
}
.user_info .intent{
  margin: 15px 0 15px -18.5px;
  color:#fefffd ;
  font-size: 14px;
  line-height: 27.5px;
  height: 26px;
  width: 101.5px;
  background: url(../../../assets/images/share/new/21.png) no-repeat center center /101.5px 23px;
  padding-left: 18.5px;
}

.user_info  .demand_1,
.user_info  .demand_2{
  color:#999999 ;
  font-size:14px;
  margin-left: 8.5px;
}
.user_info  .demand_1{
  margin-bottom: 5px;
}

.user_info  .demand_1 .demand_color,
.user_info  .demand_2 .demand_color{
  color: #ff6600;
  margin: 0 5px;
}

.info .share_code{
  padding: 15px 24px 0px 17.5px;
  position: relative;
}

.info .share_code .share_text_1,
.info .share_code .share_text_2{
  color: #2a2850;
  font-size: 14px;
  max-width: 200px;
}

.info .share_code .share_text_1{
  margin-bottom: 5px;
}

.info .share_code .code_img{
  width: 58.5px;
  height: 58.5px;
  position: absolute;
  right: 24px;
  top: 10px;
}
.info .share_code .code_img img{
  max-width:57.5px;
  max-height: 57.5px;
  position: absolute;
  left: 0;
  right: 0;
  top: 0;
  bottom: 0;
  margin:  auto;
}

.info .share_code .share_text_3{
  text-align: center;
  color:#afafaf ;
  font-size: 13px;
  margin-top: 15px;
}
</style>
