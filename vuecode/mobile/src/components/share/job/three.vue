<template>
  <div>
    <div class="bg">
      <h2 class="com_title_1">
        我们什么都有<br />
        就是这些地方还缺你<br />
        这里有属于你的一片天<br />
      </h2>
      <div class="job_poster">
        <img src="../../../assets/images/share/new/18.png" alt="" class="img_left">
        <img src="../../../assets/images/share/new/19.png" alt="" class="img_right">
        <span class="job_icon_1"></span>
        <span class="job_icon_2"></span>
        <div class="com_box">
          <p class="job_salary substring">{{info.wage}}/月</p>
          <p class="job_name substring">{{info.jobname}}</p>
          <p class="job_oth substring">
            <span class="oth_item">{{info.nature}}</span>
            <span class="oth_item">{{info.education}}</span>
            <span class="oth_item">{{info.experience}}</span>
          </p>
        </div>
        <div class="line"></div>
        <div class="share_code">
          <p class="com_name substring">{{info.companyname}}</p>
          <p class="share_info">长按识别查看职位详情</p>
          <p class="qs_text">{{$store.state.config.sitename}}</p>
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
  name: 'ShareJobThree',
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
  background: url(../../../assets/images/share/new/17.jpg) no-repeat top center / 100% 100%;
  width: 321px;
  height: 536px;
  position: relative;
  overflow:hidden;
}

.com_title_1 {
  color: #ffffff;
  font-size: 27px;
  position: absolute;
  left: 30px;
  top: 40px;
  line-height: 50px;
}

.job_poster {
  width: 300px;
  height: 290.5px;
  position: absolute;
  bottom: 28px;
  left: 50%;
  transform: translateX(-50%);
  border-radius: 15px;
  background-color: #fff;
}

.job_poster::before {
  content: "";
  border: 9.5px solid transparent;
  border-bottom: 9.5px solid #fff;
  position: absolute;
  left: 50%;
  transform: translateX(-50%);
  top: -19px;
}

.job_poster .img_left {
  width: 51px;
  height: 111px;
  position: absolute;
  left: -18px;
  z-index: 22;
  top: 96px;
}
.job_poster .img_right {
  width: 65.5px;
  height: 95.5px;
  position: absolute;
  right: -18px;
  top: 94px;
  z-index: 22;
}

.job_poster .job_icon_1,
.job_poster .job_icon_2 {
  width: 13.5px;
  height:13.5px;
  background-color: #2b235c;
  position: absolute;
  top: 194.5px;
  border-radius: 50%;
}

.job_poster .job_icon_1 {
  left: -0.67px;
}

.job_poster .job_icon_2 {
  right: -0.67px;
  background-color: #453087;
}

.job_poster .com_box {
  padding: 25px 0 0 34.5px;
}
.com_box .job_salary {
  color: #ff6000;
  margin-bottom: 10px;
  font-size: 25px;
}
.com_box .job_name {
  color: #0f0f0f;
  font-size: 25px;
  margin-bottom: 12.5px;
  font-weight: 600;
  max-width:200px ;
}

.com_box .job_oth {
  color: #777777;
  margin-bottom: 30px;
  font-size: 0;
}

.com_box .job_oth .oth_item {
  font-size: 15px;
  margin-right: 15px;
  position: relative;
}

.com_box .job_oth .oth_item::before {
  content: "";
  width: 3.5px;
  height: 3.5px;
  background-color: #c9c9c9;
  border-radius: 50%;
  position: absolute;
  right: -8.5px;
  top: 50%;
  transform: translateY(-50%);
}
.com_box .job_oth .oth_item:last-child {
  margin: 0;
}
.com_box .job_oth .oth_item:last-child:before {
  display: none;
}

.job_poster .line {
  width:264px;
  border-bottom: 3px dotted #c1c1c1;
  margin: 0 0 16.5px 26px;
}

.job_poster .share_code {
  margin: 0 22px 0 25px;
  position: relative;
}

.share_code .com_name{
  color:#555555 ;
  font-size:17px;
  max-width: 150px;
  margin-bottom:10px;
}
.share_code .share_info{
  width: 159.5px;
  height: 20px;
  line-height:22.5px;
  color:#ffffff;
  font-size: 13px;
  background-color: #363280;
  border-radius:15px ;
  text-align: center;
}

.share_code .qs_text{
text-align: center;
color: #c1c1c1;
font-size: 15px;
margin-top: 17.5px;
}

.share_code .share_img{
  position: absolute;
  right: 0;
  top: 0;
  width:61.5px;
  height: 61.5px;
}
.share_code .share_img img{
  width: 60px;
  height: 60px;
  position: absolute;
  left: 0;
  right: 0;
  top: 0;
  bottom: 0;
  margin: auto;
}

</style>
