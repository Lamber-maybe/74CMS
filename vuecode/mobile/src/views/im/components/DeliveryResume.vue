<template>
    <div>
        <van-overlay :show="isDelivery" z-index="15">
            <div class="job-list-wrapper">
                <div class="job-list-title">
                    投递简历
                    <div class="icon" @click="handleCloseDelivery">
                        <van-icon  name="cross" />
                    </div>
                </div>
                <div class="job-list-con">
                    <div class="job-list-item">
                        <div class="item-t1">外贸业务员助理</div>
                        <div class="item-t2">14.5K~15K</div>
                        <div class="item-t3">大专 · 3-5年 · 北京-海淀区</div>
                        <van-button class="item-btn" size="small" type="info" round plain>投递</van-button>
                    </div>
                </div>
            </div>
        </van-overlay>
    </div>
</template>

<script>
// import http from '@/utils/http'
// import api from '@/api'
import {mapState} from 'vuex'
export default {
  name: 'DeliveryResume',
  data () {
    return {
      isDelivery: false
    }
  },
  computed: {
    ...mapState({

    })
  },
  methods: {
    /**
     * 打开投递面板
     */
    handleOpenDeliveryPopup () {
      this.isDelivery = true
      this.getjobList()
    },
    /**
     * 关闭面板
     */
    handleCloseDelivery () {
      this.isDelivery = false
    },
    /**
     * 获取职位列表
     */
    getjobList () {
    //   http.get(api.job_list).then(res => {
    //     console.log(res)
    //   })
    }
  }
}
</script>

<style lang="scss" scoped>
.van-overlay{
    display: flex;
    align-items: center;
    justify-content: center;
}
.job-list-wrapper{
    width: 328px;
    height: 375px;
    background: #fff;
    border-radius: 15px;
    .job-list-title{
        text-align: center;
        color: #666666;
        font-size: 17px;
        padding: 20px 0;
        position: relative;
        .icon{
            position: absolute;
            top: 0px;
            right: 0px;
            padding: 10px ;
        }
    }
    .job-list-con{
        height: 310px;
        overflow-y: auto;
        .job-list-item{
            height: 105px;
            position: relative;
            padding: 19px 17px;
            &::before{
                content: '';
                position: absolute;
                left: 50%;
                bottom: 0;
                transform: translateX(-50%);
                height: 1px;
                width: 92%;
                background: #eee;
            }
            .item-t1{
                color: #333333;
                font-size:15px ;
                font-weight: bold;
                line-height: 24px;
            }
            .item-t2{
                color:#FF5017 ;
                font-size: 13px;
                line-height: 24px;
            }
            .item-t3{
                color: #666666;
                font-size: 13px;
                line-height: 24px;
            }
            .item-btn{
                position: absolute;
                right: 17px;
                top: 50%;
                transform: translateY(-50%);
            }
        }
    }
}
</style>
