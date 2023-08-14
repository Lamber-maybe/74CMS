<template>
  <div class="body">
    <Head>企业认证</Head>
    <van-cell-group>
      <van-cell class="substring">
        <template #title>
          <span class="cus_title">企业名称：</span><span class="cus_content">{{info.companyname}}</span>
        </template>
      </van-cell>
      <van-cell class="substring">
        <template #title>
          <span class="cus_title">认证状态：</span>
          <div class="i_tag" :class="info.auth_status | auditFilter">{{ options_audit[info.auth_status] }}</div>
        </template>
      </van-cell>
    </van-cell-group>
    <div class="up_line">
      <div class="up_box">
        <div class="up_title">营业执照：</div>
        <div class="up_loader photo">
          <van-image v-if="info.license!=''" :src="info.license" @click="preview(info.license)"/>
          <p class="no" v-else>暂未上传</p>
        </div>
      </div>
      <div class="up_box">
        <div class="up_title">委托书(函)：</div>
        <div class="up_loader photo">
          <van-image v-if="info.proxy!=''" :src="info.proxy" @click="preview(info.proxy)"/>
          <p class="no" v-else>暂未上传</p>
        </div>
      </div>
    </div>
    <div class="up_line">
      <div class="up_box">
        <div class="up_title">经办人身份证(正)：</div>
        <div class="up_loader photo">
          <van-image v-if="info.legal_person_idcard_front!=''" :src="info.legal_person_idcard_front" @click="preview(info.legal_person_idcard_front)"/>
          <p class="no" v-else>暂未上传</p>
        </div>
      </div>
      <div class="up_box">
        <div class="up_title">经办人身份证(反)：</div>
        <div class="up_loader photo">
          <van-image v-if="info.legal_person_idcard_back!=''" :src="info.legal_person_idcard_back" @click="preview(info.legal_person_idcard_back)"/>
          <p class="no" v-else>暂未上传</p>
        </div>
      </div>
    </div>
    <div class="bottom clear">
      <div class="l_btn" @click="funAudit(1)">认证通过</div>
      <div class="r_btn" @click="funAudit(2)">拒绝</div>
    </div>
    <van-dialog v-model="showReason" title="认证不通过原因" :show-confirm-button="false" :show-cancel-button="false" :closeOnClickOverlay="true">
      <van-form @submit="onSubmitReason">
        <van-field
          v-model="auditReason"
          rows="3"
          autosize
          label=""
          type="textarea"
          placeholder="请填写认证不通过原因"
          :rules="[{ required: true, message: '' }]"
        />
        <div style="margin: 16px;">
          <van-button round block type="info" native-type="submit">提交</van-button>
        </div>
      </van-form>
    </van-dialog>
  </div>
</template>

<script>
  import http from '@/utils/http'
  import api from '@/api'
  import Vue from 'vue'
  import { Image as VanImage } from 'vant'
  import { ImagePreview } from 'vant';
  Vue.use(VanImage)
  export default {
    name: "companyAudit",
    filters: {
      auditFilter(audit) {
        switch (audit) {
          case 0:
            return 'wait'
          case 1:
            return 'passed'
          case 2:
            return 'failed'
          default:
            return 'not'
        }
      }
    },
    data () {
      return {
        id:0,
        info:{},
        options_audit:[],
        showReason:false,
        auditReason:''
      }
    },
    created () {
      this.id = parseInt(this.$route.params.id)
      this.fetchClassify()
    },
    methods: {
      fetchClassify(){
        http
          .get(api.classify, {type:'companyAudit'})
          .then(res => {
            let auditActions = res.data
            auditActions.forEach(el => {
              this.options_audit[el.id] = el.name
            })
            this.options_audit[3] = '未认证'
            this.fetchData()
          }).catch(() => {})
      },
      fetchData(){
        http
          .get(api.companyAuthDetail, {id:this.id})
          .then(res => {
            this.info = res.data
            this.license = [{url:this.info.license}]
          }).catch(() => {})
      },
      funAudit(audit){
        if(audit==1){
          this.$dialog
            .confirm({
              title: '系统提示',
              message: '确定设置为认证通过吗?'
            })
            .then(() => {
              http
              .post(api.companyAudit, {id:[this.info.id],audit:1})
              .then(res => {
                this.$toast.success(res.message)
                this.$router.go(-1)
              })
              .catch(() => {})
            })
            .catch(() => {
              // on cancel
            })
        }else{
          this.showReason = true
        }
      },
      onSubmitReason(){
        http
          .post(api.companyAudit, {id:[this.info.id],audit:2,reason:this.auditReason})
          .then(res => {
            this.$toast.success(res.message)
            this.$router.go(-1)
          })
          .catch(() => {})
      },
      preview(src){
        ImagePreview([src]);
      },
    }
  }
</script>

<style lang="scss" scoped>
  .body { background-color: #ffffff;position: absolute;top: 0;left: 0;overflow-y: auto;width: 100%;height: 100%; }
  .cus_title { font-size: 28px;color: #999999;width: 155px;display: inline-block; }
  .cus_content { font-size: 28px;color: #333333; }
  .up_line {
    display: flex;width: 100%;padding: 0 30px;
    .up_box {
      flex: 1;
      .up_title {
        height: 96px;line-height: 96px;font-size: 28px;color: #999999;
      }
      .up_loader {
        width: 300px;height: 225px;background-color: #f6f7fb;position: relative;
        .no {
          position: absolute;width: 300px;height: 225px;line-height: 225px;text-align: center;font-size: 24px;
          color: #cbced6;
        }
      }
    }
  }
  .i_tag {
    width: 104px;text-align: center;padding: 6px 0;display: inline-block;
    font-size: 20px;border-radius: 999px;
    &.wait { background-color: #fdf6ec;color: #ff8e50; }
    &.passed { background-color: #f0f9eb;color: #67c23a; }
    &.failed { background-color: #fef0f0;color: #f56c6c; }
    &.not { background-color: #f4f4f5;color: #909399; }
  }
  .van-hairline--top-bottom::after, .van-hairline-unset--top-bottom::after { border-top: 0; }
  .bottom {
    position: fixed;left: 0;bottom: 0;width: 100%;border-top: 1PX solid #f3f3f3;padding: 20px 30px;
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
  .van-uploader {
    position: absolute;width: 100%;height: 100%;z-index: 1;opacity: 0;
  }
</style>
