/*rateYo V1.2.2, A simple and flexible star rating plugin
prashanth pamidi (https://github.com/prrashi)*/
!function(a){"use strict";function b(a,b,c){return a===b?a=b:a===c&&(a=c),a}function c(a,b,c){var d=a>=b&&c>=a;if(!d)throw Error("Invalid Rating, expected value between "+b+" and "+c);return a}function d(b,c){var d;return a.each(c,function(){return b===this.node?(d=this,!1):void 0}),d}function e(b,c){return a.each(c,function(a){if(b===this.node){var d=c.slice(0,a),e=c.slice(a+1,c.length);return c=d.concat(e),!1}}),c}function f(a){return"undefined"!=typeof a}function g(d,h){function i(a){f(a)||(a=h.rating);var b=h.minValue,c=h.maxValue,d=(a-b)/(c-b)*100;I.css("width",d+"%")}function k(a){if(!f(a))return h.starWidth;h.starWidth=h.starHeight=a;var b=parseInt(h.starWidth.replace("px","").trim());return b*=h.numStars,d.width(b),H.find("svg").attr({width:h.starWidth,height:h.starHeight}),I.find("svg").attr({width:h.starWidth,height:h.starHeight}),d}function l(a){return f(a)?(h.normalFill=a,H.find("svg").attr({fill:h.normalFill}),d):h.normalFill}function m(a){return f(a)?(h.ratedFill=a,I.find("svg").attr({fill:h.ratedFill}),d):h.ratedFill}function n(b){if(!f(b))return h.numStars;h.numStars=b,H.empty(),I.empty();for(var c=0;c<h.numStars;c++)H.append(a(j)),I.append(a(j));return k(h.starWidth),m(h.ratedFill),l(h.normalFill),i(),d}function o(a){return f(a)?(h.minValue=a,i(),d):h.minValue}function p(a){return f(a)?(h.maxValue=a,i(),d):h.maxValue}function q(a){return f(a)?(h.precision=a,i(),d):h.precision}function r(a){return f(a)?(h.halfStar=a,d):h.halfStar}function s(a){return f(a)?(h.fullStar=a,d):h.fullStar}function t(a){var b,c=H.offset(),d=c.left,e=d+H.width(),f=h.minValue,g=h.maxValue,i=a.pageX;return d>i?b=f:i>e?b=g:(b=(i-d)/(e-d),b*=g-f,b+=f),h.halfStar&&(b=b>Math.ceil(b)-.5?Math.ceil(b):Math.ceil(b)-.5),h.fullStar&&(b=Math.ceil(b)),b}function u(a){var c=t(a).toFixed(h.precision),e=h.minValue,f=h.maxValue;c=b(parseFloat(c),e,f),i(c),d.trigger("rateyo.change",{rating:c})}function v(){i(),d.trigger("rateyo.change",{rating:h.rating})}function w(a){var b=t(a).toFixed(h.precision);b=parseFloat(b),F.rating(b)}function x(a,b){h.onChange&&"function"==typeof h.onChange&&h.onChange.apply(this,[b.rating,F])}function y(a,b){h.onSet&&"function"==typeof h.onSet&&h.onSet.apply(this,[b.rating,F])}function z(){d.on("mousemove",u).on("mouseenter",u).on("mouseleave",v).on("click",w).on("rateyo.change",x).on("rateyo.set",y)}function A(){d.off("mousemove",u).off("mouseenter",u).off("mouseleave",v).off("click",w).off("rateyo.change",x).off("rateyo.set",y)}function B(a){return f(a)?(h.readOnly=a,d.attr("readonly",!0),A(),a||(d.removeAttr("readonly"),z()),d):h.readOnly}function C(a){if(!f(a))return h.rating;var e=a,g=h.maxValue,j=h.minValue;return"string"==typeof e&&("%"===e[e.length-1]&&(e=e.substr(0,e.length-1),g=100,j=0,p(g),o(j)),e=parseFloat(e)),c(e,j,g),e=parseFloat(e.toFixed(h.precision)),b(parseFloat(e),j,g),h.rating=e,i(),d.trigger("rateyo.set",{rating:e}),d}function D(a){return f(a)?(h.onSet=a,d):h.onSet}function E(a){return f(a)?(h.onChange=a,d):h.onChange}this.$node=d,this.node=d.get(0);var F=this;d.addClass("jq-ry-container");var G=a("<div/>").addClass("jq-ry-group-wrapper").appendTo(d),H=a("<div/>").addClass("jq-ry-normal-group").addClass("jq-ry-group").appendTo(G),I=a("<div/>").addClass("jq-ry-rated-group").addClass("jq-ry-group").appendTo(G);this.rating=function(a){return f(a)?(C(a),d):h.rating},this.destroy=function(){return h.readOnly||A(),g.prototype.collection=e(d.get(0),this.collection),d.removeClass("jq-ry-container").children().remove(),d},this.method=function(a){if(!a)throw Error("Method name not specified!");if(!f(this[a]))throw Error("Method "+a+" doesn't exist!");var b=Array.prototype.slice.apply(arguments,[]),c=b.slice(1),d=this[a];return d.apply(this,c)},this.option=function(a,b){if(!f(a))return h;var c;switch(a){case"starWidth":c=k;break;case"numStars":c=n;break;case"normalFill":c=l;break;case"ratedFill":c=m;break;case"minValue":c=o;break;case"maxValue":c=p;break;case"precision":c=q;break;case"rating":c=C;break;case"halfStar":c=r;break;case"fullStar":c=s;break;case"readOnly":c=B;break;case"onSet":c=D;break;case"onChange":c=E;break;default:throw Error("No such option as "+a)}return c(b)},n(h.numStars),B(h.readOnly),this.collection.push(this),this.rating(h.rating)}function h(b){var c=g.prototype.collection,e=a(this);if(0===e.length)return e;var f=Array.prototype.slice.apply(arguments,[]);if(0===f.length)b=f[0]={};else{if(1!==f.length||"object"!=typeof f[0]){if(f.length>=1&&"string"==typeof f[0]){var h=f[0],i=f.slice(1),j=[];return a.each(e,function(a,b){var e=d(b,c);if(!e)throw Error("Trying to set options before even initialization");var f=e[h];if(!f)throw Error("Method "+h+" does not exist!");var g=f.apply(e,i);j.push(g)}),j=1===j.length?j[0]:a(j)}throw Error("Invalid Arguments")}b=f[0]}return b=a.extend(JSON.parse(JSON.stringify(k)),b),a.each(e,function(){var e=d(this,c);return e?void 0:new g(a(this),b)})}function i(){return h.apply(this,Array.prototype.slice.apply(arguments,[]))}var j='<?xml version="1.0" encoding="utf-8"?><svg version="1.1" id="Layer_1"xmlns="http://www.w3.org/2000/svg"viewBox="0 12.705 512 486.59"x="0px" y="0px"xml:space="preserve"><polygon id="star-icon"points="256.814,12.705 317.205,198.566 512.631,198.566 354.529,313.435 414.918,499.295 256.814,384.427 98.713,499.295 159.102,313.435 1,198.566 196.426,198.566 "/></svg>',k={starWidth:"32px",normalFill:"gray",ratedFill:"#f39c12",numStars:5,minValue:0,maxValue:5,precision:1,rating:0,fullStar:!1,halfStar:!1,readOnly:!1,onChange:null,onSet:null};g.prototype.collection=[],a.fn.rateYo=i}(jQuery);
//# sourceMappingURL=jquery.rateyo.min.js.map