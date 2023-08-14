<template>
	<div class="boxNoResume" v-if="show && !LoginOrNot">
		<div class="closeIcon" @click="close()"><img src="../assets/images/close.png" alt="" /></div>
		<div class="bgBox">
			<img class="imgBg" v-if="origin == 'jobList'" src="../assets/images/noResume.png" alt="" />
			<img class="imgBg" v-if="origin == 'resumeList'" src="../assets/images/noCom.png" alt="" />
			<div class="content" v-if="origin == 'jobList'">
				<div class="content1">
					<div class="text1">您还没有创建简历，无法投递</div>
					<div class="text2">创建简历，为您推荐合适的岗位</div>
				</div>
        <!--
        【ID1000419】
        【优化】职位列表页未登录情况下，底部引导注册链接
        [旧]:
        @click="handlerJump('/member/regquick')
        [新]:
        @click="handlerJump('/member/reg/personal')
        -->
				<span class="contentBtn" @click="handlerJump('/member/reg/personal')">创建</span>
			</div>
			<div class="content" v-if="origin == 'resumeList'">
				<div class="content1">
					<div class="text1">您还没有创建企业，无法招人</div>
					<div class="text2">创建企业，为您推荐合适的人才</div>
				</div>
				<span class="contentBtn" @click="handlerJump('/member/reg/company')">创建</span>
			</div>
		</div>
	</div>
</template>

<script>
import { mapState } from 'vuex'
export default {
  name: 'NoResume',
  props: {
    show: {
      type: Boolean,
      default: false
    },
    origin: {
      type: String,
      default: ''
    }
  },
  computed: {
    ...mapState(['LoginOrNot'])
  },
  mounted () {
    let current = new Date()
    let origin = this.origin
    if (origin == 'resumeList') {
      let resumeList_expire_time = localStorage.getItem('show_warn_resumeList_expire_time')
      if (resumeList_expire_time && current.getTime() > new Date(resumeList_expire_time).getTime()) {
        this.$emit('showChange', true)
      } else if (resumeList_expire_time && current.getTime() < new Date(resumeList_expire_time).getTime()) {
        this.$emit('showChange', false)
      }
    } else if (origin == 'jobList') {
      let jobList_expire_time = localStorage.getItem('show_warn_jobList_expire_time')
      if (jobList_expire_time && current.getTime() > new Date(jobList_expire_time).getTime()) {
        this.$emit('showChange', true)
      } else if (jobList_expire_time && current.getTime() < new Date(jobList_expire_time).getTime()) {
        this.$emit('showChange', false)
      }
    }
  },
  methods: {
    handlerJump (path) {
      this.$router.push(path)
    },
    close () {
      this.$emit('showChange', false)
      let current = new Date()
      let origin = this.origin
      if (origin == 'resumeList') {
        let resumeList_expire_time = localStorage.getItem('show_warn_resumeList_expire_time')
        if (resumeList_expire_time === undefined || !resumeList_expire_time || current.getTime() > new Date(resumeList_expire_time).getTime()) {
          setTimeout(() => {
            resumeList_expire_time = new Date(new Date(new Date().toLocaleDateString()).getTime() + 24 * 60 * 60 * 1000 - 1)
            localStorage.setItem('show_warn_resumeList_expire_time', resumeList_expire_time)
          }, 500)
        }
      } else if (origin == 'jobList') {
        let jobList_expire_time = localStorage.getItem('show_warn_jobList_expire_time')
        if (jobList_expire_time === undefined || !jobList_expire_time || current.getTime() > new Date(jobList_expire_time).getTime()) {
          setTimeout(() => {
            jobList_expire_time = new Date(new Date(new Date().toLocaleDateString()).getTime() + 24 * 60 * 60 * 1000 - 1)
            localStorage.setItem('show_warn_jobList_expire_time', jobList_expire_time)
          }, 500)
        }
      }
    }
  }
}
</script>

<style lang="scss" scoped>
.boxNoResume {
	width: 95%;
	height: 80px;
	display: flex;
	align-items: flex-end;
	position: fixed;
	bottom: 85px;
	left: 2.5%;
	.closeIcon {
		float: right;
		width: 25px;
		height: 25px;
		position: fixed;
		bottom: 140px;
		right: 0;
		img {
			display: block;
			width: 15px;
			height: 15px;
		}
	}
	.bgBox {
		width: 100%;
		height: 60px;
		background: linear-gradient(90deg, #2f58f0, #5388ff);
		border-radius: 24px 24px 0px 0px;
		position: relative;

		.imgBg {
			width: 80px;
			height: 80px;
			position: absolute;
			bottom: 0;
			left: 0;
		}

		.content {
			float: right;
			display: flex;
			justify-content: space-between;
			align-items: center;
			width: 80%;
			box-sizing: border-box;
			padding: 10px 13px 0 10px;
			.content1 {
				.text1 {
					font-size: 14px;
					color: #fff;
					letter-spacing: 1px;
					margin-bottom: 3px;
				}
				.text2 {
					font-size: 12px;
					color: #eaf0ff;
					letter-spacing: 1px;
				}
			}
			.contentBtn {
				width: 56px;
				height: 30px;
				background: #ff6c00;
				border-radius: 6px;
				font-size: 0.373333rem;
				color: #fffffe;
				text-align: center;
				line-height: 30px;
			}
		}
	}
}
</style>
