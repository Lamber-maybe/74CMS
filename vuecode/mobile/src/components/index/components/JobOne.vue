<template>
  <div>
    <div class="b1">
      <div class="title">
        <div
          class="item"
          :class="currentTab == 1 ? 'active' : ''"
          @click="changeTab(1)"
        >
          {{ firstTabText }}
        </div>
        <div
          class="item"
          :class="currentTab == 2 ? 'active' : ''"
          @click="changeTab(2)"
        >
          最新
        </div>
        <div class="clear"></div>
        <div
          class="intent"
          v-if="firstIntentionText != ''"
          @click="intentionSelShow = true"
        >
          {{ firstIntentionText }}
        </div>
      </div>
      <div class="content">
        <div
          class="list"
          v-for="(item, index) in joblist"
          :key="index"
          @click="$router.push('/job/' + item.id)"
        >
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
          <div
            class="tag_wrapper clearfix"
            v-if="item.tag != undefined && item.tag.length > 0"
          >
            <div class="tag_item" v-for="(t, k) in item.tag" :key="k">
              {{ t }}
            </div>
          </div>
          <div class="company">
            <div class="name">{{ item.companyname }}</div>
            <div
              class="auth_ico auth_ico1"
              v-if="
                item.company_audit == 1 && mobile_company_show_tpl === 'def'
              "
            ></div>
            <div
              class="auth_ico"
              v-if="
                item.company_audit == 1 && mobile_company_show_tpl === 'tpl2'
              "
            ></div>
            <div class="crw_ico" v-if="item.setmeal_icon != ''">
              <img :src="item.setmeal_icon" />
            </div>
            <div class="clear"></div>
          </div>
        </div>
        <div class="form_split_10"></div>
        <div class="more" @click="$router.push('/joblist')">查看更多</div>
      </div>
    </div>
    <van-action-sheet
      v-model="intentionSelShow"
      :actions="intentionList"
      cancel-text="取消"
      close-on-click-action
      @select="onIntentionSelected"
    />
  </div>
</template>

<script>
import http from "@/utils/http";
import api from "@/api";
export default {
  name: "JobOne",
  data() {
    return {
      currentTab: 1,
      firstIntentionText: "",
      intentionSelShow: false,
      firstTabText: "推荐",
      intentionList: [],
      joblist: [],
      currentIntentionItem: {},
      mobile_company_show_tpl: "def",
    };
  },
  created() {
    this.mobile_company_show_tpl =
      this.$store.state.config.mobile_company_show_tpl;
    this.fetchListData(true);
  },
  methods: {
    fetchListData(first_request) {
      if (
        this.$store.state.LoginOrNot === true &&
        this.$store.state.LoginType == 2
      ) {
        this.firstTabText = "推荐";
        if (first_request === true) {
          this.fetchDataIntention();
        } else {
          this.fetchDataRecommend();
        }
      } else {
        this.firstTabText = "紧急";
        this.fetchDataEmergency();
      }
    },
    fetchDataIntention() {
      http
        .get(api.get_intentions, {})
        .then((res) => {
          this.intentionList = [...res.data.items];
          if (this.intentionList.length > 0) {
            for (const key in this.intentionList) {
              this.intentionList[key].name =
                this.intentionList[key].category_text;
            }
            this.currentIntentionItem = { ...this.intentionList[0] };
            this.fetchDataRecommend();
          }
        })
        .catch(() => {});
    },
    fetchDataRecommend() {
      const { category_text, id } = this.currentIntentionItem;
      this.firstIntentionText = category_text;
      http
        .get(api.recommend_joblist, { id, page: 1, pagesize: 5 })
        .then((res) => {
          this.joblist = [...res.data.items];
        })
        .catch(() => {});
    },
    fetchDataNew() {
      http
        .get(api.joblist, { sort: "rtime", page: 1, pagesize: 5 })
        .then((res) => {
          this.joblist = [...res.data.items];
        })
        .catch(() => {});
    },
    fetchDataEmergency() {
      http
        .get(api.joblist, { emergency: 1, page: 1, pagesize: 5 })
        .then((res) => {
          this.joblist = [...res.data.items];
        })
        .catch(() => {});
    },
    onIntentionSelected(item) {
      this.intentionSelShow = false;
      item.name = item.category_text;
      this.currentIntentionItem = item;
      this.fetchDataRecommend();
    },
    changeTab(tabId) {
      this.currentTab = tabId;
      if (tabId == 1) {
        this.fetchListData(false);
      } else {
        this.firstIntentionText = "";
        this.fetchDataNew();
      }
    },
  },
};
</script>

