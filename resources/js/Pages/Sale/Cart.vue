<template>
    <Head title="Dashboard" />
    <BreezeAuthenticatedLayout>
        <div class="card mb-4" id="summary">
            <div class="row">
                <div class="col-7 cart">
                    <div class="title">
                        <div class="row">
                            <div class="col">
                                <h4><b>Shopping Cart</b></h4>
                            </div>
                            <div class="col align-self-center text-right text-muted">{{ Object.keys(carts).length }} items</div>
                        </div>
                    </div>
                    <div class="row border-top border-bottom" v-for="cart in carts" :key="cart.id">
                        <div class="row main align-items-center py-2">
                            <div class="col-auto"><img class="img-fluid w-50" :src="cart.attributes.product.image"></div>
                            <div class="col">
                                <div class="row text-muted">{{ cart.attributes.product.product_name }}</div>
                                <div class="row">{{ cart.attributes.weight +' '+ cart.attributes.product.weight_unit }}</div>
                            </div>
                            <div class="col" v-currency>{{ cart.price }}</div>
                        </div>
                    </div>
                    <div class="back-to-shop"><inertia-link :href="route('make-sale')" class="Btn">&leftarrow; Back to shop</inertia-link></div>
                </div>
                <div class="col-5 summary bg-secondary text-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="m-0"><b>Summary</b></h5>
                        <span><b>{{ payment_type }} Amount</b> : <span>&#8377;  {{ toDecimal((totalAmount + customer.due_amount ) - receiveAmount) }}</span></span>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col" style="padding-left:0;">ITEMS {{ Object.keys(carts).length }}</div>
                        <div class="col text-right" v-currency>{{ totalAmount }}</div>
                    </div>

                    <div class="form-group my-4" :class="{'has-error':v$.receiveAmount.$errors.length }">
                        <label class="control-label">Receive Amount </label>
                        <input v-maska="'#*.##'" class="form-control m-0" v-model="v$.receiveAmount.$model" placeholder="0"/>
                        <small v-for="error of v$.receiveAmount.$errors" :key="error.$uid" v-html="error.$message"></small>
                    </div>
                    <div class="form-group text-center font-weight-bold">
                        <div class="custom-control custom-radio custom-control-inline ">
                            <input type="radio" id="round_off" name="payment_type" v-model="payment_type" class="custom-control-input" value="Round Off">
                            <label class="custom-control-label cursor-pointer" for="round_off" style="cursor:pointer;">Round Off</label>
                        </div>
                        <div class="custom-control custom-radio custom-control-inline cursor-pointer">
                            <input type="radio" id="discount" name="payment_type" v-model="payment_type" class="custom-control-input"  value="Discount">
                            <label class="custom-control-label cursor-pointer" for="discount" style="cursor:pointer;">Discount</label>
                        </div>
                        <div class="custom-control custom-radio custom-control-inline cursor-pointer">
                            <input type="radio" id="pending" name="payment_type" v-model="payment_type" class="custom-control-input"  value="Pending">
                            <label class="custom-control-label cursor-pointer" for="pending" style="cursor:pointer;">Pending</label>
                        </div>
                    </div>

                    <div class="row" style="border-top: 1px solid rgba(0,0,0,.1); padding: 1vh 0;">
                        <div class="col font-weight-bold">Total Price</div>
                        <div class="col text-right"  v-currency>{{ totalAmount }}</div>
                    </div>

                    <div class="row" style="border-top: 1px solid rgba(0,0,0,.1); padding: 1vh 0;">
                        <div class="col font-weight-bold">Past Due Amount</div>
                        <div class="col text-right" v-currency>{{ customer.due_amount }}</div>
                    </div>

                    <div class="row" style="border-top: 1px solid rgba(0,0,0,.1); padding: 1vh 0;">
                        <div class="col font-weight-bold">Grand Total</div>
                        <div class="col text-right"  v-currency>{{toDecimal(totalAmount + customer.due_amount) }}</div>
                    </div>

                    <div class="row" style="border-top: 1px solid rgba(0,0,0,.1); padding: 1vh 0;">
                        <div class="col font-weight-bold">Receive Price</div>
                        <div class="col text-right">
                            &#8377;  {{ toDecimal(receiveAmount) }}
                        </div>
                    </div>
                    <div class="row" style="border-top: 1px solid rgba(0,0,0,.1); padding: 1vh 0;" v-if="(payment_type == 'Discount' || payment_type == 'Pending')  && v$.receiveAmount.$invalid == false">
                        <div class="col font-weight-bold">{{ payment_type }} Amount</div>
                        <div class="col text-right">
                            &#8377;  {{ toDecimal((totalAmount + customer.due_amount ) - receiveAmount) }}
                        </div>
                    </div>

                    <button class="btn  btn-primary rounded p-2 font-weight-bold" @click="printAndProceed" :disabled="v$.receiveAmount.$invalid">Pay Offline</button>
                    <hr />
                    <button class="btn  btn-primary rounded p-2 font-weight-bold" @click="payOnline" :disabled="v$.receiveAmount.$invalid">Pay Online</button>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-8  mx-auto">
                <div class="card my-5" id="receipt-content" style="display:none">
                    <div class="card-body">
                        <div class="brand-logo text-center mb-4">
                            <img src="/assets/img/guest-logo.png" alt="logo" class="mx-auto" width="80"/>
                        </div>

                        <div class="d-flex justify-content-between">
                            <span>No: {{ `coop-cps-${Math.round(new Date().getTime() / 1000)}` }}</span>
                            <span class="mr-3">04/09/22 10:00 AM</span>
                        </div>
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Item</th>
                                    <th>Qty</th>
                                    <th width="100" class="text-right">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(cart , index) in carts" :key="index">
                                    <td><kbd style="padding: 0.02rem 0.3rem;">#{{index}}</kbd></td>
                                    <td>{{ cart.attributes.product.product_name }}</td>
                                    <td>{{ cart.attributes.weight +' '+ cart.attributes.product.weight_unit }}</td>
                                    <td v-currency class="text-right">{{ cart.price }}</td>
                                </tr>
                            </tbody>
                        </table>

                        <hr  class="mt-5"/>

                        <div class="ml-2 mr-1 mb-5">

                            <div class="row" style="padding: .4vh 0;">
                                <div class="col font-weight-bold">Total</div>
                                <div class="col text-right">&#8377; {{ totalAmount }}</div>
                            </div>

                            <div class="row" style="padding: .4vh 0;">
                                <div class="col font-weight-bold">PAY AMOUNT</div>
                                <div class="col text-right">&#8377; {{ toDecimal(receiveAmount) }}</div>
                            </div>

                            <div class="row" style="padding: .4vh 0;">
                                <div class="col font-weight-bold">Past Due Amount</div>
                                <div class="col text-right">&#8377; {{ customer.due_amount }}</div>
                            </div>

                            <div class="row" style="padding: .4vh 0;">
                                <div class="col font-weight-bold">Grand Total</div>
                                <div class="col text-right">&#8377; {{toDecimal(totalAmount + customer.due_amount) }}</div>
                            </div>

                            <div class="row" style="padding: .4vh 0;" v-if="payment_type=='Pending' && v$.receiveAmount.$invalid">
                                <div class="col font-weight-bold">{{ payment_type }} Amount</div>
                                <div class="col text-right">&#8377; {{ toDecimal(totalAmount-receiveAmount) }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div id="print-area"></div>
    </BreezeAuthenticatedLayout>
