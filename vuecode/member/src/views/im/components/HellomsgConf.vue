<template>
  <div class="maincontent" v-loading="loading">
    <ul class="listbox">
      <li class="li" v-for="(item,index) in dataset" :key="index">
        <div class="showbox">
          <span class="content">{{item.content}}</span>
          <a href="javascript:;" class="text" :class="item.selected==1?'selected':''" @click="select(item,index)">{{item.selected==1?'已选':'选择'}}</a>
        </div>
      </li>
    </ul>
  </div>
</template>

<script>
import http from "@/utils/http";
import api from "@/api";
export default {
  name: 'HellomsgConf',
  props:['dataset'],
  components: {
  },
  data(){
    return {
      loading:true,
      showAdd:false,
      addcontent:''
    }
  },
  watch: {
    dataset() {
      this.loading = false
    }
  },
  created(){
  },
  mounted() {
    
  },
  methods:{
    select(item,index){
      this.dataset.forEach(element => {
        element.selected = 0
      });
      this.dataset[index].selected = 1
      http
        .post(api.im_hellomsg_select, {token:this.$store.state.imToken, id:item.id })
        .then(res=>{
          if(res.code!=200){
            this.$message.error(res.message)
          }
        })
        .catch(() => {});
    }
  },
  beforeDestroy() {
  }
}
</script>

<style lang="scss" scoped>
.listbox{
}
.showbox{
  display:inline-block;
}
.maincontent{
  height:425px;
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
  width:680px;
  display:inline-block;
}
.text{
  display:inline-block;
  width:40px;
  height:40px;
  text-align: center;
  color:#1787FB;
  &.selected{
    color:#FF5544;
  }
}
</style>
