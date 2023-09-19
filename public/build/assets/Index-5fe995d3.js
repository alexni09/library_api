import{c as at,g as ot,r as K,o as it,a as ct,b as R,d as P,e as i,f as k,t as O,h as ut,F as tt,i as lt,j as et,n as dt,u as ht,p as ft,k as _t}from"./app-bc8e7929.js";var nt={exports:{}};(function(M,V){(function(S,g){M.exports=g()})(at,function(){var S=1e3,g=6e4,j=36e5,H="millisecond",b="second",p="minute",D="hour",v="day",L="week",l="month",q="quarter",y="year",x="date",N="Invalid Date",B=/^(\d{4})[-/]?(\d{1,2})?[-/]?(\d{0,2})[Tt\s]*(\d{1,2})?:?(\d{1,2})?:?(\d{1,2})?[.:]?(\d+)?$/,w=/\[([^\]]+)]|Y{1,4}|M{1,4}|D{1,2}|d{1,4}|H{1,2}|h{1,2}|a|A|m{1,2}|s{1,2}|Z{1,2}|SSS/g,st={name:"en",weekdays:"Sunday_Monday_Tuesday_Wednesday_Thursday_Friday_Saturday".split("_"),months:"January_February_March_April_May_June_July_August_September_October_November_December".split("_"),ordinal:function(r){var n=["th","st","nd","rd"],t=r%100;return"["+r+(n[(t-20)%10]||n[t]||n[0])+"]"}},G=function(r,n,t){var s=String(r);return!s||s.length>=n?r:""+Array(n+1-s.length).join(t)+r},rt={s:G,z:function(r){var n=-r.utcOffset(),t=Math.abs(n),s=Math.floor(t/60),e=t%60;return(n<=0?"+":"-")+G(s,2,"0")+":"+G(e,2,"0")},m:function r(n,t){if(n.date()<t.date())return-r(t,n);var s=12*(t.year()-n.year())+(t.month()-n.month()),e=n.clone().add(s,l),a=t-e<0,o=n.clone().add(s+(a?-1:1),l);return+(-(s+(t-e)/(a?e-o:o-e))||0)},a:function(r){return r<0?Math.ceil(r)||0:Math.floor(r)},p:function(r){return{M:l,y,w:L,d:v,D:x,h:D,m:p,s:b,ms:H,Q:q}[r]||String(r||"").toLowerCase().replace(/s$/,"")},u:function(r){return r===void 0}},W="en",T={};T[W]=st;var Q=function(r){return r instanceof J},E=function r(n,t,s){var e;if(!n)return W;if(typeof n=="string"){var a=n.toLowerCase();T[a]&&(e=a),t&&(T[a]=t,e=a);var o=n.split("-");if(!e&&o.length>1)return r(o[0])}else{var u=n.name;T[u]=n,e=u}return!s&&e&&(W=e),e||!s&&W},h=function(r,n){if(Q(r))return r.clone();var t=typeof n=="object"?n:{};return t.date=r,t.args=arguments,new J(t)},c=rt;c.l=E,c.i=Q,c.w=function(r,n){return h(r,{locale:n.$L,utc:n.$u,x:n.$x,$offset:n.$offset})};var J=function(){function r(t){this.$L=E(t.locale,null,!0),this.parse(t)}var n=r.prototype;return n.parse=function(t){this.$d=function(s){var e=s.date,a=s.utc;if(e===null)return new Date(NaN);if(c.u(e))return new Date;if(e instanceof Date)return new Date(e);if(typeof e=="string"&&!/Z$/i.test(e)){var o=e.match(B);if(o){var u=o[2]-1||0,d=(o[7]||"0").substring(0,3);return a?new Date(Date.UTC(o[1],u,o[3]||1,o[4]||0,o[5]||0,o[6]||0,d)):new Date(o[1],u,o[3]||1,o[4]||0,o[5]||0,o[6]||0,d)}}return new Date(e)}(t),this.$x=t.x||{},this.init()},n.init=function(){var t=this.$d;this.$y=t.getFullYear(),this.$M=t.getMonth(),this.$D=t.getDate(),this.$W=t.getDay(),this.$H=t.getHours(),this.$m=t.getMinutes(),this.$s=t.getSeconds(),this.$ms=t.getMilliseconds()},n.$utils=function(){return c},n.isValid=function(){return this.$d.toString()!==N},n.isSame=function(t,s){var e=h(t);return this.startOf(s)<=e&&e<=this.endOf(s)},n.isAfter=function(t,s){return h(t)<this.startOf(s)},n.isBefore=function(t,s){return this.endOf(s)<h(t)},n.$g=function(t,s,e){return c.u(t)?this[s]:this.set(e,t)},n.unix=function(){return Math.floor(this.valueOf()/1e3)},n.valueOf=function(){return this.$d.getTime()},n.startOf=function(t,s){var e=this,a=!!c.u(s)||s,o=c.p(t),u=function(A,m){var I=c.w(e.$u?Date.UTC(e.$y,m,A):new Date(e.$y,m,A),e);return a?I:I.endOf(v)},d=function(A,m){return c.w(e.toDate()[A].apply(e.toDate("s"),(a?[0,0,0,0]:[23,59,59,999]).slice(m)),e)},f=this.$W,_=this.$M,$=this.$D,F="set"+(this.$u?"UTC":"");switch(o){case y:return a?u(1,0):u(31,11);case l:return a?u(1,_):u(0,_+1);case L:var C=this.$locale().weekStart||0,z=(f<C?f+7:f)-C;return u(a?$-z:$+(6-z),_);case v:case x:return d(F+"Hours",0);case D:return d(F+"Minutes",1);case p:return d(F+"Seconds",2);case b:return d(F+"Milliseconds",3);default:return this.clone()}},n.endOf=function(t){return this.startOf(t,!1)},n.$set=function(t,s){var e,a=c.p(t),o="set"+(this.$u?"UTC":""),u=(e={},e[v]=o+"Date",e[x]=o+"Date",e[l]=o+"Month",e[y]=o+"FullYear",e[D]=o+"Hours",e[p]=o+"Minutes",e[b]=o+"Seconds",e[H]=o+"Milliseconds",e)[a],d=a===v?this.$D+(s-this.$W):s;if(a===l||a===y){var f=this.clone().set(x,1);f.$d[u](d),f.init(),this.$d=f.set(x,Math.min(this.$D,f.daysInMonth())).$d}else u&&this.$d[u](d);return this.init(),this},n.set=function(t,s){return this.clone().$set(t,s)},n.get=function(t){return this[c.p(t)]()},n.add=function(t,s){var e,a=this;t=Number(t);var o=c.p(s),u=function(_){var $=h(a);return c.w($.date($.date()+Math.round(_*t)),a)};if(o===l)return this.set(l,this.$M+t);if(o===y)return this.set(y,this.$y+t);if(o===v)return u(1);if(o===L)return u(7);var d=(e={},e[p]=g,e[D]=j,e[b]=S,e)[o]||1,f=this.$d.getTime()+t*d;return c.w(f,this)},n.subtract=function(t,s){return this.add(-1*t,s)},n.format=function(t){var s=this,e=this.$locale();if(!this.isValid())return e.invalidDate||N;var a=t||"YYYY-MM-DDTHH:mm:ssZ",o=c.z(this),u=this.$H,d=this.$m,f=this.$M,_=e.weekdays,$=e.months,F=e.meridiem,C=function(m,I,U,Z){return m&&(m[I]||m(s,a))||U[I].slice(0,Z)},z=function(m){return c.s(u%12||12,m,"0")},A=F||function(m,I,U){var Z=m<12?"AM":"PM";return U?Z.toLowerCase():Z};return a.replace(w,function(m,I){return I||function(U){switch(U){case"YY":return String(s.$y).slice(-2);case"YYYY":return c.s(s.$y,4,"0");case"M":return f+1;case"MM":return c.s(f+1,2,"0");case"MMM":return C(e.monthsShort,f,$,3);case"MMMM":return C($,f);case"D":return s.$D;case"DD":return c.s(s.$D,2,"0");case"d":return String(s.$W);case"dd":return C(e.weekdaysMin,s.$W,_,2);case"ddd":return C(e.weekdaysShort,s.$W,_,3);case"dddd":return _[s.$W];case"H":return String(u);case"HH":return c.s(u,2,"0");case"h":return z(1);case"hh":return z(2);case"a":return A(u,d,!0);case"A":return A(u,d,!1);case"m":return String(d);case"mm":return c.s(d,2,"0");case"s":return String(s.$s);case"ss":return c.s(s.$s,2,"0");case"SSS":return c.s(s.$ms,3,"0");case"Z":return o}return null}(m)||o.replace(":","")})},n.utcOffset=function(){return 15*-Math.round(this.$d.getTimezoneOffset()/15)},n.diff=function(t,s,e){var a,o=this,u=c.p(s),d=h(t),f=(d.utcOffset()-this.utcOffset())*g,_=this-d,$=function(){return c.m(o,d)};switch(u){case y:a=$()/12;break;case l:a=$();break;case q:a=$()/3;break;case L:a=(_-f)/6048e5;break;case v:a=(_-f)/864e5;break;case D:a=_/j;break;case p:a=_/g;break;case b:a=_/S;break;default:a=_}return e?a:c.a(a)},n.daysInMonth=function(){return this.endOf(l).$D},n.$locale=function(){return T[this.$L]},n.locale=function(t,s){if(!t)return this.$L;var e=this.clone(),a=E(t,s,!0);return a&&(e.$L=a),e},n.clone=function(){return c.w(this.$d,this)},n.toDate=function(){return new Date(this.valueOf())},n.toJSON=function(){return this.isValid()?this.toISOString():null},n.toISOString=function(){return this.$d.toISOString()},n.toString=function(){return this.$d.toUTCString()},r}(),X=J.prototype;return h.prototype=X,[["$ms",H],["$s",b],["$m",p],["$H",D],["$W",v],["$M",l],["$y",y],["$D",x]].forEach(function(r){X[r[1]]=function(n){return this.$g(n,r[0],r[1])}}),h.extend=function(r,n){return r.$i||(r(n,J,h),r.$i=!0),h},h.locale=E,h.isDayjs=Q,h.unix=function(r){return h(1e3*r)},h.en=T[W],h.Ls=T,h.p={},h})})(nt);var mt=nt.exports;const $t=ot(mt);const pt=(M,V)=>{const S=M.__vccOpts||M;for(const[g,j]of V)S[g]=j;return S},Y=M=>(ft("data-v-0c1c355d"),M=M(),_t(),M),vt=Y(()=>i("div",{class:"flex justify-center"},[i("h1",{class:"mt-2 mb-2 font-bold text-4xl"},[k(":"),i("span",{class:"ml-1"},":"),k(" library_api "),i("span",{class:"mr-1"},":"),k(":")])],-1)),yt={class:"flex justify-center"},gt={class:"mb-4 font-medium text-2xl"},Mt=Y(()=>i("i",{class:"mr-0.5"},"F",-1)),St={class:"flex justify-center"},bt={class:"bg-zinc-50 border border-zinc-500"},Dt=Y(()=>i("tr",null,[i("th",{class:"p-1 bg-zinc-300 font-semibold border-b border-zinc-500 whitespace-nowrap"},"When ?"),i("th",{class:"thStyle"},"Method"),i("th",{class:"thStyle"},"URL"),i("th",{class:"thStyle"},"IP"),i("th",{class:"thStyle"},"Status")],-1)),xt={key:0},wt=Y(()=>i("td",{colspan:"5",class:"p-1 font-medium italic whitespace-nowrap"},"No Records Found!",-1)),Ot=[wt],kt={class:"px-1 whitespace-nowrap"},It={class:"tdStyle"},Yt={class:"tdStyle"},jt={class:"tdStyle"},Ht={class:"tdStyle"},Lt={class:"flex justify-center"},Tt={class:"justify-center"},Ct=Y(()=>i("h4",{class:"mt-3 font-bold text-lg"},[k("Statistics:"),i("span",{class:"ml-2 italic text-sm font-semibold"},"(updated hourly)")],-1)),At={class:"font-normal text-base"},Ft=Y(()=>i("i",null,"category_count:",-1)),Nt={class:"ml-0.5 font-semibold"},Wt={class:"font-normal text-base"},zt=Y(()=>i("i",null,"book_count:",-1)),Ut={class:"ml-0.5 font-semibold"},Vt={class:"font-normal text-base"},Bt=Y(()=>i("i",null,"exemplar_count:",-1)),Et={class:"ml-0.5 font-semibold"},Jt={__name:"Index",setup(M){const V=new Intl.NumberFormat("en-US",{style:"currency",currency:"USD"});var S=null,g=null,j=null;const H=K(null),b=K(""),p=K(null),D=()=>{axios.get("/api/money").then(l=>{b.value=V.format(l.data.money/100)}).catch(function(l){console.log(l)})},v=()=>{axios.get("/api/monitor").then(l=>{H.value=et(l.data.data)}).catch(function(l){console.log(l)})},L=()=>{axios.get("/api/count").then(l=>{p.value=et(l.data.data)}).catch(function(l){console.log(l)})};return it(()=>{D(),v(),L(),S=setInterval(v,5e3),g=setInterval(D,3500),j=setInterval(L,6e4)}),ct(()=>{clearInterval(S),clearInterval(g),clearInterval(j)}),(l,q)=>{var y,x,N,B;return R(),P(tt,null,[vt,i("div",yt,[i("h3",gt,[k("Accumulated: "),Mt,k(O(b.value),1)])]),i("div",St,[i("table",bt,[Dt,((y=H.value)==null?void 0:y.length)===0?(R(),P("tr",xt,Ot)):ut("",!0),(R(!0),P(tt,null,lt(H.value,w=>(R(),P("tr",{key:w.id,class:dt({"bg-zinc-200":w.id%6>2})},[i("td",kt,O(ht($t)(w.datetime+"+00:00").format("YYYY/MM/DD HH:mm:ss")),1),i("td",It,O(w.method),1),i("td",Yt,O(w.url),1),i("td",jt,O(w.ip),1),i("td",Ht,O(w.status),1)],2))),128))])]),i("div",Lt,[i("div",Tt,[Ct,i("p",At,[Ft,k(),i("span",Nt,O((x=p.value)==null?void 0:x.category_count),1)]),i("p",Wt,[zt,k(),i("span",Ut,O((N=p.value)==null?void 0:N.book_count),1)]),i("p",Vt,[Bt,k(),i("span",Et,O((B=p.value)==null?void 0:B.exemplar_count),1)])])])],64)}}},Rt=pt(Jt,[["__scopeId","data-v-0c1c355d"]]);export{Rt as default};