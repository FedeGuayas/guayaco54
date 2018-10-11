/**
 * Created by Programador2 on 02/10/2018.
 *
 * Integracion de Paymentez
 */


$(function () {

    var paymentezCheckout = new PaymentezCheckout.modal({
        client_app_code: 'FEDE-EC-CLIENT', // Client Credentials Provied by Paymentez
        client_app_key: 'a8N2cTAlauosoRDxM2mPYbdnW9ALmP', // Client Credentials Provied by Paymentez
        locale: 'es', // User's preferred language (es, en, pt). English will be used by default.
        env_mode: 'stg', // `prod`, `stg`, `dev`, `local` to change environment. Default is `stg`
        onOpen: function () {  //The callback to invoke when Checkout is opened
            // console.log('modal open');

        },
        onClose: function () { //The callback to invoke when Checkout is closed
            // console.log('modal closed');
        },
        onResponse: function (response) { // The callback to invoke when the Checkout process is completed
            console.log(response);
            /*
             In Case of an error, this will be the response.
             response = {
             "error": {
             "type": "Server Error",
             "help": "Try Again Later",
             "description": "Sorry, there was a problem loading Checkout."
             }
             }

             When the User completes all the Flow in the Checkout, this will be the response.
             response = {
             "transaction":{
             "status":"success", // success or failure
             "id":"CB-81011", // transaction_id
             "status_detail":3 // for the status detail please refer to: https://paymentez.github.io/api-doc/#status-details
             }
             }
             */
            // console.log('modal response');

            if(response.transaction.status === 'success' && response.transaction.status_detail===3){
                swal(
                    'Transacción satisfactoria',
                    ' Se realizó el pago correctamente',
                    'success'
                )
            } else if (response.transaction.status === 'failure' || response.transaction.status === 'pending' ) {
                let message_error;
                switch (response.transaction.status_detail) {
                    case 9 :
                        message_error = 'Transacción denegada';
                        break;
                    case 1 :
                        message_error = 'Transacción revisada';
                        break;
                    case 11 :
                        message_error = 'Rechazado por transacción de sistema de fraude';
                        break;
                    case 12 :
                        message_error = 'Tarjeta en lista negra';
                        break;
                    default:
                        message_error = 'No se pudo realizar el pago';
                }
                swal(
                    ':( Lo sentimos ocurrio un error durante la transacción',
                    ' ' + message_error + ' ',
                    'error'
                )
            }

            // document.getElementById('response').innerHTML = JSON.stringify(response);
        }
    });

    var btnOpenCheckout = document.querySelector('.js-paymentez-checkout');
    btnOpenCheckout.addEventListener('click', function () {
        // Open Checkout with further options:
        paymentezCheckout.open({
            user_id: '1234', //id de usuario
            user_email: 'test@paymentez.com', //correo facturacion
            user_phone: '7777777777', //telefono facturacion
            order_description: 'Guayaco Runner 2018', //descripcion de la compra
            order_amount: 10.00, //monto del pago
            order_vat: 0, //impuestos
            order_reference: '#234323411', //orden de compra (inscripcion_id)
            //order_installments_type: 2, // optional: The installments type are only available for Equador. The valid values are: https://paymentez.github.io/api-doc/#installments-type
            //order_taxable_amount: 0, // optional: Only available for Datafast (Equador). The taxable amount, if it is zero, it is calculated on the total. Format: Decimal with two fraction digits.
            //order_tax_percentage: 10 // optional: Only available for Datafast (Equador). The tax percentage to be applied to this order.
        });
    });

    // Close Checkout on page navigation:
    window.addEventListener('popstate', function () {
        paymentezCheckout.close();
    });


});