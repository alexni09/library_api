import{c as ut,g as dt,r as B,o as ht,a as ft,b as _,d as m,e as t,f as Y,t as d,h as V,F as st,i as nt,j as it,n as ot,u as at,p as pt,k as _t}from"./app-acbe14da.js";var ct={exports:{}};(function(y,Z){(function(H,j){y.exports=j()})(ut,function(){var H=1e3,j=6e4,L=36e5,c="millisecond",S="second",M="minute",k="hour",D="day",I="week",x="month",E="quarter",O="year",p="date",et="Invalid Date",R=/^(\d{4})[-/]?(\d{1,2})?[-/]?(\d{0,2})[Tt\s]*(\d{1,2})?:?(\d{1,2})?:?(\d{1,2})?[.:]?(\d+)?$/,q=/\[([^\]]+)]|Y{1,4}|M{1,4}|D{1,2}|d{1,4}|H{1,2}|h{1,2}|a|A|m{1,2}|s{1,2}|Z{1,2}|SSS/g,Q={name:"en",weekdays:"Sunday_Monday_Tuesday_Wednesday_Thursday_Friday_Saturday".split("_"),months:"January_February_March_April_May_June_July_August_September_October_November_December".split("_"),ordinal:function(r){var n=["th","st","nd","rd"],e=r%100;return"["+r+(n[(e-20)%10]||n[e]||n[0])+"]"}},P=function(r,n,e){var a=String(r);return!a||a.length>=n?r:""+Array(n+1-a.length).join(e)+r},K={s:P,z:function(r){var n=-r.utcOffset(),e=Math.abs(n),a=Math.floor(e/60),s=e%60;return(n<=0?"+":"-")+P(a,2,"0")+":"+P(s,2,"0")},m:function r(n,e){if(n.date()<e.date())return-r(e,n);var a=12*(e.year()-n.year())+(e.month()-n.month()),s=n.clone().add(a,x),l=e-s<0,i=n.clone().add(a+(l?-1:1),x);return+(-(a+(e-s)/(l?s-i:i-s))||0)},a:function(r){return r<0?Math.ceil(r)||0:Math.floor(r)},p:function(r){return{M:x,y:O,w:I,d:D,D:p,h:k,m:M,s:S,ms:c,Q:E}[r]||String(r||"").toLowerCase().replace(/s$/,"")},u:function(r){return r===void 0}},T="en",z={};z[T]=Q;var F=function(r){return r instanceof X},N=function r(n,e,a){var s;if(!n)return T;if(typeof n=="string"){var l=n.toLowerCase();z[l]&&(s=l),e&&(z[l]=e,s=l);var i=n.split("-");if(!s&&i.length>1)return r(i[0])}else{var u=n.name;z[u]=n,s=u}return!a&&s&&(T=s),s||!a&&T},h=function(r,n){if(F(r))return r.clone();var e=typeof n=="object"?n:{};return e.date=r,e.args=arguments,new X(e)},o=K;o.l=N,o.i=F,o.w=function(r,n){return h(r,{locale:n.$L,utc:n.$u,x:n.$x,$offset:n.$offset})};var X=function(){function r(e){this.$L=N(e.locale,null,!0),this.parse(e)}var n=r.prototype;return n.parse=function(e){this.$d=function(a){var s=a.date,l=a.utc;if(s===null)return new Date(NaN);if(o.u(s))return new Date;if(s instanceof Date)return new Date(s);if(typeof s=="string"&&!/Z$/i.test(s)){var i=s.match(R);if(i){var u=i[2]-1||0,f=(i[7]||"0").substring(0,3);return l?new Date(Date.UTC(i[1],u,i[3]||1,i[4]||0,i[5]||0,i[6]||0,f)):new Date(i[1],u,i[3]||1,i[4]||0,i[5]||0,i[6]||0,f)}}return new Date(s)}(e),this.$x=e.x||{},this.init()},n.init=function(){var e=this.$d;this.$y=e.getFullYear(),this.$M=e.getMonth(),this.$D=e.getDate(),this.$W=e.getDay(),this.$H=e.getHours(),this.$m=e.getMinutes(),this.$s=e.getSeconds(),this.$ms=e.getMilliseconds()},n.$utils=function(){return o},n.isValid=function(){return this.$d.toString()!==et},n.isSame=function(e,a){var s=h(e);return this.startOf(a)<=s&&s<=this.endOf(a)},n.isAfter=function(e,a){return h(e)<this.startOf(a)},n.isBefore=function(e,a){return this.endOf(a)<h(e)},n.$g=function(e,a,s){return o.u(e)?this[a]:this.set(s,e)},n.unix=function(){return Math.floor(this.valueOf()/1e3)},n.valueOf=function(){return this.$d.getTime()},n.startOf=function(e,a){var s=this,l=!!o.u(a)||a,i=o.p(e),u=function(C,$){var A=o.w(s.$u?Date.UTC(s.$y,$,C):new Date(s.$y,$,C),s);return l?A:A.endOf(D)},f=function(C,$){return o.w(s.toDate()[C].apply(s.toDate("s"),(l?[0,0,0,0]:[23,59,59,999]).slice($)),s)},b=this.$W,g=this.$M,w=this.$D,W="set"+(this.$u?"UTC":"");switch(i){case O:return l?u(1,0):u(31,11);case x:return l?u(1,g):u(0,g+1);case I:var U=this.$locale().weekStart||0,G=(b<U?b+7:b)-U;return u(l?w-G:w+(6-G),g);case D:case p:return f(W+"Hours",0);case k:return f(W+"Minutes",1);case M:return f(W+"Seconds",2);case S:return f(W+"Milliseconds",3);default:return this.clone()}},n.endOf=function(e){return this.startOf(e,!1)},n.$set=function(e,a){var s,l=o.p(e),i="set"+(this.$u?"UTC":""),u=(s={},s[D]=i+"Date",s[p]=i+"Date",s[x]=i+"Month",s[O]=i+"FullYear",s[k]=i+"Hours",s[M]=i+"Minutes",s[S]=i+"Seconds",s[c]=i+"Milliseconds",s)[l],f=l===D?this.$D+(a-this.$W):a;if(l===x||l===O){var b=this.clone().set(p,1);b.$d[u](f),b.init(),this.$d=b.set(p,Math.min(this.$D,b.daysInMonth())).$d}else u&&this.$d[u](f);return this.init(),this},n.set=function(e,a){return this.clone().$set(e,a)},n.get=function(e){return this[o.p(e)]()},n.add=function(e,a){var s,l=this;e=Number(e);var i=o.p(a),u=function(g){var w=h(l);return o.w(w.date(w.date()+Math.round(g*e)),l)};if(i===x)return this.set(x,this.$M+e);if(i===O)return this.set(O,this.$y+e);if(i===D)return u(1);if(i===I)return u(7);var f=(s={},s[M]=j,s[k]=L,s[S]=H,s)[i]||1,b=this.$d.getTime()+e*f;return o.w(b,this)},n.subtract=function(e,a){return this.add(-1*e,a)},n.format=function(e){var a=this,s=this.$locale();if(!this.isValid())return s.invalidDate||et;var l=e||"YYYY-MM-DDTHH:mm:ssZ",i=o.z(this),u=this.$H,f=this.$m,b=this.$M,g=s.weekdays,w=s.months,W=s.meridiem,U=function($,A,J,tt){return $&&($[A]||$(a,l))||J[A].slice(0,tt)},G=function($){return o.s(u%12||12,$,"0")},C=W||function($,A,J){var tt=$<12?"AM":"PM";return J?tt.toLowerCase():tt};return l.replace(q,function($,A){return A||function(J){switch(J){case"YY":return String(a.$y).slice(-2);case"YYYY":return o.s(a.$y,4,"0");case"M":return b+1;case"MM":return o.s(b+1,2,"0");case"MMM":return U(s.monthsShort,b,w,3);case"MMMM":return U(w,b);case"D":return a.$D;case"DD":return o.s(a.$D,2,"0");case"d":return String(a.$W);case"dd":return U(s.weekdaysMin,a.$W,g,2);case"ddd":return U(s.weekdaysShort,a.$W,g,3);case"dddd":return g[a.$W];case"H":return String(u);case"HH":return o.s(u,2,"0");case"h":return G(1);case"hh":return G(2);case"a":return C(u,f,!0);case"A":return C(u,f,!1);case"m":return String(f);case"mm":return o.s(f,2,"0");case"s":return String(a.$s);case"ss":return o.s(a.$s,2,"0");case"SSS":return o.s(a.$ms,3,"0");case"Z":return i}return null}($)||i.replace(":","")})},n.utcOffset=function(){return 15*-Math.round(this.$d.getTimezoneOffset()/15)},n.diff=function(e,a,s){var l,i=this,u=o.p(a),f=h(e),b=(f.utcOffset()-this.utcOffset())*j,g=this-f,w=function(){return o.m(i,f)};switch(u){case O:l=w()/12;break;case x:l=w();break;case E:l=w()/3;break;case I:l=(g-b)/6048e5;break;case D:l=(g-b)/864e5;break;case k:l=g/L;break;case M:l=g/j;break;case S:l=g/H;break;default:l=g}return s?l:o.a(l)},n.daysInMonth=function(){return this.endOf(x).$D},n.$locale=function(){return z[this.$L]},n.locale=function(e,a){if(!e)return this.$L;var s=this.clone(),l=N(e,a,!0);return l&&(s.$L=l),s},n.clone=function(){return o.w(this.$d,this)},n.toDate=function(){return new Date(this.valueOf())},n.toJSON=function(){return this.isValid()?this.toISOString():null},n.toISOString=function(){return this.$d.toISOString()},n.toString=function(){return this.$d.toUTCString()},r}(),lt=X.prototype;return h.prototype=lt,[["$ms",c],["$s",S],["$m",M],["$H",k],["$W",D],["$M",x],["$y",O],["$D",p]].forEach(function(r){lt[r[1]]=function(n){return this.$g(n,r[0],r[1])}}),h.extend=function(r,n){return r.$i||(r(n,X,h),r.$i=!0),h},h.locale=N,h.isDayjs=F,h.unix=function(r){return h(1e3*r)},h.en=z[T],h.Ls=z,h.p={},h})})(ct);var mt=ct.exports;const rt=dt(mt);const bt=(y,Z)=>{const H=y.__vccOpts||y;for(const[j,L]of Z)H[j]=L;return H},v=y=>(pt("data-v-caf254db"),y=y(),_t(),y),vt={class:"font-oldstandardtt text-amber-950 text-lg"},gt=v(()=>t("div",{class:"flex justify-center"},[t("h1",{class:"mt-2 mb-2 font-bold text-4xl"},[Y(":"),t("span",{class:"ml-1"},":"),Y(" library_api "),t("span",{class:"mr-1"},":"),Y(":")])],-1)),yt={class:"flex justify-center"},xt={class:"mb-4 font-medium text-2xl"},$t=v(()=>t("i",{class:"mr-0.5"},"F",-1)),St={class:"flex justify-center"},wt={class:"tableStyle"},Mt=v(()=>t("tr",null,[t("th",{class:"p-1 bg-stone-300 font-semibold border-b border-stone-500 whitespace-nowrap"},"When ?"),t("th",{class:"thStyle"},"Method"),t("th",{class:"thStyle"},"URL"),t("th",{class:"thStyle"},"IP"),t("th",{class:"thStyle"},"Status")],-1)),kt={key:0},Dt=v(()=>t("td",{colspan:"5",class:"p-1 font-medium italic whitespace-nowrap"},"No Records Found!",-1)),Ot=[Dt],jt={class:"tdDatetimeStyle"},It={class:"tdMethodStyle"},zt={class:"tdUrlStyle"},Yt={key:0,class:"tdIpStyle"},Ht=["href"],Tt={key:1,class:"tdIpStyle"},At={class:"tdStatusStyle"},Lt={key:0},Nt=v(()=>t("div",{class:"flex justify-center"},[t("div",{class:"mt-5 relative z-0 w-[640px] h-20 bg-lime-100 border-2 border-lime-600 rounded-md shadow-md cursor-pointer",onclick:"window.open('https://owlsearch.games', '_blank')"},[t("img",{src:"https://owlsearch.games/images/logo/owl56.jpg",class:"absolute z-10 top-[9px] left-5 mt-[1px] p-[2px] block h-14 border-2 border-emerald-950 bg-black rounded-2xl opacity-95"}),t("span",{class:"absolute z-10 left-28 top-[3px] text-2xl font-bold font-sans text-green-900"},"Owl Search Games"),t("span",{class:"absolute z-10 left-28 top-[30px] text-base font-bold font-sans text-green-800"},"➥ Turbospeed your Brain!"),t("span",{class:"absolute z-10 left-28 top-[47px] text-base font-bold font-sans text-green-800"},"➥ Play without Ads!"),t("button",{class:"absolute left-[410px] top-[17px] z-20 px-9 py-2 bg-green-600 border border-green-900 rounded-lg text-lime-200 font-bold font-sans shadow-lg"},"➤ Play Now!"),t("div",{class:"motion-safe:animate-ping absolute z-10 left-[451px] top-[21px] w-[113px] h-[34px] bg-red-600/100 rounded-lg"})])],-1)),Ut=v(()=>t("div",{class:"flex justify-center"},[t("div",{class:"mt-0.5 mb-4 font-black text-xs uppercase"},"Please support our sponsor.")],-1)),Ct=[Nt,Ut],Pt={key:1,class:"flex justify-center"},Ft={class:"tableStyle"},Wt={class:"tdDatetimeStyle"},Bt={class:"tdMethodStyle"},Vt={class:"tdUrlStyle"},Et={key:0,class:"tdIpStyle"},Gt=["href"],Jt={key:1,class:"tdIpStyle"},Zt={class:"tdStatusStyle"},Rt={key:2},qt=v(()=>t("div",{class:"flex justify-center"},[t("div",{class:"mt-5 relative z-0 w-[640px] h-20 bg-lime-100 border-2 border-lime-600 rounded-md shadow-md cursor-pointer",onclick:"window.open('https://owlsearch.games', '_blank')"},[t("img",{src:"https://owlsearch.games/images/logo/owl56.jpg",class:"absolute z-10 top-[9px] left-5 mt-[1px] p-[2px] block h-14 border-2 border-emerald-950 bg-black rounded-2xl opacity-95"}),t("span",{class:"absolute z-10 left-28 top-[3px] text-2xl font-bold font-sans text-green-900"},"Owl Search Games"),t("span",{class:"absolute z-10 left-28 top-[30px] text-base font-bold font-sans text-green-800"},"➥ Turbospeed your Brain!"),t("span",{class:"absolute z-10 left-28 top-[47px] text-base font-bold font-sans text-green-800"},"➥ Play without Ads!"),t("button",{class:"absolute left-[410px] top-[17px] z-20 px-9 py-2 bg-green-600 border border-green-900 rounded-lg text-lime-200 font-bold font-sans shadow-lg"},"➤ Play Now!"),t("div",{class:"motion-safe:animate-ping absolute z-10 left-[451px] top-[21px] w-[113px] h-[34px] bg-red-600/100 rounded-lg"})])],-1)),Qt=v(()=>t("div",{class:"flex justify-center"},[t("div",{class:"mt-0.5 mb-4 font-black text-xs uppercase"},"Please support our sponsor.")],-1)),Kt=[qt,Qt],Xt={key:3,class:"flex justify-center"},te={class:"tableStyle"},ee={class:"tdDatetimeStyle"},se={class:"tdMethodStyle"},ne={class:"tdUrlStyle"},oe={key:0,class:"tdIpStyle"},ae=["href"],re={key:1,class:"tdIpStyle"},le={class:"tdStatusStyle"},ie={key:4},ce=v(()=>t("div",{class:"flex justify-center"},[t("div",{class:"mt-5 relative z-0 w-[640px] h-20 bg-lime-100 border-2 border-lime-600 rounded-md shadow-md cursor-pointer",onclick:"window.open('https://owlsearch.games', '_blank')"},[t("img",{src:"https://owlsearch.games/images/logo/owl56.jpg",class:"absolute z-10 top-[9px] left-5 mt-[1px] p-[2px] block h-14 border-2 border-emerald-950 bg-black rounded-2xl opacity-95"}),t("span",{class:"absolute z-10 left-28 top-[3px] text-2xl font-bold font-sans text-green-900"},"Owl Search Games"),t("span",{class:"absolute z-10 left-28 top-[30px] text-base font-bold font-sans text-green-800"},"➥ Turbospeed your Brain!"),t("span",{class:"absolute z-10 left-28 top-[47px] text-base font-bold font-sans text-green-800"},"➥ Play without Ads!"),t("button",{class:"absolute left-[410px] top-[17px] z-20 px-9 py-2 bg-green-600 border border-green-900 rounded-lg text-lime-200 font-bold font-sans shadow-lg"},"➤ Play Now!"),t("div",{class:"motion-safe:animate-ping absolute z-10 left-[451px] top-[21px] w-[113px] h-[34px] bg-red-600/100 rounded-lg"})])],-1)),ue=v(()=>t("div",{class:"flex justify-center"},[t("div",{class:"mt-0.5 font-black text-xs uppercase"},"Please support our sponsor.")],-1)),de=[ce,ue],he={class:"flex justify-center"},fe={class:"justify-center"},pe=v(()=>t("h4",{class:"mt-3 font-bold text-xl"},[Y("Statistics:"),t("span",{class:"ml-2 italic text-sm font-semibold"},"(updated hourly)")],-1)),_e={class:"font-normal"},me=v(()=>t("i",null,"category_count:",-1)),be={class:"ml-0.5 font-semibold"},ve={class:"font-normal"},ge=v(()=>t("i",null,"book_count:",-1)),ye={class:"ml-0.5 font-semibold"},xe={class:"font-normal"},$e=v(()=>t("i",null,"exemplar_count:",-1)),Se={class:"ml-0.5 font-semibold"},we={class:"font-normal"},Me=v(()=>t("i",null,"database_size:",-1)),ke={class:"ml-0.5 font-semibold"},De=v(()=>t("div",{class:"flex justify-center text-sm font-medium italic"},"This site is best wiewed on a larger screen, like either from a laptop or desktop.",-1)),Oe=v(()=>t("div",{class:"h-16"},null,-1)),je={__name:"Index",props:{ip_list:Object},setup(y){const Z=new Intl.NumberFormat("en-US",{style:"currency",currency:"USD"});var H=null,j=null,L=null;const c=B(null),S=B(null),M=B(null),k=B(null),D=B(""),I=B(null),x=()=>{axios.get("/api/money").then(p=>{D.value=Z.format(p.data.money/100)}).catch(function(p){console.log(p)})},E=()=>{axios.get("/api/monitor").then(p=>{c.value=it(p.data.data),c.value.length<60?(S.value=c.value.slice(),M.value=null,k.value=null):c.value.length>=60&&c.value.length<90?(S.value=c.value.slice(0,Math.floor(c.value.length/2)),M.value=c.value.slice(Math.floor(c.value.length/2,c.value.length)),k.value=null):(S.value=c.value.slice(0,Math.floor(c.value.length/3)),M.value=c.value.slice(Math.floor(c.value.length/3),Math.floor(c.value.length*2/3)),k.value=c.value.slice(Math.floor(c.value.length*2/3,c.value.length)))}).catch(function(p){console.log(p)})},O=()=>{axios.get("/api/count").then(p=>{I.value=it(p.data.data)}).catch(function(p){console.log(p)})};return ht(()=>{x(),E(),O(),H=setInterval(E,5e3),j=setInterval(x,3500),L=setInterval(O,6e4)}),ft(()=>{clearInterval(H),clearInterval(j),clearInterval(L)}),(p,et)=>{var R,q,Q,P,K,T,z,F,N,h;return _(),m("div",vt,[gt,t("div",yt,[t("h3",xt,[Y("Accumulated: "),$t,Y(d(D.value),1)])]),t("div",St,[t("table",wt,[Mt,((R=S.value)==null?void 0:R.length)===0?(_(),m("tr",kt,Ot)):V("",!0),(_(!0),m(st,null,nt(S.value,o=>(_(),m("tr",{key:o.id,class:ot({"bg-stone-200":o.id%6>2})},[t("td",jt,d(at(rt)(o.datetime+"+00:00").format("YYYY/MM/DD HH:mm:ss")),1),t("td",It,d(o.method),1),t("td",zt,d(o.url),1),o.ip in y.ip_list?(_(),m("td",Yt,[t("a",{href:y.ip_list[o.ip],target:"_blank"},d(o.ip),9,Ht)])):(_(),m("td",Tt,d(o.ip),1)),t("td",At,d(o.status),1)],2))),128))])]),((q=c.value)==null?void 0:q.length)>=60?(_(),m("div",Lt,Ct)):V("",!0),((Q=c.value)==null?void 0:Q.length)>=60?(_(),m("div",Pt,[t("table",Ft,[(_(!0),m(st,null,nt(M.value,o=>(_(),m("tr",{key:o.id,class:ot({"bg-stone-200":o.id%6>2})},[t("td",Wt,d(at(rt)(o.datetime+"+00:00").format("YYYY/MM/DD HH:mm:ss")),1),t("td",Bt,d(o.method),1),t("td",Vt,d(o.url),1),o.ip in y.ip_list?(_(),m("td",Et,[t("a",{href:y.ip_list[o.ip],target:"_blank"},d(o.ip),9,Gt)])):(_(),m("td",Jt,d(o.ip),1)),t("td",Zt,d(o.status),1)],2))),128))])])):V("",!0),((P=c.value)==null?void 0:P.length)>=90?(_(),m("div",Rt,Kt)):V("",!0),((K=c.value)==null?void 0:K.length)>=90?(_(),m("div",Xt,[t("table",te,[(_(!0),m(st,null,nt(k.value,o=>(_(),m("tr",{key:o.id,class:ot({"bg-stone-200":o.id%6>2})},[t("td",ee,d(at(rt)(o.datetime+"+00:00").format("YYYY/MM/DD HH:mm:ss")),1),t("td",se,d(o.method),1),t("td",ne,d(o.url),1),o.ip in y.ip_list?(_(),m("td",oe,[t("a",{href:y.ip_list[o.ip],target:"_blank"},d(o.ip),9,ae)])):(_(),m("td",re,d(o.ip),1)),t("td",le,d(o.status),1)],2))),128))])])):V("",!0),((T=c.value)==null?void 0:T.length)<60?(_(),m("div",ie,de)):V("",!0),t("div",he,[t("div",fe,[pe,t("p",_e,[me,Y(),t("span",be,d((z=I.value)==null?void 0:z.category_count),1)]),t("p",ve,[ge,Y(),t("span",ye,d((F=I.value)==null?void 0:F.book_count),1)]),t("p",xe,[$e,Y(),t("span",Se,d((N=I.value)==null?void 0:N.exemplar_count),1)]),t("p",we,[Me,Y(),t("span",ke,d((h=I.value)==null?void 0:h.mysql_count),1)])])]),De,Oe])}}},ze=bt(je,[["__scopeId","data-v-caf254db"]]);export{ze as default};