<template>
  <div>
    <div class="b1">
      <div class="content">
        <div
          class="list"
          v-for="(item, index) in joblist"
          :key="index"
          @click="$router.push('/job/' + item.id)"
        >
          <div class="up">
            <img
              :src="item.company_logo"
              :alt="item.companyname"
              class="logo"
            />
            <div class="tx1">
              <div class="name">{{ item.jobname }}</div>
              <div class="worry_ico" v-if="item.emergency == 1">急</div>
              <div class="clear"></div>
              <div class="wage">{{ item.wage_text }}</div>
            </div>
            <div class="tx2">
              {{ item.education_text }} · {{ item.experience_text }} ·
              {{ item.district_text }}
              <div class="time">{{ item.refreshtime }}</div>
            </div>
            <div class="company">
              <div class="name">{{ item.companyname }}</div>
              <div class="auth_ico" v-if="item.company_audit == 1"></div>
              <div class="crw_ico" v-if="item.setmeal_icon != ''"><img :src="item.setmeal_icon" /></div>
              <div class="clear"></div>
            </div>
          </div>
          <div
            class="tag_wrapper"
            v-if="
              item.tag != undefined && item.tag.length > 0
            "
          >
            <div
              class="tag_item"
              v-for="(t, k) in item.tag"
              :key="k"
            >
              {{ t }}
            </div>
            <div class="clear"></div>
          </div>
        </div>
        <div class="form_split_10"></div>
        <div class="more" @click="$router.push('/joblist')">查看更多</div>
      </div>
    </div>
  </div>
</template>

<script>
import http from '@/utils/http'
import api from '@/api'
export default {
  name: 'JobFive',
  data () {
    return {
      joblist: []
    }
  },
  created () {
    this.fetchListData()
  },
  methods: {
    fetchListData () {
      http
        .get(api.joblist, { sort: 'rtime', page: 1, pagesize: 5 })
        .then(res => {
          this.joblist = [...res.data.items]
        })
        .catch(() => {})
    }
  }
}
</script>

<style lang="scss" scoped>
.b1 {
  .content {
    .more {
      padding: 15px 0;
      width: 100%;
      text-align: center;
      font-size: 14px;
      color: #999999;
      background-color: #ffffff;
    }
    .list {
      .up {
        .logo {
          position: absolute;
          width: 70px;
          height: 70px;
          border: 1px solid #e4e4e4;
          left: 0;
          top: 17px;
        }
        .company {
          .crw_ico {
            float: left;
            margin-left: 6px;
            width: 14px;
            height: 18px;
            position: relative;
            img {
              position: absolute;
              left: 0;
              top: 3px;
              max-width: 14px;
              max-height: 12px;
              border: 0;
            }
          }
          .auth_ico {
            float: left;
            margin-left: 6px;
            width: 15px;
            height: 18px;
            background: url("../../../assets/images/jobs_list_auth_ico.png") 0
              4px no-repeat;
            background-size: 15px 11px;
          }
          .name {
            float: left;
            max-width: 230px;
            font-size: 13px;
            color: #999999;
            overflow: hidden;
            white-space: nowrap;
            text-overflow: ellipsis;
          }
          width: 100%;
          padding-bottom: 12.5px;
        }
        .tx2 {
          .time {
            position: absolute;
            right: 0;
            top: 10px;
            font-size: 13px;
            color: #999999;
          }
          font-size: 14px;
          color: #666666;
          padding: 9px 60px 6px 0;
          overflow: hidden;
          white-space: nowrap;
          text-overflow: ellipsis;
          position: relative;
        }
        .tx1 {
          .wage {
            position: absolute;
            right: 0;
            top: 19px;
            font-size: 14px;
            color: #ff5d24;
            font-weight: bold;
          }
          .worry_ico {
            font-size: 10px;
            color: #ffffff;
            margin-top: 3px;
            padding: 1px 3px;
            border-radius: 3px;
            background-color: #ff8b82;
            float: left;
          }
          .name {
            font-size: 16px;
            color: #333333;
            font-weight: bold;
            max-width: 150px;
            overflow: hidden;
            white-space: nowrap;
            text-overflow: ellipsis;
            float: left;
            margin-right: 6px;
          }
          position: relative;
          padding-top: 15px;
        }
        position: relative;
        padding-left: 80px;
      }
      .tag_wrapper {
        .tag_item {
          float: left;
          font-size: 10px;
          background-color: #e9f8ff;
          color: #8096a3;
          padding: 3px 5px;
          border-radius: 3px;
          &:not(:last-child) {
            margin-right:5px;
          }
          margin-bottom:4px;
        }
        width: 100%;
        padding-bottom: 12px;
      }
      &:not(:first-child) {
        .up {
          border-top: 1px dotted #f3f3f3;
        }
      }
      position: relative;
      width: 100%;
      background-color: #ffffff;
      padding: 0 17px;
      overflow: hidden;
    }
    background-color: #f3f3f3;
  }
  width: 100%;
  background-color: #ffffff;
}
</style>
