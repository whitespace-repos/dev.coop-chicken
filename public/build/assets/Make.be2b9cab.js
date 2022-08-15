import{B as Pt}from"./BillingSystem.c4b4ece5.js";import{U as Dt,l as _,J as G,P,i as Vt,Q as Ft,j as Ht,k as ct,I as Ot,V as zt,W as T,O as A,H as Ut,A as M,c as h,a as z,w as R,F as D,r as U,B as Yt,o as m,d as t,p as q,b as j,e as L,t as p,s as nt,n as X,m as lt,C as it,f as w,R as Wt,S as qt}from"./app.34dda1bd.js";import{h as At}from"./moment.9709ab41.js";import{r as It,u as Xt}from"./index.esm.a0b94528.js";import{_ as $t}from"./ResponsiveNavLink.4100381f.js";/**
 * Vue 3 Carousel 0.1.40
 * (c) 2022
 * @license MIT
 */const C={itemsToShow:1,itemsToScroll:1,modelValue:0,transition:300,autoplay:0,snapAlign:"center",wrapAround:!1,pauseAutoplayOnHover:!1,mouseDrag:!0,touchDrag:!0,dir:"ltr",breakpoints:void 0};function Gt(e,a){let o;return function(...n){o&&clearTimeout(o),o=setTimeout(()=>{e(...n),o=null},a)}}function Jt(e,a){let o;return function(...n){const c=this;o||(e.apply(c,n),o=!0,setTimeout(()=>o=!1,a))}}function Qt(e){var a,o,n;return e?((o=(a=e[0])===null||a===void 0?void 0:a.type)===null||o===void 0?void 0:o.name)==="CarouselSlide"?e:((n=e[0])===null||n===void 0?void 0:n.children)||[]:[]}function Kt(e,a){if(e.wrapAround)return a-1;switch(e.snapAlign){case"start":return a-e.itemsToShow;case"end":return a-1;case"center":case"center-odd":return a-Math.ceil(e.itemsToShow/2);case"center-even":return a-Math.ceil(e.itemsToShow/2);default:return 0}}function Zt(e){if(e.wrapAround)return 0;switch(e.snapAlign){case"start":return 0;case"end":return e.itemsToShow-1;case"center":case"center-odd":return Math.floor((e.itemsToShow-1)/2);case"center-even":return Math.floor((e.itemsToShow-2)/2);default:return 0}}function Nt(e,a,o,n){return e.wrapAround?a:Math.min(Math.max(a,n),o)}function te({slidesBuffer:e,currentSlide:a,snapAlign:o,itemsToShow:n,wrapAround:c,slidesCount:i}){let u=e.indexOf(a);if(u===-1&&(u=e.indexOf(Math.ceil(a))),o==="center"||o==="center-odd"?u-=(n-1)/2:o==="center-even"?u-=(n-2)/2:o==="end"&&(u-=n-1),!c){const d=i-n,g=0;u=Math.max(Math.min(u,d),g)}return u}var ee=Dt({name:"Carousel",props:{itemsToShow:{default:C.itemsToShow,type:Number},itemsToScroll:{default:C.itemsToScroll,type:Number},wrapAround:{default:C.wrapAround,type:Boolean},snapAlign:{default:C.snapAlign,validator(e){return["start","end","center","center-even","center-odd"].includes(e)}},transition:{default:C.transition,type:Number},breakpoints:{default:C.breakpoints,type:Object},autoplay:{default:C.autoplay,type:Number},pauseAutoplayOnHover:{default:C.pauseAutoplayOnHover,type:Boolean},modelValue:{default:void 0,type:Number},mouseDrag:{default:C.mouseDrag,type:Boolean},touchDrag:{default:C.touchDrag,type:Boolean},dir:{default:C.dir,validator(e){return["rtl","ltr"].includes(e)}},settings:{default(){return{}},type:Object}},setup(e,{slots:a,emit:o,expose:n}){var c;const i=_(null),u=_([]),d=_([]),g=_(0),f=_(1),S=_(null),x=_(null);let I=_({}),s=Object.assign({},C);const r=G(Object.assign({},s)),b=_((c=r.modelValue)!==null&&c!==void 0?c:0),Bt=_(0),ut=_(0),F=_(0),H=_(0);P("config",r),P("slidesBuffer",d),P("slidesCount",f),P("currentSlide",b),P("maxSlide",F),P("minSlide",H);function mt(){const l=Object.assign(Object.assign({},e),e.settings);I=_(Object.assign({},l.breakpoints)),s=Object.assign(Object.assign({},l),{settings:void 0,breakpoints:void 0}),ht(s)}function J(){const l=Object.keys(I.value).map(k=>Number(k)).sort((k,N)=>+N-+k);let y=Object.assign({},s);l.some(k=>window.matchMedia(`(min-width: ${k}px)`).matches?(y=Object.assign(Object.assign({},y),I.value[k]),!0):!1),ht(y)}function ht(l){for(let y in l)r[y]=l[y]}const Rt=Gt(()=>{I.value&&(J(),Y()),Q()},16);function Q(){if(!i.value)return;const l=i.value.getBoundingClientRect();g.value=l.width/r.itemsToShow}function Y(){f.value=Math.max(u.value.length,1),!(f.value<=0)&&(ut.value=Math.ceil((f.value-1)/2),F.value=Kt(r,f.value),H.value=Zt(r),b.value=Nt(r,b.value,F.value,H.value))}function K(){const l=[...Array(f.value).keys()];if(r.wrapAround&&r.itemsToShow+1<=f.value){let N=(r.itemsToShow!==1?Math.round((f.value-r.itemsToShow)/2):0)-b.value;if(r.snapAlign==="end"?N+=Math.floor(r.itemsToShow-1):(r.snapAlign==="center"||r.snapAlign==="center-odd")&&N++,N<0)for(let B=N;B<0;B++)l.push(Number(l.shift()));else for(let B=0;B<N;B++)l.unshift(Number(l.pop()))}d.value=l}Vt(()=>{I.value&&(J(),Y()),Ft(()=>setTimeout(Q,16)),r.autoplay&&r.autoplay>0&&gt(),window.addEventListener("resize",Rt,{passive:!0})}),Ht(()=>{x.value&&clearTimeout(x.value),bt(!1)});let O=!1;const Z={x:0,y:0},tt={x:0,y:0},V=G({x:0,y:0}),pt=_(!1),et=_(!1),jt=()=>{et.value=!0},Lt=()=>{et.value=!1};function ft(l){O=l.type==="touchstart",!(!O&&l.button!==0||W.value)&&(pt.value=!0,Z.x=O?l.touches[0].clientX:l.clientX,Z.y=O?l.touches[0].clientY:l.clientY,document.addEventListener(O?"touchmove":"mousemove",_t,!0),document.addEventListener(O?"touchend":"mouseup",vt,!0))}const _t=Jt(l=>{tt.x=O?l.touches[0].clientX:l.clientX,tt.y=O?l.touches[0].clientY:l.clientY;const y=tt.x-Z.x,k=tt.y-Z.y;V.y=k,V.x=y},16);function vt(){pt.value=!1;const l=r.dir==="rtl"?-1:1,y=Math.sign(V.x)*.4,k=Math.round(V.x/g.value+y)*l;let N=Nt(r,b.value-k,F.value,H.value);if(k){const B=rt=>{rt.stopPropagation(),window.removeEventListener("click",B,!0)};window.addEventListener("click",B,!0)}E(N),V.x=0,V.y=0,document.removeEventListener(O?"touchmove":"mousemove",_t,!0),document.removeEventListener(O?"touchend":"mouseup",vt,!0)}function gt(){S.value=setInterval(()=>{r.pauseAutoplayOnHover&&et.value||st()},r.autoplay)}function bt(l=!0){!S.value||(clearInterval(S.value),l&&gt())}const W=_(!1);function E(l,y=!1){if(bt(),b.value===l||W.value)return;const k=f.value-1;if(l>k)return E(l-f.value);if(l<0)return E(l+f.value);W.value=!0,Bt.value=b.value,b.value=l,y||o("update:modelValue",b.value),x.value=setTimeout(()=>{r.wrapAround&&K(),W.value=!1},r.transition)}function st(){let l=b.value+r.itemsToScroll;r.wrapAround||(l=Math.min(l,F.value)),E(l)}function wt(){let l=b.value-r.itemsToScroll;r.wrapAround||(l=Math.max(l,H.value)),E(l)}const yt={slideTo:E,next:st,prev:wt};P("nav",yt);const kt=ct(()=>te({slidesBuffer:d.value,itemsToShow:r.itemsToShow,snapAlign:r.snapAlign,wrapAround:Boolean(r.wrapAround),currentSlide:b.value,slidesCount:f.value}));P("slidesToScroll",kt);const Et=ct(()=>{const l=r.dir==="rtl"?-1:1,y=kt.value*g.value*l;return{transform:`translateX(${V.x-y}px)`,transition:`${W.value?r.transition:0}ms`}});function St(){mt()}function xt(){mt(),J(),Y(),K(),Q()}function Ct(){Y(),K()}Ot(()=>Object.values(e),xt),St(),zt(()=>{const l=f.value!==u.value.length;e.modelValue!==void 0&&b.value!==e.modelValue&&E(Number(e.modelValue),!0),l&&Ct()});const Tt={config:r,slidesBuffer:d,slidesCount:f,slideWidth:g,currentSlide:b,maxSlide:F,minSlide:H,middleSlide:ut};n({updateBreakpointsConfigs:J,updateSlidesData:Y,updateSlideWidth:Q,updateSlidesBuffer:K,initCarousel:St,restartCarousel:xt,updateCarousel:Ct,slideTo:E,next:st,prev:wt,nav:yt,data:Tt});const ot=a.default||a.slides,at=a.addons,Mt=G(Tt);return()=>{const l=Qt(ot==null?void 0:ot(Mt)),y=(at==null?void 0:at(Mt))||[];u.value=l,l.forEach((B,rt)=>B.props.index=rt);const k=T("ol",{class:"carousel__track",style:Et.value,onMousedown:r.mouseDrag?ft:null,onTouchstart:r.touchDrag?ft:null},l),N=T("div",{class:"carousel__viewport"},k);return T("section",{ref:i,class:{carousel:!0,"carousel--rtl":r.dir==="rtl"},dir:r.dir,"aria-label":"Gallery",onMouseenter:jt,onMouseleave:Lt},[N,y])}}});const se={arrowUp:"M7.41 15.41L12 10.83l4.59 4.58L18 14l-6-6-6 6z",arrowDown:"M7.41 8.59L12 13.17l4.59-4.58L18 10l-6 6-6-6 1.41-1.41z",arrowRight:"M8.59 16.59L13.17 12 8.59 7.41 10 6l6 6-6 6-1.41-1.41z",arrowLeft:"M15.41 16.59L10.83 12l4.58-4.59L14 6l-6 6 6 6 1.41-1.41z"},dt=e=>{const a=e.name;if(!a||typeof a!="string")return;const o=se[a],n=T("path",{d:o}),c=e.title||a,i=T("title",null,a);return T("svg",{class:"carousel__icon",viewBox:"0 0 24 24",role:"img",ariaLabel:c},[i,n])};dt.props={name:String,title:String};const oe=(e,{slots:a,attrs:o})=>{const{next:n,prev:c}=a,i=A("config",G(Object.assign({},C))),u=A("maxSlide",_(1)),d=A("minSlide",_(1)),g=A("currentSlide",_(1)),f=A("nav",{}),S=i.dir==="rtl",x=T("button",{type:"button",class:["carousel__prev",!i.wrapAround&&g.value<=d.value&&"carousel__prev--in-active",o==null?void 0:o.class],"aria-label":"Navigate to previous slide",onClick:f.prev},(c==null?void 0:c())||T(dt,{name:S?"arrowRight":"arrowLeft"})),I=T("button",{type:"button",class:["carousel__next",!i.wrapAround&&g.value>=u.value&&"carousel__next--in-active",o==null?void 0:o.class],"aria-label":"Navigate to next slide",onClick:f.next},(n==null?void 0:n())||T(dt,{name:S?"arrowLeft":"arrowRight"}));return[x,I]};var ae=Dt({name:"CarouselSlide",props:{index:{type:Number,default:1}},setup(e,{slots:a}){const o=A("config",G(Object.assign({},C))),n=A("slidesBuffer",_([])),c=A("currentSlide",_(0)),i=A("slidesToScroll",_(0)),u=_(e.index);o.wrapAround&&(d(),Ot(n,d));function d(){u.value=n.value.indexOf(e.index)}const g=ct(()=>{const s=o.itemsToShow;return{width:`${1/s*100}%`,order:u.value.toString()}}),f=()=>e.index===c.value,S=()=>{const s=Math.ceil(i.value),r=Math.floor(i.value+o.itemsToShow);return n.value.slice(s,r).includes(e.index)},x=()=>e.index===n.value[Math.ceil(i.value)-1],I=()=>e.index===n.value[Math.floor(i.value+o.itemsToShow)];return()=>{var s;return T("li",{style:g.value,class:{carousel__slide:!0,"carousel__slide--active":f(),"carousel__slide--visible":S(),"carousel__slide--prev":x(),"carousel__slide--next":I()}},(s=a.default)===null||s===void 0?void 0:s.call(a))}}});const re=()=>{const e=A("maxSlide",_(1)),a=A("minSlide",_(1)),o=A("currentSlide",_(1)),n=A("nav",{});function c(d){n.slideTo(d)}const i=d=>{const g=o.value;return g===d||g>e.value&&d>=e.value||g<a.value&&d<=a.value},u=[];for(let d=a.value;d<e.value+1;d++){const g=T("button",{type:"button",class:{"carousel__pagination-button":!0,"carousel__pagination-button--active":i(d)},"aria-label":`Navigate to slide ${d+1}`,onClick:()=>c(d)}),f=T("li",{class:"carousel__pagination-item",key:d},g);u.push(f)}return T("ol",{class:"carousel__pagination"},u)};const ne={components:{BreezeAuthenticatedLayout:Pt,Head:Ut,moment:At,Carousel:ee,Slide:ae,Pagination:re,Navigation:oe},props:["products","shop","carts","sales"],data(){return{sumBy:M.exports.sumBy,phoneMaskComplete:!1,showModal:!1,settings:{itemsToShow:5,snapAlign:"center"},existingCustomer:!1,token:$("[name='csrf-token']").attr("content"),formUrl:"{{ route('customer.store') }}",rate:0,purchaseHistory:[],customer:"",cartFlag:!1,paymentMethod:"EMI",currentStock:[],billingWeightInput:{},form:{customer:this.$inertia.form({name:null,email:null,location:null,phone:null}),cart:this.$inertia.form({product:"",customer:"",weight:0,amount:0,rate:0,type:""}),removeCart:this.$inertia.form({id:""}),stockRequest:this.$inertia.form({products:{}})}}},mounted(){feather.replace({"aria-hidden":"true"}),this.currentStock=this.shop.products,Object.keys(this.carts).length>0&&(this.existingCustomer=!0,this.form.customer=M.exports.get(M.exports.values(this.carts),0).attributes.customer,M.exports.forIn(this.carts,e=>{let a=this.currentStock.find(function(o){return o.id==e.attributes.product.id});if(a.stock)a.association.stock-=e.attributes.weight;else{let o=this.currentStock.find(function(n){return n.id==e.attributes.product.parent_product_id});o.association.stock-=e.attributes.weight/a.conversion_rate}}))},computed:{totalAmount:function(){let e=0;return M.exports.forIn(this.carts,function(a,o){e+=a.price}),e},productCurrentStock(){return this.currentStock}},methods:{loadCustomer(e){e.target.value.length==10?this.phoneMaskComplete==!1&&(this.phoneMaskComplete=!0,this.axios.post(this.route("customer.existance"),{phone:e.target.value}).then(a=>{this.existingCustomer=a.data.existingCustomer,this.existingCustomer?(M.exports.assignIn(this.form.customer,a.data.customer),this.purchaseHistory=a.data.purchase_history,this.$toast.success("Customer Detail Loaded !"),feather.replace({"aria-hidden":"true"})):this.$toast.warning("Customer not found !")})):(this.existingCustomer=!1,this.form.customer.name="",this.phoneMaskComplete=!1)},saveCustomerForm(){if(this.v$.form.customer.$invalid){this.v$.form.customer.$touch();return}this.form.customer.post(this.route("customer.store"),{only:["existingCustomer","customer","totalCartItem"],onSuccess:e=>{this.form.customer.reset("name"),window.history.pushState("data","Save Customer","/make-sale"),this.existingCustomer=e.props.existingCustomer,this.existingCustomer&&M.exports.merge(this.form.customer,e.props.customer),window.history.pushState("data","Add to Cart","/make-sale")}})},addToCart(e){var a=$(e.target),o=a.find("[name=weight]");let n=this,c=!1;this.form.cart.customer=this.form.customer.phone,this.form.cart.product=o.data("productId"),this.form.cart.weight=o.val();let i=this.productCurrentStock.find(function(u){return u.id==n.form.cart.product});if(i.stock?i.association.stock<o.val()&&(c=!0):this.productCurrentStock.find(function(d){return d.id==i.parent_product_id}).association.stock<o.val()&&(c=!0),c)return $.jGrowl("Insufficient Stock ! Please request for stock",{theme:"jGrowl-bg text-white"}),!1;this.form.cart.amount=o.data("amount"),this.form.cart.rate=o.data("rate"),this.form.cart.type=$("[name=product_"+this.form.cart.product+"_radio]:checked").val(),this.form.cart.post(this.route("cart.store"),{only:["carts"],onSuccess:u=>{if(window.history.pushState("data","Add to Cart","/make-sale"),i.stock)i.association.stock=i.association.stock-n.form.cart.weight;else{let d=this.productCurrentStock.find(function(g){return g.id==i.parent_product_id});d.association.stock=d.association.stock-n.form.cart.weight/i.conversion_rate}this.billingWeightInput["product-"+this.form.cart.product]=0,o.closest("form").find(".price").html(parseFloat(0)),$(".product_"+n.form.cart.product+"_retail_radio").prop("checked",!0)}})},removeCart(e,a){var o=$(e.target),n=o.find("[name='id']");this.form.removeCart.id=n.val(),this.form.removeCart.post(this.route("cart.remove"),{only:["carts"],onSuccess:c=>{window.history.pushState("data","Remove from Cart","/make-sale");let i=this.currentStock.find(function(u){return u.id==a.attributes.product.id});if(i.stock)i.association.stock+=a.attributes.weight;else{let u=this.currentStock.find(function(d){return d.id==i.parent_product_id});u.association.stock+=a.attributes.weight/i.conversion_rate}}})},calateprice(e,a){let o=$(e.target);o.data("product");let n=o.data("productId"),c=this.shop.products.find(function(x){return x.id==n}),i=parseInt(o.val()),u=c.stock?parseInt(o.val()):parseInt(o.val())/c.conversion_rate,d=a?M.exports.find(o.data("wholesaleRange"),function(x){return x.from<=u&&u<=x.to}):null,g=o.data("retailRate"),f=M.exports.isNaN(d)||M.exports.isNil(d)?parseFloat(g):parseFloat(d.rate),S=Number(parseFloat(f)*parseFloat(u)).toFixed(2);M.exports.isNaN(d)||M.exports.isNil(d)?$(".product_"+n+"_retail_radio").prop("checked",!0):$(".product_"+n+"_wholesale_radio").prop("checked",!0),u>0?(c.stock?o.closest("form").find(".price").html(parseFloat(S)+"<sup>INR</sup>"):o.closest("form").find(".price").html("<small class='fs-10'>"+i+" / "+c.conversion_rate+" * "+f+" = </small>"+parseFloat(S)+"<sup>INR</sup>"),o.attr("data-amount",S).attr("data-rate",f)):(o.closest("form").find(".price").html(parseFloat(u)),$(".product_"+n+"_retail_radio").prop("checked",!1),$(".product_"+n+"_wholesale_radio").prop("checked",!1),o.attr("data-amount",0).attr("data-rate",0))},parseToJSON(e){return JSON.parse(e)},parseDate(e,a="DD-MM-YYYY"){return At(e).format(a)},disableAddToCartButton(e){let a=this.productCurrentStock.find(function(o){return o.id==e});return a.stock?!(a.association.stock>0):!(this.productCurrentStock.find(function(n){return n.id==a.parent_product_id}).association.stock>0)},clearCart(){let e=this;M.exports.forIn(this.carts,function(a,o){e.currentStock.filter(function(n){a.attributes.product.id==n.id&&(n.association.stock=n.association.stock+a.attributes.weight)})})}},validations(){return{form:{customer:{name:{required:It},phone:{required:It}}}}},setup(){return{v$:Xt()}}},v=e=>(Wt("data-v-729ee5dd"),e=e(),qt(),e),le={class:"row"},ie={class:"col"},ce={class:"card shadow-lg"},de={class:"card-header px-1 font-weight-bold py-1 d-flex align-items-center"},ue=v(()=>t("span",{"data-feather":"truck",class:"mr-2"},null,-1)),me=w(" Current Stock "),he=w("Stock Request"),pe={class:"card-body p-2"},fe={key:0,class:"card rounded-xl shadow-sm border-danger text-danger w-100"},_e={class:"card-header font-weight-bold py-2 text-truncate px-1 small"},ve={class:"card-body p-2 d-flex flex-row justify-content-around"},ge=["src"],be={class:"col"},we={class:"card h-100 shadow-lg"},ye=v(()=>t("div",{class:"card-header p-2 font-weight-bold"},[t("span",{"data-feather":"shopping-bag",class:"mr-2"}),w(" Today's Sales ")],-1)),ke={class:"card-body p-2"},Se={key:0,class:"card rounded-xl shadow-sm border-danger text-danger w-100"},xe={class:"card-header font-weight-bold py-2 text-truncate px-1 small"},Ce={class:"card-body p-2 d-flex flex-row justify-content-around"},Te=["src"],Me={key:1,class:"p-4 mb-0"},Ae={class:"row no-gutters mt-3 shadow-lg overflow-72vh-auto"},Ie={class:"card rounded-0 h-100"},Ne=v(()=>t("div",{class:"card-header"},[t("span",{"data-feather":"user",class:"mr-2"}),w(" Customer Info ")],-1)),De={class:"p-2"},Oe=["disabled"],Be={key:0,class:"input-group input-group-sm"},Re=v(()=>t("div",{class:"input-group-append"},[t("button",{class:"btn btn-outline-danger",type:"submit",id:"button-addon2"},"Save")],-1)),je={key:1,class:"text-danger font-weight-bold text-center my-2"},Le={key:0,class:"table-responsive"},Ee={class:"table table-sm table-striped small table-hover"},Pe=v(()=>t("thead",null,[t("tr",{class:"font-weight-bold"},[t("th",{class:"border-top-0"},"Product"),t("th",{class:"border-top-0"},"Rate"),t("th",{class:"border-top-0"},"Qty"),t("th",{class:"border-top-0"},"Total"),t("th",{class:"border-top-0"})])],-1)),Ve=v(()=>t("sup",null,"INR",-1)),Fe={class:"p-0"},He=["onSubmit"],ze=["value"],Ue=v(()=>t("button",{class:"btn text-danger small p-0",type:"submit"},"x",-1)),Ye=w(" Clear Cart "),We=w(" Generate Bill "),qe={class:"ml-2 small font-weight-bold"},Xe=v(()=>t("sup",null,"INR",-1)),$e={class:"col-6"},Ge={key:0,class:"card rounded-0 border-left-0 border-right-0"},Je=v(()=>t("div",{class:"card-header"}," Billing System ",-1)),Qe={class:"card-body overflow-57vh-auto custom-scrollbar"},Ke={class:"row"},Ze={class:"card shadow mb-3"},ts={class:"card-body p-2"},es=["src"],ss={class:"ml-2"},os=v(()=>t("hr",{class:"my-2"},null,-1)),as={key:0,class:"small-sm text-center"},rs={class:"row no-gutters"},ns={class:"col-5"},ls={class:"form-check"},is=["name"],cs={class:"form-check-label",for:"exampleRadios1"},ds=v(()=>t("small",null,"Retial Rate ",-1)),us=w(),ms=v(()=>t("br",null,null,-1)),hs={class:"badge badge-danger mt-1",style:{"font-size":"9px"}},ps={key:0},fs={class:"col"},_s={class:"form-check"},vs=["name"],gs={class:"form-check-label",for:"exampleRadios1"},bs=v(()=>t("small",null,"Wholesale ",-1)),ws=w(),ys=v(()=>t("br",null,null,-1)),ks=v(()=>t("sup",null,"INR ",-1)),Ss=v(()=>t("hr",{class:"my-2"},null,-1)),xs={key:0,class:"d-block fs-10 ml-4"},Cs=v(()=>t("sup",null," INR ",-1)),Ts={class:"input-group input-group-sm"},Ms=["onUpdate:modelValue","onInput","data-wholesale-range","data-retail-rate","data-product","data-product-id","data-wholesale-weight"],As={class:"input-group-append",id:"button-addon4"},Is={class:"input-group-text"},Ns=v(()=>t("span",{class:"input-group-text price"},[w("0.00 "),t("sup",null,"INR")],-1)),Ds=["disabled"],Os={key:1,class:"card h-100 text-center card-body rounded-0"},Bs=v(()=>t("img",{src:"assets/img/blank_img1.png",alt:"icon",class:"img-fluid w-50 m-auto mb-2"},null,-1)),Rs=v(()=>t("h6",{class:"mt-2"},"Please add customer information to show order list.",-1)),js=[Bs,Rs],Ls={class:"col"},Es={key:0,class:"card h-100 rounded-0"},Ps=v(()=>t("h6",{class:"card-header",style:{"padding-bottom":"13.2px"}}," Purchase History ",-1)),Vs={class:"accordion overflow-57vh-auto custom-scrollbar",id:"accordionExample"},Fs={class:"card-header p-0",id:"headingOne"},Hs={class:"d-flex justify-content-around align-items-center font-weight-bold list-unstyled mb-0 small text-danger"},zs=["data-target","aria-controls"],Us=["id"],Ys={class:"card-body py-1 px-2",style:{"overflow-y":"auto","max-height":"8em"}},Ws={class:"table table-striped table-sm table-hover small-sm"},qs=v(()=>t("thead",null,[t("tr",null,[t("th",{class:"border-0 p-0"},"Product"),t("th",{class:"border-0 p-0"},"quantity"),t("th",{class:"border-0 p-0"},"total"),t("th",{class:"border-0 p-0"},"Receive")])],-1)),Xs=v(()=>t("td",{class:"border-0 p-0"},p("-"),-1)),$s={class:"border-0 p-0"},Gs={class:"border-0 p-0"},Js=v(()=>t("sup",null,null,-1)),Qs={class:"border-0 p-0"},Ks=v(()=>t("sup",null,null,-1)),Zs={key:1,class:"card h-100 text-center card-body rounded-0"},to=v(()=>t("img",{src:"/assets/img/blank_img2.png",alt:"icon",class:"w-50 m-auto"},null,-1)),eo=v(()=>t("h6",null,"No History Available",-1)),so=[to,eo];function oo(e,a,o,n,c,i){const u=U("Head"),d=U("inertia-link"),g=U("slide"),f=U("navigation"),S=U("carousel"),x=U("BreezeAuthenticatedLayout"),I=Yt("maska");return m(),h(D,null,[z(u,{title:"Dashboard"}),z(x,null,{default:R(()=>[t("div",le,[t("div",ie,[t("div",ce,[t("div",de,[ue,me,z(d,{href:e.route("stock.view.request"),class:"btn btn-outline-danger btn-sm ml-auto"},{default:R(()=>[he]),_:1},8,["href"])]),t("div",pe,[z(S,{settings:c.settings},{addons:R(()=>[i.productCurrentStock.length>4?(m(),q(f,{key:0})):j("",!0)]),default:R(()=>[(m(!0),h(D,null,L(i.productCurrentStock,s=>(m(),q(g,{key:s.id,class:"px-2 w-25"},{default:R(()=>[s.stock?(m(),h("div",fe,[t("div",_e,p(s.product_name),1),t("div",ve,[t("img",{src:s.image,alt:"...",width:"20"},null,8,ge),t("b",null,p(e.toDecimal(s.association.stock)+" "+s.weight_unit),1)])])):j("",!0)]),_:2},1024))),128))]),_:1},8,["settings"])])])]),t("div",be,[t("div",we,[ye,t("div",ke,[o.sales.length>0?(m(),q(S,{key:0,settings:c.settings},{addons:R(()=>[o.sales.length>4?(m(),q(f,{key:0})):j("",!0)]),default:R(()=>[(m(!0),h(D,null,L(o.sales,s=>(m(),q(g,{key:s.id,class:"px-2 w-25"},{default:R(()=>[s.product.stock?(m(),h("div",Se,[t("div",xe,p(s.product.product_name),1),t("div",Ce,[t("img",{src:s.product.image,alt:"...",width:"20"},null,8,Te),t("b",null,"\u20B9 "+p(e.toDecimal(s.total_sales)),1)])])):j("",!0)]),_:2},1024))),128))]),_:1},8,["settings"])):(m(),h("h6",Me,"No Sale Found For Today"))])])])]),t("div",Ae,[t("div",null,[t("div",Ie,[Ne,t("div",De,[t("form",{action:"#",method:"POST",onSubmit:a[3]||(a[3]=nt((...s)=>i.saveCustomerForm&&i.saveCustomerForm(...s),["prevent"]))},[t("div",{class:X(["form-group mb-1",{"has-error":n.v$.form.customer.phone.$errors.length}])},[lt(t("input",{placeholder:"Mobile",class:"form-control form-control-sm",id:"customer",autocomplete:"off","onUpdate:modelValue":a[0]||(a[0]=s=>n.v$.form.customer.phone.$model=s),disabled:Object.keys(o.carts).length>0,onMaska:a[1]||(a[1]=(...s)=>i.loadCustomer&&i.loadCustomer(...s))},null,40,Oe),[[it,n.v$.form.customer.phone.$model],[I,"##########"]]),(m(!0),h(D,null,L(n.v$.form.customer.phone.$errors,(s,r)=>(m(),h("small",{key:r,class:"text-danger"},p(s.$message),1))),128))],2),c.existingCustomer?j("",!0):(m(),h("div",Be,[lt(t("input",{type:"text",class:"form-control",placeholder:"Customer Name","aria-label":"Customer Name","aria-describedby":"button-addon2","onUpdate:modelValue":a[2]||(a[2]=s=>n.v$.form.customer.name.$model=s)},null,512),[[it,n.v$.form.customer.name.$model]]),Re])),(m(!0),h(D,null,L(n.v$.form.customer.name.$errors,(s,r)=>(m(),h("small",{key:r,class:"text-danger"},p(s.$message),1))),128)),c.existingCustomer?(m(),h("h6",je,p(c.form.customer.name),1)):j("",!0)],32)]),Object.keys(o.carts).length>0?(m(),h("div",Le,[t("table",Ee,[Pe,t("tbody",null,[(m(!0),h(D,null,L(o.carts,s=>(m(),h("tr",{key:s.id},[t("td",null,p(s.name),1),t("td",null,[w(p(e.toDecimal(s.attributes.rate))+" ",1),Ve]),t("td",null,[w(p(e.toDecimal(s.attributes.weight))+" ",1),t("sup",null,p(s.attributes.product.weight_unit),1)]),t("td",null,p(e.toDecimal(s.price)),1),t("td",Fe,[t("form",{method:"POST",class:"mr-2 small",onSubmit:nt(r=>i.removeCart(r,s),["prevent"])},[t("input",{type:"hidden",value:s.id,name:"id"},null,8,ze),Ue],40,He)])]))),128))])])])):j("",!0),t("div",{class:X(["card-footer p-0 mt-auto",{"d-none":!Object.keys(o.carts).length}])},[z(d,{href:this.route("cart.clear"),onClick:i.clearCart,type:"button",as:"button",method:"POST",class:"btn btn-warning py-0 m-1 small btn-sm px-1"},{default:R(()=>[Ye]),_:1},8,["href","onClick"]),z(d,{href:this.route("cart.list"),type:"button",class:"btn btn-danger py-0 m-1 small btn-sm px-1"},{default:R(()=>[We]),_:1},8,["href"]),t("strong",qe,[Xe,w(" "+p(e.toDecimal(i.totalAmount)),1)])],2)])]),t("div",$e,[c.existingCustomer?(m(),h("div",Ge,[Je,t("div",Qe,[t("div",Ke,[(m(!0),h(D,null,L(o.shop.products,s=>(m(),h("div",{class:"col-6",key:s.product_name},[t("div",Ze,[t("div",ts,[t("img",{src:s.image,alt:"icon",class:"img-fluid mx-auto",width:"20"},null,8,es),t("b",ss,p(s.product_name),1),os,s.rate==null||i.disableAddToCartButton(s.id)?(m(),h("h6",as,"Please Check Product Rate Or Stock ")):(m(),h(D,{key:1},[t("div",rs,[t("div",ns,[t("div",ls,[t("input",{type:"radio",class:X([["tbName","product_"+s.id+"_retail_radio"],"form-check-input"]),name:"product_"+s.id+"_radio",value:"retail","data-btn":"submit1",id:"customRadioInline1"},null,10,is),t("label",cs,[ds,us,ms,t("span",hs,[w(p(s.rate==null?" - ":e.toDecimal(s.rate.retail_rate))+" ",1),s.rate!=null?(m(),h("sup",ps,"INR ")):j("",!0),w(" "+p(" - "+s.weight_unit),1)])])])]),t("div",fs,[t("div",_s,[t("input",{type:"radio",name:"product_"+s.id+"_radio",class:X(["tbName form-check-input","product_"+s.id+"_wholesale_radio"]),value:"wholesale","data-btn":"submit1"},null,10,vs),t("label",gs,[bs,ws,ys,s.rate!=null&&s.rate!=""?(m(!0),h(D,{key:0},L(i.parseToJSON(s.rate.wholesale_rate),r=>(m(),h("span",{class:"badge badge-danger mt-1 font-weight-normal",key:r.id,style:{"font-size":"9px"}},[w(p(e.toDecimal(r.rate))+" ",1),ks,w(" "+p(" : "+e.toDecimal(r.from)+" - ")+" "+p(r.to==5e4?"MAX":e.toDecimal(r.to))+" "+p(s.weight_unit),1)]))),128)):j("",!0)])])])]),Ss,s.stock?j("",!0):(m(),h("small",xs,[w("CR : "+p(e.toDecimal(s.conversion_rate))+" ",1),Cs])),t("form",{action:"#",method:"POST",class:"font-weight-bold mb-0",onSubmit:a[4]||(a[4]=nt((...r)=>i.addToCart&&i.addToCart(...r),["prevent"]))},[t("div",Ts,[lt(t("input",{class:"form-control",style:{width:"80px"},placeholder:"0",name:"weight","onUpdate:modelValue":r=>c.billingWeightInput["product-"+s.id]=r,onInput:r=>i.calateprice(r,s.wholesale_weight_range),"data-wholesale-range":s.rate!=null&&s.rate!=""?s.rate.wholesale_rate:[],"data-retail-rate":s.rate==null?0:s.rate.retail_rate,"data-product":s.product_name,"data-product-id":s.id,"data-wholesale-weight":s.wholesale_weight,autocomplete:"off","data-amount":"0","data-rate":"0","aria-describedby":"button-addon4"},null,40,Ms),[[it,c.billingWeightInput["product-"+s.id]],[I,"#*.##"]]),t("div",As,[t("span",Is,p(s.weight_unit),1),Ns,t("button",{class:X(["btn btn-outline-danger",{"cursor-not-allowed":s.rate==null||i.disableAddToCartButton(s.id)}]),type:"submit",disabled:s.rate==null||i.disableAddToCartButton(s.id)},"ADD",10,Ds)])])],32)],64))])])]))),128))])])])):(m(),h("div",Os,js))]),t("div",Ls,[Object.keys(c.purchaseHistory).length>0&&c.existingCustomer==!0?(m(),h("div",Es,[Ps,t("div",Vs,[(m(!0),h(D,null,L(c.purchaseHistory,(s,r)=>(m(),h("div",{class:"card",key:r},[t("div",Fs,[t("ul",Hs,[t("li",null,[t("button",{class:"btn btn-link btn-block text-left p-1 collapsed border-0 d-flex align-items-center",type:"button","data-toggle":"collapse","data-target":"#collapse"+r,"aria-expanded":"true","aria-controls":"collapse"+r},p(i.parseDate(r,"YY/MM/D")),9,zs)]),t("li",null," Total : \u20B9 "+p(e.toDecimal(c.sumBy(s,"total"))),1),t("li",null," Received : \u20B9 "+p(e.toDecimal(c.sumBy(s,"receive"))),1)])]),t("div",{id:"collapse"+r,class:"collapse","aria-labelledby":"headingOne","data-parent":"#accordionExample"},[t("div",Ys,[t("table",Ws,[qs,t("tbody",null,[(m(!0),h(D,null,L(s,b=>(m(),h("tr",{key:b.id},[Xs,t("td",$s,p(b.quantity),1),t("td",Gs,[w("\u20B9 "+p(e.toDecimal(b.total))+" ",1),Js]),t("td",Qs,[w("\u20B9 "+p(e.toDecimal(b.receive))+" ",1),Ks])]))),128))])])])],8,Us)]))),128))])])):(m(),h("div",Zs,so))])])]),_:1})],64)}var co=$t(ne,[["render",oo],["__scopeId","data-v-729ee5dd"]]);export{co as default};