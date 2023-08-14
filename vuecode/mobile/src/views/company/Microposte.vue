<template>
  <div id="app">
    <Head :goback_custom="true" @gobackCustomMethod="goPrev">微海报</Head>
    <div class="form_split_10"></div>
    <van-steps class="step_box" :active="active" active-icon="success" active-color="#38f">
      <van-step>选择展示职位</van-step>
      <van-step>选择海报模板</van-step>
      <van-step>长按保存海报</van-step>
    </van-steps>
    <div v-if="active===0">
      <div class="form_split_title">选择职位<div class="sub">最多可选8个</div></div>
      <van-checkbox-group v-model="result" :max="8">
        <van-cell-group>
          <van-cell
            v-for="(item, index) in joblist"
            clickable
            :key="index"
            :title="item.jobname"
            @click="toggle(index)"
          >
            <template #right-icon>
              <van-checkbox :name="item.id" ref="checkboxes" />
            </template>
          </van-cell>
        </van-cell-group>
      </van-checkbox-group>
      <div style="margin: 16px;">
        <van-button round block type="info" native-type="button" @click="handlerSubmit">
          下一步
        </van-button>
      </div>
    </div>
    <div v-if="active===1">
      <div class="form_split_title">选择模板<div class="sub">点击模板立即使用</div></div>
      <div class="whb_template">
        <div class="whb_template_content">
          <ul>
              <li v-for="(item,index) in tpllist" :key="index" @click="goStep3(item)">
                <div class="whb_template_img">
                  <img :src="require('../../assets/images/posteimages/'+item.thumb+'_bg.jpg')">
                </div>
                <div class="whb_template_text">
                  {{item.name}}
                </div>
              </li>
          </ul>
        </div>
      </div>
    </div>
    <div v-if="active===2">
      <microposteResult :tpl="tpl" :jobidarr="result"></microposteResult>
    </div>
  </div>
</template>

<script>
import microposteResult from '@/components/microposte/index'
import http from '@/utils/http'
import api from '@/api'
export default {
  name: 'Microposte',
  components: {
    microposteResult
  },
  data () {
    return {
      tpl: '',
      joblist: [],
      result: [],
      tpllist: [],
      active: 0
    }
  },
  created () {
    if (this.$route.query.jobid !== undefined) {
      this.result.push(this.$route.query.jobid)
      this.goStep2()
      return false
    }
    this.fetchData()
  },
  methods: {
    fetchData () {
      http.get(api.company_published_jobslist, {}).then(res => {
        this.joblist = res.data
      }).catch(() => {})
    },
    toggle (index) {
      this.$refs.checkboxes[index].toggle()
    },
    handlerSubmit () {
      this.goStep2()
    },
    getTplByJobnum () {
      this.tpllist = []
      let jobnum = this.result.length
      http.get(api.microposte_get_tpl_by_jobnum, {jobnum}).then(res => {
        this.tpllist = res.data.items
      }).catch(() => {})
    },
    goStep2 () {
      if (this.result.length === 0) {
        this.$notify('请选择职位')
        return false
      }
      this.getTplByJobnum()
      this.active = 1
    },
    goStep3 (tpl) {
      this.tpl = tpl.thumb
      this.active = 2
    },
    goPrev () {
      if (this.active == 2) {
        this.active = 1
      } else if (this.active == 1) {
        this.result = []
        this.active = 0
      } else {
        this.$router.go(-1)
      }
    }
  }
}
</script>

<style lang="scss" scoped>
.step_box{
  width:90%;
  margin:10px auto;
}
/* 选择模板开始 */
.whb_template{
width: 100%;
height: auto;
background: #fff;
font-size: 15px;
color: #333333;

}
.whb_title{
height: 51px;
border: 1px solid #efefef;
line-height: 51px;
}
.whb_template_content{
width: 100%;
height: auto;
padding: 10px 16px 0;
box-sizing: border-box;
overflow: hidden;
}
.whb_template_content ul li{
width: 50%;
height: 315px;
float: left;
/* margin-left: 0.46rem; */
}
.whb_template_img{
width: 100%;
height: 270px;
text-align: center;
}
.whb_template_img img{
width: 160px;
height: 270px;
box-shadow:0 0 10px 0px rgba(0,0,0,.14);
}
.whb_template_text{
width: 100%;
height: 44px;
line-height: 44px;
text-align: center;
font-size: 13px;
color: #666666;
}
/* 选择模板结束 */
</style>
