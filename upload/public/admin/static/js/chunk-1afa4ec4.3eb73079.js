(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["chunk-1afa4ec4"],{"0ccb":function(t,e,n){var r=n("50c4"),a=n("1148"),i=n("1d80"),l=Math.ceil,o=function(t){return function(e,n,o){var c,u,s=String(i(e)),d=s.length,f=void 0===o?" ":String(o),g=r(n);return g<=d||""==f?s:(c=g-d,u=a.call(f,l(c/f.length)),u.length>c&&(u=u.slice(0,c)),t?s+u:u+s)}};t.exports={start:o(!1),end:o(!0)}},1148:function(t,e,n){"use strict";var r=n("a691"),a=n("1d80");t.exports="".repeat||function(t){var e=String(a(this)),n="",i=r(t);if(i<0||i==1/0)throw RangeError("Wrong number of repetitions");for(;i>0;(i>>>=1)&&(e+=e))1&i&&(n+=e);return n}},1276:function(t,e,n){"use strict";var r=n("d784"),a=n("44e7"),i=n("825a"),l=n("1d80"),o=n("4840"),c=n("8aa5"),u=n("50c4"),s=n("14c3"),d=n("9263"),f=n("d039"),g=[].push,p=Math.min,h=4294967295,v=!f((function(){return!RegExp(h,"y")}));r("split",2,(function(t,e,n){var r;return r="c"=="abbc".split(/(b)*/)[1]||4!="test".split(/(?:)/,-1).length||2!="ab".split(/(?:ab)*/).length||4!=".".split(/(.?)(.?)/).length||".".split(/()()/).length>1||"".split(/.?/).length?function(t,n){var r=String(l(this)),i=void 0===n?h:n>>>0;if(0===i)return[];if(void 0===t)return[r];if(!a(t))return e.call(r,t,i);var o,c,u,s=[],f=(t.ignoreCase?"i":"")+(t.multiline?"m":"")+(t.unicode?"u":"")+(t.sticky?"y":""),p=0,v=new RegExp(t.source,f+"g");while(o=d.call(v,r)){if(c=v.lastIndex,c>p&&(s.push(r.slice(p,o.index)),o.length>1&&o.index<r.length&&g.apply(s,o.slice(1)),u=o[0].length,p=c,s.length>=i))break;v.lastIndex===o.index&&v.lastIndex++}return p===r.length?!u&&v.test("")||s.push(""):s.push(r.slice(p)),s.length>i?s.slice(0,i):s}:"0".split(void 0,0).length?function(t,n){return void 0===t&&0===n?[]:e.call(this,t,n)}:e,[function(e,n){var a=l(this),i=void 0==e?void 0:e[t];return void 0!==i?i.call(e,a,n):r.call(String(a),e,n)},function(t,a){var l=n(r,t,this,a,r!==e);if(l.done)return l.value;var d=i(t),f=String(this),g=o(d,RegExp),b=d.unicode,m=(d.ignoreCase?"i":"")+(d.multiline?"m":"")+(d.unicode?"u":"")+(v?"y":"g"),x=new g(v?d:"^(?:"+d.source+")",m),y=void 0===a?h:a>>>0;if(0===y)return[];if(0===f.length)return null===s(x,f)?[f]:[];var _=0,E=0,S=[];while(E<f.length){x.lastIndex=v?E:0;var w,I=s(x,v?f:f.slice(E));if(null===I||(w=p(u(x.lastIndex+(v?0:E)),f.length))===_)E=c(f,E,b);else{if(S.push(f.slice(_,E)),S.length===y)return S;for(var R=1;R<=I.length-1;R++)if(S.push(I[R]),S.length===y)return S;E=_=w}}return S.push(f.slice(_)),S}]}),!v)},"14c3":function(t,e,n){var r=n("c6b6"),a=n("9263");t.exports=function(t,e){var n=t.exec;if("function"===typeof n){var i=n.call(t,e);if("object"!==typeof i)throw TypeError("RegExp exec method returned something other than an Object or null");return i}if("RegExp"!==r(t))throw TypeError("RegExp#exec called on incompatible receiver");return a.call(t,e)}},"28d8":function(t,e,n){"use strict";n.r(e);var r=function(){var t=this,e=t.$createElement,n=t._self._c||e;return n("div",{staticClass:"app-container"},[n("el-card",{staticClass:"box-card"},[n("div",{staticClass:"clearfix",attrs:{slot:"header"},slot:"header"},[n("span",[t._v("发放记录")])]),n("el-table",{directives:[{name:"loading",rawName:"v-loading",value:t.listLoading,expression:"listLoading"}],attrs:{data:t.list,"element-loading-text":"Loading",fit:"","highlight-current-row":""}},[n("el-table-column",{attrs:{label:"发放时间"},scopedSlots:t._u([{key:"default",fn:function(e){return[n("span",[t._v(t._s(t._f("timeFilter")(e.row.addtime)))])]}}])}),n("el-table-column",{attrs:{label:"操作管理员",prop:"admin_name"}}),n("el-table-column",{attrs:{label:"发放企业数",prop:"count_num"}}),n("el-table-column",{attrs:{fixed:"right",label:"操作",width:"220"},scopedSlots:t._u([{key:"default",fn:function(e){return[n("el-button",{attrs:{size:"small",type:"primary"},on:{click:function(n){return t.funDetail(e.row)}}},[t._v(" 查看详情 ")])]}}])})],1),n("div",{staticClass:"spaceline"}),n("el-row",{attrs:{gutter:20}},[n("el-col",{attrs:{span:8}}),n("el-col",{staticStyle:{"text-align":"right"},attrs:{offset:8,span:16}},[n("el-pagination",{attrs:{background:"","current-page":t.currentPage,"page-sizes":[10,15,20,30,40],"page-size":t.pagesize,layout:"total, sizes, prev, pager, next, jumper",total:t.total},on:{"size-change":t.handleSizeChange,"current-change":t.handleCurrentChange}})],1)],1)],1),t.dialogFormVisible?n("el-dialog",{attrs:{title:"发放详情",visible:t.dialogFormVisible,width:"50%","close-on-click-modal":!1},on:{"update:visible":function(e){t.dialogFormVisible=e},close:t.closeDialog}},[n("dialist",{attrs:{"log-id":t.logId},on:{setDialogFormVisible:t.closeDialog}})],1):t._e()],1)},a=[],i=function(){var t=this,e=t.$createElement,n=t._self._c||e;return n("div",{staticClass:"app-container"},[n("el-table",{directives:[{name:"loading",rawName:"v-loading",value:t.listLoading,expression:"listLoading"}],attrs:{data:t.list,"element-loading-text":"Loading",fit:"",size:"small","highlight-current-row":""}},[n("el-table-column",{attrs:{label:"优惠券名称",prop:"coupon_name"}}),n("el-table-column",{attrs:{label:"发放企业"},scopedSlots:t._u([{key:"default",fn:function(e){return[t._v(" 【uid:"+t._s(e.row.uid)+"】"+t._s(e.row.companyname)+" ")]}}])}),n("el-table-column",{attrs:{align:"center",label:"发放时间"},scopedSlots:t._u([{key:"default",fn:function(e){return[n("span",[t._v(t._s(t._f("timeFilter")(e.row.addtime)))])]}}])}),n("el-table-column",{attrs:{align:"center",label:"是否使用"},scopedSlots:t._u([{key:"default",fn:function(e){return[e.row.usetime>0?n("span",{staticClass:"font_success"},[t._v("已使用")]):n("span",{staticClass:"font_warning"},[t._v("未使用")])]}}])}),n("el-table-column",{attrs:{align:"center",label:"使用时间"},scopedSlots:t._u([{key:"default",fn:function(e){return[0==e.row.usetime?n("span",[t._v("-")]):n("span",[t._v(t._s(t._f("timeFilter")(e.row.usetime)))])]}}])})],1),n("div",{staticClass:"spaceline"}),n("el-row",{attrs:{gutter:20}},[n("el-col",{staticStyle:{"text-align":"right"},attrs:{span:20}},[n("el-pagination",{attrs:{layout:"prev, pager, next",background:"",total:t.total,"current-page":t.currentPage,"page-size":t.pagesize},on:{"current-change":t.handleCurrentChange}})],1)],1)],1)},l=[],o=n("cbfe"),c=n("ed08"),u={filters:{timeFilter:function(t){return Object(c["b"])(t,"{y}-{m}-{d} {h}:{i}")}},props:["logId"],data:function(){return{list:null,listLoading:!0,total:0,currentPage:1,pagesize:5}},created:function(){this.fetchData()},methods:{fetchData:function(){var t=this;this.listLoading=!0;var e={log_id:this.logId,page:this.currentPage,pagesize:this.pagesize};Object(o["f"])(e).then((function(e){t.list=e.data.items,t.listLoading=!1,t.total=e.data.total,t.currentPage=e.data.current_page,t.pagesize=e.data.pagesize}))},handleCurrentChange:function(t){this.currentPage=t,this.fetchData()}}},s=u,d=n("2877"),f=Object(d["a"])(s,i,l,!1,null,null,null),g=f.exports,p={components:{dialist:g},filters:{timeFilter:function(t){return Object(c["b"])(t,"{y}-{m}-{d} {h}:{i}")}},data:function(){return{logId:0,dialogFormVisible:!1,list:null,listLoading:!0,total:0,currentPage:1,pagesize:10}},created:function(){this.fetchData()},methods:{fetchData:function(){var t=this;this.listLoading=!0;var e={page:this.currentPage,pagesize:this.pagesize};Object(o["e"])(e).then((function(e){t.list=e.data.items,t.listLoading=!1,t.total=e.data.total,t.currentPage=e.data.current_page,t.pagesize=e.data.pagesize}))},handleSizeChange:function(t){this.pagesize=t,this.fetchData()},handleCurrentChange:function(t){this.currentPage=t,this.fetchData()},closeDialog:function(){this.dialogFormVisible=!1},funDetail:function(t){this.logId=t.id,this.dialogFormVisible=!0}}},h=p,v=Object(d["a"])(h,r,a,!1,null,null,null);e["default"]=v.exports},"4d90":function(t,e,n){"use strict";var r=n("23e7"),a=n("0ccb").start,i=n("9a0c");r({target:"String",proto:!0,forced:i},{padStart:function(t){return a(this,t,arguments.length>1?arguments[1]:void 0)}})},5319:function(t,e,n){"use strict";var r=n("d784"),a=n("825a"),i=n("7b0b"),l=n("50c4"),o=n("a691"),c=n("1d80"),u=n("8aa5"),s=n("14c3"),d=Math.max,f=Math.min,g=Math.floor,p=/\$([$&'`]|\d\d?|<[^>]*>)/g,h=/\$([$&'`]|\d\d?)/g,v=function(t){return void 0===t?t:String(t)};r("replace",2,(function(t,e,n,r){var b=r.REGEXP_REPLACE_SUBSTITUTES_UNDEFINED_CAPTURE,m=r.REPLACE_KEEPS_$0,x=b?"$":"$0";return[function(n,r){var a=c(this),i=void 0==n?void 0:n[t];return void 0!==i?i.call(n,a,r):e.call(String(a),n,r)},function(t,r){if(!b&&m||"string"===typeof r&&-1===r.indexOf(x)){var i=n(e,t,this,r);if(i.done)return i.value}var c=a(t),g=String(this),p="function"===typeof r;p||(r=String(r));var h=c.global;if(h){var _=c.unicode;c.lastIndex=0}var E=[];while(1){var S=s(c,g);if(null===S)break;if(E.push(S),!h)break;var w=String(S[0]);""===w&&(c.lastIndex=u(g,l(c.lastIndex),_))}for(var I="",R=0,C=0;C<E.length;C++){S=E[C];for(var D=String(S[0]),P=d(f(o(S.index),g.length),0),O=[],z=1;z<S.length;z++)O.push(v(S[z]));var k=S.groups;if(p){var j=[D].concat(O,P,g);void 0!==k&&j.push(k);var L=String(r.apply(void 0,j))}else L=y(D,g,P,O,k,r);P>=R&&(I+=g.slice(R,P)+L,R=P+D.length)}return I+g.slice(R)}];function y(t,n,r,a,l,o){var c=r+t.length,u=a.length,s=h;return void 0!==l&&(l=i(l),s=p),e.call(o,s,(function(e,i){var o;switch(i.charAt(0)){case"$":return"$";case"&":return t;case"`":return n.slice(0,r);case"'":return n.slice(c);case"<":o=l[i.slice(1,-1)];break;default:var s=+i;if(0===s)return e;if(s>u){var d=g(s/10);return 0===d?e:d<=u?void 0===a[d-1]?i.charAt(1):a[d-1]+i.charAt(1):e}o=a[s-1]}return void 0===o?"":o}))}}))},"53ca":function(t,e,n){"use strict";n.d(e,"a",(function(){return r}));n("a4d3"),n("e01a"),n("d28b"),n("d3b7"),n("3ca3"),n("ddb0");function r(t){return r="function"===typeof Symbol&&"symbol"===typeof Symbol.iterator?function(t){return typeof t}:function(t){return t&&"function"===typeof Symbol&&t.constructor===Symbol&&t!==Symbol.prototype?"symbol":typeof t},r(t)}},"8aa5":function(t,e,n){"use strict";var r=n("6547").charAt;t.exports=function(t,e,n){return e+(n?r(t,e).length:1)}},9263:function(t,e,n){"use strict";var r=n("ad6d"),a=n("9f7f"),i=RegExp.prototype.exec,l=String.prototype.replace,o=i,c=function(){var t=/a/,e=/b*/g;return i.call(t,"a"),i.call(e,"a"),0!==t.lastIndex||0!==e.lastIndex}(),u=a.UNSUPPORTED_Y||a.BROKEN_CARET,s=void 0!==/()??/.exec("")[1],d=c||s||u;d&&(o=function(t){var e,n,a,o,d=this,f=u&&d.sticky,g=r.call(d),p=d.source,h=0,v=t;return f&&(g=g.replace("y",""),-1===g.indexOf("g")&&(g+="g"),v=String(t).slice(d.lastIndex),d.lastIndex>0&&(!d.multiline||d.multiline&&"\n"!==t[d.lastIndex-1])&&(p="(?: "+p+")",v=" "+v,h++),n=new RegExp("^(?:"+p+")",g)),s&&(n=new RegExp("^"+p+"$(?!\\s)",g)),c&&(e=d.lastIndex),a=i.call(f?n:d,v),f?a?(a.input=a.input.slice(h),a[0]=a[0].slice(h),a.index=d.lastIndex,d.lastIndex+=a[0].length):d.lastIndex=0:c&&a&&(d.lastIndex=d.global?a.index+a[0].length:e),s&&a&&a.length>1&&l.call(a[0],n,(function(){for(o=1;o<arguments.length-2;o++)void 0===arguments[o]&&(a[o]=void 0)})),a}),t.exports=o},"9a0c":function(t,e,n){var r=n("342f");t.exports=/Version\/10\.\d+(\.\d+)?( Mobile\/\w+)? Safari\//.test(r)},"9f7f":function(t,e,n){"use strict";var r=n("d039");function a(t,e){return RegExp(t,e)}e.UNSUPPORTED_Y=r((function(){var t=a("a","y");return t.lastIndex=2,null!=t.exec("abcd")})),e.BROKEN_CARET=r((function(){var t=a("^r","gy");return t.lastIndex=2,null!=t.exec("str")}))},ac1f:function(t,e,n){"use strict";var r=n("23e7"),a=n("9263");r({target:"RegExp",proto:!0,forced:/./.exec!==a},{exec:a})},cbfe:function(t,e,n){"use strict";n.d(e,"d",(function(){return i})),n.d(e,"a",(function(){return l})),n.d(e,"c",(function(){return o})),n.d(e,"b",(function(){return c})),n.d(e,"e",(function(){return u})),n.d(e,"f",(function(){return s})),n.d(e,"g",(function(){return d}));var r=n("b775"),a=n("d722");function i(t){return Object(r["a"])({url:a["a"].couponList,method:"get",params:t})}function l(t){return Object(r["a"])({url:a["a"].couponAdd,method:"post",data:t})}function o(t){var e=arguments.length>1&&void 0!==arguments[1]?arguments[1]:"post";return"post"==e?Object(r["a"])({url:a["a"].couponEdit,method:e,data:t}):Object(r["a"])({url:a["a"].couponEdit,method:e,params:t})}function c(t){return Object(r["a"])({url:a["a"].couponDelete,method:"post",data:t})}function u(t){return Object(r["a"])({url:a["a"].couponLog,method:"get",params:t})}function s(t){return Object(r["a"])({url:a["a"].couponRecord,method:"get",params:t})}function d(t){return Object(r["a"])({url:a["a"].couponSend,method:"post",data:t})}},d784:function(t,e,n){"use strict";n("ac1f");var r=n("6eeb"),a=n("d039"),i=n("b622"),l=n("9263"),o=n("9112"),c=i("species"),u=!a((function(){var t=/./;return t.exec=function(){var t=[];return t.groups={a:"7"},t},"7"!=="".replace(t,"$<a>")})),s=function(){return"$0"==="a".replace(/./,"$0")}(),d=i("replace"),f=function(){return!!/./[d]&&""===/./[d]("a","$0")}(),g=!a((function(){var t=/(?:)/,e=t.exec;t.exec=function(){return e.apply(this,arguments)};var n="ab".split(t);return 2!==n.length||"a"!==n[0]||"b"!==n[1]}));t.exports=function(t,e,n,d){var p=i(t),h=!a((function(){var e={};return e[p]=function(){return 7},7!=""[t](e)})),v=h&&!a((function(){var e=!1,n=/a/;return"split"===t&&(n={},n.constructor={},n.constructor[c]=function(){return n},n.flags="",n[p]=/./[p]),n.exec=function(){return e=!0,null},n[p](""),!e}));if(!h||!v||"replace"===t&&(!u||!s||f)||"split"===t&&!g){var b=/./[p],m=n(p,""[t],(function(t,e,n,r,a){return e.exec===l?h&&!a?{done:!0,value:b.call(e,n,r)}:{done:!0,value:t.call(n,e,r)}:{done:!1}}),{REPLACE_KEEPS_$0:s,REGEXP_REPLACE_SUBSTITUTES_UNDEFINED_CAPTURE:f}),x=m[0],y=m[1];r(String.prototype,t,x),r(RegExp.prototype,p,2==e?function(t,e){return y.call(t,this,e)}:function(t){return y.call(t,this)})}d&&o(RegExp.prototype[p],"sham",!0)}},ed08:function(t,e,n){"use strict";n.d(e,"b",(function(){return l})),n.d(e,"a",(function(){return o})),n.d(e,"c",(function(){return c}));n("d3b7"),n("ac1f"),n("25f0"),n("4d90"),n("5319"),n("1276");var r=n("53ca"),a=n("a78e"),i=n.n(a);function l(t,e){if(0===arguments.length)return null;var n,a=e||"{y}-{m}-{d} {h}:{i}:{s}";"object"===Object(r["a"])(t)?n=t:("string"===typeof t&&/^[0-9]+$/.test(t)&&(t=parseInt(t)),"number"===typeof t&&t.toString().length<=10&&(t*=1e3),n=new Date(t));var i={y:n.getFullYear(),m:n.getMonth()+1,d:n.getDate(),h:n.getHours(),i:n.getMinutes(),s:n.getSeconds(),a:n.getDay()},l=a.replace(/{([ymdhisa])+}/g,(function(t,e){var n=i[e];return"a"===e?["日","一","二","三","四","五","六"][n]:n.toString().padStart(2,"0")}));return l}function o(t,e){t=(""+t).length<=10?1e3*parseInt(t):+t;var n=new Date(t),r=Date.now(),a=(r-n)/1e3;return a<30?"刚刚":a<3600?Math.ceil(a/60)+"分钟前":a<86400?Math.ceil(a/3600)+"小时前":a<172800?"1天前":e?l(t,e):n.getMonth()+1+"月"+n.getDate()+"日"+n.getHours()+"时"+n.getMinutes()+"分"}function c(t){var e={utype:t.utype,token:t.token,mobile:t.mobile,userIminfo:t.user_iminfo};i.a.set("qscms_visitor",JSON.stringify(e),{expires:7})}}}]);