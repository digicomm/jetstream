import './bootstrap';
import '../css/app.scss';
import {createApp, h} from 'vue';
import {createInertiaApp} from '@inertiajs/vue3';
import {resolvePageComponent} from 'laravel-vite-plugin/inertia-helpers';
import {ZiggyVue} from '../../vendor/tightenco/ziggy/dist/vue.m';
import {createPinia} from 'pinia';
import Notifications from 'notiwind';
import './Plugins/tailwind.js';


import {library} from '@fortawesome/fontawesome-svg-core'
import {FontAwesomeIcon} from '@fortawesome/vue-fontawesome'
import {
    faExclamation as farExclamation,
    faInfo as farInfo,
    faCircleUser as farCircleUser,
    faDoNotEnter as farDoNotEnter,
    faUser as farUser,
    faWebhook as farWebhook,
    faCheck as farCheck,
    faLock as farLock,
    faClock as farClock,
    faChevronDown as farChevronDown,
    faInventory as farInventory,
    faTachometer as farTachometer,
    faChevronLeft as farChevronLeft,
    faTimes as farTimes,
    faShieldExclamation as farShieldExclamation,
    faSort as farSort,
    faMagnifyingGlass as farMagnifyingGlass,

} from '@fortawesome/pro-regular-svg-icons'
import {
    faSort as falSort,
    faMagnifyingGlass as falMagnifyingGlass,

} from '@fortawesome/pro-light-svg-icons'


import {
    faSquare as fasSquare,

} from '@fortawesome/pro-solid-svg-icons'
import {faMicrosoft as fabMicrosoft, faWindows as fabWindows} from '@fortawesome/free-brands-svg-icons'

library.add(farExclamation, farCircleUser, farUser, farWebhook, fabMicrosoft, fabWindows, farInfo, farDoNotEnter, farCheck, farLock, farClock, farChevronDown, farInventory, farTachometer, farChevronLeft, fasSquare, farTimes, farShieldExclamation, farSort, farMagnifyingGlass, falMagnifyingGlass, falSort)

const appName = import.meta.env.VITE_APP_NAME || 'Laravel';

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) => resolvePageComponent(`./Pages/${name}.vue`, import.meta.glob('./Pages/**/*.vue')),
    setup({el, App, props, plugin}) {
        return createApp({render: () => h(App, props)})
            .component('FontAwesomeIcon', FontAwesomeIcon)
            .use(plugin)
            .use(ZiggyVue)
            .use(createPinia())
            .use(Notifications)
            .mount(el);
    },
    progress: {
        color: '#007961',
    },
}).then(r => {
});
