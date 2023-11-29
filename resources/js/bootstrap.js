let cookieName = 'XSRF-TOKEN' + (!import.meta.env.VITE_SESSION_TOKEN ? '' : '-' + import.meta.env.VITE_SESSION_TOKEN);

/**
 * We'll load the axios HTTP library which allows us to easily issue requests
 * to our Laravel back-end. This library automatically handles sending the
 * CSRF token as a header based on the value of the "XSRF" token cookie.
 */

import axios from 'axios';
window.axios = axios;

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
window.axios.defaults.xsrfCookieName = cookieName;
window.axios.interceptors.response.use(
    response => { return response; },
    error => {
        if (
            error.request.responseType === 'blob' &&
            error.response.data instanceof Blob &&
            error.response.data.type &&
            error.response.data.type.toLowerCase().indexOf('json') != -1
        )
        {
            return new Promise((resolve, reject) => {
                let reader = new FileReader();
                reader.onload = () => {
                    error.response.data = JSON.parse(reader.result);
                    resolve(Promise.reject(error));
                };

                reader.onerror = () => {
                    reject(error);
                };

                reader.readAsText(error.response.data);
            });
        };

        return Promise.reject(error);
    }
)

/**
 * Echo exposes an expressive API for subscribing to channels and listening
 * for events that are broadcast by Laravel. Echo and event broadcasting
 * allows your team to easily build robust real-time web applications.
 */

// import Echo from 'laravel-echo';

import Pusher from "pusher-js";
window.Pusher = Pusher;
window.pusher = new Pusher('a15494ac42b53378ba1c', {encrypted: true, cluster: 'us3'})

// window.Echo = new Echo({
//     broadcaster: 'pusher',
//     key: import.meta.env.VITE_PUSHER_APP_KEY,
//     cluster: import.meta.env.VITE_PUSHER_APP_CLUSTER ?? 'mt1',
//     wsHost: import.meta.env.VITE_PUSHER_HOST ? import.meta.env.VITE_PUSHER_HOST : `ws-${import.meta.env.VITE_PUSHER_APP_CLUSTER}.pusher.com`,
//     wsPort: import.meta.env.VITE_PUSHER_PORT ?? 80,
//     wssPort: import.meta.env.VITE_PUSHER_PORT ?? 443,
//     forceTLS: (import.meta.env.VITE_PUSHER_SCHEME ?? 'https') === 'https',
//     enabledTransports: ['ws', 'wss'],
// });

import Swal from 'sweetalert2/src/sweetalert2.js'
window.Swal = Swal


