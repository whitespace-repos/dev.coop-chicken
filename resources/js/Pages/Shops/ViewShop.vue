<template>
    <Head title="Dashboard" />

    <BreezeAuthenticatedLayout>
      <div class="row">
        <div class="col-lg-7 col-md-12 ">
          <h6 class="d-flex"><span data-feather="file" class="align-text-bottom mr-1"></span> {{ 'COOP - ' + shop.shop_name }}</h6>
          <small class="text-muted font-weight-bold">Due Amount  -  <icon :icon="$page.props.currency" />  {{ currency(due_amount) }}</small>
          <small class="text-muted font-weight-bold ml-4"> <span data-feather="user" class="align-text-bottom"></span> Retailer  -  {{ isNil(shop.employee) ? '-' : shop.employee.name }}</small>
        </div>
      </div>

      <hr />
      <div class="row no-gutters shadow-lg">
        <div class="col-auto">
          <div class="card rounded-0">
            <div class="card-header font-weight-bold text-primary"><span data-feather="phone" class="align-text-bottom"></span> {{ shop.phone }} </div>
            <ul class="list-group list-group-flush" style="width: 16em;">
              <li class="list-group-item p-2  text-truncate"><span data-feather="map-pin" class="align-text-bottom mr-1"></span> <b>Address</b> : {{ shop.address }}</li>
              <li class="list-group-item p-2  text-truncate"><span data-feather="globe" class="align-text-bottom mr-1"></span> <b>Distance</b> : {{ shop.distance_from_cps }}</li>
              <li class="list-group-item p-2  text-truncate"><span data-feather="sidebar" class="align-text-bottom mr-1"></span> <b>Dimensive</b> : {{ shop.shop_dimentions }}</li>
              <li class="list-group-item p-2  text-truncate"><icon :icon="$page.props.currency" /> <b class="ml-1">Max Sale</b> : {{  currency(shop.max_sale_estimate_per_day) }}</li>
              <li class="list-group-item p-2  text-truncate"><icon :icon="$page.props.currency" /> <b  class="ml-1">Max stock</b> : {{ scale(shop.stock_capacity_per_day) }}</li>
              <li class="list-group-item p-2  text-truncate"><span data-feather="calendar" class="align-text-bottom mr-1"></span> <b>Start Date</b> : {{ shop.estimated_start_date }}</li>
              <li class="list-group-item p-2  text-truncate"><span data-feather="users" class="align-text-bottom mr-1"></span> <b>Supplier</b> : {{ shop.supplier.name }}</li>
            </ul>
            <div class="card-body px-0 pb-0" >
              <h6 class="heading px-2 mb-2">
                <span data-feather="layers" class="align-text-bottom mr-2"></span>  Product
                <a data-toggle="modal" href="#Add_Product" class="float-right text-primary"><span data-feather="plus-circle" class="align-text-bottom mr-2"></span> </a>
              </h6>
              <ul class="list-group list-group-flush  overflow-10em-auto custom-scrollbar">
                <li class="list-group-item" v-for="product in shop.products" :key="product.id">
                  <img :src="product.image" alt="" class="img-fluid mr-2" width="20"/> <span>{{ product.product_name }}</span>
                </li>
              </ul>
            </div>
          </div>
        </div>
        <div class="col">
          <div class="card h-100 rounded-0 border-0">
            <div class="card-header bg-transparent font-weight-bold text-primary"> <span data-feather="shopping-bag" class="mr-2"></span> Today's Sales </div>
              <div class="card-body custom-scrollbar"  style="max-height: 32.45em;overflow: auto;">
              <!-------- item 1 -------->
              <template v-if="sales.length > 0">
                <div class="row">
                  <div class="col-6" v-for="sale  in sales" :key="sale.id">
                    <div class="card text-primary mb-4 shadow-lg">
                        <ul class="list-group list-group-flush">
                          <li class="list-group-item font-weight-bold text-center">
                            <img  :src="sale.product.image" class="img-fluid mr-2" width="20"/> <span>{{ sale.product.product_name }}</span>
                          </li>
                          <li class="list-group-item px-1 text-center font-weight-bold">                                
                              <span data-feather="shopping-bag" class="mr-1"></span> {{ scale(sale.total_quantity,sale.product.digits) }} <sup>{{ sale.product.weight_unit }}</sup>
                                <span data-feather="at-sign" class="mr-1  ml-3"></span> <span>{{ currency(sale.total_sales) }}</span>
                          </li>
                        </ul>
                    </div>
                  </div>
                </div>
              </template>
              <template v-else>
                    <h6 class="text-center">No Sale Found For Today</h6>
              </template>
            </div>
          </div>
        </div>
        <div class="col-auto">
          <div class="card h-100 rounded-0">
            <div class="card-header bg-transparent font-weight-bold text-primary"> <span data-feather="truck" class="mr-2"></span> Current Stock </div>
            <div class="card-body custom-scrollbar p-2"  style="max-height: 32.45em;overflow: auto;">
              <ul class="list-group shadow-lg mb-3" v-for="product in shop.products" :key="product.id">
                <template v-if="product.stock">
                    <li class="list-group-item font-weight-bold"><img  :src="product.image" class="img-fluid mr-2" width="20"/> {{ product.product_name }}</li>
                    <li class="list-group-item text-primary font-weight-bold"><span data-feather="truck" class="mr-1"></span> {{ scale(product.association.stock,product.digits) +' '+ product.weight_unit }} </li>
                </template>
              </ul>
            </div>
          </div>
        </div>
      </div>

      <div class="row no-gutters mb-5">
        <div class="col-auto">
          <div class="card h-100 rounded-0">
            <div class="card-header font-weight-bold text-primary d-flex align-items-center p-1">
              <span data-feather="activity" class="mr-2"></span>  Today's Rate
              <select class="form-control form-control-sm w-50 ml-auto" aria-label="Default select example" data-live-search="false" @change="filterProduct($event.target.value)" ref="filterProductForTodayRate">
                <option v-for="product in shop.products" :key="product.id" :value="product.id">{{ product.product_name }}</option>
              </select>
            </div>
            <template v-if="!exceptionalRateFlag">
              <ul class="list-group list-group-horizontal rounded-0 border-0" v-if="productRate.rate != null && productRate.rate != ''">
                <li class="list-group-item rounded-0 border-left-0 border-top-0 border-bottom-0 w-100">
                  <h6> Wholesale </h6>
                  <p>
                    <span class="badge badge-primary font-weight-normal d-block my-1" v-for="range in parseToJSON(productRate.rate.wholesale_rate)" :key="range.id" style="font-size:11px">&#8377;  {{ currency(range.rate) }} {{ ' : ' + scale(range.from) +' - ' }} {{ (range.to == 50000) ? 'MAX' : scale(range.to)  }} {{ productRate.weight_unit }}</span>
                  </p>
                </li>
                <li class="list-group-item rounded-0 border-right-0 border-top-0 border-bottom-0 w-auto">
                  <h6>Retail</h6>
                  <p>
                    <span class="badge badge-primary font-weight-normal d-block">{{ currency(productRate.rate.retail_rate)  }}</span>
                  </p>
                </li>
              </ul>

              <small class="pt-3 px-3 font-weight-bold" v-if="productRate.exceptional_rate != null && productRate.exceptional_rate != ''">Exceptinal Rate</small>
              <ul class="list-group list-group-horizontal rounded-0 border-bottom-0 border-top" v-if="productRate.exceptional_rate != null && productRate.exceptional_rate != ''">
                <li class="list-group-item rounded-0 border-left-0 border-top-0 border-bottom-0 w-100">
                  <h6> Wholesale </h6>
                  <p>
                    <span class="badge badge-primary font-weight-normal d-block my-1" v-for="range in parseToJSON(productRate.exceptional_rate.wholesale_rate)" :key="range.id" style="font-size:11px">&#8377;   {{ currency(range.rate) }} {{ ' : ' + scale(range.from) +' - ' }} {{ (range.to == 50000) ? 'MAX' : scale(range.to)  }} {{ productRate.weight_unit }}</span>
                  </p>
                </li>
                <li class="list-group-item rounded-0 border-right-0 border-top-0 border-bottom-0 w-auto">
                  <h6>Retail</h6>
                  <p>
                    <span class="badge badge-primary font-weight-normal d-block">  {{ currency(productRate.exceptional_rate.retail_rate) }}</span>
                  </p>
                </li>
              </ul>
              <h6 class="text-primary text-center my-4" v-if="productRate.exceptional_rate == null && productRate.rate == null"> Product rate not added yet.</h6>

              <a href="#" class="btn btn-primary py-0 mx-4 mt-auto mb-4 small btn-sm" @click="exceptionalRateFlag = true">Exceptional Rate</a>
            </template>
            <template v-else>
              <form class="py-2 px-1 h-100" @submit.prevent="saveExceptionalRate">
                  <div class="form-group mb-4">
                    <label for="font-weight-bold">Retail Rate</label>
                    <input v-maska="'#*.##'" class="form-control form-control-sm" placeholder="0.00" v-model="form.exceptionalRate.retail_rate"/>
                  </div>

                  <div class="form-group">
                    <label class="font-wegiht-bold">Add Weight Ranges</label>
                    <div class="d-flex mb-2">
                      <div>
                        <div class="form-group mb-0" v-for="(r,i) in form.exceptionalRate.weightRanges" :key="i">
                            <input class="border-gray border rounded border-bottom-0" v-model="form.exceptionalRate.weightRanges[i].from" v-maska="'#*'" style="width:40px"/>
                            -
                            <template v-if="i == form.exceptionalRate.weightRanges.length - 1">
                              <input type="hidden" class="border-gray border rounded border-bottom-0 mr-1 ml-1" v-model="form.exceptionalRate.weightRanges[i].to" v-maska="'#*'"  style="width:40px"/>
                              <input class="border-gray border rounded border-bottom-0 mr-1 ml-1" value="MAX" disabled  style="width:40px"/>
                            </template>
                            <template v-else>
                              <input class="border-gray border rounded border-bottom-0  mr-1 ml-1" v-model="form.exceptionalRate.weightRanges[i].to" v-maska="'#*'"  style="width:40px"/>
                            </template>
                            :

                            <input class="border-gray border rounded border-bottom-0 mx-2" v-model="form.exceptionalRate.weightRanges[i].rate" v-maska="'#*.##'" placeholder="0.00"  style="width:60px"/>
                        </div>
                      </div>
                      <div>
                        <a href="javascript:void(0)" class="btn btn-link btn-sm small ml-2 pt-0 align-self-start" @click="addRange(form.exceptionalRate.weightRanges)" v-if="(form.exceptionalRate.weightRanges.length < 3)"> Add </a> <br />
                        <a href="javascript:void(0)" class="btn btn-link btn-sm small ml-2 pt-0 align-self-start" @click="form.exceptionalRate.weightRanges = []" v-if="(form.exceptionalRate.weightRanges.length > 0)"> Reset</a>
                      </div>
                    </div>
                  </div>

                  <div class="d-flex justify-content-center mt-5">
                    <button class="btn btn-sm btn-primary py-0 mr-2" type="submit">Save Rate</button>
                    <button  class="btn btn-sm btn-outline-secondary py-0" @click="exceptionalRateFlag = false">Cancel</button>
                  </div>
              </form>
            </template>

          </div>
        </div>
        <div class="col">
          <!-- Tab Start -->
          <div class="TabWrapper bg-white border-right border-bottom">
            <ul class="nav nav-tabs  font-weight-bold">
              <li class="nav-item"> <a id="StkReq" class="nav-link active p-2" data-toggle="tab" href="#StockRequest"><span data-feather="truck" class="mr-1"></span>  Stock Request</a> </li>
              <li class="nav-item"> <a id="SndStk" class="nav-link p-2 " data-toggle="tab" href="#SendNewStock"><span data-feather="truck" class="mr-1"></span>  Send New Stock</a> </li>
              <li class="nav-item"> <a id="comStkReq" class="nav-link p-2" data-toggle="tab" href="#ComStockRequest"><span data-feather="check-circle" class="mr-1"></span>  Completed Requests</a> </li>
            </ul>
            <div class="tab-content px-1 py-3 custom-scrollbar" style="max-height: 300px; height:300px; overflow-y: auto;">
              <div class="tab-pane active" id="StockRequest">
                <div class="accordion" id="accordionExample">
                  <template v-for="(request,index) in shop.stock_requests" :key="request.id">
                    <div class="card" v-if="request.status != 'Completed'">
                      <div class="card-header p-0" id="headingOne">
                          <ul class="d-flex justify-content-between align-items-center font-weight-bold list-unstyled mb-0">
                              <li>
                                  <b class="mx-2">{{ index+1 }}. </b>
                                  <button class="btn btn-link text-left p-1 collapsed border-0 d-inline-flex align-items-center" type="button" data-toggle="collapse" :data-target="'#collapse'+index" aria-expanded="true" :aria-controls="'collapse'+index">
                                      <span data-feather="calendar" class="mr-1"></span> {{ request.date }}
                                  </button>

                                  <template v-if="request.status != 'Requested'">
                                    <span class="my-2 ml-2 mr-3">Status - {{ request.status }}  <small>Actual Payment : <span v-currency>{{ request.actual_payment }}</span></small></span>
                                  </template>
                                  <template v-else>
                                    <span class="my-2 ml-2">Status - {{ request.status }} </span>
                                  </template>
                              </li>
                              <li>
                                  <button type="button" data-target="#sendStockConfirmRequest" data-toggle="modal" class="btn btn-primary btn-sm small-sm mx-2 py-0" v-if="request.status == 'Processing'" @click="openSendConfirmationModal(request.id)"><span data-feather="check-circle" class="mr-1"></span>  Send</button>
                                  <button type="button" class="btn btn-primary btn-sm  small-sm mx-2 py-0" data-target="#completeStockRequestConfirmationModal" data-toggle="modal" v-if="request.status == 'Received'"  @click="openSendConfirmationModal(request.id)"><span data-feather="check-circle" class="mr-1"></span>  Completed</button>
                              </li>
                          </ul>
                      </div>

                      <div :id="'collapse'+index" class="collapse container-fluid" aria-labelledby="headingOne" data-parent="#accordionExample">
                        <form @submit.prevent="approveStockRequest(request.id)" class="w-100">
                          <div class="row">
                            <div class="col-4"  v-for="rp  in request.requested_products" :key="rp.id">
                              <ul class="list-group list-group-flush shadow-lg my-3">
                                <li class="list-group-item font-weight-bold text-center px-1 text-truncate">
                                  <img :src="rp.product.image" class="img-fluid mr-2" width="20"> <span>{{ rp.product.product_name }}</span>
                                </li>
                                <li class="list-group-item px-1 text-center small" v-if="request.status != 'Requested'">
                                      <b v-if="request.type == 'Direct'"><span data-feather="shopping-bag" class="mr-1"></span> {{ scale(rp.stock_sent) +' ' + rp.product.weight_unit }}</b>
                                      <b  v-else><span data-feather="truck" class="mr-1  ml-3"></span> {{ scale(rp.stock_request) +' ' + rp.product.weight_unit }}</b>
                                      <b> <span data-feather="at-sign" class="mr-1  ml-3"></span>  <span>{{ currency(rp.supply_rate) }}</span> </b>
                                </li>
                                <li class="list-group-item px-1 text-center" v-else>
                                  <!--  -->
                                  <div class="input-group input-group-sm" >
                                    <div class="input-group-prepend">
                                      <span class="input-group-text" v-if="request.type == 'Direct'">{{ scale(rp.stock_sent) +' ' + rp.product.weight_unit }}</span>
                                      <span class="input-group-text" v-else>{{ scale(rp.stock_request) +' ' + rp.product.weight_unit }}</span>
                                    </div>
                                    <input class="form-control" id="inlineFormInputGroup" placeholder="Supply Rate" v-model="form.approvedStockRequest.supply_rates['product-' + rp.id]" v-maska="'#*.##'" maxlength="4"/>
                                    <div class="input-group-append">
                                      <span class="input-group-text"><sup v-currency>{{form.approvedStockRequest.supply_rates['product-' + rp.id]}}</sup> / {{ rp.product.weight_unit }}</span>
                                    </div>
                                  </div>
                                </li>
                              </ul>
                            </div>
                          </div>
                          <!--  -->
                          <hr v-if="request.status == 'Requested'" />
                          <button type="submit" class="btn btn-primary btn-sm px-5 mx-2" v-if="request.status == 'Requested'"><span data-feather="check-circle" class="mr-1"></span> Confirm</button>
                        </form>
                      </div>
                    </div>
                  </template>
                </div>
              </div>
              <div id="ComStockRequest" class="tab-pane">
                <div class="accordion" id="accordionExample1">
                  <template v-for="(request,index) in shop.stock_requests" :key="request.id">
                    <div class="card" v-if="request.status == 'Completed'">
                      <div class="card-header p-0" id="headingOne">
                          <ul class="d-flex justify-content-between align-items-center font-weight-bold list-unstyled mb-0">
                              <li>
                                  <b class="mx-2">{{ ++index }}. </b>
                                  <button class="btn btn-link text-left p-1 collapsed border-0 d-inline-flex align-items-center" type="button" data-toggle="collapse" :data-target="'#collapse'+index" aria-expanded="true" :aria-controls="'collapse'+index">
                                      <span data-feather="calendar" class="mr-1"></span> {{ request.date }}
                                  </button>

                                  <template v-if="request.status != 'Requested'">
                                    <span class="my-2 ml-2 mr-3">Status - {{ request.status }}  <small>Actual Payment :<span v-currency>{{ request.actual_payment }}</span></small></span>
                                  </template>
                              </li>
                          </ul>
                      </div>

                      <div :id="'collapse'+index" class="collapse container-fluid" aria-labelledby="headingOne" data-parent="#accordionExample1">
                        <div class="row no-gutters">
                          <div class="col-3"  v-for="rp  in request.requested_products" :key="rp.id">
                            <ul class="list-group list-group-flush shadow-lg my-3">
                              <li class="list-group-item font-weight-bold text-center px-1 small text-truncate">
                                <img :src="rp.product.image" class="img-fluid mr-2" width="20"> <span>{{ rp.product.product_name }}</span>
                              </li>
                              <li class="list-group-item px-1 text-center small" v-if="request.status != 'Requested'">
                                <small><span data-feather="shopping-bag" class="mr-1"></span> {{ scale(rp.stock_sent) +' ' + rp.product.weight_unit }}</small>
                                <small><span data-feather="at-sign" class="mr-1  ml-3"></span> <span v-currency>{{ scale(rp.stock_request)  }} </span></small>
                              </li>
                            </ul>
                          </div>
                        </div>
                      </div>
                    </div>
                  </template>
                </div>
              </div>
              <div id="SendNewStock" class="tab-pane fade" >
                <form method="POST" @submit.prevent="directStockRequest" class="container-fluid">
                  <label class="mr-5 ml-2 font-weight-bold"> Grand Total Price : {{ currency(calculateTotalPrice) }} <sup>INR </sup></label>
                  <button class="btn btn-outline-primary btn-sm  px-5" type="submit">Confirm</button>
                  <hr />
                  <div class="row">
                    <div class="col-4"  v-for="product in shop.products" :key="product.id">
                      <ul class="list-group shadow-lg mb-3" v-if="product.stock">
                        <li class="list-group-item font-weight-bold text-truncate px-1 text-center"><img  :src="product.image" class="img-fluid mr-2" width="20"/> {{ product.product_name }} <span class="text-primary">({{ scale(product.association.stock) +' '+ product.weight_unit }} )</span></li>
                        <li class="list-group-item px-2">
                          <div class="input-group input-group-sm">
                            <input type="text" v-maska="'#*.##'" class="form-control" @input="calculateSupplyRate($event,product.id)" placeholder="Quantity" v-model="form.directStockRequest.products['product-'+product.id]" />
                            <div class="input-group-prepend">
                              <span class="input-group-text" id="inputGroup-sizing-sm">{{ product.weight_unit }}</span>
                            </div>
                            <input type="text" v-maska="'#*.##'" class="form-control" @input="calculateSupplyRate($event,product.id)" placeholder="Supply Rate" v-model="form.directStockRequest.products['product-'+product.id+'-supply-rate']" />
                            <div class="input-group-prepend">
                              <span class="input-group-text" id="inputGroup-sizing-sm">INR</span>
                            </div>
                          </div>
                        </li>
                        <li class="list-group-item p-1 d-flex justify-content-between">
                          <small class="help-block text-muted">&#10153; Supply Rate for per {{ product.weight_unit }}.</small>
                          <small><icon :icon="$page.props.currency" /> {{ currency(form.directStockRequest.products['product-'+product.id+'-total-price']) }}</small>
                        </li>
                      </ul>
                    </div>
                  </div>
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- This modal open for review while supplier send direct stock to shop -->
      <div class="modal" id="sendStockConfirmRequest">
        <div class="modal-dialog modal-lg">
          <div class="modal-content">
            <div class="modal-header py-1 border-primary text-primary d-flex align-items-center">
                <h6 class="mb-0">Confirm and Modify Stock Detail </h6>
                <a href="javascript:void(0)" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </a>
            </div>
            <!-- Modal body -->
            <div class="modal-body">
              <form method="POST" @submit.prevent="sendStockToShop(selectedRequest.id)" class="mb-0">
                <table class="table table-bordered table-striped table-hover table-sm">
                  <thead>
                    <tr>
                        <th>#</th>
                        <th>Product</th>
                        <th>Requested</th>
                        <th>Send</th>
                    </tr>
                  </thead>
                  <tbody>
                      <template v-if="isNotEmpty(selectedRequest)">
                      <!--  -->
                      <tr v-for="rp in selectedRequest.requested_products" :key="rp.id">
                          <td>{{ rp.id }} </td>
                          <td>{{ rp.product.product_name }}</td>
                          <td>{{ rp.stock_request + ' ' + rp.product.weight_unit }} </td>
                          <td width="180">
                            <div class="input-group input-group-sm">
                              <input v-maska="'#*.##'" class="form-control" id="inlineFormInputGroup" placeholder="Stock Send" v-model="form.sendStock.send_stocks['product-' + rp.id]"/>
                              <div class="input-group-prepend">
                                <div class="input-group-text">{{ rp.product.weight_unit }}</div>
                              </div>
                            </div>
                          </td>
                      </tr>
                      </template>
                  </tbody>
                </table>
                <button type="submit" class="btn btn-primary btn-sm px-5">Send Stock To Shop</button>
              </form>
            </div>
          </div>
        </div>
      </div>

      <!-- Review Stock after retailer receive the stock. -->
      <div class="modal" id="completeStockRequestConfirmationModal">
        <div class="modal-dialog modal-lg">
          <div class="modal-content">
            <div class="modal-header py-2 border-primary text-primary">
                <h6 class="" v-if="isNotEmpty(selectedRequest)">Review Stock Detail  <small>Actual Payment : {{ selectedRequest.actual_payment }} <sup>INR</sup></small></h6>
                <a href="javascript:void(0)" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </a>
            </div>
            <!-- Modal body -->
            <div class="modal-body">
              <template v-if="isNotEmpty(selectedRequest)">
                <table class="table table-bordered table-striped table-hover table-sm">
                  <thead>
                    <tr>
                        <th>#</th>
                        <th>Product</th>
                        <th>Requested</th>
                        <th>Send</th>
                        <th>Received</th>
                        <th>Wastage</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr v-for="rp in selectedRequest.requested_products" :key="rp.id">
                        <td>{{ rp.id }} </td>
                        <td>{{ rp.product.product_name }}</td>
                        <td>{{ rp.stock_request + ' ' + rp.product.weight_unit }} </td>
                        <td>{{ rp.stock_sent + ' ' + rp.product.weight_unit }}</td>
                        <td>{{ rp.stock_received + ' ' + rp.product.weight_unit }}</td>
                        <td>{{ rp.stock_wastage + ' ' + rp.product.weight_unit }}</td>
                    </tr>
                  </tbody>
                </table>
                <div class="form-group mr-4 float-left w-25">
                  <label>Received Amount </label>
                  <input v-model="receiveStockPayment" class="form-control form-control-sm" placeholder="Receive Amount" v-maska="'#*.##'"/>
                </div>
                <button @click="completeStockRequest(selectedRequest.id)" class="btn btn-outline-primary btn-sm" style="margin-top: 2em;">Yes , I Reviewed</button>
              </template>
            </div>
          </div>
        </div>
      </div>


      <!-- this modal open while supper add product to shop  -->
      <div class="modal" id="Add_Product">
        <div class="modal-dialog modal-lg">
          <div class="modal-content">
            <div class="modal-header border-primary text-primary py-2 align-items-center">
                <h6 class="mb-0">Add new product to shop</h6>
                <a href="javascript:void(0)" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </a>
            </div>
            <!-- Modal body -->
            <div class="modal-body">
              <form method="POST" @submit.prevent="addProductsToShop" class="mb-0">
                <div class="form-group">
                    <label class="font-weight-bold">Choose Products </label>
                    <v-select label="selectProduct" v-model="form.addProduct.products" multiple :options="products" :get-option-label="(option) => option.product_name" :get-option-key="(option) => option.id"></v-select>
                </div>
                <hr />
                <button class="btn btn-primary btn-sm add-btn" type="submit" >Add Product</button>
              </form>
            </div>
          </div>
        </div>
      </div>
    </BreezeAuthenticatedLayout>
