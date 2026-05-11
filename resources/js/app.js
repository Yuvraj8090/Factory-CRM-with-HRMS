import './bootstrap';

import '@fortawesome/fontawesome-free/css/all.min.css';
import 'bootstrap/dist/css/bootstrap.min.css';
import 'admin-lte/dist/css/adminlte.min.css';

import $ from 'jquery';
import * as bootstrap from 'bootstrap';
import Alpine from 'alpinejs';
import 'admin-lte';

window.$ = $;
window.jQuery = $;
window.bootstrap = bootstrap;
window.Alpine = Alpine;

Alpine.start();
