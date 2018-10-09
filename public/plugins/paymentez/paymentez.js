/**
 * Created by Programador2 on 02/10/2018.
 *
 * Integracion de Paymentez
 */


$(function() {
    //
    // EXAMPLE CODE FOR PAYMENTEZ INTEGRATION
    // ---------------------------------------------------------------------------
    //  OK
    //  1.) You need to import the Paymentez JS -> https://cdn.paymentez.com/js/v1.0.1/paymentez.min.js
    //  OK
    //  2.) You need to import the Paymentez CSS -> https://cdn.paymentez.com/js/v1.0.1/paymentez.min.css
    //  OK
    //  3.) Add The Paymentez Form
    //  <div class="paymentez-form" id="my-card" data-capture-name="true"></div>
    //  Pedir las credenciales
    //  3.) Init library
    //  Replace "PAYMENTEZ_CLIENT_APP_CODE" and "PAYMENTEZ_CLIENT_APP_KEY" with your own Paymentez Client Credentials.
    //  Preguntar creo no se guardara informacion de las tarjetas
    // 4.) Add Card: converts sensitive card data to a single-use token which you can safely pass to your server to charge the user.
    /**
     * Init library
     *
     * @param env_mode `prod`, `stg`, `local` to change environment. Default is `stg`
     * @param paymentez_client_app_code provided by Paymentez.
     * @param paymentez_client_app_key provided by Paymentez.
     */
    Paymentez.init('stg', 'FEDE-EC-CLIENT', 'a8N2cTAlauosoRDxM2mPYbdnW9ALmP');

    var form              = $("#add-card-form");
    var submitButton            = form.find("button");
    var submitInitialText = submitButton.text();

    $("#add-card-form").submit(function(e){

        var myCard = $('#my-card');
        $('#messages_paymentez').text("");
        var cardToSave = myCard.PaymentezForm('card');
        if(cardToSave == null){
            $('#messages_paymentez').text("Datos de tarjeta inválidos");
        }else{
            $("#loader").attr('hidden', false);
            submitButton.attr("disabled", "disabled").text("Procesando su pago...");

            /*
             After passing all the validations cardToSave should have the following structure:
             var cardToSave = {
             "card": {
             "number": "5119159076977991",
             "holder_name": "Martin Mucito",
             "expiry_month": 9,
             "expiry_year": 2020,
             "cvc": "123",
             "type": "vi"
             }
             };
             */

            var uid = "uid1234"; //Id del usuario logueado
            var email = "dev@paymentez.com"; //Email del usuario
            /* Add Card converts sensitive card data to a single-use token which you can safely pass to your server to charge the user.
             *
             * @param uid User identifier. This is the identifier you use inside your application; you will receive it in notifications.
             * @param email Email of the user initiating the purchase. Format: Valid e-mail format.
             * @param card the Card used to create this payment token
             * @param success_callback a callback to receive the token
             * @param failure_callback a callback to receive an error
             */
            Paymentez.addCard(uid, email, cardToSave, successHandler, errorHandler);
        }

        e.preventDefault();
    });

    var successHandler = function(cardResponse) {
        console.log(cardResponse.card);
        if(cardResponse.card.status === 'valid'){
            $('#messages_paymentez').html('Tarjeta agregada exitosamente<br>'+
                'status: ' + cardResponse.card.status + '<br>' +
                "Card Token: " + cardResponse.card.token + "<br>" +
                "transaction_reference: " + cardResponse.card.transaction_reference
            );
        }else if(cardResponse.card.status === 'review'){
            $('#messages_paymentez').html('Tarjeta bajo revisión<br>'+
                'status: ' + cardResponse.card.status + '<br>' +
                "Card Token: " + cardResponse.card.token + "<br>" +
                "transaction_reference: " + cardResponse.card.transaction_reference
            );
        }else{
            $('#messages_paymentez').html('Error<br>'+
                'status: ' + cardResponse.card.status + '<br>' +
                "message Token: " + cardResponse.card.message + "<br>"
            );
        }
        $("#loader").attr('hidden', true);
        submitButton.removeAttr("disabled");
        submitButton.text(submitInitialText);
    };
    var errorHandler = function(err) {
        console.log(err.error);
        let description = err.error.description;
        let help = err.error.help;
        let type =err.error.type;

        swal(
            ':( Lo sentimos ocurrio un error durante el pago',
            'Tipo: ' + type + '</br>Descripción: ' + description + '</br> Ayuda: '+ help,
            'error'
        );

        // $('#messages_paymentez').html(err.error.type);
        $("#loader").attr('hidden', true);
        submitButton.removeAttr("disabled");
        submitButton.text(submitInitialText);
    };
});