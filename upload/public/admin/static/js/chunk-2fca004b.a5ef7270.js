(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["chunk-2fca004b"],{"0ccb":function(t,e,n){var r=n("50c4"),a=n("1148"),i=n("1d80"),o=Math.ceil,l=function(t){return function(e,n,l){var c,u,s=String(i(e)),d=s.length,f=void 0===l?" ":String(l),g=r(n);return g<=d||""==f?s:(c=g-d,u=a.call(f,o(c/f.length)),u.length>c&&(u=u.slice(0,c)),t?s+u:u+s)}};t.exports={start:l(!1),end:l(!0)}},1148:function(t,e,n){"use strict";var r=n("a691"),a=n("1d80");t.exports="".repeat||function(t){var e=String(a(this)),n="",i=r(t);if(i<0||i==1/0)throw RangeError("Wrong number of repetitions");for(;i>0;(i>>>=1)&&(e+=e))1&i&&(n+=e);return n}},1276:function(t,e,n){"use strict";var r=n("d784"),a=n("44e7"),i=n("825a"),o=n("1d80"),l=n("4840"),c=n("8aa5"),u=n("50c4"),s=n("14c3"),d=n("9263"),f=n("d039"),g=[].push,p=Math.min,h=4294967295,v=!f((function(){return!RegExp(h,"y")}));r("split",2,(function(t,e,n){var r;return r="c"=="abbc".split(/(b)*/)[1]||4!="test".split(/(?:)/,-1).length||2!="ab".split(/(?:ab)*/).length||4!=".".split(/(.?)(.?)/).length||".".split(/()()/).length>1||"".split(/.?/).length?function(t,n){var r=String(o(this)),i=void 0===n?h:n>>>0;if(0===i)return[];if(void 0===t)return[r];if(!a(t))return e.call(r,t,i);var l,c,u,s=[],f=(t.ignoreCase?"i":"")+(t.multiline?"m":"")+(t.unicode?"u":"")+(t.sticky?"y":""),p=0,v=new RegExp(t.source,f+"g");while(l=d.call(v,r)){if(c=v.lastIndex,c>p&&(s.push(r.slice(p,l.index)),l.length>1&&l.index<r.length&&g.apply(s,l.slice(1)),u=l[0].length,p=c,s.length>=i))break;v.lastIndex===l.index&&v.lastIndex++}return p===r.length?!u&&v.test("")||s.push(""):s.push(r.slice(p)),s.length>i?s.slice(0,i):s}:"0".split(void 0,0).length?function(t,n){return void 0===t&&0===n?[]:e.call(this,t,n)}:e,[function(e,n){var a=o(this),i=void 0==e?void 0:e[t];return void 0!==i?i.call(e,a,n):r.call(String(a),e,n)},function(t,a){var o=n(r,t,this,a,r!==e);if(o.done)return o.value;var d=i(t),f=String(this),g=l(d,RegExp),x=d.unicode,b=(d.ignoreCase?"i":"")+(d.multiline?"m":"")+(d.unicode?"u":"")+(v?"y":"g"),m=new g(v?d:"^(?:"+d.source+")",b),y=void 0===a?h:a>>>0;if(0===y)return[];if(0===f.length)return null===s(m,f)?[f]:[];var E=0,S=0,R=[];while(S<f.length){m.lastIndex=v?S:0;var w,_=s(m,v?f:f.slice(S));if(null===_||(w=p(u(m.lastIndex+(v?0:S)),f.length))===E)S=c(f,S,x);else{if(R.push(f.slice(E,S)),R.length===y)return R;for(var I=1;I<=_.length-1;I++)if(R.push(_[I]),R.length===y)return R;S=E=w}}return R.push(f.slice(E)),R}]}),!v)},"14c3":function(t,e,n){var r=n("c6b6"),a=n("9263");t.exports=function(t,e){var n=t.exec;if("function"===typeof n){var i=n.call(t,e);if("object"!==typeof i)throw TypeError("RegExp exec method returned something other than an Object or null");return i}if("RegExp"!==r(t))throw TypeError("RegExp#exec called on incompatible receiver");return a.call(t,e)}},"4d90":function(t,e,n){"use strict";var r=n("23e7"),a=n("0ccb").start,i=n("9a0c");r({target:"String",proto:!0,forced:i},{padStart:function(t){return a(this,t,arguments.length>1?arguments[1]:void 0)}})},5319:function(t,e,n){"use strict";var r=n("d784"),a=n("825a"),i=n("7b0b"),o=n("50c4"),l=n("a691"),c=n("1d80"),u=n("8aa5"),s=n("14c3"),d=Math.max,f=Math.min,g=Math.floor,p=/\$([$&'`]|\d\d?|<[^>]*>)/g,h=/\$([$&'`]|\d\d?)/g,v=function(t){return void 0===t?t:String(t)};r("replace",2,(function(t,e,n,r){var x=r.REGEXP_REPLACE_SUBSTITUTES_UNDEFINED_CAPTURE,b=r.REPLACE_KEEPS_$0,m=x?"$":"$0";return[function(n,r){var a=c(this),i=void 0==n?void 0:n[t];return void 0!==i?i.call(n,a,r):e.call(String(a),n,r)},function(t,r){if(!x&&b||"string"===typeof r&&-1===r.indexOf(m)){var i=n(e,t,this,r);if(i.done)return i.value}var c=a(t),g=String(this),p="function"===typeof r;p||(r=String(r));var h=c.global;if(h){var E=c.unicode;c.lastIndex=0}var S=[];while(1){var R=s(c,g);if(null===R)break;if(S.push(R),!h)break;var w=String(R[0]);""===w&&(c.lastIndex=u(g,o(c.lastIndex),E))}for(var _="",I=0,$=0;$<S.length;$++){R=S[$];for(var C=String(R[0]),P=d(f(l(R.index),g.length),0),D=[],O=1;O<R.length;O++)D.push(v(R[O]));var T=R.groups;if(p){var k=[C].concat(D,P,g);void 0!==T&&k.push(T);var z=String(r.apply(void 0,k))}else z=y(C,g,P,D,T,r);P>=I&&(_+=g.slice(I,P)+z,I=P+C.length)}return _+g.slice(I)}];function y(t,n,r,a,o,l){var c=r+t.length,u=a.length,s=h;return void 0!==o&&(o=i(o),s=p),e.call(l,s,(function(e,i){var l;switch(i.charAt(0)){case"$":return"$";case"&":return t;case"`":return n.slice(0,r);case"'":return n.slice(c);case"<":l=o[i.slice(1,-1)];break;default:var s=+i;if(0===s)return e;if(s>u){var d=g(s/10);return 0===d?e:d<=u?void 0===a[d-1]?i.charAt(1):a[d-1]+i.charAt(1):e}l=a[s-1]}return void 0===l?"":l}))}}))},"53ca":function(t,e,n){"use strict";n.d(e,"a",(function(){return r}));n("a4d3"),n("e01a"),n("d28b"),n("d3b7"),n("3ca3"),n("ddb0");function r(t){return r="function"===typeof Symbol&&"symbol"===typeof Symbol.iterator?function(t){return typeof t}:function(t){return t&&"function"===typeof Symbol&&t.constructor===Symbol&&t!==Symbol.prototype?"symbol":typeof t},r(t)}},"8aa5":function(t,e,n){"use strict";var r=n("6547").charAt;t.exports=function(t,e,n){return e+(n?r(t,e).length:1)}},9263:function(t,e,n){"use strict";var r=n("ad6d"),a=n("9f7f"),i=RegExp.prototype.exec,o=String.prototype.replace,l=i,c=function(){var t=/a/,e=/b*/g;return i.call(t,"a"),i.call(e,"a"),0!==t.lastIndex||0!==e.lastIndex}(),u=a.UNSUPPORTED_Y||a.BROKEN_CARET,s=void 0!==/()??/.exec("")[1],d=c||s||u;d&&(l=function(t){var e,n,a,l,d=this,f=u&&d.sticky,g=r.call(d),p=d.source,h=0,v=t;return f&&(g=g.replace("y",""),-1===g.indexOf("g")&&(g+="g"),v=String(t).slice(d.lastIndex),d.lastIndex>0&&(!d.multiline||d.multiline&&"\n"!==t[d.lastIndex-1])&&(p="(?: "+p+")",v=" "+v,h++),n=new RegExp("^(?:"+p+")",g)),s&&(n=new RegExp("^"+p+"$(?!\\s)",g)),c&&(e=d.lastIndex),a=i.call(f?n:d,v),f?a?(a.input=a.input.slice(h),a[0]=a[0].slice(h),a.index=d.lastIndex,d.lastIndex+=a[0].length):d.lastIndex=0:c&&a&&(d.lastIndex=d.global?a.index+a[0].length:e),s&&a&&a.length>1&&o.call(a[0],n,(function(){for(l=1;l<arguments.length-2;l++)void 0===arguments[l]&&(a[l]=void 0)})),a}),t.exports=l},"9a0c":function(t,e,n){var r=n("342f");t.exports=/Version\/10\.\d+(\.\d+)?( Mobile\/\w+)? Safari\//.test(r)},"9f7f":function(t,e,n){"use strict";var r=n("d039");function a(t,e){return RegExp(t,e)}e.UNSUPPORTED_Y=r((function(){var t=a("a","y");return t.lastIndex=2,null!=t.exec("abcd")})),e.BROKEN_CARET=r((function(){var t=a("^r","gy");return t.lastIndex=2,null!=t.exec("str")}))},ab6d:function(t,e,n){"use strict";n.r(e);var r=function(){var t=this,e=t.$createElement,n=t._self._c||e;return n("div",{staticClass:"app-container"},[n("el-card",{staticClass:"box-card"},[n("div",{staticClass:"clearfix",attrs:{slot:"header"},slot:"header"},[n("span",[t._v("角色权限管理")])]),n("el-table",{directives:[{name:"loading",rawName:"v-loading",value:t.listLoading,expression:"listLoading"}],attrs:{data:t.list,"element-loading-text":"Loading",fit:"","highlight-current-row":""}},[n("el-table-column",{attrs:{align:"center",label:"ID",width:"80"},scopedSlots:t._u([{key:"default",fn:function(e){return[t._v(" "+t._s(e.row.id)+" ")]}}])}),n("el-table-column",{attrs:{label:"角色",prop:"name","min-width":"200"}}),n("el-table-column",{attrs:{label:"关联管理员",prop:"user_total","min-width":"100"}}),n("el-table-column",{attrs:{fixed:"right",label:"操作","min-width":"220"},scopedSlots:t._u([{key:"default",fn:function(e){return["all"==e.row.access?n("span",{staticStyle:{color:"#909399"}},[t._v(" 超级管理员不允许修改 ")]):n("span",[n("el-button",{attrs:{size:"small",type:"primary"},on:{click:function(n){return t.funEdit(e.$index,e.row)}}},[t._v(" 编辑 / 权限 ")]),n("el-button",{attrs:{size:"small",type:"danger"},on:{click:function(n){return t.funDelete(e.$index,e.row)}}},[t._v(" 删除 ")])],1)]}}])})],1),n("div",{staticClass:"spaceline"}),n("el-row",{attrs:{gutter:20}},[n("el-col",{attrs:{span:8}},[n("el-button",{attrs:{size:"small",type:"primary"},on:{click:function(e){return t.goTo("add")}}},[t._v(" 添加 ")])],1),n("el-col",{staticStyle:{"text-align":"right"},attrs:{span:16}},[n("el-pagination",{attrs:{background:"","current-page":t.currentPage,"page-sizes":[10,15,20,30,40],"page-size":t.pagesize,layout:"total, sizes, prev, pager, next, jumper",total:t.total},on:{"size-change":t.handleSizeChange,"current-change":t.handleCurrentChange}})],1)],1)],1)],1)},a=[],i=n("d00f"),o=n("ed08"),l={filters:{timeFilter:function(t){return Object(o["a"])(t,"{y}-{m}-{d} {h}:{i}")}},data:function(){return{list:null,listLoading:!0,total:0,currentPage:1,pagesize:10}},created:function(){this.fetchData()},methods:{fetchData:function(){var t=this;this.listLoading=!0;var e={page:this.currentPage,pagesize:this.pagesize};Object(i["d"])(e).then((function(e){t.list=e.data.items,t.listLoading=!1,t.total=e.data.total,t.currentPage=e.data.current_page,t.pagesize=e.data.pagesize}))},handleSizeChange:function(t){this.pagesize=t,this.fetchData()},handleCurrentChange:function(t){this.currentPage=t,this.fetchData()},funEdit:function(t,e){this.$router.push({path:"/sys/admin/role/edit",query:{id:e.id}})},funDelete:function(t,e){var n=this;n.$confirm("此操作将永久删除该数据, 是否继续?","提示",{confirmButtonText:"确定",cancelButtonText:"取消",type:"warning"}).then((function(){var t={id:e.id};Object(i["b"])(t).then((function(t){return n.$message.success(t.message),n.fetchData(),!0}))})).catch((function(){}))},goTo:function(t){this.$router.push("/sys/admin/role/"+t)}}},c=l,u=n("2877"),s=Object(u["a"])(c,r,a,!1,null,null,null);e["default"]=s.exports},ac1f:function(t,e,n){"use strict";var r=n("23e7"),a=n("9263");r({target:"RegExp",proto:!0,forced:/./.exec!==a},{exec:a})},d00f:function(t,e,n){"use strict";n.d(e,"d",(function(){return i})),n.d(e,"a",(function(){return o})),n.d(e,"c",(function(){return l})),n.d(e,"b",(function(){return c}));var r=n("b775"),a=n("d722");function i(t){return Object(r["a"])({url:a["a"].adminRoleList,method:"get",params:t})}function o(t){return Object(r["a"])({url:a["a"].adminRoleAdd,method:"post",data:t})}function l(t){var e=arguments.length>1&&void 0!==arguments[1]?arguments[1]:"post";return"post"==e?Object(r["a"])({url:a["a"].adminRoleEdit,method:e,data:t}):Object(r["a"])({url:a["a"].adminRoleEdit,method:e,params:t})}function c(t){return Object(r["a"])({url:a["a"].adminRoleDelete,method:"post",data:t})}},d784:function(t,e,n){"use strict";n("ac1f");var r=n("6eeb"),a=n("d039"),i=n("b622"),o=n("9263"),l=n("9112"),c=i("species"),u=!a((function(){var t=/./;return t.exec=function(){var t=[];return t.groups={a:"7"},t},"7"!=="".replace(t,"$<a>")})),s=function(){return"$0"==="a".replace(/./,"$0")}(),d=i("replace"),f=function(){return!!/./[d]&&""===/./[d]("a","$0")}(),g=!a((function(){var t=/(?:)/,e=t.exec;t.exec=function(){return e.apply(this,arguments)};var n="ab".split(t);return 2!==n.length||"a"!==n[0]||"b"!==n[1]}));t.exports=function(t,e,n,d){var p=i(t),h=!a((function(){var e={};return e[p]=function(){return 7},7!=""[t](e)})),v=h&&!a((function(){var e=!1,n=/a/;return"split"===t&&(n={},n.constructor={},n.constructor[c]=function(){return n},n.flags="",n[p]=/./[p]),n.exec=function(){return e=!0,null},n[p](""),!e}));if(!h||!v||"replace"===t&&(!u||!s||f)||"split"===t&&!g){var x=/./[p],b=n(p,""[t],(function(t,e,n,r,a){return e.exec===o?h&&!a?{done:!0,value:x.call(e,n,r)}:{done:!0,value:t.call(n,e,r)}:{done:!1}}),{REPLACE_KEEPS_$0:s,REGEXP_REPLACE_SUBSTITUTES_UNDEFINED_CAPTURE:f}),m=b[0],y=b[1];r(String.prototype,t,m),r(RegExp.prototype,p,2==e?function(t,e){return y.call(t,this,e)}:function(t){return y.call(t,this)})}d&&l(RegExp.prototype[p],"sham",!0)}},ed08:function(t,e,n){"use strict";n.d(e,"a",(function(){return o})),n.d(e,"b",(function(){return l}));n("d3b7"),n("ac1f"),n("25f0"),n("4d90"),n("5319"),n("1276");var r=n("53ca"),a=n("a78e"),i=n.n(a);function o(t,e){if(0===arguments.length)return null;var n,a=e||"{y}-{m}-{d} {h}:{i}:{s}";"object"===Object(r["a"])(t)?n=t:("string"===typeof t&&/^[0-9]+$/.test(t)&&(t=parseInt(t)),"number"===typeof t&&10===t.toString().length&&(t*=1e3),n=new Date(t));var i={y:n.getFullYear(),m:n.getMonth()+1,d:n.getDate(),h:n.getHours(),i:n.getMinutes(),s:n.getSeconds(),a:n.getDay()},o=a.replace(/{([ymdhisa])+}/g,(function(t,e){var n=i[e];return"a"===e?["日","一","二","三","四","五","六"][n]:n.toString().padStart(2,"0")}));return o}function l(t){var e={utype:t.utype,token:t.token,mobile:t.mobile,userIminfo:t.user_iminfo};i.a.set("qscms_visitor",JSON.stringify(e),{expires:7})}}}]);