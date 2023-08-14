<template>
	<div id="app">
		<Meta pagealias="resumelist" :query_data="$route.query" />
		<Head>简历列表</Head>
		<div class="box_1">
			<div class="content" @click="toggleSearch">
				<div :class="params.keyword === '' ? 'search_ico' : 'search_ico has'">
					{{ params.keyword === '' ? '请输入关键字' : params.keyword }}
				</div>
			</div>
		</div>
		<van-popup v-model="show" position="top" :overlay="true" :style="{ height: '90%', width: '100%' }">
			<TopSearch type="resume" @hideSearch="toggleSearch" @doSearch="doSearchByKeyword"></TopSearch>
		</van-popup>
		<div class="box_2">
			<van-dropdown-menu class="filter_menu">
				<van-dropdown-item :title="districtTitle" :lock-scroll="false" ref="dropDistrict"
					@opened="openedDistrict" @closed="closedDistrict">
					<DistrictFilter :districts="[params.district1, params.district2, params.district3]" :type="true"
						@doSearch="doSearchByDistrict"></DistrictFilter>
				</van-dropdown-item>
				<van-dropdown-item :title="experienceTitle" v-model="params.experience" :options="optionExperience"
					@change="handleExperience" @opened="openedExperience" />
				<van-dropdown-item :title="educationTitle" v-model="params.education" :options="optionEducation"
					@change="handleEducation" @opened="openedEducation" />
				<van-dropdown-item :title="otherTitle" ref="dropMore">
					<div class="more_box">
						<div class="item_wrapper">
							<div class="item_title">性别</div>
							<div class="item_group">
								<div :class="item.select ? 'item select' : 'item'" v-for="(item, index) in optionGender"
									:key="index"
									@click="optionGender = restructureData(optionGender, item.id, 'gender')">
									{{ item.text }}
								</div>
								<div class="clear"></div>
							</div>
							<div class="item_title">年龄</div>
							<div class="item_group">
								<div :class="item.select ? 'item select' : 'item'" v-for="(item, index) in optionAge"
									:key="index" @click="handleAge(item)">{{ item.text }}</div>
								<div class="clear"></div>
							</div>
							<div class="item_title">期望薪资</div>
							<div class="item_group">
								<div :class="item.select ? 'item select' : 'item'" v-for="(item, index) in optionWage"
									:key="index" @click="handleWage(item)">{{ item.text }}</div>
								<div class="clear"></div>
							</div>
							<div class="item_title">简历标签</div>
							<div class="item_group">
								<div :class="item.select ? 'item select' : 'item'"
									v-for="(item, index) in optionResumeTag" :key="index"
									@click="handleCheckResumeTag(item)">
									{{ item.text }}
								</div>
								<div class="clear"></div>
							</div>
							<div class="item_title">更新时间</div>
							<div class="item_group">
								<div :class="item.select ? 'item select' : 'item'" v-for="(item, index) in optionSettr"
									:key="index" @click="optionSettr = restructureData(optionSettr, item.id, 'settr')">
									{{ item.text }}
								</div>
								<div class="clear"></div>
							</div>
							<div class="wrapper_bottom">
								<div class="btn_c" @click="handleClearMore">清空</div>
								<div class="btn_c blue" @click="handleSelectMore">确定</div>
							</div>
						</div>
					</div>
				</van-dropdown-item>
			</van-dropdown-menu>
		</div>
		<div class="form_split_10"></div>
		<van-empty image="search" description="没有找到对应的数据" style="background-color:#fff"
			v-if="show_empty === true && showLayer === false" />
		<van-list v-if="dataset.length > 0" v-model="loading" :finished="finished" :finished-text="finished_text"
			@load="onLoad" :immediate-check="true">
			<div class="box_3">
				<div v-for="(item, index) in dataset" :key="index" @click="toDetail(item.id)">
					<div class="list">
						<div class="top" v-if="item.stick == 1">置顶</div>
						<div class="up">
							<div class="avatar_box">
								<img :src="item.photo_img_src" :alt="item.fullname" />
								<div class="gender " :class="item.sex == 1 ? 'male' : 'female'"></div>
							</div>
							<div class="tx1">
								<div :class="item.clicked?'name clickedColor':'name'">{{ item.fullname }}</div>
								<div class="level_ico" v-if="item.high_quality == 1"></div>
								<div class="clear"></div>
								<div class="wage">{{ item.intention_wage }}</div>
							</div>
							<div class="tx2">
								{{ item.age_text }}岁 · {{ item.experience_text }} · {{ item.education_text }}
								<div class="time">{{ item.refreshtime }}</div>
							</div>
						</div>
						<div class="tx3">
							想找
							<span>{{ item.intention_jobs }}</span>
							工作
						</div>
						<div class="tx3">
							想在
							<span>{{ item.intention_district }}</span>
							工作
						</div>
						<div class="tx4">{{ item.current_text }}</div>
						<div class="tag" v-if="item.service_tag != ''">{{ item.service_tag }}</div>
					</div>
					<div class="form_split_10"></div>
				</div>
			</div>
		</van-list>
		<div class="login_layer" v-if="showLayer">
			<div class="ll_tip">注册企业会员，海量简历任你选</div>
			<div class="ll_tip_more">登录后可查看更多简历</div>
			<div class="ll_qr_box"><img :src="$store.state.config.wechat_qrcode" alt="" class="ll_qr" /></div>
			<div class="ll_tip_more">微信扫一扫，招聘更轻松</div>
			<div class="ll_tip_bth">
				<router-link to="/member/login" class="a_btn">登录</router-link>
				<router-link to="/member/reg/company" class="a_btn blue">注册</router-link>
			</div>
			<div class="ll_tip_tel" v-if="$store.state.config.contact_tel">联系客服：{{ $store.state.config.contact_tel }}
			</div>
		</div>
		<BottomNav></BottomNav>
		<!-- 企业未创建提示 -->
		<NoResume :show="showWarn" origin="resumeList" @showChange="showChange"></NoResume>
	</div>
