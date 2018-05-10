<template>
    <div>
        <b-alert variant="success" :show="deleteSuccessCountDown" @dismiss-count-down="deleteCountDownChanged">Successfully deleted!</b-alert>
        <b-alert variant="success" :show="editSuccessCountDown" @dismiss-count-down="editCountDownChanged">Successfully Edited!</b-alert>

        <table class="table table-hover">
            <thead>
                <th scope="col" class="">Start Location</th>
                <th scope="col" class="">End Location</th>
                <th scope="col" class="" >Date</th>
                <th scope="col" class="">Start Time</th>
                <th scope="col">End Time</th>
                <th scope="col">Driver</th>
                <th scope="col" class="w-25">Notes</th>
                <th scope="col">Added By</th>
                <th scope="col"></th>
            </thead>
            <tbody>
                <tr v-for="journey in descendingJourneys">
                    <td>{{ journey.start_location }}</td>
                    <td>{{ journey.end_location }}</td>
                    <td>{{ journey.date | date }}</td>
                    <td>{{ journey.start_time }}</td>
                    <td>{{ journey.end_time }}</td>
                    <td>{{ journey.driver_name }}</td>
                    <td>{{ journey.notes }}</td>
                    <td>{{ journey.user.name }}</td>
                    <td class="flex space-around">
                        <div>
                            <a href="#" v-on:click.prevent="editJourney(journey)"><i class="fas fa-edit icon-action"></i></a>
                        </div>
                        <div>
                            <a href="#" v-on:click.prevent="deleteJourney(journey.id)"><i class="fas fa-times-circle icon-action"></i></a>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</template>

<script>
    import EditJourney from './EditJourney';
    
    export default {
        components: {EditJourney},
        
        mounted () {
            axios.get('/journey')
                .then(response => this.setJourneys(response.data));
            
            this.$root.$on('journey.added', journey => {
                this.addJourney(journey)
            });
            
            this.$root.$on('journey.edited', journey => {
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
                return this.journeys.sort((a, b) => {
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
                this.$root.$emit('journey.edit', journey)
            },
        },
        
        filters : {
            date(date) {
                return date.format('Do MMM YYYY');
            }
        }
        
    }
</script>