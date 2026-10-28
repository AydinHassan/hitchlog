<template>
    <div>
        <countdown-alert variant="success" :show="deleteSuccessCountDown" @dismiss-count-down="deleteCountDownChanged">Successfully deleted!</countdown-alert>
        <countdown-alert variant="success" :show="editSuccessCountDown" @dismiss-count-down="editCountDownChanged">Successfully Edited!</countdown-alert>

        <div class="hidden overflow-x-auto sm:block">
            <table class="w-full text-left text-sm">
                <thead class="border-b-2 border-slate-200 text-slate-700">
                    <tr>
                        <th scope="col" class="px-3 py-3 font-bold">Start Location</th>
                        <th scope="col" class="px-3 py-3 font-bold">End Location</th>
                        <th scope="col" class="px-3 py-3 font-bold">Date</th>
                        <th scope="col" class="px-3 py-3 font-bold">Start Time</th>
                        <th scope="col" class="px-3 py-3 font-bold">End Time</th>
                        <th scope="col" class="px-3 py-3 font-bold">Driver</th>
                        <th scope="col" class="w-1/4 px-3 py-3 font-bold">Notes</th>
                        <th scope="col" class="px-3 py-3 font-bold">Added By</th>
                        <th scope="col" class="px-3 py-3"><span class="sr-only">Actions</span></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200">
                    <tr v-for="journey in descendingJourneys" :key="journey.id" data-journey-row class="hover:bg-white">
                        <td class="px-3 py-3">{{ journey.start_location }}</td>
                        <td class="px-3 py-3">{{ journey.end_location }}</td>
                        <td class="px-3 py-3 whitespace-nowrap">{{ formatDate(journey.date) }}</td>
                        <td class="px-3 py-3">{{ journey.start_time }}</td>
                        <td class="px-3 py-3">{{ journey.end_time }}</td>
                        <td class="px-3 py-3">{{ journey.driver_name }}</td>
                        <td class="px-3 py-3">{{ journey.notes }}</td>
                        <td class="px-3 py-3">{{ journey.user.name }}</td>
                        <td class="px-3 py-2">
                            <div class="flex items-center justify-end gap-3 text-2xl text-ink">
                                <a href="#" class="hover:opacity-70" title="Edit" v-on:click.prevent="editJourney(journey)"><i class="fas fa-edit"></i></a>
                                <a href="#" class="hover:opacity-70" title="Delete" v-on:click.prevent="deleteJourney(journey.id)"><i class="fas fa-times-circle"></i></a>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="space-y-3 sm:hidden">
            <div v-for="journey in descendingJourneys" :key="journey.id" data-journey-card class="panel p-4">
                <div class="flex items-start justify-between gap-3">
                    <h3 class="text-lg font-bold">
                        {{ journey.start_location }}
                        <i class="fas fa-arrow-circle-right px-1 text-violet-400"></i>
                        {{ journey.end_location }}
                    </h3>
                    <div class="flex shrink-0 gap-2 pt-1 text-lg text-indigo-500">
                        <a href="#" class="hover:opacity-70" title="Edit" v-on:click.prevent="editJourney(journey)"><i class="fas fa-edit"></i></a>
                        <a href="#" class="hover:opacity-70" title="Delete" v-on:click.prevent="deleteJourney(journey.id)"><i class="fas fa-times-circle"></i></a>
                    </div>
                </div>
                <p v-if="journey.driver_name" class="mt-1 flex items-center text-sm text-slate-500"><i class="fas fa-car pr-2"></i>{{ journey.driver_name }}</p>
                <p v-if="journey.notes" class="mt-3 text-slate-600">{{ journey.notes }}</p>
                <div class="mt-3 flex flex-wrap gap-2 text-xs font-semibold text-slate-600">
                    <span class="bg-slate-100 px-2 py-1">{{ formatDate(journey.date) }}</span>
                    <span class="bg-slate-100 px-2 py-1">{{ journey.start_time }} - {{ journey.end_time }}</span>
                    <span class="bg-slate-100 px-2 py-1">{{ journey.user.name }}</span>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import CountdownAlert from './CountdownAlert.vue';
    import bus from '../utils/bus';

    export default {
        components: {CountdownAlert},
        
        mounted () {
            axios.get('/journey')
                .then(response => this.setJourneys(response.data));
            
            bus.on('journey.added', journey => {
                this.addJourney(journey)
            });
            
            bus.on('journey.edited', journey => {
                this.editSuccessCountDown = 4;
                
                this.removeJourney(journey.id);
                this.addJourney(journey);
            });
        },
        
        data () {
            return {
                deleteSuccessCountDown : 0,
                editSuccessCountDown : 0,
                journeys : []
            }
        },
        
        computed : {
            descendingJourneys() {
                return [...this.journeys].sort((a, b) => {
                    var aEndDateTime = this.journeyEndDateTime(a);
                    var bEndDateTime = this.journeyEndDateTime(b);
                    
                    return bEndDateTime.unix() - aEndDateTime.unix();
                })
            }
        },
        
        methods : {
            journeyEndDateTime(journey) {
                var dateTime = journey.date.clone();
                var time = journey.end_time.split(':');
                dateTime.hours(time[0]);
                dateTime.minutes(time[1]);
                return dateTime;
            },
            
            setJourneys(journeys) {
                this.journeys = [];
                journeys.forEach(journey => this.addJourney(journey));
            },

            addJourney(journeyData) {
                this.journeys.push({
                    id : journeyData.id,
                    start_location : journeyData.start_location,
                    end_location : journeyData.end_location,
                    date : moment(journeyData.date),
                    start_time : journeyData.start_time,
                    end_time : journeyData.end_time,
                    driver_name : journeyData.driver_name,
                    notes : journeyData.notes,
                    user : {name : journeyData.user.name},
                })
            },
            
            removeJourney(id) {
                this.journeys = this.journeys.filter(journey => {
                    return journey.id !== id;
                })
            },
            
            deleteJourney(id) {
                axios.delete('/journey/' + id)
                    .then(response => {
                        this.deleteSuccessCountDown = 4;
                        this.removeJourney(id);
                    })
            },

            deleteCountDownChanged (dismissCountDown) {
                this.deleteSuccessCountDown = dismissCountDown
            },

            editCountDownChanged (dismissCountDown) {
                this.editSuccessCountDown = dismissCountDown
            },
            
            editJourney(journey) {
                bus.emit('journey.edit', journey)
            },

            formatDate(date) {
                return date.format('Do MMM YYYY');
            },
        }
    }
</script>