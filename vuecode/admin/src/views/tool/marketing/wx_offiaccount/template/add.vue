<template>
  <el-card>
    <div class="left">
      <div slot="header">
        <div class="header">
          <span class="titles">微信公众号文章</span>
          <span class="close" @click="closeClick"><i class="el-icon-arrow-left" />返回列表</span>
        </div>
      </div>
      <el-form ref="form" :rules="formRules" :model="form" label-width="100px">
        <el-form-item v-if="!disabled" label="模板类型：">
          <el-radio-group v-model="form.type" :disabled="disabled">
            <el-radio :label="1">职位模板</el-radio>
            <el-radio :label="2">企业模板</el-radio>
          </el-radio-group>
        </el-form-item>
        <el-form-item label="模板名称：" prop="name">
          <el-input v-model="form.name" />
        </el-form-item>
        <el-form-item label="头部模板：" class="formStyle">
          <div class="describe">
            <span class="words"><i class="el-icon-warning-outline icons" /> 公共参数，全局都可调用</span>
            <el-popover
              v-model="isShow1"
              placement="right-start"
              width="260"
              trigger="manual"
            >
              <div class="tagTitle">
                <span>模板参数 <b>复制参数插入到编辑区</b></span>
                <i class="el-icon-close" style="font-size: 16px" @click="onShow(1)" />
              </div>
              <div class="tagList">
                <el-tag
                  v-for="tag in headOption"
                  :key="tag.label"
                  class="tags"
                  @click="tagClick(tag,'1')"
                >
                  {{ tag.label }}
                </el-tag>
              </div>
              <el-button
                slot="reference"
                size="mini"
                type="primary"
                plain
                icon="el-icon-receiving"
                @click="onShow(1)"
              >模板参数</el-button>
            </el-popover>
          </div>

          <ueditor ref="ue" :random-id="`edit1`" :default-msg="defaultMsg1" />
        </el-form-item>
        <el-form-item label="职位模板：" class="formStyle">
          <div class="describe">
            <span class="words"><i class="el-icon-warning-outline icons" /> 只需要添加一条样式即可；具体显示的数量可到发布工具区选择</span>
            <el-popover
              v-model="isShow2"
              placement="right-start"
              width="260"
              trigger="manual"
            >
              <div class="tagTitle">
                <span>模板参数 <b>复制参数插入到编辑区</b></span>
                <i class="el-icon-close" style="font-size: 16px" @click="onShow(2)" />
              </div>
              <div v-if="form.type == '1'" class="tagList">
                <el-tag
                  v-for="tag in bodyJobOption"
                  :key="tag.label"
                  class="tags"
                  @click="tagClick(tag,'2')"
                >
                  {{ tag.label }}
                </el-tag>
              </div>
              <div v-else class="tagList">
                <template v-for="tag in bodyCompanyOption">
                  <el-tag
                    v-if="tag.item != 'items.job_list'"
                    class="tags"
                    @click="tagClick(tag,'2')"
                  >
                    {{ tag.label }}
                  </el-tag>
                </template>
                <div style="font-size: 12px;text-align: center;margin: 0 0 6px;color: #999999;width: 100%">以下内容仅支持在"企业职位"标签内插入</div>
                <template v-for="tag in bodyCompanyOption">
                  <el-tag
                    v-if="tag.item == 'items.job_list'"
                    class="tags"
                    @click="tagClick(tag,'2')"
                  >
                    {{ tag.label }}
                  </el-tag>
                </template>

              </div>
              <el-button
                slot="reference"
                size="mini"
                type="primary"
                plain
                icon="el-icon-receiving"
                @click="onShow(2)"
              >模板参数</el-button>
            </el-popover>
          </div>

          <ueditor ref="ue1" :random-id="`edit2`" :default-msg="defaultMsg2" />
        </el-form-item>
        <el-form-item label="尾部模板：" class="formStyle">
          <div class="describe">
            <span class="words"><i class="el-icon-warning-outline icons" /> 公共参数，全局都可调用</span>
            <el-popover
              v-model="isShow3"
              placement="right-start"
              width="260"
              trigger="manual"
            >
              <div class="tagTitle">
                <span>模板参数 <b>复制参数插入到编辑区</b></span>
                <i class="el-icon-close" style="font-size: 16px" @click="onShow(3)" />
              </div>
              <div class="tagList">
                <el-tag
                  v-for="tag in tailOption"
                  :key="tag.label"
                  class="tags"
                  @click="tagClick(tag,'3')"
                >
                  {{ tag.label }}
                </el-tag>
              </div>
              <el-button
                slot="reference"
                size="mini"
                type="primary"
                plain
                icon="el-icon-receiving"
                @click="onShow(3)"
              >模板参数</el-button>
            </el-popover>
          </div>

          <ueditor ref="ue2" :random-id="`edit3`" :default-msg="defaultMsg3" />
        </el-form-item>
        <el-form-item>
          <el-button :disabled="templateType != 'see' ? false : true" type="primary" @click="saveClick('form')">保存模板</el-button>
        </el-form-item>
      </el-form>

    </div>
    <!--<div class="right">-->
    <!--<div class="rightInner">-->
    <!--<div v-html="preview.header" />-->
    <!--<div v-html="preview.content" />-->
    <!--<div v-html="preview.footer" />-->
    <!--</div>-->
    <!--<el-button type="primary" size="small" class="preview" @click="previewClick">点击预览</el-button>-->
    <!--</div>-->
  </el-card>