</template>

<script>

import BreezeAuthenticatedLayout from '@/Layouts/BillingSystem.vue'
import { Head } from '@inertiajs/inertia-vue3'
import {forEach , _ , assignIn } from 'lodash'
import useVuelidate from '@vuelidate/core'
import { required , maxValue, minValue} from '@vuelidate/validators'


export default {
    components: {
        BreezeAuthenticatedLayout,
        Head
    },
    props:['carts','items','customer'],
    data () {
      return {
                receiptNo : `coop-cps-${Math.round(new Date().getTime() / 1000)}`,
                receiveAmount:0,
                payment_type:"Round Off",
                customerId:this.customer.id,
                orderId:null,
                paymentId:null,
            }
      },
    mounted(){
      var _this = this;
      this.receiveAmount = this.totalAmount + this.customer.due_amount;
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
    validations() {
        return {
                    receiveAmount : {
                                        maxValue:maxValue(this.totalAmount + this.customer.due_amount),
                                        minValue:minValue(1) ,
                                        required
                                }
        }
    },
    setup () {
        return { v$: useVuelidate() }
    },


    methods: {
        parseToJSON(data){
            return JSON.parse(data);
        },
        printAndProceed() {
            if(this.v$.receiveAmount.$invalid){
                this.v$.receiveAmount.$touch();
                return
            }
            //
            document.getElementById("navbar").style.display="none";
            document.getElementById("summary").style.display="none";
            document.getElementById('receipt-content').style.display="block";
            window.document.title = "COOP Payment Receipt";
            window.print();
            //
            this.$inertia.form({"payment_id":this.paymentId,'receive': this.receiveAmount,'total':this.totalAmount,'customer_id':this.customerId,'payment_type':this.payment_type}).post(this.route('cart.checkout'));
        },
        payOnline(e){
            let notes = {};
            forEach(this.carts, function(item) {
                let value = item.attributes.product.product_name + ' => ₹ ' + item.price +' - '+ item.quantity +' '+ item.attributes.product.weight_unit;
                let obj = { 'Item' : value };
                assignIn(notes,obj);
            });
            //
            var _this = this;
            var options = {
                "receipt":this.receiptNo,
                "key": "rzp_test_RHigXN4roHTXEx",
                "amount": this.receiveAmount * 100,
                "name": this.customer.name,
                "description": "A Wild Sheep Chase is the third novel by Japanese author Haruki Murakami",
                "image": "https://dev.coop-chicken.in/assets/img/guest-logo.png",
                "prefill": {
                    "name": this.customer.name,
                    "email": this.customer.phone+'@coop-chicken.in',
                    "contact": this.customer.phone
                },
                "notes": notes,
                "handler": function (response){
                    _this.paymentId = response.razorpay_payment_id;
                    _this.$toast.success("Payment done successfully.");
                    _this.printAndProceed();
                },
                "modal": {
                    "ondismiss": function(){
                        _this.$toast.warning("Payment not done please try again or pay offline.");
                    }
                }
            };
            var rzp1 = new Razorpay(options);
            rzp1.open();
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
