/*
Toast para mostrar errores y alertas
 */

// {{--Alertas con Toastr--}}
function showAlert(type,text_toastr) {

    switch (type) {
        case 'info':
            showInfo(text_toastr);
            break;

        case 'warning':
            showWarning(text_toastr);
            break;

        case 'success':
            showSucces(text_toastr);
            break;

        case 'error':
            showError(text_toastr);
            break;
    }

}

// {{-- FIN Alertas con Toastr--}}

function showError(errors) {
    toastr.error(errors, '', {
        "positionClass": "toast-top-center",
        timeOut: 5000,
        "closeButton": true,
        "debug": false,
        "newestOnTop": true,
        "progressBar": true,
        "preventDuplicates": true,
        "onclick": null,
        "showDuration": "300",
        "hideDuration": "1000",
        "extendedTimeOut": "1000",
        "showEasing": "swing",
        "hideEasing": "linear",
        "showMethod": "fadeIn",
        "hideMethod": "fadeOut",
        "tapToDismiss": false
    });
}

//funcion para pasar los warning al toast
function showWarning(warning) {
    toastr.warning(warning, '', {
        "positionClass": "toast-top-center",
        timeOut: 5000,
        "closeButton": true,
        "debug": false,
        "newestOnTop": true,
        "progressBar": true,
        "preventDuplicates": true,
        "onclick": null,
        "showDuration": "300",
        "hideDuration": "1000",
        "extendedTimeOut": "1000",
        "showEasing": "swing",
        "hideEasing": "linear",
        "showMethod": "fadeIn",
        "hideMethod": "fadeOut",
        "tapToDismiss": false
    });
}
//funcion para pasar los mensajes success al toast
function showSucces(message) {
    toastr.success(message, '', {
        "positionClass": "toast-top-center",
        timeOut: 5000,
        "closeButton": true,
        "debug": false,
        "newestOnTop": true,
        "progressBar": true,
        "preventDuplicates": true,
        "onclick": null,
        "showDuration": "300",
        "hideDuration": "1000",
        "extendedTimeOut": "1000",
        "showEasing": "swing",
        "hideEasing": "linear",
        "showMethod": "fadeIn",
        "hideMethod": "fadeOut",
        "tapToDismiss": false
    });
}
//funcion para pasar los mensajes success al toast
function showInfo(message) {
    toastr.info(message, '', {
        "positionClass": "toast-top-center",
        timeOut: 5000,
        "closeButton": true,
        "debug": false,
        "newestOnTop": true,
        "progressBar": true,
        "preventDuplicates": true,
        "onclick": null,
        "showDuration": "300",
        "hideDuration": "1000",
        "extendedTimeOut": "1000",
        "showEasing": "swing",
        "hideEasing": "linear",
        "showMethod": "fadeIn",
        "hideMethod": "fadeOut",
        "tapToDismiss": false
    });
}


