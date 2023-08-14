<template>
  <div>
     <warning :title="title"></warning>
     <div class="shield_header">
       <span>您已屏蔽{{num}}个公司</span>
       <el-button icon="el-icon-plus" @click="dialogVisible=true">添加屏蔽企业</el-button>
     </div>
     <ul class="shield_box">
        <li v-for="(item) in blacklist" :key="item.id">
          <span class="margin">{{item.companyname}}</span>
          <span class="margin2" @click="handleDelete(item.id)">取消屏蔽</span>
        </li>
     </ul>


     <el-dialog title="添加屏蔽企业"  :visible.sync="dialogVisible"  width="540px">
       <shield-dialog ref="sonV" @search="search" :AllSelect="AllSelect" :dialog="dialogVisible" @footer_btn="btn"  @selectChangebox="selectArr"></shield-dialog>
       <div slot="footer" class="dialog-footer">
          <span class="footer_btn" v-show="switch_btn">
            <span><el-checkbox v-if="checkboxIndex"  v-model="checked" @change="handleAllSelect">{{checked?'取消全选':'全选'}}</el-checkbox></span>
            <el-button type="primary" @click="handleShield ">屏蔽所选公司</el-button>
          </span>
       </div>
     </el-dialog>
  </div>
</template>

<script>
  import http from '@/utils/http'
  import api from '@/api'
  import warning from '@/components/Warning'
  import shieldDialog from './shieldDialog.vue'
  export default{
    data(){
      return{
        checked:false,
        title:'屏蔽后，关键词覆盖到的企业都不能看到我！',
        dialogVisible:false,
        D_selectArr:[],
        blacklist:[],
        is_display:0,
        switch_btn:false,
        AllSelect:[],
        num:0,
        checkboxIndex:0
      }
    },
    created() {
     this.fetchData()
    },
    computed:{
    },
    methods:{
      // 添加屏蔽企业
      handleShield(){
        http.post(api.resume_privates_add_blacklist ,{id:this.D_selectArr}).then(()=>{
          this.fetchData()
        })
        this.dialogVisible = false
        this.checked=false
      },
      // 获取屏蔽企业id
      selectArr(shield_id){
        this.D_selectArr.push(shield_id.id)
      },
      // 底部btn显示和隐藏
      btn(btn_type){
       this.switch_btn=btn_type
      },
      // 全选和取消全选type
      search(select_val){
        this.checkboxIndex=0
        this.AllSelect=select_val
        this.AllSelect.forEach(item=>{
          if(item.in_blacklist==0){
            this.checkboxIndex++
          }
        })
      },
      // 获取屏蔽企业列表
      fetchData(){
        http.get(api.resume_privates_info).then(res=>{
          this.blacklist=[...res.data.blacklist]
          this.num=this.blacklist.length
        })
      },
      // 删除屏蔽企业
      handleDelete(id){
        http.post(api.resume_privates_deleteBlacklist,{id:id}).then(()=>{
          this.fetchData()
        })
      },
      // 切换全选和取消全选
      handleAllSelect(selectVal){
        if(selectVal){
          this.AllSelect.map(item=>{
            if(item.in_blacklist==0){
              item.type=true
            this.D_selectArr.push(item.id)
            }
          })
          this.$refs.sonV.handleAllSelect(this.AllSelect);
        }else{
          this.AllSelect.map(item=>{
            item.type=false
          })
          this.D_selectArr=[]
          this.$refs.sonV.handleAllSelect(this.AllSelect);
        }
      }
    },
    components:{
      warning,
      shieldDialog
    }
  }
</script>

<style lang="scss"  scoped>
  .shield_header{
    color:#a1a4a9;
    font-size: 13px;
    display: flex;
    justify-content: space-between;
    line-height: 50px;
  }
  .shield_header span{
    margin-left: 30px;
  }
  .shield_header .el-button{
    margin: 6px 10px;
    color:#1787fb ;
    border: 1px solid #1787fb;
    font-size:13px ;
    padding: 10px 16px;
    height: 36px;
  }
  .shield_box li{
    height: 50px;
    line-height: 50px;
    display: flex;
    justify-content: space-between;
  }
  .margin{
    margin-left:30px;
    color: #333333;
    font-size: 13px;
  }
  .margin2{
    margin-right:42px;
    color: #1787fb;
    cursor: pointer;
    display: none;
  }
  .shield_box li:hover{
    background: #fafafa;
  }
  .shield_box li:hover .margin2{
    display: block;
  }

  .footer_btn{
    width: 100%;
    display: flex;
    justify-content:space-between ;
  }
  .el-checkbox{
    margin-top: 8px;
  }
</style>
