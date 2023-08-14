<template>
  <div class="body">
    <Head>积分充值</Head>
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
      v-if="companyname!=''"
      v-model="points"
      label="会员积分"
      placeholder="会员积分"
      input-align="right"
      :colon="true"
      readonly
      class="reset_after"
    />
    <van-field
      readonly
      clickable
      :value="opCn"
      label="操作积分"
      placeholder="请选择"
      input-align="right"
      :colon="true"
      class="form_choose reset_after"
      @click="showPicker = true"
    />
    <van-field
      v-model="form.points_val"
      type="number"
      label="增减数额"
      placeholder="输入要操作的积分数(整数)"
      input-align="right"
      :colon="true"
      class="reset_after"
    />
    <van-field
      label="是否收费"
      class="reset_after"
    />
    <div class="for_btn">
      <van-switch
        class="self_switch"
        v-model="form.is_charge"
        size="18px"
        active-color="#0088fc"
      />
    </div>
    <van-field
      v-if="form.is_charge===true || form.is_charge==1"
      v-model="form.charge_val"
      type="digit"
      label="收费金额"
      placeholder="请输入收费金额"
      input-align="right"
      :colon="true"
      class="reset_after"
    />
    <van-field
      readonly
      clickable
      label="操作说明"
      :colon="true"
      input-align="right"
      class="reset_after no_border"
    />
    <div class="box_1">
      <van-field
        style="background-color:#f9f9f9;"
        v-model="form.explain"
        rows="5"
        autosize
        label=""
        type="textarea"
        placeholder="请输入操作说明"
      />
    </div>
    <div class="bottom clear">
      <div class="l_btn" @click="savePoints">修改积分</div>
      <div class="r_btn" @click="$router.push('/business')">取消</div>
    </div>
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
    name: "pointsEdit",
    components: {
      searchCompany
    },
    data () {
      return {
        columnsPicker:[{text:'增加',id:1},{text:'减少',id:2}],
        showPicker:false,
        form:{
          uid: 0,
          op: 1,
          points_val: '',
          explain: "",
          is_charge: true,
          charge_val: ''
        },
        showSearch: false,
        companyname: '',
        points: 0,
        opCn: '增加',
      }
    },
    created () {
    },
    methods:{
      confirmCompany(row){
        this.form.uid = row.uid
        this.companyname = row.companyname
        this.points = row.points
        this.showSearch = false
      },
      onConfirmPicker(value){
        this.form.op = value.id
        this.opCn = value.text
        this.showPicker = !this.showPicker
      },
      savePoints(){
        if(this.form.uid=='' || this.form.uid==0){
          this.$toast.fail('请选择企业')
          return false
        }
        if(this.form.points_val=='' || this.form.points_val==0){
          this.$toast.fail('请输入增减数额')
          return false
        }
        let data = JSON.parse(JSON.stringify(this.form))
        data.is_charge = data.is_charge===true?1:0
        http
          .post(api.memberPointsSet, data)
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
  .self_switch {
    position: absolute;
    right: 30px;
    top: -70px;
  }
  .box_1 {
    textarea {
      width: 100%;
      height: 260px;
      border: 0;
      background-color: #f9f9f9;
      font-size: 28px;
      resize: none;
      padding: 16px 30px;
      line-height: 1.8;
      &::placeholder {
        color: #c9c9c9;
      }
    }
    width: 100%;
    background-color: #ffffff;
    position: relative;
    padding: 0 40px 40px 40px;
  }
  .bottom {
    position: fixed;left: 0;bottom: 0;right: 0;width: 100%;border-top: 1PX solid #f3f3f3;padding: 20px 30px;z-index: 1;
    background-color: #ffffff;
    .l_btn {
      float: left;width: 490px;height: 90px;line-height: 90px;text-align: center;border-radius: 8px;font-size: 30px;
      color: #ffffff;background-color: #1787fb;
    }
    .r_btn {
      float: right;width: 180px;height: 90px;line-height: 90px;text-align: center;border-radius: 8px;font-size: 30px;
      color: #ffffff;background-color: #f56c6c;
    }
  }
</style>
