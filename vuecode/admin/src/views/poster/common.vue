<template>
  <div>
    <el-card class="box-card">
      <div slot="header" class="clearfix">
        <span>海报设置</span>
      </div>
      <div class="tip">
        <p>海报开启后前台切换下一个自动调用对应海报背景。您必须保证至少有一个海报在启用状态。</p>
        <p>您可自主添加海报模板，海报尺寸1080(宽)x1920(高)，生成的海报自带信息内容展示区域，无须预留白块区域。</p>
      </div>

      <el-card
        class="img-content-box"
        :body-style="{ padding: '0px',height:'416px',display:'flex','justify-content':'center','align-items':'center',cursor:'pointer' }"
        @click.native="funAdd"
      >
        <div>
          <i class="el-icon-plus" />
          <span class="add">添加海报</span>
        </div>
      </el-card>
      <el-card
        v-for="(item, index) in list"
        :key="index"
        class="img-content-box"
        :body-style="{ padding: '0px' }"
      >
        <div
          class="imgbox"
        >
          <img
            :src="
              item.img_src != ''
                ? item.img_src
                : 'https://shadow.elemecdn.com/app/element/hamburger.9cf7b091-55e9-11e9-a976-7f4d0b07eef6.png'
            "
            class="image"
          >
          <span class="actions">
            <el-button type="text" style="float:left;margin-right:6px;" @click="funEdit(item)"><i class="el-icon-edit-outline" style="margin-right:2px;" />编辑</el-button>
            <el-button type="text" style="float:left;" @click="funPoster(item)"><i class="el-icon-view" style="margin-right:2px;" />预览</el-button>
            <el-button type="text" style="float:right;" @click="funDelete(item)"><i class="el-icon-delete" style="margin-right:2px;" />删除</el-button>
            <div class="clearfix" />
          </span>
        </div>
        <div style="padding: 14px;">
          <div class="bottom clearfix">
            <span class="name">{{ item.name }}</span>
            <span class="audit">状态：</span>
            <el-switch
              v-model="item.is_display"
              class="is_display"
              @change="funDisplay(item)"
            />
          </div>
        </div>
      </el-card>
      <div class="clearfix" />
    </el-card>
    <el-dialog
      v-if="showDialog"
      :title="dialogTitle"
      :visible.sync="showDialog"
      width="500px"
      :close-on-click-modal="false"
    >
      <el-form ref="dialogForm" :model="form" :rules="rules" label-width="80px">
        <el-form-item
          label="海报名称"
          prop="name"
        >
          <el-input v-model="form.name" />
        </el-form-item>
        <el-form-item
          label="背景图"
          prop="img"
        >
          <el-upload
            class="img-uploader"
            :action="apiUpload"
            :headers="headers"
            :show-file-list="false"
            :on-success="handleImgSuccess"
            :before-upload="beforeImgUpload"
          >
            <img
              v-if="imgUrl"
              :src="imgUrl"
              class="img"
            >
            <i
              v-else
              class="el-icon-plus img-uploader-icon"
            />
          </el-upload>
          <span class="smalltip">
            <i class="el-icon-info" />
            建议尺寸1080*1920
          </span>
        </el-form-item>
        <el-form-item label="排序">
          <el-input v-model="form.sort_id" />
        </el-form-item>
        <el-form-item label="是否显示">
          <el-switch v-model="form.is_display" />
        </el-form-item>
        <el-form-item>
          <el-button type="primary" @click="onSubmitDialog('dialogForm')">保存</el-button>
          <el-button @click="showDialog=false">取消</el-button>
        </el-form-item>
      </el-form>
    </el-dialog>
    <Poster
      v-if="showPoster"
      :poster-index="posterIndex"
      :poster-type="posterType"
      @closeDialog="showPoster = false"
    />
  </div>
</template>

