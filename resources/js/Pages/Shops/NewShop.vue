<template>
    <Head title="Dashboard" />

    <BreezeAuthenticatedLayout>
      <h6 class="mb-0"><span data-feather="calendar" class="align-text-bottom mr-2"></span> Add New Store </h6>
      <hr />
      <!-- main-panel -->
      <form method="POST" @submit.prevent="saveShop" class="font-weight-bold">
        <div class="card card-body shadow-lg">
          <div class="row">
            <div class="form-group col-md-4" >
              <template v-if="auth.isAdmin">
                <label>Choose Supplier</label>
                <select v-model="form.shop.supplier_id" class="form-control" @change="loadSupplierProducts">
                  <option :value="supplier.id" v-for="supplier in suppliers" :key="supplier.id">{{ supplier.name }}</option>
                </select>
              </template>
              <template v-else>
                  <label>Supplier</label>
                  <input :value="auth.user.name" class="form-control" disabled readonly/>
              </template>
            </div>


            <!-- -->
            <div class="form-group col-md-4" :class="{ 'has-error': v$.form.shop.products.$errors.length }">
              <label>Select Product *</label>
              <v-select
                v-model="v$.form.shop.products.$model"
                :options="productsList"
                :get-option-key="(option) => option.id"
                :get-option-label="(option) => option.product_name"
                :key="(option) => option.id"
                multiple
              >
              </v-select>
              <template v-for="(error, index) of v$.form.shop.products.$errors" :key="index">
                <small>{{ error.$message }}</small>
              </template>
            </div>

            <div class="form-group col-md-4" :class="{ 'has-error': v$.form.shop.shop_name.$errors.length }">
              <label>Shop Full Name *</label>
              <input v-model="v$.form.shop.shop_name.$model" placeholder="Type shop name here" class="form-control" />
              <template v-for="(error, index) of v$.form.shop.shop_name.$errors" :key="index">
                <small>{{ error.$message }}</small>
              </template>
            </div>

            <!-- -->
            <div class="form-group col-md-4" :class="{ 'has-error': v$.form.shop.address.$errors.length }">
              <label>Shop Address *</label>
              <input v-model="v$.form.shop.address.$model" placeholder="Type shop address here" class="form-control" />
              <template v-for="(error, index) of v$.form.shop.address.$errors" :key="index">
                <small>{{ error.$message }}</small>
              </template>
            </div>

            <!-- -->
            <div class="form-group col-md-4" :class="{ 'has-error': v$.form.shop.shop_dimentions.$errors.length }">
              <label>Shop Dimentions *</label>
              <input v-model="v$.form.shop.shop_dimentions.$model" placeholder="Type shop dimentions here" class="form-control" />
              <template v-for="(error, index) of v$.form.shop.shop_dimentions.$errors" :key="index">
                <small>{{ error.$message }}</small>
              </template>
            </div>

            <!-- -->
            <div class="form-group col-md-4" :class="{ 'has-error': v$.form.shop.stock_capacity_per_day.$errors.length }">
              <label>Stock Capacity Per Day *</label>
              <input v-model="v$.form.shop.stock_capacity_per_day.$model" placeholder="Type stock capacity here (per day)" class="form-control" v-maska="'#*.##'"/>
              <template v-for="(error, index) of v$.form.shop.stock_capacity_per_day.$errors" :key="index">
                <small>{{ error.$message }}</small>
              </template>
            </div>
            <!-- -->
            <div class="form-group col-md-4" :class="{ 'has-error': v$.form.shop.max_sale_estimate_per_day.$errors.length }">
              <label>Max Sale Estimate<label class="small mb-0">(amount calculated in INR)</label> *</label>
              <input v-model="v$.form.shop.max_sale_estimate_per_day.$model" placeholder="Type max sale estimate here" class="form-control"  v-maska="'#*.##'"/>
              <template v-for="(error, index) of v$.form.shop.max_sale_estimate_per_day.$errors" :key="index">
                <small>{{ error.$message }}</small>
              </template>
            </div>

              <!-- -->
            <div class="form-group col-md-4" :class="{ 'has-error': v$.form.shop.distance_from_cps.$errors.length }">
              <label>Distance From CPS *</label>
              <input v-model="v$.form.shop.distance_from_cps.$model" placeholder="Type distance here" class="form-control"  v-maska="'#*.##'"/>
              <template v-for="(error, index) of v$.form.shop.distance_from_cps.$errors" :key="index">
                <small>{{ error.$message }}</small>
              </template>
            </div>

              <!-- -->
            <div class="form-group col-md-4" :class="{ 'has-error': v$.form.shop.estimated_start_date.$errors.length }">
              <label>Estimated Start Date *</label>
              <input  placeholder="Type max sale estimate here" class="form-control" type="date" v-model="v$.form.shop.estimated_start_date.$model"/>
              <template v-for="(error, index) of v$.form.shop.estimated_start_date.$errors" :key="index">
                <small>{{ error.$message }}</small>
              </template>
            </div>

              <!-- -->
            <div class="form-group col-md-4" :class="{ 'has-error': v$.form.shop.phone.$errors.length }">
              <label>Contact Number *</label>
              <input v-model="v$.form.shop.phone.$model" class="form-control"  v-maska="'#*##'"/>
              <template v-for="(error, index) of v$.form.shop.phone.$errors" :key="index">
                <small>{{ error.$message }}</small>
              </template>
            </div>

            <!-- -->
            <div class="col-md-12">
              <div class="form-group py-1">
                  <button class="btn btn-primary px-5 my-4" type="submit" :disabled="v$.form.shop.$invalid"><span data-feather="database" class="mr-2 align-text-bottom"></span> Save New Shop</button>
              </div>
            </div>
          </div>
        </div>
      </form>
    </BreezeAuthenticatedLayout>