</template>

<script>
	import wxshare from '@/assets/js/share.js'
	import {
		obj2Param
	} from '@/utils/index'
	import http from '@/utils/http'
	import api from '@/api'
	import DistrictFilter from '@/components/DistrictFilter'
	import NoResume from '@/components/NoResume'
	export default {
		name: 'ResumeList',
		components: {
			DistrictFilter,
			NoResume
		},
		data() {
			return {
				dataset: [],
				selectResumeArrOnload: [],
				loading: false,
				finished: false,
				finished_text: '',
				show_empty: false,
				params: {
					keyword: '',
					district1: '',
					district2: '',
					district3: '',
					experience: '',
					education: '',
					sex: '',
					minage: '',
					maxage: '',
					major: '',
					minwage: '',
					maxwage: '',
					tag: '',
					settr: ''
				},
				page: 1,
				pagesize: 10,
				show: false,
				districtTitle: '地区',
				experienceTitle: '经验',
				educationTitle: '学历',
				otherTitle: '其他',
				optionWage: [{
						text: '全部',
						id: 0,
						min: '',
						max: '',
						select: true
					},
					{
						text: '1千以下',
						id: 1,
						min: 0,
						max: 1000,
						select: false
					},
					{
						text: '1千-2千',
						id: 2,
						min: 1000,
						max: 2000,
						select: false
					},
					{
						text: '2千-3千',
						id: 3,
						min: 2000,
						max: 3000,
						select: false
					},
					{
						text: '3千-5千',
						id: 4,
						min: 3000,
						max: 5000,
						select: false
					},
					{
						text: '5千-8千',
						id: 5,
						min: 5000,
						max: 8000,
						select: false
					},
					{
						text: '8千-1万2',
						id: 6,
						min: 8000,
						max: 12000,
						select: false
					},
					{
						text: '1万2-1万5',
						id: 7,
						min: 12000,
						max: 15000,
						select: false
					},
					{
						text: '1万5以上',
						id: 8,
						min: 15000,
						max: '',
						select: false
					}
				],
				optionAge: [{
						text: '全部',
						id: 0,
						min: '',
						max: '',
						select: true
					},
					{
						text: '16-20岁',
						id: 1,
						min: 16,
						max: 20,
						select: false
					},
					{
						text: '20-30岁',
						id: 2,
						min: 20,
						max: 30,
						select: false
					},
					{
						text: '30-40岁',
						id: 3,
						min: 30,
						max: 40,
						select: false
					},
					{
						text: '40-50岁',
						id: 4,
						min: 40,
						max: 50,
						select: false
					},
					{
						text: '50岁以上',
						id: 5,
						min: 50,
						max: '',
						select: false
					}
				],
				optionGender: [{
					text: '男',
					id: 1
				}, {
					text: '女',
					id: 2
				}],
				optionSettr: [{
					text: '3天内',
					id: 3
				}, {
					text: '7天内',
					id: 7
				}, {
					text: '15天内',
					id: 15
				}, {
					text: '30天内',
					id: 30
				}],
				optionEducation: [],
				optionExperience: [],
				optionResumeTag: [],
				selectResumeTag: [],
				// 未登录引导
				showLayer: false,
				showWarn: true,
				totalPage: 0
			}
		},
		watch: {
			$route(to, from) {
				// 对路由变化作出响应...
				if (from.name !== 'resumeShow' && to.name === 'resumeList') {
					// 将地址栏中的url参数初始化到参数对象中
					this.initQuery(to.query)
					this.fetchData(true)
					this.restoreFilter()
				}
			}
		},
		beforeRouteLeave(to, from, next) {
			if (to.name === 'resumeShow') {
				if (!from.meta.keepAlive) {
					from.meta.keepAlive = true
				}
				next()
			} else {
				from.meta.keepAlive = false
				next()
			}
		},
		activated() {
			// this.initdata()
		},
		created() {
			this.initdata()
		},
		mounted() {
			// 重构筛选项数据格式
			// 性别
			this.optionGender = this.restructureData(this.optionGender, '', 'gender')
			// 更新时间
			this.optionSettr = this.restructureData(this.optionSettr, '', 'settr')
			this.restoreFilter()
		},
		methods: {
			initdata() {
				// 请求列表数据
				this.initQuery(this.$route.query)
				this.fetchData(true)
				this.$store.dispatch('getClassify', 'citycategory')
				this.$store.dispatch('getClassify', 'experience').then(() => {
					// 经验
					let storeExperience = this.$store.state.classifyExperience
					storeExperience.unshift({
						id: '',
						text: '不限'
					})
					this.optionExperience = storeExperience.map(function(item) {
						return {
							text: item.text,
							value: item.id
						}
					})
					// 得到缓存分类之后，再次尝试恢复选中项
					this.restoreFilter()
				})
				this.$store.dispatch('getClassify', 'education').then(() => {
					// 学历
					let storeEducation = this.$store.state.classifyEdu
					storeEducation.unshift({
						id: '',
						text: '不限'
					})
					this.optionEducation = storeEducation.map(function(item) {
						return {
							text: item.text,
							value: item.id
						}
					})
					this.restoreFilter()
				})
				this.$store.dispatch('getClassify', 'resumeTag').then(() => {
					// 重构简历标签
					/*
+      * 【bug】触屏简历列表页更多筛选项标签调用错误
+      * zch 2022.10.19
+      * 【旧】
+      * classifyJobTag
+      * 【新】
+      * classifyResumeTag
+      * */
					let storeResumeTag = (JSON.parse(JSON.stringify(this.$store.state.classifyResumeTag)))
					storeResumeTag = storeResumeTag.map(function(item) {
						return {
							id: item.id,
							text: item.text,
							select: false
						}
					})
					storeResumeTag.unshift({
						id: '',
						text: '全部',
						select: true
					})
					this.optionResumeTag = storeResumeTag
					this.restoreFilter()
				})
				wxshare({}, 'resumelist', location.href)
			},
			// 清空
			handleClearMore() {
				this.params.sex = ''
				this.params.minage = ''
				this.params.maxage = ''
				this.params.minwage = ''
				this.params.maxwage = ''
				this.params.tag = ''
				this.params.settr = ''
				this.handleSelectMore()
				this.optionGender = this.restructureData(this.optionGender, '', 'gender')
				this.optionSettr = this.restructureData(this.optionSettr, '', 'settr')
				this.optionAge = this.optionAge.map(function(item, index) {
					return {
						text: item.text,
						id: item.id,
						min: item.min,
						max: item.max,
						select: index === 0
					}
				})
				this.optionWage = this.optionWage.map(function(item, index) {
					return {
						text: item.text,
						id: item.id,
						min: item.min,
						max: item.max,
						select: index === 0
					}
				})
				this.optionResumeTag = this.optionResumeTag.map(function(item, index) {
					return {
						id: item.id,
						text: item.text,
						select: index === 0
					}
				})
			},
			// 恢复福利待遇
			restoreResumeTag() {
				let queryData = this.$route.query
				if (queryData['tag'] && this.optionResumeTag) {
					this.selectResumeTag = queryData['tag'].split(',')
					this.selectResumeTag = this.selectResumeTag.map(function(item) {
						return parseInt(item)
					})
					let _this = this
					this.optionResumeTag = this.optionResumeTag.map(function(item) {
						return {
							id: item.id,
							text: item.text,
							select: _this.selectResumeTag.includes(item.id)
						}
					})
				} else {
					this.optionResumeTag = this.optionResumeTag.map(function(item, index) {
						return {
							id: item.id,
							text: item.text,
							select: index === 0
						}
					})
				}
			},
			// 简历标签多选
			handleCheckResumeTag(item) {
				if (item.id) {
					if (this.selectResumeTag.includes(item.id)) {
						this.selectResumeTag.splice(this.selectResumeTag.findIndex(v => parseInt(v.id) === parseInt(item
							.id)), 1)
					} else {
						if (this.selectResumeTag.length >= 5) {
							this.$toast('简历标签最多可选5个')
						} else {
							this.selectResumeTag.push(item.id)
						}
					}
					let _this = this
					this.optionResumeTag = this.optionResumeTag.map(function(item) {
						return {
							id: item.id,
							text: item.text,
							select: _this.selectResumeTag.includes(item.id)
						}
					})
				} else {
					// 全部
					this.selectResumeTag = []
					this.optionResumeTag = this.optionResumeTag.map(function(item, index) {
						return {
							id: item.id,
							text: item.text,
							select: index === 0
						}
					})
				}
				this.params.tag = this.selectResumeTag.join(',')
			},
			initQuery(query) {
				for (const key in this.params) {
					if (query.hasOwnProperty(key)) {
						this.params[key] = query[key]
					} else {
						this.params[key] = ''
					}
				}
			},
			openedEducation() {
				this.params.education = parseInt(this.params.education)
			},
			openedExperience() {
				this.params.experience = parseInt(this.params.experience)
			},
			// 筛选年龄
			handleAge(option) {
				this.params.minage = option.min
				this.params.maxage = option.max
				this.optionAge = this.optionAge.map(function(item) {
					return {
						text: item.text,
						id: item.id,
						min: item.min,
						max: item.max,
						select: parseInt(item.id) === parseInt(option.id)
					}
				})
			},
			// 筛选薪资
			handleWage(option) {
				this.params.minwage = option.min
				this.params.maxwage = option.max
				this.optionWage = this.optionWage.map(function(item) {
					return {
						text: item.text,
						id: item.id,
						min: item.min,
						max: item.max,
						select: parseInt(item.id) === parseInt(option.id)
					}
				})
			},
			// 筛选学历
			handleEducation(value) {
				if (value) {
					let thisEducation = this.optionEducation.filter(item => parseInt(item.value) === value)
					this.educationTitle = thisEducation[0].text
					this.params.education = value
				} else {
					this.educationTitle = '学历'
					this.params.education = ''
				}
				this.doSearch({
					education: value
				})
			},
			// 筛选经验
			handleExperience(value) {
				if (value) {
					let thisExperience = this.optionExperience.filter(item => parseInt(item.value) === parseInt(value))
					this.experienceTitle = thisExperience[0].text
					this.params.experience = value
				} else {
					this.experienceTitle = '经验'
					this.params.experience = ''
				}
				this.doSearch({
					experience: value
				})
			},
			// 地区筛选打开之后给筛选组件赋值
			openedDistrict() {
				this.$refs.dropDistrict.$children[0].$children[0].initData()
				setTimeout(() => {
					this.setComponentAttribute(this.$refs.dropDistrict)
				}, 300)
			},
			closedDistrict() {
				this.$refs.dropDistrict.$children[0].$children[0].handleCityOverlay()
			},
			// 设置地区筛选组件高度
			setComponentAttribute(component) {
				if (component.$children[0]) {
					let thisHeight = component.$children[0].$el.clientHeight
					component.$children[0].$children[0].layHeight = thisHeight
					let offTop = component.$el.offsetTop
					component.$children[0].$children[0].offTop = parseInt(parseInt(offTop) + parseInt(thisHeight) / 2)
				}
			},
			// 恢复选中项
			restoreFilter() {
				let queryData = this.$route.query
				// 恢复地区
				if (queryData['district1']) {
					this.params.district1 = queryData['district1']
					this.params.district2 = queryData['district2']
					this.params.district3 = queryData['district3']
					let storeCity = this.$store.state.classifyCityOriginal
					let selectText = []
					let topItem = storeCity.filter(item => parseInt(item.value) === parseInt(this.params.district1))[0]
					selectText.push(topItem.label)
					if (topItem.children.length) {
						if (parseInt(this.params.district2)) {
							let secondItem = topItem.children.filter(item => parseInt(item.value) === parseInt(this.params
								.district2))[0]
							selectText.push(secondItem.label)
							if (secondItem.children.length) {
								if (parseInt(this.params.district3)) {
									let lowestItem = secondItem.children.filter(item => parseInt(item.value) === parseInt(
										this.params.district3))[0]
									selectText.push(lowestItem.label)
								} else {
									selectText.push(`全${selectText[selectText.length - 1]}`)
								}
							}
						} else {
							selectText.push(`全${selectText[selectText.length - 1]}`)
						}
					}
					this.districtTitle = selectText[selectText.length - 1]
				} else {
					this.params.district1 = ''
					this.params.district2 = ''
					this.params.district3 = ''
					this.districtTitle = '地区'
				}
				// 恢复经验
				if (queryData['experience'] && this.optionExperience) {
					let thisExperience = this.optionExperience.filter(item => parseInt(item.value) === parseInt(queryData[
						'experience']))
					this.experienceTitle = thisExperience[0].text
					this.params.experience = parseInt(thisExperience[0].value)
				} else {
					this.experienceTitle = '经验'
					this.params.experience = ''
				}
				// 恢复学历
				if (queryData['education'] && this.optionEducation) {
					let thisEducation = this.optionEducation.filter(item => parseInt(item.value) === parseInt(queryData[
						'education']))
					this.educationTitle = thisEducation[0].text
					this.params.education = parseInt(thisEducation[0].value)
				} else {
					this.educationTitle = '学历'
					this.params.education = ''
				}
				// 恢复性别
				let resetGender = ''
				if (queryData['sex']) {
					resetGender = queryData['sex']
				}
				this.optionGender = this.restructureData(this.optionGender, resetGender, 'gender')
				// 恢复年龄
				if (queryData['minage']) {
					this.params.minage = queryData['minage']
					this.params.maxage = queryData['maxage']
					this.optionAge = this.optionAge.map(function(item) {
						return {
							text: item.text,
							id: item.id,
							min: item.min,
							max: item.max,
							select: parseInt(item.min) === parseInt(queryData['minage'])
						}
					})
				} else {
					this.params.minage = ''
					this.params.maxage = ''
					this.optionAge = this.optionAge.map(function(item, index) {
						return {
							text: item.text,
							id: item.id,
							min: item.min,
							max: item.max,
							select: index === 0
						}
					})
				}
				// 恢复薪资
				if (queryData['minwage']) {
					this.params.minwage = queryData['minwage']
					this.params.maxwage = queryData['maxwage']
					this.optionWage = this.optionWage.map(function(item) {
						return {
							text: item.text,
							id: item.id,
							min: item.min,
							max: item.max,
							select: parseInt(item.min) === parseInt(queryData['minwage'])
						}
					})
				} else {
					this.params.minwage = ''
					this.params.maxwage = ''
					this.optionWage = this.optionWage.map(function(item, index) {
						return {
							text: item.text,
							id: item.id,
							min: item.min,
							max: item.max,
							select: index === 0
						}
					})
				}
				// 恢复简历标签
				this.restoreResumeTag()
				// 恢复更新时间
				let resetSettr = ''
				if (queryData['settr']) {
					resetSettr = queryData['settr']
				}
				this.optionSettr = this.restructureData(this.optionSettr, resetSettr, 'settr')
			},
			// 地区筛选
			doSearchByDistrict(data) {
				this.doSearch({
					district1: data.district1,
					district2: data.district2,
					district3: data.district3
				})
				this.districtTitle = data.districtName
				this.$refs.dropDistrict.toggle()
			},
			/**
			 * 重构数据
			 * @param data 需要重构的数据
			 * @param id 标记是否选中
			 * @param type 筛选条件
			 * @returns {*}
			 */
			restructureData(data, id, type) {
				let restoreArray = data
				let existSub = restoreArray.findIndex(item => {
					return item.id === ''
				})
				if (existSub === -1) {
					// 防止重复添加
					restoreArray.unshift({
						id: '',
						text: '全部'
					})
				}
				if (type === 'gender') {
					// 性别
					this.params.sex = id
				} else if (type === 'settr') {
					// 更新时间
					this.params.settr = id
				}
				restoreArray = restoreArray.map(function(item, index) {
					let iSelect = false
					if (id) {
						// 标记选中项
						iSelect = parseInt(item.id) === parseInt(id)
					} else {
						// 无选中项，选中全部
						iSelect = index === 0
					}
					return {
						id: item.id,
						text: item.text,
						select: iSelect
					}
				})
				return restoreArray
			},
			// 更多选择确定
			handleSelectMore() {
				this.doSearch({
					sex: this.params.sex,
					minage: this.params.minage,
					maxage: this.params.maxage,
					minwage: this.params.minwage,
					maxwage: this.params.maxwage,
					tag: this.params.tag,
					settr: this.params.settr
				})
				this.$refs.dropMore.toggle()
			},
			// 请求列表数据，init为true时直接更改dataset值，false时代表上拉加载回的数据追加进dataset
			fetchData(init) {
				this.show_empty = false

				let conditions = {
					...this.params
				}
				if (init === true) {
					this.page = 1
					this.finished = false
					this.finished_text = ''
				}
				conditions.page = this.page
				conditions.pagesize = this.pagesize
				http.get(api.resumelist, conditions)
					.then(res => {
						this.totalPage = res.data.total_page
						if (init === true) {
							this.dataset = [...res.data.items]
							this.showLayer = parseInt(res.data.show_mask) === 1
						} else {
							this.dataset = this.dataset.concat(res.data.items)
						}
						this.dataset.forEach(item => {
							item.clicked = false
						})
						this.getColorChange(); //缓存数据标题是否变色
						// 加载状态结束
						this.loading = false

						// 数据全部加载完成
						if (res.data.items.length === 0) {
							this.show_empty = true
						} else if (res.data.items.length < this.pagesize || this.page >= res.data.total_page) {
							this.finished = true
							// if (init === false) {
							this.finished_text = '暂无更多'
							// }
						}
					})
					.catch(() => {})
			},
			onLoad() {
				if (this.page < this.totalPage) {
					this.page++
					this.fetchData(false)
				}
			},
			toDetail(id) {
				let selectResumeArr = this.selectResumeArrOnload == undefined ? [] : this.selectResumeArrOnload
				if (selectResumeArr.indexOf(id) == -1) {
					selectResumeArr.push(id)
				}
				let selectResumeArrStr = JSON.stringify(selectResumeArr)
				localStorage.setItem('selectResumeArr', selectResumeArrStr)
				for (let i in this.dataset) {
					if (this.dataset[i].id == id && !this.dataset[i].clicked) {
						this.dataset[i].clicked = true
					}
				}
				/**
				 * 【bug】触屏简历列表进入详情右下角通过多功能侧边栏按钮返回首页，再次进入简历列表页时，会出现重复数据
				 *  zch 2022.10.9
				 * 【新增】列表置空
				 * this.dataset = []
				 * */
				// this.dataset = []
				this.$router.push('/resume/' + id)
			},
			// 搜索通用函数
			doSearch(data) {
				for (const key in data) {
					if (this.params.hasOwnProperty(key)) {
						this.params[key] = data[key]
					}
				}
				this.page = 1
				this.$router.push('/resumelist' + obj2Param(this.params, '?'))
			},
			// 关键词搜索函数，重置所有参数，并且把当前页设置为第一页
			doSearchByKeyword(data) {
				for (const key in this.params) {
					this.params[key] = ''
				}
				this.params.keyword = data.keyword
				this.page = 1
				this.$router.push('/resumelist' + obj2Param(this.params, '?'))
			},
			toggleSearch() {
				this.show = !this.show
			},
			showChange(e) {
				this.showWarn = e
			},
			getColorChange() {
				let LoginOrNotLocal = localStorage.getItem('LoginOrNot');
				let LoginOrNot = this.$store.state.LoginOrNot.toString();
				localStorage.setItem('LoginOrNot', LoginOrNot); //更新缓存中登录状态
				if (LoginOrNot != LoginOrNotLocal) { //登录状态改变时清空缓存数据
					localStorage.setItem('selectArr', null); //职位
					localStorage.setItem('selectResumeArr', null); //简历
					localStorage.setItem('selectComArr', null); //企业
				}
				this.selectResumeArrOnload = localStorage.getItem('selectResumeArr') == null || localStorage.getItem('selectResumeArr') == 'null' || localStorage.getItem(
					'selectResumeArr') == '' || localStorage.getItem(
					'selectResumeArr') == undefined ? [] : JSON.parse(localStorage.getItem('selectResumeArr'))
				this.selectResumeArrOnload.forEach(items => {
					this.dataset.forEach(item => {
						if (item.id == items) {
							item.clicked = true
						}
					})
				})
			}
		}
	}
