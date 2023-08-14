<template>
  <div class="app-container">
    <el-table
      :data="list"
    >
      <el-table-column
        label="页面"
        prop="name"
        min-width="120"
      />
      <el-table-column
        label="分享语句"
        prop="content"
        min-width="200"
      />
      <el-table-column
        align="center"
        label="显示图片"
        min-width="150"
      >
        <template slot-scope="scope">
          <span v-if="scope.row.img==='self'">{{ scope.row.img_self_cn }}</span>
          <span v-else>网站Logo</span>
        </template>
      </el-table-column>
      <el-table-column
        label="说明文字"
        prop="explain"
        min-width="200"
      />
      <el-table-column
        fixed="right"
        label="操作"
        min-width="80"
      >
        <template slot-scope="scope">
          <el-button
            size="small"
            type="primary"
            @click="funEdit(scope.$index, scope.row)"
          >
            编辑
          </el-button>
        </template>
      </el-table-column>
    </el-table>

    <el-dialog
      v-if="dialogFormVisible"
      :title="dialogTitle"
      :visible.sync="dialogFormVisible"
      width="30%"
      :close-on-click-modal="false"
    >
      <el-form
        ref="form"
        class="common-form"
        :model="form"
        label-width="120px"
        :rules="rules"
        :inline-message="false"
      >
        <el-form-item
          label="页面"
          prop="name"
        >
          <el-input
            v-model="form.name"
            disabled
            readonly
          />
        </el-form-item>
        <el-form-item
          label="分享语句"
          prop="content"
        >
          <el-input
            id="form_content"
            v-model="form.content"
            type="textarea"
            rows="4"
          />
          <div class="clearfix" />
          <el-tag
            v-for="(item,index) in form.params_arr"
            :key="index"
            size="small"
            class="param_tag"
            @click="handlerTag('content',item)"
          >
            {{ item.label }}
          </el-tag>
        </el-form-item>
        <el-form-item
          label="显示图片"
        >
          <el-radio-group v-model="form.img">
            <el-radio
              v-if="form.img_self_cn!=''"
              label="self"
            >
              {{ form.img_self_cn }}
            </el-radio>
            <el-radio label="logo">
              网站Logo
            </el-radio>
          </el-radio-group>
        </el-form-item>
        <el-form-item
          label="说明文字"
          prop="explain"
        >
          <el-input
            id="form_explain"
            v-model="form.explain"
            type="textarea"
            rows="4"
          />
          <div class="clearfix" />
          <el-tag
            v-for="(item,index) in form.params_arr"
            :key="index"
            size="small"
            class="param_tag"
            @click="handlerTag('explain',item)"
          >
            {{ item.label }}
          </el-tag>
        </el-form-item>
        <el-form-item label=" ">
          <el-button
            type="primary"
            @click="onSubmit('form')"
          >
            保存
          </el-button>
          <el-button @click="closeDialog">
            取 消
          </el-button>
        </el-form-item>
      </el-form>
    </el-dialog>
  </div>
</template>

<script>
import {
  wechatShareList,
  wechatShareEdit
} from '@/api/wechat_share'

export default {
  data() {
    return {
      dialogFormVisible: false,
      infoLoading: true,
      list: null,
      listLoading: true,
      form: {
        id: '',
        name: '',
        content: '',
        img: '',
        img_self_cn: '',
        explain: '',
        params: '',
        params_arr: []
      },
      rules: {
        name: [
          { required: true, message: '请输入页面', trigger: 'blur' },
          { max: 30, message: '长度在 1 到 30 个字符', trigger: 'blur' }
        ]
      }
    }
  },
  created() {
    this.fetchData()
  },
  methods: {
    fetchData() {
      this.listLoading = true
      wechatShareList({}, 'get')
        .then(response => {
          this.list = response.data.items
          this.listLoading = false
        })
        .catch(() => {})
    },
    onSubmit(formName) {
      const that = this
      const insertData = { ...that.form }
      this.$refs[formName].validate(valid => {
        if (valid) {
          wechatShareEdit(insertData)
            .then(response => {
              this.$message.success(response.message)
              this.closeDialog()
              this.fetchData()
              return true
            })
            .catch(() => {})
        } else {
          return false
        }
      })
    },
    funEdit(index, row) {
      this.form = row
      console.log(this.form)
      this.form.params_arr = JSON.parse(this.form.params)
      this.dialogTitle = '编辑'
      this.dialogFormVisible = true
    },
    closeDialog() {
      this.dialogFormVisible = false
    },
    handlerTag(source, item) {
      const insertTxt = '{' + item.value + '}'
      var elInput = document.getElementById('form_' + source); var startPos = elInput.selectionStart; var endPos = elInput.selectionEnd; if (startPos === undefined || endPos === undefined) return
      var txt = elInput.value; var result = txt.substring(0, startPos) + insertTxt + txt.substring(endPos)
      elInput.value = result
      elInput.focus()
      elInput.selectionStart = startPos + insertTxt.length
      elInput.selectionEnd = startPos + insertTxt.length
      this.form[source] = result
    }
  }
}
</script>

<style scoped>
.el-input,
.el-textarea,
.el-input-group {
  width: 80%;
}
.param_tag{
  margin-right:10px;
  cursor:pointer;
}
</style>
