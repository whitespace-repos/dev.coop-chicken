<template>
    <Head title="Dashboard" />
    <BreezeAuthenticatedLayout>
        <div class="card">
            <div class="row">
                <div class="col-md-8 cart">
                    <div class="title">
                        <div class="row">
                            <div class="col">
                                <h4><b>Shopping Cart</b></h4>
                            </div>
                            <div class="col align-self-center text-right text-muted">{{ Object.keys(carts).length }} items</div>
                        </div>
                    </div>
                    <div class="row border-top border-bottom" v-for="cart in carts" :key="cart.id">
                        <div class="row main align-items-center">
                            <div class="col-2"><img class="img-fluid" :src="cart.attributes.product.image"></div>
                            <div class="col">
                                <div class="row text-muted">{{ cart.attributes.product.product_name }}</div>
                                <div class="row">{{ cart.attributes.weight +' '+ cart.attributes.product.weight_unit }}</div>
                            </div>
                            <div class="col">{{ cart.price }}<sup>INR</sup></div>
                        </div>
                    </div>

                    <div class="back-to-shop"><inertia-link :href="route('make-sale')" class="Btn">&leftarrow; Back to shop</inertia-link></div>
                </div>
                <div class="col-md-4 summary">
                    <div>
                        <h5><b>Summary</b></h5>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col" style="padding-left:0;">ITEMS {{ Object.keys(carts).length }}</div>
                        <div class="col text-right">{{ totalAmount }} <sup>INR</sup></div>
                    </div>
                    <form>
                        <div class="form-group">
                            <label class="control-label">Receive Amount </label>
                            <input type="number" class="form-control" v-model="receiveAmount" placeholder="0"/>
                        </div>
                    </form>
                    <div class="row" style="border-top: 1px solid rgba(0,0,0,.1); padding: 2vh 0;">
                        <div class="col">Total Price</div>
                        <div class="col text-right" :class="{'text-line-through':receiveAmount }">{{ totalAmount }} <sup>INR</sup></div>
                    </div>
                    <div class="row" style="border-top: 1px solid rgba(0,0,0,.1); padding: 2vh 0;">
                        <div class="col">Receive Price</div>
                        <div class="col text-right">{{ receiveAmount }} <sup>INR</sup></div>
                    </div><inertia-link :href="route('cart.checkout')"  class="btn  btn-primary rounded p-2" method="post" as="button" type="button" :data="{'receive':receiveAmount,'total':totalAmount}" @click="print">Pay</inertia-link>
                </div>
            </div>
        </div>

    </BreezeAuthenticatedLayout>
</template>

<script>

import BreezeAuthenticatedLayout from '@/Layouts/BillingSystem.vue'
import { Head } from '@inertiajs/inertia-vue3'
import _ from 'lodash'



export default {
    components: {
        BreezeAuthenticatedLayout,
        Head
    },
    props:['carts','items'],
    data () {
      return {
                receiveAmount:0,
            }
      },
    mounted(){
      var _this = this;
    },

    computed:{
        //
        totalAmount: function () {
            let total = 0;
            _.forIn(this.carts, function(o, k) {
                total += o.price;
            });
            return total;
        }
    },
    methods: {
        parseToJSON(data){
            return JSON.parse(data);
        },
        print(){
            let carts = JSON.stringify(this.carts);
            let url = this.route('print-receipt')+"?carts="+carts+"&receiveAmount="+this.receiveAmount;

            let params = `scrollbars=no,resizable=no,status=no,location=no,toolbar=no,menubar=no,width=0,height=0,left=-1000,top=-1000`;
            var w = window.open(url, 'test', params);
            w.window.print();
            w.document.close();
            return false;
        }
    },
}
</script>

<style scoped>
  .today_sales .item:last-child > .itemBox{
    border-right:none !important;
  }

  body {
        background: #ddd;
        min-height: 100vh;
        vertical-align: middle;
        display: flex;
        font-family: sans-serif;
        font-size: 0.8rem;
        font-weight: bold
    }

    .title {
        margin-bottom: 5vh
    }

    .card {
        margin: auto;
        max-width: 950px;
        width: 90%;
        box-shadow: 0 6px 20px 0 rgba(0, 0, 0, 0.19);
        border-radius: 1rem;
        border: transparent
    }

    @media(max-width:767px) {
        .card {
            margin: 3vh auto
        }
    }

    .cart {
        background-color: #fff;
        padding: 4vh 5vh;
        border-bottom-left-radius: 1rem;
        border-top-left-radius: 1rem
    }

    @media(max-width:767px) {
        .cart {
            padding: 4vh;
            border-bottom-left-radius: unset;
            border-top-right-radius: 1rem
        }
    }

    .summary {
        background-color: #ddd;
        border-top-right-radius: 1rem;
        border-bottom-right-radius: 1rem;
        padding: 4vh;
        color: rgb(65, 65, 65)
    }

    @media(max-width:767px) {
        .summary {
            border-top-right-radius: unset;
            border-bottom-left-radius: 1rem
        }
    }

    .summary .col-2 {
        padding: 0
    }

    .summary .col-10 {
        padding: 0
    }

    .row {
        margin: 0
    }

    .title b {
        font-size: 1.5rem
    }

    .main {
        margin: 0;
        padding: 2vh 0;
        width: 100%
    }

    .col-2,
    .col {
        padding: 0 1vh
    }

    a {
        padding: 0 1vh
    }


    .back-to-shop {
        margin-top: 4.5rem
    }

    h5 {
        margin-top: 4vh
    }

    hr {
        margin-top: 1.25rem
    }

    form {
        padding: 2vh 0
    }

    select {
        border: 1px solid rgba(0, 0, 0, 0.137);
        padding: 1.5vh 1vh;
        margin-bottom: 4vh;
        outline: none;
        width: 100%;
        background-color: rgb(247, 247, 247)
    }

    input {
        border: 1px solid rgba(0, 0, 0, 0.137);
        padding: 1vh;
        margin-bottom: 4vh;
        outline: none;
        width: 100%;
        background-color: rgb(247, 247, 247)
    }

    input:focus::-webkit-input-placeholder {
        color: transparent
    }

    .btn {
        background-color: #000;
        border-color: #000;
        color: white;
        width: 100%;
        font-size: 0.7rem;
        margin-top: 4vh;
        padding: 1vh;
        border-radius: 0
    }

    .btn:focus {
        box-shadow: none;
        outline: none;
        box-shadow: none;
        color: white;
        -webkit-box-shadow: none;
        -webkit-user-select: none;
        transition: none
    }

    .btn:hover {
        color: white
    }

    a {
        color: black
    }

    a:hover {
        color: black;
        text-decoration: none
    }

    #code {
        background-image: linear-gradient(to left, rgba(255, 255, 255, 0.253), rgba(255, 255, 255, 0.185)), url("https://img.icons8.com/small/16/000000/long-arrow-right.png");
        background-repeat: no-repeat;
        background-position-x: 95%;
        background-position-y: center
    }
    .text-line-through{
        text-decoration: line-through;
    }
</style>
