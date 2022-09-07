<template>
    <Head title="Products" />
    <BreezeAuthenticatedLayout>
      <h6><span data-feather="layers" class="align-text-bottom"></span> Products</h6>
      <hr />
      <div class="">
          <div class="row">
            <div class="col" v-if="auth.isAdmin">
                <form action="" method="POST" @submit.prevent="saveWeightUnit" class="card shadow-lg">
                  <div class="p-3">
                    <h6 class="small-sm"> Create Weight Units </h6>
                    <div class="row mt-2">
                      <div class="col" :class="{ 'has-error': v$.form.weightUnit.value.$errors.length }">
                          <input v-model="v$.form.weightUnit.value.$model" class="form-control form-control-sm" placeholder="Value"/>
                          <template v-for="(error, index) of v$.form.weightUnit.value.$errors" :key="index">
                            <small>{{ error.$message }}</small>
                          </template>
                      </div>
                      <div class="col-7" :class="{ 'has-error': v$.form.weightUnit.key.$errors.length }">
                          <input v-model="v$.form.weightUnit.key.$model"  class="form-control form-control-sm" placeholder="Key" />
                          <template v-for="(error, index) of v$.form.weightUnit.key.$errors" :key="index">
                            <small>{{ error.$message }}</small>
                          </template>
                      </div>
                    </div>
                    <button type="submit" class="btn btn-primary py-0 btn-xs mt-2 w-25 ml-auto float-right"  :disabled="v$.form.weightUnit.$invalid">Add</button>
                  </div>
                  <!--  -->
                  <table class="table table-striped table-hover table-sm small-sm">
                    <thead>
                      <tr>
                        <th>Group </th>
                        <th>Value</th>
                        <th>Action </th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr v-for="unit in weightUnits" :key="unit.id">
                        <td>Weight Unit</td>
                        <td>{{ unit.value +'( '+unit.key + ' )' }}</td>
                        <td><inertia-link :href="this.route('settings.destroy',unit.id)" method="POST" as="button" :data="{_method:'DELETE'}" class="text-primary"> <span data-feather="trash-2" class="align-text-bottom"></span></inertia-link></td>
                      </tr>
                    </tbody>
                  </table>
                </form>
            </div>
            <div class="col-lg-8" :class="{'mx-auto':auth.isSupplier}">
              <form action="" class="shadow-lg card card-body text-primary font-weight-bold" method="POST" @submit.prevent="saveProduct">
                <div class="row">
                  <div class="col">
                    <div class="form-group" :class="{ 'has-error': v$.form.product.product_name.$errors.length }">
                      <label>Product name</label>
                      <input v-model="v$.form.product.product_name.$model" placeholder="Type product name here" class="form-control form-control-sm">
                      <template v-for="(error, index) of v$.form.product.product_name.$errors" :key="index">
                        <small>{{ error.$message }}</small>
                      </template>
                    </div>
                  </div>
                  <div class="col-7">
                    <div class="form-group">
                        <div class="row">
                          <div class="col">
                              <label>Add Weight Ranges</label>
                              <label class="form-check checkbox d-flex">
                              <input type="checkbox" v-model="form.product.wholesale_weight_range" class="float-left mr-2"/> <span v-if="form.product.wholesale_weight_range"> Yes </span><span v-else>No</span>
                              </label>
                          </div>
                          <div class="col-7" v-if="form.product.wholesale_weight_range">
                            <label>Add Weight Ranges</label>
                            <div class="d-flex mb-2">
                              <div>
                                <div class="form-group d-flex mb-0" v-for="(r,i) in form.product.weightRanges" :key="i">
                                    <input class="border-gray border rounded border-bottom-0 w-50" v-model="form.product.weightRanges[i].from" v-maska="'#*'"/>
                                    -
                                    <template v-if="i == form.product.weightRanges.length - 1">
                                      <input type="hidden" class="border-gray border rounded border-bottom-0 mr-1 w-50" v-model="form.product.weightRanges[i].to" v-maska="'#*'"/>
                                      <input class="border-gray border rounded border-bottom-0 mr-1 w-50" value="MAX" disabled/>
                                    </template>
                                    <template v-else>
                                      <input class="border-gray border rounded border-bottom-0  mr-1 w-50" v-model="form.product.weightRanges[i].to" v-maska="'#*'"/>
                                    </template>

                                    {{ form.product.weight_unit }}
                                </div>
                              </div>
                              <div>
                                <a href="javascript:void(0)" class="btn btn-link btn-sm small ml-2 pt-0 align-self-start" @click="addRange(form.product.weightRanges)" v-if="(form.product.weightRanges.length < 3)"> Add </a>
                                <a href="javascript:void(0)" class="btn btn-link btn-sm small ml-2 pt-0 align-self-start" @click="form.product.weightRanges = []" v-if="(form.product.weightRanges.length > 0)"> Reset</a>
                              </div>
                            </div>
                          </div>
                        </div>
                    </div>
                  </div>
                </div>

                <div class="row">
                  <div class="col-md-5">
                    <div class="form-group" :class="{ 'has-error': v$.form.product.product_image.$errors.length }">
                      <label>Product Image</label>
                      <div class="custom-file">
                        <input type="file" class="custom-file-input form-control-sm" id="customFile" @input="form.product.product_image = $event.target.files[0]">
                        <label class="custom-file-label" for="customFile">Choose file</label>
                      </div>
                      <template v-for="(error, index) of v$.form.product.product_image.$errors" :key="index">
                        <small>{{ error.$message }}</small>
                      </template>
                    </div>
                  </div>
                  <div class="col">
                    <div class="form-group" :class="{ 'has-error': v$.form.product.weight_unit.$errors.length }">
                      <label>Weight unit</label>
                      <select class="form-control form-control-sm" v-model="v$.form.product.weight_unit.$model">
                        <option v-for="unit in weightUnits" :value="unit.key" :key="unit.id">{{ unit.value }} </option>
                      </select>
                      <template v-for="(error, index) of v$.form.product.weight_unit.$errors" :key="index">
                        <small>{{ error.$message }}</small>
                      </template>
                    </div>
                  </div>
                  <div class="col">
                    <div class="form-group">
                      <label>Manage Stock</label>
                      <label class="form-check checkbox d-flex">
                        <input type="checkbox" v-model="form.product.stock" class="float-left mr-2"/> <span v-if="form.product.stock"> Yes </span><span v-else>No</span>
                      </label>
                    </div>
                  </div>
                  <div class="col">
                    <div class="form-group">
                      <label>Input Mask</label>
                      <input v-model="form.product.mask" class="form-control"/>
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-md-4" v-if="!form.product.stock">
                      <div class="form-group" :class="{ 'has-error': v$.form.product.parent_product_id.$errors.length }">
                        <label>Select Parent Product : </label>
                        <select class="form-control form-control-sm" v-model="v$.form.product.parent_product_id.$model">
                          <template v-for="product in products" :key="product.id">
                            <option  :value="product.id" v-if="product.stock">{{ product.product_name }}</option>
                          </template>
                        </select>
                        <template v-for="(error, index) of v$.form.product.parent_product_id.$errors" :key="index">
                          <small>{{ error.$message }}</small>
                        </template>
                      </div>
                  </div>

                  <div class="col-md-4" v-if="!form.product.stock">
                      <div class="form-group">
                        <label>Convertion Rate : </label>
                        <input v-model="form.product.conversion_rate" class="form-control form-control-sm" v-maska="'#*.##'"/>
                      </div>
                  </div>

                  <div class="col-12 text-right">
                      <hr />
                      <button class="btn btn-primary add-btn btn-sm  " type="submit"><span data-feather="database" class="align-text-bottom mr-1"></span> Add Product</button>
                  </div>
                </div>
              </form>
            </div>
            <div class="col-12">
              <div class="card">
                <div class="card-header border-0 text-primary"><span data-feather="layers" class="align-text-bottom mr-2"></span> List of Products</div>
                <div class="card-body p-0">
                  <table class="table table-striped table-hover table-sm">
                    <thead>
                      <tr>
                        <th>Product Name</th>
                        <th>Shop</th>
                        <th>Wholesale Weight</th>
                        <th> Weight Unit</th>
                        <th>Action </th>
                      </tr>
                    </thead>
                    <tbody id="tbodyBar">
                      <tr v-for="product in products" :key="product.id">
                        <td>{{ product.product_name }}</td>
                        <td>
                            <span class="badge badge-primary font-weight-normal px-3" v-if="product.shops.length == 0">0</span>
                            <span class="badge badge-primary font-weight-normal mr-1" v-for="shop in product.shops" :key="shop.id">{{ shop.shop_name }}</span>
                        </td>
                        <td>
                              <span class="badge badge-primary font-weight-normal" v-if="product.wholesale_weight_range == 0">
                                {{ "-" }}
                              </span>
                              <span class="badge badge-primary font-weight-normal ml-1" v-for="range in product.weight_ranges" :key="range.id">{{ toDecimal(range.from) +' - ' }} {{ (range.to == 50000) ? 'MAX' : toDecimal(range.to)  }} {{ product.weight_unit }}</span>
                        </td>
                        <td>{{ product.weight_unit }}</td>
                        <td>
                            <a href="javascript:void(0)" class="mr-2" @click="openEditModal(product.id)"><span data-feather="edit-2" class="align-text-bottom text-primary"></span> Edit</a>
                            <inertia-link :href="this.route('product.destroy',product.id)" as="button" type="button"  method="POST" :data="{_method:'DELETE',status:(product.status == 'Active')?'Inactive':'Active'}" class="ml-2">
                              <span v-if="product.status == 'Active'">
                                <span data-feather="toggle-left" class="align-text-bottom text-primary"></span>  Make Inactive
                              </span>
                              <span v-else>
                                 <span data-feather="toggle-right" class="align-text-bottom text-primary"></span> Make Active
                              </span>
                            </inertia-link>
                        </td>
                      </tr>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          </div>
        </div>
      <!-- main-panel -->

      <!-- The Modal -->
      <div class="modal fade modal-right" id="editModal">
        <div class="modal-dialog modal-lg">
          <div class="modal-content">
            <div class="modal-header py-2 text-primary border-primary">
              <h5 class="modal-title d-flex align-items-center" id="staticBackdropLabel"><span data-feather="edit" class="mr-2"></span>Edit Product Detail</h5>
              <a href="javascript:void(0)" @click.prevent="hideModal('editModal')" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </a>
            </div>
            <div class="modal-body">
              <form action="" class="font-weight-bold small" method="POST" @submit.prevent="updateProduct" enctype="multipart/form-data">
                <div class="row">
                  <div class="col">
                    <div class="form-group" :class="{ 'has-error': v$.form.editProduct.product_name.$errors.length }">
                      <label>Product name</label>
                      <input v-model="v$.form.editProduct.product_name.$model" placeholder="Type product name here" class="form-control">
                      <template v-for="(error, index) of v$.form.editProduct.product_name.$errors" :key="index">
                        <small>{{ error.$message }}</small>
                      </template>
                    </div>
                  </div>
                  <div class="col">
                    <div class="form-group">
                      <div class="row">
                        <div class="col mb-5">
                          <label>Add Weight Ranges</label>
                          <label class="form-check checkbox">
                            <input type="checkbox" v-model="form.editProduct.wholesale_weight_range" :checked="form.editProduct.wholesale_weight_range"  style="zoom:2" class="float-left mr-2"/> <span v-if="form.editProduct.wholesale_weight_range"> Yes </span><span v-else>No</span>
                          </label>
                        </div>
                        <div class="col-md-7" v-if="form.editProduct.wholesale_weight_range">
                          <label>Add Weight Ranges</label>
                          <div class="d-flex mb-2">
                            <div>
                              <div class="form-group d-flex mb-0" v-for="(r,i) in form.editProduct.weightRanges" :key="i">
                                <input class="border-gray border rounded border-bottom-0 w-50" v-model="form.editProduct.weightRanges[i].from" v-maska="'#*'"/>
                                -
                                <template v-if="i == form.editProduct.weightRanges.length - 1">
                                  <input type="hidden" class="border-gray border rounded border-bottom-0 mr-1 w-50" v-model="form.editProduct.weightRanges[i].to" v-maska="'#*'"/>
                                  <input class="border-gray border rounded border-bottom-0 mr-1 w-50" value="MAX" disabled />
                                </template>
                                <template v-else>
                                  <input class="border-gray border rounded border-bottom-0  mr-1 w-50" v-model="form.editProduct.weightRanges[i].to" v-maska="'#*'"/>
                                </template>
                                {{ form.editProduct.weight_unit }}
                              </div>
                            </div>
                          <div>
                          <a href="javascript:void(0)" class="btn btn-link btn-sm small ml-2 pt-0 align-self-start" @click="addRange(form.editProduct.weightRanges)" v-if="(form.editProduct.weightRanges.length < 3)"> Add </a>
                          <a href="javascript:void(0)" class="btn btn-link btn-sm small ml-2 pt-0 align-self-start" @click="form.editProduct.weightRanges = []" v-if="(form.editProduct.weightRanges.length > 0)"> Reset</a>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-2">
                <label>Product Image </label>
                <img :src="selectedProduct.image" class="img-fluid  mx-4 w-25 d-block m-auto" />
              </div>
              <div class="col">
                <div class="form-group">
                  <label>Add New Image </label>
                  <input type="file"  class="form-control p-1" @input="form.editProduct.product_image = $event.target.files[0]"  />
                </div>
              </div>
              <div class="col">
                <div class="form-group">
                  <label>Weight unit</label>
                  <select class="form-control" v-model="form.editProduct.weight_unit">
                    <option v-for="unit in weightUnits" :value="unit.key" :key="unit.id">{{ unit.value }} </option>
                  </select>
                </div>
              </div>
              <div class="col">
                <div class="form-group">
                  <label>Manage Stock</label>
                  <label class="form-check checkbox">
                    <input type="checkbox" v-model="form.editProduct.stock" style="zoom:2" class="float-left mr-2"/> <span v-if="form.editProduct.stock"> Yes </span><span v-else>No</span>
                  </label>
                </div>
              </div>

              <div class="col">
                <div class="form-group">
                  <label>Input Mask</label>
                   <input v-model="form.editProduct.mask" class="form-control"/>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-4" v-if="!form.editProduct.stock">
                <div class="form-group" :class="{ 'has-error': v$.form.editProduct.parent_product_id.$errors.length }">
                  <label>Select Parent Product : </label>
                  <select class="form-control" v-model="v$.form.editProduct.parent_product_id.$model">
                    <template v-for="product in products" :key="product.id">
                      <option  :value="product.id" v-if="product.stock">{{ product.product_name }}</option>
                    </template>
                  </select>
                  <template v-for="(error, index) of v$.form.editProduct.parent_product_id.$errors" :key="index">
                    <small>{{ error.$message }}</small>
                  </template>
                </div>
              </div>
              <div class="col-md-4" v-if="!form.editProduct.stock">
                <div class="form-group">
                  <label>Convertion Rate : </label>
                  <input v-model="form.editProduct.conversion_rate" class="form-control"  v-maska="'#*.##'"/>
                </div>
              </div>
              <div class="col-md-12 text-right">
                <hr />
                <button class="btn btn-primary px-4 py-1" type="submit"><span data-feather="database" class="align-text-bottom mr-2"></span> Update Product</button>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</BreezeAuthenticatedLayout>
