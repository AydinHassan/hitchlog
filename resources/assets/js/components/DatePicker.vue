<template>
    <vue-date-picker
        :model-value="modelValue"
        @update:model-value="value => $emit('update:modelValue', value)"
        :time-config="{ enableTimePicker: false }"
        :formats="{ input: 'dd MMM yyyy' }"
        :input-attrs="{ name: name, clearable: false }"
        :ui="{ input: 'form-control' }"
        auto-apply
    ></vue-date-picker>
</template>

<script>
    import { VueDatePicker } from '@vuepic/vue-datepicker';
    import '@vuepic/vue-datepicker/dist/main.css';

    export default {
        components: { VueDatePicker },
        props: {
            name: String,
            modelValue: Date,
        },
        emits: ['update:modelValue'],
    }
</script>
