<div class="row justify-content-center">
    <div class="col-md-8 col-sm-12">
        <div class="card card-deck border-radius-10 box-shadow bg-light animated fadeIn"
             id="payment_card"
             hidden>
            <div class="card-body text-center">
                <h4 class="card-title weight-500 mb-20">Detalles de su tarjeta</h4>
                <h6 class="card-subtitle mb-2 text-muted mb-10">Mientras se realiza la transacción no
                    debe recargar el navegador ni regresar atrás. Por favor espere que termine la
                    operación</h6>
                {!! Form::open(['class'=>'form-horizontal', 'id'=>'add-card-form']) !!}
                <div class="paymentez-form" id="my-card" data-capture-name="true"
                     data-capture-email="true" data-capture-cellphone="true" data-icon-colour="#569B29">
                </div>
                <button class="btn btn-outline-success weight-500 btn-block"><i
                            class="fa fa-money"></i> Proceder al Pago
                </button>
                <div id="messages_paymentez"></div>
                {!! Form::close() !!}
            </div>
            <div id="loader" hidden>
                <i class="fa fa-spinner fa-pulse fa-5x fa-fw text-success"></i>
                <span class="sr-only">Cargando...</span></div>
        </div>
    </div>
</div>