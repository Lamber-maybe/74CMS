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
            <span style="color: #666"> {{ item.type == 1 ? '职位模板' : '企业模板' }} <b style="font-size: 12px;color: #666666;font-weight: normal" v-if="item.built_in == 1">（系统模板）</b></span>
          </div>
          <div style="display: flex;flex-direction: column;justify-content: center;align-items: center">
            <span style="color: #999">模板名称</span>
            <div style="margin-top: 16px">{{ item.name }}</div>
            <el-button  size="small" style="margin-top: 38px;color: #999999;font-size: 13px" v-if="item.built_in == 1" @click="funEdit(item.template_id,'see')">查看</el-button>
            <div style="margin-top: 38px" v-else>
              <el-button type="primary" style="margin-right: 20px" size="small" @click="funEdit(item.template_id,'edit')">编辑</el-button>
              <el-button type="danger" size="small" @click="funDel(item.template_id)">删除</el-button>
            </div>
          </div>
        </el-card>
      </div>
    </div>

  </div>
</template>

<script>
import { wxTemplateList, templateDelete } from '@/api/marketing_wxoffiaccount'

export default {
  data() {
    return {
      list: null,
      listLoading: true
    }
  },
  created() {
    this.fetchData()
  },
  methods: {
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
            template_id: row
          }
          templateDelete(param).then(response => {
            that.$message.success(response.message)
            that.fetchData()
            return true
          })
        })
        .catch(() => {})
      console.log(row)
    },
    funAdd() {
      this.$emit('addTeamplate')
    },
    funEdit(id,name) {
      this.$emit('addTeamplate', {id:id,name:name})
    },
    fetchData() {
      this.listLoading = true
      wxTemplateList({}).then(response => {
        this.list = response.data.rows
        this.listLoading = false
      })
    }
  }
}
</script>

<style scoped>
  .tem_div{
    height:230px;
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
    margin:60px auto;
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
