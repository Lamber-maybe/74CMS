<template>
<div class="left">
  <div class="l-top">
    <el-checkbox v-model="noread">未读消息</el-checkbox>
    <el-dropdown trigger="click">
      <div class="config"></div>
      <el-dropdown-menu slot="dropdown">
        <el-dropdown-item @click.native="openHellomsg">招呼语管理</el-dropdown-item>
        <el-dropdown-item @click.native="openPhrase">常用语管理</el-dropdown-item>
        <el-dropdown-item @click.native="openBlacklist">黑名单管理</el-dropdown-item>
      </el-dropdown-menu>
    </el-dropdown>
  </div>
  <div class="search">
    <div class="search-form">
      <input type="text" class="search-input" v-model.trim="search_keyword" />
      <div class="search-btn" @click="filterChat"></div>
    </div>
  </div>
  <div class="chat-list">
    <div
      v-show="item.hide===false"
      class="chat-item"
      :class="item.chat_id == $store.state.imChatid ? 'active' : ''"
      v-for="(item, index) in chatList"
      :key="index"
      @click="onclickChangeTarget(item)"
    >
      <div class="stick" v-if="item.stick == 1"></div>
      <div class="remove" @click.stop="removeChat(item)"></div>
      <div class="photo">
        <span class="new" v-if="item.new >0 "></span>
        <img class="image" :src="item.avatar" />
      </div>
      <div class="info">
        <div class="name">{{ item.nickname }}</div>
        <div class="detail">{{ item.detail }}</div>
      </div>
      <div class="time">
        {{ item.updatetime }}
      </div>
      <div class="clear"></div>
    </div>
  </div>
  <el-dialog title="招呼语管理" width="800px" :visible.sync="showHellomsg" v-if="showHellomsg" :close-on-click-modal="false">
    <HellomsgConf :dataset="hello_msglist"></HellomsgConf>
  </el-dialog>
  <el-dialog title="黑名单管理" width="576px" :visible.sync="showBlacklist" v-if="showBlacklist" :close-on-click-modal="false">
    <BlacklistConf :dataset="blacklist"></BlacklistConf>
  </el-dialog>
</div>

</template>

<script>
import HellomsgConf from './HellomsgConf'
import BlacklistConf from './BlacklistConf'
import http from "@/utils/http";
import api from "@/api";
export default {
  name: 'ChatList',
  props:['websocketOk'],
  components: {
    HellomsgConf,
    BlacklistConf
  },
  data(){
    return {
      hello_msglist:[],
      blacklist:[],
      showBlacklist:false,
      showHellomsg:false,
      search_keyword:'',
      noread: false,
      chatList:[],
    }
  },
  watch: {
    websocketOk(val) {
      if (val == true) {
        this.getChatList(true)
      }
    },
    noread(){
      this.filterChat()
    }
  },
  created(){

  },
  mounted() {

  },
  methods:{
    openPhrase(){
      this.$emit('showPhrase')
    },
    openBlacklist(){
      this.showBlacklist = true
      this.getBlacklist()
    },
    openHellomsg(){
      this.showHellomsg = true
      this.getHelloMsglist()
    },
    getBlacklist () {
      http
        .post(api.im_blacklist, {token:this.$store.state.imToken})
        .then(res => {
          this.blacklist = [...res.data.items]
        })
        .catch(() => {})
    },
    getHelloMsglist () {
      http
        .post(api.im_hellomsg_list, {token:this.$store.state.imToken})
        .then(res => {
          this.hello_msglist = [...res.data.items]
        })
        .catch(() => {})
    },
    removeChat(chat){
      this.$confirm('确定删除此对话吗?', '提示', {
        confirmButtonText: '确定',
        cancelButtonText: '取消',
        type: 'warning'
      }).then(() => {
        http
        .post(api.im_remove_chat, {token:this.$store.state.imToken,chat_id:chat.chat_id})
        .then(res => {
          if(res.code==200){
            this.chatList.forEach((item,index)=>{
              if(item.chat_id==chat.chat_id){
                this.chatList.splice(index,1);
                if(this.$store.state.imChatid==item.chat_id){
                  this.changeTarget({nickname:''})
                }
              }
            })
          }
        })
        .catch(() => {});
      }).catch(() => {

      });
    },
    filterChat(){
      var that = this
      this.$store.state.imUnreaded = false
      this.chatList.forEach((item,index)=>{
        if(that.search_keyword!='' && item.nickname.indexOf(that.search_keyword)==-1){
          that.$set(that.chatList, index,Object.assign(item,{hide:true}))
        }else if(that.noread===true && item.new==0){
          that.$set(that.chatList, index,Object.assign(item,{hide:true}))
        }else{
          that.$set(that.chatList, index,Object.assign(item,{hide:false}))
        }
        if(item.new>0){
          this.$store.state.imUnreaded = true
        }
      })
    },
    getChatList(init) {
      http
        .post(api.im_chat_list, {token:this.$store.state.imToken})
        .then(res => {
          this.chatList = res.data.items;
          this.filterChat()
          if(init===true){
            this.chatList.forEach(element => {
              if(this.$store.state.imChatid==element.chat_id){
                this.changeTarget(element)
              }
            });
          }
        })
        .catch(() => {});
    },
    onclickChangeTarget(item){
      if(item.chat_id==this.$store.state.imChatid){
        return false;
      }
      this.changeTarget(item)
    },
    changeTarget(item) {
      this.removeRedPoint(item.chat_id);
      this.$emit("setTatgetinfo",item)
    },
    // addRedPoint(chat_id){
    //   this.chatList.forEach(element => {
    //     if(chat_id==element.chat_id){
    //       element.new = 1
    //       this.$store.state.imUnreadedCounter++
    //     }
    //   });
    // },
    removeRedPoint(chat_id){
      this.chatList.forEach(element => {
        if(chat_id==element.chat_id){
          this.$store.state.imUnreaded = false
          element.new = 0
        }
      });
    }
  },
  beforeDestroy() {
  }
}
</script>

