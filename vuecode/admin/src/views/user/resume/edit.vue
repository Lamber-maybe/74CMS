<template>
  <div class="app-container">
    <div
      v-for="(item,index) in moduleList"
      :key="index"
      :name="index"
    >
      <component :is="'resume_'+index" />
    </div>
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
    resume_img
  },
  data() {
    return {
      moduleList: {}
    }
  },
  created() {
    this.getModuleList()
  },
  methods: {
    getModuleList() {
      resumeModuleList().then(response => {
        this.moduleList = { ...response.data.items }
      })
    }
  }
}
</script>
