<template>
    <div>
        <form 
              action="/journey"
              @submit.prevent="onSubmit"
              @keydown="form.errors.clear()"
              novalidate
                            autocomplete="off"
              class="space-y-4">
            <div>
                <label for="start_location" class="field-label">Start Location</label>
                <input id="start_location"
                       type="text"
                       class="field-input"
                       v-bind:class="{ 'is-invalid': form.errors.has('start_location')}"
                       name="start_location"
                       required
                       autofocus
                       v-model="form.start_location"
                >
                <p class="field-error" v-if="form.errors.has('start_location')" v-text="form.errors.get('start_location')"></p>
            </div>

            <div>
                <label for="end_location" class="field-label">End Location</label>
                <input id="end_location"
                       type="text"
                       class="field-input"
                       v-bind:class="{ 'is-invalid': form.errors.has('end_location')}"
                       name="end_location"
                       required
                       autofocus
                       v-model="form.end_location"
                >
                <p class="field-error" v-if="form.errors.has('end_location')" v-text="form.errors.get('end_location')"></p>
            </div>

            <div>
                <label for="date" class="field-label">Date</label>
                <datepicker name="date" v-model="form.date"></datepicker>
                <p class="field-error" v-if="form.errors.has('date')" v-text="form.errors.get('date')"></p>
            </div>

            <div class="grid grid-cols-2 gap-4 text-center">
                <div>
                    <timepicker name="start_time" v-model="form.start_time">Start Time</timepicker>
                </div>
                <div>
                    <timepicker name="end_time" v-model="form.end_time">End Time</timepicker>
                </div>
            </div>

            <div>
                <label for="driver_name" class="field-label">Driver Name</label>

                <input id="driver_name"
                       type="text"
                       class="field-input"
                       v-bind:class="{ 'is-invalid': form.errors.has('driver_name')}"
                       name="driver_name"
                       autofocus
                       v-model="form.driver_name"
                >
                <p class="field-error" v-if="form.errors.has('driver_name')" v-text="form.errors.get('driver_name')"></p>
            </div>

            <div>
                <label for="notes" class="field-label">Notes</label>

                <textarea id="notes"
                          type="text"
                          class="field-input"
                          v-bind:class="{ 'is-invalid': form.errors.has('notes')}"
                          name="notes"
                          autofocus
                          v-model="form.notes">
                </textarea>
                <p class="field-error" v-if="form.errors.has('notes')" v-text="form.errors.get('notes')"></p>
            </div>

            <div class="grid gap-3 pt-2 sm:grid-cols-12">
                <button type="submit" class="btn btn-primary w-full sm:col-span-7" :disabled="form.errors.any()">Save Journey</button>
                <button type="button"
                        class="btn btn-outline w-full sm:col-span-5"
                        v-on:click.prevent="cancelEdit()">Cancel</button>
            </div>
        </form>
    </div>
</template>

<script>
    import Datepicker from './DatePicker.vue';
    import Timepicker from './TimePicker.vue';
    import CountdownAlert from './CountdownAlert.vue';
    import bus from '../utils/bus';
    
    const startTime = new Date();
    startTime.setHours(10, 0, 0);

    const endTime = new Date();
    endTime.setHours(11, 0, 0);
    
    export default {
        components: {Datepicker, Timepicker, CountdownAlert},
        
        mounted () {
            bus.on('journey.edit', journey => {
                this.id = journey.id;                
                this.form.start_location = journey.start_location;
                this.form.end_location = journey.end_location;
                this.form.date = journey.date.toDate();
                this.form.start_time = this.timeStringToDate(journey.start_time);
                this.form.end_time = this.timeStringToDate(journey.end_time);
                this.form.driver_name = journey.driver_name;
                this.form.notes = journey.notes;
            })  
        },

        data() {
            return {
                id: 0,
                successCountDown : 0,
                form: new Form(
                    {
                        start_location: '',
                        end_location: '',
                        date: new Date(),
                        start_time: new Date(),
                        end_time: new Date(),
                        driver_name: '',
                        notes: ''
                    },
                    {
                        date: function (dateTime) {
                            return moment(dateTime).format('YYYY-MM-DD')
                        },
                        start_time: function (dateTime) {
                            return moment(dateTime).format('HH:mm');
                        },
                        end_time: function (dateTime) {
                            return moment(dateTime).format('HH:mm');
                        },
                    }
                ),
            }
        },
        
        methods : {
            timeStringToDate (timeString) {
                var time = timeString.split(':');
                let dateTime = new Date();
                dateTime.setHours(time[0], time[1], 0);
                return dateTime;
            },
            
            cancelEdit () {
                this.form.reset();
                bus.emit('journey.edit.cancel');
            },
            onSubmit () {
                this.form
                    .patch('/journey/' + this.id)
                    .then(journey => {
                        this.id = 0;
                        bus.emit('journey.edited', journey)
                    })
                    .catch(() => {})
            }
        }
    }
</script>
