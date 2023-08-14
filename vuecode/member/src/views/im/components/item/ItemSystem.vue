<template>
  <div>
    <div class="datetime">{{ item.datetime }}</div>
    <div class="system">
      <div class="system-content">{{item.message}}</div>
    </div>   
  </div>      
</template>

<script>
export default {
  name: 'MessageItem',
  props:['item'],
  data(){
    return {
    }
  },
  created(){
    
  },
  mounted() {
    
  },
  methods:{
    
  },
  beforeDestroy() {
  }
}
</script>

<style lang="scss" scoped>
.datetime {
  text-align: center;
  font-size: 12px;
  color: #999999;
  margin-bottom: 18px;
}
.system-content{
  font-size: 13px;
  background-color: #F9FAFB;
  display: table;
  margin: 0 auto;
  padding: 6px 18px;
  border-radius: 14px;
  color: #666;
  font-weight: 400;
}
</style>
