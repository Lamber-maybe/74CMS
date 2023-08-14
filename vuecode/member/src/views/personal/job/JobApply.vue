<template>
  <div>
    <el-table  :data="dataset" :header-cell-style="{background:'#fcfcfc',color:'#909399'}" v-loading="listLoading"  style="width: 100%" @selection-change="handleSelectionChange">
      <el-table-column type="selection"></el-table-column>
      <el-table-column  prop="jobname" width="225"  label="职位名称">
        <template slot-scope="scope">
          <span class="name_title" @click="handlerOuterLink(scope.row.job_link_url_web)">{{scope.row.jobname}}</span>
        </template>
      </el-table-column>
      <el-table-column  prop="companyname" width="259"   label="公司名称">
        <template slot-scope="scope">
          <span class="company_title" @click="handlerOuterLink(scope.row.company_link_url_web)">{{scope.row.companyname}}</span>
        </template>
      </el-table-column>
      <el-table-column align="center">
        <template slot="header">
          <el-dropdown trigger="click" @command="filterStatus">
            <span class="el-dropdown-link" style="cursor:pointer;color:rgb(144, 147, 153)">
              {{status_label}}<i class="el-icon-arrow-down el-icon--right"></i>
            </span>
            <el-dropdown-menu slot="dropdown">
              <el-dropdown-item v-for="(item,index) in optionStatus" :key="index" :command="item">{{item.text}}</el-dropdown-item>
            </el-dropdown-menu>
          </el-dropdown>
        </template>
        <template slot-scope="scope">
          <el-tag size="medium" type="success" v-if="scope.row.status_code=='agree'">{{scope.row.status_text}}</el-tag>
          <el-tag size="medium" type="info" v-if="scope.row.status_code=='pause'">{{scope.row.status_text}}</el-tag>
          <el-tag size="medium" type="danger" v-if="scope.row.status_code=='refuse'">{{scope.row.status_text}}</el-tag>
          <el-tag size="medium" v-if="scope.row.status_code=='is_look'">{{scope.row.status_text}}</el-tag>
          <el-tag size="medium" type="warning" v-if="scope.row.status_code=='no_look'">{{scope.row.status_text}}</el-tag>
        </template>
      </el-table-column>
      <el-table-column align="center" width="150">
        <template slot="header">
          <el-dropdown trigger="click" @command="filterSettr">
            <span class="el-dropdown-link" style="cursor:pointer;color:rgb(144, 147, 153)">
              {{settr_label}}<i class="el-icon-arrow-down el-icon--right"></i>
            </span>
            <el-dropdown-menu slot="dropdown">
              <el-dropdown-item v-for="(item,index) in optionSettr" :key="index" :command="item">{{item.text}}</el-dropdown-item>
            </el-dropdown-menu>
          </el-dropdown>
        </template>
        <template slot-scope="scope">
          <span class="date_title">{{scope.row.addtime | timeFilter}}</span>
        </template>
      </el-table-column>
      <el-table-column align="center"   label="操作">
        <template slot-scope="scope">
          <el-button size="mini" @click="handlerDel(scope.row)">删除</el-button>
        </template>
      </el-table-column>

    </el-table>
    <div class="checkbox_wrapper">
        <el-button size="mini" @click="handlerDelBatch">删除</el-button>
    </div>
    <pagination :total="total" :current-page="params.page"  :page-size="params.pagesize"  @handleCurrentChange="doSearch"></pagination>
  </div>
</template>

