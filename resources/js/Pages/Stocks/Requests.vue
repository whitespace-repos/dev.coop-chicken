<template>
    <Head title="Dashboard" />
    <BreezeAuthenticatedLayout>
        <section>
            <div class="container">
                <div class="d-flex justify-content-between align-items-center">
                    <a href="#" class="Btn mx-2 btn btn-outline-primary btn-sm" data-toggle="modal" data-target="#myModal"> New Stock Request </a>
                    <Link :href="route('make-sale')" class="heading text-primary">
                        Return Back
                    </Link>
                </div>
                <hr />
                <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <a class="nav-link active" id="pending-link" data-toggle="pill" href="#pending" role="tab" aria-controls="pills-pending" aria-selected="false"><span data-feather="truck" class="mr-2"></span> Pending Stock Request</a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link" id="completed-link" data-toggle="pill" href="#completed" role="tab" aria-controls="pills-completed" aria-selected="false"><span data-feather="check-circle" class="mr-2"></span>  Completed Stock Request</a>
                    </li>
                </ul>
                <div class="tab-content" id="pills-tabContent">
                    <div class="tab-pane fade show active" id="pending" role="tabpanel" aria-labelledby="pills-pending-tab">
                        <div class="accordion" id="accordionExample">
                            <div class="card" v-for="(request,index) in shop.stock_requests.filter((item) => {  return item.status != 'Completed' })" :key="request.id">
                                <div class="card-header p-0" id="headingOne">
                                    <ul class="d-flex justify-content-between align-items-center font-weight-bold list-unstyled mb-0">
                                        <li>
                                            <b class="mx-2">{{ index+1 }}. </b>
                                            <button class="btn btn-link text-left p-1 collapsed border-0 d-inline-flex align-items-center" type="button" data-toggle="collapse" :data-target="'#collapse'+index" aria-expanded="true" :aria-controls="'collapse'+index">
                                                <span data-feather="calendar" class="mr-1"></span> {{ request.date }}
                                            </button>
                                            <!--  -->
                                            <h6 class="my-2 d-inline-flex small-sm font-weight-bold ml-3 text-primary">Status - {{ request.status }}    <sub> on {{ parseDate(request.updated_at) }} </sub> <small v-if="request.status != 'Requested'" class="ml-4">Actual Payment : {{ toDecimal(request.actual_payment) }} <sup>INR</sup></small> <small v-if="request.status == 'Completed'" class="ml-3"> Receive Payment : {{ toDecimal(request.payment_received) }} <sup>INR</sup> </small></h6>
                                        </li>
                                        <li>
                                            <button class="btn btn-primary btn-sm my-1 py-0" data-toggle="modal" data-target="#receiveStockConfirmModal" @click="openReceiveConfirmationModal(request.id)" v-if="request.status == 'Sent'"><span data-feather="check-circle" class="mr-1"></span>  Receive Stock </button>
                                        </li>
                                    </ul>
                                </div>

                                <div :id="'collapse'+index" class="collapse container-fluid" aria-labelledby="headingOne" data-parent="#accordionExample">
                                    <form @submit.prevent="approveStockRequest(request.id)" class="w-100">
                                        <div class="row">
                                            <div class="col-3"  v-for="rp  in request.requested_products" :key="rp.id">
                                                <ul class="list-group list-group-flush shadow-lg my-3">
                                                    <li class="list-group-item font-weight-bold text-center">
                                                        <img :src="rp.product.image" class="img-fluid mr-2" width="20"> <span>{{ rp.product.product_name }}</span>
                                                    </li>
                                                    <li class="list-group-item px-1 text-center small" v-if="request.status != 'Completed'">
                                                        <b v-if="request.type == 'Direct'"><span data-feather="shopping-bag" class="mr-1"></span> {{ toDecimal(rp.stock_sent) +' ' + rp.product.weight_unit }}</b>
                                                        <b  v-else><span data-feather="at-sign" class="mr-1  ml-3"></span> {{ toDecimal(rp.stock_request) +' ' + rp.product.weight_unit }} <sup>INR</sup></b>
                                                        <b v-if="request.status != 'Requested'"> <span data-feather="at-sign" class="mr-1  ml-3"></span>  {{ toDecimal(rp.supply_rate) }} <sup>INR</sup> </b>
                                                    </li>
                                                    <li class="list-group-item p-2 text-center small">
                                                        <span class="font-weight-semibold">Created At <sub> {{ parseDate(request.created_at) }} </sub></span>
                                                    </li>
                                                </ul>
                                            </div>
                                            <div class="col-12">
                                                <form v-if="request.status == 'Approved'" @submit.prevent="savePaymentOptionForStockRequest(request.id)">
                                                    <div class="row">
                                                        <label class="col-12" for="inlineFormInputGroup">Choose Payment Method</label>
                                                        <div class="col-3">
                                                            <select class="form-control form-control-sm p-0" v-model="form.paymentOptions.payment_method">
                                                                <option value="Pay When Stock Received">Pay When Stock Received</option>
                                                                <option value="Pay After Sales Completed">Pay After Sales Completed</option>
                                                            </select>
                                                        </div>
                                                        <div class="col-4 mt-auto">
                                                            <button type="submit" class="btn btn-primary btn-sm px-4">Submit</button>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <div v-if="shop.stock_requests.filter((item) => {  return item.status != 'Completed' }).length == 0">
                            <div class="alert alert-warning" role="alert">
                                    No Record Found !
                            </div>
                        </div>
                    </div>
                    <!--  -->
                    <div class="tab-pane fade" id="completed" role="tabpanel" aria-labelledby="pills-completed-tab">
                        <div class="accordion" id="accordionExample1">
                            <div class="card" v-for="(request,index) in shop.stock_requests.filter((item) => {  return item.status == 'Completed' })" :key="request.id">
                                <div class="card-header p-0" id="headingOne">
                                    <ul class="d-flex justify-content-between align-items-center font-weight-bold list-unstyled mb-0">
                                        <li>
                                            <b class="mx-2">{{ index+1 }}. </b>
                                            <button class="btn btn-link text-left p-1 collapsed border-0 d-inline-flex align-items-center" type="button" data-toggle="collapse" :data-target="'#collapse'+index" aria-expanded="true" :aria-controls="'collapse'+index">
                                                <span data-feather="calendar" class="mr-1"></span> {{ request.date }}
                                            </button>
                                            <!--  -->
                                            <h6 class="my-2 d-inline-flex small-sm font-weight-bold ml-3 text-primary">Status - {{ request.status }}    <sub> on {{ parseDate(request.updated_at) }} </sub> <small v-if="request.status != 'Requested'" class="ml-4">Actual Payment : {{ toDecimal(request.actual_payment) }} <sup>INR</sup></small> <small v-if="request.status == 'Completed'" class="ml-3"> Receive Payment : {{ toDecimal(request.payment_received) }} <sup>INR</sup> </small></h6>
                                        </li>
                                    </ul>
                                </div>

                                <div :id="'collapse'+index" class="collapse container-fluid" aria-labelledby="headingOne" data-parent="#accordionExample1">
                                    <form @submit.prevent="approveStockRequest(request.id)" class="w-100">
                                        <div class="row">
                                            <div class="col-3"  v-for="rp  in request.requested_products" :key="rp.id">
                                                <ul class="list-group list-group-flush shadow-lg my-3">
                                                    <li class="list-group-item font-weight-bold text-center">
                                                        <img :src="rp.product.image" class="img-fluid mr-2" width="20"> <span>{{ rp.product.product_name }}</span>
                                                    </li>
                                                    <li class="list-group-item px-1 text-center small" v-if="request.status == 'Completed'">
                                                        <b ><span data-feather="shopping-bag" class="mr-1"></span> {{ toDecimal(rp.stock_sent) +' ' + rp.product.weight_unit }}</b>
                                                        <b ><span data-feather="at-sign" class="mr-1  ml-3"></span> {{ toDecimal(rp.stock_request) +' ' + rp.product.weight_unit }} <sup>INR</sup></b>
                                                    </li>
                                                    <li class="list-group-item p-2 text-center small">
                                                        <span class="font-weight-semibold">Created At <sub> {{ parseDate(request.created_at) }} </sub></span>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <div v-if="shop.stock_requests.filter((item) => {  return item.status == 'Completed' }).length == 0">
                            <div class="alert alert-warning" role="alert">
                                    No Record Found !
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <div class="modal CmnModal" id="receiveStockConfirmModal">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header py-3 border-primary text-primary align-items-center">
                        <h6 class="m-0" v-if="isNotEmpty(selectedRequest)">Confirm and Modify Stock Detail <small>Actual Payment : <sub>{{ selectedRequest.actual_payment }} </sub> </small></h6>
                        <a href="javascript:void(0)" class="close py-0" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </a>
                    </div>
                    <!-- Modal body -->
                    <div class="modal-body">
                        <template v-if="isNotEmpty(selectedRequest)">
                            <form method="POST" @submit.prevent="ReceiveStockOnShop(selectedRequest.id)" >
                                <table class="table table-bordered table-sm table-striped table-hover">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Product</th>
                                            <th>Requested</th>
                                            <th>Sent</th>
                                            <th>Received</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <!--  -->
                                        <tr v-for="rp in selectedRequest.requested_products" :key="rp.id">
                                            <td>{{ rp.id }} </td>
                                            <td>{{ rp.product.product_name }}</td>
                                            <td>{{ toDecimal(rp.stock_request) + ' ' + rp.product.weight_unit }} </td>
                                            <td>{{ toDecimal(rp.stock_sent) + ' ' + rp.product.weight_unit}}</td>
                                            <td width="160">
                                                <div class="input-group input-group-sm">
                                                    <input v-maska="'#*.##'" class="form-control" id="inlineFormInputGroup" placeholder="Stock Receive" v-model="form.receiveStock.receive_stocks['product-' + rp.id]"/>
                                                    <div class="input-group-prepend">
                                                        <div class="input-group-text">{{ rp.product.weight_unit }}</div>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                                <button type="submit" class="btn btn-outline-primary btn-sm ml-auto"><span data-feather="check-circle" class="mr-1"></span>  Yes ,I Confirm Receive Stock</button>
                            </form>
                        </template>
                    </div>
                </div>
            </div>
        </div>

        <!-- The Modal -->
        <div class="modal" id="myModal">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <form method="POST" @submit.prevent="sendStockRequest" class="mb-0">
                        <!-- Modal Header -->
                        <div class="modal-header py-2 d-flex align-items-center text-primary border-primary">
                            <h6 class="mb-0"><span data-feather="truck" class="mr-2 "></span> Request Stock</h6>
                            <button type="button" class="btn btn-sm btn-link text-primary" data-dismiss="modal">&times;</button>
                        </div>

                        <!-- Modal body -->
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-4"  v-for="product in shop.products" :key="product.id">
                                    <ul class="list-group shadow-lg mb-3" v-if="product.stock">
                                        <li class="list-group-item font-weight-bold small-sm text-truncate px-2"><img  :src="product.image" class="img-fluid mr-2" width="20"/> {{ product.product_name }} <span class="text-primary">({{ toDecimal(product.association.stock) +' '+ product.weight_unit }} )</span></li>
                                        <li class="list-group-item px-2">
                                            <div class="input-group input-group-sm">
                                                <input min="0" onkeypress="return event.charCode >= 48" class="form-control" placeholder="0" v-model="form.stockRequest.products['product-'+product.id]" v-maska="'#*.##'"/>
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text" id="inputGroup-sizing-sm">{{ product.weight_unit }}</span>
                                                </div>
                                            </div>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <!-- Modal footer -->
                        <div class="modal-footer p-0">
                            <button type="submit" class="btn btn-primary btn-sm px-5">Stock Request</button>
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
import _ from 'lodash';
import moment from 'moment'
import { Link } from '@inertiajs/inertia-vue3';


