const { default: Echo } = require('laravel-echo');

require('./bootstrap');
Echo.channel("notify").listen("SendMessage", (e) => {
    alert("ffffff");
});

