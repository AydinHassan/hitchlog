<template>
    <div ref="root" class="relative">
        <button id="navbarDropdown"
                type="button"
                class="inline-flex cursor-pointer items-center gap-1.5 rounded-md px-3 py-2 text-sm font-semibold text-slate-600 hover:bg-slate-100 hover:text-slate-900"
                aria-haspopup="true"
                :aria-expanded="open ? 'true' : 'false'"
                @click="open = !open">
            {{ name }}
            <i class="fas fa-caret-down text-xs"></i>
        </button>
        <div v-show="open"
             class="absolute right-0 z-20 mt-1 w-40 rounded-md border border-slate-200 bg-white py-1 shadow-lg"
             aria-labelledby="navbarDropdown">
            <slot></slot>
        </div>
    </div>
</template>

<script>
    export default {
        props: {
            name: String,
        },

        data() {
            return { open: false };
        },

        mounted() {
            document.addEventListener('click', this.onDocumentClick);
            document.addEventListener('keydown', this.onKeydown);
        },

        unmounted() {
            document.removeEventListener('click', this.onDocumentClick);
            document.removeEventListener('keydown', this.onKeydown);
        },

        methods: {
            onDocumentClick(event) {
                if (!this.$refs.root.contains(event.target)) {
                    this.open = false;
                }
            },

            onKeydown(event) {
                if (event.key === 'Escape') {
                    this.open = false;
                }
            },
        },
    }
</script>
