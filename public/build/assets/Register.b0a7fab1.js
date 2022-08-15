import{q as p,p as f,w as m,o as c,a as s,u as o,H as _,d as l,L as w,n as V,s as v,f as d}from"./app.34dda1bd.js";import{_ as b}from"./Button.ad1bb19d.js";import{_ as g}from"./Guest.36f0f31a.js";import{_ as r}from"./Input.9bccca05.js";import{_ as y,a as i}from"./ValidationErrors.446b4ecf.js";const x=["onSubmit"],k={class:"mt-4"},h={class:"mt-4"},q={class:"mt-4"},$={class:"flex items-center justify-end mt-4"},N=d(" Already registered? "),U=d(" Register "),P={__name:"Register",setup(B){const e=p({name:"",email:"",password:"",password_confirmation:"",terms:!1}),n=()=>{e.post(route("register"),{onFinish:()=>e.reset("password","password_confirmation")})};return(u,a)=>(c(),f(g,null,{default:m(()=>[s(o(_),{title:"Register"}),s(y,{class:"mb-4"}),l("form",{onSubmit:v(n,["prevent"])},[l("div",null,[s(i,{for:"name",value:"Name"}),s(r,{id:"name",type:"text",class:"mt-1 block w-full",modelValue:o(e).name,"onUpdate:modelValue":a[0]||(a[0]=t=>o(e).name=t),required:"",autofocus:"",autocomplete:"name"},null,8,["modelValue"])]),l("div",k,[s(i,{for:"email",value:"Email"}),s(r,{id:"email",type:"email",class:"mt-1 block w-full",modelValue:o(e).email,"onUpdate:modelValue":a[1]||(a[1]=t=>o(e).email=t),required:"",autocomplete:"username"},null,8,["modelValue"])]),l("div",h,[s(i,{for:"password",value:"Password"}),s(r,{id:"password",type:"password",class:"mt-1 block w-full",modelValue:o(e).password,"onUpdate:modelValue":a[2]||(a[2]=t=>o(e).password=t),required:"",autocomplete:"new-password"},null,8,["modelValue"])]),l("div",q,[s(i,{for:"password_confirmation",value:"Confirm Password"}),s(r,{id:"password_confirmation",type:"password",class:"mt-1 block w-full",modelValue:o(e).password_confirmation,"onUpdate:modelValue":a[3]||(a[3]=t=>o(e).password_confirmation=t),required:"",autocomplete:"new-password"},null,8,["modelValue"])]),l("div",$,[s(o(w),{href:u.route("login"),class:"underline text-sm text-gray-600 hover:text-gray-900"},{default:m(()=>[N]),_:1},8,["href"]),s(b,{class:V(["ml-4",{"opacity-25":o(e).processing}]),disabled:o(e).processing},{default:m(()=>[U]),_:1},8,["class","disabled"])])],40,x)]),_:1}))}};export{P as default};
