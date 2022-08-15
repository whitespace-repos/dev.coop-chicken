import{B as k}from"./Authenticated.4bf69a33.js";import{H as w,L as v,A as p,c as i,a as u,w as h,F as _,r as f,o as d,d as t,m,D as x,e as b,t as a,C as D,f as n,b as C}from"./app.34dda1bd.js";import{_ as q}from"./ResponsiveNavLink.4100381f.js";const L={components:{BreezeAuthenticatedLayout:k,Head:w,Link:v},props:["prop_products","prop_shops","prop_product","prop_filterProduct","prop_filterDate"],data(){return{products:this.prop_products,shops:this.prop_shops,product:this.prop_product,filterProduct:this.prop_filterProduct,filterDate:this.prop_filterDate}},computed:{totalStockRequest(){let o=0;return p.exports.forEach(this.product.shops,function(r){p.exports.forEach(r.stock_requests,function(c){o+=c.status!="Completed"?1:0})}),o}},methods:{shopStockReqeustSize(o){let r=0;return p.exports.forEach(o.stock_requests,function(c){r+=c.status!="Completed"?1:0}),r},filterShop(){this.axios.get("/filter/product/shops",{params:{id:this.filterProduct,date:this.filterDate}}).then(o=>{p.exports.assignIn(this.$data,o.data),this.$toast.info("Shops filtered basis on product and date.")})}}},P={class:"row align-items-center justify-content-between"},R=t("div",{class:"col-auto"},[t("h6",{class:"mb-0"},[t("span",{"data-feather":"calendar",class:"align-text-bottom mr-2"}),n(" Shops ")])],-1),B={class:"col-auto"},N={class:"input-group input-group-sm"},V=t("div",{class:"input-group-prepend"},[t("span",{class:"input-group-text"},"Filter Product")],-1),j=["value","selected"],z={class:"col-auto text-right d-flex align-items-center"},A={class:"border-danger btn-sm border rounded-lg small font-weight-bold mr-2 m-0 d-inline-block"},E=n(" Stock Request Pending "),H={class:"badge badge-danger"},F=t("span",{"data-feather":"database",class:"mr-1 align-text-bottom"},null,-1),J=n(" Add New Shop"),M=t("hr",null,null,-1),O={class:"row"},T={class:"card shadow-lg mb-3"},U={class:"card-header py-1 px-3 font-weight-bold"},I=t("span",{"data-feather":"file",class:"align-text-bottom"},null,-1),W=n(" View "),G={class:"badge badge-danger"},K={class:"list-group list-group-flush"},Q={class:"list-group-item d-flex justify-content-between align-items-center py-1"},X=n("Wholesale Rate "),Y={class:"badge badge-danger"},Z={class:"list-group-item d-flex justify-content-between align-items-center py-1"},$=n("Retail Rate "),tt={class:"badge badge-danger"},et={class:"list-group-item d-flex justify-content-between align-items-center py-1"},st=n("Sale "),ot={class:"badge badge-danger"},rt={key:0,class:"list-group-item d-flex justify-content-between align-items-center py-1"},nt=n("Stock "),at={class:"badge badge-danger"};function lt(o,r,c,it,s,l){const S=f("Head"),g=f("Link"),y=f("BreezeAuthenticatedLayout");return d(),i(_,null,[u(S,{title:"Shops"}),u(y,null,{default:h(()=>[t("div",P,[R,t("div",B,[t("div",N,[V,m(t("select",{class:"form-control",onChange:r[0]||(r[0]=(...e)=>l.filterShop&&l.filterShop(...e)),"onUpdate:modelValue":r[1]||(r[1]=e=>s.filterProduct=e)},[(d(!0),i(_,null,b(s.products,e=>(d(),i("option",{key:e.id,value:e.id,selected:e.id==s.product.id},a(e.product_name),9,j))),128))],544),[[x,s.filterProduct]]),m(t("input",{type:"date",onChange:r[2]||(r[2]=(...e)=>l.filterShop&&l.filterShop(...e)),"onUpdate:modelValue":r[3]||(r[3]=e=>s.filterDate=e),class:"form-control"},null,544),[[D,s.filterDate]])])]),t("div",z,[t("h6",A,[E,t("span",H,a(l.totalStockRequest),1)]),u(g,{class:"btn add-btn btn-danger btn-sm",href:o.route("shop.create")},{default:h(()=>[F,J]),_:1},8,["href"])])]),M,t("div",O,[(d(!0),i(_,null,b(s.product.shops,e=>(d(),i("div",{class:"col-auto",key:e.id},[t("div",T,[t("div",U,[I,n(" "+a(e.shop_name)+" ",1),u(g,{href:o.route("shop.show",e.id),class:"btn btn-outline-danger py-0 btn-sm float-right ml-5 px-3"},{default:h(()=>[W,t("span",G,a(l.shopStockReqeustSize(e)),1)]),_:2},1032,["href"])]),t("ul",K,[t("li",Q,[X,t("span",Y,"\u20B9 "+a(s.product.rate==null||JSON.parse(s.product.rate.wholesale_rate).length==0?0:o.toDecimal(JSON.parse(s.product.rate.wholesale_rate)[0].rate)),1)]),t("li",Z,[$,t("span",tt,"\u20B9 "+a(s.product.rate==null?0:o.toDecimal(s.product.rate.retail_rate)),1)]),t("li",et,[st,t("span",ot,"\u20B9 "+a(o.toDecimal(e.today_sale)),1)]),s.product.stock?(d(),i("li",rt,[nt,t("span",at,[n(a(o.toDecimal(e.association.stock))+" ",1),t("sup",null,a(s.product.weight_unit),1)])])):C("",!0)])])]))),128))])]),_:1})],64)}var ut=q(L,[["render",lt]]);export{ut as default};