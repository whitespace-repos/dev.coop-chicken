<template>
    <Head title="Dashboard" />
    <BreezeAuthenticatedLayout>
    <div class="row">
        <div class="col">
            <div class="card shadow-lg">
                <div class="card-header px-1 font-weight-bold py-1 d-flex align-items-center ">
                    <span data-feather="truck" class="mr-2"></span> Current Stock
                    <inertia-link :href="route('stock.view.request')" class="btn btn-outline-primary btn-sm ml-auto">Stock Request</inertia-link>
                </div>
                <div class="card-body p-2 px-3">
                    <carousel :settings="settings" >
                        <slide v-for="product in productCurrentStock" :key="product.id" class="px-2 w-25">
                            <template v-if="product.stock">
                                <div class="card rounded-xl shadow-sm border-primary text-primary w-100">
                                    <div class="card-header font-weight-bold py-2 text-truncate px-1 small">{{ product.product_name }}</div>
                                    <div class="card-body p-2 d-flex flex-row justify-content-center">
                                        <img :src="product.image" alt="..." width="20" class="mr-2"/>
                                        <b class="text-truncate">{{ toDecimal(product.association.stock) +' ' + product.weight_unit }}</b>
                                    </div>
                                </div>
                            </template>
                        </slide>

                        <template #addons >
                            <navigation v-if="productCurrentStock.length > 5"/>
                        </template>
                    </carousel>
                </div>
            </div>
        </div>
        <div class="col">
            <div class="card h-100 shadow-lg">
                <div class="card-header p-2 font-weight-bold">
                    <span data-feather="shopping-bag" class="mr-2"></span> Today's Sales
                </div>
                <div class="card-body p-2 px-3">
                    <template v-if="sales.length > 0">
                        <carousel :settings="settings" >
                            <slide v-for="sale  in sales" :key="sale.id" class="px-2 w-25">
                                <template v-if="sale.product.stock">
                                    <div class="card rounded-xl shadow-sm border-primary text-primary w-100">
                                        <div class="card-header font-weight-bold py-2 text-truncate px-1 small">{{ sale.product.product_name }}</div>
                                        <div class="card-body p-2 d-flex flex-row justify-content-around">
                                            <img :src="sale.product.image" alt="..." width="20" />
                                            <b>&#8377; {{ toDecimal(sale.total_sales) }} </b>
                                        </div>
                                    </div>
                                </template>
                            </slide>

                            <template #addons >
                                <navigation v-if="sales.length > 5"/>
                            </template>
                        </carousel>
                    </template>
                    <template v-else>
                        <h6 class="p-4 mb-0">No Sale Found For Today</h6>
                    </template>
                </div>
            </div>
        </div>
    </div>

    <div class="row no-gutters mt-3 shadow-lg billing-screen">
        <div>
            <div class="card rounded-0 h-100">
                <div class="card-header"><span data-feather="user" class="mr-2"></span> Customer Info </div>
                <div class="p-2 overflow-hiddenss33q">
                    <form action="#" method="POST" @submit.prevent="saveCustomerForm">
                        <div class="form-group mb-1" :class="{ 'has-error': v$.form.customer.phone.$errors.length }">
                            <input placeholder="Mobile" class="form-control form-control-sm" id="customer" autocomplete="off" v-model="v$.form.customer.phone.$model" :disabled="Object.keys(carts).length > 0" v-maska="'##########'" @maska="loadCustomer">
                            <template v-for="(error, index) of v$.form.customer.phone.$errors" :key="index">
                                <small class="text-primary">{{ error.$message }}</small>
                            </template>
                        </div>
                        <div class="input-group input-group-sm" v-if="!existingCustomer">
                            <input type="text" class="form-control" placeholder="Customer Name" aria-label="Customer Name" aria-describedby="button-addon2" v-model="v$.form.customer.name.$model" >
                            <div class="input-group-append">
                                <button class="btn btn-outline-primary" type="submit" id="button-addon2">Save</button>
                            </div>
                        </div>
                            <template v-for="(error, index) of v$.form.customer.name.$errors" :key="index">
                            <small class="text-primary">{{ error.$message }}</small>
                        </template>
                        <h6 v-if="existingCustomer" class="text-primary font-weight-bold text-center my-2">{{ form.customer.name }}</h6>
                    </form>
                </div>
                <div class="table-responsive custom-scrollbar" v-if="Object.keys(carts).length > 0">
                    <table class="table table-sm table-striped small table-hover">
                        <thead>
                            <tr class="font-weight-bold">
                                <th class="border-top-0">Product</th>
                                <th class="border-top-0">Rate</th>
                                <th class="border-top-0">Qty</th>
                                <th class="border-top-0">Total</th>
                                <th class="border-top-0"></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="cart in carts" :key="cart.id">
                                <td>{{ cart.name }}</td>
                                <td>{{ toDecimal(cart.attributes.rate) }} <sup>INR</sup> </td>
                                <td>{{ toDecimal(cart.attributes.weight) }} <sup>{{ cart.attributes.product.weight_unit }}</sup></td>
                                <td>{{ toDecimal(cart.price) }}</td>
                                <td class="p-0">
                                    <form  method="POST" class="mr-2 small" @submit.prevent="removeCart($event,cart)">
                                        <input type="hidden" :value="cart.id" name='id' />
                                        <button class="btn text-primary small p-0" type="submit">x</button>
                                    </form>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="card-footer p-0 mt-auto" :class="{'d-none':!Object.keys(carts).length}">
                    <inertia-link :href="this.route('cart.clear')" @click="clearCart" type="button" as="button" method="POST" class="btn btn-warning py-0 m-1 small btn-sm px-1"> Clear Cart </inertia-link>
                    <inertia-link :href="this.route('cart.list')"  type="button" class="btn btn-primary py-0 m-1 small btn-sm px-1"> Generate Bill </inertia-link>
                    <strong class="ml-2 small font-weight-bold"><sup>INR</sup> {{ toDecimal(totalAmount) }}</strong>
                </div>
            </div>
        </div>
        <div class="col">
                <div class="card rounded-0 border-left-0 border-right-0 h-100" v-if="existingCustomer">
                    <div class="card-header"> Billing System </div>
                    <div class="card-body  custom-scrollbar">
                        <div class="row">
                            <div class="col-6" v-for="product in shop.products" :key="product.product_name">
                                <div class="card shadow mb-3">
                                     <div class="card-body p-2">
                                        <img :src="product.image" alt="icon" class="img-fluid mx-auto" width="20"/>
                                        <b class="ml-2">{{ product.product_name}}</b>
                                        <hr class="my-2"/>
                                        <template v-if="(product.rate == null || disableAddToCartButton(product.id))">
                                            <h6 class="small-sm text-center">Please Check Product Rate Or Stock </h6>
                                        </template>
                                        <template v-else>
                                            <div class="row no-gutters">
                                                <div class="col-5">
                                                    <div class="form-check">
                                                        <input type="radio"  :class="['tbName' , 'product_' +product.id+ '_retail_radio' ]" :name="'product_'+product.id+'_radio'" value="retail" data-btn="submit1"   id="customRadioInline1" name="customRadioInline" class="form-check-input" />
                                                        <label class="form-check-label" for="exampleRadios1">
                                                            <small>Retial Rate </small> <br />
                                                            <span class="badge badge-primary mt-1" style="font-size:9px">{{ (product.rate == null ) ? ' - ' : toDecimal(product.rate.retail_rate) }} <sup v-if="product.rate != null">INR </sup>  {{ ' - ' + product.weight_unit  }} </span>
                                                        </label>
                                                    </div>
                                                </div>

                                                <div class="col">
                                                    <div class="form-check">
                                                        <input type="radio" :name="'product_'+product.id+'_radio'"  :class="['tbName form-check-input' , 'product_' +product.id+ '_wholesale_radio' ]" value="wholesale" data-btn="submit1" />
                                                        <label class="form-check-label" for="exampleRadios1">
                                                            <small>Wholesale </small> <br />
                                                            <template v-if="product.rate != null && product.rate != ''">
                                                                <span class="badge badge-primary mt-1 font-weight-normal" v-for="range in parseToJSON(product.rate.wholesale_rate)" :key="range.id" style="font-size:9px">{{ toDecimal(range.rate) }} <sup>INR </sup> {{ ' : ' + toDecimal(range.from) +' - ' }} {{ (range.to == 50000) ? 'MAX' : toDecimal(range.to)  }}  {{ product.weight_unit }}</span>
                                                            </template>
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                            <hr  class="my-2"/>
                                            <small class="d-block fs-10 ml-4" v-if="!product.stock">CR : {{ toDecimal(product.conversion_rate) }} <sup> INR </sup></small>
                                            <form action="#" method="POST" class="font-weight-bold mb-0" @submit.prevent="addToCart">
                                                <div class="input-group input-group-sm">
                                                    <input  class="form-control" style="width:80px" placeholder="0"  name="weight" v-model="billingWeightInput['product-'+product.id]" @input="calateprice($event,product.wholesale_weight_range)" :data-wholesale-range="(product.rate != null && product.rate != '') ? product.rate.wholesale_rate : []"  :data-retail-rate="(product.rate == null ) ? 0 : product.rate.retail_rate" :data-product="product.product_name" :data-product-id="product.id" :data-wholesale-weight="product.wholesale_weight" autocomplete="off" data-amount="0" data-rate="0" aria-describedby="button-addon4" v-maska="product.mask"/>
                                                    <div class="input-group-append" id="button-addon4">
                                                        <span class="input-group-text">{{product.weight_unit}}</span>
                                                        <span class="input-group-text price">0.00 <sup>INR</sup></span>
                                                        <button class="btn btn-outline-primary" type="submit" :class="{'cursor-not-allowed': (product.rate == null || disableAddToCartButton(product.id))}" :disabled="product.rate == null || disableAddToCartButton(product.id)">ADD</button>
                                                    </div>
                                                </div>
                                            </form>
                                        </template>
                                        <!--  -->
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card h-100 text-center card-body rounded-0" v-else>
                    <img src="assets/img/blank_img1.png" alt="icon" class="img-fluid w-50 m-auto mb-2" />
                    <h6 class="mt-2">Please add customer information to show order list.</h6>
                </div>
                <!--  -->
        </div>
        <div class="col-auto w-30">
            <template v-if="customer != null">
                <div class="card h-100 rounded-0" v-if="customer.purchased_history.length > 0 && existingCustomer == true">
                    <h6 class="card-header" style="padding-bottom: 13.2px;">
                        <div class="d-flex justify-content-between">
                            <strong>Purchase History </strong>
                            <span class="text-danger"><b>Due Amount</b> : &#8377; {{ customer.due_amount }} </span>
                        </div>
                    </h6>
                    <div class="accordion   custom-scrollbar" id="accordionExample" >
                        <div class="card  border-0 rounded-0" v-for="(historyArr,key) in groupBy(customer.purchased_history,'date')" :key="key">
                            <div class="card-header p-0" id="headingOne">
                                <ul class="d-flex justify-content-around align-items-center font-weight-bold list-unstyled mb-0 small text-primary">
                                    <li>
                                        <button class="btn btn-link btn-block text-left p-1 collapsed border-0 d-flex align-items-center" type="button" data-toggle="collapse" :data-target="'#collapse'+key" aria-expanded="true" :aria-controls="'collapse'+key">
                                            <Icon icon="radix-icons:calendar" />  {{ parseDate(key,'DD-MMM , YY') }}
                                        </button>
                                    </li>
                                </ul>
                            </div>

                            <div :id="'collapse'+key" class="collapse" aria-labelledby="headingOne" data-parent="#accordionExample">
                                <div class="card-body  py-1 px-0  custom-scrollbar" style="overflow-y: auto; max-height:12em;">
                                    <div class="accordion   custom-scrollbar" id="accordionExample2" >
                                        <div class="card border-top-0 border-left-0 border-right-0 rounded-0 border-bottom" v-for="(history,index) in historyArr" :key="index">
                                            <div class="card-header p-0" id="heading">
                                                <ul class="d-flex justify-content-around align-items-center font-weight-bold list-unstyled mb-0 small text-primary">
                                                    <li>
                                                        <button class="btn btn-link btn-block text-left p-1 collapsed border-0 d-flex align-items-center" type="button" data-toggle="collapse" :data-target="'#collapse'+index" aria-expanded="true" :aria-controls="'collapse'+index" style="font-size:12px">
                                                            {{ parseDate(history.created_at,'DD-MMM, YY hh:mmA') }}
                                                        </button>
                                                    </li>
                                                    <li style="line-height: 1.2;"  class="mx-1">
                                                        <small class="font-weight-bold">Total &nbsp; &nbsp; &nbsp; : &nbsp;  &#8377; {{ toDecimal(history.total) }} </small> <br />
                                                        <small class="font-weight-bold" v-if="history.past_due_amount > 0">Past Due  :&nbsp;   &#8377; {{ toDecimal(history.past_due_amount) }} </small>
                                                    </li>

                                                    <li style="line-height: 1.2;" class="mx-1">
                                                        <small class="font-weight-bold">Grand &nbsp; &nbsp; :&nbsp;   &#8377; {{ toDecimal(history.total + history.past_due_amount) }} </small> <br />
                                                        <small class="font-weight-bold">Received  :&nbsp;   &#8377; {{ toDecimal(history.receive) }} </small>
                                                    </li>
                                                    <li style="line-height: 1.2;">
                                                        <div class="d-flex flex-column text-center">
                                                            <small class="font-weight-bold" v-if="isNil(history.payment_id)">Offline</small>
                                                            <small class="font-weight-bold text-secondary" v-else>Online</small>
                                                            <span class="badge badge-primary mx-auto" style="font-size: 8px;margin-top: 2px;" v-if="history.payment_type == 'Pending'">P</span>
                                                            <span class="badge badge-primary mx-auto"  style="font-size: 8px;margin-top: 2px;" v-else-if="history.payment_type == 'Discount'">D</span>
                                                            <span class="badge badge-primary mx-auto"  style="font-size: 8px;margin-top: 2px;" v-else="history.payment_type == 'Round Off'">R</span>
                                                        </div>
                                                    </li>
                                                    <li v-if="moment(moment().format('Y-MM-DD')).isSame(parseDate(key,'Y-MM-DD'))"><a href="javascript:void(0)" class="mx-1" @click="editHistoryModal(history)"><Icon icon="mdi:database-edit" /></a></li>
                                                </ul>
                                            </div>

                                            <div :id="'collapse'+index" class="collapse" aria-labelledby="headingOne" data-parent="#accordionExample2">
                                                <div class="card-body p-2">
                                                    <table class="table table-striped table-sm table-hover small-sm mb-0">
                                                        <thead>
                                                            <tr>
                                                                <th class="border-0 p-0">Product</th>
                                                                <th class="border-0 p-0">Quantity</th>
                                                                <th class="border-0 p-0">Total</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <tr v-for="sale in history.sales" :key="sale.id" >
                                                                <td class="border-0 p-0">{{ sale.product.product_name }}</td>
                                                                <td class="border-0 p-0">{{ sale.quantity +' '+ sale.product.weight_unit }}</td>
                                                                <td class="border-0 p-0">&#8377; {{ toDecimal(sale.total) }} <sup> </sup></td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </template>
            <div class="card h-100 text-center card-body rounded-0" v-else>
                <img src="/assets/img/blank_img2.png" alt="icon" class=" w-50 m-auto">
                <h6>No History Available</h6>
            </div>
        </div>
    </div>

        <!-- The Modal -->
        <div class="modal" id="editHistoryModal" data-backdrop="static">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content">
                    <form method="POST" @submit.prevent="updateHistory" class="mb-0">
                        <!-- Modal Header -->
                        <div class="modal-header py-2 d-flex align-items-center text-primary border-primary">
                            <h6 class="mb-0"><Icon icon="radix-icons:calendar" /> Purchase History Detail</h6>
                            <a href="javascript:void(0)" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </a>
                        </div>

                        <!-- Modal body -->
                        <div class="modal-body">
                            <p v-if="v$.form.historyObj.$invalid" class="text-danger">Invalid Data please check before proceed .</p>

                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th class="w-20">Date</th>
                                        <th>Item</th>
                                        <th>Rate</th>
                                        <th>Quantity</th>
                                        <th>Total</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-light">
                                    <template v-if="!isNil(form.historyObj.history)">
                                        <tr v-for="(item,index) in form.historyObj.history" :key="index" >
                                            <td>{{ item.date }}</td>
                                            <td>{{ item.item }}</td>
                                            <td>&#8377; {{ item.rate }}</td>
                                            <td class="border-0 p-0" width="100">
                                                <div class="input-group input-group-sm px-2">
                                                    <input type="text" class="form-control" placeholder="Username" aria-label="Username" aria-describedby="basic-addon1" v-model="v$.form.historyObj.history[index].quantity.$model" @input="form.historyObj.history[index].total = form.historyObj.history[index].quantity * item.rate" v-maska="item.maska">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text" id="basic-addon1">{{ item.weight_unit }}</span>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="border-0 p-0" width="100">
                                                <div class="input-group input-group-sm px-2">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text" id="basic-addon1">&#8377;</span>
                                                    </div>
                                                    <input type="text" class="form-control" placeholder="Username" aria-label="Username" aria-describedby="basic-addon1" disabled readonly v-model="form.historyObj.history[index].total">
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th colspan="4"  class="text-right">Total</th>
                                            <td class="border-0 p-0" width="100">
                                                <div class="input-group input-group-sm px-2">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text" id="basic-addon1">&#8377;</span>
                                                    </div>
                                                    <input type="text" class="form-control" placeholder="Username" aria-label="Username" aria-describedby="basic-addon1" v-model="v$.form.historyObj.total.$model">
                                                </div>
                                            </td>
                                        </tr>
                                    </template>
                                </tbody>
                            </table>
                        </div>

                        <!-- Modal footer -->
                        <div class="modal-footer p-0">
                            <button type="submit" class="btn btn-primary btn-sm px-5" :disabled="v$.form.historyObj.$invalid">Update History</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </BreezeAuthenticatedLayout>