<script>
  import { formatTime } from '@/utils/index'
  import http from '@/utils/http'
  import api from '@/api'
  export default{
    data(){
      return{
        optionStatus: [
          { text: '不限', value: '' },
          { text: 'hr未查看', value: 1 },
          { text: 'hr已查看', value: 2 },
          { text: '同意面试', value: 3 },
          { text: '已被婉拒', value: 4 },
          { text: '停止招聘', value: 5 }
        ],
        optionSettr:[
          { text: '不限', value: '' },
          { text: '3天内', value: 3 },
          { text: '7天内', value: 7 },
          { text: '15天内', value: 15 },
          { text: '30天内', value: 30 },
        ],
        status_label:'反馈状态',
        settr_label:'投递时间',
         listLoading:false,
         total:1,
         params: {
           status:'',
           settr:'',
           page: 1,
           pagesize: 10,
         },
         dataset:[],
         multipleSelection:[]
      }
    },
    created() {
      this.fetchData(true)
    },
    filters: {
      timeFilter(timestamp) {
        return formatTime(timestamp, '{y}-{m}-{d} {h}:{i}', true)
      },
    },
    methods:{
      fetchData(init){
        this.listLoading=true
          http.get(api.personal_manage_job_apply,this.params).then((res)=>{
            this.dataset=[...res.data.items]
            this.listLoading=false
            if(init==true){
              this.fetchTotal()
            }
          })
      },
      fetchTotal(){
        http.get(api.personal_manage_job_apply_total,this.params).then((res)=>{
             this.total=res.data
        })
      },
      doSearch(page){
        this.params.page=page
        this.fetchData()
      },
      handlerOuterLink(url){
        window.open(url)
      },
      handleSelectionChange(val) {
        this.multipleSelection = val;
      },
      // 删除
      handlerDel(item){
        this.$confirm('确定删除该职位吗？','系统提示',{type:'warning'})
          .then(() => {
            http
              .post(api.personal_manage_job_apply_del, { id: item.id })
              .then(() => {
                this.$message({
                  type: 'success',
                  message: '删除成功'
                })
                this.fetchData(true)
              })
              .catch(() => {})
          })
          .catch(() => {
            // on cancel
          })
      },
      handlerDelBatch () {
        if(this.multipleSelection.length==0){
          this.$message.error('请选择职位')
          return false
        }
        let idarr = [];
        this.multipleSelection.forEach(element => {
          idarr.push(element.id)
        });
        this.$confirm('确定删除选中的职位吗？','系统提示',{type:'warning'})
          .then(() => {
            http
              .post(api.personal_manage_job_apply_del_batch, { id: idarr })
              .then(() => {
                this.$message({
                  type: 'success',
                  message: '删除成功'
                })
                this.fetchData(true)
              })
              .catch(() => {})
          })
          .catch(() => {
            // on cancel
          })
      },
      filterStatus(item) {
        this.params.page = 1
        this.params.status = item.value
        if(item.value>0){
          this.status_label = item.text
        }else{
          this.status_label = '反馈状态'
        }
        this.fetchData(true)
      },
      filterSettr(item) {
        this.params.page = 1
        this.params.settr = item.value
        if(item.value>0){
          this.settr_label = item.text
        }else{
          this.settr_label = '投递时间'
        }
        this.fetchData(true)
      }
    }
  }
</script>

<style lang="scss" scoped>
  .name_title{
    cursor:pointer;
    font-size:14px ;
    color: #1787fb;
  }
  .name_title:hover{
    text-decoration:underline;
  }
  .company_title{
    cursor:pointer;
    font-size: 13px;
    color: #333333;
  }
  .company_title:hover{
    text-decoration:underline;
  }
  .state_box{
    display: inline-block;
    padding: 0px 11px;
    font-size: 12px;
    border-radius: 5px;
  }
 .bg1{
    color: #e9b16d;
    background: #fdf6ec;
    border: 1px solid #faecd8;
  }
  .bg2{
    color: #f68282;
    background: #fef0f0;
    border: 1px solid #fde2e2;
  }
  .bg3{
    color: #6899f8;
    background: #ecf5ff;
    border: 1px solid #d9ecff;
    padding: 0 18px;
  }
  .date_title{
    // font-size: 12px;
    color: #333333;
  }
  .checkbox_wrapper{
    margin-top: 20px;
  }
  .checkbox_wrapper .el-checkbox{
    margin: 0  22px 0 14px;
  }
</style>
