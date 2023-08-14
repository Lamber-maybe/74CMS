<template>
  <div>
    <div class="cc_top">
      该企业的在招职位
    </div>
    <div class="cc_content" v-if="dataset.length > 0">
      <van-radio-group v-model="selected_id">
        <div class="cc_item" v-for="(item,index) in dataset" :key="index" @click="funSelected(item)">
          <p class="t1 substring">{{item.jobname}}</p>
          <van-radio class="absolute_for_y" :name="item.id"></van-radio>
        </div>
      </van-radio-group>
    </div>
    <van-empty
      image="search"
      description="该企业没有在招职位"
      style="background-color:#fff"
      v-else
    />
    <div class="bottom"><van-button type="primary" block color="#1787fb" @click="funConfirm">确定</van-button></div>
  </div>
</template>

<script>
  import http from '@/utils/http'
  import api from '@/api'
  export default {
    name: "searchJob",
    props:['comid'],
    data () {
      return {
        selected_id: 1,
        row:{},
        dataset: [],
      }
    },
    created(){
      this.fetchData()
    },
    watch:{
      comid(){
        this.fetchData()
      }
    },
    methods:{
      funSelected(row){
        this.row = row
        this.selected_id = row.id
      },
      funConfirm(){
        this.$emit('onConfirm',this.row)
      },
      fetchData () {
        let conditions = {company_id:this.comid}
        http
          .get(api.searchJob, conditions)
          .then(res => {
            this.dataset = [...res.data.items]
          })
          .catch(() => {})
      },
    }
  }
</script>

<style lang="scss" scoped>
  .bottom { position: fixed;left: 0;bottom: 0;right:0;width: 100%;border-top: 1PX solid #f3f3f3;padding: 0 0; }
  .cc_content {
    width: 100%;padding: 0 30px;max-height: 80vh;overflow-y: auto;
    .cc_item {
      position: relative;border-top: 1PX solid #e2e2e2;
      &:nth-of-type(1) { border-top: 0; }
      .van-radio {
        position: absolute;right: 30px;top: 50%;
      }
      .t1 {
        padding: 20px 70px 20px 0;font-size: 28px;
      }
      .t2 {
        padding: 0 70px 20px 0;font-size: 24px;
      }
    }
  }
  .cc_top {
    width: 100%;height: 80px;background-color: #f6f6f6;font-size:30px;line-height:80px;padding-left:20px;
  }
</style>
