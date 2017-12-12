require('./bootstrap');
import Vue from 'vue';
import App from './App.vue';

import Bulma from 'bulma';
import Axios from 'axios';
import XML from 'xmlbuilder';

export default new Vue({
    el: '#root',
    components: {
        'app': App
    }
});
