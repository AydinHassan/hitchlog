<template>
    <div>
        <form 
              action="/journey"
              @submit.prevent="onSubmit"
              @keydown="form.errors.clear()"
              novalidate
              style="padding-bottom: 20px;"
              autocomplete="off">
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

            <div class="form-group">
                <div class="row">
                    <div class="col-sm-7">
                        <button type="submit" class="btn btn-info button-primary w-max" :disabled="form.errors.any()">Save Journey</button>
                    </div>
                    <div class="col-sm-5">
                        <button type="button" 
                                class="btn btn-outline-info button-primary w-max"
                                v-on:click.prevent="cancelEdit()">Cancel</button>
                    </div>
                </div>
            </div>
        </form>
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
        
        mounted () {
            this.$root.$on('journey.edit', journey => {
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
                this.$root.$emit('journey.edit.cancel');
            },
            onSubmit () {
                this.form
                    .patch('/journey/' + this.id)
                    .then(journey => {
                        this.id = 0;
                        this.$root.$emit('journey.edited', journey)
                    })
            }
        }
    }
</script>
