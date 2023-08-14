<template>
  <div>
    <div class="bg">
      <h2 class="res_title_2">
          伯乐识良马<br />
          明主觅良才<br />
          您的选择肯定没错<br />
        </h2>
        <div class="info">
          <div class="user_info">
            <div class="user_bg_1"></div>
            <div class="user_bg_2"></div>
            <div class="user_img">
              <img :src="info.photo" alt="">
            </div>
            <div class="user_name substring">{{info.fullname}}</div>
            <div class="oth_info substring">
              <span class="oth_item">{{info.age}}岁</span>
              <span class="oth_item">{{info.education}}</span>
              <span class="oth_item">{{info.experience}}</span>
            </div>
            <div class="job_demand">
              <p class="demand_1">
                想找<span class="demand_color">{{info.intention_jobs}}</span>的工作
              </p>
              <p class="demand_2">
                想在<span class="demand_color">{{info.intention_district}}</span>工作
              </p>
            </div>
          </div>
          <img src="../../../assets/images/share/new/24.png" alt="" class="line">
          <div class="share_code">
            <div class="share_text_1">长按识别二维码</div>
            <div class="share_text_2">立即查看我的简历信息</div>
            <div class="code_img">
              <img :src="qrcode" alt="">
            </div>
          </div>
          <p class="share_text_3">{{$store.state.config.sitename}}</p>
        </div>
  </div>
  </div>
</template>

<script>
export default {
  name: 'ShareResumeThree',
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
  background: url(../../../assets/images/share/new/3.jpg) no-repeat top center / 100% 100%;
  width: 321px;
  height: 536px;
  position: relative;
}

.res_title_2 {
  color: #ffffff;
  font-size: 29px;
  position: absolute;
  left: 32.5px;
  top: 25px;
  line-height: 40px;
}

.info {
  width: 276.5px;
  height: 334px;
  position: absolute;
  bottom: 20px;
  left: 50%;
  transform: translateX(-50%);
  border-radius: 15px;
}

.info .line {
  width: 236.5px;
  height: 36.5px;
  position: absolute;
  top:175.5px;
  left: 50%;
  transform: translateX(-50%);
  z-index: 99;
}

.info .user_info {
  height: 191px;
  position: relative;
  padding: 24px 18px 37px 18px;
  background-color: #fff;
  border-radius: 15px;
}

.user_info .user_bg_1 {
  width: 252.5px;
  height: 5.5px;
  background: rgba(225, 225, 225, 0.2);
  position: absolute;
  top: -5.5px;
  left: 50%;
  transform: translateX(-50%);
  border-radius: 5px 5px 0 0;
  z-index: 55;
}

.user_info .user_bg_2 {
  width: 231.5px;
  height: 12.5px;
  background: rgba(225, 225, 225, 0.2);
  position: absolute;
  top: -12.5px;
  left: 50%;
  transform: translateX(-50%);
  border-radius: 10px 10px 0 0;
}

.user_info .user_img {
  width: 60px;
  height: 60px;
  border-radius: 50%;
  position: absolute;
  left: 16px;
  top: 24.5px;
}

.user_info .user_img img {
  position: absolute;
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
  font-size: 16px;
  font-weight: bold;
  margin: 5px 0 7px 70px;
}

.user_info .oth_info {
  color: #999999;
  font-size: 0;
  margin-left: 70px;
}

.user_info .oth_info .oth_item {
  font-size: 13px;
  margin-right: 13.5px;
  position: relative;
}

.user_info .oth_info .oth_item::before {
  content: "";
  width: 3.5px;
  height: 3.5px;
  border-radius: 50%;
  background-color: #c9c9c9;
  position: absolute;
  right: -7.5px;
  top: 50%;
  transform: translateY(-50%);
}

.user_info .oth_info .oth_item:last-child:before {
  display: none;
}
.user_info .intent {
  margin: 17.5px 0 15px -18.5px;
  color: #fefffd;
  font-size: 14px;
  line-height: 25px;
  height: 26px;
  width: 101.5px;
  background-color: #a35e63;
  padding-left: 18.5px;
}

.user_info .job_demand {
  margin-top: 20px;
}

.user_info .demand_1,
.user_info .demand_2 {
  color: #999999;
  font-size:14px;
  margin-left: 8.5px;
}
.user_info .demand_1 {
  margin-bottom: 5px;
  overflow: hidden;
  text-overflow: ellipsis;
  -webkit-line-clamp: 2;
  -webkit-box-orient: vertical;
}

.user_info .demand_1 .demand_color,
.user_info .demand_2 .demand_color {
  color: #ff6600;
  margin: 0 5px;
}

.info .share_code {
  background-color: #fff;
  height: 97.5px;
  padding: 30px 17.5px 14px 16px;
  position: relative;
  border-radius: 15px;
  margin-top: 11px;
}

.info .share_code .share_text_1,
.info .share_code .share_text_2 {
  color: #807474;
  font-size: 14px;
  max-width: 200px;
}

.info .share_code .share_text_1 {
  margin-bottom: 2px;
}

.info .share_code .code_img {
  width: 58.5px;
  height: 58.5px;
  position: absolute;
  right:17.5px;
  top: 20px;
}
.info .share_code .code_img img {
  max-width: 58.5px;
  max-height: 58.5px;
  position: absolute;
  left: 0;
  right: 0;
  top: 0;
  bottom: 0;
  margin: auto;
}

.info .share_text_3 {
  text-align: center;
  color: #fefffd;
  font-size: 13px;
  margin-top: 17.5px;
  font-size: 14px;
}

</style>
