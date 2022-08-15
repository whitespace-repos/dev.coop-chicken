<template>
    <header class="navbar navbar-dark sticky-top bg-dark flex-md-nowrap p-0 shadow">
        <Link :href="route('dashboard')" class="navbar-brand col-auto pl-4 pr-5 py-1" style="width:8.9em">
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
    <div class="container-fluid" style="max-height: 91.8vh;overflow: auto;height: 91.8vh;">
        <div class="row">
            <nav id="sidebarMenu" class="bg-dark sidebar collapse show pt-0">
                <div class="position-sticky pt-3 sidebar-sticky custom-scrollbar">
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <Link href="/dashboard" class="nav-link active" aria-current="page">
                                <span data-feather="home" class="align-text-bottom"></span>
                                Dashboard
                            </Link>
                        </li>
                        <li class="nav-item">
                            <Link class="nav-link" href="/shop">
                                <span data-feather="file" class="align-text-bottom"></span>
                                Shops
                            </Link>
                        </li>
                        <li class="nav-item">
                            <Link class="nav-link" href="/product">
                                <span data-feather="layers" class="align-text-bottom"></span>
                                Products
                            </Link>
                        </li>
                        <li class="nav-item">
                            <Link class="nav-link" href="/user">
                                <span data-feather="user" class="align-text-bottom"></span>
                                Users
                            </Link>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">
                                <span data-feather="bar-chart-2" class="align-text-bottom"></span>
                                Expenses
                            </a>
                        </li>
                        <li class="nav-item">
                            <Link class="nav-link" href="/rate">
                                <icon icon="fa6-solid:indian-rupee-sign" />
                                Rates
                            </Link>
                        </li>
                    </ul>
                    <h6 class="sidebar-heading d-flex justify-content-between align-items-center px-3 mt-4 mb-1 text-muted text-uppercase">
                        <span>Reports</span>
                        <a class="link-dark" href="#" aria-label="Add a new report">
                            <span data-feather="plus-circle" class="align-text-bottom"></span>
                        </a>
                    </h6>
                    <ul class="nav flex-column mb-2">
                        <li class="nav-item">
                            <a class="nav-link" href="#">
                                <span data-feather="file-text" class="align-text-bottom"></span>
                                Current month
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">
                                <span data-feather="file-text" class="align-text-bottom"></span>
                                Last quarter
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">
                                <span data-feather="file-text" class="align-text-bottom"></span>
                                Social engagement
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">
                                <span data-feather="file-text" class="align-text-bottom"></span>
                                Year-end sale
                            </a>
                        </li>
                    </ul>
                </div>
            </nav>

            <main class="col py-4">
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
