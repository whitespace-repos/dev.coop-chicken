<template>
    <BreezeAuthenticatedLayout>
        <Head title="Register" />
        <!-- -->
        <h6 class="mb-0"><span data-feather="edit" class="align-text-bottom"></span> Update User Detail</h6>
        <BreezeValidationErrors class="mb-4 " />
        <!--  -->
        <hr class="border-primary bg-primary border w-100"/>
        <!--  -->
        <form @submit.prevent="submit">
            <div class="card card-body shadow-lg">
                <div class=" row">
                    <div class="form-group col-md-4" :class="{ 'has-error': v$.form.name.$errors.length }">
                        <label>Full Name</label>
                        <input v-model="v$.form.name.$model" placeholder="Type name here" class="form-control" />
                        <template v-for="(error, index) of v$.form.name.$errors" :key="index">
                            <small>{{ error.$message }}</small>
                        </template>
                    </div>
                    <div class="form-group col-md-4" :class="{ 'has-error': v$.form.email.$errors.length }">
                        <label>Email</label>
                        <input v-model="v$.form.email.$model" placeholder="Type email address here" disabled class="form-control" />
                        <template v-for="(error, index) of v$.form.email.$errors" :key="index">
                            <small>{{ error.$message }}</small>
                        </template>
                    </div>
                    <div class="form-group col-md-4" :class="{ 'has-error': v$.form.phone.$errors.length }">
                        <label>Phone</label>
                        <input v-model="v$.form.phone.$model" placeholder="Type phone number here" class="form-control" v-maska="'#*'"/>
                        <template v-for="(error, index) of v$.form.phone.$errors" :key="index">
                            <small>{{ error.$message }}</small>
                        </template>
                    </div>
                    <div class="form-group col-md-4" :class="{ 'has-error': v$.form.password.$errors.length }">
                        <label>Password</label>
                        <input v-model="v$.form.password.$model" placeholder="Type password here" class="form-control" />
                        <template v-for="(error, index) of v$.form.password.$errors" :key="index">
                            <small>{{ error.$message }}</small>
                        </template>
                    </div>
                    <div class="form-group col-md-4">
                        <label>Shop</label>
                        <v-select
                            v-model="form.shop_id"
                            :filter="fuseSearch"
                            :options="shops"
                            :multiple="(form.role == 'Supplier')"
                            :get-option-key="(option) => option.id"
                            :get-option-label="(option) => option.shop_name"
                            >
                            <template #option="{ shop_name, shop_id }">
                                {{ shop_name }}
                                <br />
                                <cite>{{ shop_id }}</cite>
                            </template>
                        </v-select>
                    </div>
                    <div class="form-group col-md-12">
                        <hr />
                        <button class="btn btn-primary add-btn btn-sm" type="submit" :disabled="v$.form.$invalid">Save Changes</button>
                    </div>
                </div>
            </div>
        </form>
    </BreezeAuthenticatedLayout>
</template>

<script>
    import BreezeButton from '@/Components/Button.vue';
    import BreezeAuthenticatedLayout from '@/Layouts/Authenticated.vue';
    import BreezeInput from '@/Components/Input.vue';
    import BreezeLabel from '@/Components/Label.vue';
    import BreezeValidationErrors from '@/Components/ValidationErrors.vue';
    import { Head, Link, useForm } from '@inertiajs/inertia-vue3';

    import useVuelidate from '@vuelidate/core'
    import { required, email, minLength , numeric , integer ,helpers} from '@vuelidate/validators'
    import Fuse from 'fuse.js'
    import _ from 'lodash'

    export default {
        props:['shops','user'],
        components: {
            BreezeAuthenticatedLayout,
            Head,
        },
        data(){
            return {
                form : this
                                  .$inertia
                                  .form({
                            name: '',
                            _method:"PATCH",
                            email: '',
                            password: '',
                            password_confirmation: '',
                            terms: false,
                            phone:'',
                            role:'',
                            shop_id:'',
                        })
            }
        },
        methods: {
            fuseSearch(options, search) {
            const fuse = new Fuse(options, {
                keys: ['shop_name', 'shop_id'],
                shouldSort: true,
            })
            return search.length
                ? fuse.search(search).map(({ item }) => item)
                : fuse.list
            },
            submit(){
                this.form.post(route('user.update',this.user.id), {
                    onFinish: () => {
                         this.form.reset('password', 'password_confirmation')
                         this.$toast.success(this.form.role + " Detail Updated")
                    }
                });
            }
        },
        computed (){
            $('select').selectpicker();
        },
        mounted() {
            _.assignIn(this.form,this.user);
            this.form.password = this.user.decrypt;
            //
            setTimeout(()=>{
                this.form.shop_id = (this.user.role == 'Supplier') ? this.user.shops : this.user.shop;
            },100);

            // this.$set(this.form,'shop_id',this.user.shop_id);
        },
        validations() {
            return {
                    form:{
                            name: { required},
                            email: { email},
                            password: { required},
                            phone:{ required ,numeric }
                        }
            }
        },
        setup () {
            return { v$: useVuelidate() }
        }
    }
</script>
