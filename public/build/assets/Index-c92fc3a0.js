import{c as ut,g as dt,r as V,o as ht,a as ft,b as m,d as b,e,f as y,t as u,h as q,F as nt,i as at,j as it,n as ot,u as rt,p as pt,k as _t}from"./app-d6069beb.js";var ct={exports:{}};(function(x,R){(function(T,j){x.exports=j()})(ut,function(){var T=1e3,j=6e4,U=36e5,c="millisecond",M="second",D="minute",z="hour",I="day",$="week",w="month",E="quarter",O="year",_="date",st="Invalid Date",Q=/^(\d{4})[-/]?(\d{1,2})?[-/]?(\d{0,2})[Tt\s]*(\d{1,2})?:?(\d{1,2})?:?(\d{1,2})?[.:]?(\d+)?$/,K=/\[([^\]]+)]|Y{1,4}|M{1,4}|D{1,2}|d{1,4}|H{1,2}|h{1,2}|a|A|m{1,2}|s{1,2}|Z{1,2}|SSS/g,X={name:"en",weekdays:"Sunday_Monday_Tuesday_Wednesday_Thursday_Friday_Saturday".split("_"),months:"January_February_March_April_May_June_July_August_September_October_November_December".split("_"),ordinal:function(o){var t=["th","st","nd","rd"],s=o%100;return"["+o+(t[(s-20)%10]||t[s]||t[0])+"]"}},F=function(o,t,s){var a=String(o);return!a||a.length>=t?o:""+Array(t+1-a.length).join(s)+o},tt={s:F,z:function(o){var t=-o.utcOffset(),s=Math.abs(t),a=Math.floor(s/60),n=s%60;return(t<=0?"+":"-")+F(a,2,"0")+":"+F(n,2,"0")},m:function o(t,s){if(t.date()<s.date())return-o(s,t);var a=12*(s.year()-t.year())+(s.month()-t.month()),n=t.clone().add(a,w),r=s-n<0,l=t.clone().add(a+(r?-1:1),w);return+(-(a+(s-n)/(r?n-l:l-n))||0)},a:function(o){return o<0?Math.ceil(o)||0:Math.floor(o)},p:function(o){return{M:w,y:O,w:$,d:I,D:_,h:z,m:D,s:M,ms:c,Q:E}[o]||String(o||"").toLowerCase().replace(/s$/,"")},u:function(o){return o===void 0}},H="en",Y={};Y[H]=X;var W=function(o){return o instanceof N},A=function o(t,s,a){var n;if(!t)return H;if(typeof t=="string"){var r=t.toLowerCase();Y[r]&&(n=r),s&&(Y[r]=s,n=r);var l=t.split("-");if(!n&&l.length>1)return o(l[0])}else{var d=t.name;Y[d]=t,n=d}return!a&&n&&(H=n),n||!a&&H},f=function(o,t){if(W(o))return o.clone();var s=typeof t=="object"?t:{};return s.date=o,s.args=arguments,new N(s)},i=tt;i.l=A,i.i=W,i.w=function(o,t){return f(o,{locale:t.$L,utc:t.$u,x:t.$x,$offset:t.$offset})};var N=function(){function o(s){this.$L=A(s.locale,null,!0),this.parse(s)}var t=o.prototype;return t.parse=function(s){this.$d=function(a){var n=a.date,r=a.utc;if(n===null)return new Date(NaN);if(i.u(n))return new Date;if(n instanceof Date)return new Date(n);if(typeof n=="string"&&!/Z$/i.test(n)){var l=n.match(Q);if(l){var d=l[2]-1||0,p=(l[7]||"0").substring(0,3);return r?new Date(Date.UTC(l[1],d,l[3]||1,l[4]||0,l[5]||0,l[6]||0,p)):new Date(l[1],d,l[3]||1,l[4]||0,l[5]||0,l[6]||0,p)}}return new Date(n)}(s),this.$x=s.x||{},this.init()},t.init=function(){var s=this.$d;this.$y=s.getFullYear(),this.$M=s.getMonth(),this.$D=s.getDate(),this.$W=s.getDay(),this.$H=s.getHours(),this.$m=s.getMinutes(),this.$s=s.getSeconds(),this.$ms=s.getMilliseconds()},t.$utils=function(){return i},t.isValid=function(){return this.$d.toString()!==st},t.isSame=function(s,a){var n=f(s);return this.startOf(a)<=n&&n<=this.endOf(a)},t.isAfter=function(s,a){return f(s)<this.startOf(a)},t.isBefore=function(s,a){return this.endOf(a)<f(s)},t.$g=function(s,a,n){return i.u(s)?this[a]:this.set(n,s)},t.unix=function(){return Math.floor(this.valueOf()/1e3)},t.valueOf=function(){return this.$d.getTime()},t.startOf=function(s,a){var n=this,r=!!i.u(a)||a,l=i.p(s),d=function(P,S){var L=i.w(n.$u?Date.UTC(n.$y,S,P):new Date(n.$y,S,P),n);return r?L:L.endOf(I)},p=function(P,S){return i.w(n.toDate()[P].apply(n.toDate("s"),(r?[0,0,0,0]:[23,59,59,999]).slice(S)),n)},g=this.$W,v=this.$M,k=this.$D,B="set"+(this.$u?"UTC":"");switch(l){case O:return r?d(1,0):d(31,11);case w:return r?d(1,v):d(0,v+1);case $:var C=this.$locale().weekStart||0,J=(g<C?g+7:g)-C;return d(r?k-J:k+(6-J),v);case I:case _:return p(B+"Hours",0);case z:return p(B+"Minutes",1);case D:return p(B+"Seconds",2);case M:return p(B+"Milliseconds",3);default:return this.clone()}},t.endOf=function(s){return this.startOf(s,!1)},t.$set=function(s,a){var n,r=i.p(s),l="set"+(this.$u?"UTC":""),d=(n={},n[I]=l+"Date",n[_]=l+"Date",n[w]=l+"Month",n[O]=l+"FullYear",n[z]=l+"Hours",n[D]=l+"Minutes",n[M]=l+"Seconds",n[c]=l+"Milliseconds",n)[r],p=r===I?this.$D+(a-this.$W):a;if(r===w||r===O){var g=this.clone().set(_,1);g.$d[d](p),g.init(),this.$d=g.set(_,Math.min(this.$D,g.daysInMonth())).$d}else d&&this.$d[d](p);return this.init(),this},t.set=function(s,a){return this.clone().$set(s,a)},t.get=function(s){return this[i.p(s)]()},t.add=function(s,a){var n,r=this;s=Number(s);var l=i.p(a),d=function(v){var k=f(r);return i.w(k.date(k.date()+Math.round(v*s)),r)};if(l===w)return this.set(w,this.$M+s);if(l===O)return this.set(O,this.$y+s);if(l===I)return d(1);if(l===$)return d(7);var p=(n={},n[D]=j,n[z]=U,n[M]=T,n)[l]||1,g=this.$d.getTime()+s*p;return i.w(g,this)},t.subtract=function(s,a){return this.add(-1*s,a)},t.format=function(s){var a=this,n=this.$locale();if(!this.isValid())return n.invalidDate||st;var r=s||"YYYY-MM-DDTHH:mm:ssZ",l=i.z(this),d=this.$H,p=this.$m,g=this.$M,v=n.weekdays,k=n.months,B=n.meridiem,C=function(S,L,Z,et){return S&&(S[L]||S(a,r))||Z[L].slice(0,et)},J=function(S){return i.s(d%12||12,S,"0")},P=B||function(S,L,Z){var et=S<12?"AM":"PM";return Z?et.toLowerCase():et};return r.replace(K,function(S,L){return L||function(Z){switch(Z){case"YY":return String(a.$y).slice(-2);case"YYYY":return i.s(a.$y,4,"0");case"M":return g+1;case"MM":return i.s(g+1,2,"0");case"MMM":return C(n.monthsShort,g,k,3);case"MMMM":return C(k,g);case"D":return a.$D;case"DD":return i.s(a.$D,2,"0");case"d":return String(a.$W);case"dd":return C(n.weekdaysMin,a.$W,v,2);case"ddd":return C(n.weekdaysShort,a.$W,v,3);case"dddd":return v[a.$W];case"H":return String(d);case"HH":return i.s(d,2,"0");case"h":return J(1);case"hh":return J(2);case"a":return P(d,p,!0);case"A":return P(d,p,!1);case"m":return String(p);case"mm":return i.s(p,2,"0");case"s":return String(a.$s);case"ss":return i.s(a.$s,2,"0");case"SSS":return i.s(a.$ms,3,"0");case"Z":return l}return null}(S)||l.replace(":","")})},t.utcOffset=function(){return 15*-Math.round(this.$d.getTimezoneOffset()/15)},t.diff=function(s,a,n){var r,l=this,d=i.p(a),p=f(s),g=(p.utcOffset()-this.utcOffset())*j,v=this-p,k=function(){return i.m(l,p)};switch(d){case O:r=k()/12;break;case w:r=k();break;case E:r=k()/3;break;case $:r=(v-g)/6048e5;break;case I:r=(v-g)/864e5;break;case z:r=v/U;break;case D:r=v/j;break;case M:r=v/T;break;default:r=v}return n?r:i.a(r)},t.daysInMonth=function(){return this.endOf(w).$D},t.$locale=function(){return Y[this.$L]},t.locale=function(s,a){if(!s)return this.$L;var n=this.clone(),r=A(s,a,!0);return r&&(n.$L=r),n},t.clone=function(){return i.w(this.$d,this)},t.toDate=function(){return new Date(this.valueOf())},t.toJSON=function(){return this.isValid()?this.toISOString():null},t.toISOString=function(){return this.$d.toISOString()},t.toString=function(){return this.$d.toUTCString()},o}(),G=N.prototype;return f.prototype=G,[["$ms",c],["$s",M],["$m",D],["$H",z],["$W",I],["$M",w],["$y",O],["$D",_]].forEach(function(o){G[o[1]]=function(t){return this.$g(t,o[0],o[1])}}),f.extend=function(o,t){return o.$i||(o(t,N,f),o.$i=!0),f},f.locale=A,f.isDayjs=W,f.unix=function(o){return f(1e3*o)},f.en=Y[H],f.Ls=Y,f.p={},f})})(ct);var mt=ct.exports;const lt=dt(mt);const bt=(x,R)=>{const T=x.__vccOpts||x;for(const[j,U]of R)T[j]=U;return T},h=x=>(pt("data-v-8ef8757c"),x=x(),_t(),x),gt={class:"font-oldstandardtt text-amber-950 text-lg"},yt=h(()=>e("div",{class:"flex justify-center"},[e("h1",{class:"mt-2 mb-2 font-bold text-4xl"},[y(":"),e("span",{class:"ml-1"},":"),y(" library_api "),e("span",{class:"mr-1"},":"),y(":")])],-1)),vt={class:"flex justify-center"},xt={class:"mb-4 w-[840px] font-medium"},$t=h(()=>e("span",{class:"italic text-sm font-medium"},"(These statistics are updated hourly.)",-1)),wt=h(()=>e("br",null,null,-1)),St=h(()=>e("a",{href:"/docs",target:"_blank",class:"underline"},"here",-1)),Mt=h(()=>e("br",null,null,-1)),kt=h(()=>e("a",{href:"https://genericapiclient.xyz",target:"_blank",class:"underline"},"here",-1)),Dt=h(()=>e("a",{href:"https://genericapi.xyz",target:"_blank",class:"underline"},"here",-1)),zt=h(()=>e("br",null,null,-1)),It={class:"flex justify-center"},Ot={class:"tableStyle"},jt=h(()=>e("tr",null,[e("th",{class:"p-1 bg-stone-300 font-semibold border-b border-stone-500 whitespace-nowrap"},"When ?"),e("th",{class:"thStyle"},"Method"),e("th",{class:"thStyle"},"URL"),e("th",{class:"thStyle"},"IP"),e("th",{class:"thStyle"},"Status")],-1)),Yt={key:0},Tt=h(()=>e("td",{colspan:"5",class:"p-1 font-medium italic whitespace-nowrap"},"No Records Found!",-1)),Ht=[Tt],Lt={class:"tdDatetimeStyle"},Ut={class:"tdMethodStyle"},At={class:"tdUrlStyle"},Nt={key:0,class:"tdIpStyle"},Ct=["href"],Pt={key:1,class:"tdIpStyle"},Ft={class:"tdStatusStyle"},Wt={key:0},Bt=h(()=>e("div",{class:"flex justify-center"},[e("div",{class:"mt-5 relative z-0 w-[640px] h-20 bg-lime-100 border-2 border-lime-600 rounded-md shadow-md cursor-pointer",onclick:"window.open('https://owlsearch.games', '_blank')"},[e("img",{src:"https://owlsearch.games/images/logo/owl56.jpg",class:"absolute z-10 top-[9px] left-5 mt-[1px] p-[2px] block h-14 border-2 border-emerald-950 bg-black rounded-2xl opacity-95"}),e("span",{class:"absolute z-10 left-28 top-[3px] text-2xl font-bold font-sans text-green-900"},"Owl Search Games"),e("span",{class:"absolute z-10 left-28 top-[30px] text-base font-bold font-sans text-green-800"},"➥ Turbospeed your Brain!"),e("span",{class:"absolute z-10 left-28 top-[47px] text-base font-bold font-sans text-green-800"},"➥ Play without Ads!"),e("button",{class:"absolute left-[410px] top-[17px] z-20 px-9 py-2 bg-green-600 border border-green-900 rounded-lg text-lime-200 font-bold font-sans shadow-lg"},"➤ Play Now!"),e("div",{class:"motion-safe:animate-ping absolute z-10 left-[451px] top-[21px] w-[113px] h-[34px] bg-red-600/100 rounded-lg"})])],-1)),Vt=h(()=>e("div",{class:"flex justify-center"},[e("div",{class:"mt-0.5 mb-4 font-black text-xs uppercase"},"Please support our sponsor.")],-1)),qt=[Bt,Vt],Et={key:1,class:"flex justify-center"},Gt={class:"tableStyle"},Jt={class:"tdDatetimeStyle"},Zt={class:"tdMethodStyle"},Rt={class:"tdUrlStyle"},Qt={key:0,class:"tdIpStyle"},Kt=["href"],Xt={key:1,class:"tdIpStyle"},te={class:"tdStatusStyle"},ee={key:2},se=h(()=>e("div",{class:"flex justify-center"},[e("div",{class:"mt-5 relative z-0 w-[640px] h-20 bg-lime-100 border-2 border-lime-600 rounded-md shadow-md cursor-pointer",onclick:"window.open('https://owlsearch.games', '_blank')"},[e("img",{src:"https://owlsearch.games/images/logo/owl56.jpg",class:"absolute z-10 top-[9px] left-5 mt-[1px] p-[2px] block h-14 border-2 border-emerald-950 bg-black rounded-2xl opacity-95"}),e("span",{class:"absolute z-10 left-28 top-[3px] text-2xl font-bold font-sans text-green-900"},"Owl Search Games"),e("span",{class:"absolute z-10 left-28 top-[30px] text-base font-bold font-sans text-green-800"},"➥ Turbospeed your Brain!"),e("span",{class:"absolute z-10 left-28 top-[47px] text-base font-bold font-sans text-green-800"},"➥ Play without Ads!"),e("button",{class:"absolute left-[410px] top-[17px] z-20 px-9 py-2 bg-green-600 border border-green-900 rounded-lg text-lime-200 font-bold font-sans shadow-lg"},"➤ Play Now!"),e("div",{class:"motion-safe:animate-ping absolute z-10 left-[451px] top-[21px] w-[113px] h-[34px] bg-red-600/100 rounded-lg"})])],-1)),ne=h(()=>e("div",{class:"flex justify-center"},[e("div",{class:"mt-0.5 mb-4 font-black text-xs uppercase"},"Please support our sponsor.")],-1)),ae=[se,ne],oe={key:3,class:"flex justify-center"},re={class:"tableStyle"},le={class:"tdDatetimeStyle"},ie={class:"tdMethodStyle"},ce={class:"tdUrlStyle"},ue={key:0,class:"tdIpStyle"},de=["href"],he={key:1,class:"tdIpStyle"},fe={class:"tdStatusStyle"},pe={key:4},_e=h(()=>e("div",{class:"flex justify-center"},[e("div",{class:"mt-5 relative z-0 w-[640px] h-20 bg-lime-100 border-2 border-lime-600 rounded-md shadow-md cursor-pointer",onclick:"window.open('https://owlsearch.games', '_blank')"},[e("img",{src:"https://owlsearch.games/images/logo/owl56.jpg",class:"absolute z-10 top-[9px] left-5 mt-[1px] p-[2px] block h-14 border-2 border-emerald-950 bg-black rounded-2xl opacity-95"}),e("span",{class:"absolute z-10 left-28 top-[3px] text-2xl font-bold font-sans text-green-900"},"Owl Search Games"),e("span",{class:"absolute z-10 left-28 top-[30px] text-base font-bold font-sans text-green-800"},"➥ Turbospeed your Brain!"),e("span",{class:"absolute z-10 left-28 top-[47px] text-base font-bold font-sans text-green-800"},"➥ Play without Ads!"),e("button",{class:"absolute left-[410px] top-[17px] z-20 px-9 py-2 bg-green-600 border border-green-900 rounded-lg text-lime-200 font-bold font-sans shadow-lg"},"➤ Play Now!"),e("div",{class:"motion-safe:animate-ping absolute z-10 left-[451px] top-[21px] w-[113px] h-[34px] bg-red-600/100 rounded-lg"})])],-1)),me=h(()=>e("div",{class:"flex justify-center"},[e("div",{class:"mt-0.5 font-black text-xs uppercase"},"Please support our sponsor.")],-1)),be=[_e,me],ge={class:"flex justify-center"},ye={class:"justify-center"},ve=h(()=>e("h4",{class:"mt-3 font-bold text-xl"},[y("Statistics:"),e("span",{class:"ml-2 italic text-sm font-semibold"},"(updated hourly)")],-1)),xe={class:"font-normal"},$e=h(()=>e("i",null,"category_count:",-1)),we={class:"ml-0.5 font-semibold"},Se={class:"font-normal"},Me=h(()=>e("i",null,"book_count:",-1)),ke={class:"ml-0.5 font-semibold"},De={class:"font-normal"},ze=h(()=>e("i",null,"exemplar_count:",-1)),Ie={class:"ml-0.5 font-semibold"},Oe={class:"font-normal"},je=h(()=>e("i",null,"database_size:",-1)),Ye={class:"ml-0.5 font-semibold"},Te=h(()=>e("div",{class:"flex justify-center"},[e("p",{class:"text-sm font-medium italic"},[y(" This site is best wiewed on a larger screen, such as either from a laptop or desktop. "),e("br"),e("br"),y(" Also, we may change domain names, so please check "),e("a",{href:"https://github.com/alexni09/library_api",target:"_blank",class:"underline"},"this github repository"),y(" to find out where this app is hosted or where it will be hosted next. ")])],-1)),He=h(()=>e("div",{class:"h-16"},null,-1)),Le={__name:"Index",props:{ip_list:Object},setup(x){const R=new Intl.NumberFormat("en-US",{style:"currency",currency:"USD"});var T=null,j=null,U=null;const c=V(null),M=V(null),D=V(null),z=V(null),I=V(""),$=V(null),w=()=>{axios.get("/api/money").then(_=>{I.value=R.format(_.data.money/100)}).catch(function(_){console.log(_)})},E=()=>{axios.get("/api/monitor").then(_=>{c.value=it(_.data.data),c.value.length<60?(M.value=c.value.slice(),D.value=null,z.value=null):c.value.length>=60&&c.value.length<90?(M.value=c.value.slice(0,Math.floor(c.value.length/2)),D.value=c.value.slice(Math.floor(c.value.length/2,c.value.length)),z.value=null):(M.value=c.value.slice(0,Math.floor(c.value.length/3)),D.value=c.value.slice(Math.floor(c.value.length/3),Math.floor(c.value.length*2/3)),z.value=c.value.slice(Math.floor(c.value.length*2/3,c.value.length)))}).catch(function(_){console.log(_)})},O=()=>{axios.get("/api/count").then(_=>{$.value=it(_.data.data)}).catch(function(_){console.log(_)})};return ht(()=>{w(),E(),O(),T=setInterval(E,5e3),j=setInterval(w,3500),U=setInterval(O,6e4)}),ft(()=>{clearInterval(T),clearInterval(j),clearInterval(U)}),(_,st)=>{var Q,K,X,F,tt,H,Y,W,A,f,i,N,G,o;return m(),b("div",gt,[yt,e("div",vt,[e("p",xt,[y(" This is a digital library with fictitious data. Its aim is to demonstrate the fastness and robustness of the api responses over a very big database, which currently holds: "+u((Q=$.value)==null?void 0:Q.category_count.toLocaleString("en-US"))+" categories, "+u((K=$.value)==null?void 0:K.book_count.toLocaleString("en-US"))+" books, "+u((X=$.value)==null?void 0:X.exemplar_count.toLocaleString("en-US"))+" exemplars; and is "+u((F=$.value)==null?void 0:F.mysql_count)+" big. The api customers pay after returning the borrowed book; and when that happens this library makes money. Until now, it has accumulated F"+u(I.value)+". ",1),$t,wt,y(" The api documentation can be accessed "),St,y(". "),Mt,y(" There are two clients for this library. They can be accessed "),kt,y(" and "),Dt,y(". "),zt,y(" These are the latest api requests, in real time: ")])]),e("div",It,[e("table",Ot,[jt,((tt=M.value)==null?void 0:tt.length)===0?(m(),b("tr",Yt,Ht)):q("",!0),(m(!0),b(nt,null,at(M.value,t=>(m(),b("tr",{key:t.id,class:ot({"bg-stone-200":t.id%6>2})},[e("td",Lt,u(rt(lt)(t.datetime+"+00:00").format("YYYY/MM/DD HH:mm:ss")),1),e("td",Ut,u(t.method),1),e("td",At,u(t.url),1),t.ip in x.ip_list?(m(),b("td",Nt,[e("a",{href:x.ip_list[t.ip],target:"_blank"},u(t.ip),9,Ct)])):(m(),b("td",Pt,u(t.ip),1)),e("td",Ft,u(t.status),1)],2))),128))])]),((H=c.value)==null?void 0:H.length)>=60?(m(),b("div",Wt,qt)):q("",!0),((Y=c.value)==null?void 0:Y.length)>=60?(m(),b("div",Et,[e("table",Gt,[(m(!0),b(nt,null,at(D.value,t=>(m(),b("tr",{key:t.id,class:ot({"bg-stone-200":t.id%6>2})},[e("td",Jt,u(rt(lt)(t.datetime+"+00:00").format("YYYY/MM/DD HH:mm:ss")),1),e("td",Zt,u(t.method),1),e("td",Rt,u(t.url),1),t.ip in x.ip_list?(m(),b("td",Qt,[e("a",{href:x.ip_list[t.ip],target:"_blank"},u(t.ip),9,Kt)])):(m(),b("td",Xt,u(t.ip),1)),e("td",te,u(t.status),1)],2))),128))])])):q("",!0),((W=c.value)==null?void 0:W.length)>=90?(m(),b("div",ee,ae)):q("",!0),((A=c.value)==null?void 0:A.length)>=90?(m(),b("div",oe,[e("table",re,[(m(!0),b(nt,null,at(z.value,t=>(m(),b("tr",{key:t.id,class:ot({"bg-stone-200":t.id%6>2})},[e("td",le,u(rt(lt)(t.datetime+"+00:00").format("YYYY/MM/DD HH:mm:ss")),1),e("td",ie,u(t.method),1),e("td",ce,u(t.url),1),t.ip in x.ip_list?(m(),b("td",ue,[e("a",{href:x.ip_list[t.ip],target:"_blank"},u(t.ip),9,de)])):(m(),b("td",he,u(t.ip),1)),e("td",fe,u(t.status),1)],2))),128))])])):q("",!0),((f=c.value)==null?void 0:f.length)<60?(m(),b("div",pe,be)):q("",!0),e("div",ge,[e("div",ye,[ve,e("p",xe,[$e,y(),e("span",we,u((i=$.value)==null?void 0:i.category_count),1)]),e("p",Se,[Me,y(),e("span",ke,u((N=$.value)==null?void 0:N.book_count),1)]),e("p",De,[ze,y(),e("span",Ie,u((G=$.value)==null?void 0:G.exemplar_count),1)]),e("p",Oe,[je,y(),e("span",Ye,u((o=$.value)==null?void 0:o.mysql_count),1)])])]),Te,He])}}},Ae=bt(Le,[["__scopeId","data-v-8ef8757c"]]);export{Ae as default};
