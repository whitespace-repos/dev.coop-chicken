<script>
import BreezeButton from '@/Components/Button.vue';
import BreezeCheckbox from '@/Components/Checkbox.vue';
import BreezeGuestLayout from '@/Layouts/Guest.vue';
import BreezeInput from '@/Components/Input.vue';
import BreezeLabel from '@/Components/Label.vue';
import BreezeValidationErrors from '@/Components/ValidationErrors.vue';
import { Head, Link, useForm } from '@inertiajs/inertia-vue3';

export default {
                    props:["canResetPassword","status","auth"],
                    data(){
                                return {    
                                            form:{
                                                    credentials : this
                                                                    .$inertia
                                                                    .form({ 
                                                                            email: '',
                                                                            password: '',
                                                                            remember: false,
                                                                            _token: this.auth.csrf,
                                                    })
                                            }
                                            
                                }
                    },
                    components:{
                        BreezeButton,
                        BreezeCheckbox,
                        BreezeGuestLayout,
                        BreezeInput,
                        BreezeLabel,
                        BreezeValidationErrors,
                        Head,
                        Link
                    },
                    methods:{
                        submit(){   
                                    this.form.credentials.post(route('login'), {
                                        headers: { "X-XSRF-TOKEN" : this.auth.csrf },
                                        onFinish: () => this.form.credentials.reset('password'),
                                    });
                        }
                    },
                    mounted(){
                        console.log(this.auth.csrf)
                    }
    
}
</script>

<template>
    <BreezeGuestLayout>
        <Head title="Log in" />

        <BreezeValidationErrors class="mb-4" />

        <div v-if="status" class="mb-4 font-medium text-sm text-green-600 org-shadow">
            {{ status }}
        </div>

        <form @submit.prevent="submit">
            <div>
                <BreezeLabel for="email" value="Email" class="text-white"/>
                <BreezeInput id="email" type="email" class="mt-1 block w-full" v-model="form.credentials.email" required autofocus autocomplete="username" />
            </div>

            <div class="mt-4">
                <BreezeLabel for="password" value="Password"  class="text-white"/>
                <BreezeInput id="password" type="password" class="mt-1 block w-full" v-model="form.credentials.password" required autocomplete="current-password" />
            </div>

            <div class="block mt-4">
                <label class="flex items-center">
                    <BreezeCheckbox name="remember" v-model:checked="form.credentials.remember"  class="text-white"/>
                    <span class="ml-2 text-sm text-gray-600">Remember me</span>
                </label>
            </div>

            <div class="flex items-center justify-end mt-4">
                <Link v-if="canResetPassword" :href="route('password.request')" class="underline text-sm text-gray-600 hover:text-gray-900">
                    Forgot your password?
                </Link>

                <BreezeButton class="ml-4" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                    Log in
                </BreezeButton>
            </div>
        </form>
    </BreezeGuestLayout>
</template>
