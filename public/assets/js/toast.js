window.showToast = function ({
    text = '',
    type = 'info', // success | error | warning | info
    duration = 3000
}) {
    const colors = {
        success: "#16a34a",
        error: "#dc2626",
        warning: "#f59e0b",
        info: "#2563eb"
    };

    Toastify({
        text: text,
        duration: duration,
        gravity: "top",
        position: "right",
        close: true,
        style: {
            background: colors[type] || colors.info
        }
    }).showToast();
};
