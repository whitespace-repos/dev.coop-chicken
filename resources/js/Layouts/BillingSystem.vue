<template>
    <header class="navbar navbar-dark sticky-top bg-primary flex-md-nowrap p-0 shadow" id="navbar">
        <Link :href="route('make-sale')" class="navbar-brand col-md-3 col-lg-2 me-0 px-3 fs-6 py-1">
            <img src="/assets/img/logo-bg.png" alt="logo">
        </Link>
        <button class="navbar-toggler position-absolute d-md-none collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#sidebarMenu" aria-controls="sidebarMenu" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <h6 class="text-white heading mb-0" style="line-height: 20px;"> {{ 'COOP - ' + $page.props.shop.shop_name }}  <br /> <small class="d-flex align-items-center justify-content-center font-weight-bold"><span data-feather="truck" class="mr-2"></span>{{ $page.props.auth.user.shop.supplier.name }} </small></h6>

        <div class="navbar-nav">
            <div class="nav-item text-nowrap d-flex align-items-center">
                <li class="nav-link border-right pr-3 text-black"><span data-feather="user" class="mr-2"></span>{{ $page.props.auth.user.name }}</li>
                <Link href="/logout" as="button" method="post" class="nav-link px-3  btn btn-link">Sign out</Link>
            </div>
        </div>
    </header>

    <!-- Page Content -->
     <!-- Page Content -->

    <main class="pt-3 px-3 pb-2" style="height:94vh;overflow:auto">
        <div class="container-fluid">
            <slot />
        </div>
    </main>

</template>

<script>
import BreezeApplicationLogo from '@/Components/ApplicationLogo.vue'
import BreezeDropdown from '@/Components/Dropdown.vue'
import BreezeDropdownLink from '@/Components/DropdownLink.vue'
import BreezeNavLink from '@/Components/NavLink.vue'
import BreezeResponsiveNavLink from '@/Components/ResponsiveNavLink.vue'
import { Link } from '@inertiajs/inertia-vue3';

export default {
    components: {
        BreezeApplicationLogo,
        BreezeDropdown,
        BreezeDropdownLink,
        BreezeNavLink,
        BreezeResponsiveNavLink,
        Link,
    },

    data() {
        return {
            showingNavigationDropdown: false,
        }
    },
}

</script>
