!function(e){"object"==typeof module&&"undefined"!=typeof module.exports?module.exports=e:e()}(function(){(window.webpackJsonpFusionCharts=window.webpackJsonpFusionCharts||[]).push([[12],{1102:function(e,t,n){"use strict";t.__esModule=!0,t.OverlapperColumn2D=undefined;var o=l(n(1103)),a=l(n(958));function l(e){return e&&e.__esModule?e:{"default":e}}t.OverlapperColumn2D=o["default"],t["default"]={name:"overlappedcolumn2d",type:"package",requiresFusionCharts:!0,extension:function(e){e.addDep(a["default"]),e.addDep(o["default"])}}},1103:function(e,t,n){"use strict";t.__esModule=!0;var o,a=n(1104),l=(o=a)&&o.__esModule?o:{"default":o};t["default"]=l["default"]},1104:function(e,t,n){"use strict";t.__esModule=!0;var o=r(n(459)),a=r(n(1105)),l=r(n(1101)),i=r(n(461));function r(e){return e&&e.__esModule?e:{"default":e}}function s(e,t){if("function"!=typeof t&&null!==t)throw new TypeError("Super expression must either be null or a function, not "+typeof t);e.prototype=Object.create(t&&t.prototype,{constructor:{value:e,enumerable:!1,writable:!0,configurable:!0}}),t&&(Object.setPrototypeOf?Object.setPrototypeOf(e,t):function(e,t){for(var n=Object.getOwnPropertyNames(t),o=0;o<n.length;o++){var a=n[o],l=Object.getOwnPropertyDescriptor(t,a);l&&l.configurable&&e[a]===undefined&&Object.defineProperty(e,a,l)}}(e,t))}var u=function(e){function t(){!function(e,t){if(!(e instanceof t))throw new TypeError("Cannot call a class as a function")}(this,t);var n=function(e,t){if(!e)throw new ReferenceError("this hasn't been initialised - super() hasn't been called");return!t||"object"!=typeof t&&"function"!=typeof t?e:t}(this,e.call(this));return n.eiMethods={},n.registerFactory("dataset",i["default"],["vCanvas"]),n}return s(t,e),t.prototype.getName=function(){return"OverlappedColumn2D"},t.getName=function(){return"OverlappedColumn2D"},t.prototype.__setDefaultConfig=function(){e.prototype.__setDefaultConfig.call(this),this.config.friendlyName="Multi-series Overlapped Column Chart",this.config.defaultDatasetType="column",this.config.enablemousetracking=!0},t.prototype.getDSdef=function(){return a["default"]},t.prototype.getDSGroupdef=function(){return l["default"]},t}(o["default"]);t["default"]=u},1105:function(e,t,n){"use strict";t.__esModule=!0;var o,a=n(125),l=n(432);function i(e,t){if("function"!=typeof t&&null!==t)throw new TypeError("Super expression must either be null or a function, not "+typeof t);e.prototype=Object.create(t&&t.prototype,{constructor:{value:e,enumerable:!1,writable:!0,configurable:!0}}),t&&(Object.setPrototypeOf?Object.setPrototypeOf(e,t):function(e,t){for(var n=Object.getOwnPropertyNames(t),o=0;o<n.length;o++){var a=n[o],l=Object.getOwnPropertyDescriptor(t,a);l&&l.configurable&&e[a]===undefined&&Object.defineProperty(e,a,l)}}(e,t))}function r(e,t){var n,o=void 0,a=e.y,l=void 0,i=e.height,r=void 0;for(o=0,n=t.length;o<n;o++)if(r=t[o].height,l=t[o].y,t[o].labelShown&&a+i>=l&&l+r>=a)return!0;return!1}var s=function(e){function t(){!function(e,t){if(!(e instanceof t))throw new TypeError("Cannot call a class as a function")}(this,t);var n=function(e,t){if(!e)throw new ReferenceError("this hasn't been initialised - super() hasn't been called");return!t||"object"!=typeof t&&"function"!=typeof t?e:t}(this,e.call(this));return n._labeldimensionMap={},n}return i(t,e),t.prototype.drawLabel=function(e,t){var n,o,l,i,s,u,f,p,c,d,h,b,g,y=this.getFromEnv("chart"),m=this.getFromEnv("animationManager"),v=y.config,w=this.getFromEnv("xAxis"),O=this.getFromEnv("paper"),_=this.getState("visible"),S=y.getFromEnv("smartLabel"),M=y.config.dataLabelStyle,D=this.config,j=w.getTicksLen(),x=this.components,C=x.data,k=x.pool,E=v.rotatevalues?270:0,F={},N=this.getJSONIndex(),P=this.getSkippingInfo&&this.getSkippingInfo(),L=P&&P.skippingApplied,T=P&&P.labelDraw||[],A=T.length,I=(0,a.pluckNumber)(e,0),J=(0,a.pluckNumber)(t,L?A:j),R=A===Math.abs(J-(I+1)),W=function(){this.attr({"text-bound":[]}),this.hide()},G=function(){this.show()};for((g=this.getContainer("labelGroup")).css({fontFamily:M.fontFamily,fontSize:M.fontSize,fontWeight:M.fontWeight,fontStyle:M.fontStyle}),g.show(),S.useEllipsesOnOverflow(y.config.useEllipsesWhenOverflow),S.setStyle(M),s=I;s<J;s++)f=(p=(o=C[i=L&&R?T[s]:s])&&o.config)&&p.setValue,void 0!==o&&void 0!==f&&null!==f&&!0!==p.labelSkip?(u=o.graphics)&&(c=p.showValue,b=y.getDatasets().map(function(e){return e.getJSONIndex()<N&&e._labeldimensionMap[s]}).filter(Boolean),D.showValues&&(n=r(F={x:p.props.label.attr.x,y:p.props.label.attr.y,width:p._state.labelWidth,height:p._state.labelHeight},b)),this._labeldimensionMap[s]=F,c&&null!==f&&!n?((l=p.props.label.attr).transform=O.getSuggestiveRotation(E,l.x,l.y),(d=u.label)||k&&k.label[0]&&(d=u.label=k.label[0],k.label.splice(0,1)),d=m.setAnimation({el:u.label||"text",attr:l,component:this,label:"plotLabel",index:i,container:g,callback:_?G:W}),this._labeldimensionMap[s].labelShown=!!_,u.label||(u.label=this.addGraphicalElement("plotLabel",d,!0))):(u.label&&m.setAnimation({el:u.label,component:this,doNotRemove:!0,callback:W,label:"plotLabel"}),this._labeldimensionMap[s].labelShown=!1)):(p&&delete p.labelSkip,(h=o&&o.graphics)&&h.label&&h.label.hide());D.labelDrawn=!0},t}(((o=l)&&o.__esModule?o:{"default":o})["default"]);t["default"]=s}}])});
//# sourceMappingURL=http://localhost:3052/3.13.3/map/licensed/fusioncharts.overlappedcolumn2d.js.map