
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

import Vue from 'vue';
import Alert from 'bootstrap-vue/es/components/alert';

Vue.use(Alert);

window.Vue = Vue;

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

import AddJourney from './components/AddJourney';
import EditJourney from './components/EditJourney';
import ListJourneys from './components/ListJourneys';

Vue.component('add-journey', AddJourney);
Vue.component('edit-journey', EditJourney);
Vue.component('list-journeys', ListJourneys);

const app = new Vue({
    el: '#app',
    
    data : {
        editing : false
    },
    
    mounted () {
        this.$root.$on('journey.edit', journey => {
            this.editing = true;
        });
        
        this.$root.$on('journey.edited', journey => {
            this.editing = false;
        });
        
        this.$root.$on('journey.edit.cancel', () => {
            this.editing = false;
        });
    }
});

