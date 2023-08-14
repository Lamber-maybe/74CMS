<template>
  <div>
    <div ref="imageDom" v-if="makePicDone===false">
      <components :is="componentsName" v-if="initData===true" :joblist="joblist" :qrcode="qrcode"></components>
    </div>
    <div v-if="makePicDone===true" class="final-pic"><img :src="imgUrl"/></div>
  </div>
</template>

<script>
import tpl_1_1 from '@/components/microposte/1-1'
import tpl_1_2 from '@/components/microposte/1-2'
import tpl_1_3 from '@/components/microposte/1-3'
import tpl_2_1 from '@/components/microposte/2-1'
import tpl_2_2 from '@/components/microposte/2-2'
import tpl_2_3 from '@/components/microposte/2-3'
import tpl_3_1 from '@/components/microposte/3-1'
import tpl_3_2 from '@/components/microposte/3-2'
import tpl_3_3 from '@/components/microposte/3-3'
import tpl_4_1 from '@/components/microposte/4-1'
import tpl_4_2 from '@/components/microposte/4-2'
import tpl_4_3 from '@/components/microposte/4-3'
import tpl_5_1 from '@/components/microposte/5-1'
import tpl_5_2 from '@/components/microposte/5-2'
import tpl_5_3 from '@/components/microposte/5-3'
import tpl_6_1 from '@/components/microposte/6-1'
import tpl_6_2 from '@/components/microposte/6-2'
import tpl_6_3 from '@/components/microposte/6-3'
import tpl_7_1 from '@/components/microposte/7-1'
import tpl_7_2 from '@/components/microposte/7-2'
import tpl_7_3 from '@/components/microposte/7-3'
import tpl_8_1 from '@/components/microposte/8-1'
import tpl_8_2 from '@/components/microposte/8-2'
import tpl_8_3 from '@/components/microposte/8-3'
import api from '@/api'
import http from '@/utils/http'
import {generatePicture} from '@/utils/index'
export default {
  name: 'microposte',
  components: {
    tpl_1_1,
    tpl_1_2,
    tpl_1_3,
    tpl_2_1,
    tpl_2_2,
    tpl_2_3,
    tpl_3_1,
    tpl_3_2,
    tpl_3_3,
    tpl_4_1,
    tpl_4_2,
    tpl_4_3,
    tpl_5_1,
    tpl_5_2,
    tpl_5_3,
    tpl_6_1,
    tpl_6_2,
    tpl_6_3,
    tpl_7_1,
    tpl_7_2,
    tpl_7_3,
    tpl_8_1,
    tpl_8_2,
    tpl_8_3
  },
  props: ['tpl', 'jobidarr'],
  data () {
    return {
      joblist: [],
      initData: false,
      makePicDone: false,
      imgUrl: '',
      qrcode: '',
      componentsName: ''
    }
  },
  mounted () {
    this.componentsName = 'tpl_' + this.tpl
    this.fetchData()
  },
  methods: {
    fetchData () {
      let that = this
      http.post(api.microposte_get_joblist, {jobid: that.jobidarr}).then(res => {
        this.joblist = res.data.items
        let locationUrl = this.$store.state.config.mobile_domain + 'company/' + this.joblist[0].company_id
        this.qrcode = window.global.RequestBaseUrl + api.get_qrcode + '?type=normal&url=' + locationUrl
        window.pageYOffset = 0
        document.documentElement.scrollTop = 0
        document.body.scrollTop = 0
        this.initData = true
        setTimeout(() => {
          generatePicture(that.$refs.imageDom).then(url => {
            that.imgUrl = url
            that.makePicDone = true
          })
        }, 500)
      })
    }
  }
}
</script>

<style lang="scss" scoped>
  .final-pic{
    font-size:0;
    img{width:375px;}
  }
</style>
