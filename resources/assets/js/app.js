import './bootstrap';

import { createApp } from 'vue';
import bus from './utils/bus';

import AddJourney from './components/AddJourney.vue';
import EditJourney from './components/EditJourney.vue';
import ListJourneys from './components/ListJourneys.vue';

const app = createApp({
    data() {
        return {
            editing: false,
        };
    },

    mounted() {
        bus.on('journey.edit', () => {
            this.editing = true;
        });

        bus.on('journey.edited', () => {
            this.editing = false;
        });

        bus.on('journey.edit.cancel', () => {
            this.editing = false;
        });
    },
});

app.component('add-journey', AddJourney);
app.component('edit-journey', EditJourney);
app.component('list-journeys', ListJourneys);

app.mount('#app');