<style lang="scss" scoped>
.b1 {
  .content {
    .more {
      padding: 12.5px 0;
      width: 100%;
      text-align: center;
      font-size: 14px;
      color: #999999;
      background-color: #ffffff;
    }
    .list {
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
            top: 50%;
            transform: translate(0, -50%);
            max-width: 14px;
            max-height: 12px;
            border: 0;
          }
        }
        .auth_ico {
          float: left;
          margin-left: 6px;
          width: 35px;
          height: 18px;
          background: url("../../../assets/images/jobs_list_auth_ico.png") 0
            center no-repeat;
          background-size: 100% 12px;
        }

        .auth_ico1 {
          float: left;
          margin-left: 6px;
          width: 15px;
          height: 18px;
          background: url("../../../assets/images/jobs_list_auth_ico_1.png") 0
            4px no-repeat;
          background-size: 15px 11px;
        }
        .name {
          float: left;
          max-width: 300px;
          font-size: 13px;
          color: #999999;
          overflow: hidden;
          white-space: nowrap;
          text-overflow: ellipsis;
        }
        width: 100%;
        border-top: 1px solid #f3f3f3;
        padding: 12.5px 0;
      }
      .tag_wrapper {
        .tag_item {
          float: left;
          font-size: 10px;
          background-color: #e9f8ff;
          color: #8096a3;
          padding: 3px 5px;
          border-radius: 3px;
          margin-bottom: 5px;
          &:not(:last-child) {
            margin-right: 5px;
          }
          margin-bottom: 4px;
        }
        width: 100%;
        padding-bottom: 7px;
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
        padding: 9px 60px 8.5px 0;
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
          padding: 1px 3px;
          border-radius: 3px;
          background-color: #ff8b82;
          margin-top: 3px;
          float: left;
        }
        .name {
          font-size: 16px;
          color: #333333;
          font-weight: bold;
          max-width: 240px;
          overflow: hidden;
          white-space: nowrap;
          text-overflow: ellipsis;
          float: left;
          margin-right: 6px;
        }
        position: relative;
        padding-top: 17.5px;
      }
      position: relative;
      width: 100%;
      background-color: #ffffff;
      padding: 0 17px;
      overflow: hidden;
      &:not(:first-child) {
        margin-top: 5px;
      }
    }
    background-color: #f3f3f3;
  }
  .title {
    .intent {
      &::after {
        content: " ";
        display: block;
        position: absolute;
        width: 0;
        height: 0;
        transform: rotate(45deg);
        border: 5px solid transparent;
        border-left-color: #999999;
        right: 3px;
        bottom: 2px;
      }
      position: absolute;
      right: 18px;
      top: 50%;
      transform: translate(0, -50%);
      background-color: #f3f3f3;
      border-radius: 4px;
      padding: 5.5px 20px 5.5px 10px;
      font-size: 14px;
      color: #666666;
    }
    .item {
      &.active {
        &::after {
          position: absolute;
          left: 50%;
          bottom: 3px;
          transform: translate(-50%, 0);
          width: 13px;
          height: 4px;
          border-radius: 4px;
          background-color: #1787fb;
          content: " ";
        }
        color: #333333;
      }
      position: relative;
      padding: 13.5px 20px;
      font-size: 17px;
      color: #999999;
      float: left;
    }
    width: 100%;
    border-bottom: 1px solid #f3f3f3;
    position: relative;
  }
  width: 100%;
  background-color: #ffffff;
}
</style>
