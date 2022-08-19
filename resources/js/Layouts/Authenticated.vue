<template>
    <header class="navbar navbar-primary sticky-top bg-primary flex-md-nowrap p-0 shadow">
        <Link :href="route('dashboard')" class="navbar-brand col-auto py-1" style="width: 5.7em;">
            <img src="/assets/img/logo-bg.png" alt="logo">
        </Link>
        <button class="navbar-toggler position-absolute d-md-none collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#sidebarMenu" aria-controls="sidebarMenu" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="navbar-nav">
            <div class="nav-item text-nowrap">
                <Link href="/logout" as="button" method="post" class="nav-link px-3  btn btn-link">Sign out</Link>
            </div>
        </div>
    </header>
    <!-- Page Content -->
    <div class="container-fluid" style="max-height: 93.8vh;overflow: auto;height: 93.8vh;">
        <div class="row">
            <nav id="sidebarMenu" class="bg-primary sidebar collapse show pt-0">
                <div class="position-sticky pt-3 sidebar-sticky custom-scrollbar">
                    <ul class="nav flex-column text-xs space-x-1 flex mt-5">
                        <li class="nav-item nm-flat-dark-shadow rounded-xl text-center nav-shadow">
                            <Link href="/dashboard" class="nav-link  px-3 py-4" aria-current="page" :class="{ 'active': $page.url.startsWith('/dashboard') }">
                                <Icon icon="radix-icons:dashboard" />
                            </Link>
                        </li>
                        <li class="nav-item rounded-xl text-center">
                            <Link class="nav-link" href="/shop" :class="{ 'active': $page.url.startsWith('/shop') }">
                                Shops
                            </Link>
                        </li>
                        <li class="nav-item rounded-xl text-center">
                            <Link class="nav-link" href="/product" :class="{ 'active': $page.url.startsWith('/product') }">
                                Products
                            </Link>
                        </li>

                        <li class="nav-item rounded-xl text-center">
                            <Link class="nav-link" href="/user" :class="{ 'active': $page.url.startsWith('/user') }">
                                Users
                            </Link>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">
                                Expenses
                            </a>
                        </li>
                        <li class="nav-item">
                            <Link class="nav-link" href="/rate" :class="{ 'active': $page.url.startsWith('/rate') }">
                                Rates
                            </Link>
                        </li>
                    </ul>

                </div>
            </nav>

            <main class="col-11 ml-auto py-4 bg-primary">
                <slot />
            </main>
        </div>
    </div>
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
    mounted() {
        feather.replace({ 'aria-hidden': 'true' })
    },
}
</script>
