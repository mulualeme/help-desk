// Toast manager that can be imported anywhere in the app

let containerRef = null;

export function setToastContainer(ref) {
    containerRef = ref;
}

export function toast(message, type = "success", duration = 5000) {
    if (!containerRef) {
        console.warn("Toast container reference not set");
        return;
    }

    return containerRef.addToast(message, type, duration);
}

export default {
    setToastContainer,
    success: (message, duration) => toast(message, "success", duration),
    error: (message, duration) => toast(message, "error", duration),
    info: (message, duration) => toast(message, "info", duration),
};
