(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["chunk-68c1387c"],{"057f":function(t,e,n){var r=n("fc6a"),i=n("241c").f,o={}.toString,a="object"==typeof window&&window&&Object.getOwnPropertyNames?Object.getOwnPropertyNames(window):[],s=function(t){try{return i(t)}catch(e){return a.slice()}};t.exports.f=function(t){return a&&"[object Window]"==o.call(t)?s(t):i(r(t))}},"0ccb":function(t,e,n){var r=n("50c4"),i=n("1148"),o=n("1d80"),a=Math.ceil,s=function(t){return function(e,n,s){var c,u,f=String(o(e)),l=f.length,d=void 0===s?" ":String(s),h=r(n);return h<=l||""==d?f:(c=h-l,u=i.call(d,a(c/d.length)),u.length>c&&(u=u.slice(0,c)),t?f+u:u+f)}};t.exports={start:s(!1),end:s(!0)}},1148:function(t,e,n){"use strict";var r=n("a691"),i=n("1d80");t.exports=function(t){var e=String(i(this)),n="",o=r(t);if(o<0||o==1/0)throw RangeError("Wrong number of repetitions");for(;o>0;(o>>>=1)&&(e+=e))1&o&&(n+=e);return n}},"1dde":function(t,e,n){var r=n("d039"),i=n("b622"),o=n("2d00"),a=i("species");t.exports=function(t){return o>=51||!r((function(){var e=[],n=e.constructor={};return n[a]=function(){return{foo:1}},1!==e[t](Boolean).foo}))}},"25f0":function(t,e,n){"use strict";var r=n("6eeb"),i=n("825a"),o=n("d039"),a=n("ad6d"),s="toString",c=RegExp.prototype,u=c[s],f=o((function(){return"/a/b"!=u.call({source:"a",flags:"b"})})),l=u.name!=s;(f||l)&&r(RegExp.prototype,s,(function(){var t=i(this),e=String(t.source),n=t.flags,r=String(void 0===n&&t instanceof RegExp&&!("flags"in c)?a.call(t):n);return"/"+e+"/"+r}),{unsafe:!0})},2909:function(t,e,n){"use strict";function r(t,e){(null==e||e>t.length)&&(e=t.length);for(var n=0,r=new Array(e);n<e;n++)r[n]=t[n];return r}function i(t){if(Array.isArray(t))return r(t)}n.d(e,"a",(function(){return c}));n("a4d3"),n("e01a"),n("d3b7"),n("d28b"),n("3ca3"),n("ddb0"),n("a630");function o(t){if("undefined"!==typeof Symbol&&null!=t[Symbol.iterator]||null!=t["@@iterator"])return Array.from(t)}n("fb6a"),n("b0c0");function a(t,e){if(t){if("string"===typeof t)return r(t,e);var n=Object.prototype.toString.call(t).slice(8,-1);return"Object"===n&&t.constructor&&(n=t.constructor.name),"Map"===n||"Set"===n?Array.from(t):"Arguments"===n||/^(?:Ui|I)nt(?:8|16|32)(?:Clamped)?Array$/.test(n)?r(t,e):void 0}}function s(){throw new TypeError("Invalid attempt to spread non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method.")}function c(t){return i(t)||o(t)||a(t)||s()}},"4d90":function(t,e,n){"use strict";var r=n("23e7"),i=n("0ccb").start,o=n("9a0c");r({target:"String",proto:!0,forced:o},{padStart:function(t){return i(this,t,arguments.length>1?arguments[1]:void 0)}})},"4df4":function(t,e,n){"use strict";var r=n("0366"),i=n("7b0b"),o=n("9bdd"),a=n("e95a"),s=n("50c4"),c=n("8418"),u=n("35a1");t.exports=function(t){var e,n,f,l,d,h,p=i(t),v="function"==typeof this?this:Array,b=arguments.length,y=b>1?arguments[1]:void 0,g=void 0!==y,m=u(p),_=0;if(g&&(y=r(y,b>2?arguments[2]:void 0,2)),void 0==m||v==Array&&a(m))for(e=s(p.length),n=new v(e);e>_;_++)h=g?y(p[_],_):p[_],c(n,_,h);else for(l=m.call(p),d=l.next,n=new v;!(f=d.call(l)).done;_++)h=g?o(l,y,[f.value,_],!0):f.value,c(n,_,h);return n.length=_,n}},"746f":function(t,e,n){var r=n("428f"),i=n("5135"),o=n("e538"),a=n("9bf2").f;t.exports=function(t){var e=r.Symbol||(r.Symbol={});i(e,t)||a(e,t,{value:o.f(t)})}},"7ab8":function(t,e,n){},8418:function(t,e,n){"use strict";var r=n("c04e"),i=n("9bf2"),o=n("5c6c");t.exports=function(t,e,n){var a=r(e);a in t?i.f(t,a,o(0,n)):t[a]=n}},"99af":function(t,e,n){"use strict";var r=n("23e7"),i=n("d039"),o=n("e8b5"),a=n("861d"),s=n("7b0b"),c=n("50c4"),u=n("8418"),f=n("65f0"),l=n("1dde"),d=n("b622"),h=n("2d00"),p=d("isConcatSpreadable"),v=9007199254740991,b="Maximum allowed index exceeded",y=h>=51||!i((function(){var t=[];return t[p]=!1,t.concat()[0]!==t})),g=l("concat"),m=function(t){if(!a(t))return!1;var e=t[p];return void 0!==e?!!e:o(t)},_=!y||!g;r({target:"Array",proto:!0,forced:_},{concat:function(t){var e,n,r,i,o,a=s(this),l=f(a,0),d=0;for(e=-1,r=arguments.length;e<r;e++)if(o=-1===e?a:arguments[e],m(o)){if(i=c(o.length),d+i>v)throw TypeError(b);for(n=0;n<i;n++,d++)n in o&&u(l,d,o[n])}else{if(d>=v)throw TypeError(b);u(l,d++,o)}return l.length=d,l}})},"9a0c":function(t,e,n){var r=n("342f");t.exports=/Version\/10(?:\.\d+){1,2}(?: [\w./]+)?(?: Mobile\/\w+)? Safari\//.test(r)},"9bdd":function(t,e,n){var r=n("825a"),i=n("2a62");t.exports=function(t,e,n,o){try{return o?e(r(n)[0],n[1]):e(n)}catch(a){throw i(t),a}}},a15b:function(t,e,n){"use strict";var r=n("23e7"),i=n("44ad"),o=n("fc6a"),a=n("a640"),s=[].join,c=i!=Object,u=a("join",",");r({target:"Array",proto:!0,forced:c||!u},{join:function(t){return s.call(o(this),void 0===t?",":t)}})},a4d3:function(t,e,n){"use strict";var r=n("23e7"),i=n("da84"),o=n("d066"),a=n("c430"),s=n("83ab"),c=n("4930"),u=n("fdbf"),f=n("d039"),l=n("5135"),d=n("e8b5"),h=n("861d"),p=n("825a"),v=n("7b0b"),b=n("fc6a"),y=n("c04e"),g=n("5c6c"),m=n("7c73"),_=n("df75"),w=n("241c"),S=n("057f"),C=n("7418"),x=n("06cf"),O=n("9bf2"),k=n("d1e7"),j=n("9112"),A=n("6eeb"),N=n("5692"),D=n("f772"),$=n("d012"),E=n("90e3"),M=n("b622"),P=n("e538"),F=n("746f"),R=n("d44e"),I=n("69f3"),z=n("b727").forEach,L=D("hidden"),T="Symbol",J="prototype",H=M("toPrimitive"),U=I.set,W=I.getterFor(T),q=Object[J],B=i.Symbol,Q=o("JSON","stringify"),V=x.f,Y=O.f,G=S.f,K=k.f,X=N("symbols"),Z=N("op-symbols"),tt=N("string-to-symbol-registry"),et=N("symbol-to-string-registry"),nt=N("wks"),rt=i.QObject,it=!rt||!rt[J]||!rt[J].findChild,ot=s&&f((function(){return 7!=m(Y({},"a",{get:function(){return Y(this,"a",{value:7}).a}})).a}))?function(t,e,n){var r=V(q,e);r&&delete q[e],Y(t,e,n),r&&t!==q&&Y(q,e,r)}:Y,at=function(t,e){var n=X[t]=m(B[J]);return U(n,{type:T,tag:t,description:e}),s||(n.description=e),n},st=u?function(t){return"symbol"==typeof t}:function(t){return Object(t)instanceof B},ct=function(t,e,n){t===q&&ct(Z,e,n),p(t);var r=y(e,!0);return p(n),l(X,r)?(n.enumerable?(l(t,L)&&t[L][r]&&(t[L][r]=!1),n=m(n,{enumerable:g(0,!1)})):(l(t,L)||Y(t,L,g(1,{})),t[L][r]=!0),ot(t,r,n)):Y(t,r,n)},ut=function(t,e){p(t);var n=b(e),r=_(n).concat(pt(n));return z(r,(function(e){s&&!lt.call(n,e)||ct(t,e,n[e])})),t},ft=function(t,e){return void 0===e?m(t):ut(m(t),e)},lt=function(t){var e=y(t,!0),n=K.call(this,e);return!(this===q&&l(X,e)&&!l(Z,e))&&(!(n||!l(this,e)||!l(X,e)||l(this,L)&&this[L][e])||n)},dt=function(t,e){var n=b(t),r=y(e,!0);if(n!==q||!l(X,r)||l(Z,r)){var i=V(n,r);return!i||!l(X,r)||l(n,L)&&n[L][r]||(i.enumerable=!0),i}},ht=function(t){var e=G(b(t)),n=[];return z(e,(function(t){l(X,t)||l($,t)||n.push(t)})),n},pt=function(t){var e=t===q,n=G(e?Z:b(t)),r=[];return z(n,(function(t){!l(X,t)||e&&!l(q,t)||r.push(X[t])})),r};if(c||(B=function(){if(this instanceof B)throw TypeError("Symbol is not a constructor");var t=arguments.length&&void 0!==arguments[0]?String(arguments[0]):void 0,e=E(t),n=function(t){this===q&&n.call(Z,t),l(this,L)&&l(this[L],e)&&(this[L][e]=!1),ot(this,e,g(1,t))};return s&&it&&ot(q,e,{configurable:!0,set:n}),at(e,t)},A(B[J],"toString",(function(){return W(this).tag})),A(B,"withoutSetter",(function(t){return at(E(t),t)})),k.f=lt,O.f=ct,x.f=dt,w.f=S.f=ht,C.f=pt,P.f=function(t){return at(M(t),t)},s&&(Y(B[J],"description",{configurable:!0,get:function(){return W(this).description}}),a||A(q,"propertyIsEnumerable",lt,{unsafe:!0}))),r({global:!0,wrap:!0,forced:!c,sham:!c},{Symbol:B}),z(_(nt),(function(t){F(t)})),r({target:T,stat:!0,forced:!c},{for:function(t){var e=String(t);if(l(tt,e))return tt[e];var n=B(e);return tt[e]=n,et[n]=e,n},keyFor:function(t){if(!st(t))throw TypeError(t+" is not a symbol");if(l(et,t))return et[t]},useSetter:function(){it=!0},useSimple:function(){it=!1}}),r({target:"Object",stat:!0,forced:!c,sham:!s},{create:ft,defineProperty:ct,defineProperties:ut,getOwnPropertyDescriptor:dt}),r({target:"Object",stat:!0,forced:!c},{getOwnPropertyNames:ht,getOwnPropertySymbols:pt}),r({target:"Object",stat:!0,forced:f((function(){C.f(1)}))},{getOwnPropertySymbols:function(t){return C.f(v(t))}}),Q){var vt=!c||f((function(){var t=B();return"[null]"!=Q([t])||"{}"!=Q({a:t})||"{}"!=Q(Object(t))}));r({target:"JSON",stat:!0,forced:vt},{stringify:function(t,e,n){var r,i=[t],o=1;while(arguments.length>o)i.push(arguments[o++]);if(r=e,(h(e)||void 0!==t)&&!st(t))return d(e)||(e=function(t,e){if("function"==typeof r&&(e=r.call(this,t,e)),!st(e))return e}),i[1]=e,Q.apply(null,i)}})}B[J][H]||j(B[J],H,B[J].valueOf),R(B,T),$[L]=!0},a630:function(t,e,n){var r=n("23e7"),i=n("4df4"),o=n("1c7e"),a=!o((function(t){Array.from(t)}));r({target:"Array",stat:!0,forced:a},{from:i})},ce93:function(t,e,n){"use strict";n.r(e);var r=function(){var t=this,e=t.$createElement,n=t._self._c||e;return n("div",[n("Head",[t._v("订单管理")]),n("Search",{attrs:{placeholder:"请输入UID/手机号查询"},on:{doSearch:t.doSearch}}),n("van-overlay",{attrs:{"z-index":"10",show:!1===t.fetchDataFinish}},[n("van-loading",{staticClass:"loading",attrs:{color:"#1989fa"}},[t._v("数据加载中...")])],1),!0===t.show_empty?n("van-empty",{staticStyle:{"background-color":"#fff"},attrs:{image:"search",description:"没有找到对应的数据"}}):t._e(),t.dataset.length>0?n("van-list",{attrs:{finished:t.finished,"finished-text":t.finished_text,"immediate-check":!0},on:{load:t.onLoad},model:{value:t.loading,callback:function(e){t.loading=e},expression:"loading"}},[n("div",{staticClass:"b1"},t._l(t.dataset,(function(e,r){return n("div",{key:r,staticClass:"b_item",on:{click:function(n){return t.jumpShow(e)}}},[n("p",{staticClass:"t1 substring"},[t._v(t._s(1==t.utype?e.companyname:e.fullname))]),n("van-icon",{staticClass:"arrow",attrs:{name:"arrow",color:"#999999"}}),n("div",{staticClass:"i_tag",class:t._f("statusFilter")(e.status)},[t._v(t._s(t.options_status[e.status]))]),n("div",{staticClass:"t_line"},[n("div",{staticClass:"tl_cell substring"},[n("span",{staticClass:"l_title"},[t._v("订单号：")]),n("span",{staticClass:"l_con mr"},[t._v(t._s(e.oid))])])]),n("div",{staticClass:"t_line"},[n("div",{staticClass:"tl_cell substring"},[n("span",{staticClass:"l_title"},[t._v("服务内容：")]),n("span",{staticClass:"l_con"},[t._v(t._s(e.service_name))])])]),n("div",{staticClass:"t_line"},[n("div",{staticClass:"tl_cell"},[n("span",{staticClass:"l_title"},[t._v("实付金额：")]),n("span",{staticClass:"l_con"},[t._v("￥"+t._s(e.amount))])]),n("div",{staticClass:"tl_cell"},[n("span",{staticClass:"l_title"},[t._v("下单时间：")]),n("span",{staticClass:"l_con"},[t._v(t._s(t._f("timeFilter")(e.addtime)))])])]),n("div",{staticClass:"t_line last"},[n("div",{staticClass:"tl_cell"},[n("span",{staticClass:"l_title"},[t._v("支付方式：")]),""==e.payment?n("span",{staticClass:"l_con"},[t._v("-")]):n("span",{staticClass:"l_con"},[t._v(t._s(t.options_payment[e.payment]))])]),n("div",{staticClass:"tl_cell"},[n("span",{staticClass:"l_title"},[t._v("支付时间：")]),e.paytime>0?n("span",{staticClass:"l_con"},[t._v(t._s(t._f("timeFilter")(e.paytime)))]):n("span",{staticClass:"l_con"},[t._v("-")])])]),0==e.status?n("div",{staticClass:"i_btn b1 blue",on:{click:function(n){return n.stopPropagation(),t.funSet(e)}}},[t._v("设置")]):n("div",{staticClass:"i_btn b1 grey",on:{click:function(t){return t.stopPropagation(),!1}}},[t._v("设置")]),n("div",{staticClass:"i_btn b2"},[t._v("查看")])],1)})),0)]):t._e(),n("van-action-sheet",{attrs:{actions:t.setActions,"cancel-text":"取消","close-on-click-action":""},on:{cancel:function(e){t.showSet=!1},select:t.doSet},model:{value:t.showSet,callback:function(e){t.showSet=e},expression:"showSet"}}),n("van-dialog",{attrs:{title:"备注","show-confirm-button":!1,"show-cancel-button":!1,closeOnClickOverlay:!0},model:{value:t.showNote,callback:function(e){t.showNote=e},expression:"showNote"}},[n("van-form",{on:{submit:t.onSubmitNote}},[n("van-field",{attrs:{rows:"3",autosize:"",label:"",type:"textarea",placeholder:"请填写备注",rules:[{required:!0,message:""}]},model:{value:t.setNote,callback:function(e){t.setNote=e},expression:"setNote"}}),n("div",{staticStyle:{margin:"16px"}},[n("van-button",{attrs:{round:"",block:"",type:"info","native-type":"submit"}},[t._v("提交")])],1)],1)],1)],1)},i=[],o=n("2909"),a=(n("159b"),n("b0c0"),n("99af"),n("751a")),s=n("d722"),c=n("ed08"),u={name:"orderList",filters:{timeFilter:function(t){return Object(c["a"])(t,"{y}-{m}-{d} {h}:{i}")},statusFilter:function(t){switch(t){case 0:return"wait";case 1:return"passed";default:return"not"}}},data:function(){return{fetchDataFinish:!1,setNote:"",showNote:!1,setRow:{},showSet:!1,setActions:[{name:"确认收款",value:1},{name:"关闭订单",value:0}],options_status:[],options_payment:[],dataset:[],loading:!1,finished:!1,finished_text:"",show_empty:!1,keyword:"",page:1,pagesize:15,regularMobile:/^13[0-9]{9}$|14[0-9]{9}$|15[0-9]{9}$|18[0-9]{9}$|17[0-9]{9}$|16[0-9]{9}$|19[0-9]{9}$/,regularNumber:/^\d+$/,utype:1}},created:function(){this.utype=parseInt(this.$route.params.utype),this.fetchClassify()},methods:{fetchClassify:function(){var t=this;a["a"].get(s["a"].classify,{type:"orderStatus,orderPayment"}).then((function(e){e.data.orderStatus.forEach((function(e){t.options_status[e.id]=e.name})),e.data.orderPayment.forEach((function(e){t.options_payment[e.id]=e.name})),t.fetchData(!0)})).catch((function(){}))},buildCondition:function(){var t={keyword:this.keyword};return t.page=this.page,t.pagesize=this.pagesize,t.utype=this.utype,this.regularMobile.test(t.keyword)?t.key_type=4:t.key_type=3,t},fetchData:function(t){var e=this;this.show_empty=!1,!0===t&&(this.page=1,this.finished_text="",this.finished=!1);var n=this.buildCondition();a["a"].get(s["a"].orderList,n).then((function(n){e.dataset=!0===t?Object(o["a"])(n.data.items):e.dataset.concat(n.data.items),e.loading=!1,e.fetchDataFinish=!0,n.data.items.length<e.pagesize&&(e.finished=!0,!1===t?e.finished_text="没有更多了":0===n.data.items.length&&(e.show_empty=!0))})).catch((function(){}))},onLoad:function(){this.page++,this.fetchData(!1)},doSearch:function(t){this.fetchDataFinish=!1,this.keyword=t,this.fetchData(!0)},jumpShow:function(t){this.$router.push("/order/detail/".concat(t.id,"?name=").concat(1==this.utype?t.companyname:t.fullname))},funSet:function(t){this.setRow=t,this.showSet=!0},doSet:function(t){var e=this;0==t.value?this.$dialog.confirm({title:"系统提示",message:"确定关闭该订单吗?"}).then((function(){a["a"].post(s["a"].orderCancel,{id:e.setRow.id}).then((function(t){e.$toast.success(t.message),e.fetchData(!0)})).catch((function(){}))})).catch((function(){})):(this.showSet=!1,this.showNote=!0)},onSubmitNote:function(){var t=this;a["a"].post(s["a"].orderConfirm,{id:this.setRow.id,note:this.setNote}).then((function(e){t.$toast.success(e.message),t.fetchData(!0),t.showSet=!1,t.showNote=!1,t.setRow={},t.setNote=""})).catch((function(){}))}}},f=u,l=(n("eef8"),n("2877")),d=Object(l["a"])(f,r,i,!1,null,"5e8a0210",null);e["default"]=d.exports},d28b:function(t,e,n){var r=n("746f");r("iterator")},e01a:function(t,e,n){"use strict";var r=n("23e7"),i=n("83ab"),o=n("da84"),a=n("5135"),s=n("861d"),c=n("9bf2").f,u=n("e893"),f=o.Symbol;if(i&&"function"==typeof f&&(!("description"in f.prototype)||void 0!==f().description)){var l={},d=function(){var t=arguments.length<1||void 0===arguments[0]?void 0:String(arguments[0]),e=this instanceof d?new f(t):void 0===t?f():f(t);return""===t&&(l[e]=!0),e};u(d,f);var h=d.prototype=f.prototype;h.constructor=d;var p=h.toString,v="Symbol(test)"==String(f("test")),b=/^Symbol\((.*)\)[^)]+$/;c(h,"description",{configurable:!0,get:function(){var t=s(this)?this.valueOf():this,e=p.call(t);if(a(l,t))return"";var n=v?e.slice(7,-1):e.replace(b,"$1");return""===n?void 0:n}}),r({global:!0,forced:!0},{Symbol:d})}},e538:function(t,e,n){var r=n("b622");e.f=r},ed08:function(t,e,n){"use strict";n.d(e,"c",(function(){return i})),n.d(e,"a",(function(){return o})),n.d(e,"b",(function(){return a}));n("a4d3"),n("e01a"),n("d3b7"),n("d28b"),n("3ca3"),n("ddb0");function r(t){return r="function"===typeof Symbol&&"symbol"===typeof Symbol.iterator?function(t){return typeof t}:function(t){return t&&"function"===typeof Symbol&&t.constructor===Symbol&&t!==Symbol.prototype?"symbol":typeof t},r(t)}n("25f0"),n("ac1f"),n("5319"),n("4d90"),n("1276"),n("a15b"),n("caad"),n("159b");function i(t,e){if(0===arguments.length)return null;var n,i=e||"{y}-{m}-{d} {h}:{i}:{s}";"object"===r(t)?n=t:("string"===typeof t&&/^[0-9]+$/.test(t)&&(t=parseInt(t)),"number"===typeof t&&10===t.toString().length&&(t*=1e3),n=new Date(t));var o={y:n.getFullYear(),m:n.getMonth()+1,d:n.getDate(),h:n.getHours(),i:n.getMinutes(),s:n.getSeconds(),a:n.getDay()},a=i.replace(/{([ymdhisa])+}/g,(function(t,e){var n=o[e];return"a"===e?["日","一","二","三","四","五","六"][n]:n.toString().padStart(2,"0")}));return a}function o(t,e,n){t=10===(""+t).length?1e3*parseInt(t):+t;var r=new Date(t),o=Date.now();if(n){var a=(o-r)/1e3;if(a<30)return"刚刚";if(a<3600)return Math.ceil(a/60)+"分钟前";if(a<86400)return Math.ceil(a/3600)+"小时前";if(a<172800)return"1天前"}return e?i(t,e):r.getMonth()+1+"月"+r.getDate()+"日"+r.getHours()+"时"+r.getMinutes()+"分"}function a(){var t=window.navigator.userAgent.toLowerCase();return-1!=t.indexOf("micromessenger")}},eef8:function(t,e,n){"use strict";n("7ab8")},fb6a:function(t,e,n){"use strict";var r=n("23e7"),i=n("861d"),o=n("e8b5"),a=n("23cb"),s=n("50c4"),c=n("fc6a"),u=n("8418"),f=n("b622"),l=n("1dde"),d=l("slice"),h=f("species"),p=[].slice,v=Math.max;r({target:"Array",proto:!0,forced:!d},{slice:function(t,e){var n,r,f,l=c(this),d=s(l.length),b=a(t,d),y=a(void 0===e?d:e,d);if(o(l)&&(n=l.constructor,"function"!=typeof n||n!==Array&&!o(n.prototype)?i(n)&&(n=n[h],null===n&&(n=void 0)):n=void 0,n===Array||void 0===n))return p.call(l,b,y);for(r=new(void 0===n?Array:n)(v(y-b,0)),f=0;b<y;b++,f++)b in l&&u(r,f,l[b]);return r.length=f,r}})}}]);