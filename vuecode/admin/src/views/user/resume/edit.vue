<template>
  <div v-loading="loading" class="app-container">
    <div
      v-for="(item,index) in moduleList"
      :key="index"
      :name="index"
    >
      <component :is="'resume_'+index" :id="id" @resumeDetails="resumeDetails" @setLoading="setLoading($event)" />
    </div>
    <resume_enclosure :id="id" :resumeEnclosure="resumeEnclosure" @resumeDetails="resumeDetails"></resume_enclosure>
  </div>
</template>

<script>
import { resumeModuleList } from '@/api/resume'
import resume_basic from './components/resumeedit/resumeBasic.vue'
import resume_intention from './components/resumeedit/resumeIntention.vue'
import resume_specialty from './components/resumeedit/resumeSpecialty.vue'
import resume_education from './components/resumeedit/resumeEducation.vue'
import resume_work from './components/resumeedit/resumeWork.vue'
import resume_training from './components/resumeedit/resumeTraining.vue'
import resume_project from './components/resumeedit/resumeProject.vue'
import resume_certificate from './components/resumeedit/resumeCertificate.vue'
import resume_language from './components/resumeedit/resumeLanguage.vue'
import resume_tag from './components/resumeedit/resumeTag.vue'
import resume_img from './components/resumeedit/resumeImg.vue'
import resume_enclosure from './components/resumeedit/resumeEnclosure.vue'

export default {
  components: {
    resume_basic,
    resume_intention,
    resume_specialty,
    resume_education,
    resume_work,
    resume_training,
    resume_project,
    resume_certificate,
    resume_language,
    resume_tag,
    resume_img,
    resume_enclosure
  },
  props: ['id','resumeEnclosure'],
  data() {
    return {
      moduleList: {},
      loading: true,
      resumeBasic: false,
      resumeIntention: false,
      resumeSpecialty: false,
      resumeEducation: false,
      resumeWork: false,
      resumeTag: false
    }
  },
  created() {
    this.getModuleList()
  },
  methods: {
    setLoading(e){
      this[e] = true
      if (this.resumeBasic &&
        this.resumeIntention &&
        this.resumeSpecialty &&
        this.resumeEducation &&
        this.resumeWork &&
        this.resumeTag
      ){
        this.loading = false
      }
    },
    getModuleList() {
      resumeModuleList().then(response => {
        this.moduleList = { ...response.data.items }
      })
    },
    resumeDetails(){
      this.$emit('resumeDetails')
    }
  }
}
</script>
<style lang="scss" scoped>
::v-deep .app-container{
  padding: 0px 20px;
}
::v-deep .el-card{
  border: 0px solid #EBEEF5;
  box-shadow:0 0px 0px;
}
</style>
