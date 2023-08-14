<template>
    <div class="emoji-content">
        <span class="emoji-item" v-for="(item, index) in emojiJson" :key="index" @click="chooseEmojiDefault(item)">
            {{item}}
        </span>
    </div>
</template>

<script>
var emojiJson = require("./emoji.json");
emojiJson = emojiJson.data.split(',');
export default {
  name: 'emoji',
  data(){
    return {
        emojiJson:emojiJson
    }
  },
  created(){
  },
  mounted() {
    
  },
  methods:{
    chooseEmojiDefault(e){
        this.$emit("chooseEmojiDefault",e)
    }
  },
  beforeDestroy() {
  }
}
</script>

<style lang="scss" scoped>
.emoji-content{
    height:195px;
    overflow-y:auto;
    .emoji-item{
        width:44px;
        height:34px;
        display:inline-block;
        cursor:pointer;
        font-size:18px;
    }
}
</style>
