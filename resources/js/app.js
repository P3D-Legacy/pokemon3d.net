require('./bootstrap');

import flatpickr from "flatpickr";
flatpickr(".flatpickrSelector", {
    enableTime: true,
    dateFormat: "Y-m-d H:i:ss",
    time_24hr: true,
});

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();
