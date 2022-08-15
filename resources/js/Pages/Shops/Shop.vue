<template>
    <Head title="Shops" />
    <BreezeAuthenticatedLayout>
      <div class="row align-items-center justify-content-between">
        <div class="col-auto">
            <h6 class="mb-0"><span data-feather="calendar" class="align-text-bottom mr-2"></span> Shops </h6>
        </div>
        <div class="col-auto">
          <div class="input-group input-group-sm">
            <div class="input-group-prepend">
              <span class="input-group-text">Filter Product</span>
            </div>
            <select class="form-control" @change="filterShop" v-model="filterProduct" >
              <option v-for="item in products" :key="item.id" :value="item.id" :selected="item.id == product.id"> {{ item.product_name }}</option>
            </select>
            <input type="date" @change="filterShop" v-model="filterDate" class="form-control" />
          </div>
        </div>

        <div class="col-auto text-right d-flex align-items-center">
            <h6 class="border-dark btn-sm border rounded-lg small font-weight-bold mr-2 m-0 d-inline-block">
              Stock Request Pending <span class="badge badge-dark">{{ totalStockRequest }}</span>
            </h6>
            <Link class="btn add-btn btn-dark btn-sm" :href="route('shop.create')" >
            <span data-feather="database" class="mr-1 align-text-bottom"></span> Add New Shop</Link>
        </div>
      </div>

      <hr />

      <div class="row">
        <div class="col-auto" v-for="shop in product.shops" :key="shop.id">
          <div class="card shadow-lg mb-3">
            <div class="card-header py-1 px-3 font-weight-bold">
              <span data-feather="file" class="align-text-bottom"></span> {{ shop.shop_name }}
              <Link :href="route('shop.show',shop.id)" class="btn btn-outline-dark py-0 btn-sm float-right ml-5 px-3" > View <span class="badge badge-dark">{{ shopStockReqeustSize(shop) }}</span></Link>
            </div>
             <ul class="list-group list-group-flush">
                <li class="list-group-item d-flex justify-content-between align-items-center py-1">Wholesale Rate <span class="badge badge-dark">&#8377; {{ (product.rate == null) ? 0 : (JSON.parse(product.rate.wholesale_rate).length == 0) ? 0 : toDecimal(JSON.parse(product.rate.wholesale_rate)[0].rate) }}</span></li>
                <li class="list-group-item d-flex justify-content-between align-items-center py-1">Retail Rate <span class="badge badge-dark">&#8377; {{ (product.rate == null) ? 0 : toDecimal(product.rate.retail_rate) }}</span></li>
                <li class="list-group-item d-flex justify-content-between align-items-center py-1">Sale <span class="badge badge-dark">&#8377; {{ toDecimal(shop.today_sale) }}</span></li>
                <li class="list-group-item d-flex justify-content-between align-items-center py-1" v-if="product.stock">Stock <span class="badge badge-dark">{{ toDecimal(shop.association.stock) }} <sup>{{ product.weight_unit }}</sup></span></li>
            </ul>
          </div>
        </div>
      </div>
    </BreezeAuthenticatedLayout>
</template>

<script>
import BreezeAuthenticatedLayout from '@/Layouts/Authenticated.vue'
import { Head , Link } from '@inertiajs/inertia-vue3';
import { forEach , assignIn } from 'lodash';

export default {
    components: {
        BreezeAuthenticatedLayout,
        Head,
        Link,
    },
    props:['prop_products', 'prop_shops', 'prop_product', 'prop_filterProduct', 'prop_filterDate'],
    data () {
              return {
                        products:this.prop_products,
                        shops:this.prop_shops,
                        product:this.prop_product,
                        filterProduct:this.prop_filterProduct,
                        filterDate:this.prop_filterDate
              }
    },
    computed:{
      totalStockRequest(){
          let requestsCount = 0;
          //
          forEach(this.product.shops, function(s) {
            forEach(s.stock_requests,function(r){
              requestsCount += (r.status != "Completed") ? 1 : 0;
            });
          });
          //
          return  requestsCount;
      }
    },
    methods:{
       shopStockReqeustSize(s){
          let requestsCount = 0;
          forEach(s.stock_requests,function(r){
            requestsCount += (r.status != "Completed") ? 1 : 0;
          });
         return  requestsCount;
       },
       filterShop(){
          this.axios.get('/filter/product/shops',
          {
            params: {
                      "id" : this.filterProduct,
                      "date" : this.filterDate,
            }
          }).then(response => {
             assignIn(this.$data,response.data);
             this.$toast.info("Shops filtered basis on product and date.")
          });
       }
    }
}
</script>