<script>
import { posterList, posterAdd, posterEdit, posterDelete, posterDisplay } from '@/api/poster'
import apiArr from '@/api'
import { getToken } from '@/utils/auth'
import Poster from '@/components/Poster'
export default {
  components: {
    Poster
  },
  props: ['type'],
  data () {
    return {
      showPoster: false,
      posterIndex: '',
      posterType: '',
      headers: { admintoken: getToken() },
      fileupload_size: '',
      fileupload_ext: '',
      apiUpload: window.global.RequestBaseUrl + apiArr.uploadPoster,
      showDialog: false,
      dialogTitle: '',
      listLoading: false,
      form: {
        id: '',
        type: '',
        name: '',
        img: '',
        sort_id: 0,
        is_display: true
      },
      imgUrl: '',
      list: [],
      rules: {
        name: [
          { required: true, message: '请输入海报名称', trigger: 'blur' }
        ],
        img: [
          { required: true, message: '请上传背景图', trigger: 'change' }
        ]
      }
    }
  },
  computed: {
    config() {
      return this.$store.state.config
    }
  },
  created() {
    this.fileupload_size = this.config.fileupload_size
    this.fileupload_ext = this.config.fileupload_ext
    this.form.type = this.type
    this.fetchData()
  },
  methods: {
    funAdd(){
      this.dialogTitle = '添加海报'
      this.form = {
        id: '',
        type: this.type,
        name: '',
        img: '',
        sort_id: 0,
        is_display: true
      }
      this.imgUrl = ''
      this.showDialog = true
    },
    funEdit(row){
      this.dialogTitle = '编辑海报'
      this.form = Object.assign(this.form, row)
      this.form.img = '-1'
      this.imgUrl = row.img_src
      this.form.type = this.type
      this.showDialog = true
    },
    handleImgSuccess(res, file) {
      if (res.code === 200) {
        this.imgUrl = URL.createObjectURL(file.raw)
        this.form.img = res.data
      } else {
        this.$message.error(res.message)
        return false
      }
    },
    beforeImgUpload(file) {
      const filetypeArr = file.type.split('/')
      const filetype = filetypeArr[1]
      const configFileExtArr = this.fileupload_ext.split(',')

      if (!configFileExtArr.includes(filetype)) {
        this.$message.error('上传文件格式不允许')
        return false
      }
      if (file.size / 1024 > this.fileupload_size) {
        this.$message.error(`上传文件最大为${this.fileupload_size}kb`)
        return false
      }
      return true
    },
    fetchData () {
      this.listLoading = true
      posterList({ type: this.type }).then(response => {
        this.list = response.data
        this.list.forEach(element => {
          element.is_display = element.is_display == 1
        })
      }).catch(() => {
        this.listLoading = false
      })
    },
    funDelete (row) {
      var that = this
      if (this.list.length <= 1){
        this.$message.error('请至少保留一个海报模板')
        return false
      }
      that
        .$confirm('此操作将永久删除该数据, 是否继续?', '提示', {
          confirmButtonText: '确定',
          cancelButtonText: '取消',
          type: 'warning'
        })
        .then(() => {
          const param = {
            id: row.id
          }
          posterDelete(param).then(response => {
            that.$message.success(response.message)
            that.showDialog = false
            that.fetchData()
            return true
          })
        })
        .catch(() => {})
    },
    funDisplay (row) {
      if (row.is_display == false){
        const arr_display = this.list.filter((value, key, arr) => {
          return value.is_display
        })
        if (arr_display.length == 0){
          row.is_display = true
          this.$message.error('请至少保留一个海报模板为显示状态')
          return false
        }
      }
      const param = {
        id: row.id
      }
      posterDisplay(param).then(response => {
        this.$message.success(response.message)
        this.fetchData()
        return true
      })
    },
    onSubmitDialog(formName){
      const insertData = { ...this.form }
      this.$refs[formName].validate(valid => {
        if (valid) {
          insertData.is_display = insertData.is_display == true ? 1 : 0
          if (insertData.id > 0){
            posterEdit(insertData)
              .then(response => {
                this.$message.success(response.message)
                this.fetchData()
                this.showDialog = false
                return true
              })
              .catch(() => {})
          } else {
            posterAdd(insertData)
              .then(response => {
                this.$message.success(response.message)
                this.fetchData()
                this.showDialog = false
                return true
              })
              .catch(() => {})
          }
        }
      })
    },
    funPoster(row) {
      this.showPoster = true
      this.posterIndex = row.indexid
      this.posterType = this.type == 1 ? 'job' : (this.type == 2 ? 'resume' : 'company')
    }
  }
}
</script>
<style scoped>
.img-uploader {
  display: inline-block;
  border: 1px dashed #d9d9d9;
  border-radius: 6px;
  cursor: pointer;
  width: 122px;
  height: 122px;
}
.img-uploader img {
  width: 120px;
  height: 120px;
  border-radius: 6px;
}
.img-uploader:hover {
  border-color: #409eff;
}
.img-uploader-icon {
  font-size: 28px;
  color: #8c939d;
  width: 120px;
  height: 120px;
  line-height: 120px;
  text-align: center;
}
.img-content-box {
  width: 197px;
  margin-right: 20px;
  float: left;
  margin-bottom:20px;
}
.img-content-box .imgbox .actions{
    position: absolute;
    left: 0px;
    bottom: 0;
    width: 195px;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    background-color: rgba(51, 51, 51, 0.7);
    color: #e3e3e3;
    padding: 0 10px;
    font-size: 14px;
    display: inline-block;
    line-height:30px;
    display:none;
}
.img-content-box:hover .imgbox .actions{
  display:block;
}
.img-content-box .el-button--text{
  color:#fff;
}

.audit {
  font-size:13px;
  color:#666;
}
.is_display{
  position:absolute;
  top:22px;
  right:0px;
}
.name {
  font-size:14px;
  margin-bottom: 14px;
  display: block;
}
.imgbox {
  width: 100%;
  display: block;
  height: 347px;
  border-bottom: 1px solid #e3e3e3;
  background-color: #999;
  overflow:hidden;
  position:relative;
}
.bottom {
  margin-top: 4px;
  line-height: 12px;
  position:relative;
}
.botton {
  padding: 0;
  float: right;
}
.image {
  width: 100%;
  display: block;
}
.empty {
  width: 100%;
  height: 200px;
  text-align: center;
  color: #c1c1c1;
  line-height: 200px;
}
.clearfix:before,
.clearfix:after {
  display: table;
  content: '';
}

.clearfix:after {
  clear: both;
}
.img-content-box .el-icon-plus{
  padding: 0px; font-size: 46px;margin: 0 auto;width: 48px;color: #999;display: block;
}
.add{
  margin-top:20px;
  color:#3a8ee6;
  display:inline-block;
}
.el-form{
  width:400px;
}
</style>

