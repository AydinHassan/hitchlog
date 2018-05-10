<template>
    <div>
        <form method="POST" 
              action="/journey" 
              @submit.prevent="onSubmit" 
              @keydown="form.errors.clear()" 
              novalidate
              style="padding-bottom: 20px;">
            <div class="form-group">
                <label for="start_location" class=" col-form-label text-md-right">Start Location</label>
                <input id="start_location" 
                    type="text" 
                    class="form-control"
                    v-bind:class="{ 'is-invalid': form.errors.has('start_location')}"
                    name="start_location"
                    required
                    autofocus
                    v-model="form.start_location"
                >
                <span class="invalid-feedback">
                    <strong v-text="form.errors.get('start_location')"></strong>
                </span>
            </div>
    
            <div class="form-group">
                <label for="end_location" class=" col-form-label text-md-right">End Location</label>
                <input id="end_location"
                       type="text"
                       class="form-control"
                       v-bind:class="{ 'is-invalid': form.errors.has('end_location')}"
                       name="end_location"
                       required
                       autofocus
                       v-model="form.end_location"
                >
                <span class="invalid-feedback">
                    <strong v-text="form.errors.get('end_location')"></strong>
                </span>
            </div>
    
            <div class="form-group">
                <label for="date" class=" col-form-label text-md-right">Date</label>
                <datepicker name="date" v-model="form.date" :bootstrap-styling="true"></datepicker>
                <span class="invalid-feedback">
                    <strong v-text="form.errors.get('date')"></strong>
                </span>
            </div>
            
            <div class="row">
                <div class="col-sm-6 text-center">
                    <timepicker name="start_time" v-model="form.start_time">Start Time</timepicker>
                </div>
                <div class="col-sm-6 text-center">
                    <timepicker name="end_time" v-model="form.end_time">End Time</timepicker>
                </div>
            </div>
            
            <div class="form-group">
                <label for="driver_name" class=" col-form-label text-md-right">Driver Name</label>
    
                <input id="driver_name" 
                       type="text"
                       class="form-control"
                       v-bind:class="{ 'is-invalid': form.errors.has('driver_name')}"
                       name="driver_name" 
                       autofocus 
                       v-model="form.driver_name"
                >
                <span class="invalid-feedback">
                    <strong v-text="form.errors.get('driver_name')"></strong>
                </span>
            </div>
    
            <div class="form-group">
                <label for="notes" class=" col-form-label text-md-right">Notes</label>
    
                <textarea id="notes"
                          type="text"
                          class="form-control"
                          v-bind:class="{ 'is-invalid': form.errors.has('notes')}"
                          name="notes"
                          autofocus
                          v-model="form.notes">
                </textarea>
                <span class="invalid-feedback">
                    <strong v-text="form.errors.get('notes')"></strong>
                </span>
            </div>
    
            <div class="form-group mb-0" style="display: flex; justify-content: space-around">
                <div class="">
                    <button type="submit" class="btn btn-info button-primary" :disabled="form.errors.any()">Create Journey</button>
                </div>
            </div>
        </form>
        <b-alert dismissible variant="success" :show="successCountDown" @dismiss-count-down="countDownChanged">Successfully Added!</b-alert>
    </div>
</template>

<script>
    import Datepicker from 'vuejs-datepicker';
    import Timepicker from './TimePicker';
    
    const startTime = new Date();
    startTime.setHours(10, 0, 0);

    const endTime = new Date();
    endTime.setHours(11, 0, 0);
    
    export default {
        components: {Datepicker, Timepicker},
        
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
                        this.$root.$emit('journey.added', journey)
                        this.successCountDown = 4;
                    })                
            },

            countDownChanged (dismissCountDown) {
                this.successCountDown = dismissCountDown
            }
        }
    }
</script>
