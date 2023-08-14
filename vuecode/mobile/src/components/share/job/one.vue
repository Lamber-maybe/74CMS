<template>
  <div>
    <div class="bg">
      <h2 class="com_title_1">
        大神留步<br />
        全程招募职等你来<br />
      </h2>
      <div class=" job_poster">
        <div class="com_box">
          <h3 class="com_name substring">{{info.companyname}}</h3>
          <p class="job_name substring">{{info.jobname}}</p>
          <p class="job_salary">{{info.wage}}/月</p>
          <p class="job_oth">
            <span class="oth_item">{{info.nature}}</span>
            <span class="oth_item">{{info.education}}</span>
            <span class="oth_item">{{info.experience}}</span>
          </p>
          <p class="job_address">{{info.district}}</p>
        </div>
        <div class="com_line"></div>
        <div class="com_share_code">
          <div class="share_code">
            <img :src="qrcode" alt="">
          </div>
          <p class="share_text">长按识别，查看职位详情</p>
        </div>
        <p class="qs_text">{{$store.state.config.sitename}}</p>
      </div>
  </div>
  </div>
</template>

<script>
export default {
  name: 'ShareJobOne',
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
    background: url(../../../assets/images/share/new/11.jpg) no-repeat top center / 100% 100%;
    width: 321px;
    height: 536px;
    position: relative;
  }
  .com_title_1 {
    color: #ffffff;
    font-size: 29px;
    position: absolute;
    left: 29.5px;
    top:60px;
    line-height: 40px;
  }

  .job_poster {
    width: 280px;
    height: 293.5px;
    position: absolute;
    top: 200px;
    left: 50%;
    transform: translateX(-50%);
    background: url(../../../assets/images/share/new/12.png) no-repeat top center / 280px 293px;
  }

  .job_poster .com_box {
    text-align: center;
    padding: 15px 0 15px;
  }

  .com_box .com_name {
    color: #0f0f0f;
    font-size: 15px;
    font-weight: normal;
    max-width: 250px;
    margin: 0 auto 10px;
  }

  .com_box .job_name {
    color: #0f0f0f;
    font-size: 22px;
    max-width: 250px;
    font-weight: 600;
    margin: 0 auto 5px;
  }

  .com_box .job_salary {
    color: #ff6000;
    margin-bottom: 10px;
    font-size: 20px;
  }

  .com_box .job_oth {
    color: #777777;
    margin-bottom: 5px;
    font-size: 0;
  }

  .com_box .job_oth .oth_item {
    font-size:13px;
    margin-right: 15px;
    position: relative;
  }

  .com_box .job_oth .oth_item::before{
    content: '';
    width: 3.5px;
    height: 3.5px;
    border-radius: 50%;
    background-color: #c9c9c9;
    position: absolute;
    right: -10px;
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
    color: #777777;
    font-size: 13px;
  }

  .job_poster .com_line {
    width: 258px;
    height: 5px;
    background: url(../../../assets/images/share/new/13.png) no-repeat center center /258px 5px;
    margin: 0 auto 8px;
  }

  .com_share_code {
    text-align: center;
  }

  .com_share_code .share_code {
    width: 61.5px;
    height: 61.5px;
    margin: 0 auto 5px;
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

  .com_share_code .share_text{
    text-align: center;
    color:#999999 ;
    font-size:13px ;
  }

  .job_poster .qs_text {
    color: #ffffff;
    font-size: 13px;
    position: absolute;
    bottom: -30px;
    left: 50%;
    transform: translateX(-50%);
  }
</style>