</template>

<script>
import { templateOption, templateEdit, templateDetails } from '@/api/marketing_wxoffiaccount'
import ueditor from '@/components/ueditor'
export default {
  name: 'Add',
  components: { // 定义组件名称
    ueditor
  },
  props: {
    templateId: {
      default: ''
    },
    templateType: {
      default: ''
    }
  },
  data(){
    return {
      defaultMsg1: '',
      defaultMsg2: '',
      defaultMsg3: '',
      form: {
        type: 1
      },
      formRules: {
        name: [
          { required: true, message: '请输入模板名称', trigger: 'blur' }
        ]
      },
      headOption: [],
      bodyCompanyOption: [],
      bodyJobOption: [],
      tailOption: [],

      isShow1: false,
      isShow2: false,
      isShow3: false,

      // 预览
      preview: {},

      disabled: false
    }
  },
  created(){
    templateOption().then(res => {
      const { code, data, message } = res
      if (code == '200'){
        this.headOption = data.head_option
        this.bodyCompanyOption = data.body_option.company
        this.bodyJobOption = data.body_option.job
        this.tailOption = data.tail_option
      } else {
        this.$message.error(message)
      }
    })
  },
  mounted(){
    if (this.templateId){
      this.disabled = true
      this.$nextTick(() => {
        this.init()
      })
    }
  },
  methods: {
    // 编辑初始化
    init(){
      templateDetails({ 'template_id': this.templateId }).then(res => {
        const { code, data, message } = res
        if (code == '200'){
          this.form = data
          this.defaultMsg1 = data.head
          this.defaultMsg2 = data.body
          this.defaultMsg3 = data.tail
        } else {
          this.$message.error(message)
        }
      })
    },
    // 模板参数显示隐藏
    onShow(name){
      if (name == 1){
        this.isShow1 = !this.isShow1
      } else if (name == 2){
        this.isShow2 = !this.isShow2
      } else {
        this.isShow3 = !this.isShow3
      }
    },
    //  标签插入编辑器
    tagClick(row, name){
      console.log(row)
      if (name == 1){
        this.$refs.ue.setUEContent(`{${row.label}}`)
      } else if (name == 3){
        this.$refs.ue2.setUEContent(`{${row.label}}`)
      } else {
        if (row.flag == 'list'){
          this.$refs.ue1.setUEContent(`{职位列表开始}{职位列表结束}`)
        } else if (row.flag == 'each'){
          this.$refs.ue1.setUEContent(`{职位福利开始}{${row.label}}{职位福利结束}`)
        } else if (row.label == '企业H5场景码'){
          this.$refs.ue1.setUEContent(`{企业H5场景码|样式="width:100px;height:100px;"}`)
        } else if (row.label == '职位H5场景码'){
          this.$refs.ue1.setUEContent(`{职位H5场景码|样式="width:100px;height:100px;"}`)
        } else if (row.label == '企业小程序链接'){
          this.$refs.ue1.setUEContent(`<a data-miniprogram-type="text" data-miniprogram-appid="{小程序APPID}" data-miniprogram-path="{企业小程序跳转}">查看详情</a>`)
        } else if (row.label == '职位小程序链接'){
          this.$refs.ue1.setUEContent(`<a data-miniprogram-type="text" data-miniprogram-appid="{小程序APPID}" data-miniprogram-path="{职位小程序跳转}">查看详情</a>`)
        } else if (row.label == '企业H5链接'){
          this.$refs.ue1.setUEContent(`<a href="{企业H5链接}">查看详情</a>`)
        } else if (row.label == '职位H5链接'){
          this.$refs.ue1.setUEContent(`<a href="{职位H5链接}">查看详情</a>`)
        } else {
          this.$refs.ue1.setUEContent(`{${row.label}}`)
        }
      }
    },
    previewClick(){
      this.preview = {
        header: this.$refs.ue.getUEContent(),
        content: this.$refs.ue1.getUEContent(),
        footer: this.$refs.ue2.getUEContent()
      }
    },

    closeClick(){
      this.$parent.addTeamplate()
    },

    saveClick(formName){
      if (this.templateType == 'see'){
        return
      } else {
        this.$refs[formName].validate((valid) => {
          if (valid) {
            this.form.head = this.$refs.ue.getUEContent()
            this.form.body = this.$refs.ue1.getUEContent()
            this.form.tail = this.$refs.ue2.getUEContent()
            templateEdit(this.form).then(res => {
              const { code, message } = res
              if (code == '200'){
                if (this.disabled){
                  this.$message.success('模板修改成功！')
                } else {
                  this.$message.success('模板创建成功！')
                }
                this.closeClick()
              } else {
                this.$message.error(message)
              }
            })
          } else {
            console.log('error submit!!')
            return false
          }
        })
      }
    }
  }

}
</script>

