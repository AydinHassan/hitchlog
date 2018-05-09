<template>
    <div class="form-group">
        <label v-bind:id="name" class="col-form-label text-center"><slot></slot></label>
        <section @click.stop style="">
            <table style="margin: 0 auto">
                <tbody>
                    <tr class="text-center">
                        <td>
                            <button type="button" class="btn btn-link btn-sm" @click="changeTime(1,1)">
                                <i class="fas fa-chevron-up"></i>
                            </button>
                        </td>
                        <td>&nbsp;</td>
                        <td>
                            <button type="button" class="btn btn-link btn-sm" @click="changeTime(0,1)">
                                <i class="fas fa-chevron-up"></i>
                            </button>
                        </td>
                    </tr>
                    <tr>
                        <td class="form-group">
                            <input
                                    ref="hoursInput"
                                    type="tel"
                                    pattern="\d*"
                                    class="form-control text-center"
                                    style="width: 50px"
                                    @mouseup="selectInputValue"
                                    @keydown.prevent.up="changeTime(1, 1)"
                                    @keydown.prevent.down="changeTime(1, 0)"
                                    @wheel="onWheel($event, true)"
                                    placeholder="HH"
                                    v-model.lazy="hoursText"
                                    maxlength="2"
                                    size="2"
                                    v-bind:aria-labelledby="name">
                        </td>
                        <td>&nbsp;<b>:</b>&nbsp;</td>
                        <td class="form-group">
                            <input
                                    ref="minutesInput"
                                    type="tel"
                                    pattern="\d*"
                                    class="form-control text-center"
                                    style="width: 50px"
                                    @mouseup="selectInputValue"
                                    @keydown.prevent.up="changeTime(0, 1)"
                                    @keydown.prevent.down="changeTime(0, 0)"
                                    @wheel="onWheel($event, false)"
                                    placeholder="MM"
                                    v-model.lazy="minutesText"
                                    maxlength="2"
                                    size="2"
                                    v-bind:aria-labelledby="name">
                        </td>
                    </tr>
                    <tr class="text-center">
                        <td>
                            <button type="button" class="btn btn-link btn-sm" @click="changeTime(1,0)">
                                <i class="fas fa-chevron-down"></i>
                            </button>
                        </td>
                        <td>&nbsp;</td>
                        <td>
                            <button type="button" class="btn btn-link btn-sm" @click="changeTime(0,0)">
                                <i class="fas fa-chevron-down"></i>
                            </button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </section>
        <!--<timepicker :minute-interval="10" v-model="startTime"></timepicker>-->
    </div>
</template>

<script>
    import {pad} from '../utils/stringUtils'
    
    const maxHours = 23
    const zero = 0
    const maxMinutes = 59
    
    export default {
        props : {
            name: {
                type: String,
                required: true
            },
            value: {
                type: Date,
                required: true
            },
            min: Date,
            max: Date,
            hourStep: {
                type: Number,
                default: 1
            },
            minStep: {
                type: Number,
                default: 1
            },
        },

        data () {
            return {
                hours: 0,
                minutes: 0,
                hoursText: '',
                minutesText: ''
            }
        },

        mounted () {
            this.updateByValue(this.value)
        },
        
        watch : {
            value (value) {
                this.updateByValue(value)
            },
            hoursText (value) {
                if (this.hours === 0 && value === '') {
                    // Prevent a runtime reset from being overwritten
                    return
                }
                let hour = parseInt(value)
                if (hour >= zero && hour <= maxHours) {
                    this.hours = hour
                }
                this.setTime()
            },
            minutesText (value) {
                if (this.minutes === 0 && value === '') {
                    // Prevent a runtime reset from being overwritten
                    return
                }
                let minutesStr = parseInt(value)
                if (minutesStr >= zero && minutesStr <= maxMinutes) {
                    this.minutes = minutesStr
                }
                this.setTime()
            }
        },
        
        methods : {
            updateByValue (value) {
                if (isNaN(value.getTime())) {
                    this.hours = 0
                    this.minutes = 0
                    this.hoursText = ''
                    this.minutesText = ''
                    return
                }
                this.hours = value.getHours()
                this.minutes = value.getMinutes()
                this.hoursText = pad(this.hours, 2)
                this.minutesText = pad(this.minutes, 2)
                // lazy model won't update when using keyboard up/down
                this.$refs.hoursInput.value = this.hoursText
                this.$refs.minutesInput.value = this.minutesText
            },
            addHour (step) {
                step = step || this.hourStep
                this.hours = this.hours >= maxHours ? zero : this.hours + step
            },
            reduceHour (step) {
                step = step || this.hourStep
                this.hours = this.hours <= zero ? maxHours : this.hours - step
            },
            addMinute () {
                if (this.minutes >= maxMinutes) {
                    this.minutes = zero
                    this.addHour(1)
                } else {
                    this.minutes += this.minStep
                }
            },
            reduceMinute () {
                if (this.minutes <= zero) {
                    this.minutes = maxMinutes
                    this.reduceHour(1)
                } else {
                    this.minutes -= this.minStep
                }
            },
            changeTime (isHour, isPlus) {
                if (isHour && isPlus) {
                    this.addHour()
                } else if (isHour && !isPlus) {
                    this.reduceHour()
                } else if (!isHour && isPlus) {
                    this.addMinute()
                } else {
                    this.reduceMinute()
                }
                this.setTime()
            },
            onWheel (e, isHour) {
                e.preventDefault()
                this.changeTime(isHour, e.deltaY < 0)
            },
            setTime () {
                let time = this.value
                if (isNaN(time.getTime())) {
                    time = new Date()
                    time.setHours(0)
                    time.setMinutes(0)
                }
                time.setHours(this.hours)
                time.setMinutes(this.minutes)
                if (this.max) {
                    let max = new Date(time)
                    max.setHours(this.max.getHours())
                    max.setMinutes(this.max.getMinutes())
                    time = time > max ? max : time
                }
                if (this.min) {
                    let min = new Date(time)
                    min.setHours(this.min.getHours())
                    min.setMinutes(this.min.getMinutes())
                    time = time < min ? min : time
                }
                this.$emit('input', new Date(time))
            },
            selectInputValue (e) {
                // mouseup should be prevented!
                // See various comments in https://stackoverflow.com/questions/3272089/programmatically-selecting-text-in-an-input-field-on-ios-devices-mobile-safari
                e.target.setSelectionRange(0, 2)
            }
        }
    }
</script>