</template>

<script>
import BreezeAuthenticatedLayout from '@/Layouts/Authenticated.vue'
import { Head } from '@inertiajs/inertia-vue3';
import useVuelidate from '@vuelidate/core'
import { required, email, minLength , numeric , integer ,helpers} from '@vuelidate/validators'

export default {
    components: {
        BreezeAuthenticatedLayout,
        Head,
    },
    props:['products','suppliers','auth'],
    data () {
        return {
                  productsList:this.products,
                  form:{
                        shop:this
                                  .$inertia
                                  .form({
                                          shop_name:'',
                                          address:'',
                                          distance_from_cps:'',
                                          shop_dimentions:'',
                                          stock_capacity_per_day:'',
                                          max_sale_estimate_per_day:'',
                                          estimated_start_date:'',
                                          products:[],
                                          phone:'',
                                          supplier_id:'',
                        })
                  }

        }
    },
    methods: {
        saveShop() {
            this.form.shop.post(this.route('shop.store'), {
                onSuccess: () => {
                                    this.form.shop.reset();
                },
            })
        },
        loadSupplierProducts(){
          axios.get(this.route('supplier.products.list',this.form.shop.supplier_id))
                .then(response => {
                  this.form.shop.products = [];
                  this.productsList = response.data.products;
                  this.$toast.info("All products loaded associated with supplier !")
                });
        }
    },
    mounted() {
      setTimeout( ()=>{
        this.form.shop.supplier_id = this.auth.user.id;
      },1000);
    },
    validations() {
      return {
              form:{
                        shop:{
                                shop_name:{required},
                                address:{required},
                                shop_dimentions:{required},
                                stock_capacity_per_day:{required , numeric},
                                max_sale_estimate_per_day:{required , numeric},
                                phone:{required , numeric},
                                products:{required},
                                estimated_start_date:{required},
                                distance_from_cps:{required , numeric}
                      }
                }
      }
    },
    setup () {
      return { v$: useVuelidate() }
    }
}
</script>
