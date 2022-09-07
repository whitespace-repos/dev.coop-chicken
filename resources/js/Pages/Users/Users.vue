<template>
    <Head title="Dashboard" />

    <BreezeAuthenticatedLayout>
      <div class="d-flex justify-content-between align-items-center">
        <h6 class="mb-0"><span data-feather="users" class="align-text-bottom"></span> Users</h6>
        <inertia-link :href="this.route('user.create')"  class="btn add-btn btn-primary btn-sm" as="button" type="button" ><span data-feather="database" class="mr-2"></span> Add New User</inertia-link>
      </div>
      <hr />
      <!--  -->
      <dataset
          v-slot="{ ds }"
          :ds-data="users"
          :ds-sortby="sortBy"
          :ds-search-in="['name','phone']"

        >
          <div class="card card-body shadow-lg">
            <div class="row" :data-page-count="ds.dsPagecount">
              <div class="col-md-6 mb-2 mb-md-0">
                <dataset-show />
              </div>
              <div class="col-md-6">
                <dataset-search ds-search-placeholder="Search..." />
              </div>
            </div>
            <div class="row">
              <div class="col-md-12">
                  <div class="table-responsive">
                    <table class="table table-striped d-md-table table-sm my-4">
                      <thead>
                        <tr>
                          <td scope="col"><kbd>{{ '# ' }}</kbd></td>
                          <th v-for="(th, index) in cols" :key="th.field" :class="['sort', th.sort]" @click="click($event, index)">
                            {{ th.name }} <i class="gg-select float-right"></i>
                          </th>
                          <th  width="200">Action</th>
                        </tr>
                      </thead>
                      <dataset-item tag="tbody">
                        <template #default="{ row, rowIndex }">
                          <tr>
                            <th scope="row"><kbd>{{ '#' + (rowIndex + 1) }}</kbd></th>
                            <td>
                                {{ row.name }}
                                <sup v-if="row.roles[0].name == 'Employee'"  class="font-weight-bold">
                                  ( {{ '#'+row.shop.supplier.id +' - '+ row.shop.supplier.name }} )
                                </sup>
                              </td>
                            <td><span class="badge badge-primary"> {{ (isEmpty(row)) ? '' : row.roles[0].name  }}</span></td>
                            <td><span class="badge badge-warning" :class="{'px-4':isEmpty(row.shop)}"> {{ isEmpty(row.shop) ? '-' : row.shop.shop_name }}</span></td>
                            <td><span class="badge badge-secondary font-weight-normal" :class="{'px-4':isEmpty(row.phone)}"> {{ isEmpty(row.phone) ? '-' : row.phone }}</span></td>
                            <td>
                              <inertia-link :href="route('user.edit',row.id)" class=""><span data-feather="edit-2" class="mr-2"></span>Edit</inertia-link>
                              <inertia-link :href="route('user.show',row.id)" class="ml-4"  v-if="row.roles[0].name == 'Supplier' || row.roles[0].name == 'Admin'">View Detail</inertia-link>
                            </td>
                          </tr>
                        </template>
                      </dataset-item>
                    </table>
                  </div>
              </div>
            </div>
            <div class="d-flex flex-md-row flex-column justify-content-between align-items-center">
              <dataset-info class="mb-2 mb-md-0" />
              <dataset-pager />
            </div>
          </div>

        </dataset>
      <!--  main-panel-->
    </BreezeAuthenticatedLayout>
</template>



<script>
import BreezeAuthenticatedLayout from '@/Layouts/Authenticated.vue'
import { Head } from '@inertiajs/inertia-vue3';
import _ from 'lodash'

export default {
    components: {
        BreezeAuthenticatedLayout,
        Head,
    },
    data: function () {
      return {
        cols: [
          {
            name: 'Name',
            field: 'name',
            sort: ''
          },
          {
            name: 'Role',
            field: 'role',
            sort: ''
          },
          {
            name: 'Shop',
            field: 'shop',
            sort: ''
          },{
            name: 'Contact',
            field: 'phone',
            sort: ''
          },
        ]
      }
    },
    props:['users'],
    computed: {
      sortBy() {
        return this.cols.reduce((acc, o) => {
          if (o.sort) {
            o.sort === 'asc' ? acc.push(o.field) : acc.push('-' + o.field)
          }
          return acc
        }, [])
      }
    },
    methods:{
       isEmpty:function(o){
         return  _.isNil(o);
       },
       click(event, i) {
          let toset
          const sortEl = this.cols[i]
          if (!event.shiftKey) {
            this.cols.forEach((o) => {
              if (o.field !== sortEl.field) {
                o.sort = ''
              }
            })
          }
          if (!sortEl.sort) {
            toset = 'asc'
          }
          if (sortEl.sort === 'desc') {
            toset = event.shiftKey ? '' : 'asc'
          }
          if (sortEl.sort === 'asc') {
            toset = 'desc'
          }
          sortEl.sort = toset
        }
    }
}
</script>
