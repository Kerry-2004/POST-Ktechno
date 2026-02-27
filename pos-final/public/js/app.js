// Auto-dismiss alerts
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.alert.fade.show').forEach(function (el) {
        setTimeout(function () {
            bootstrap.Alert.getOrCreateInstance(el)?.close();
        }, 4000);
    });
});
