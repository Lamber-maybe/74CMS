<template>
  <div>
    <el-form
      ref="form"
      class="common-form"
      :model="form"
      label-width="100px"
      :rules="rules"
    >
      <el-form-item label="任务标题" prop="title">
        <el-input
          v-model="form.title"
          placeholder="请输入内容"
          class="large"
        />
      </el-form-item>
      <el-form-item label="发送渠道" prop="send_type">
        <el-checkbox-group v-model="form.send_type">
          <el-checkbox label="message">站内信</el-checkbox>
          <el-checkbox label="sms" disabled>短信</el-checkbox>
          <el-checkbox label="email">Email</el-checkbox>
        </el-checkbox-group>
        <p class="messageBox">
          <img src="../../../../assets/images/marketing/warn.png" alt="">
          根据相关政策要求，群发短信需报备模板，如有群发需求请<span @click="concatClick()">联系官方客服</span>
        </p>
      </el-form-item>
      <el-form-item label="选择模板">
        <el-dropdown
          v-for="(item,index) in tplList"
          :key="index"
          split-button
          type="text"
          @click="funUseTpl(item)"
        >
          {{ item.name }}
          <el-dropdown-menu slot="dropdown">
            <el-dropdown-item @click.native="funUseTpl(item)">
              应用
            </el-dropdown-item>
            <el-dropdown-item @click.native="funEditTpl(item)">
              编辑
            </el-dropdown-item>
            <el-dropdown-item @click.native="funDeleteTpl(item)">
              删除
            </el-dropdown-item>
          </el-dropdown-menu>
        </el-dropdown>
        <el-button
          class="button-new-tag"
          size="small"
          style="margin-left:0"
          @click="funAddTpl"
        >
          + 新建模板
        </el-button>
      </el-form-item>
      <el-form-item label="消息内容" prop="content">
        <el-input
          v-model="form.content"
          type="textarea"
          :rows="5"
          placeholder="请输入内容"
          class="large"
        />
      </el-form-item>
    </el-form>
    <el-dialog
      v-if="dialogVisible"
      :title="dialogTitle"
      :visible.sync="dialogVisible"
      width="35%"
      :close-on-click-modal="false"
      @close="closeDialog"
    >
      <el-form
        ref="tplform"
        class="common-form"
        :model="tplform"
        label-width="100px"
        :rules="tplrules"
      >
        <el-form-item label="模板名称" prop="name">
          <el-input v-model="tplform.name" />
        </el-form-item>
        <el-form-item label="消息内容" prop="content">
          <el-input v-model="tplform.content" type="textarea" rows="5" />
        </el-form-item>
        <el-form-item label=" ">
          <el-button type="primary" @click="saveTpl('tplform')">
            保存
          </el-button>
          <el-button @click="closeDialog">取 消</el-button>
        </el-form-item>
      </el-form>
    </el-dialog>
    <el-dialog
      v-if="messageDialogVisible"
      title=""
      :visible.sync="messageDialogVisible"
      width="377px"
      :close-on-click-modal="false"
      @close="closeDialog"
    >
      <p class="messageQrcodeTxt3">咨询客服</p>
      <p class="messageQrcode">
        <img src="../../../../assets/images/marketing/qrcode.png">
      </p>
      <p class="messageQrcodeTxt1">微信扫码咨询或拨打电话</p>
      <p class="messageQrcodeTxt2">182-3404-5230</p>
    </el-dialog>
  </div>
</template>

<script>
import {
  marketTplList,
  marketTplAdd,
  marketTplEdit,
  marketTplDelete
} from '@/api/market'

