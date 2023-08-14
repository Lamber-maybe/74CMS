<template>
  <div class="body">
    <Head>新增推广</Head>
    <van-field
      readonly
      clickable
      :value="fullname"
      label="选择简历"
      placeholder="请选择"
      input-align="right"
      :colon="true"
      class="form_choose reset_after big_left"
      @click="showSearch = true"
    />
    <van-field
      readonly
      clickable
      :value="promotion_name"
      label="推广方案"
      placeholder="请选择"
      input-align="right"
      :colon="true"
      class="form_choose reset_after"
      @click="showPicker = true"
    />
    <van-field
      v-if="form.type=='tag'"
      v-model="form.tag"
      label="醒目标签"
      placeholder="输入醒目标签"
      input-align="right"
      :colon="true"
      class="reset_after"
      type="text"
    />
    <van-field
      v-model="form.days"
      label="推广天数"
      placeholder="输入推广天数"
      input-align="right"
      :colon="true"
      class="reset_after"
      type="number"
    />
    <div class="bottom"><van-button type="primary" block color="#1787fb" @click="onSubmit">开通推广</van-button></div>
    <van-popup
        :lazy-render="false"
        v-model="showSearch"
        position="right"
        style="width:80%;height:100%"
    >
      <search-resume @onConfirm="confirmResume"></search-resume>
    </van-popup>
    <van-popup v-model="showPicker" position="bottom">
      <van-picker
        show-toolbar
        :columns="columnsPicker"
        @confirm="onConfirmPicker"
        @cancel="showPicker = false"
      />
    </van-popup>
  </div>
</template>

<script>
  import http from '@/utils/http'
  import api from '@/api'
  import searchResume from '@/components/searchResume'
  import Vue from 'vue'
  import { Picker } from 'vant'
  Vue.use(Picker)
  export default {
    name: "promotionResumeAdd",
    components: {
      searchResume
    },
    data () {
      return {
        columnsPicker:[{text:'置顶',id:'stick'},{text:'醒目标签',id:'tag'}],
        showPicker:false,
        fullname:'',
        promotion_name:'',
        showSearch: false,
        form:{
          pid:0,
          type:'',
          days:'',
          tag:''
        }
      }
    },
    created () {
    },
    methods:{
      confirmResume(row){
        this.form.pid = row.id
        this.fullname = row.fullname
        this.showSearch = false
      },
      onConfirmPicker(value){
        this.form.type = value.id
        this.promotion_name = value.text
        this.showPicker = !this.showPicker
      },
      onSubmit(){
        if(this.form.pid=='' || this.form.pid==0){
          this.$toast.fail('请选择简历')
          return false
        }
        if(this.form.type==''){
          this.$toast.fail('请选择推广方案')
          return false
        }
        if(this.form.days==''){
          this.$toast.fail('请输入推广天数')
          return false
        }
        http
          .post(api.promotionResumeAdd, this.form)
          .then(res => {
            this.$toast.success(res.message)
            this.$router.push('/business')
          }).catch(() => {})
      }
    }
  }
</script>
<style lang="scss" scoped>
  .body { background-color: #ffffff;position: absolute;top: 0;left: 0;overflow-y: auto;width: 100%;height: 100%; }
  .bottom { position: absolute;left: 0;bottom: 0;width: 100%;border-top: 1PX solid #f3f3f3;padding: 20px 30px; }
</style>