<style lang="scss" scoped>
  .header{
    display: flex;
    flex-direction: row;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 20px;
    .close{
      font-size: 14px;
      color: #999999;
      cursor: pointer;
    }
  }
  .titles{
    font-size: 18px;
    color: #333333;
    font-weight: 600;
  }
  .left{
    max-width: 900px;
  }
  .formStyle{
    ::v-deep .el-form-item__content{
      line-height: normal;
    }
  }
  .describe{
    display: flex;
    flex-direction: row;
    align-items: center;
    justify-content: space-between;
    font-size: 14px;
    color: #999999;
    margin-bottom: 10px;
    padding-top: 4px;
    .icons{
      color: #00a0e9;
      font-size: 16px;
    }
  }
  .tagTitle{
    display: flex;
    flex-direction: row;
    align-items: center;
    justify-content: space-between;
    font-size: 14px;
    color: #333333;
    margin-bottom: 8px;
    b{
      color: #999999;
      font-size: 12px;
      font-weight: normal;
    }
  }
  .tagList{
    display: flex;
    flex-direction: row;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    margin-bottom: -10px;
    .tags{
      width: 48%;
      text-align: center;
      margin-bottom: 10px;
      cursor: pointer;
    }
  }
  .right{
    width: 300px;
    height: 570px;

    background: red;
    position: fixed;
    right: 80px;
    top: 20%;
    border-radius: 45px;
    background: url("../../../../../assets/images/iphone.png") no-repeat 0 0;
    background-size: 94% 100%;
    padding: 30px 0 26px 18px;
    box-sizing: border-box;
    .rightInner{
      width: 100%;
      height: 100%;
      overflow-y: auto;
      padding-right: 34px;
      font-size: 12px;
      line-height: 1;

    }
    .preview{
      position: absolute;
      bottom: -50px;
      left: 110px;
      margin: auto;
    }
  }

</style>
<style>
  .edui-default .edui-editor{
    z-index: 1!important;
  }
</style>
