webpackJsonp([10],{HcVH:function(t,i,n){"use strict";Object.defineProperty(i,"__esModule",{value:!0});var e=n("PVXw"),s={render:function(){var t=this.$createElement;return(this._self._c||t)("div",[this._t("default")],2)},staticRenderFns:[]},a=n("VU/8")(e.a,s,!1,null,null,null);i.default=a.exports},PVXw:function(t,i,n){"use strict";(function(t){var e=n("9LO+"),s=n("HpPs"),a=n("Brla");i.a={name:"bm-marker",mixins:[Object(e.a)("overlay")],props:{position:{},offset:{},icon:{},massClear:{type:Boolean,default:!0},dragging:{type:Boolean,default:!1},clicking:{type:Boolean,default:!0},raiseOnDrag:{type:Boolean,default:!1},draggingCursor:{type:String},rotation:{type:Number},shadow:{type:Object},title:{type:String},label:{type:Object},animation:{type:String},top:{type:Boolean,default:!1},zIndex:{type:Number,default:0}},watch:{"position.lng":function(t,i){var n=this.BMap,e=this.originInstance,s=this.position,o=this.renderByParent,r=this.$parent;t!==i&&t>=-180&&t<=180&&e.setPosition(Object(a.d)(n,{lng:t,lat:s.lat})),o&&r.reload()},"position.lat":function(t,i){var n=this.BMap,e=this.originInstance,s=this.position,o=this.renderByParent,r=this.$parent;t!==i&&t>=-74&&t<=74&&e.setPosition(Object(a.d)(n,{lng:s.lng,lat:t})),o&&r.reload()},"offset.width":function(t,i){var n=this.BMap,e=this.originInstance;t!==i&&e.setOffset(new n.Size(t,this.offset.height))},"offset.height":function(t,i){var n=this.BMap,e=this.originInstance;t!==i&&e.setOffset(new n.Size(this.offset.width,t))},icon:{deep:!0,handler:function(t){var i=this.BMap,n=this.originInstance,e=this.rotation;n&&n.setIcon(Object(a.b)(i,t)),e&&n&&n.setRotation(e)}},massClear:function(t){t?this.originInstance.enableMassClear():this.originInstance.disableMassClear()},dragging:function(t){t?this.originInstance.enableDragging():this.originInstance.disableDragging()},clicking:function(){this.reload()},raiseOnDrag:function(){this.reload()},draggingCursor:function(t){this.originInstance.setDraggingCursor(t)},rotation:function(t){this.originInstance.setRotation(t)},shadow:function(t){this.originInstance.setShadow(t)},title:function(t){this.originInstance.setTitle(t)},label:function(t){this.reload()},animation:function(i){this.originInstance.setAnimation(t[i])},top:function(t){this.originInstance.setTop(t)},zIndex:function(t){this.originInstance.setZIndex(t)}},methods:{load:function(){var i=this.BMap,n=this.map,e=this.position,o=this.offset,r=this.icon,l=this.massClear,c=this.dragging,h=this.clicking,g=this.raiseOnDrag,d=this.draggingCursor,f=this.rotation,u=this.shadow,p=this.title,b=this.label,I=this.animation,y=this.top,m=this.renderByParent,O=this.$parent,w=this.zIndex,B=new i.Marker(new i.Point(e.lng,e.lat),{offset:o,icon:r&&Object(a.b)(i,r),enableMassClear:l,enableDragging:c,enableClicking:h,raiseOnDrag:g,draggingCursor:d,rotation:f,shadow:u,title:p});this.originInstance=B,b&&B&&B.setLabel(Object(a.c)(i,b)),B.setTop(y),B.setZIndex(w),s.a.call(this,B),m?O.reload():n.addOverlay(B),B.setAnimation(t[I])}}}}).call(i,n("DuR2"))}});