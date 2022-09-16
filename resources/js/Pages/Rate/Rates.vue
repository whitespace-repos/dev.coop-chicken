<template>
    <Head title="Dashboard" />

    <BreezeAuthenticatedLayout>
      <h6 class="d-flex align-items-center mb-1">
        <span data-feather="calendar" class="align-text-bottom mr-2"></span> Set / Update Today's Rate
      </h6>
      <small class="text-white ml-4">Date: <strong>{{ currentDate }}</strong></small>

      <hr />

      <form method="POST"  @submit.prevent="addRate" class="font-weight-bold shadow-lg p-3 rounded-lg org-shadow bg-white">
        <div class="row">
          <div class="col-md-8">
            <div class="form-group" :class="{'has-error':v$.form.rate.product_id.$error}">
                <label> Select Product </label>
                <select class="form-control" name="product_id" v-model="v$.form.rate.product_id.$model" @change="this.$inertia.get('/rate/'+$event.target.value)">
                  <option v-for="product in products" :key="product.id" :value="product.id">{{ product.product_name }}</option>
                </select>
            </div>
          </div>
          <div class="col-md-4">
            <div class="form-group" :class="{ 'has-error': v$.form.rate.retail_rate.$errors.length }">
                <label>Retail Rate </label>
                <input v-maska="'#*.##'" class="form-control" name="retail_rate" v-model="v$.form.rate.retail_rate.$model"/>
                <template v-for="(error, index) of v$.form.rate.retail_rate.$errors" :key="index">
                  <small>{{ error.$message }}</small>
                </template>
            </div>
          </div>
        </div>
        <div class="row">
            <div class="col" v-for="(range , index ) in selectedProduct.weight_ranges" :key="range.id" >
              <div class="form-group" v-if = "range.from != 0 || range.to != 0 " :class="{'has-error':v$.form.rate.range['range-'+range.id].$error}">
                  <label>Range {{index + 1 }} : <label class="badge badge-primary font-weight-normal"> {{ toDecimal(range.from) + ' - ' }} {{ (range.to == 50000) ? 'MAX' : toDecimal(range.to)  }} {{ selectedProduct.weight_unit }} </label> </label>
                  <input v-maska="'#*.##'" class="form-control" :name="range.id" v-model="v$.form.rate.range['range-'+range.id].$model"/>
                  <small v-if="v$.form.rate.range['range-'+range.id].$error">{{ 'Please Enter a Valid Rate' }} </small>
              </div>
            </div>
          <div class="col-md-12">
            <hr />
            <button class="btn btn-primary px-5" type="submit" :disabled="v$.form.rate.$invalid"><span data-feather="database" class="mr-2 align-text-bottom"></span> Save Rate</button>
          </div>
        </div>
      </form>

      <div class="row">
        <div class="col">
          <div class="card">
            <div class="card-header bg-white heading m-0">
              Today's Rate chart
            </div>
            <div class="card-body p-0">
              <div class="table-responsive">
                <table cellpadding="0" cellspacing="0"  class="table mb-0 table-striped table-sm table-hover">
                  <thead>
                    <tr>
                      <th>Product</th>
                      <th>Date</th>
                      <th>Wholesale Rate</th>
                      <th>Retail Rate</th>
                      <th>Status</th>
                    </tr>
                  </thead>
                  <tbody>
                    <template v-if="rates.length > 0">
                      <tr v-for="r in rates" :key="r.id">
                        <td>{{ r.product.product_name }} <sup v-if="r.shop" class="font-weight-bold text-secondary">( {{ r.shop.shop_name }} )</sup></td>
                        <td>{{ r.date }}</td>
                        <td>
                          <template v-if="r.wholesale_rate != null && r.wholesale_rate != '[]'">
                            <template  v-for="(range,index) in parseToJSON(r.wholesale_rate)" :key="range.id">
                              <span class="badge badge-primary font-weight-normal mr-2" v-if="index==0">
                                  {{toDecimal(range.from) +" - "+ toDecimal(range.to) +" "+ r.product.weight_unit }} : <icon :icon="$page.props.currency" /> {{  toDecimal(range.rate) }}
                              </span>
                            </template>
                          </template>
                        </td>
                        <td> <span class="badge badge-primary font-weight-normal "><icon :icon="$page.props.currency" />  {{  toDecimal(r.retail_rate) }} {{ " / "+ r.product.weight_unit }} </span> </td>
                        <td> <span class="badge badge-primary font-weight-normal ">{{ r.status }} - {{ r.type }} </span> </td>
                      </tr>
                    </template>
                    <template v-else>
                      <tr>
                        <td colspan="6" class="p-3 text-center"> No Record Found.</td>
                      </tr>
                    </template>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
        <div class="px-3">
          <v-calendar is-expanded
                        title-position="left"
                        show-weeknumbers="right"
                        @dayclick="getRateDetail"
              />
        </div>
      </div>
    </BreezeAuthenticatedLayout>
