<template>
  <div>
     <ul class="details">
      <li v-if="isVideo===true"><span>面试形式：</span>视频面试</li>
      <li><span>面试职位：</span>{{detailItem.jobname}}</li>
      <li><span>公司名称：</span>{{detailItem.companyname}}</li>
      <li><span>面试时间：</span>{{detailItem.interview_time|interviewTimeFilter}}</li>
      <li v-if="isVideo===false"><span>面试地址：</span>{{detailItem.address}}</li>
      <li><span>联系电话：</span><span class="telColor">{{detailItem.tel}}</span>（{{detailItem.contact}}）</li>
      <li v-if="isVideo===false"><span>备<strong></strong>注：</span>{{detailItem.note}}</li>
    </ul>
  </div>

</template>

<script>
import { parseTime } from '@/utils/index'
  export default{
    filters: {
      interviewTimeFilter (timestamp) {
        return parseTime(timestamp, '{y}-{m}-{d} {h}:{i}')
      }
    },
    data(){
      return{
      }
    },
    props:['detailItem','isVideo']
  }
</script>

<style lang="scss" scoped>
  .details{
  margin: 0 30px ;
  }
  .details li{
    // font-size: 12px;
    color: #333333;
    margin-bottom: 20px;
  }
  .details li span{
    color: #999999;
    font-size:15px;
  }
  strong{
    padding: 0 15px;
  }
  .details .telColor{
    color: #3388ff;
  }
</style>