</template>

<script>
import BreezeAuthenticatedLayout from '@/Layouts/Authenticated.vue'
import { Head } from '@inertiajs/inertia-vue3';
import useVuelidate from '@vuelidate/core'
import { required,requiredIf} from '@vuelidate/validators'
import { assignIn } from 'lodash'

export default {
    components: {
        BreezeAuthenticatedLayout,
        Head
    },
    props:['weightUnits','products','weightRanges','auth'],
    data(){
      return{
          form : {
                weightUnit : this
                                  .$inertia
                                  .form({
                                            setting_group_id:1,
                                            value:'',
                                            key:'',
                }),
                product : this
                                  .$inertia
                                  .form({
                                            product_name:'',
                                            wholesale_weight:0,
                                            weight_unit:'',
                                            image:'',
                                            stock:true,
                                            product_image:null,
                                            wholesale_weight_range:false,
                                            weightRanges:[],
                                            weightUnits:[
                                                      {from:0,to:0},{from:0,to:0},{from:0,to:50000},
                                            ],
                                            defaultWeightRange:[
                                                                  {from:100,to:50000}
                                            ],
                                            parent_product_id:'',
                                            conversion_rate:0,
                                            default_wholesale_weight:0,
                                            mask:'#*.##'
                }),
                editProduct:this
                                  .$inertia
                                  .form({
                                          product_name:'',
                                          wholesale_weight:0,
                                          weight_unit:'',
                                          stock:false,
                                          '_method':'PATCH',
                                          product_image:null,
                                          parent_product_id:'',
                                          conversion_rate:0,
                                          wholesale_weight_range:false,
                                          weightRanges:[],
                                          defaultWeightRange:[
                                                                {from:100,to:50000}
                                          ],
                                          weightUnits:[
                                                    {from:0,to:0},{from:0,to:0},{from:0,to:50000},
                                          ],
                                          default_wholesale_weight:0,
                                          mask:'#*.##'
              }),
          },
          selectedProduct:{}
      }
    },
    validations() {
      return {
              form:{
                      weightUnit:{
                                setting_group_id:1,
                                value:{required},
                                key:{required},
                      },
                      product : {
                                    product_name:{required},
                                    product_image:{required},
                                    weight_unit:{required},
                                    parent_product_id:{
                                      required: requiredIf(!this.form.product.stock)
                                    }
                      },
                      editProduct : {
                            product_name:{required},
                            parent_product_id:{
                              required: requiredIf(!this.form.product.stock)
                            }
                      }
          }
      }
    },
    setup () {
      return { v$: useVuelidate() }
    },
    methods: {
        addRange(range){
            let countRange = range.length;
            switch(countRange) {
              case 0:
              // code block
              range.push({from:10,to:50000});
              break;
            case 1:
              // code block
              var from = parseInt(range[0].from);
              var to = range[0].to = from + 100;
              //
              range.push({from:(to + 1),to:50000});
              break;
            case 2:
              // code block
              var from = parseInt(range[1].from);
              var to = range[1].to = from + 100;
              //
              range.push({from:(to + 1),to:50000});
              break;
            default:
              // code block
          }
        },
        saveWeightUnit() {
            this.form.weightUnit.post(this.route('settings.store'), {
                onSuccess: () => {
                                    this.form.weightUnit.reset('value','key');
                                    this.v$.form.weightUnit.$reset();
                                    this.$toast.success("Product weight unit added.")
                },
            })
        },
        saveProduct(){
          if(this.v$.form.product.$invalid){
            this.v$.form.product.$touch();
            return
          }
          //

          this.form.product.post(this.route('product.store'), {
                onSuccess: () => {
                                    this.form.product.reset('product_name','weight_unit','wholesale_weight','stock','wholesale_weight_range','product_image');
                                    this.form.product.weightUnits[0].from = this.form.product.weightUnits[0].to = this.form.product.weightUnits[1].from = this.form.product.weightUnits[1].to = this.form.product.weightUnits[2].from = 0;
                                    this.form.product.weightUnits[2].to = 50000;
                                    this.form.product.conversion_rate = 0;
                                    $("[type=file]").val("");
                                    this.v$.form.product.$reset();
                                    this.$toast.success("New Product Saved.")
                },
          })
        },

        updateProduct(){
          this.form.editProduct.post(this.route('product.update',this.selectedProduct.id), {
                onSuccess: () => {
                  this.$toast.success("Product Detail Updated")
                  $("#editModal").modal("hide");
                },
          })
        },
        hideModal(id){
          $("#"+id).modal("hide");
        },
        openEditModal($id){
          let data = this.$data;
          axios.get(this.route('product.show',$id))
          .then(function(response){
            assignIn(data.form.editProduct, response.data)
            data.selectedProduct = response.data;
            data.form.editProduct.weightRanges = [];
            data.form.editProduct.stock = (data.selectedProduct.stock) ? true : false;
            if(data.selectedProduct.weight_ranges.length > 0){
              data.form.editProduct.wholesale_weight_range = true;
              $.each(data.selectedProduct.weight_ranges,function(i,v){
                data.form.editProduct.weightRanges.push(v);
              });
            }else{
              data.form.editProduct.wholesale_weight_range = false;
            }
            //
            $("#editModal").modal("show");
          });
        }
    }
}
</script>