</script>

<style lang="scss" scoped>
	.more_box {
		.item_wrapper {
			.item_group {
				.item {
					&.select {
						background-color: #e5f1ff;
						color: #5da9fc;
					}

					display: block;
					float: left;
					width: 81px;
					margin: 0 7px 10px 0;
					text-align: center;
					background-color: #f4f4f4;
					font-size: 13px;
					padding: 6.5px 0;

					&:nth-of-type(4n) {
						margin-right: 0;
					}
				}
			}

			.item_title {
				padding: 15px 0;
				font-size: 15px;
				color: #333333;
			}

			padding: 0 15px;
			position: relative;

			.wrapper_bottom {
				.btn_c {
					&.blue {
						background-color: #1787fb;
						color: #ffffff;
					}

					flex: 1;
					background-color: #f4f4f4;
					color: #333333;
					font-size: 15px;
					text-align: center;
					padding: 10.5px 0;
				}

				position: fixed;
				left: 0;
				top: 79%;
				display: flex;
				width: 100%;
				z-index: 3;
			}
		}

		position: relative;
		padding-bottom: 41px;
	}

	.login_layer {
		width: 100%;
		padding: 40px 0;
		text-align: center;
		background: url('../assets/images/login_layer_resume_bg.jpg') 0 no-repeat;
		background-size: 100%;

		.ll_tip_tel {
			font-size: 14px;
			color: #666;
			padding-top: 10px;
		}

		.ll_tip_bth {
			.a_btn {
				&.blue {
					background: #128bed;
				}

				display: inline-block;
				padding: 6px 25px;
				background: #e33244;
				color: #fff;
				border-radius: 3px;
				margin: 0 10px;
				font-size: 14px;
			}
		}

		.ll_qr_box {
			.ll_qr {
				width: 100px;
				height: 100px;
				border: 0;
			}

			width: 100px;
			height: 100px;
			margin: 10px auto 5px;
		}

		.ll_tip {
			font-size: 16px;
			font-weight: bold;
			margin-bottom: 5px;
			color: #333;
		}

		.ll_tip_more {
			font-size: 14px;
			color: #666;
			margin-bottom: 10px;
		}
	}

	.box_3 {
		.list {
			.tag {
				padding: 3px 5px 3px 17px;
				border-radius: 3px;
				color: #ffffff;
				font-size: 10px;
				position: absolute;
				right: 17px;
				bottom: 15px;
				background: #ffa57d url('../assets/images/fab_ico.svg') 5px center no-repeat;
				background-size: 10px;
			}

			.top {
				position: absolute;
				right: -25px;
				top: -25px;
				width: 50px;
				height: 50px;
				background-color: #feae41;
				color: #ffffff;
				font-weight: bold;
				text-align: center;
				transform: rotateZ(45deg);
				padding-top: 36px;
				font-size: 10px;
			}

			.tx4 {
				font-size: 13px;
				color: #999999;
				padding-right: 90px;
				overflow: hidden;
				white-space: nowrap;
				text-overflow: ellipsis;
				padding-bottom: 17px;
			}

			.tx3 {
				span {
					color: #666666;
				}

				font-size: 13px;
				color: #999999;
				overflow: hidden;
				white-space: nowrap;
				text-overflow: ellipsis;
				padding-bottom: 11.5px;
			}

			.up {
				.tx2 {
					.time {
						position: absolute;
						right: 0;
						top: 7px;
						font-size: 13px;
						color: #999999;
					}

					position: relative;
					font-size: 15px;
					color: #666666;
					padding: 5px 0 17.5px;
				}

				.tx1 {
					.wage {
						position: absolute;
						right: 0;
						top: 25px;
						font-size: 14px;
						font-weight: bold;
						color: #ff5d24;
					}

					.level_ico {
						float: left;
						margin-left: 10px;
						width: 36px;
						height: 25px;
						background: url('../assets/images/resume_list_level_ico.png') 0 center no-repeat;
						background-size: 36px 15px;
					}

					.name {
						float: left;
						font-size: 18px;
						font-weight: bold;
						color: #333333;

						&.clickedColor {
							color: #909090;
						}
					}

					position: relative;
					padding-top: 22.5px;
				}

				.avatar_box {
					.gender {
						&.female {
							background: #ff8d65 url('../assets/images/female_ico.svg') center no-repeat;
							background-size: 9px;
						}

						&.male {
							background: #4fa5fa url('../assets/images/male_ico.svg') center no-repeat;
							background-size: 9px;
						}

						position: absolute;
						right: 0;
						bottom: 2px;
						width: 13px;
						height: 13px;
						border-radius: 100%;
					}

					img {
						width: 49px;
						height: 49px;
						border: 0;
						border-radius: 100%;
					}

					position: absolute;
					left: 0;
					top: 22px;
					width: 49px;
					height: 49px;
				}

				position: relative;
				padding-left: 61px;
			}

			position: relative;
			width: 100%;
			background-color: #ffffff;
			padding: 0 17px;
			overflow: hidden;
		}

		width: 100%;
	}

	.box_2 {
		.van-hairline--top-bottom {
			&::after {
				border: 0;
			}
		}
	}

	.box_1 {
		.content {
			.search_ico {
				font-size: 12px;
				color: #c9c9c9;
				padding: 10px 0 10px 23px;
				background: url('../assets/images/search_ico_gray.svg') 0 center no-repeat;
				background-size: 15px;
				position: absolute;
				top: 0;
				left: 50%;
				transform: translate(-50%, 0);
				line-height: normal;

				&.has {
					color: #333;
				}
			}

			position: relative;
			width: 340px;
			height: 37px;
			margin: 0 auto;
			background-color: #f8f8f8;
			text-align: center;
			border-radius: 36px;
		}

		width: 100%;
		background-color: #ffffff;
		padding-top: 11px;
	}
</style>