export default {
  data() {
    return {
      tplList: [],
      form: {
        title: '',
        send_type: [],
        content: ''
      },
      dialogTitle: '',
      dialogVisible: false,
      tplform: {
        id: '',
        name: '',
        content: ''
      },
      rules: {
        title: [
          {
            required: true,
            message: '请输入任务标题',
            trigger: 'blur'
          },
          {
            max: 30,
            message: '最大 30 个字符',
            trigger: 'blur'
          }
        ],
        send_type: [
          {
            required: true,
            message: '请选择发送渠道',
            trigger: 'change'
          }
        ],
        content: [
          {
            required: true,
            message: '请输入消息内容',
            trigger: 'blur'
          }
        ]
      },
      tplrules: {
        name: [
          {
            required: true,
            message: '请输入模板名称',
            trigger: 'blur'
          },
          {
            max: 30,
            message: '最大 30 个字符',
            trigger: 'blur'
          }
        ],
        content: [
          {
            required: true,
            message: '请输入消息内容',
            trigger: 'blur'
          }
        ]
      },
      messageDialogVisible: false
    }
  },
  created() {
    this.fetchTplList()
  },
  methods: {
    funAddTpl() {
      this.dialogTitle = '新建模板'
      this.dialogVisible = true
    },
    funEditTpl(row) {
      this.tplform = { ...row }
      this.dialogTitle = '编辑模板'
      this.dialogVisible = true
    },
    funDeleteTpl(row) {
      marketTplDelete({ id: row.id })
        .then(response => {
          this.fetchTplList()
          return true
        })
        .catch(() => {})
    },
    funUseTpl(row) {
      this.form.content = row.content
    },
    addTplSave(insertData, formName) {
      this.$refs[formName].validate(valid => {
        if (valid) {
          marketTplAdd(insertData)
            .then(response => {
              this.closeDialog()
              this.fetchTplList()
              return true
            })
            .catch(() => {})
        } else {
          return false
        }
      })
    },
    editTplSave(insertData, formName) {
      this.$refs[formName].validate(valid => {
        if (valid) {
          marketTplEdit(insertData)
            .then(response => {
              this.closeDialog()
              this.fetchTplList()
              return true
            })
            .catch(() => {})
        } else {
          return false
        }
      })
    },
    saveTpl(formName) {
      const that = this
      const insertData = { ...that.tplform }
      if (parseInt(insertData.id) > 0) {
        that.editTplSave(insertData, formName)
      } else {
        that.addTplSave(insertData, formName)
      }
    },
    fetchTplList() {
      marketTplList({})
        .then(response => {
          this.tplList = [...response.data.items]
          return true
        })
        .catch(() => {})
    },
    closeDialog() {
      this.dialogVisible = false
      this.messageDialogVisible = false
      this.tplform = {
        id: '',
        name: '',
        content: ''
      }
    },
    concatClick(){
      this.messageDialogVisible = true
    }
  }
}
</script>
<style scoped>
.el-tag + .el-tag {
  margin-left: 10px;
}
.button-new-tag {
  margin-left: 10px;
  height: 28px;
  line-height: 26px;
  padding-top: 0;
  padding-bottom: 0;
  border-color:#409EFF;
  color:#409EFF;
  padding-left: 6px;
  padding-right: 8px;
}
.input-new-tag {
  width: 90px;
  margin-left: 10px;
  vertical-align: bottom;
}
.large {
  width: 608px;
}
.messageBox{
  width: 437px;
  height: 41px;
  color:#FF9536;
  font-size: 12px;
  background: url('../../../../assets/images/marketing/bg.png') 0 0 no-repeat;
  background-size: 100% 100%;
  margin: 0;
  line-height: 49px;
  display: flex;
  justify-content: start;
  align-items: center;
  padding-top: 7px;
}
.messageBox img{
  display: inline-block;
  width: 14px;
  height: 14px;
  margin: 0 5px 0 15px;
}
.messageBox span{
  text-decoration-color: #FF9536;
    text-decoration-line: underline;
  cursor: pointer;
}
.messageQrcode{
  width:158px;
  height:158px;
  margin:0 auto;
}
.messageQrcodeTxt1{
  width: 100%;
  text-align: center;
  color: #7E7E7E;
  font-size: 14px;
  margin: 21px 0 7px 0;
}
.messageQrcodeTxt2{
  width: 100%;
  text-align: center;
  font-size: 18px;
  color: #188AFF;
  margin: 0;
}
.messageQrcodeTxt3{
  color:#474747;
  font-weight: 600;
  width: 100%;
  text-align: center;
  font-size: 20px;
}
>>>.el-dialog__wrapper{
  top: 100px;
}
</style>
