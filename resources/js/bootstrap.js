import axios from 'axios';
window.axios = axios;

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

/**
 * Echo exposes an expressive API for subscribing to channels and listening
 * for events that are broadcast by Laravel. Echo and event broadcasting
 * allow your team to quickly build robust real-time web applications.
 */

import './echo';

import Echo from 'laravel-echo';
import Reverb from 'reverb-js';

window.Pusher = require('pusher-js');
window.Echo = new Echo({
    broadcaster: 'reverb',
    host: window.location.hostname + ':8080',  // Sesuaikan dengan host Reverb
    clientId: 'your-client-id',  // Sesuaikan dengan App key yang sesuai di Reverb
    namespace: 'App.Events',
});
