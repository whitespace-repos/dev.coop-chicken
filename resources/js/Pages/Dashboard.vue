<template>
    <Head title="Dashboard" />
    <BreezeAuthenticatedLayout>
        <h6><span data-feather="users" class="align-text-bottom"></span> Supplier Detail</h6>
        <hr />
        <!--  main-panel-->
        <div class="alert alert-warning" role="alert" v-if="suppliers.length == 0 && auth.isAdmin">
            No supplier found. Go to Users section and add  supplier.
        </div>

        <div class="accordion" id="accordionExample" >
            <div class="card" v-for="(supplier , indx ) in suppliers" :key="indx">
                <div class="card-header p-0" :id="'headingOne'+indx">
                    <h2 class="mb-0">
                        <button class="btn btn-link btn-block text-left" :class="{'collapsed':(indx != 0)}" type="button" data-toggle="collapse" :data-target="'#collapseOne'+indx" aria-expanded="true" :aria-controls="'collapseOne'+indx">
                        Supplier  <i> ( {{ supplier.name }} ) </i> Detail
                        </button>
                    </h2>
                </div>

                <div :id="'collapseOne'+indx" class="collapse" :class="{'show':(indx == 0)}" :aria-labelledby="'headingOne'+indx" data-parent="#accordionExample">
                    <div class="card-body px-0 pt-0">
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
                        <div class="px-2">
                            <h6 v-if="supplier.shops.length > 0" class="text-underline my-3" style="text-decoration:underline"> Shop associated with supplier </h6>
                            <h6 v-else class="text-dark mt-3 mb-0" style="text-decoration:underline"> No shop associated with supplier </h6>

                            <div class="accordion" :id="'accordionShops'+indx">
                                <div class="card" v-for="(shop,index) in supplier.shops" :key="index">
                                    <div class="card-header p-0" :id="'shop'+index">
                                        <h2 class="mb-0">
                                            <button class="btn btn-link btn-block text-left collapsed small"  type="button" data-toggle="collapse" :data-target="'#collapseShop'+shop.id" aria-expanded="true" :aria-controls="'collapseShop'+shop.id">
                                            <kbd>{{ '#' + (index + 1) }} </kbd> {{ shop.shop_name }}
                                            </button>
                                        </h2>
                                    </div>

                                    <div :id="'collapseShop'+shop.id" class="collapse fade" :aria-labelledby="'shop'+index" :data-parent="'#accordionShops'+indx">
                                        <div class="card-body p-0">
                                            <table class="table table-sm table-striped small-sm m-0">
                                                <thead>
                                                    <tr>
                                                        <th>Product</th>
                                                        <th>Today Retail Rate</th>
                                                        <th>Today Wholesale Rate</th>
                                                        <th>Today Sale</th>
                                                        <th>Current Stock</th>
                                                        <th  class="text-right"><inertia-link :href="route('shop.show',shop.id)">View {{ shop.shop_name }} Shop Detail</inertia-link></th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr v-for="product in shop.products" :key="product.id">
                                                        <td>{{ product.product_name }}</td>
                                                        <td>{{ (product.rate == null) ? toDecimal(0) : toDecimal(product.rate.retail_rate) }} <sup>INR</sup> </td>
                                                        <td>{{ (product.rate == null) ? toDecimal(0) : (JSON.parse(product.rate.wholesale_rate).length == 0) ? toDecimal(0) : toDecimal(JSON.parse(product.rate.wholesale_rate)[0].rate) }} <sup>INR</sup> </td>
                                                        <td>{{ toDecimal(product.today_sale) }} <sup>INR</sup> </td>
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
    </BreezeAuthenticatedLayout>
</template>

<script>
import BreezeAuthenticatedLayout from '@/Layouts/Authenticated.vue'
import { Head } from '@inertiajs/inertia-vue3';
import _ from 'lodash'

export default {
    props:['suppliers'],
    components: {
        BreezeAuthenticatedLayout,
        Head,
    }
}
</script>