</template>

<script>
import BreezeAuthenticatedLayout from '@/Layouts/Authenticated.vue'
import { Head } from '@inertiajs/inertia-vue3';
import useVuelidate from '@vuelidate/core'
import { required, helpers , requiredIf, minValue} from '@vuelidate/validators'
const rateValidation = helpers.regex(/^\d+(?:\.\d{1,2})?$/);
import { set , assign , slice, assignIn} from 'lodash'


export default {
    setup () {
      return { v$: useVuelidate() }
    },
    components: {
        BreezeAuthenticatedLayout,
        Head,
    },
    computed:{
        currentDate() {
            const current = new Date();
            const date = `${current.getDate()}-${current.getMonth()+1}-${current.getFullYear()}`;
            return date;
        },
        defaultWholesaleWeightValidation(){
            let validation= {};
            //
            if(this.selectedProduct.wholesale_weight_range == 0){
              return {
                        rateValidation:helpers.withMessage('Please Enter a Valid Rate',rateValidation),
                        required: requiredIf(this.selectedProduct.wholesale_weight_range == 0)
                    };
            }

            return validation;
        },
        weightRangeValidation(){
            let validation= {};
            //
            if(this.selectedProduct.wholesale_weight_range == 1){
              this.selectedProduct.weight_ranges.filter(function(obj,index){
                  let key = "range-"+obj.id;
                  let ObjString = '{"range-'+obj.id+'" : null}';
                  let Objest = JSON.parse(ObjString);
                  //
                  set(Objest, key,{
                    rateValidation:helpers.withMessage('Please Enter a Valid Rate',rateValidation),
                    required,
                    minValue:minValue(1)
                  });
                  //
                  assign(validation, Objest);
              })
            }
            return validation;
        }


    },
    props:['products','selectedProduct','rates','shop_id'],

    data(){
        return {
                form:{
                        rate:this
                                  .$inertia
                                  .form({
                                            product_id:null,
                                            range:{},
                                            retail_rate:0,
                                            wholesale_rate:0,
                                            default_wholesale_weight:0,
                                            shop_id:this.shop_id
                          }),
                        selectedRate:this
                                  .$inertia
                                  .form({
                                            id:0,
                                            product_id:null,
                                            product:{product_name:''},
                                            range:{},
                                            retail_rate:0,
                                            wholesale_rate:0,
                                            shop_id:this.shop_id
                          }),
                },
                _token:'',
                date: new Date()
        }
    },
    validations() {
      return {
              form:{
                        rate:{
                                retail_rate:{
                                    rateValidation:helpers.withMessage('Please Enter a Valid Rate',rateValidation),
                                    required,
                                    minValue:minValue(1)
                                },
                                range:this.weightRangeValidation,
                                product_id:{required}
                      },
                }
      }
    },
    methods:{
        addRate:function(e){
            const result = this.v$.form.rate.$validate();
            if (!result) {
              return
            }
            this.form.rate.post(this.route('rate.store'), {
                onSuccess: (response) => {
                                    this.form.rate.reset();
                                    // reset the validation
                                    this.v$.form.rate.$reset();
                                    this.$toast.success("Rate added successfully.")
                },
            });
        },
        getRateDetail:function(d){
            this.$inertia.get(this.route('rate.index'),{ date:d.id}, { only: ["rates"] });
            this.$toast.info("Today's Rate chart updated")
        },
        parseToJSON(data){
               return JSON.parse(data);
        }
    },
    mounted(){
      this.form.rate.product_id = this.selectedProduct.id;
      this._token = $('meta[name="csrf-token"]').attr('content');
      //
      feather.replace({ 'aria-hidden': 'true' });
    },
}
</script>
