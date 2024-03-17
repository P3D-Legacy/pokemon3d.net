import './bootstrap';

import Alpine from 'alpinejs';
window.Alpine = Alpine;
Alpine.start();

import flatpickr from 'flatpickr';
import 'flatpickr/dist/themes/dark.css';

flatpickr('.flatpickrSelector', {
    enableTime: true,
    dateFormat: 'Y-m-d H:i:S',
    time_24hr: true,
});

var date_max = new Date();
date_max.setFullYear(date_max.getFullYear() - 13);

var date_min = new Date();
date_min.setFullYear(date_min.getFullYear() - 90);

flatpickr('.flatpickrBirthdate', {
    enableTime: false,
    dateFormat: 'd-m-Y',
    minDate: date_min,
    maxDate: date_max,
});

import EasyMDE from 'easymde';
window.EasyMDE = EasyMDE;
