<template>
    <div class="im-item-box">
        <div class="im-time">
            {{itemSystem.datetime}}
        </div>
        <div class="system-box">
            <div class="system-inner">{{itemSystem.message}}</div>
        </div>
    </div>
</template>
<script>
import {parseTime, formatTime} from '@/utils/index.js'
export default {
  props: ['itemSystem'],
  filters: {
    timeFilter (timestamp) {
      return formatTime(timestamp, '{y}-{m}-{d}', true)
    },
    interviewTimeFilter (timestamp) {
      return parseTime(timestamp, '{y}-{m}-{d} {h}:{i}')
    }
  }
}
</script>
<style lang="scss" scoped>
.system-box{
    display: flex;
    justify-content: center;
    .system-inner{
        background:#CBCBCB ;
        font-size:13px ;
        color:#FFFFFF ;
        padding:8px 15px ;
        border-radius: 30px;
    }
}
</style>