</template>
<script type="text/javascript">
import BreezeAuthenticatedLayout from '@/Layouts/Authenticated.vue'
import { Head } from '@inertiajs/inertia-vue3';
import moment from 'moment'
import Button from '@/Components/Button.vue';
import {head,forEach,filter,isNil,assignIn} from 'lodash'
import { Inertia } from '@inertiajs/inertia'
import Input from '@/Components/Input.vue';
import { currency , scale } from "../../utils"
//import Vue from 'vue'

export default {
    components: {
        BreezeAuthenticatedLayout,
        Head,
        Button,
        Input,
    },
    props:['products','shop','users','due_amount','sales'],
    data () {
        return {
                  currency,
                  scale,
                  exceptionalRateFlag:false,
                  // lodash instance for templates
                  isNil,
                  //
                  form:{
                        addProduct:this.$inertia.form({
                                  products:this.shop.products
                        }),
                        approvedStockRequest:this.$inertia.form({
                                  supply_rates:{}
                        }),
                        sendStock:this.$inertia.form({
                                  send_stocks:{}
                        }),
                        directStockRequest:this.$inertia.form({
                                  products:{},
                                  actual_payment:0,
                                  shop_id:'',
                        }),
                        exceptionalRate:this.$inertia.form({
                            retail_rate:0,
                            shop_id:this.shop.id,
                            weightRanges:[]
                        })
                  },
                  selectedRequest:null,
                  receiveStockPayment:0,
                  productRate:{},

        }
    },
    mounted (){
      //
      forEach(this.shop.products, e => {
          this.form.directStockRequest.products['product-'+e.id+'-total-price'] = 0;
      })
      //
      this.productRate = head(this.products);
      //
      feather.replace({ 'aria-hidden': 'true' });
    },
    computed:{
      calculateTotalPrice:function(){
        let directStockTotalCost = 0;
        //
        forEach(this.shop.products, e => {
            directStockTotalCost += this.form.directStockRequest.products['product-'+e.id+'-total-price'];
        })
        return directStockTotalCost;
      }
    },
    methods: {
        openSendConfirmationModal(id){
          this.selectedRequest = head(filter(this.shop.stock_requests, ['id', id]));
          //
          this.receiveStockPayment = this.selectedRequest.actual_payment;
          forEach(this.selectedRequest.requested_products, rp => {
              this.form.sendStock.send_stocks['product-' + rp.id] = rp.stock_request;
          });

        },
        completeStockRequest(id){
          Inertia.post(this.route('completed.stock',id),{"payment_received":this.receiveStockPayment},{
              onSuccess: (page) => {
                $("#completeStockRequestConfirmationModal").modal("hide");
              }
          })
        },
        addProductsToShop() {
            this.form.addProduct.patch(this.route('shop.update',this.shop.id), {
                onSuccess: () => {
                    $("#Add_Product").modal("hide");
                    this.$toast.success("Shop products list updated successfully.")
                },
            })
        },
        approveStockRequest(id) {
            this.form.approvedStockRequest.post(this.route('approve.stock.approved',id), {
                onSuccess: (response) => {
                    this.form.approvedStockRequest.reset('supply_rate');
                },
            })
        },
        sendStockToShop(id) {
            this.form.sendStock.post(this.route('send.stock',id), {
                onSuccess: (response) => {
                    this.form.sendStock.reset();
                    $("#sendStockConfirmRequest").modal("hide");
                    this.$toast.success("Stock sent to shop successfully.")
                },
            })
        },
        directStockRequest(){
            this.form.directStockRequest.actual_payment = this.calculateTotalPrice;
            this.form.directStockRequest.shop_id = this.shop.id;
            this.form.directStockRequest.post(this.route('direct.requested'),{
               onSuccess:(response) => {
                  this.form.directStockRequest.products = {};
                  this.$toast.success("Direct stock sent to shop successfully.")
                },
            })
        },
        isNotEmpty:function(o){
          return !isNil(o);
        },
        calculateSupplyRate:function(e,id){
          let data = this.$data;
          let weight = isNaN(parseFloat(data.form.directStockRequest.products['product-'+id])) ? 0 : parseFloat(data.form.directStockRequest.products['product-'+id]);
          let supplyRate = isNaN(parseFloat(data.form.directStockRequest.products['product-'+id+'-supply-rate'])) ? 0 : parseFloat(data.form.directStockRequest.products['product-'+id+'-supply-rate']);
          let total = weight * supplyRate;
          this.form.directStockRequest.products['product-'+id+'-total-price'] = total;
        },
        parseToJSON(data){
          return JSON.parse(data);
        },
        filterProduct(id){//
          this.axios.get(this.route('filter.product',id)).then(response => {
            this.productRate = response.data.productRate;
            this.$toast.info("Today's Rate loaded")
            this.exceptionalRateFlag = false;
          })
        },
        saveExceptionalRate(){
          this.form.exceptionalRate
            .transform((data) => ({
                ...data,
                shop_id:this.shop.id,
                product_id: this.productRate.id,
              })).post(this.route('exceptional.rate'),{
                onSuccess:() => {
                  this.exceptionalRateFlag = false;
                  this.$toast.success("Exceptional Rate is set for this shop.")
                },
            })
            //
            setTimeout(() => {
                this.filterProduct(this.productRate.id);
            }, 2000);

        },
        addRange(range){
            let countRange = range.length;
            switch(countRange) {
              case 0:
              // code block
              range.push({from:10,to:50000,product_id:this.productRate.id});
              break;
            case 1:
              // code block
              var from = parseInt(range[0].from);
              var to = range[0].to = from + 100;
              //
              range.push({from:(to + 1),to:50000,product_id:this.productRate.id});
              break;
            case 2:
              // code block
              var from = parseInt(range[1].from);
              var to = range[1].to = from + 100;
              //
              range.push({from:(to + 1),to:50000,product_id:this.productRate.id});
              break;
            default:
              // code block
          }
        }
    }
}
</script>
