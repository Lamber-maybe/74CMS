<template>
  <div>
      <ul class="dialog_box">
        <li><span>面试形式：</span>视频面试</li>
        <li><span>面试职位：</span>{{info.jobname}}</li>
        <li><span>公司名称：</span>{{info.companyname}}</li>
        <li><span>面试时间：</span>{{ info.interview_time | interviewTimeFilter }}</li>
        <li><span>联系电话：</span><span class="color_blue">{{ info.tel }}</span>({{ info.contact }})</li>
        <li><span>备注：</span>请在正式面试前30分钟做好面试准备和设备调试，准时参加视频面试</li>
      </ul>
  </div>
</template>

<script>
import { parseTime } from '@/utils/index'
export default{
  props:['info'],
  filters: {
    interviewTimeFilter (timestamp) {
    return parseTime(timestamp, '{y}-{m}-{d} {h}:{i}')
    }
  },
}
</script>

<style lang="scss" scoped>
  /* 面试邀请 */
    .dialog_box .color_blue{
      color: #3388ff;
      font-size: 14px;
    }
    .dialog_box{
      padding: 0 0 0  50px;
    }
    .dialog_box li{
      padding: 10px 0 0 0;
      font-size: 14px;
      color: #333333;
    }
    .dialog_box li span{
      color: #a1a1a1;
    }
</style>
