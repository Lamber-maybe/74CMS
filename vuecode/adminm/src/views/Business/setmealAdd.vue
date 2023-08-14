<template>
  <div class="body">
    <Head>开通套餐</Head>
    <van-field
      readonly
      clickable
      :value="companyname"
      label="选择企业"
      placeholder="请选择"
      input-align="right"
      :colon="true"
      class="form_choose reset_after big_left"
      @click="showSearch = true"
    />
    <van-field
      readonly
      clickable
      :value="setmeal_name"
      label="选择套餐"
      placeholder="请选择"
      input-align="right"
      :colon="true"
      class="form_choose reset_after big_left"
      @click="showPicker = true"
    />
    <div class="bottom"><van-button type="primary" block color="#1787fb" @click="addSetmeal">开通套餐</van-button></div>
    <van-popup
        :lazy-render="false"
        v-model="showSearch"
        position="right"
        style="width:80%;height:100%"
    >
      <search-company @onConfirm="confirmCompany"></search-company>
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
  import searchCompany from '@/components/searchCompany'
  import Vue from 'vue'
  import { Picker } from 'vant'
  Vue.use(Picker)
  export default {
    name: "setmealAdd",
    components: {
      searchCompany
    },
    data () {
      return {
        columnsPicker:[],
        showPicker:false,
        uid:'',
        setmeal_id:'',
        setmeal_name:'',
        companyname:'',
        showSearch: false
      }
    },
    created () {
      this.fetchClassify()
    },
    methods:{
      confirmCompany(row){
        this.uid = row.uid
        this.companyname = row.companyname
        this.showSearch = false
      },
      fetchClassify(){
        http
          .get(api.classify, {type:'setmealList'})
          .then(res => {
            res.data.forEach(el => {
              this.columnsPicker.push({text:el.name,id:el.id})
            })
          }).catch(() => {})
      },
      onConfirmPicker(value){
        this.setmeal_id = value.id
        this.setmeal_name = value.text
        this.showPicker = !this.showPicker
      },
      addSetmeal(){
        if(this.uid=='' || this.uid==0){
          this.$toast.fail('请选择企业')
          return false
        }
        if(this.setmeal_id=='' || this.setmeal_id==0){
          this.$toast.fail('请选择套餐')
          return false
        }
        http
          .post(api.companySetmealAdd, {uid:this.uid,setmeal_id:this.setmeal_id})
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
