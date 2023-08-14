import request from '@/utils/request'
import apiArr from '@/api'


export function videoList(params) {
    return request({
        url: '/shortvideo/lists',
        method: 'get',
        params
    })
}
export function videoAudit(data) {
    return request({
        url: '/shortvideo/audit',
        method: 'post',
        data
    })
}
export function videoDel(data) {
    return request({
        url: '/shortvideo/del',
        method: 'post',
        data
    })
}

////////////

export function adList(params) {
    return request({
        url: '/shortvideo/ad_list',
        method: 'get',
        params
    })
}
export function adAdd(data) {
    return request({
        url: '/shortvideo/ad_add',
        method: 'post',
        data
    })
}
export function adEdit(params, method = 'post') {
    if (method == 'post') {
        return request({
            url: '/shortvideo/ad_edit',
            method,
            data: params
        })
    } else {
        return request({
            url: '/shortvideo/ad_edit',
            method,
            params
        })
    }
}
export function adDelete(data) {
    return request({
        url:  '/shortvideo/ad_del',
        method: 'post',
        data
    })
}
export function adInnerLinkOptions(params) {
    return request({
        url:  '/shortvideo/innerLinkOptions',
        method: 'get',
        params
    })
}

//

export function categoryList(params) {
    return request({
        url:  '/shortvideo/ad_cat_list',
        method: 'get',
        params
    })
}
export function categoryAdd(data) {
    return request({
        url:  '/shortvideo/ad_cat_add',
        method: 'post',
        data
    })
}
export function categoryEdit(params, method = 'post') {
    if (method == 'post') {
        return request({
            url:  '/shortvideo/ad_cat_edit',
            method,
            data: params
        })
    } else {
        return request({
            url:  '/shortvideo/ad_cat_edit',
            method,
            params
        })
    }
}
export function categoryDelete(data) {
    return request({
        url:  '/shortvideo/ad_cat_del',
        method: 'post',
        data
    })
}
export function adPlatformList(params) {
    return request({
        url:  '/shortvideo/ad_cat_platform',
        method: 'get',
        params
    })
}
export function adCategoryList(params) {
    return request({
        url:  '/shortvideo/ad_cat_tree',
        method: 'get',
        params
    })
}

