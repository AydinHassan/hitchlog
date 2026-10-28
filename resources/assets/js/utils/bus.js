const handlers = {};

export default {
    on(event, handler) {
        (handlers[event] = handlers[event] || []).push(handler);
    },
    off(event, handler) {
        handlers[event] = (handlers[event] || []).filter(h => h !== handler);
    },
    emit(event, payload) {
        (handlers[event] || []).slice().forEach(h => h(payload));
    },
};
