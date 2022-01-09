require('./bootstrap');

import Alpine from 'alpinejs';
window.Alpine = Alpine;
import './../../vendor/power-components/livewire-powergrid/dist/powergrid';
Alpine.start();

import flatpickr from "flatpickr";
require("flatpickr/dist/themes/dark.css");

flatpickr(".flatpickrSelector", {
    enableTime: true,
    dateFormat: "Y-m-d H:i:S",
    time_24hr: true,
});

var date_max = new Date();
date_max.setFullYear(date_max.getFullYear() - 13);

var date_min = new Date();
date_min.setFullYear(date_min.getFullYear() - 90);

flatpickr(".flatpickrBirtdate", {
    enableTime: false,
    dateFormat: "Y-m-d",
    minDate: date_min,
    maxDate: date_max
});

import 'tw-elements';

import EasyMDE from 'easyMDE';
const easyMDE = new EasyMDE({ 
    element: document.getElementById("easyMDE"),
});