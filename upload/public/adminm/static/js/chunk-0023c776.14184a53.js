(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["chunk-0023c776"],{"09f6":function(t,e,n){"use strict";var i=n("bd2d"),a=n("8160"),r=n("a78d"),o=n("8358"),s=n("5b80"),c=n("7f24"),u=n("4396"),f=n("92e2"),l=n("d7bf"),d=n("a53a"),h=n("9122"),p=d("isConcatSpreadable"),v=9007199254740991,b="Maximum allowed index exceeded",y=h>=51||!a((function(){var t=[];return t[p]=!1,t.concat()[0]!==t})),g=l("concat"),m=function(t){if(!o(t))return!1;var e=t[p];return void 0!==e?!!e:r(t)},w=!y||!g;i({target:"Array",proto:!0,forced:w},{concat:function(t){var e,n,i,a,r,o=s(this),l=f(o,0),d=0;for(e=-1,i=arguments.length;e<i;e++)if(r=-1===e?o:arguments[e],m(r)){if(a=c(r.length),d+a>v)throw TypeError(b);for(n=0;n<a;n++,d++)n in r&&u(l,d,r[n])}else{if(d>=v)throw TypeError(b);u(l,d++,r)}return l.length=d,l}})},1166:function(t,e,n){"use strict";n("65e7")},2127:function(t,e,n){var i=n("9207");i("iterator")},"332f":function(t,e,n){"use strict";function i(t,e){(null==e||e>t.length)&&(e=t.length);for(var n=0,i=new Array(e);n<e;n++)i[n]=t[n];return i}function a(t){if(Array.isArray(t))return i(t)}n.d(e,"a",(function(){return c}));n("c958"),n("839d"),n("a73a"),n("2127"),n("3dbb"),n("dcba"),n("9fae");function r(t){if("undefined"!==typeof Symbol&&null!=t[Symbol.iterator]||null!=t["@@iterator"])return Array.from(t)}n("ecfb"),n("3d83");function o(t,e){if(t){if("string"===typeof t)return i(t,e);var n=Object.prototype.toString.call(t).slice(8,-1);return"Object"===n&&t.constructor&&(n=t.constructor.name),"Map"===n||"Set"===n?Array.from(t):"Arguments"===n||/^(?:Ui|I)nt(?:8|16|32)(?:Clamped)?Array$/.test(n)?i(t,e):void 0}}function s(){throw new TypeError("Invalid attempt to spread non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method.")}function c(t){return a(t)||r(t)||o(t)||s()}},"3bd6":function(t,e,n){"use strict";n.r(e);var i=function(){var t=this,e=t.$createElement,n=t._self._c||e;return n("div",[n("Head",[t._v(t._s(t.title))]),n("Search",{attrs:{placeholder:"请输入职位名称/职位ID/手机号"},on:{doSearch:t.doSearch}}),n("van-overlay",{attrs:{"z-index":"10",show:!1===t.fetchDataFinish}},[n("van-loading",{staticClass:"loading",attrs:{color:"#1989fa"}},[t._v("数据加载中...")])],1),!0===t.show_empty?n("van-empty",{staticStyle:{"background-color":"#fff"},attrs:{image:"search",description:"没有找到对应的数据"}}):t._e(),t.dataset.length>0?n("van-list",{attrs:{finished:t.finished,"finished-text":t.finished_text,"immediate-check":!0},on:{load:t.onLoad},model:{value:t.loading,callback:function(e){t.loading=e},expression:"loading"}},[n("div",{staticClass:"b1"},t._l(t.dataset,(function(e,i){return n("div",{key:i,staticClass:"b_item",on:{click:function(n){return t.jumpShow(e.job_link)}}},[n("p",{staticClass:"t1 substring"},[t._v(t._s(e.jobname))]),n("van-icon",{staticClass:"arrow",attrs:{name:"arrow",color:"#999999"}}),n("div",{staticClass:"i_tag",class:t._f("auditFilter")(e.audit)},[t._v(t._s(t.options_audit[e.audit]))]),n("div",{staticClass:"t_line"},[n("div",{staticClass:"tl_cell substring"},[n("span",{staticClass:"l_title"},[t._v("企业：")]),n("span",{staticClass:"l_con mr"},[t._v(t._s(e.companyname))])])]),n("div",{staticClass:"t_line"},[n("div",{staticClass:"tl_cell"},[n("span",{staticClass:"l_title"},[t._v("联系人：")]),n("span",{staticClass:"l_con"},[t._v(t._s(e.contact))])]),n("div",{staticClass:"tl_cell"},[n("span",{staticClass:"l_title"},[t._v("状态：")]),n("span",{staticClass:"l_con"},[t._v(t._s(t.options_display[e.is_display]))])])]),n("div",{staticClass:"t_line last"},[n("div",{staticClass:"tl_cell"},[n("span",{staticClass:"l_title"},[t._v("手机号：")]),n("span",{staticClass:"l_con"},[t._v(t._s(e.mobile))])]),n("div",{staticClass:"tl_cell"},[n("span",{staticClass:"l_title"},[t._v("更新：")]),n("span",{staticClass:"l_con"},[t._v(t._s(t._f("timeFilter")(e.refreshtime)))])])]),n("div",{staticClass:"i_btn b1 blue",on:{click:function(n){return n.stopPropagation(),t.funAudit(e)}}},[t._v("审核")]),n("div",{staticClass:"i_btn b2"},[t._v("查看")])],1)})),0)]):t._e(),n("van-action-sheet",{attrs:{actions:t.auditActions,"cancel-text":"取消","close-on-click-action":""},on:{cancel:function(e){t.showAudit=!1},select:t.doAudit},model:{value:t.showAudit,callback:function(e){t.showAudit=e},expression:"showAudit"}}),n("van-dialog",{attrs:{title:"审核不通过原因","show-confirm-button":!1,"show-cancel-button":!1,closeOnClickOverlay:!0},model:{value:t.showReason,callback:function(e){t.showReason=e},expression:"showReason"}},[n("van-form",{on:{submit:t.onSubmitReason}},[n("van-field",{attrs:{rows:"3",autosize:"",label:"",type:"textarea",placeholder:"请填写审核不通过原因",rules:[{required:!0,message:""}]},model:{value:t.auditReason,callback:function(e){t.auditReason=e},expression:"auditReason"}}),n("div",{staticStyle:{margin:"16px"}},[n("van-button",{attrs:{round:"",block:"",type:"info","native-type":"submit"}},[t._v("提交")])],1)],1)],1)],1)},a=[],r=n("332f"),o=(n("f5f5"),n("3d83"),n("09f6"),n("751a")),s=n("d722"),c=n("ed08"),u={name:"jobList",filters:{timeFilter:function(t){return Object(c["a"])(t,"{y}-{m}-{d} {h}:{i}")},auditFilter:function(t){switch(t){case 0:return"wait";case 1:return"passed";case 2:return"failed";default:return"not"}}},data:function(){return{fetchDataFinish:!1,showReason:!1,auditResult:1,auditReason:"",auditRow:{},auditActions:[],showAudit:!1,dataset:[],loading:!1,finished:!1,finished_text:"",show_empty:!1,keyword:"",page:1,pagesize:15,regularMobile:/^13[0-9]{9}$|14[0-9]{9}$|15[0-9]{9}$|18[0-9]{9}$|17[0-9]{9}$|16[0-9]{9}$|19[0-9]{9}$/,regularNumber:/^\d+$/,title:"",type:1,options_audit:[],options_display:[]}},created:function(){this.type=parseInt(this.$route.params.type),this.title=1===this.type?"职位管理":"待审核职位",this.fetchClassify()},methods:{fetchClassify:function(){var t=this;o["a"].get(s["a"].classify,{type:"jobAudit,jobDisplay"}).then((function(e){t.auditActions=e.data.jobAudit,t.auditActions.forEach((function(e){t.options_audit[e.id]=e.name}));var n=Object(r["a"])(e.data.jobDisplay);n.forEach((function(e){t.options_display[e.id]=e.name})),t.fetchData(!0)})).catch((function(){}))},buildCondition:function(){var t={keyword:this.keyword};return t.page=this.page,t.pagesize=this.pagesize,t.list_type=1==this.type?"":"noaudit",this.regularMobile.test(t.keyword)?t.key_type=6:this.regularNumber.test(t.keyword)?t.key_type=3:t.key_type=1,t},fetchData:function(t){var e=this;this.show_empty=!1,!0===t&&(this.page=1,this.finished_text="",this.finished=!1);var n=this.buildCondition();o["a"].get(s["a"].jobList,n).then((function(n){e.dataset=!0===t?Object(r["a"])(n.data.items):e.dataset.concat(n.data.items),e.loading=!1,e.fetchDataFinish=!0,n.data.items.length<e.pagesize&&(e.finished=!0,!1===t?e.finished_text="没有更多了":0===n.data.items.length&&(e.show_empty=!0))})).catch((function(){}))},onLoad:function(){this.page++,this.fetchData(!1)},doSearch:function(t){this.fetchDataFinish=!1,this.keyword=t,this.fetchData(!0)},funAudit:function(t){this.auditRow=t,this.showAudit=!0},doAudit:function(t){this.auditResult=t.id,2==t.id?(this.showAudit=!1,this.showReason=!0):this.onSubmitReason()},onSubmitReason:function(){var t=this;o["a"].post(s["a"].jobAudit,{id:[this.auditRow.id],audit:this.auditResult,reason:this.auditReason}).then((function(e){t.$toast.success(e.message),t.fetchData(!0),t.showAudit=!1,t.showReason=!1,t.auditRow={},t.auditReason=""})).catch((function(){}))},jumpShow:function(t){location.href=t}}},f=u,l=(n("1166"),n("cba8")),d=Object(l["a"])(f,i,a,!1,null,"4f60e0d7",null);e["default"]=d.exports},4396:function(t,e,n){"use strict";var i=n("e036"),a=n("3361"),r=n("1368");t.exports=function(t,e,n){var o=i(e);o in t?a.f(t,o,r(0,n)):t[o]=n}},"460f":function(t,e,n){"use strict";var i=n("bd2d"),a=n("aaaf").start,r=n("4d8d");i({target:"String",proto:!0,forced:r},{padStart:function(t){return a(this,t,arguments.length>1?arguments[1]:void 0)}})},"4d8d":function(t,e,n){var i=n("8525");t.exports=/Version\/10(?:\.\d+){1,2}(?: [\w./]+)?(?: Mobile\/\w+)? Safari\//.test(i)},"64b7":function(t,e,n){var i=n("f5f7"),a=n("0eda");t.exports=function(t,e,n,r){try{return r?e(i(n)[0],n[1]):e(n)}catch(o){throw a(t),o}}},"65e7":function(t,e,n){},"6e57":function(t,e,n){"use strict";var i=n("7509"),a=n("86c2"),r=n("c3af");t.exports=function(t){var e=a(r(this)),n="",o=i(t);if(o<0||o==1/0)throw RangeError("Wrong number of repetitions");for(;o>0;(o>>>=1)&&(e+=e))1&o&&(n+=e);return n}},"70ac":function(t,e,n){"use strict";var i=n("c128"),a=n("f5f7"),r=n("86c2"),o=n("8160"),s=n("d9a6"),c="toString",u=RegExp.prototype,f=u[c],l=o((function(){return"/a/b"!=f.call({source:"a",flags:"b"})})),d=f.name!=c;(l||d)&&i(RegExp.prototype,c,(function(){var t=a(this),e=r(t.source),n=t.flags,i=r(void 0===n&&t instanceof RegExp&&!("flags"in u)?s.call(t):n);return"/"+e+"/"+i}),{unsafe:!0})},"839d":function(t,e,n){"use strict";var i=n("bd2d"),a=n("d053"),r=n("6b88"),o=n("cb56"),s=n("8358"),c=n("3361").f,u=n("22069"),f=r.Symbol;if(a&&"function"==typeof f&&(!("description"in f.prototype)||void 0!==f().description)){var l={},d=function(){var t=arguments.length<1||void 0===arguments[0]?void 0:String(arguments[0]),e=this instanceof d?new f(t):void 0===t?f():f(t);return""===t&&(l[e]=!0),e};u(d,f);var h=d.prototype=f.prototype;h.constructor=d;var p=h.toString,v="Symbol(test)"==String(f("test")),b=/^Symbol\((.*)\)[^)]+$/;c(h,"description",{configurable:!0,get:function(){var t=s(this)?this.valueOf():this,e=p.call(t);if(o(l,t))return"";var n=v?e.slice(7,-1):e.replace(b,"$1");return""===n?void 0:n}}),i({global:!0,forced:!0},{Symbol:d})}},9207:function(t,e,n){var i=n("afea"),a=n("cb56"),r=n("c17f"),o=n("3361").f;t.exports=function(t){var e=i.Symbol||(i.Symbol={});a(e,t)||o(e,t,{value:r.f(t)})}},"9fae":function(t,e,n){var i=n("bd2d"),a=n("bc14"),r=n("bb89"),o=!r((function(t){Array.from(t)}));i({target:"Array",stat:!0,forced:o},{from:a})},aaaf:function(t,e,n){var i=n("7f24"),a=n("86c2"),r=n("6e57"),o=n("c3af"),s=Math.ceil,c=function(t){return function(e,n,c){var u,f,l=a(o(e)),d=l.length,h=void 0===c?" ":a(c),p=i(n);return p<=d||""==h?l:(u=p-d,f=r.call(h,s(u/h.length)),f.length>u&&(f=f.slice(0,u)),t?l+f:f+l)}};t.exports={start:c(!1),end:c(!0)}},afea:function(t,e,n){var i=n("6b88");t.exports=i},b7d4:function(t,e,n){"use strict";var i=n("bd2d"),a=n("8088"),r=n("1f3c"),o=n("4649"),s=[].join,c=a!=Object,u=o("join",",");i({target:"Array",proto:!0,forced:c||!u},{join:function(t){return s.call(r(this),void 0===t?",":t)}})},bc14:function(t,e,n){"use strict";var i=n("6ed4"),a=n("5b80"),r=n("64b7"),o=n("49dd"),s=n("7f24"),c=n("4396"),u=n("3a2c");t.exports=function(t){var e,n,f,l,d,h,p=a(t),v="function"==typeof this?this:Array,b=arguments.length,y=b>1?arguments[1]:void 0,g=void 0!==y,m=u(p),w=0;if(g&&(y=i(y,b>2?arguments[2]:void 0,2)),void 0==m||v==Array&&o(m))for(e=s(p.length),n=new v(e);e>w;w++)h=g?y(p[w],w):p[w],c(n,w,h);else for(l=m.call(p),d=l.next,n=new v;!(f=d.call(l)).done;w++)h=g?r(l,y,[f.value,w],!0):f.value,c(n,w,h);return n.length=w,n}},c17f:function(t,e,n){var i=n("a53a");e.f=i},c958:function(t,e,n){"use strict";var i=n("bd2d"),a=n("6b88"),r=n("6e45"),o=n("6e31"),s=n("d053"),c=n("7f1a"),u=n("8160"),f=n("cb56"),l=n("a78d"),d=n("8358"),h=n("97ad"),p=n("f5f7"),v=n("5b80"),b=n("1f3c"),y=n("e036"),g=n("86c2"),m=n("1368"),w=n("c997"),_=n("fe56"),S=n("37c6"),A=n("e48b"),C=n("fe33"),j=n("2847"),x=n("3361"),O=n("c647"),k=n("59bc"),R=n("c128"),D=n("53ba"),$=n("f309"),E=n("a1e5"),M=n("697d"),F=n("a53a"),P=n("c17f"),I=n("9207"),N=n("c368"),z=n("c4ec"),L=n("70a2").forEach,T=$("hidden"),J="Symbol",H="prototype",W=F("toPrimitive"),q=z.set,B=z.getterFor(J),Q=Object[H],U=a.Symbol,V=r("JSON","stringify"),Y=j.f,G=x.f,K=A.f,X=O.f,Z=D("symbols"),tt=D("op-symbols"),et=D("string-to-symbol-registry"),nt=D("symbol-to-string-registry"),it=D("wks"),at=a.QObject,rt=!at||!at[H]||!at[H].findChild,ot=s&&u((function(){return 7!=w(G({},"a",{get:function(){return G(this,"a",{value:7}).a}})).a}))?function(t,e,n){var i=Y(Q,e);i&&delete Q[e],G(t,e,n),i&&t!==Q&&G(Q,e,i)}:G,st=function(t,e){var n=Z[t]=w(U[H]);return q(n,{type:J,tag:t,description:e}),s||(n.description=e),n},ct=function(t,e,n){t===Q&&ct(tt,e,n),p(t);var i=y(e);return p(n),f(Z,i)?(n.enumerable?(f(t,T)&&t[T][i]&&(t[T][i]=!1),n=w(n,{enumerable:m(0,!1)})):(f(t,T)||G(t,T,m(1,{})),t[T][i]=!0),ot(t,i,n)):G(t,i,n)},ut=function(t,e){p(t);var n=b(e),i=_(n).concat(pt(n));return L(i,(function(e){s&&!lt.call(n,e)||ct(t,e,n[e])})),t},ft=function(t,e){return void 0===e?w(t):ut(w(t),e)},lt=function(t){var e=y(t),n=X.call(this,e);return!(this===Q&&f(Z,e)&&!f(tt,e))&&(!(n||!f(this,e)||!f(Z,e)||f(this,T)&&this[T][e])||n)},dt=function(t,e){var n=b(t),i=y(e);if(n!==Q||!f(Z,i)||f(tt,i)){var a=Y(n,i);return!a||!f(Z,i)||f(n,T)&&n[T][i]||(a.enumerable=!0),a}},ht=function(t){var e=K(b(t)),n=[];return L(e,(function(t){f(Z,t)||f(E,t)||n.push(t)})),n},pt=function(t){var e=t===Q,n=K(e?tt:b(t)),i=[];return L(n,(function(t){!f(Z,t)||e&&!f(Q,t)||i.push(Z[t])})),i};if(c||(U=function(){if(this instanceof U)throw TypeError("Symbol is not a constructor");var t=arguments.length&&void 0!==arguments[0]?g(arguments[0]):void 0,e=M(t),n=function(t){this===Q&&n.call(tt,t),f(this,T)&&f(this[T],e)&&(this[T][e]=!1),ot(this,e,m(1,t))};return s&&rt&&ot(Q,e,{configurable:!0,set:n}),st(e,t)},R(U[H],"toString",(function(){return B(this).tag})),R(U,"withoutSetter",(function(t){return st(M(t),t)})),O.f=lt,x.f=ct,j.f=dt,S.f=A.f=ht,C.f=pt,P.f=function(t){return st(F(t),t)},s&&(G(U[H],"description",{configurable:!0,get:function(){return B(this).description}}),o||R(Q,"propertyIsEnumerable",lt,{unsafe:!0}))),i({global:!0,wrap:!0,forced:!c,sham:!c},{Symbol:U}),L(_(it),(function(t){I(t)})),i({target:J,stat:!0,forced:!c},{for:function(t){var e=g(t);if(f(et,e))return et[e];var n=U(e);return et[e]=n,nt[n]=e,n},keyFor:function(t){if(!h(t))throw TypeError(t+" is not a symbol");if(f(nt,t))return nt[t]},useSetter:function(){rt=!0},useSimple:function(){rt=!1}}),i({target:"Object",stat:!0,forced:!c,sham:!s},{create:ft,defineProperty:ct,defineProperties:ut,getOwnPropertyDescriptor:dt}),i({target:"Object",stat:!0,forced:!c},{getOwnPropertyNames:ht,getOwnPropertySymbols:pt}),i({target:"Object",stat:!0,forced:u((function(){C.f(1)}))},{getOwnPropertySymbols:function(t){return C.f(v(t))}}),V){var vt=!c||u((function(){var t=U();return"[null]"!=V([t])||"{}"!=V({a:t})||"{}"!=V(Object(t))}));i({target:"JSON",stat:!0,forced:vt},{stringify:function(t,e,n){var i,a=[t],r=1;while(arguments.length>r)a.push(arguments[r++]);if(i=e,(d(e)||void 0!==t)&&!h(t))return l(e)||(e=function(t,e){if("function"==typeof i&&(e=i.call(this,t,e)),!h(e))return e}),a[1]=e,V.apply(null,a)}})}U[H][W]||k(U[H],W,U[H].valueOf),N(U,J),E[T]=!0},d7bf:function(t,e,n){var i=n("8160"),a=n("a53a"),r=n("9122"),o=a("species");t.exports=function(t){return r>=51||!i((function(){var e=[],n=e.constructor={};return n[o]=function(){return{foo:1}},1!==e[t](Boolean).foo}))}},e48b:function(t,e,n){var i=n("1f3c"),a=n("37c6").f,r={}.toString,o="object"==typeof window&&window&&Object.getOwnPropertyNames?Object.getOwnPropertyNames(window):[],s=function(t){try{return a(t)}catch(e){return o.slice()}};t.exports.f=function(t){return o&&"[object Window]"==r.call(t)?s(t):a(i(t))}},ecfb:function(t,e,n){"use strict";var i=n("bd2d"),a=n("8358"),r=n("a78d"),o=n("2775"),s=n("7f24"),c=n("1f3c"),u=n("4396"),f=n("a53a"),l=n("d7bf"),d=l("slice"),h=f("species"),p=[].slice,v=Math.max;i({target:"Array",proto:!0,forced:!d},{slice:function(t,e){var n,i,f,l=c(this),d=s(l.length),b=o(t,d),y=o(void 0===e?d:e,d);if(r(l)&&(n=l.constructor,"function"!=typeof n||n!==Array&&!r(n.prototype)?a(n)&&(n=n[h],null===n&&(n=void 0)):n=void 0,n===Array||void 0===n))return p.call(l,b,y);for(i=new(void 0===n?Array:n)(v(y-b,0)),f=0;b<y;b++,f++)b in l&&u(i,f,l[b]);return i.length=f,i}})},ed08:function(t,e,n){"use strict";n.d(e,"c",(function(){return a})),n.d(e,"a",(function(){return r})),n.d(e,"b",(function(){return o}));n("c958"),n("839d"),n("a73a"),n("2127"),n("3dbb"),n("dcba");function i(t){return i="function"===typeof Symbol&&"symbol"===typeof Symbol.iterator?function(t){return typeof t}:function(t){return t&&"function"===typeof Symbol&&t.constructor===Symbol&&t!==Symbol.prototype?"symbol":typeof t},i(t)}n("70ac"),n("6a4a"),n("a82d"),n("460f"),n("fd8f"),n("b7d4"),n("666d"),n("f5f5");function a(t,e){if(0===arguments.length)return null;var n,a=e||"{y}-{m}-{d} {h}:{i}:{s}";"object"===i(t)?n=t:("string"===typeof t&&/^[0-9]+$/.test(t)&&(t=parseInt(t)),"number"===typeof t&&10===t.toString().length&&(t*=1e3),n=new Date(t));var r={y:n.getFullYear(),m:n.getMonth()+1,d:n.getDate(),h:n.getHours(),i:n.getMinutes(),s:n.getSeconds(),a:n.getDay()},o=a.replace(/{([ymdhisa])+}/g,(function(t,e){var n=r[e];return"a"===e?["日","一","二","三","四","五","六"][n]:n.toString().padStart(2,"0")}));return o}function r(t,e,n){t=10===(""+t).length?1e3*parseInt(t):+t;var i=new Date(t),r=Date.now();if(n){var o=(r-i)/1e3;if(o<30)return"刚刚";if(o<3600)return Math.ceil(o/60)+"分钟前";if(o<86400)return Math.ceil(o/3600)+"小时前";if(o<172800)return"1天前"}return e?a(t,e):i.getMonth()+1+"月"+i.getDate()+"日"+i.getHours()+"时"+i.getMinutes()+"分"}function o(){var t=window.navigator.userAgent.toLowerCase();return-1!=t.indexOf("micromessenger")}}}]);