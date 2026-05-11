import './bootstrap';

import '@fortawesome/fontawesome-free/css/all.min.css';
import 'bootstrap/dist/css/bootstrap.min.css';
import 'admin-lte/dist/css/adminlte.min.css';

import $ from 'jquery';
import Popper from 'popper.js';
import Alpine from 'alpinejs';

window.$ = $;
window.jQuery = $;
window.Popper = Popper;

await import('bootstrap');
await import('admin-lte');

window.Alpine = Alpine;

Alpine.start();
