<template>
    <div class="appbox">
      <div class="box1">
          <div class="volume"></div>
      </div>
      <div class="box2">
        <div class="box_title">
          <div class="head_back">
            <i class="el-icon-arrow-left"></i>
          </div>
          <div class="boxs2">职位详情</div>
          <div class="boxs3">
            <div class="head_more"></div>
            <div class="head_user">
              <div class="sub_block">
                <div class="block_con"></div>
              </div>
            </div>
          </div>
        </div>
        <div class="box_1">
          <div class="collect">
            <div class="ico"></div>
            <div class="text">收藏</div>
          </div>
          <div class="share">
            <div class="ico"></div>
            <div class="text">分享</div>
          </div>
          <div class="tx1">
            {{row.jobname}}
          </div>
          <div class="tx2">{{row.wage_text}}</div>
          <div class="tx3">
            {{row.district_name}} · {{row.education_text}} · {{row.experience_text}}
            <div class="time">更新：{{row.updatetime | timeFilter }}</div>
          </div>
          <div class="chat_bar">
            {{row.click}}人查看
            <div class="right">在线直聊</div>
          </div>
        </div>
        <div class="box_2">
          <div class="cell">企业认证</div>
          <div class="cell">多重审核</div>
          <div class="cell">信誉担保</div>
          <div class="cell">违规严惩</div>
          <div class="auth_ico">认证</div>
          <div class="bg_box"></div>
          <div class="clear"></div>
        </div>
        <div class="box_3">
          <div class="put">
            工作地址
          </div>
          <div class="address">
            {{row.district_name1}}{{row.district_name2}}{{row.district_name3}}{{row.address}}
          </div>
          <div class="bg">
            <div class="box">
              <div class="tx1">{{row.company_name}}</div>
              <div class="tx2">{{row.district_name1}}{{row.district_name2}}{{row.district_name3}}{{row.address}}</div>
            </div>
          </div>
        </div>
        <div class="box_4">
          <div class="put">职位福利</div>
          <div class="content">
            <div class="item" v-for="(item) in row.tag_name">{{item}}</div>
            <div class="clear"></div>
          </div>
        </div>
        <div class="box_6">
          <div class="put">职位描述</div>
          <div class="content">
            <div class="b_item">
              {{row.content}}
            </div>
            <div class="clear"></div>
          </div>
        </div>
        <div class="box_12">
          <div class="bottom_bar">
            <div class="item_call">电话</div>
            <div class="item_collect item_collect_ac">聊天</div>
            <div class="item_apply">投递简历</div>
            <div class="clear"></div>
          </div>
        </div>
        <div class="click_copy">一键<br/>复制</div>
        <div class="generate_posters">生成<br/>海报</div>
      </div>
    </div>
</template>
<script>

import { jobEdit } from "@/api/job"
import { parseTime } from "@/utils"

export default {
  components: {
  },
  filters: {
    timeFilter(timestamp) {
      return parseTime(timestamp, '{y}-{m}-{d}')
    }
  },
  data() {
    return {
      row: []
    }
  },
  created() {

  },
  methods: {
    getJobLink(row) {
      this.row = row
    }
  }
}
</script>

