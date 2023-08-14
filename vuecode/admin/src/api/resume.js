import request from '@/utils/request'
import apiArr from '@/api'

export function resumeAdd(params, method = 'post') {
  if (method == 'post') {
    return request({
      url: apiArr.resumeAdd,
      method,
      data: params
    })
  } else {
    return request({
      url: apiArr.resumeAdd,
      method,
      params
    })
  }
}
export function resumeList(params) {
  return request({
    url: apiArr.resumeList,
    method: 'get',
    params
  })
}
export function resumeModuleList(params) {
  return request({
    url: apiArr.resumeModuleList,
    method: 'get',
    params
  })
}
export function resumeBasic(params, method = 'post') {
  if (method == 'post') {
    return request({
      url: apiArr.resumeBasic,
      method,
      data: params
    })
  } else {
    return request({
      url: apiArr.resumeBasic,
      method,
      params
    })
  }
}
export function resumeIntentionList(params) {
  return request({
    url: apiArr.resumeIntentionList,
    method: 'get',
    params
  })
}
export function resumeIntentionAddAndEdit(params, method = 'post') {
  if (method == 'post') {
    return request({
      url: apiArr.resumeIntentionAddAndEdit,
      method,
      data: params
    })
  } else {
    return request({
      url: apiArr.resumeIntentionAddAndEdit,
      method,
      params
    })
  }
}
export function resumeIntentionDelete(data) {
  return request({
    url: apiArr.resumeIntentionDelete,
    method: 'post',
    data
  })
}
export function resumeSpecialty(params, method = 'post') {
  if (method == 'post') {
    return request({
      url: apiArr.resumeSpecialty,
      method,
      data: params
    })
  } else {
    return request({
      url: apiArr.resumeSpecialty,
      method,
      params
    })
  }
}
export function resumeEducationList(params) {
  return request({
    url: apiArr.resumeEducationList,
    method: 'get',
    params
  })
}
export function resumeEducationAddAndEdit(params, method = 'post') {
  if (method == 'post') {
    return request({
      url: apiArr.resumeEducationAddAndEdit,
      method,
      data: params
    })
  } else {
    return request({
      url: apiArr.resumeEducationAddAndEdit,
      method,
      params
    })
  }
}
export function resumeEducationDelete(data) {
  return request({
    url: apiArr.resumeEducationDelete,
    method: 'post',
    data
  })
}
export function resumeWorkList(params) {
  return request({
    url: apiArr.resumeWorkList,
    method: 'get',
    params
  })
}
export function resumeWorkAddAndEdit(params, method = 'post') {
  if (method == 'post') {
    return request({
      url: apiArr.resumeWorkAddAndEdit,
      method,
      data: params
    })
  } else {
    return request({
      url: apiArr.resumeWorkAddAndEdit,
      method,
      params
    })
  }
}
export function resumeWorkDelete(data) {
  return request({
    url: apiArr.resumeWorkDelete,
    method: 'post',
    data
  })
}
export function resumeTrainingList(params) {
  return request({
    url: apiArr.resumeTrainingList,
    method: 'get',
    params
  })
}
export function resumeTrainingAddAndEdit(params, method = 'post') {
  if (method == 'post') {
    return request({
      url: apiArr.resumeTrainingAddAndEdit,
      method,
      data: params
    })
  } else {
    return request({
      url: apiArr.resumeTrainingAddAndEdit,
      method,
      params
    })
  }
}
export function resumeTrainingDelete(data) {
  return request({
    url: apiArr.resumeTrainingDelete,
    method: 'post',
    data
  })
}
export function resumeProjectList(params) {
  return request({
    url: apiArr.resumeProjectList,
    method: 'get',
    params
  })
}
export function resumeProjectAddAndEdit(params, method = 'post') {
  if (method == 'post') {
    return request({
      url: apiArr.resumeProjectAddAndEdit,
      method,
      data: params
    })
  } else {
    return request({
      url: apiArr.resumeProjectAddAndEdit,
      method,
      params
    })
  }
}
export function resumeProjectDelete(data) {
  return request({
    url: apiArr.resumeProjectDelete,
    method: 'post',
    data
  })
}
export function resumeCertificateList(params) {
  return request({
    url: apiArr.resumeCertificateList,
    method: 'get',
    params
  })
}
export function resumeCertificateAddAndEdit(params, method = 'post') {
  if (method == 'post') {
    return request({
      url: apiArr.resumeCertificateAddAndEdit,
      method,
      data: params
    })
  } else {
    return request({
      url: apiArr.resumeCertificateAddAndEdit,
      method,
      params
    })
  }
}
export function resumeCertificateDelete(data) {
  return request({
    url: apiArr.resumeCertificateDelete,
    method: 'post',
    data
  })
}
export function resumeLanguageList(params) {
  return request({
    url: apiArr.resumeLanguageList,
    method: 'get',
    params
  })
}
export function resumeLanguageAddAndEdit(params, method = 'post') {
  if (method == 'post') {
    return request({
      url: apiArr.resumeLanguageAddAndEdit,
      method,
      data: params
    })
  } else {
    return request({
      url: apiArr.resumeLanguageAddAndEdit,
      method,
      params
    })
  }
}
export function resumeLanguageDelete(data) {
  return request({
    url: apiArr.resumeLanguageDelete,
    method: 'post',
    data
  })
}
export function resumeTag(params, method = 'post') {
  if (method == 'post') {
    return request({
      url: apiArr.resumeTag,
      method,
      data: params
    })
  } else {
    return request({
      url: apiArr.resumeTag,
      method,
      params
    })
  }
}
export function resumeImg(params) {
  return request({
    url: apiArr.resumeImg,
    method: 'get',
    params
  })
}
export function resumeImgAdd(data) {
  return request({
    url: apiArr.resumeImgAdd,
    method: 'post',
    data
  })
}
export function resumeImgDelete(data) {
  return request({
    url: apiArr.resumeImgDelete,
    method: 'post',
    data
  })
}
export function resumeComment(data) {
  return request({
    url: apiArr.resumeComment,
    method: 'post',
    data
  })
}
export function resumeLevel(data) {
  return request({
    url: apiArr.resumeLevel,
    method: 'post',
    data
  })
}
export function resumeAudit(data) {
  return request({
    url: apiArr.resumeAudit,
    method: 'post',
    data
  })
}
export function resumeImgList(params) {
  return request({
    url: apiArr.resumeImgList,
    method: 'get',
    params
  })
}
export function resumeImgAudit(data) {
  return request({
    url: apiArr.resumeImgAudit,
    method: 'post',
    data
  })
}
export function resumeImgDel(data) {
  return request({
    url: apiArr.resumeImgDel,
    method: 'post',
    data
  })
}
export function resumeDelete(data) {
  return request({
    url: apiArr.resumeDelete,
    method: 'post',
    data
  })
}
export function resumeRefresh(data) {
  return request({
    url: apiArr.resumeRefresh,
    method: 'post',
    data
  })
}
export function jobApplyList(params) {
  return request({
    url: apiArr.jobApplyList,
    method: 'get',
    params
  })
}
export function resumeImport(data) {
  return request({
    url: apiArr.resumeImport,
    method: 'post',
    data
  })
}
