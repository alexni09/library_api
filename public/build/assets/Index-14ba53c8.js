import{k as p,l as u,m as h,o,f as r,b as e,g as m,F as b,p as _,q as f,n,t as a}from"./app-afa26236.js";const z=e("div",{class:"flex justify-center"},[e("h1",{class:"mt-2 mb-3 font-bold text-3xl"},"library_api")],-1),g={class:"flex justify-center"},x={class:"bg-zinc-50 border border-zinc-500"},v=e("tr",null,[e("th",{class:"p-1 bg-zinc-300 font-semibold border-b border-zinc-500 whitespace-nowrap"},"When ?"),e("th",{class:"p-1 bg-zinc-300 font-semibold border-l border-b border-zinc-500"},"Method"),e("th",{class:"p-1 bg-zinc-300 font-semibold border-l border-b border-zinc-500"},"URL"),e("th",{class:"p-1 bg-zinc-300 font-semibold border-l border-b border-zinc-500"},"Status")],-1),w={key:0},y=e("td",{colspan:"4",class:"p-1 font-medium whitespace-nowrap"},[e("i",null,"No Records Found!")],-1),k=[y],L={__name:"Index",setup(B){var d=null;const c=p(null),l=()=>{axios.get("/api/monitor").then(s=>{c.value=f(s.data.data)}).catch(function(s){console.log(s)})};return u(()=>{l(),d=setInterval(l,5e3)}),h(()=>clearInterval(d)),(s,I)=>{var i;return o(),r(b,null,[z,e("div",g,[e("table",x,[v,((i=c.value)==null?void 0:i.length)===0?(o(),r("tr",w,k)):m("",!0),(o(!0),r(b,null,_(c.value,t=>(o(),r("tr",{key:t.id},[e("td",{class:n(["px-1 whitespace-nowrap",{"bg-zinc-200":t.id%6>2}])},a(t.datetime),3),e("td",{class:n(["px-1 border-l border-zinc-500",{"bg-zinc-200":t.id%6>2}])},a(t.method),3),e("td",{class:n(["px-1 border-l border-zinc-500",{"bg-zinc-200":t.id%6>2}])},a(t.url),3),e("td",{class:n(["px-1 border-l border-zinc-500",{"bg-zinc-200":t.id%6>2}])},a(t.status),3)]))),128))])])],64)}}};export{L as default};
