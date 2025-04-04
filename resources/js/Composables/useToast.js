import toast from "@/utils/toast";

export function useToast() {
    return {
        success: (message, duration) => toast.success(message, duration),
        error: (message, duration) => toast.error(message, duration),
        info: (message, duration) => toast.info(message, duration),
    };
}
