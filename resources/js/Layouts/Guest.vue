<script setup>
import BreezeApplicationLogo from '@/Components/ApplicationLogo.vue';
import { Link } from '@inertiajs/inertia-vue3';
</script>

<template>
    <link rel="stylesheet" href="/css/app.css" />
    <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 " v-show="elementVisible">
        <div>
            <Link href="/">
                <img src="/assets/img/guest-logo.png" alt="logo" />
            </Link>
        </div>

        <div class="w-full sm:max-w-md mt-2 p-4 overflow-hidden sm:rounded-lg org-shadow">
            <slot />
        </div>
    </div>
</template>

 <script>
        export default {
            data() {
                return {
                    elementVisible: false
                }
            },

            created() {
                setTimeout(() => this.elementVisible = true, 400)
            }
        }
    </script>
