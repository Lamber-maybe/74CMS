webpackJsonp([0],{"9LO+":function(e,t,r){"use strict";const a={control:{unload:"removeControl"},layer:{unload:"removeTileLayer"},overlay:{unload:"removeOverlay"},contextMenu:{unload:"removeContextMenu"}},n=e=>e.abstract||e.$el===e.$children[0].$el?n(e.$parent):e;function s(){const{unload:e,renderByParent:t,$parent:r}=this;t&&r.reload(),e()}t.a=(e=>new class{constructor(e){this.methods={ready(){const e=n(this.$parent),t=this.BMap=e.BMap,r=this.map=e.map;this.load(),this.$emit("ready",{BMap:t,map:r})},transmitEvent(e){this.$emit(e.type.replace(/^on/,""),e)},reload(){this&&this.BMap&&this.$nextTick(()=>{this.unload(),this.$nextTick(this.load)})},unload(){const{map:t,originInstance:r}=this;try{switch(e.type){case"search":return r.clearResults();case"autoComplete":case"lushu":return r.dispose();case"markerClusterer":return r.clearMarkers();default:t[a[e.type].unload](r)}}catch(e){}}},this.computed={renderByParent(){return this.$parent.preventChildrenRender}},this.mounted=function(){const e=n(this.$parent),t=e.map,{ready:r}=this;t?r():e.$on("ready",r)},this.destroyed=s,this.beforeDestroy=s}}({type:e}))}});