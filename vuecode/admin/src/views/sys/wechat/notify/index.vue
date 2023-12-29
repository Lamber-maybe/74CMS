<template>
  <div class="app-container">
    <div class="tip">
      <p>自7月4日起，微信官方下线历史模板库，若您在此之前未申请过适配SE系统的模板消息，请使用类目模板。<a class="aHref" href="http://doc.74cms.com/#/se/wxmb"
          target="_blank">【点击查看配置教程】</a></p>
      <p>在此处切换模板类型后全站模板消息将使用您选择的渠道发送，切换后就检查对应模板ID是否完成配置。</p>
    </div>
    <div class="chose_tpl">
      <el-form ref="form" :model="form" label-width="95px">
        <el-form-item label="选择模板:">
          <el-radio-group v-model="form.tpl" @change="tplChange">
            <el-radio label="1">历史模板
              <el-tooltip class="item" effect="dark" placement="top-start">
                <div slot="content">
                  <p class="tooltipText">7月4日起，微信已不支持新申请历史模板</p>
                </div>
                <i class="infoIcon el-icon-info" />
              </el-tooltip>
            </el-radio>
            <el-radio label="2">类目模板
              <el-tooltip class="item" effect="dark" placement="top-start">
                <div slot="content">
                  <p class="tooltipText">若您未申请过历史模板，请使用类目模板</p>
                </div>
                <i class="infoIcon el-icon-info" />
              </el-tooltip>
            </el-radio>
          </el-radio-group>
        </el-form-item>
      </el-form>
    </div>
    <el-tabs type="border-card" @tab-click="tabChange">
      <el-tab-pane label="通知企业">
        <ruleForm ref="ruleForm" :dataset="dataset" :infoLoading="infoLoading" />
      </el-tab-pane>
      <el-tab-pane label="通知求职者">
        <ruleForm ref="ruleForm" :dataset="dataset" :infoLoading="infoLoading" />
      </el-tab-pane>
    </el-tabs>
  </div>
</template>

<script>
  import ruleForm from './ruleForm.vue'
  import {
    getWechatNotifyRule
  } from '@/api/configuration'
  import {
    switchWechatTplType
  } from '@/api/configuration'
  export default {
    components: {
      ruleForm
    },
    data() {
      return {
        form: {
          tpl: '1'
        },
        listtype: 'company',
        dataset: [],
        utype: 1,
        infoLoading: false
      }
    },
    computed: {
      config() {
        return this.$store.state.config
      }
    },
    created() {
      this.form.tpl = this.config.wechat_tpl_type
      this.getDataset(this.utype)
    },
    methods: {
      tplChange(e) {
        let _this = this,
          href = 'http://doc.74cms.com/',
          warnTxt = e == 1 ? '微信官方已下线历史模板库，若您在此之前未申请过适配SE系统的模板消息，请使用类目模板渠道。' : '切换类目模板后，全站将使用类目模板渠道，请检查模板ID是否完成配置。'
        _this.$confirm(warnTxt, '提示', {
          confirmButtonText: '确定',
          cancelButtonText: '取消',
          dangerouslyUseHTMLString: true, // 需要设置此选项为 true，才能解析 HTML 字符串
          type: 'warning'
        }).then(() => {
          this.switchWechatTplTypeOprate(e)
        }).catch(() => {
          _this.form.tpl = e == 1 ? '2' : '1'
        });
      },
      switchWechatTplTypeOprate(val) {
        let params = {
          wechat_tpl_type: val
        }
        switchWechatTplType(params).then(res => {
          if (res.code == 200) {
            this.$message.success(res.message)
            this.getDataset(this.utype)
          }
        }).catch(() => {})
      },
      tabChange(e) {
        this.listtype = e.paneName == 0 ? 'company' : e.paneName == 1 ? 'personal' : 'administrators'
        this.utype = e.paneName == 0 ? 1 : e.paneName == 1 ? 2 : 3
        this.getDataset(this.utype)
      },
      getDataset(utype) {
        this.infoLoading = true
        this.$set(this, 'dataset', [])
        let params = {
          utype: utype
        }
        getWechatNotifyRule(params, 'get')
          .then(response => {
            const data = [...response.data]
            data.forEach(function(val, index, arr) {
              val.is_open = val.is_open == 1
            })
            this.$set(this, 'dataset', data)
            this.infoLoading = false
          })
          .catch(() => {})
      }
    }
  }
</script>
<style lang="scss" scoped>
  .chose_tpl {
    box-shadow: 0 2px 4px 0 rgba(0, 0, 0, .12), 0 0 6px 0 rgba(0, 0, 0, .04);
  }
</style>
