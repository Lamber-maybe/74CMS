(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["chunk-24e0a65a"],{"057f":function(t,e,n){var i=n("fc6a"),r=n("241c").f,a={}.toString,o="object"==typeof window&&window&&Object.getOwnPropertyNames?Object.getOwnPropertyNames(window):[],s=function(t){try{return r(t)}catch(e){return o.slice()}};t.exports.f=function(t){return o&&"[object Window]"==a.call(t)?s(t):r(i(t))}},"0ccb":function(t,e,n){var i=n("50c4"),r=n("1148"),a=n("1d80"),o=Math.ceil,s=function(t){return function(e,n,s){var c,u,f=String(a(e)),l=f.length,d=void 0===s?" ":String(s),h=i(n);return h<=l||""==d?f:(c=h-l,u=r.call(d,o(c/d.length)),u.length>c&&(u=u.slice(0,c)),t?f+u:u+f)}};t.exports={start:s(!1),end:s(!0)}},1148:function(t,e,n){"use strict";var i=n("a691"),r=n("1d80");t.exports=function(t){var e=String(r(this)),n="",a=i(t);if(a<0||a==1/0)throw RangeError("Wrong number of repetitions");for(;a>0;(a>>>=1)&&(e+=e))1&a&&(n+=e);return n}},"1a8a":function(t,e,n){"use strict";n("96fe")},"1dde":function(t,e,n){var i=n("d039"),r=n("b622"),a=n("2d00"),o=r("species");t.exports=function(t){return a>=51||!i((function(){var e=[],n=e.constructor={};return n[o]=function(){return{foo:1}},1!==e[t](Boolean).foo}))}},"25f0":function(t,e,n){"use strict";var i=n("6eeb"),r=n("825a"),a=n("d039"),o=n("ad6d"),s="toString",c=RegExp.prototype,u=c[s],f=a((function(){return"/a/b"!=u.call({source:"a",flags:"b"})})),l=u.name!=s;(f||l)&&i(RegExp.prototype,s,(function(){var t=r(this),e=String(t.source),n=t.flags,i=String(void 0===n&&t instanceof RegExp&&!("flags"in c)?o.call(t):n);return"/"+e+"/"+i}),{unsafe:!0})},2909:function(t,e,n){"use strict";function i(t,e){(null==e||e>t.length)&&(e=t.length);for(var n=0,i=new Array(e);n<e;n++)i[n]=t[n];return i}function r(t){if(Array.isArray(t))return i(t)}n.d(e,"a",(function(){return c}));n("a4d3"),n("e01a"),n("d3b7"),n("d28b"),n("3ca3"),n("ddb0"),n("a630");function a(t){if("undefined"!==typeof Symbol&&null!=t[Symbol.iterator]||null!=t["@@iterator"])return Array.from(t)}n("fb6a"),n("b0c0");function o(t,e){if(t){if("string"===typeof t)return i(t,e);var n=Object.prototype.toString.call(t).slice(8,-1);return"Object"===n&&t.constructor&&(n=t.constructor.name),"Map"===n||"Set"===n?Array.from(t):"Arguments"===n||/^(?:Ui|I)nt(?:8|16|32)(?:Clamped)?Array$/.test(n)?i(t,e):void 0}}function s(){throw new TypeError("Invalid attempt to spread non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method.")}function c(t){return r(t)||a(t)||o(t)||s()}},"4d90":function(t,e,n){"use strict";var i=n("23e7"),r=n("0ccb").start,a=n("9a0c");i({target:"String",proto:!0,forced:a},{padStart:function(t){return r(this,t,arguments.length>1?arguments[1]:void 0)}})},"4df4":function(t,e,n){"use strict";var i=n("0366"),r=n("7b0b"),a=n("9bdd"),o=n("e95a"),s=n("50c4"),c=n("8418"),u=n("35a1");t.exports=function(t){var e,n,f,l,d,h,p=r(t),v="function"==typeof this?this:Array,b=arguments.length,g=b>1?arguments[1]:void 0,y=void 0!==g,m=u(p),w=0;if(y&&(g=i(g,b>2?arguments[2]:void 0,2)),void 0==m||v==Array&&o(m))for(e=s(p.length),n=new v(e);e>w;w++)h=y?g(p[w],w):p[w],c(n,w,h);else for(l=m.call(p),d=l.next,n=new v;!(f=d.call(l)).done;w++)h=y?a(l,g,[f.value,w],!0):f.value,c(n,w,h);return n.length=w,n}},"746f":function(t,e,n){var i=n("428f"),r=n("5135"),a=n("e538"),o=n("9bf2").f;t.exports=function(t){var e=i.Symbol||(i.Symbol={});r(e,t)||o(e,t,{value:a.f(t)})}},8418:function(t,e,n){"use strict";var i=n("c04e"),r=n("9bf2"),a=n("5c6c");t.exports=function(t,e,n){var o=i(e);o in t?r.f(t,o,a(0,n)):t[o]=n}},"96fe":function(t,e,n){},"99af":function(t,e,n){"use strict";var i=n("23e7"),r=n("d039"),a=n("e8b5"),o=n("861d"),s=n("7b0b"),c=n("50c4"),u=n("8418"),f=n("65f0"),l=n("1dde"),d=n("b622"),h=n("2d00"),p=d("isConcatSpreadable"),v=9007199254740991,b="Maximum allowed index exceeded",g=h>=51||!r((function(){var t=[];return t[p]=!1,t.concat()[0]!==t})),y=l("concat"),m=function(t){if(!o(t))return!1;var e=t[p];return void 0!==e?!!e:a(t)},w=!g||!y;i({target:"Array",proto:!0,forced:w},{concat:function(t){var e,n,i,r,a,o=s(this),l=f(o,0),d=0;for(e=-1,i=arguments.length;e<i;e++)if(a=-1===e?o:arguments[e],m(a)){if(r=c(a.length),d+r>v)throw TypeError(b);for(n=0;n<r;n++,d++)n in a&&u(l,d,a[n])}else{if(d>=v)throw TypeError(b);u(l,d++,a)}return l.length=d,l}})},"9a0c":function(t,e,n){var i=n("342f");t.exports=/Version\/10(?:\.\d+){1,2}(?: [\w./]+)?(?: Mobile\/\w+)? Safari\//.test(i)},"9bdd":function(t,e,n){var i=n("825a"),r=n("2a62");t.exports=function(t,e,n,a){try{return a?e(i(n)[0],n[1]):e(n)}catch(o){throw r(t),o}}},a15b:function(t,e,n){"use strict";var i=n("23e7"),r=n("44ad"),a=n("fc6a"),o=n("a640"),s=[].join,c=r!=Object,u=o("join",",");i({target:"Array",proto:!0,forced:c||!u},{join:function(t){return s.call(a(this),void 0===t?",":t)}})},a4d3:function(t,e,n){"use strict";var i=n("23e7"),r=n("da84"),a=n("d066"),o=n("c430"),s=n("83ab"),c=n("4930"),u=n("fdbf"),f=n("d039"),l=n("5135"),d=n("e8b5"),h=n("861d"),p=n("825a"),v=n("7b0b"),b=n("fc6a"),g=n("c04e"),y=n("5c6c"),m=n("7c73"),w=n("df75"),_=n("241c"),S=n("057f"),A=n("7418"),x=n("06cf"),C=n("9bf2"),O=n("d1e7"),k=n("9112"),R=n("6eeb"),j=n("5692"),D=n("f772"),$=n("d012"),E=n("90e3"),M=n("b622"),F=n("e538"),P=n("746f"),I=n("d44e"),N=n("69f3"),z=n("b727").forEach,L=D("hidden"),T="Symbol",J="prototype",H=M("toPrimitive"),q=N.set,W=N.getterFor(T),B=Object[J],Q=r.Symbol,U=a("JSON","stringify"),V=x.f,Y=C.f,G=S.f,K=O.f,X=j("symbols"),Z=j("op-symbols"),tt=j("string-to-symbol-registry"),et=j("symbol-to-string-registry"),nt=j("wks"),it=r.QObject,rt=!it||!it[J]||!it[J].findChild,at=s&&f((function(){return 7!=m(Y({},"a",{get:function(){return Y(this,"a",{value:7}).a}})).a}))?function(t,e,n){var i=V(B,e);i&&delete B[e],Y(t,e,n),i&&t!==B&&Y(B,e,i)}:Y,ot=function(t,e){var n=X[t]=m(Q[J]);return q(n,{type:T,tag:t,description:e}),s||(n.description=e),n},st=u?function(t){return"symbol"==typeof t}:function(t){return Object(t)instanceof Q},ct=function(t,e,n){t===B&&ct(Z,e,n),p(t);var i=g(e,!0);return p(n),l(X,i)?(n.enumerable?(l(t,L)&&t[L][i]&&(t[L][i]=!1),n=m(n,{enumerable:y(0,!1)})):(l(t,L)||Y(t,L,y(1,{})),t[L][i]=!0),at(t,i,n)):Y(t,i,n)},ut=function(t,e){p(t);var n=b(e),i=w(n).concat(pt(n));return z(i,(function(e){s&&!lt.call(n,e)||ct(t,e,n[e])})),t},ft=function(t,e){return void 0===e?m(t):ut(m(t),e)},lt=function(t){var e=g(t,!0),n=K.call(this,e);return!(this===B&&l(X,e)&&!l(Z,e))&&(!(n||!l(this,e)||!l(X,e)||l(this,L)&&this[L][e])||n)},dt=function(t,e){var n=b(t),i=g(e,!0);if(n!==B||!l(X,i)||l(Z,i)){var r=V(n,i);return!r||!l(X,i)||l(n,L)&&n[L][i]||(r.enumerable=!0),r}},ht=function(t){var e=G(b(t)),n=[];return z(e,(function(t){l(X,t)||l($,t)||n.push(t)})),n},pt=function(t){var e=t===B,n=G(e?Z:b(t)),i=[];return z(n,(function(t){!l(X,t)||e&&!l(B,t)||i.push(X[t])})),i};if(c||(Q=function(){if(this instanceof Q)throw TypeError("Symbol is not a constructor");var t=arguments.length&&void 0!==arguments[0]?String(arguments[0]):void 0,e=E(t),n=function(t){this===B&&n.call(Z,t),l(this,L)&&l(this[L],e)&&(this[L][e]=!1),at(this,e,y(1,t))};return s&&rt&&at(B,e,{configurable:!0,set:n}),ot(e,t)},R(Q[J],"toString",(function(){return W(this).tag})),R(Q,"withoutSetter",(function(t){return ot(E(t),t)})),O.f=lt,C.f=ct,x.f=dt,_.f=S.f=ht,A.f=pt,F.f=function(t){return ot(M(t),t)},s&&(Y(Q[J],"description",{configurable:!0,get:function(){return W(this).description}}),o||R(B,"propertyIsEnumerable",lt,{unsafe:!0}))),i({global:!0,wrap:!0,forced:!c,sham:!c},{Symbol:Q}),z(w(nt),(function(t){P(t)})),i({target:T,stat:!0,forced:!c},{for:function(t){var e=String(t);if(l(tt,e))return tt[e];var n=Q(e);return tt[e]=n,et[n]=e,n},keyFor:function(t){if(!st(t))throw TypeError(t+" is not a symbol");if(l(et,t))return et[t]},useSetter:function(){rt=!0},useSimple:function(){rt=!1}}),i({target:"Object",stat:!0,forced:!c,sham:!s},{create:ft,defineProperty:ct,defineProperties:ut,getOwnPropertyDescriptor:dt}),i({target:"Object",stat:!0,forced:!c},{getOwnPropertyNames:ht,getOwnPropertySymbols:pt}),i({target:"Object",stat:!0,forced:f((function(){A.f(1)}))},{getOwnPropertySymbols:function(t){return A.f(v(t))}}),U){var vt=!c||f((function(){var t=Q();return"[null]"!=U([t])||"{}"!=U({a:t})||"{}"!=U(Object(t))}));i({target:"JSON",stat:!0,forced:vt},{stringify:function(t,e,n){var i,r=[t],a=1;while(arguments.length>a)r.push(arguments[a++]);if(i=e,(h(e)||void 0!==t)&&!st(t))return d(e)||(e=function(t,e){if("function"==typeof i&&(e=i.call(this,t,e)),!st(e))return e}),r[1]=e,U.apply(null,r)}})}Q[J][H]||k(Q[J],H,Q[J].valueOf),I(Q,T),$[L]=!0},a630:function(t,e,n){var i=n("23e7"),r=n("4df4"),a=n("1c7e"),o=!a((function(t){Array.from(t)}));i({target:"Array",stat:!0,forced:o},{from:r})},d28b:function(t,e,n){var i=n("746f");i("iterator")},e01a:function(t,e,n){"use strict";var i=n("23e7"),r=n("83ab"),a=n("da84"),o=n("5135"),s=n("861d"),c=n("9bf2").f,u=n("e893"),f=a.Symbol;if(r&&"function"==typeof f&&(!("description"in f.prototype)||void 0!==f().description)){var l={},d=function(){var t=arguments.length<1||void 0===arguments[0]?void 0:String(arguments[0]),e=this instanceof d?new f(t):void 0===t?f():f(t);return""===t&&(l[e]=!0),e};u(d,f);var h=d.prototype=f.prototype;h.constructor=d;var p=h.toString,v="Symbol(test)"==String(f("test")),b=/^Symbol\((.*)\)[^)]+$/;c(h,"description",{configurable:!0,get:function(){var t=s(this)?this.valueOf():this,e=p.call(t);if(o(l,t))return"";var n=v?e.slice(7,-1):e.replace(b,"$1");return""===n?void 0:n}}),i({global:!0,forced:!0},{Symbol:d})}},e538:function(t,e,n){var i=n("b622");e.f=i},ed08:function(t,e,n){"use strict";n.d(e,"c",(function(){return r})),n.d(e,"a",(function(){return a})),n.d(e,"b",(function(){return o}));n("a4d3"),n("e01a"),n("d3b7"),n("d28b"),n("3ca3"),n("ddb0");function i(t){return i="function"===typeof Symbol&&"symbol"===typeof Symbol.iterator?function(t){return typeof t}:function(t){return t&&"function"===typeof Symbol&&t.constructor===Symbol&&t!==Symbol.prototype?"symbol":typeof t},i(t)}n("25f0"),n("ac1f"),n("5319"),n("4d90"),n("1276"),n("a15b"),n("caad"),n("159b");function r(t,e){if(0===arguments.length)return null;var n,r=e||"{y}-{m}-{d} {h}:{i}:{s}";"object"===i(t)?n=t:("string"===typeof t&&/^[0-9]+$/.test(t)&&(t=parseInt(t)),"number"===typeof t&&10===t.toString().length&&(t*=1e3),n=new Date(t));var a={y:n.getFullYear(),m:n.getMonth()+1,d:n.getDate(),h:n.getHours(),i:n.getMinutes(),s:n.getSeconds(),a:n.getDay()},o=r.replace(/{([ymdhisa])+}/g,(function(t,e){var n=a[e];return"a"===e?["日","一","二","三","四","五","六"][n]:n.toString().padStart(2,"0")}));return o}function a(t,e,n){t=10===(""+t).length?1e3*parseInt(t):+t;var i=new Date(t),a=Date.now();if(n){var o=(a-i)/1e3;if(o<30)return"刚刚";if(o<3600)return Math.ceil(o/60)+"分钟前";if(o<86400)return Math.ceil(o/3600)+"小时前";if(o<172800)return"1天前"}return e?r(t,e):i.getMonth()+1+"月"+i.getDate()+"日"+i.getHours()+"时"+i.getMinutes()+"分"}function o(){var t=window.navigator.userAgent.toLowerCase();return-1!=t.indexOf("micromessenger")}},edee:function(t,e,n){"use strict";n.r(e);var i=function(){var t=this,e=t.$createElement,n=t._self._c||e;return n("div",[n("Head",[t._v(t._s(t.title))]),n("Search",{attrs:{placeholder:"请输入姓名/简历ID/手机号"},on:{doSearch:t.doSearch}}),n("van-overlay",{attrs:{"z-index":"10",show:!1===t.fetchDataFinish}},[n("van-loading",{staticClass:"loading",attrs:{color:"#1989fa"}},[t._v("数据加载中...")])],1),!0===t.show_empty?n("van-empty",{staticStyle:{"background-color":"#fff"},attrs:{image:"search",description:"没有找到对应的数据"}}):t._e(),t.dataset.length>0?n("van-list",{attrs:{finished:t.finished,"finished-text":t.finished_text,"immediate-check":!0},on:{load:t.onLoad},model:{value:t.loading,callback:function(e){t.loading=e},expression:"loading"}},[n("div",{staticClass:"b1"},t._l(t.dataset,(function(e,i){return n("div",{key:i,staticClass:"b_item",on:{click:function(n){return t.jumpShow(e.link)}}},[n("img",{staticClass:"ava",attrs:{src:e.photo_img_src}}),n("p",{staticClass:"t1 substring"},[t._v(t._s(e.fullname))]),n("van-icon",{staticClass:"arrow",attrs:{name:"arrow",color:"#999999"}}),n("p",{staticClass:"t2"},[t._v(t._s(e.age)+"/"+t._s(e.sex_cn)+"/"+t._s(e.education_cn)+"/"+t._s(e.experience_cn))]),n("div",{staticClass:"i_tag",class:t._f("auditFilter")(e.audit)},[t._v(t._s(t.options_audit[e.audit]))]),n("div",{staticClass:"t_line"},[n("div",{staticClass:"tl_cell"},[n("span",{staticClass:"l_title"},[t._v("等级：")]),n("span",{staticClass:"l_con"},[t._v(t._s(1==e.high_quality?"优质":"普通"))])]),n("div",{staticClass:"tl_cell"},[n("span",{staticClass:"l_title"},[t._v("完整度：")]),n("span",{staticClass:"l_con"},[t._v(t._s(e.complete_percent)+"%")])])]),n("div",{staticClass:"t_line last"},[n("div",{staticClass:"tl_cell"},[n("span",{staticClass:"l_title"},[t._v("手机号：")]),n("span",{staticClass:"l_con"},[t._v(t._s(e.mobile))])]),n("div",{staticClass:"tl_cell"},[n("span",{staticClass:"l_title"},[t._v("刷新：")]),n("span",{staticClass:"l_con"},[t._v(t._s(t._f("timeFilter")(e.refreshtime)))])])]),n("div",{staticClass:"i_btn b1 blue",on:{click:function(n){return n.stopPropagation(),t.funAudit(e)}}},[t._v("审核")]),n("div",{staticClass:"i_btn b2"},[t._v("查看")])],1)})),0)]):t._e(),n("van-action-sheet",{attrs:{actions:t.auditActions,"cancel-text":"取消","close-on-click-action":""},on:{cancel:function(e){t.showAudit=!1},select:t.doAudit},model:{value:t.showAudit,callback:function(e){t.showAudit=e},expression:"showAudit"}}),n("van-dialog",{attrs:{title:"审核不通过原因","show-confirm-button":!1,"show-cancel-button":!1,closeOnClickOverlay:!0},model:{value:t.showReason,callback:function(e){t.showReason=e},expression:"showReason"}},[n("van-form",{on:{submit:t.onSubmitReason}},[n("van-field",{attrs:{rows:"3",autosize:"",label:"",type:"textarea",placeholder:"请填写审核不通过原因",rules:[{required:!0,message:""}]},model:{value:t.auditReason,callback:function(e){t.auditReason=e},expression:"auditReason"}}),n("div",{staticStyle:{margin:"16px"}},[n("van-button",{attrs:{round:"",block:"",type:"info","native-type":"submit"}},[t._v("提交")])],1)],1)],1)],1)},r=[],a=n("2909"),o=(n("159b"),n("b0c0"),n("99af"),n("751a")),s=n("d722"),c=n("ed08"),u={name:"resumeList",filters:{timeFilter:function(t){return Object(c["a"])(t,"{y}-{m}-{d} {h}:{i}")},auditFilter:function(t){switch(t){case 0:return"wait";case 1:return"passed";case 2:return"failed";default:return"not"}}},data:function(){return{fetchDataFinish:!1,showReason:!1,auditResult:1,auditReason:"",auditRow:{},auditActions:[],showAudit:!1,dataset:[],loading:!1,finished:!1,finished_text:"",show_empty:!1,keyword:"",page:1,pagesize:15,regularMobile:/^13[0-9]{9}$|14[0-9]{9}$|15[0-9]{9}$|18[0-9]{9}$|17[0-9]{9}$|16[0-9]{9}$|19[0-9]{9}$/,regularNumber:/^\d+$/,title:"",type:1,options_audit:[]}},created:function(){this.type=parseInt(this.$route.params.type),this.title=1===this.type?"简历管理":"待审核简历",this.fetchClassify()},methods:{fetchClassify:function(){var t=this;o["a"].get(s["a"].classify,{type:"resumeAudit"}).then((function(e){t.auditActions=e.data,t.auditActions.forEach((function(e){t.options_audit[e.id]=e.name})),t.fetchData(!0)})).catch((function(){}))},buildCondition:function(){var t={keyword:this.keyword};return t.page=this.page,t.pagesize=this.pagesize,t.list_type=1==this.type?"":"noaudit",this.regularMobile.test(t.keyword)?t.key_type=4:this.regularNumber.test(t.keyword)?t.key_type=2:t.key_type=1,t},fetchData:function(t){var e=this;this.show_empty=!1,!0===t&&(this.page=1,this.finished_text="",this.finished=!1);var n=this.buildCondition();o["a"].get(s["a"].resumeList,n).then((function(n){e.dataset=!0===t?Object(a["a"])(n.data.items):e.dataset.concat(n.data.items),e.loading=!1,e.fetchDataFinish=!0,n.data.items.length<e.pagesize&&(e.finished=!0,!1===t?e.finished_text="没有更多了":0===n.data.items.length&&(e.show_empty=!0))})).catch((function(){}))},onLoad:function(){this.page++,this.fetchData(!1)},doSearch:function(t){this.fetchDataFinish=!1,this.keyword=t,this.fetchData(!0)},funAudit:function(t){this.auditRow=t,this.showAudit=!0},doAudit:function(t){this.auditResult=t.id,2==t.id?(this.showAudit=!1,this.showReason=!0):this.onSubmitReason()},onSubmitReason:function(){var t=this;o["a"].post(s["a"].resumeAudit,{id:[this.auditRow.id],audit:this.auditResult,reason:this.auditReason}).then((function(e){t.$toast.success(e.message),t.fetchData(!0),t.showAudit=!1,t.showReason=!1,t.auditRow={},t.auditReason=""})).catch((function(){}))},jumpShow:function(t){location.href=t}}},f=u,l=(n("1a8a"),n("2877")),d=Object(l["a"])(f,i,r,!1,null,"2b63e27b",null);e["default"]=d.exports},fb6a:function(t,e,n){"use strict";var i=n("23e7"),r=n("861d"),a=n("e8b5"),o=n("23cb"),s=n("50c4"),c=n("fc6a"),u=n("8418"),f=n("b622"),l=n("1dde"),d=l("slice"),h=f("species"),p=[].slice,v=Math.max;i({target:"Array",proto:!0,forced:!d},{slice:function(t,e){var n,i,f,l=c(this),d=s(l.length),b=o(t,d),g=o(void 0===e?d:e,d);if(a(l)&&(n=l.constructor,"function"!=typeof n||n!==Array&&!a(n.prototype)?r(n)&&(n=n[h],null===n&&(n=void 0)):n=void 0,n===Array||void 0===n))return p.call(l,b,g);for(i=new(void 0===n?Array:n)(v(g-b,0)),f=0;b<g;b++,f++)b in l&&u(i,f,l[b]);return i.length=f,i}})}}]);