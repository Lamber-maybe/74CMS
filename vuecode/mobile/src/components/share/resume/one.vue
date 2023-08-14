<template>
  <div>
    <div class="bg">
      <p class="res_title_1">{{$store.state.config.sitename}}</p>
      <h2 class="res_title_2">我有能力<br />您有空间<br />就差一个机会了</h2>
      <div class="info">
        <div class="user_name substring">{{info.fullname}}</div>
        <div class="oth_info substring">
          <span class="oth_item">{{info.age}}岁</span>
          <span class="oth_item">{{info.education}}</span>
          <span class="oth_item">{{info.experience}}</span>
        </div>
        <div class="info_text_1 substring">{{info.current}}</div>

        <div class="info_job">
          想找<span class="job_color">{{info.intention_jobs}}</span>的工作
        </div>

        <div class="info_region">
          想在 <span class="region_color">{{info.intention_district}}</span> 工作
        </div>

        <div class="user_img"><img :src="info.photo" /></div>

        <div class="res_code">
          <div>长按识别二维码，查看我的简历详情</div>
          <div class="code_box">
            <img :src="qrcode" alt="">
          </div>
        </div>
      </div>
  </div>
  </div>
</template>

<script>
export default {
  name: 'ShareResumeOne',
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
  background: url(../../../assets/images/share/new/1.png) no-repeat top center / 100% 100%;
  width: 321px;
  height: 536px;
  position: relative;
}

.res_title_1 {
  color: #ffffff;
  font-size: 15px;
  position: absolute;
  left: 23.5px;
  top: 23.5px;
}
.res_title_2 {
  color: #ffffff;
  font-size: 30px;
  position: absolute;
  left: 27.5px;
  top: 75px;
  line-height: 50px;
}

.info {
  width: 100%;
  height: 270px;
  background-color: #fff;
  position: absolute;
  bottom: 0;
  left: 0;
  right: 0;
  border-radius: 15px 15px 0 0;
  padding: 25px 17px 0;
}
.info .user_name {
  color: #0f0f0f;
  font-size: 20px;
  font-weight: bold;
  margin-bottom: 2px;
  max-width: 250px;
}

.info .oth_info {
  color: #999999;
  margin-bottom: 2px;
  font-size: 0;
  max-width: 200px;
}

.info .oth_info .oth_item {
  font-size: 14px;
  margin-right: 13.5px;
  position: relative;
}

.info .oth_info .oth_item::before{
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

.info .oth_info .oth_item:last-child:before{
  display: none;
}

.info .info_text_1{
  color:#999999 ;
  font-size: 14px;
  margin-bottom: 8px;
  max-width: 215px;
}

.info .info_job,
.info .info_region{
  color:#999999 ;
  font-size: 15px;
}

.info .info_job{
  margin-bottom: 7px;
  overflow: hidden;
  text-overflow: ellipsis;
  -webkit-line-clamp: 2;
  -webkit-box-orient: vertical;
}

.info .info_region{
  margin-bottom: 20px;
  overflow: hidden;
  white-space: nowrap;
  text-overflow: ellipsis;
}

.info .user_img{
  width: 69px;
  height: 69px;
  position: absolute;
  right: 20px;
  top: 25px;
  border: 1px solid #ccc;
  border-radius: 50%;
}

.info .user_img img{
  max-width: 69px;
  height: 69px;
  position: absolute;
  left: 0;
  right: 0;
  top: 0;
  bottom: 0;
  margin: auto;
}

.info .info_job .job_color,
.info .info_region .region_color{
  color: #ff6600;
  margin: 0 5px;
}

.info .res_code {
  width: 280px;
  height: 37.5px;
  line-height: 37.5px;
  border: 1px solid #3d2069;
  border-radius: 10px;
  color: #3d2069;
  font-size: 13px;
  padding-left: 8px;
  position: relative;
}
.info .res_code .code_box {
  width: 67.5px;
  height: 67.5px;
  border: 1px solid #3d2069;
  position: absolute;
  right: -14px;
  top: -15px;
  background-color: #ffffff;
}
.info .res_code .code_box img {
  max-width: 65px;
  max-height: 65px;
  position: absolute;
  left: 0;
  top: 0;
  bottom: 0;
  right: 0;
  margin: auto;
}
</style>
