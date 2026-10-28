<template>
    <div v-if="visible"
         class="mb-4 flex items-center justify-between gap-4 border-b-4 px-4 py-3 text-sm"
         :class="variants[variant] || variants.info"
         role="alert">
        <div><slot></slot></div>
        <button v-if="dismissible" type="button" class="cursor-pointer text-lg leading-none opacity-60 hover:opacity-100" aria-label="Close" @click="dismiss">
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
            return {
                timer: null,
                variants: {
                    success: 'border-green-300 bg-green-50 text-green-800',
                    info: 'border-sky-300 bg-sky-50 text-sky-800',
                },
            };
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
