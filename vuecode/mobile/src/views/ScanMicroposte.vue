<template>
  <div id="app">
    <Head :goback_custom="true" @gobackCustomMethod="$router.push('/')">微海报</Head>
    <van-divider
      :style="{ color: '#1989fa', borderColor: '#1989fa', padding: '0 16px' }"
    >
      长按保存图片
    </van-divider>
    <microposteResult :tpl="tpl" :jobidarr="result"></microposteResult>
  </div>
</template>

<script>
import microposteResult from '@/components/microposte/index'
export default {
  name: 'ScanMicroposte',
  components: {
    microposteResult
  },
  data () {
    return {
      tpl: '',
      result: []
    }
  },
  created () {
    if (this.$route.query.jobid !== undefined && this.$route.query.tpl !== undefined) {
      let jobidArr = this.$route.query.jobid.split(',')
      jobidArr.forEach(element => {
        this.result.push(element)
      })
      this.tpl = this.$route.query.tpl
    } else {
      this.$notify('请正确扫描二维码')
    }
  },
  methods: {

  }
}
</script>

<style lang="scss" scoped>
</style>
