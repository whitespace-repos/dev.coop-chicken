import{_ as n,B as r,a as i,b as l,c,d}from"./ResponsiveNavLink.4100381f.js";import{L as p,r as _,o as g,c as m,d as a,a as o,w as t,t as h,g as b,F as f,f as u}from"./app.34dda1bd.js";const v={components:{BreezeApplicationLogo:r,BreezeDropdown:i,BreezeDropdownLink:l,BreezeNavLink:c,BreezeResponsiveNavLink:d,Link:p},data(){return{showingNavigationDropdown:!1}}},k={class:"navbar navbar-dark sticky-top bg-danger flex-md-nowrap p-0 shadow"},w=a("img",{src:"/assets/img/logo-bg.png",alt:"logo"},null,-1),B=a("button",{class:"navbar-toggler position-absolute d-md-none collapsed",type:"button","data-bs-toggle":"collapse","data-bs-target":"#sidebarMenu","aria-controls":"sidebarMenu","aria-expanded":"false","aria-label":"Toggle navigation"},[a("span",{class:"navbar-toggler-icon"})],-1),$={class:"text-white heading mb-0"},x={class:"navbar-nav"},L={class:"nav-item text-nowrap"},z=u("Sign out"),N={class:"container-fluid overflow-92vh-auto"},y={class:"pt-3 px-3 pb-2"};function D(e,C,S,V,A,F){const s=_("Link");return g(),m(f,null,[a("header",k,[o(s,{href:e.route("make-sale"),class:"navbar-brand col-md-3 col-lg-2 me-0 px-3 fs-6 py-1"},{default:t(()=>[w]),_:1},8,["href"]),B,a("h5",$,h("COOP - "+e.$page.props.shop.shop_name),1),a("div",x,[a("div",L,[o(s,{href:"/logout",as:"button",method:"post",class:"nav-link px-3 btn btn-link"},{default:t(()=>[z]),_:1})])])]),a("div",N,[a("main",y,[b(e.$slots,"default")])])],64)}var T=n(v,[["render",D]]);export{T as B};
