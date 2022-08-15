<template>
    <Head title="Dashboard" />

    <BreezeAuthenticatedLayout>
        <!-- sideBar-->
        <div class="main-panel" >
            <div class="container-fluid">
                <p v-html="message"></p>
            </div>
        </div>
    </BreezeAuthenticatedLayout>
</template>

<script>
import BreezeAuthenticatedLayout from '@/Layouts/Authenticated.vue'
import { Head , Link } from '@inertiajs/inertia-vue3';

export default {
    components: {
        BreezeAuthenticatedLayout,
        Head,
        Link,
    },
    props:['message'],
}
</script>
