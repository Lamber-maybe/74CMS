<template>
  <div class="maincontent" id="maincontent">
    <ul class="listbox">
      <li class="li" v-for="(item,index) in dataset" :key="index">
        <div class="editbox" v-show="item.edit===true">
          <el-input type="textarea" maxlength="200" resize="none" :autofocus="true" show-word-limit class="editinput" v-model.trim="item.content" placeholder="请输入内容"></el-input>
          <el-button type="primary" size="mini" @click="saveItem(item,index)">确定</el-button>
          <el-button size="mini" @click="closeEdit(item,index,false)">取消</el-button>
        </div>
        <div class="showbox" v-show="item.edit===false">
          <span class="content">{{item.content}}</span>
          <a href="javascript:;" class="op up" @click="changeSort(item,index,1)"></a>
          <a href="javascript:;" class="op down" @click="changeSort(item,index,0)">&nbsp;</a>
          <a href="javascript:;" class="text" @click="openEdit(item,index)">编辑</a>
          <a href="javascript:;" class="text" @click="del(item,index)">删除</a>
        </div>
      </li>
    </ul>
    <div class="addtit" v-if="dataset.length<15" @click="showAdd=true;scrollBottom()">添加常用语<span class="tip">（最多可添加 15 条，还可添加 {{15-dataset.length}} 条）</span></div>
    <div class="addbox" v-if="showAdd">
      <el-input
        type="textarea"
        placeholder="请输入内容"
        maxlength="200"
        resize="none"
        :autofocus="true"
        show-word-limit
        :rows="4"
        v-model.trim="addcontent">
      </el-input>
    </div>
    <div class="addbtn" v-if="showAdd">
      <el-button type="primary" size="small" @click="submit">确定</el-button>
      <el-button size="small" @click="showAdd=false;addcontent=''">取消</el-button>
    </div>
  </div>
</template>

<script>
import http from "@/utils/http";
import api from "@/api";
export default {
  name: 'PhraseConf',
  props:['dataset'],
  components: {
  },
  data(){
    return {
      tmpContent:'',
      showAdd:false,
      addcontent:''
    }
  },
  created(){
  },
  mounted() {

  },
  methods:{
    scrollBottom(){
      this.$nextTick(() => {
        document.getElementById("maincontent").scrollTop = document.getElementById("maincontent").scrollHeight
      })
    },
    del(item,index){
      this.$confirm('确定删除此条信息吗?', '提示', {
        confirmButtonText: '确定',
        cancelButtonText: '取消',
        type: 'warning'
      }).then(() => {
        http
          .post(api.im_phrase_del, { token:this.$store.state.imToken,id:item.id })
          .then(res=>{
            if(res.code==200){
              this.dataset.splice(index,1)
              this.$message({type:'success',message:res.message})
            }else{
              this.$message.error(res.message)
            }
          })
          .catch(() => {});
      }).catch(() => {

      });
    },
    submit(){
      if(this.addcontent==''){
        return false;
      }
      http
        .post(api.im_phrase_add, { token:this.$store.state.imToken,content:this.addcontent })
        .then(res=>{
          if(res.code==200){
            this.$message({type:'success',message:res.message})
            res.data.edit = false
            this.dataset.push(res.data)
            this.addcontent = ''
            this.showAdd = false
          }else{
            this.$message.error(res.message)
          }
        })
        .catch(() => {});
    },
    changeSort(item,index,isup){
      if(isup==1){
        if(index>0){
          let tmp = this.dataset[index-1]
          this.$set(this.dataset, index-1, item)
          this.$set(this.dataset, index, tmp)
        }
      }else{
        if(index<this.dataset.length-1){
          let tmp = this.dataset[index+1]
          this.$set(this.dataset, index+1, item)
          this.$set(this.dataset, index, tmp)
        }
      }
      http
        .post(api.im_phrase_sort, { token:this.$store.state.imToken,id:item.id,isup:isup })
        .then(()=>{})
        .catch(() => {});
    },
    saveItem(item,index){
      http
        .post(api.im_phrase_edit, { token:this.$store.state.imToken,id:item.id,content:item.content })
        .then(res => {
          if(res.code==200){
            this.$message({type:'success',message:res.message})
            this.closeEdit(item,index,true)
          }else{
            this.$message.error(res.message)
          }
        })
        .catch(() => {});
    },
    openEdit(item,index){
      this.tmpContent = item.content
      item.edit = true
      this.$set(this.dataset, index, item)
    },
    closeEdit(item,index,isSave){
      if(isSave===false){
        item.content = this.tmpContent
      }
      item.edit = false
      this.$set(this.dataset, index, item)
    }
  },
  beforeDestroy() {
  }
}
</script>

<style lang="scss" scoped>
.addbox{
  padding:0 20px;
}
.addbtn{
  margin:20px;
}
.addtit{
  padding:40px 20px 20px;
  cursor:pointer;
  color:#1687FB;
  font-size:14px;
  .tip{
    color:#999999;
  }
}
.listbox{
}
.editinput{
  margin-bottom:10px;
  width:724px;
}
.editbox{
  display:inline-block;
}
.showbox{
  display:inline-block;
}
.maincontent{
  height:625px;
  overflow-y:auto;
}
.li{
  min-height:40px;
  line-height:24px;
  position:relative;
  padding-left:20px;
}
.li::before{
  content:'';
  position:absolute;
  left:0;
  top:12px;
  margin-top:-2.5px;
  width:5px;
  height:5px;
  background:#999;
  border-radius:50%;
}
.content{
  width:580px;
  display:inline-block;
}
.op{
  display:inline-block;
  width:30px;
  height:20px;
}
.text{
  display:inline-block;
  width:40px;
  height:40px;
  text-align: center;
  color:#C6C8D0;
}
.up{
  background-image:url("../../../assets/images/im/up.jpg");
  background-repeat:no-repeat;
  background-position:bottom;
  background-size:12px 14px;
}
.down{
  background-image:url("../../../assets/images/im/down.jpg");
  background-repeat:no-repeat;
  background-position:bottom;
  background-size:12px 14px;
}
</style>