</template>

<script>
import BreezeAuthenticatedLayout from '@/Layouts/BillingSystem.vue'
import { Head } from '@inertiajs/inertia-vue3'
import { assignIn ,get , values , forIn , merge , isNaN , isNil , find , sumBy , groupBy, forEach , set , assign, sum } from 'lodash'
import moment from 'moment'
import useVuelidate from '@vuelidate/core'
import { required , helpers , maxValue, between, minValue} from '@vuelidate/validators'
import 'vue3-carousel/dist/carousel.css';
import { Carousel, Slide, Pagination, Navigation } from 'vue3-carousel';

export default {
    components: {
        BreezeAuthenticatedLayout,
        Head,
        moment,
        Carousel,
        Slide,
        Pagination,
        Navigation
    },
    props:['products','shop','carts','sales'],
    data () {
      return {
                groupBy,
                isNil,
                sumBy,
                moment,
                phoneMaskComplete:false,
                showModal: false,
                // carousel settings
                settings: {
                    itemsToShow: 5,
                    snapAlign: 'center',
                },

                existingCustomer:false,
                token:$("[name='csrf-token']").attr("content"),
                formUrl:"{{ route('customer.store') }}",
                rate:0,
                purchaseHistory:[],
                customer:null,
                cartFlag:false,
                paymentMethod:'EMI',
                currentStock:[],
                billingWeightInput:{},
                form:{
                        customer :this
                                    .$inertia
                                    .form({
                                            name:null,
                                            email:null,
                                            location:null,
                                            phone:null,
                        }),
                        cart :this
                                    .$inertia
                                    .form({
                                            product:"",
                                            customer:"",
                                            weight:0,
                                            amount:0,
                                            rate:0,
                                            type:''
                        }),
                        removeCart : this
                                        .$inertia
                                        .form({
                                            id : ""
                        }),
                        stockRequest : this
                                        .$inertia
                                        .form({
                                            products : {},

                        }),
                        historyObj : this
                                        .$inertia
                                        .form({
                                           history : [],
                                           total:0,
                                           receive:0,
                                           id:null

                        }),
                        selectedHistory:null

                },
            }
      },
    mounted(){
        //



      //
      feather.replace({ 'aria-hidden': 'true' });
      var _this = this;
      //
      this.currentStock = this.shop.products;
      //
      if(Object.keys(this.carts).length > 0 ){
          this.existingCustomer = true;
          this.form.customer = get(values(this.carts), 0).attributes.customer;
          //
          forIn(this.carts, o => {
            let prod = this.currentStock.find(function(p){ return p.id == o.attributes.product.id; });
            if(prod.stock){
                prod.association.stock -= o.attributes.weight;
            }else{
                let parent = this.currentStock.find(function(p){ return p.id == o.attributes.product.parent_product_id; });
                parent.association.stock -= ( o.attributes.weight / prod.conversion_rate );
            }
          });
      }
    },
    computed:{
            // Make a request for a user with a given ID
            // get only
            totalAmount: function () {
                //
                let total = 0;
                forIn(this.carts, function(o, k) {
                    total += o.price;
                });
                return total;
            },
            productCurrentStock(){
                return this.currentStock;
            },
            historyValidation(){
                let validation= [];
                this.form.historyObj.history.filter(function(obj){
                    let ob = {quantity : { between:between(1,obj.max) , required }};
                    //
                    validation.push(ob);
                });
                return validation;
            }
      },
      methods: {
        loadCustomer(event){
            if(event.target.value.length == 10 ){
                if(this.phoneMaskComplete == false){
                    this.phoneMaskComplete = true;
                    //
                    this.axios
                        .post(this.route('customer.existance'),{ phone:event.target.value })
                        .then(response => {
                            this.existingCustomer = response.data.existingCustomer;
                            if(this.existingCustomer)  {
                                assignIn(this.form.customer,response.data.customer);
                                this.$toast.success("Customer Detail Loaded !")
                                feather.replace({ 'aria-hidden': 'true' });
                                this.customer = response.data.customer;
                            }else{
                                this.$toast.warning("Customer not found !")
                            }
                        });
                }
            }else{
               this.existingCustomer = false;
               this.form.customer.name = "";
               this.phoneMaskComplete = false;
            }
        },
        saveCustomerForm() {
            if(this.v$.form.customer.$invalid){
                this.v$.form.customer.$touch();
                return
            }
            //
            this.form.customer.post(this.route('customer.store',), {
                only: ["existingCustomer","customer","totalCartItem"],
                onSuccess: (response) => {
                                    this.form.customer.reset('name');
                                    window.history.pushState('data', 'Save Customer', '/make-sale');
                                    //
                                    this.existingCustomer = response.props.existingCustomer;
                                    //
                                    if(this.existingCustomer)
                                        merge(this.form.customer,response.props.customer);

                                    window.history.pushState('data', 'Add to Cart', '/make-sale');
                },
            })
          },
          addToCart(event){
              //
              var $form = $(event.target),
                  $el = $form.find('[name=weight]');
            //
            let _this = this;
            let InsufficientStock = false;
            this.form.cart.customer = this.form.customer.phone;
            this.form.cart.product = $el.data('productId');
            this.form.cart.weight = $el.val();
            //
            let prod = this.productCurrentStock.find(function(p){ return p.id == _this.form.cart.product; });

            if(prod.stock){
                if(prod.association.stock < $el.val())
                    InsufficientStock = true;
            }else{
                let parent = this.productCurrentStock.find(function(p){ return p.id == prod.parent_product_id; });
                //
                if(parent.association.stock < $el.val())
                    InsufficientStock = true;
            }

            //
            if(InsufficientStock){
                this.$toast.warning("Insufficient Stock ! Please request for stock !")
                return false;
            }
            //

            this.form.cart.amount = $el.data('amount');
            this.form.cart.rate = $el.data('rate');
            this.form.cart.type = $("[name=product_"+this.form.cart.product+"_radio]:checked").val();

                this.form.cart.post(this.route('cart.store'), {
                    only: ["carts"],
                    onSuccess: (response) => {
                                    window.history.pushState('data', 'Add to Cart', '/make-sale');
                                    //
                                    this.beep();
                                    if(prod.stock){
                                        prod.association.stock = prod.association.stock - _this.form.cart.weight;
                                    }else{
                                        let parent = this.productCurrentStock.find(function(p){ return p.id == prod.parent_product_id; });
                                        parent.association.stock = parent.association.stock -  ( _this.form.cart.weight / prod.conversion_rate );
                                    }
                                    //
                                    this.billingWeightInput["product-"+this.form.cart.product] = 0;
                                    $el.closest('form').find(".price").html(parseFloat(0));
                                    $(".product_"+_this.form.cart.product+"_retail_radio").prop("checked",true);
                                    this.$toast.success("Product added to cart successfully !")
                    },
                })
          },
          removeCart(event,item){
              var  $form = $(event.target),
                    $el = $form.find("[name='id']");
              this.form.removeCart.id = $el.val();
              this.form.removeCart.post(this.route('cart.remove') ,{
                    only: ["carts"],
                    onSuccess: (response) => {
                                    window.history.pushState('data', 'Remove from Cart', '/make-sale');
                                    //
                                    this.clearSound();
                                    let _this = this;
                                    //
                                    let prod = this.currentStock.find(function(p){ return p.id == item.attributes.product.id; });
                                    if(prod.stock){
                                        prod.association.stock += item.attributes.weight;
                                    }else{
                                        let parent = this.currentStock.find(function(p){ return p.id == prod.parent_product_id; });
                                        //
                                        parent.association.stock += ( item.attributes.weight / prod.conversion_rate );
                                    }
                    },
                })
          },
          calateprice(event , wholesale_weight_range){
                let $el = $(event.target) ,
                product = $el.data('product'),
                productId = $el.data('productId'),
                prod = this.shop.products.find(function(p){ return p.id == productId; }),
                orignalWeight = parseFloat($el.val()),
                weight = (prod.stock) ? parseFloat($el.val()) : ( parseFloat($el.val()) / prod.conversion_rate ),
                wholesale = (wholesale_weight_range) ? find($el.data('wholesaleRange'), function(r) { return  (r.from <= weight && weight <= r.to) ; }) : null,
                retail = $el.data('retailRate'),
                rate = (isNaN(wholesale) || isNil(wholesale)) ? parseFloat(retail) : parseFloat(wholesale.rate),
                totalCost = Number(parseFloat(rate) * parseFloat(weight)).toFixed(2);
                //
                if(isNaN(wholesale) || isNil(wholesale)){
                    $(".product_"+productId+"_retail_radio").prop("checked",true);
                }else{
                    $(".product_"+productId+"_wholesale_radio").prop("checked",true);
                }
                //
                if(weight > 0){
                    if(prod.stock)
                        $el.closest('form').find(".price").html(parseFloat(totalCost)+"<sup>INR</sup>");
                    else
                        $el.closest('form').find(".price").html("<small class='fs-10'>"+orignalWeight+" / " + prod.conversion_rate + " * " + rate +" = </small>"+parseFloat(totalCost)+"<sup>INR</sup>");
                    //
                    $el.attr('data-amount',totalCost).attr('data-rate',rate);
                }else{
                    $el.closest('form').find(".price").html(parseFloat(weight));
                    $(".product_"+productId+"_retail_radio").prop("checked",false);
                    $(".product_"+productId+"_wholesale_radio").prop("checked",false);
                    $el.attr('data-amount',0).attr('data-rate',0);
                }
          },
          parseToJSON(data){
               return JSON.parse(data);
          },
          parseDate(d,f = 'DD-MM-YYYY'){
              return moment(d).format(f);
          },
          disableAddToCartButton(id){
            let product = this.productCurrentStock.find(function(p){ return p.id == id; });
            if(product.stock){
                return (product.association.stock > 0 ) ? false : true ;
            }else{
                let parent = this.productCurrentStock.find(function(p){ return p.id == product.parent_product_id; });
                return (parent.association.stock > 0 ) ? false : true ;
            }
          },
          clearCart(){
                let _this = this;
                this.clearSound();
                //
                forIn(this.carts, function(o, k) {
                    _this.currentStock.filter(function(s){
                        if(o.attributes.product.id == s.id){
                            s.association.stock = s.association.stock + o.attributes.weight;
                        }
                    })
                });
        },
        editHistoryModal(history){
            this.form.historyObj.history = [];
            this.form.selectedHistory = null;
            //
            this.form.selectedHistory = history;
            forEach(history.sales, sale => {
                this.form.historyObj.history.push({sale_id: sale.id,date:history.date, item : sale.product.product_name  , rate : sale.rate , quantity : sale.quantity , total : sale.total , weight_unit : sale.product.weight_unit , maska:sale.product.mask , max:sale.quantity})
            })

            this.form.historyObj.total = sumBy(history.sales,'total');
            this.form.historyObj.receive = sumBy(history.receive,'receive');
            this.form.historyObj.id = history.id;
            //
            console.log(this.form.historyObj.history);
            $("#editHistoryModal").modal("show");
        },
        updateHistory(){
            this.form.historyObj.post(this.route('update.purchase.history'),{preserveState: false });
            $("#editHistoryModal").modal("hide");
        }
    },
    validations() {
        return {
                form:{
                      customer :{
                                            name:{required},
                                            phone:{required}
                      },
                      historyObj:{
                            history:this.historyValidation,
                            total:{required,between:between(1,(this.form.selectedHistory != null ) ? this.form.selectedHistory.total : 1 )},
                            receive:{}
                      }

                }
        }
    },
    setup () {
        return { v$: useVuelidate() }
    }
}
</script>

<style scoped>
  .today_sales .item:last-child > .itemBox{
    border-right:none !important;
  }
  .cursor-not-allowed{
      cursor:not-allowed;
  }

  .fs-10{
        font-size: 10px;
        font-weight: 600;
  }
</style>
