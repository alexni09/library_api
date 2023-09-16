import{k as at,l as it,m as q,p as ot,q as ct,o as B,f as E,b as o,d as D,t as b,g as ut,F as K,s as lt,x as X,n as dt,u as ht,y as ft,z as _t}from"./app-42f0d454.js";import{_ as mt}from"./_plugin-vue_export-helper-c27b6911.js";var tt={exports:{}};(function(L,P){(function(T,x){L.exports=x()})(at,function(){var T=1e3,x=6e4,z=36e5,k="millisecond",M="second",v="minute",S="hour",y="day",I="week",l="month",J="quarter",g="year",m="date",G="Invalid Date",et=/^(\d{4})[-/]?(\d{1,2})?[-/]?(\d{0,2})[Tt\s]*(\d{1,2})?:?(\d{1,2})?:?(\d{1,2})?[.:]?(\d+)?$/,nt=/\[([^\]]+)]|Y{1,4}|M{1,4}|D{1,2}|d{1,4}|H{1,2}|h{1,2}|a|A|m{1,2}|s{1,2}|Z{1,2}|SSS/g,st={name:"en",weekdays:"Sunday_Monday_Tuesday_Wednesday_Thursday_Friday_Saturday".split("_"),months:"January_February_March_April_May_June_July_August_September_October_November_December".split("_"),ordinal:function(r){var n=["th","st","nd","rd"],t=r%100;return"["+r+(n[(t-20)%10]||n[t]||n[0])+"]"}},Z=function(r,n,t){var s=String(r);return!s||s.length>=n?r:""+Array(n+1-s.length).join(t)+r},rt={s:Z,z:function(r){var n=-r.utcOffset(),t=Math.abs(n),s=Math.floor(t/60),e=t%60;return(n<=0?"+":"-")+Z(s,2,"0")+":"+Z(e,2,"0")},m:function r(n,t){if(n.date()<t.date())return-r(t,n);var s=12*(t.year()-n.year())+(t.month()-n.month()),e=n.clone().add(s,l),a=t-e<0,i=n.clone().add(s+(a?-1:1),l);return+(-(s+(t-e)/(a?e-i:i-e))||0)},a:function(r){return r<0?Math.ceil(r)||0:Math.floor(r)},p:function(r){return{M:l,y:g,w:I,d:y,D:m,h:S,m:v,s:M,ms:k,Q:J}[r]||String(r||"").toLowerCase().replace(/s$/,"")},u:function(r){return r===void 0}},A="en",Y={};Y[A]=st;var R=function(r){return r instanceof U},W=function r(n,t,s){var e;if(!n)return A;if(typeof n=="string"){var a=n.toLowerCase();Y[a]&&(e=a),t&&(Y[a]=t,e=a);var i=n.split("-");if(!e&&i.length>1)return r(i[0])}else{var u=n.name;Y[u]=n,e=u}return!s&&e&&(A=e),e||!s&&A},h=function(r,n){if(R(r))return r.clone();var t=typeof n=="object"?n:{};return t.date=r,t.args=arguments,new U(t)},c=rt;c.l=W,c.i=R,c.w=function(r,n){return h(r,{locale:n.$L,utc:n.$u,x:n.$x,$offset:n.$offset})};var U=function(){function r(t){this.$L=W(t.locale,null,!0),this.parse(t)}var n=r.prototype;return n.parse=function(t){this.$d=function(s){var e=s.date,a=s.utc;if(e===null)return new Date(NaN);if(c.u(e))return new Date;if(e instanceof Date)return new Date(e);if(typeof e=="string"&&!/Z$/i.test(e)){var i=e.match(et);if(i){var u=i[2]-1||0,d=(i[7]||"0").substring(0,3);return a?new Date(Date.UTC(i[1],u,i[3]||1,i[4]||0,i[5]||0,i[6]||0,d)):new Date(i[1],u,i[3]||1,i[4]||0,i[5]||0,i[6]||0,d)}}return new Date(e)}(t),this.$x=t.x||{},this.init()},n.init=function(){var t=this.$d;this.$y=t.getFullYear(),this.$M=t.getMonth(),this.$D=t.getDate(),this.$W=t.getDay(),this.$H=t.getHours(),this.$m=t.getMinutes(),this.$s=t.getSeconds(),this.$ms=t.getMilliseconds()},n.$utils=function(){return c},n.isValid=function(){return this.$d.toString()!==G},n.isSame=function(t,s){var e=h(t);return this.startOf(s)<=e&&e<=this.endOf(s)},n.isAfter=function(t,s){return h(t)<this.startOf(s)},n.isBefore=function(t,s){return this.endOf(s)<h(t)},n.$g=function(t,s,e){return c.u(t)?this[s]:this.set(e,t)},n.unix=function(){return Math.floor(this.valueOf()/1e3)},n.valueOf=function(){return this.$d.getTime()},n.startOf=function(t,s){var e=this,a=!!c.u(s)||s,i=c.p(t),u=function(j,$){var w=c.w(e.$u?Date.UTC(e.$y,$,j):new Date(e.$y,$,j),e);return a?w:w.endOf(y)},d=function(j,$){return c.w(e.toDate()[j].apply(e.toDate("s"),(a?[0,0,0,0]:[23,59,59,999]).slice($)),e)},f=this.$W,_=this.$M,p=this.$D,C="set"+(this.$u?"UTC":"");switch(i){case g:return a?u(1,0):u(31,11);case l:return a?u(1,_):u(0,_+1);case I:var H=this.$locale().weekStart||0,F=(f<H?f+7:f)-H;return u(a?p-F:p+(6-F),_);case y:case m:return d(C+"Hours",0);case S:return d(C+"Minutes",1);case v:return d(C+"Seconds",2);case M:return d(C+"Milliseconds",3);default:return this.clone()}},n.endOf=function(t){return this.startOf(t,!1)},n.$set=function(t,s){var e,a=c.p(t),i="set"+(this.$u?"UTC":""),u=(e={},e[y]=i+"Date",e[m]=i+"Date",e[l]=i+"Month",e[g]=i+"FullYear",e[S]=i+"Hours",e[v]=i+"Minutes",e[M]=i+"Seconds",e[k]=i+"Milliseconds",e)[a],d=a===y?this.$D+(s-this.$W):s;if(a===l||a===g){var f=this.clone().set(m,1);f.$d[u](d),f.init(),this.$d=f.set(m,Math.min(this.$D,f.daysInMonth())).$d}else u&&this.$d[u](d);return this.init(),this},n.set=function(t,s){return this.clone().$set(t,s)},n.get=function(t){return this[c.p(t)]()},n.add=function(t,s){var e,a=this;t=Number(t);var i=c.p(s),u=function(_){var p=h(a);return c.w(p.date(p.date()+Math.round(_*t)),a)};if(i===l)return this.set(l,this.$M+t);if(i===g)return this.set(g,this.$y+t);if(i===y)return u(1);if(i===I)return u(7);var d=(e={},e[v]=x,e[S]=z,e[M]=T,e)[i]||1,f=this.$d.getTime()+t*d;return c.w(f,this)},n.subtract=function(t,s){return this.add(-1*t,s)},n.format=function(t){var s=this,e=this.$locale();if(!this.isValid())return e.invalidDate||G;var a=t||"YYYY-MM-DDTHH:mm:ssZ",i=c.z(this),u=this.$H,d=this.$m,f=this.$M,_=e.weekdays,p=e.months,C=e.meridiem,H=function($,w,N,V){return $&&($[w]||$(s,a))||N[w].slice(0,V)},F=function($){return c.s(u%12||12,$,"0")},j=C||function($,w,N){var V=$<12?"AM":"PM";return N?V.toLowerCase():V};return a.replace(nt,function($,w){return w||function(N){switch(N){case"YY":return String(s.$y).slice(-2);case"YYYY":return c.s(s.$y,4,"0");case"M":return f+1;case"MM":return c.s(f+1,2,"0");case"MMM":return H(e.monthsShort,f,p,3);case"MMMM":return H(p,f);case"D":return s.$D;case"DD":return c.s(s.$D,2,"0");case"d":return String(s.$W);case"dd":return H(e.weekdaysMin,s.$W,_,2);case"ddd":return H(e.weekdaysShort,s.$W,_,3);case"dddd":return _[s.$W];case"H":return String(u);case"HH":return c.s(u,2,"0");case"h":return F(1);case"hh":return F(2);case"a":return j(u,d,!0);case"A":return j(u,d,!1);case"m":return String(d);case"mm":return c.s(d,2,"0");case"s":return String(s.$s);case"ss":return c.s(s.$s,2,"0");case"SSS":return c.s(s.$ms,3,"0");case"Z":return i}return null}($)||i.replace(":","")})},n.utcOffset=function(){return 15*-Math.round(this.$d.getTimezoneOffset()/15)},n.diff=function(t,s,e){var a,i=this,u=c.p(s),d=h(t),f=(d.utcOffset()-this.utcOffset())*x,_=this-d,p=function(){return c.m(i,d)};switch(u){case g:a=p()/12;break;case l:a=p();break;case J:a=p()/3;break;case I:a=(_-f)/6048e5;break;case y:a=(_-f)/864e5;break;case S:a=_/z;break;case v:a=_/x;break;case M:a=_/T;break;default:a=_}return e?a:c.a(a)},n.daysInMonth=function(){return this.endOf(l).$D},n.$locale=function(){return Y[this.$L]},n.locale=function(t,s){if(!t)return this.$L;var e=this.clone(),a=W(t,s,!0);return a&&(e.$L=a),e},n.clone=function(){return c.w(this.$d,this)},n.toDate=function(){return new Date(this.valueOf())},n.toJSON=function(){return this.isValid()?this.toISOString():null},n.toISOString=function(){return this.$d.toISOString()},n.toString=function(){return this.$d.toUTCString()},r}(),Q=U.prototype;return h.prototype=Q,[["$ms",k],["$s",M],["$m",v],["$H",S],["$W",y],["$M",l],["$y",g],["$D",m]].forEach(function(r){Q[r[1]]=function(n){return this.$g(n,r[0],r[1])}}),h.extend=function(r,n){return r.$i||(r(n,U,h),r.$i=!0),h},h.locale=W,h.isDayjs=R,h.unix=function(r){return h(1e3*r)},h.en=Y[A],h.Ls=Y,h.p={},h})})(tt);var $t=tt.exports;const pt=it($t);const O=L=>(ft("data-v-9d019e37"),L=L(),_t(),L),vt=O(()=>o("div",{class:"flex justify-center"},[o("h1",{class:"mt-2 mb-2 font-bold text-4xl"},[D(":"),o("span",{class:"ml-1"},":"),D(" library_api "),o("span",{class:"mr-1"},":"),D(":")])],-1)),yt={class:"flex justify-center"},gt={class:"mb-4 font-medium text-2xl"},Mt=O(()=>o("i",{class:"mr-0.5"},"F",-1)),St={class:"flex justify-center"},bt={class:"bg-zinc-50 border border-zinc-500"},Dt=O(()=>o("tr",null,[o("th",{class:"p-1 bg-zinc-300 font-semibold border-b border-zinc-500 whitespace-nowrap"},"When ?"),o("th",{class:"thStyle"},"Method"),o("th",{class:"thStyle"},"URL"),o("th",{class:"thStyle"},"IP"),o("th",{class:"thStyle"},"Status")],-1)),xt={key:0},wt=O(()=>o("td",{colspan:"5",class:"p-1 font-medium italic whitespace-nowrap"},"No Records Found!",-1)),Ot=[wt],kt={class:"px-1 whitespace-nowrap"},It={class:"tdStyle"},Yt={class:"tdStyle"},Ht={class:"tdStyle"},jt={class:"tdStyle"},Lt={class:"flex justify-center"},Tt={class:"justify-center"},Ct=O(()=>o("h4",{class:"mt-3 font-bold text-lg"},[D("Statistics:"),o("span",{class:"ml-2 italic text-sm font-semibold"},"(updated hourly)")],-1)),zt={class:"font-normal text-base"},At=O(()=>o("i",null,"category_count:",-1)),Ft={class:"ml-0.5 font-semibold"},Nt={class:"font-normal text-base"},Wt=O(()=>o("i",null,"book_count:",-1)),Ut={class:"ml-0.5 font-semibold"},Vt={class:"font-normal text-base"},Bt=O(()=>o("i",null,"exemplar_count:",-1)),Et={class:"ml-0.5 font-semibold"},Jt={__name:"Index",setup(L){const P=new Intl.NumberFormat("en-US",{style:"currency",currency:"USD"});var T=null,x=null,z=null;const k=q(null),M=q(""),v=q(null),S=()=>{axios.get("/api/money").then(l=>{M.value=P.format(l.data.money/100)}).catch(function(l){console.log(l)})},y=()=>{axios.get("/api/monitor").then(l=>{k.value=X(l.data.data)}).catch(function(l){console.log(l)})},I=()=>{axios.get("/api/count").then(l=>{v.value=X(l.data.data)}).catch(function(l){console.log(l)})};return ot(()=>{S(),y(),I(),T=setInterval(y,5e3),x=setInterval(S,3500),z=setInterval(I,6e4)}),ct(()=>{clearInterval(T),clearInterval(x),clearInterval(z)}),(l,J)=>{var g;return B(),E(K,null,[vt,o("div",yt,[o("h3",gt,[D("Accumulated: "),Mt,D(b(M.value),1)])]),o("div",St,[o("table",bt,[Dt,((g=k.value)==null?void 0:g.length)===0?(B(),E("tr",xt,Ot)):ut("",!0),(B(!0),E(K,null,lt(k.value,m=>(B(),E("tr",{key:m.id,class:dt({"bg-zinc-200":m.id%6>2})},[o("td",kt,b(ht(pt)(m.datetime+"+00:00").format("YYYY/MM/DD HH:mm:ss")),1),o("td",It,b(m.method),1),o("td",Yt,b(m.url),1),o("td",Ht,b(m.ip),1),o("td",jt,b(m.status),1)],2))),128))])]),o("div",Lt,[o("div",Tt,[Ct,o("p",zt,[At,D(),o("span",Ft,b(v.value.category_count),1)]),o("p",Nt,[Wt,D(),o("span",Ut,b(v.value.book_count),1)]),o("p",Vt,[Bt,D(),o("span",Et,b(v.value.exemplar_count),1)])])])],64)}}},qt=mt(Jt,[["__scopeId","data-v-9d019e37"]]);export{qt as default};
