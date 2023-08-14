<template>
  <div id="app">
      <div class="analysis_box" v-if="!analysis_finish">
      <Head>智能分析中</Head>
      <div class="box_1" v-if="show">
        <div class="item over" :class="'item'+parseInt(index+1)" v-for="(item,index) in module_list" :key="index">
          {{item}}
          <div class="txt">完成</div>
        </div>
      </div>
      <div class="box_2">根据您的简历完整度</div>
      <div class="box_2 pd">正在为您计算求职竞争力</div>
      <div class="box_3">
        <van-slider
          v-model="percent"
          bar-height="8px"
          active-color="#3196ff"
          inactive-color="#cce5ff"
          button-size="16px"
        />
      </div>
    </div>
    <div class="finish_box" v-if="analysis_finish">
      <Head>创建完成</Head>
      <div class="top_bg"></div>
      <div class="txt_1">您的求职竞争力 <span class="level_low">较低</span></div>
      <div class="txt_2">请继续完善简历，助您快速高效面试</div>
      <div style="margin: 23px;">
        <van-button round block type="info" @click="$router.push('/member/personal/resume')">继续完善</van-button>
      </div>
      <div style="margin: 23px;">
        <van-button plain hairline round block type="info" @click="$router.push('/member/personal/index')">会员中心</van-button>
      </div>
    </div>
  </div>
</template>

<script>
import http from '@/utils/http'
import api from '@/api'
export default {
  name: 'AddResumeFinish',
  data () {
    return {
      show: false,
      analysis_finish: false,
      percent: 0,
      module_list: []
    }
  },
  created () {
    this.initInfo()
  },
  methods: {
    initInfo () {
      http.get(api.resume_complete_info, {})
        .then(res => {
          this.module_list = res.data.module
          this.show = true
          setTimeout(() => {
            this.handlerPercent()
          }, 1200)
        })
        .catch(() => {})
    },
    handlerPercent () {
      let that = this
      let plus = 80
      let t = setInterval(function () {
        plus = plus / 2
        if (plus <= 1) {
          plus = 1
        }
        that.percent += plus
        if (that.percent >= 100) {
          clearInterval(t)
          that.analysis_finish = true
        }
      }, 100)
    }
  }
}
</script>

<style lang="scss" scoped>
@keyframes leftIn {
  from {
    transform: translateX(-150px);
    opacity: 0;
  }
  to {
    transform: translateX(0px);
    opacity: 1;
  }
}
.analysis_box{
  .van-slider__button {
    background-color: #1787fb;
  }
  .box_3 {
    width: 255px;
    margin: 0 auto;
  }
  .box_2 {
    font-size: 16px;
    font-weight: bold;
    color: #333333;
    text-align: center;
    &.pd {
      padding: 10px 0 35px;
    }
  }
  .box_1 {
    .item {
      &.item1{
        animation: leftIn 0.3s ease;
      }
      &.item2{
        animation: leftIn 0.8s ease;
      }
      &.item3{
        animation: leftIn 1.2s ease;
      }
      &.item4{
        animation: leftIn 1.2s ease;
      }
      &.item5{
        animation: leftIn 1.2s ease;
      }
      &.item6{
        animation: leftIn 1.2s ease;
      }
      &.ing {
        box-shadow: 0 0 5px #e2ebff;
      }
      &.over {
        &::before {
          background-color: #1787fb;
          border: 2px solid #c5e1fe;
        }
        &::after {
          content: " ";
        }
      }
      &::after {
        position: absolute;
        right: 40px;
        top: 11px;
        width: 17px;
        height: 17px;
        background: url("../../assets/images/over_ico_green.svg") 0 no-repeat;
        background-size: 17px;
      }
      .txt {
        position: absolute;
        right: 70px;
        top: 12px;
        font-size: 15px;
        color: #333333;
      }
      &::before {
        content: " ";
        position: absolute;
        left: 39px;
        top: 50%;
        transform: translate(0, -50%);
        width: 5px;
        height: 5px;
        border-radius: 100%;
        background-color: #999999;
        border: 2px solid #e5e5e5;
      }
      position: relative;
      width: 305px;
      margin: 0 auto;
      border-radius: 5px;
      font-size: 15px;
      color: #333333;
      padding: 12px 0 12px 57px;
    }
    width: 100%;
    padding-top: 56px;
    margin-bottom: 71px;
  }
}
.finish_box{
  .top_bg {
    width: 100%;
    height: 178px;
    background: url("../../assets/images/add_resume_finish_bg.png") center 53px
      no-repeat;
    background-size: 105.5px 102.5px;
  }
  .txt_1 {
    text-align: center;
    font-size: 18px;
    color: #333333;
    font-weight: bold;
    .level_low {
      color: #ff5d24;
    }
  }
  .txt_2 {
    text-align: center;
    font-size: 18px;
    color: #666666;
    padding: 15px 0 35px;
  }
}

</style>
