<template>
    <Head title="Dashboard" />
    <BreezeAuthenticatedLayout>
      <div class="main-panel">
        <div class="d-flex justify-content-between">
          <h6><span data-feather="users" class="mr-2"></span> Supplier Detail </h6>
        </div>

        <hr class="w-100 border-dark bg-dark "/>


        <div class="accordion" id="accordionExample">
            <div class="card">
                <div class="card-header p-0" id="headingOne">
                    <h2 class="mb-0">
                        <button class="btn btn-link btn-block text-left" type="button" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                         Supplier Detail
                        </button>
                    </h2>
                </div>

                <div id="collapseOne" class="collapse show" aria-labelledby="headingOne" data-parent="#accordionExample">
                    <div class="card-body p-0">
                        <table class="table table-sm table-striped">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Contact</th>
                                    <th>Email</th>
                                    <th> Total Shops </th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>{{ supplier.name }}</td>
                                    <td>{{ supplier.phone }}</td>
                                    <td>{{ supplier.email }}</td>
                                    <td>{{ supplier.shops.length }} </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-header p-0" id="headingTwo">
                    <h2 class="mb-0">
                        <button class="btn btn-link btn-block text-left collapsed" type="button" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                            Shops associated with supplier ( {{ supplier.shops.length }} )
                        </button>
                    </h2>
                </div>
                <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionExample">
                    <div class="card-body p-1 shadow-lg">
                        <div class="alert alert-warning p-2" role="alert" v-if="supplier.shops.length == 0">
                            No Data Found In Data.
                        </div>
                        <div class="accordion" id="accordionShops">
                            <div class="card" v-for="(shop,index) in supplier.shops" :key="index">
                                <div class="card-header p-0" :id="'shop'+index">
                                    <h2 class="mb-0">
                                        <button class="btn btn-link btn-block text-left" :class="{'collapsed':(index != 0)}" type="button" data-toggle="collapse" :data-target="'#collapseShop'+index" aria-expanded="true" :aria-controls="'collapseShop'+index">
                                           <kbd>{{ '#' + (index + 1) }} </kbd> {{ shop.shop_name }}
                                        </button>
                                    </h2>
                                </div>

                                <div :id="'collapseShop'+index" class="collapse" :class="{'show':(index == 0)}" :aria-labelledby="'shop'+index" data-parent="#accordionShops">

                                    <div class="card-body p-0">
                                        <table class="table table-sm table-striped">
                                            <thead>
                                                <tr>
                                                    <th>Product</th>
                                                    <th>Today Retail Rate</th>
                                                    <th>Today Wholesale Rate</th>
                                                    <th>Current Stock</th>
                                                    <th  class="text-right"><inertia-link :href="route('shop.show',shop.id)">View {{ shop.shop_name }} Shop Detail</inertia-link></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr v-for="product in shop.products" :key="product.id">
                                                    <td>{{ product.product_name }}</td>
                                                    <td>{{ (product.rate == null) ? toDecimal(0) : toDecimal(product.rate.retail_rate) }} <sup>INR</sup> </td>
                                                    <td>{{ (product.rate == null) ? toDecimal(0) : (JSON.parse(product.rate.wholesale_rate).length == 0) ? toDecimal(0) : toDecimal(JSON.parse(product.rate.wholesale_rate)[0].rate) }} <sup>INR</sup> </td>
                                                    <td colspan="2">{{ toDecimal(product.association.stock) }} <sup>{{ product.weight_unit }} </sup></td>
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
      <!--  main-panel-->
    </BreezeAuthenticatedLayout>
</template>


<style>
  .btn:focus,.btn:hover{
    transition:none !important;
    text-decoration:none !important;
  }
</style>

<script>
import BreezeAuthenticatedLayout from '@/Layouts/Authenticated.vue'
import { Head } from '@inertiajs/inertia-vue3';
import _ from 'lodash'

export default {
    props:['supplier'],
    components: {
        BreezeAuthenticatedLayout,
        Head,
    }
}
</script>