<style lang="scss" scoped>
  .appbox{
      width: 364px;
      height: 700px;
      border-radius: 30px;
    background: rgb(228 228 228 / 24%);
      margin-top: 180px;
      .box1{
        height: 50px;
        padding-top: 20px;
        .volume{
          width: 71px;
          height: 11px;
          margin: 0px auto;
          border-radius: 20px;
          background: #b9bdc296;
        }
      }
    .box2{
      width: 329px;
      height: 620px;
      margin: 0 auto;
      background: #FFFFFF;
      border-radius: 10px;
      .click_copy {
        position: relative;
        z-index: 1;
        width: 41px;
        height: 41px;
        border-radius: 999px;
        background-color: rgba(0, 0, 0, 0.7);
        left: 278px;
        bottom: 210px;
        font-size: 12px;
        color: #ffffff;
        line-height: 14px;
        text-align: center;
        padding-top: 7px;
      }
      .generate_posters {
        position: relative;
        z-index: 1;
        width: 41px;
        height: 41px;
        border-radius: 999px;
        background-color: rgba(0, 0, 0, 0.7);
        bottom: 200px;
        left: 278px;
        font-size: 12px;
        color: #ffffff;
        line-height: 14px;
        text-align: center;
        padding-top: 7px;
      }
      .box_title{
        width: 100%;
        height: 45px;
        border-bottom: 2px solid #beb6b654;
        display: flex;
        .head_back {
          .el-icon-arrow-left:before{
            position: absolute;
            left: 157px;
          }
        }
        .boxs2{
          width: 110px;
          height: 100%;
          text-align: center;
          line-height: 45px;
          margin-left: 110px;
          font-weight: bold;
        }
        .boxs3{
          width: 110px;
          height: 100%;
          .head_more {
            position: relative;
            left: 65px;
            width: 40px;
            height: 100%;
            background: url("../../../../assets/images/jobshow/head_more_ico.png") center no-repeat;
            background-size: 15px 14.5px;
          }
          .head_user {
            position: relative;
            top: -42px;
            left: 32px;
            width: 40px;
            height: 100%;
            display: block;
            background: url("../../../../assets/images/jobshow/head_user_ico.png") center no-repeat;
            background-size: 16px 17px;
            .sub_block {
              height: 10px;
              width: 10px;
              position: absolute;
              border-radius: 100%;
              background: #1787fb;
              top: 22px;
              left: 24px;
              z-index: 1;
              display: block;
              .block_con {
                position: relative;
                height: 10px;
                width: 10px;
                border-radius: 100%;
                display: block;
                &::after,
                &::before {
                  content: "";
                  height: 5px;
                  width: 1px;
                  display: block;
                  background: #fff;
                  position: absolute;
                  top: 3px;
                  left: 5px;
                  border-radius: 8px;
                  transform: rotate(45deg);
                }
                &::before {
                  height: 3px;
                  transform: rotate(-45deg);
                  top: 5px;
                  left: 3px;
                }
              }
            }
          }
        }
      }
      .box_1 {
        .chat_bar {
          .right {
            &::after {
              position: absolute;
              right: 0;
              top: 5px;
              width: 6px;
              height: 6px;
              border-top: 1px solid #1787fb;
              border-right: 1px solid #1787fb;
              transform: rotate(45deg);
              content: " ";
            }

            position: absolute;
            right: 2px;
            top: 50%;
            transform: translate(0, -50%);
            font-size: 12px;
            color: #1787fb;
            padding-right: 11px;
          }

          position: relative;
          font-size: 12px;
          color: #999999;
          padding: 12px 0 12px 17px;
          border-top: 1px solid #f5f5f5;
          background: url("../../../../assets/images/jobshow/hot_point_ico.svg") 0 center no-repeat;
          background-size: 15px;
        }

        .tx3 {
          .time {
            position: absolute;
            right: 0;
            top: 50%;
            transform: translate(0, -50%);
            font-size: 12px;
            color: #999999;
          }

          font-size: 12px;
          color: #666666;
          padding: 0 80px 0 0;
          position: relative;
          overflow: hidden;
          white-space: nowrap;
          text-overflow: ellipsis;
          margin-bottom: 17px;
        }

        .tx2 {
          font-size: 16px;
          font-weight: bold;
          color: #ff5d24;
          margin-bottom: 10.5px;
        }

        .tx1 {
          margin-bottom: 7.5px;
          position: relative;
          font-size: 16px;
          font-weight: bold;
          color: #333333;
          overflow: hidden;
          white-space: nowrap;
          text-overflow: ellipsis;
        }

        .share {
          .text {
            font-size: 12px;
            color: #999999;
          }

          .ico {
            width: 16px;
            height: 16px;
            margin: 0 auto 2.5px;
            background: url("../../../../assets/images/jobshow/share_ico.png") 0 no-repeat;
            background-size: 16px;
          }

          position: absolute;
          top: 25px;
          right: 17px;
          z-index: 1;
        }

        .collect {
          .text {
            font-size: 12px;
            color: #999999;
          }

          .ico {
            width: 16px;
            height: 16px;
            margin: 0 auto 2.5px;
            position: relative;
            border-radius: 100%;
            background: url("../../../../assets/images/jobshow/collect_ico.png") 0 no-repeat;
            background-size: 16px;
          }

          position: absolute;
          top: 25px;
          right: 55px;
          z-index: 1;
        }

        width: 100%;
        background-color: #ffffff;
        position: relative;
        padding: 21px 16px 0;
        border-top: 1px solid #f3f3f3;
      }
      .box_2{
        .bg_box {
          position: absolute;
          left: -20px;
          top: 2px;
          width: 50px;
          height: 100%;
          background: linear-gradient(to right, #3b9dfe, #6fc9ff);
          z-index: 1;
          transform: rotate(35deg);
        }

        .auth_ico {
          position: absolute;
          left: 10px;
          top: 50%;
          transform: translate(0, -50%);
          padding-top: 13px;
          font-size: 10px;
          color: #ffffff;
          z-index: 2;
          background: url("../../../../assets/images/jobshow/job_show_auth_ico.png") center 0 no-repeat;
          background-size: 13px;
        }

        .cell {
          float: left;
          font-size: 12px;
          color: #5f82a7;
          padding: 10px 3px 7px 18px;
          background: url("../../../../assets/images/jobshow/done_ico_blue.svg") 0 center no-repeat;
          background-size: 12px;

          &:last-child {
            padding-right: 0;
          }
        }

        position: relative;
        width: 100%;
        background-color: #eef9ff;
        overflow: hidden;
        padding-left: 43px;
        height: 34px;
      }
      .box_3 {
        .bg {
          .box {
            &::after {
              position: absolute;
              right: 17px;
              top: 21px;
              width: 8px;
              height: 8px;
              border-top: 1px solid #666666;
              border-right: 1px solid #666666;
              transform: rotate(45deg);
              content: " ";
            }

            .tx2 {
              font-size: 12px;
              color: #767676;
              overflow: hidden;
              white-space: nowrap;
              text-overflow: ellipsis;
            }

            .tx1 {
              font-size: 14px;
              font-weight: bold;
              color: #333333;
              overflow: hidden;
              white-space: nowrap;
              text-overflow: ellipsis;
              margin-bottom: 6.5px;
            }

            width: 253px;
            margin: 0 auto;
            padding: 6.5px 40px 7px 50px;
            position: relative;
            border-radius: 53px;
            background: #ffffff url("../../../../assets/images/jobshow/job_show_add_ico.png") 5px center no-repeat;
            background-size: 40.5px;
          }

          width: 100%;
          height: 81px;
          background: url("../../../../assets/images/jobshow/job_show_address_bg.png") 0 no-repeat;
          background-size: 100% 81px;
          padding-top: 14px;
        }

        .address {
          padding-left: 20px;
          line-height: 1.8;
          font-size: 14px;
          color: #767676;
          word-break: break-all;
          margin-bottom: 8px;
          background: url("../../../../assets/images/jobshow/address_ico_blue.svg") 0 5px no-repeat;
          background-size: 13px;
        }

        width: 100%;
        background-color: #ffffff;
        padding: 14px 16px;
      }
      .box_4 {
        .content {
          .item {
            float: left;
            font-size: 13px;
            color: #8096a3;
            padding: 8px 7px;
            background-color: #e9f8ff;
            border-radius: 33px;
            margin: 0 9px 9px 0;
          }

          padding-bottom: 16px;
        }

        width: 325px;
        background-color: #ffffff;
        padding: 0 0 0 16px;
      }
      .box_6 {
        .content {
          .tx1 {
            font-size: 14px;
            color: #666666;
            margin-bottom: 2px;
          }

          .b_item {
            float: left;
            width: 50%;
            vertical-align: top;
            font-size: 14px;
            color: #999999;
            margin-bottom: 9px;

            span {
              color: #666666;
            }
          }

          padding-bottom: 10px;
        }
        background-color: #ffffff;
        margin-top: 85px;
        margin-left: 19px;
      }
      .box_12 {
        .bottom_bar {
          .item_apply {
            height: 43px;
            line-height: 43px;
            text-align: center;
            background-color: #1787fb;
            color: #ffffff;
            font-size: 15px;
            width: 224px;
            margin-right: 15px;
            border-radius: 0 0 10px;
            flex-shrink: 0;
          }
          .item_call {
            flex-shrink: 0;
            width: 60px;
            height: 46px;
            padding: 30px 0 4px 7px;
            text-align: center;
            font-size: 10px;
            color: #333333;
            background: url("../../../../assets/images/jobshow/calling_ico.svg") center 9px no-repeat;
            background-size: 17px;
            position: relative;
            top: -4px;
            left: 7px;
          }
          .item_collect{
            flex-shrink: 0;
            width: 60px;
            height: 46px;
            padding: 30px 0 4px 2px;
            top: -5px;
            left: -2px;
            text-align: center;
            font-size: 10px;
            color: #333333;
            position: relative;
            background: url("../../../../assets/images/jobshow/com_show_col_ico_2.png") center 9px no-repeat;
            background-size: 17px;
          }
          background-color: #ffffff;
          z-index: 3;
          height: 43px;
          width: 100%;
          display: flex;
          justify-content: center;
          position: relative;
          position: absolute;
          top: -11px;
          border-radius: 0 0 10px 10px;
        }
        height: 63px;
        width: 100%;
        position: relative;
        top: 25px;
      }
      .put{
        padding-bottom: 10px;
      }
    }
  }
</style>
