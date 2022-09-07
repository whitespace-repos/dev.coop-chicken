import './bootstrap';
import '../css/app.css';

import { createApp, h } from 'vue';
import { createInertiaApp , Head, Link } from '@inertiajs/inertia-vue3';
import { InertiaProgress } from '@inertiajs/progress';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { ZiggyVue } from '../../vendor/tightenco/ziggy/dist/vue.m';
import { Dataset, DatasetItem, DatasetInfo, DatasetPager, DatasetSearch, DatasetShow } from 'vue-dataset'
import { Icon } from '@iconify/vue';
//
import VueLoading from 'vue-loading-overlay';
import Maska from 'maska'
import { maska } from 'maska'
import vSelect from "vue-select";
import VCalendar from 'v-calendar';
import { Calendar, DatePicker } from 'v-calendar';
import { Inertia } from '@inertiajs/inertia'
import "vue-select/dist/vue-select.css";
import 'vue-loading-overlay/dist/vue-loading.css';
import 'v-calendar/dist/style.css';
import axios from 'axios'
import VueAxios from 'vue-axios'
import ToastPlugin from 'vue-toast-notification';
import 'vue-toast-notification/dist/theme-sugar.css';
const appName = window.document.getElementsByTagName('title')[0]?.innerText || 'Laravel';

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) => resolvePageComponent(`./Pages/${name}.vue`, import.meta.glob('./Pages/**/*.vue')),
    setup({ el, app, props, plugin }) {
        const appInstance =  createApp({ render: () => h(app, props) })
            .use(plugin)
            .use(ZiggyVue, Ziggy)
            .use(VCalendar, {})
            .use(Maska)
            //.use(vfmPlugin)
            .use(VueAxios, axios)
            .use(ToastPlugin,{
                // One of the options
                position: 'top-right'
            })
            .use(VueLoading,{
                // Pass props by their camelCased names
                color: '#BF8A00',
                loader: 'dots',
                width: 50,
                height: 50,
                backgroundColor: '#202123',
                opacity: 0.6,
                zIndex: 9999,
            })
            .component('InertiaHead', Head)
            .component('InertiaLink', Link)
            .component('Dataset', Dataset)
            .component('DatasetItem', DatasetItem)
            .component('DatasetInfo', DatasetInfo)
            .component('DatasetPager', DatasetPager)
            .component('DatasetSearch', DatasetSearch)
            .component('DatasetShow', DatasetShow)
            .component("v-select", vSelect)
            .component('Calendar', Calendar)
            .component('DatePicker', DatePicker)
            .component('Icon',Icon)
            .mixin({
                directives: {
                                maska,
                                currency:{
                                    mounted(el){
                                        el.innerHTML = "&#8377; "+ Number(el.innerHTML).toFixed(2);
                                    }
                                }
                },
                methods: {
                            route,
                            toDecimal : str => Number(str).toFixed(2),
                            beep() {
                                this.playSound("../sound/beep-29.mp3");
                            },
                            clearSound() {
                                this.playSound("../sound/button-21.mp3");
                            },
                            playSound(src) {
                                const sound = new Audio(src);
                                sound.src = src;
                                sound.play();
                            },
                            preserveUrl: url => {
                                                    return  {
                                                                preserveState: true,
                                                                replace:true,
                                                                only:['products'],
                                                                onSuccess: () => {
                                                                    window.history.pushState("Update History","", url.replace(window.location.origin,""))
                                                                }
                                                    };
                            },
                }
            }).mount(el);

        //
        let loader = null;

        // Axios Uses
        axios.interceptors.request.use(config => {
            loader = appInstance.$loading.show();
            return config;
        });

        axios.interceptors.response.use(response => {
            loader.hide();
            return response;
        });


        // Inertia Uses
        Inertia.on('start', () => {
            loader = appInstance.$loading.show();
        })
        //
        Inertia.on('finish', () => {
            feather.replace({ 'aria-hidden': 'true' });
            loader.hide();
        });

        // appInstance.directive('currency', {
        //     /* ... */
        //   });

        return appInstance;
    },
});
//
InertiaProgress.init();
