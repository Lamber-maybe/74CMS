(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["chunk-12105346"],{"0ab8":function(t,c,s){"use strict";s("6816")},"0fe2":function(t,c,s){"use strict";s.r(c);var i=function(){var t=this,c=t.$createElement,s=t._self._c||c;return s("div",[s("Head",{attrs:{"show-back":!1,"show-admin":!0}},[t._v("系统工具")]),s("div",{staticClass:"public_item_title"},[t._v("系统项目")]),s("div",{staticClass:"b1"},[s("div",{staticClass:"bc_line"},[s("div",{staticClass:"bc_cell c1",on:{click:t.clearcache}},[s("p",{staticClass:"t1"},[t._v("更新缓存")])]),s("div",{staticClass:"bc_cell c2",on:{click:t.closeOpenSite}},[s("p",{staticClass:"t1"},[t._v("网站启停")]),s("p",{staticClass:"t2"},[t._v(t._s(1==t.globalConfig.isclose?"关闭中":"已开启"))])]),s("div",{staticClass:"bc_cell c3",on:{click:t.closeOpenReg}},[s("p",{staticClass:"t1"},[t._v("暂停注册")]),s("p",{staticClass:"t2"},[t._v(t._s(1==t.globalConfig.closereg?"关闭注册":"允许注册"))])])])]),s("div",{staticClass:"public_item_title"},[t._v("个人业务管理")]),s("div",{staticClass:"b1"},[s("div",{staticClass:"bc_line"},[s("div",{staticClass:"bc_cell c4",on:{click:function(c){return t.$router.push("/config/admin")}}},[s("p",{staticClass:"t1"},[t._v("账号信息")])]),s("div",{staticClass:"bc_cell c5",on:{click:t.logout}},[s("p",{staticClass:"t1"},[t._v("退出登录")])]),s("div",{staticClass:"bc_cell c6"})])]),s("BottomNav")],1)},n=[],a=s("751a"),o=s("d722"),e={name:"configIndex",data:function(){return{globalConfig:{}}},created:function(){this.config()},methods:{config:function(){var t=this;a["a"].get(o["a"].config,{}).then((function(c){t.globalConfig=c.data})).catch((function(){}))},clearcache:function(){var t=this;t.$dialog.confirm({title:"",message:"确定清除缓存吗"}).then((function(){a["a"].get(o["a"].clearcache,{}).then((function(c){t.$toast.success(c.message)})).catch((function(){}))})).catch((function(){}))},closeOpenSite:function(){var t=this,c=0==t.globalConfig.isclose?"关闭":"开启";t.$dialog.confirm({title:"",message:"确定"+c+"网站吗"}).then((function(){a["a"].post(o["a"].closeOpenSite,{is_close:1==t.globalConfig.isclose?0:1}).then((function(){t.$toast.success(c+"网站成功"),t.config()})).catch((function(){}))})).catch((function(){}))},closeOpenReg:function(){var t=this,c=0==t.globalConfig.closereg?"关闭":"开启";t.$dialog.confirm({title:"",message:"确定"+c+"会员注册吗"}).then((function(){a["a"].post(o["a"].closeOpenReg,{is_close:1==t.globalConfig.closereg?0:1}).then((function(){t.$toast.success(c+"会员注册成功"),t.config()})).catch((function(){}))})).catch((function(){}))},logout:function(){var t=this;t.$dialog.confirm({title:"",message:"确定退出吗"}).then((function(){localStorage.removeItem("admintoken"),t.$toast.success("退出成功"),location.reload()})).catch((function(){}))}}},l=e,f=(s("0ab8"),s("2877")),u=Object(f["a"])(l,i,n,!1,null,"9848b010",null);c["default"]=u.exports},6816:function(t,c,s){}}]);