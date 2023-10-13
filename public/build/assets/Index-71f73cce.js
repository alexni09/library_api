import{c as ut,g as dt,r as B,o as ht,a as ft,b as m,d as _,e,f as Y,t as d,h as V,F as st,i as at,j as pt,k as lt,n as nt,u as ot,p as mt,l as _t}from"./app-989bb6dd.js";var ct={exports:{}};(function(y,Z){(function(T,O){y.exports=O()})(ut,function(){var T=1e3,O=6e4,L=36e5,c="millisecond",$="second",M="minute",k="hour",D="day",z="week",x="month",E="quarter",I="year",p="date",et="Invalid Date",q=/^(\d{4})[-/]?(\d{1,2})?[-/]?(\d{0,2})[Tt\s]*(\d{1,2})?:?(\d{1,2})?:?(\d{1,2})?[.:]?(\d+)?$/,R=/\[([^\]]+)]|Y{1,4}|M{1,4}|D{1,2}|d{1,4}|H{1,2}|h{1,2}|a|A|m{1,2}|s{1,2}|Z{1,2}|SSS/g,Q={name:"en",weekdays:"Sunday_Monday_Tuesday_Wednesday_Thursday_Friday_Saturday".split("_"),months:"January_February_March_April_May_June_July_August_September_October_November_December".split("_"),ordinal:function(r){var a=["th","st","nd","rd"],t=r%100;return"["+r+(a[(t-20)%10]||a[t]||a[0])+"]"}},P=function(r,a,t){var o=String(r);return!o||o.length>=a?r:""+Array(a+1-o.length).join(t)+r},K={s:P,z:function(r){var a=-r.utcOffset(),t=Math.abs(a),o=Math.floor(t/60),s=t%60;return(a<=0?"+":"-")+P(o,2,"0")+":"+P(s,2,"0")},m:function r(a,t){if(a.date()<t.date())return-r(t,a);var o=12*(t.year()-a.year())+(t.month()-a.month()),s=a.clone().add(o,x),i=t-s<0,l=a.clone().add(o+(i?-1:1),x);return+(-(o+(t-s)/(i?s-l:l-s))||0)},a:function(r){return r<0?Math.ceil(r)||0:Math.floor(r)},p:function(r){return{M:x,y:I,w:z,d:D,D:p,h:k,m:M,s:$,ms:c,Q:E}[r]||String(r||"").toLowerCase().replace(/s$/,"")},u:function(r){return r===void 0}},H="en",j={};j[H]=Q;var F=function(r){return r instanceof X},U=function r(a,t,o){var s;if(!a)return H;if(typeof a=="string"){var i=a.toLowerCase();j[i]&&(s=i),t&&(j[i]=t,s=i);var l=a.split("-");if(!s&&l.length>1)return r(l[0])}else{var u=a.name;j[u]=a,s=u}return!o&&s&&(H=s),s||!o&&H},h=function(r,a){if(F(r))return r.clone();var t=typeof a=="object"?a:{};return t.date=r,t.args=arguments,new X(t)},n=K;n.l=U,n.i=F,n.w=function(r,a){return h(r,{locale:a.$L,utc:a.$u,x:a.$x,$offset:a.$offset})};var X=function(){function r(t){this.$L=U(t.locale,null,!0),this.parse(t)}var a=r.prototype;return a.parse=function(t){this.$d=function(o){var s=o.date,i=o.utc;if(s===null)return new Date(NaN);if(n.u(s))return new Date;if(s instanceof Date)return new Date(s);if(typeof s=="string"&&!/Z$/i.test(s)){var l=s.match(q);if(l){var u=l[2]-1||0,f=(l[7]||"0").substring(0,3);return i?new Date(Date.UTC(l[1],u,l[3]||1,l[4]||0,l[5]||0,l[6]||0,f)):new Date(l[1],u,l[3]||1,l[4]||0,l[5]||0,l[6]||0,f)}}return new Date(s)}(t),this.$x=t.x||{},this.init()},a.init=function(){var t=this.$d;this.$y=t.getFullYear(),this.$M=t.getMonth(),this.$D=t.getDate(),this.$W=t.getDay(),this.$H=t.getHours(),this.$m=t.getMinutes(),this.$s=t.getSeconds(),this.$ms=t.getMilliseconds()},a.$utils=function(){return n},a.isValid=function(){return this.$d.toString()!==et},a.isSame=function(t,o){var s=h(t);return this.startOf(o)<=s&&s<=this.endOf(o)},a.isAfter=function(t,o){return h(t)<this.startOf(o)},a.isBefore=function(t,o){return this.endOf(o)<h(t)},a.$g=function(t,o,s){return n.u(t)?this[o]:this.set(s,t)},a.unix=function(){return Math.floor(this.valueOf()/1e3)},a.valueOf=function(){return this.$d.getTime()},a.startOf=function(t,o){var s=this,i=!!n.u(o)||o,l=n.p(t),u=function(C,w){var N=n.w(s.$u?Date.UTC(s.$y,w,C):new Date(s.$y,w,C),s);return i?N:N.endOf(D)},f=function(C,w){return n.w(s.toDate()[C].apply(s.toDate("s"),(i?[0,0,0,0]:[23,59,59,999]).slice(w)),s)},b=this.$W,g=this.$M,S=this.$D,W="set"+(this.$u?"UTC":"");switch(l){case I:return i?u(1,0):u(31,11);case x:return i?u(1,g):u(0,g+1);case z:var A=this.$locale().weekStart||0,G=(b<A?b+7:b)-A;return u(i?S-G:S+(6-G),g);case D:case p:return f(W+"Hours",0);case k:return f(W+"Minutes",1);case M:return f(W+"Seconds",2);case $:return f(W+"Milliseconds",3);default:return this.clone()}},a.endOf=function(t){return this.startOf(t,!1)},a.$set=function(t,o){var s,i=n.p(t),l="set"+(this.$u?"UTC":""),u=(s={},s[D]=l+"Date",s[p]=l+"Date",s[x]=l+"Month",s[I]=l+"FullYear",s[k]=l+"Hours",s[M]=l+"Minutes",s[$]=l+"Seconds",s[c]=l+"Milliseconds",s)[i],f=i===D?this.$D+(o-this.$W):o;if(i===x||i===I){var b=this.clone().set(p,1);b.$d[u](f),b.init(),this.$d=b.set(p,Math.min(this.$D,b.daysInMonth())).$d}else u&&this.$d[u](f);return this.init(),this},a.set=function(t,o){return this.clone().$set(t,o)},a.get=function(t){return this[n.p(t)]()},a.add=function(t,o){var s,i=this;t=Number(t);var l=n.p(o),u=function(g){var S=h(i);return n.w(S.date(S.date()+Math.round(g*t)),i)};if(l===x)return this.set(x,this.$M+t);if(l===I)return this.set(I,this.$y+t);if(l===D)return u(1);if(l===z)return u(7);var f=(s={},s[M]=O,s[k]=L,s[$]=T,s)[l]||1,b=this.$d.getTime()+t*f;return n.w(b,this)},a.subtract=function(t,o){return this.add(-1*t,o)},a.format=function(t){var o=this,s=this.$locale();if(!this.isValid())return s.invalidDate||et;var i=t||"YYYY-MM-DDTHH:mm:ssZ",l=n.z(this),u=this.$H,f=this.$m,b=this.$M,g=s.weekdays,S=s.months,W=s.meridiem,A=function(w,N,J,tt){return w&&(w[N]||w(o,i))||J[N].slice(0,tt)},G=function(w){return n.s(u%12||12,w,"0")},C=W||function(w,N,J){var tt=w<12?"AM":"PM";return J?tt.toLowerCase():tt};return i.replace(R,function(w,N){return N||function(J){switch(J){case"YY":return String(o.$y).slice(-2);case"YYYY":return n.s(o.$y,4,"0");case"M":return b+1;case"MM":return n.s(b+1,2,"0");case"MMM":return A(s.monthsShort,b,S,3);case"MMMM":return A(S,b);case"D":return o.$D;case"DD":return n.s(o.$D,2,"0");case"d":return String(o.$W);case"dd":return A(s.weekdaysMin,o.$W,g,2);case"ddd":return A(s.weekdaysShort,o.$W,g,3);case"dddd":return g[o.$W];case"H":return String(u);case"HH":return n.s(u,2,"0");case"h":return G(1);case"hh":return G(2);case"a":return C(u,f,!0);case"A":return C(u,f,!1);case"m":return String(f);case"mm":return n.s(f,2,"0");case"s":return String(o.$s);case"ss":return n.s(o.$s,2,"0");case"SSS":return n.s(o.$ms,3,"0");case"Z":return l}return null}(w)||l.replace(":","")})},a.utcOffset=function(){return 15*-Math.round(this.$d.getTimezoneOffset()/15)},a.diff=function(t,o,s){var i,l=this,u=n.p(o),f=h(t),b=(f.utcOffset()-this.utcOffset())*O,g=this-f,S=function(){return n.m(l,f)};switch(u){case I:i=S()/12;break;case x:i=S();break;case E:i=S()/3;break;case z:i=(g-b)/6048e5;break;case D:i=(g-b)/864e5;break;case k:i=g/L;break;case M:i=g/O;break;case $:i=g/T;break;default:i=g}return s?i:n.a(i)},a.daysInMonth=function(){return this.endOf(x).$D},a.$locale=function(){return j[this.$L]},a.locale=function(t,o){if(!t)return this.$L;var s=this.clone(),i=U(t,o,!0);return i&&(s.$L=i),s},a.clone=function(){return n.w(this.$d,this)},a.toDate=function(){return new Date(this.valueOf())},a.toJSON=function(){return this.isValid()?this.toISOString():null},a.toISOString=function(){return this.$d.toISOString()},a.toString=function(){return this.$d.toUTCString()},r}(),it=X.prototype;return h.prototype=it,[["$ms",c],["$s",$],["$m",M],["$H",k],["$W",D],["$M",x],["$y",I],["$D",p]].forEach(function(r){it[r[1]]=function(a){return this.$g(a,r[0],r[1])}}),h.extend=function(r,a){return r.$i||(r(a,X,h),r.$i=!0),h},h.locale=U,h.isDayjs=F,h.unix=function(r){return h(1e3*r)},h.en=j[H],h.Ls=j,h.p={},h})})(ct);var bt=ct.exports;const rt=dt(bt);const gt=(y,Z)=>{const T=y.__vccOpts||y;for(const[O,L]of Z)T[O]=L;return T},v=y=>(mt("data-v-5c38e805"),y=y(),_t(),y),vt={class:"font-oldstandardtt text-amber-950 text-lg"},yt=v(()=>e("div",{class:"flex justify-center"},[e("h1",{class:"mt-2 mb-2 font-bold text-4xl"},[Y(":"),e("span",{class:"ml-1"},":"),Y(" library_api "),e("span",{class:"mr-1"},":"),Y(":")])],-1)),xt={class:"flex justify-center"},wt={class:"mb-4 w-[840px] font-medium"},$t=v(()=>e("span",{class:"italic text-sm font-medium"},"(These statistics are updated hourly.)",-1)),St=v(()=>e("br",null,null,-1)),Mt=v(()=>e("a",{href:"/docs",target:"_blank",class:"underline"},"here",-1)),kt=v(()=>e("br",null,null,-1)),Dt=v(()=>e("a",{href:"https://genericapiclient.xyz",target:"_blank",class:"underline"},"here",-1)),It=v(()=>e("a",{href:"https://genericapi.xyz",target:"_blank",class:"underline"},"here",-1)),Ot=v(()=>e("br",null,null,-1)),zt={class:"flex justify-center"},jt={class:"tableStyle"},Yt=v(()=>e("tr",null,[e("th",{class:"p-1 bg-stone-300 font-semibold border-b border-stone-500 whitespace-nowrap"},"When ?"),e("th",{class:"thStyle"},"Method"),e("th",{class:"thStyle"},"URL"),e("th",{class:"thStyle"},"IP"),e("th",{class:"thStyle"},"Status")],-1)),Tt={key:0},Ht=v(()=>e("td",{colspan:"5",class:"p-1 font-medium italic whitespace-nowrap"},"No Records Found!",-1)),Nt=[Ht],Lt={class:"tdDatetimeStyle"},Ut={class:"tdMethodStyle"},At={class:"tdUrlStyle"},Ct={key:0,class:"tdIpStyle"},Pt=["href"],Ft={key:1,class:"tdIpStyle"},Wt={class:"tdStatusStyle"},Bt={key:0},Vt=v(()=>e("div",{class:"flex justify-center"},[e("div",{class:"mt-5 relative z-0 w-[640px] h-20 bg-lime-100 border-2 border-lime-600 rounded-md shadow-md cursor-pointer",onclick:"window.open('https://owlsearch.games', '_blank')"},[e("img",{src:"https://owlsearch.games/images/logo/owl56.jpg",class:"absolute z-10 top-[9px] left-5 mt-[1px] p-[2px] block h-14 border-2 border-emerald-950 bg-black rounded-2xl opacity-95"}),e("span",{class:"absolute z-10 left-28 top-[3px] text-2xl font-bold font-sans text-green-900"},"Owl Search Games"),e("span",{class:"absolute z-10 left-28 top-[30px] text-base font-bold font-sans text-green-800"},"➥ Turbospeed your Brain!"),e("span",{class:"absolute z-10 left-28 top-[47px] text-base font-bold font-sans text-green-800"},"➥ Play without Ads!"),e("button",{class:"absolute left-[410px] top-[17px] z-20 px-9 py-2 bg-green-600 border border-green-900 rounded-lg text-lime-200 font-bold font-sans shadow-lg"},"➤ Play Now!"),e("div",{class:"motion-safe:animate-ping absolute z-10 left-[451px] top-[21px] w-[113px] h-[34px] bg-red-600/100 rounded-lg"})])],-1)),Et=v(()=>e("div",{class:"flex justify-center"},[e("div",{class:"mt-0.5 mb-4 font-black text-xs uppercase"},"Please support our sponsor.")],-1)),Gt=[Vt,Et],Jt={key:1,class:"flex justify-center"},Zt={class:"tableStyle"},qt={class:"tdDatetimeStyle"},Rt={class:"tdMethodStyle"},Qt={class:"tdUrlStyle"},Kt={key:0,class:"tdIpStyle"},Xt=["href"],te={key:1,class:"tdIpStyle"},ee={class:"tdStatusStyle"},se={key:2},ae=v(()=>e("div",{class:"flex justify-center"},[e("div",{class:"mt-5 relative z-0 w-[640px] h-20 bg-lime-100 border-2 border-lime-600 rounded-md shadow-md cursor-pointer",onclick:"window.open('https://owlsearch.games', '_blank')"},[e("img",{src:"https://owlsearch.games/images/logo/owl56.jpg",class:"absolute z-10 top-[9px] left-5 mt-[1px] p-[2px] block h-14 border-2 border-emerald-950 bg-black rounded-2xl opacity-95"}),e("span",{class:"absolute z-10 left-28 top-[3px] text-2xl font-bold font-sans text-green-900"},"Owl Search Games"),e("span",{class:"absolute z-10 left-28 top-[30px] text-base font-bold font-sans text-green-800"},"➥ Turbospeed your Brain!"),e("span",{class:"absolute z-10 left-28 top-[47px] text-base font-bold font-sans text-green-800"},"➥ Play without Ads!"),e("button",{class:"absolute left-[410px] top-[17px] z-20 px-9 py-2 bg-green-600 border border-green-900 rounded-lg text-lime-200 font-bold font-sans shadow-lg"},"➤ Play Now!"),e("div",{class:"motion-safe:animate-ping absolute z-10 left-[451px] top-[21px] w-[113px] h-[34px] bg-red-600/100 rounded-lg"})])],-1)),ne=v(()=>e("div",{class:"flex justify-center"},[e("div",{class:"mt-0.5 mb-4 font-black text-xs uppercase"},"Please support our sponsor.")],-1)),oe=[ae,ne],re={key:3,class:"flex justify-center"},ie={class:"tableStyle"},le={class:"tdDatetimeStyle"},ce={class:"tdMethodStyle"},ue={class:"tdUrlStyle"},de={key:0,class:"tdIpStyle"},he=["href"],fe={key:1,class:"tdIpStyle"},pe={class:"tdStatusStyle"},me={key:4},_e=v(()=>e("div",{class:"flex justify-center"},[e("div",{class:"mt-5 relative z-0 w-[640px] h-20 bg-lime-100 border-2 border-lime-600 rounded-md shadow-md cursor-pointer",onclick:"window.open('https://owlsearch.games', '_blank')"},[e("img",{src:"https://owlsearch.games/images/logo/owl56.jpg",class:"absolute z-10 top-[9px] left-5 mt-[1px] p-[2px] block h-14 border-2 border-emerald-950 bg-black rounded-2xl opacity-95"}),e("span",{class:"absolute z-10 left-28 top-[3px] text-2xl font-bold font-sans text-green-900"},"Owl Search Games"),e("span",{class:"absolute z-10 left-28 top-[30px] text-base font-bold font-sans text-green-800"},"➥ Turbospeed your Brain!"),e("span",{class:"absolute z-10 left-28 top-[47px] text-base font-bold font-sans text-green-800"},"➥ Play without Ads!"),e("button",{class:"absolute left-[410px] top-[17px] z-20 px-9 py-2 bg-green-600 border border-green-900 rounded-lg text-lime-200 font-bold font-sans shadow-lg"},"➤ Play Now!"),e("div",{class:"motion-safe:animate-ping absolute z-10 left-[451px] top-[21px] w-[113px] h-[34px] bg-red-600/100 rounded-lg"})])],-1)),be=v(()=>e("div",{class:"flex justify-center"},[e("div",{class:"mt-0.5 font-black text-xs uppercase"},"Please support our sponsor.")],-1)),ge=[_e,be],ve=pt('<div class="flex justify-center" data-v-5c38e805><p class="mt-3 w-[840px] text-sm font-medium italic" data-v-5c38e805> This site is best wiewed on a larger screen, such as either from a laptop or desktop. </p></div><div class="flex justify-center" data-v-5c38e805><p class="mt-1 w-[840px] text-sm font-medium italic" data-v-5c38e805> Also, domain names may change anytime, so please check <a href="https://github.com/alexni09/library_api" target="_blank" class="underline" data-v-5c38e805>this github repository</a> to find out where this app is hosted or where it will be hosted next. </p></div><div class="h-16" data-v-5c38e805></div>',3),ye={__name:"Index",props:{ip_list:Object},setup(y){const Z=new Intl.NumberFormat("en-US",{style:"currency",currency:"USD"});var T=null,O=null,L=null;const c=B(null),$=B(null),M=B(null),k=B(null),D=B(""),z=B(null),x=()=>{axios.get("/api/money").then(p=>{D.value=Z.format(p.data.money/100)}).catch(function(p){console.log(p)})},E=()=>{axios.get("/api/monitor").then(p=>{c.value=lt(p.data.data),c.value.length<60?($.value=c.value.slice(),M.value=null,k.value=null):c.value.length>=60&&c.value.length<90?($.value=c.value.slice(0,Math.floor(c.value.length/2)),M.value=c.value.slice(Math.floor(c.value.length/2,c.value.length)),k.value=null):($.value=c.value.slice(0,Math.floor(c.value.length/3)),M.value=c.value.slice(Math.floor(c.value.length/3),Math.floor(c.value.length*2/3)),k.value=c.value.slice(Math.floor(c.value.length*2/3,c.value.length)))}).catch(function(p){console.log(p)})},I=()=>{axios.get("/api/count").then(p=>{z.value=lt(p.data.data)}).catch(function(p){console.log(p)})};return ht(()=>{x(),E(),I(),T=setInterval(E,5e3),O=setInterval(x,3500),L=setInterval(I,6e4)}),ft(()=>{clearInterval(T),clearInterval(O),clearInterval(L)}),(p,et)=>{var q,R,Q,P,K,H,j,F,U,h;return m(),_("div",vt,[yt,e("div",xt,[e("p",wt,[Y(" This is a digital library with fictitious data. Its aim is to demonstrate the fastness and robustness of the api responses over a very big database, which currently holds: "+d(Number((q=z.value)==null?void 0:q.category_count).toLocaleString("en-US"))+" categories, "+d(Number((R=z.value)==null?void 0:R.book_count).toLocaleString("en-US"))+" books, "+d(Number((Q=z.value)==null?void 0:Q.exemplar_count).toLocaleString("en-US"))+" exemplars; and is "+d((P=z.value)==null?void 0:P.mysql_count)+" big. The api customers pay after returning the borrowed book; and when that happens this library makes money. Until now, it has accumulated F"+d(D.value)+". ",1),$t,St,Y(" The api documentation can be accessed "),Mt,Y(". "),kt,Y(" There are two clients for this library. They can be accessed "),Dt,Y(" and "),It,Y(". "),Ot,Y(" These are the latest api requests, in real time: ")])]),e("div",zt,[e("table",jt,[Yt,((K=$.value)==null?void 0:K.length)===0?(m(),_("tr",Tt,Nt)):V("",!0),(m(!0),_(st,null,at($.value,n=>(m(),_("tr",{key:n.id,class:nt({"bg-stone-200":n.id%6>2})},[e("td",Lt,d(ot(rt)(n.datetime+"+00:00").format("YYYY/MM/DD HH:mm:ss")),1),e("td",Ut,d(n.method),1),e("td",At,d(n.url),1),n.ip in y.ip_list?(m(),_("td",Ct,[e("a",{href:y.ip_list[n.ip],target:"_blank"},d(n.ip),9,Pt)])):(m(),_("td",Ft,d(n.ip),1)),e("td",Wt,d(n.status),1)],2))),128))])]),((H=c.value)==null?void 0:H.length)>=60?(m(),_("div",Bt,Gt)):V("",!0),((j=c.value)==null?void 0:j.length)>=60?(m(),_("div",Jt,[e("table",Zt,[(m(!0),_(st,null,at(M.value,n=>(m(),_("tr",{key:n.id,class:nt({"bg-stone-200":n.id%6>2})},[e("td",qt,d(ot(rt)(n.datetime+"+00:00").format("YYYY/MM/DD HH:mm:ss")),1),e("td",Rt,d(n.method),1),e("td",Qt,d(n.url),1),n.ip in y.ip_list?(m(),_("td",Kt,[e("a",{href:y.ip_list[n.ip],target:"_blank"},d(n.ip),9,Xt)])):(m(),_("td",te,d(n.ip),1)),e("td",ee,d(n.status),1)],2))),128))])])):V("",!0),((F=c.value)==null?void 0:F.length)>=90?(m(),_("div",se,oe)):V("",!0),((U=c.value)==null?void 0:U.length)>=90?(m(),_("div",re,[e("table",ie,[(m(!0),_(st,null,at(k.value,n=>(m(),_("tr",{key:n.id,class:nt({"bg-stone-200":n.id%6>2})},[e("td",le,d(ot(rt)(n.datetime+"+00:00").format("YYYY/MM/DD HH:mm:ss")),1),e("td",ce,d(n.method),1),e("td",ue,d(n.url),1),n.ip in y.ip_list?(m(),_("td",de,[e("a",{href:y.ip_list[n.ip],target:"_blank"},d(n.ip),9,he)])):(m(),_("td",fe,d(n.ip),1)),e("td",pe,d(n.status),1)],2))),128))])])):V("",!0),((h=c.value)==null?void 0:h.length)<60?(m(),_("div",me,ge)):V("",!0),ve])}}},we=gt(ye,[["__scopeId","data-v-5c38e805"]]);export{we as default};