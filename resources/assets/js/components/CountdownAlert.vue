<template>
    <div v-if="visible" class="alert" :class="['alert-' + variant, { 'alert-dismissible': dismissible }]" role="alert">
        <slot></slot>
        <button v-if="dismissible" type="button" class="close" aria-label="Close" @click="dismiss">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
</template>

<script>
    export default {
        props: {
            show: { type: [Number, Boolean], default: false },
            variant: { type: String, default: 'info' },
            dismissible: Boolean,
        },
        emits: ['dismiss-count-down'],

        data() {
            return { timer: null };
        },

        computed: {
            visible() {
                return this.show === true || this.show > 0;
            },
        },

        watch: {
            show: {
                immediate: true,
                handler(value) {
                    clearTimeout(this.timer);
                    if (typeof value === 'number' && value > 0) {
                        this.timer = setTimeout(() => this.$emit('dismiss-count-down', value - 1), 1000);
                    }
                },
            },
        },

        unmounted() {
            clearTimeout(this.timer);
        },

        methods: {
            dismiss() {
                clearTimeout(this.timer);
                this.$emit('dismiss-count-down', 0);
            },
        },
    }
</script>
