<template>
  <div class="app-container">
    <div class="tem_div" @click="funAdd()">
      <div class="tem_add">
        <i class="el-icon-circle-plus" style="font-size: 75px;color: #409eff;" />
        <br><br>
        <span class="icon-name" style="font-size: 16px;color: #409eff;">新增模板</span>
      </div>
    </div>
    <div v-for="(item,index) in list" :key="index">
      <div class="tem_div">
        <el-card class="box-card" style="border:none;-webkit-box-shadow:0;box-shadow:0 0 0 0 rgba(0,0,0,.1);">
          <div slot="header" class="clearfix">
            <span>{{ item.temname }}</span>
            <el-button v-if="item.is_sys=='0'" style="float: right; padding: 3px 0" type="text" @click="funDel(item.id)">删除</el-button>
            <el-button style="float: right; padding: 3px 0;margin-right:10px;" type="text" @click="funEdit(item.id)">编辑</el-button>
          </div>
          <div class="tem_title" style="white-space: pre-line;" v-html="item.content_">
            {{ item.content_ }}
          </div>
        </el-card>
      </div>
    </div>
    <el-dialog
      v-if="dialogEditFormVisible"
      title="编辑模板"
      :visible.sync="dialogEditFormVisible"
      width="59%"
      :close-on-click-modal="false"
      @close="closeDialog"
    >
      <diaeditform
        :item-info="itemInfo"
        @closeDia="eaCloseDia('edit')"
        @setDialogEditFormVisible="closeDialog"
        @pageReload="fetchData"
      />
    </el-dialog>
    <el-dialog
      v-if="dialogFormVisible"
      title="新增模板"
      :visible.sync="dialogFormVisible"
      width="59%"
      :close-on-click-modal="false"
      @close="closeDialog"
    >
      <diaaddform
        @closeAddDia=" eaCloseDia('add')"
        @setDialogFormVisible="closeDialog"
      />
    </el-dialog>
  </div>
</template>

<script>
import { tweetsTemplateList, tweetsTemplateDel } from '@/api/tweets_template'
import diaeditform from '@/views/tool/marketing/tweets/template/edit.vue'
import diaaddform from '@/views/tool/marketing/tweets/template/add.vue'

export default {
  components: {
    diaeditform,
    diaaddform
  },
  data() {
    return {
      dialogFormVisible: false,
      dialogEditFormVisible: false,
      list: null,
      listLoading: true,
      itemInfo: null
    }
  },
  created() {
    this.fetchData()
  },
  methods: {
    funEdit(row) {
      var that = this
      that.itemInfo = row
      that.dialogEditFormVisible = true
    },
    funDel(row) {
      var that = this
      that
        .$confirm('此操作将永久删除该数据, 是否继续?', '提示', {
          confirmButtonText: '确定',
          cancelButtonText: '取消',
          type: 'warning'
        })
        .then(() => {
          const param = {
            id: row
          }
          tweetsTemplateDel(param).then(response => {
            that.$message.success(response.message)
            that.fetchData()
            return true
          })
        })
        .catch(() => {})
      console.log(row)
    },
    funAdd() {
      this.dialogFormVisible = true
    },
    goto(target) {
      this.$router.push(target)
    },
    fetchData() {
      this.listLoading = true
      tweetsTemplateList({}).then(response => {
        this.list = response.data.items
        this.listLoading = false
      })
    },
    closeDialog() {
      this.dialogFormVisible = false
    },
    eaCloseDia(type) {
      if (type === 'add') {
        this.dialogFormVisible = false
      } else {
        this.dialogEditFormVisible = false
      }
      this.fetchData()
    }
  }
}
</script>

<style scoped>
.tem_div{
  height:300px;
  width:300px;
  border:1px solid #eee;
  border-radius: 5px;
  float:left;
  margin-right:18px;
  margin-top:18px;
  position:relative;
  box-shadow: 0 2px 12px 0 rgba(0,0,0,.1);
}
.tem_div .tem_add{
  margin:80px auto;
  text-align: center;
  cursor: pointer;
}
.tem_div .tem_del{
  position: absolute;
  right: 10px;
  top: 10px;
  color: #999;
}
.tem_div .tem_name{
  width:250px;
  padding:20px 15px;
  line-height:40px;
  font-size: 24px;
  font-weight: bold;
  border-bottom: 1px solid #eee;
}
.tem_div .tem_title{
  max-height: 150px;
  overflow: hidden;
  color: #6c6c6c;
  line-height: 30px;
  font-size: 14px;
}
</style>
