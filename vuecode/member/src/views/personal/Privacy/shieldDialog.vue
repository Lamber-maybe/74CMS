<template>
  <div>
      <div class="s_bar">
        <el-input  placeholder="请输入内容" v-model="search_text" class="input-with-select" :style="{'border': 0}"></el-input>
        <el-button @click="fetchData" class="el_button" icon="el-icon-search"></el-button>
      </div>
      <div class="text" v-if="!changeBox">
        <span>可通过以下方式搜索公司</span>
        <p>公司全称：如“百度（中国）有限公司”</p>
        <p>公司简称：如“百度”</p>
        <p>屏蔽企业后，该企业将无法查看您的简历</p>
      </div>

      <div v-else class="search_wrapper">
        <div class="search_box" v-if="!search_list.length==0">
          <p>与{{this.search_text}}有关的公司共{{num}}个</p>
          <ul  class="search_list">
            <li v-for="(i,idx) in search_list" :key="idx">
              <div class="checkbox">
                <el-checkbox v-if="!i.in_blacklist" :checked="i.type" v-model="i.type"  @change="selectIndex(i)"></el-checkbox>
              </div>
              <div class="search_list_text">
                  <p>{{i.companyname}}</p>
                  <p v-if="i.scale_text" class="people"><span>{{i.scale_text}}</span>  |  <span>{{i.trade_text}}</span>  |  <span>{{i.nature_text}}</span></p>
              </div>
              <div class="shield_box" v-if="i.in_blacklist">
               已屏蔽该企业
              </div>
            </li>
          </ul>
        </div>
        <div v-else class="search_error">
          没有搜到您搜索的公司
        </div>

      </div>
  </div>
</template>

<script>
  import http from'@/utils/http'
  import api from'@/api'
  export default{
    data(){
      return{
        search_text:'',
        changeBox:false,
        num:0,
        search_list:[],
        selectChangebox:[]
      }
    },
    props:['dialog','AllSelect'],
    watch:{
      dialog(a){
        if(a==true){
          this.changeBox=false
          this.$emit('footer_btn',false)
        }
        this.search_text=''
      },
      changeBox(){
        this.$emit('footer_btn',this.changeBox)
      },
    },

    methods:{
      // 搜索框数据请求
      fetchData(){
        this.search_list=[]
        if(this.search_text!=''){
          this.changeBox=true
          http.get(api.resume_privates_searchCompany,{keyword:this.search_text}).then(res=>{
            if(parseInt(res.code)==200){
              this.num=res.data.items.length
              this.search_list=[...res.data.items]
              this.search_list.forEach(item=>{
                item.type=false
              })
              this.$emit('search',this.search_list)
            }
          })
        }else{
          this.changeBox=false
          this.$message.error('请输入搜索内容');
        }
      },
      // 用户当前点击的企业名称数据
       selectIndex(value) {
        this.$forceUpdate()
        this.$emit('selectChangebox',value)
      },
      // 全选和取消全选
      handleAllSelect(AllSelect){
          this.$set(this.search_list,this.search_list.type,AllSelect.type)
        this.$forceUpdate()

      }
    }
  }
</script>

<style lang="scss" scoped>
  .el-input{
    width: 419px;
    border: 1px solid #1787fb;
    vertical-align: middle;
  }
  .el_button{
    background: #1787fb !important;
    font-size: 22px;
    color: #fafcff !important;
    padding: 8.5px 20px;
    vertical-align: middle;
    margin-left: -1px;
  }
  .text span{
    display: inline-block;
    margin: 28px 0;
    font-size:14px ;
    color:#747474 ;
  }
  .text{
    margin-bottom: 60px;
  }
  .text p{
    margin-bottom: 16px;
    color: #999999;
    font-size: 12px;
  }
  .search_list{
    height: 317px;
    overflow-y: auto;
  }
  .search_list li{
    border-bottom: 1px solid #ebeef5;
    display: flex;
  }
  .search_box>p{
    padding: 22px 0;
  }
  .search_box .people {
    font-size:13px;
    color:#c6c6c6;
   }
   .checkbox{
     height: 16px;
     margin: 25px 27px 23px 0;
   }
   .search_list_text{
    flex: 1;
   }
  .shield_box{
     margin-right: 20px;
     line-height: 64px;
     color: #999;
  }
  .search_list_text p:nth-of-type(1){
   margin-top: 10px;
  }
  .search_list::-webkit-scrollbar{
    width: 4px;
    height: 4px;
  }
  .search_list::-webkit-scrollbar-thumb{
    border-radius: 5px;
    background: #dddddd;
  }
  .search_list::-webkit-scrollbar-track{
    border-radius: 0;
    background: #f1f1f1;
  }
  .search_error{
   text-align: center;
   line-height:317px ;
  }
</style>
