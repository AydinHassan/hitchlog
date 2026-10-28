<template>
    <div>
        <form method="POST" 
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
    
            <div class="flex justify-center pt-2">
                <button type="submit" class="btn btn-primary px-10" :disabled="form.errors.any()">Create Journey</button>
            </div>
        </form>
        <countdown-alert class="mt-4" dismissible variant="success" :show="successCountDown" @dismiss-count-down="countDownChanged">Successfully Added!</countdown-alert>
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
        
        data() {
            return {
                successCountDown : 0,
                form: new Form(
                    {
                        start_location: '',
                        end_location: '',
                        date: new Date(),
                        start_time: startTime,
                        end_time: endTime,
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
            onSubmit() {
                this.form
                    .post('/journey')
                    .then(journey => {
                        bus.emit('journey.added', journey)
                        this.successCountDown = 4;
                    })
                    .catch(() => {})
            },

            countDownChanged (dismissCountDown) {
                this.successCountDown = dismissCountDown
            }
        }
    }
</script>