export default {
    components: {
        BreezeAuthenticatedLayout,
        Head,
        Link
    },
    props:['products','shop','carts'],
    data () {
      return {
                form:{
                    paymentOptions:this.$inertia.form({
                                                payment_method:'Pay When Stock Received'
                    }),
                    receiveStock:this.$inertia.form({
                                receive_stocks:{}
                    }),
                    stockRequest : this
                                .$inertia
                                .form({
                                    products : {},
                    })
                },
                selectedRequest:null
      }
    } ,
    mounted(){
        feather.replace({ 'aria-hidden': 'true' })
    },
    methods:{
        openReceiveConfirmationModal(id){
          this.selectedRequest = _.head(_.filter(this.shop.stock_requests, ['id', id]));
          var _this = this;
          _.forEach(this.selectedRequest.requested_products, function(rp) {
            _this.form.receiveStock.receive_stocks['product-' + rp.id] = rp.stock_sent;
          });
        },
        parseDate:function(date) {
            return moment(date).format("ddd, MMM Do YYYY, h:mm:ss a");
        },
        savePaymentOptionForStockRequest(id){
                this.form.paymentOptions.post(this.route('stock.request.payment.option',id), {
                    onSuccess: (response) => {
                                        this.form.paymentOptions.reset('payment_method');
                    },
                })
        },
        ReceiveStockOnShop(id){
            this.form.receiveStock.post(this.route('received.stock',id), {
                onSuccess: (response) => {
                                    this.form.receiveStock.reset();
                                    $("#receiveStockConfirmModal").modal("hide");
                },
            })
        },
        isEmpty:function(o){
          return _.isNil(o);
        },
        isNotEmpty:function(o){
          return !_.isNil(o);
        },
        sendStockRequest(event) {
            this.form.stockRequest.post(this.route('stock.request.submit'), {
                    onSuccess: (response) => {
                            $("#myModal").modal("hide");
                            this.form.stockRequest.products = [];
                            window.history.pushState('data', 'Add to Cart', '/make-sale');
                    }
            });
        }
   }

}
</script>

<style scoped>
  .today_sales .item:last-child > .itemBox{
    border-right:none !important;
  }
  .nav-pills .nav-link.active, .nav-pills .show>.nav-link {
        color: #202123!important;
        background-color: #ebebeb!important;
    }
</style>
