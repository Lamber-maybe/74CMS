<template>
  <div>
    <div class="bg">
      <p class="com_title_1">{{$store.state.config.sitename}}</p>
      <h2 class="com_title_2">
        英雄请留步<br />
        我们有个职位<br />
        想和你谈谈<br />
        高薪厚职等你来
      </h2>
      <div class="job_poster">
        <div class="com_box">
          <p class="job_name substring">{{info.jobname}}</p>
          <p class="job_salary substring">{{info.wage}}/月</p>
          <p class="job_oth substring">
            <span class="oth_item">{{info.nature}}</span>
            <span class="oth_item">{{info.education}}</span>
            <span class="oth_item">{{info.experience}}</span>
          </p>
          <p class="job_address substring">{{info.companyname}}</p>
        </div>
        <div class="share_code">
          <img :src="qrcode" alt="">
        </div>
        <p class="job_text">
          <img src="../../../assets/images/share/new/15.png" alt="">
          <span class="info_text">长按识别 查看职位详情</span>
          <img src="../../../assets/images/share/new/16.png" alt="">
        </p>
      </div>
      </div>
  </div>
</template>

<script>
export default {
  name: 'ShareJobTwo',
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
  background: url(../../../assets/images/share/new/14.jpg) no-repeat top center / 100% 100%;
  width: 321px;
  height: 536px;
  position: relative;
}
.com_title_1 {
  color: #ffffff;
  font-size: 15px;
  position: absolute;
  left: 18.5px;
  top: 22px;
}

.com_title_2 {
  color: #ffffff;
  font-size: 32px;
  position: absolute;
  left: 30px;
  top: 80px;
  line-height: 50px;
}

.job_poster {
  width: 100%;
  height: 218.5px;
  position: absolute;
  bottom: 0rem;
  left: 0;
  right: 0;
  background-color: #fff;
  border-radius: 15px 15px 0 0;
}

.job_poster .com_box {
  padding: 22px 0 0 25px;
  max-width: 275px;
}

.com_box .job_name {
  color: #0f0f0f;
  font-size: 25px;
  margin-bottom: 17.5px;
  font-weight: 600;
}

.com_box .job_salary {
  color: #ff6000;
  margin-bottom: 15px;
  font-size: 20px;
}

.com_box .job_oth {
  color: #777777;
  margin-bottom: 15px;
  font-size: 0;
}

.com_box .job_oth .oth_item {
  font-size: 15px;
  margin-right: 15px;
  position: relative;
}

.com_box .job_oth .oth_item::before{
  content: '';
  width: 1px;
  height: 12px;
  background-color: #dadada;
  position: absolute;
  right: -7.5px;
  top: 50%;
  transform: translateY(-50%);
}
.com_box .job_oth .oth_item:last-child{
  margin:  0 ;
}
.com_box .job_oth .oth_item:last-child:before{
  display: none;
}

.com_box  .job_address{
  color: #555555;
  font-size: 15px;
}

.share_code {
  width: 61.5px;
  height: 61.5px;
  margin: 0 auto 10px;
  position: absolute;
  right: 25px;
  top: 65px;
}

.share_code img {
  position: absolute;
  left: 0;
  right: 0;
  bottom: 0;
  top: 0;
  margin: auto;
  max-width: 60px;
  max-height: 60px;
}

.job_text{
  color: #999999;
  font-size: 13px;
  display: flex;
  justify-content: center;
  align-items: center;
  margin-top: 20px;
}

.job_text .info_text{
  margin: 0 12.5px;
}

.job_text img{
  width: 11.5px;
  height: 13px;
}
</style>