<style lang="scss" scoped>
.left {
  width: 280px;
  height: 762px;
  float: left;
  .l-top {
    width: 100%;
    height: 50px;
    line-height: 50px;
    font-size: 12px;
    color: #666666;
    padding-left: 14px;
    background-color: #f9f9f9;
    position: relative;
    .el-dropdown{
      position: absolute;
      top: 16px;
      right: 14px;
      cursor:pointer;
      .config {
        width: 24px;
        height: 20px;
        background-image: url("../../../assets/images/im/config.png");
        background-repeat:no-repeat;
        background-position:center;
        background-size:20px;
      }
    }
  }
}
.chat-list {
    height: 646px;
    overflow-y: auto;
    .chat-item {
      display: flex;
      cursor: pointer;
      height: 76px;
      position: relative;
      &.active {
        background-color: #f6f9fe;
      }
      &:hover{
        background-color:#fafcfe;
      }
      &:hover .remove {
        flex: 0.7;
        opacity: 1;
        margin-left: 6px;
        margin-right: -10px;
      }
      .stick {
        position: absolute;
        top: 0;
        right: 0;
        background-image: url("../../../assets/images/im/stick.png");
        width: 12px;
        height: 12px;
      }
      .remove {
        opacity: 0;
        background-image: url("../../../assets/images/im/remove.png");
        height: 76px;
        background-size: 13px;
        background-position: center;
        background-repeat: no-repeat;
        transition: all 0.5s;
      }
      .photo {
        padding: 18px 14px;
        position: relative;
        .new {
          position: absolute;
          width: 8px;
          height: 8px;
          background-color: red;
          border-radius: 8px;
          right: 18px;
        }
        .image {
          width: 40px;
          height: 40px;
          border-radius: 40px;
        }
      }
      .info {
        padding: 18px 0;
        flex: 4;
        line-height: 20px;
        .name {
          font-size: 14px;
          color: #333;
          width: 130px;
          overflow: hidden;
          text-overflow: ellipsis;
          white-space: nowrap;
        }
        .detail {
          font-size: 12px;
          color: #999;
          width: 130px;
          overflow: hidden;
          text-overflow: ellipsis;
          white-space: nowrap;
        }
      }
      .time {
        width: 70px;
        font-size: 12px;
        color: #999;
        margin: 18px 14px;
        position:absolute;
        right:0;
        text-align:right;
      }
    }
  }
  .search {
    .search-form {
      width: 250px;
      height: 32px;
      background: #ffffff;
      border: 1px solid #e2e2e2;
      border-radius: 16px;
      margin: 16px;
      position: relative;
      .search-input {
        width: 188px;
        height: 30px;
        line-height: 30px;
        border: 0;
        margin-left: 20px;
        color: #666;
      }
      .search-btn {
        position: absolute;
        top: 0;
        right: 0;
        width: 44px;
        height: 30px;
        background-image: url("../../../assets/images/im/search.png");
        background-size: 14px;
        background-repeat: no-repeat;
        background-position: center;
        cursor: pointer;
      }
    }
  }

::-webkit-scrollbar{
  width: 6px;
  background: #fff;
}
::-webkit-scrollbar-thumb{
  width: 6px;
  height: 30px;
  border-radius: 10px;
  background: #e3e3e3;
}

</style>
