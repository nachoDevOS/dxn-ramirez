@extends('voyager::master')

@section('page_title', 'Añadir Orden de Ingresos')

@section('page_header')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-bordered">
                    <div class="panel-body" style="padding: 0px">
                        <div class="col-md-8" style="padding: 0px">
                            <h1 class="page-title">
                                <i class="fa-solid fa-cart-plus"></i> Añadir Compras
                            </h1>
                        </div>
                        <div class="col-md-4 text-right" style="margin-top: 30px">
                            <a href="{{ route('incomes.index') }}" class="btn btn-warning">
                                <i class="voyager-plus"></i> <span>Volver</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

@section('content')
    <div class="page-content edit-add container-fluid">
        <form class="form-edit-add" action="{{ route('incomes.store') }}" method="post" enctype="multipart/form-data">
            @csrf
            <div class="row">
                <div class="col-md-8">
                    <div class="panel panel-bordered">
                        <div class="panel-heading"><h3 class="panel-title">Detalle de la Orden</h3></div>
                        <div class="panel-body">
                            <div class="col-md-12" style="height: 80px; max-height: 600px; overflow-y: auto; padding: 0px;">
                                <div class="form-group">
                                    <label for="product_id">Buscar producto</label> <br>
                                    <select class="form-control" id="select-product_id"></select>
                                </div>
                            </div>
                            <div class="col-md-12" style="height: 900px; max-height: 550px; overflow-y: auto; padding: 0px;">
                                <div class="table-responsive">
                                    <table id="dataTable" class="table table-bordered table-hover">
                                        <thead>
                                            <tr>
                                                <th style="width: 2%">N&deg;</th>
                                                <th >Detalles</th>
                                                <th style="text-align: center; width: 12%;">Lote <br>F. Expiración </th>
                                                <th style="text-align: center; width: 12%;">Cantidad</th>
                                                <th style="text-align: center; width: 14%;">Precios</th>
                                                <th style="text-align: center; width: 14%;">Subtotales</th>
                                            </tr>
                                        </thead>
                                        <tbody id="table-body">
                                            <tr id="tr-empty">
                                                <td colspan="6" style="height: 450px">
                                                    <h4 class="text-center text-muted" style="margin-top: 100px">
                                                        <i class="glyphicon glyphicon-shopping-cart" style="font-size: 50px"></i> <br><br>
                                                        Lista de orden vacía
                                                    </h4>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="panel panel-bordered">
                        <div class="panel-heading"><h3 class="panel-title">Datos generales</h3></div>
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="supplier_id">Proveedor</label>
                                        <select class="form-control select2" name="supplier_id" id="supplier_id" required>
                                            <option value="">Ninguno</option>
                                            @foreach ($suppliers as $item)
                                                <option value="{{$item->id}}">{{$item->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="date">Fecha</label>
                                        <input type="date" class="form-control" name="date" value="{{ date('Y-m-d') }}" required>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="fileInput">Archivo de respaldo</label>
                                        <input type="file" name="file" accept=".jpg,.jpeg,.png,.gif,.bmp,.webp,.pdf" />
                                        <small>Solo se aceptan archivos de imagen (JPG, PNG, etc.) o PDF</small>
                                    </div>
                                    <textarea name="observation" class="form-control" rows="2" placeholder="Observaciones"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="panel panel-bordered">
                        <div class="panel-heading"><h3 class="panel-title">Detalles de pago</h3></div>
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="typeIncome">Tipo</label>
                                        <select class="form-control select2" name="typeIncome" id="typeIncome" onchange="function_typeIncome()" required>
                                            <option value="" disabled selected>--Selecione una opción--</option>
                                            <option value="Compra al Contado">Compra al Contado</option>
                                            <option value="Compra al Credito">Compra al Credito</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="payment_type">Método de pago</label>
                                        <select name="payment_type" id="select-payment_type" class="form-control select2" required>
                                            <option value="" disabled selected>--Seleccione una opción--</option>
                                            <option value="Efectivo">Efectivo</option>
                                            <option value="Qr">Qr/Transferencia</option>
                                            <option value="Efectivo y Qr">Efectivo y Qr</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="amount_cash">Monto en Efectivo</label>
                                        <input type="number" name="amount_cash" id="amount_cash" class="form-control" value="0" step="0.01" style="text-align: right" placeholder="0.00">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="amount_qr">Monto en Qr</label>
                                        <input type="number" name="amount_qr" id="amount_qr" class="form-control" value="0" step="0.01" style="text-align: right" placeholder="0.00">
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <div class="form-group">
                                <input type="hidden" name="amountPayment" id="amountPayment" value="0">
                                <h3 class="text-right" id="change-message-error" style="display: none; color: red !important"><small>Monto insuficiente</small></h3>
                                <h3 class="text-right" id="change-message-error-credito" style="display: none; color: red !important"><small>El monto es mayor al total</small></h3>
                                <h3 class="text-right" id="change-message" style="color: #28a745;"><small>Cambio:</small> <b id="change-amount">0.00</b></h3>
                                <h3 class="text-right" id="change-message-credito" style="color: #dd4b39;"><small>Deuda:</small> <b id="change-amount-credito">0.00</b></h3>
                                <h2 class="text-right"><small>Total a pagar:</small> <b id="label-total">0.00</b></h2>
                                <input type="hidden" id="amountTotalSale" name="amountTotalSale" value="0">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-12" style="margin-top: -20px;">
                <div class="panel-footer">
                    <label class="checkbox-inline"><input type="checkbox" required>Confirmar</label>
                    <button type="submit" id="btn-submit" class="btn btn-primary btn-block btn-submit">Registrar <i class="voyager-basket"></i></button>
                    <a href="{{ route('incomes.index') }}" class="btn btn-default btn-block" >Volver a la lista</a>
                </div>
            </div>
        </form>
    </div>
@stop

@section('css')
    <style>
        .form-group{
            margin-bottom: 10px !important;
        }
        /* Estilos para los input-group en el carrito */
        .input-group-item {
            margin-bottom: 5px; /* Pequeño margen entre los grupos de inputs */
        }
        .input-group-item:last-child {
            margin-bottom: 0; /* Eliminar margen del último elemento en un grupo */
        }
        .input-group-addon {
            min-width: 70px; /* Ancho mínimo para alinear los addons */
            text-align: right;
            font-weight: bold; /* Texto del addon en negrita */
        }
        .panel-footer {
            background-color: #f5f5f5;
            padding: 15px;
        }
    </style>
@endsection

@section('javascript')
    <script src="{{ asset('vendor/tippy/popper.min.js') }}"></script>
    <script src="{{ asset('vendor/tippy/tippy-bundle.umd.min.js') }}"></script>
    <script src="{{ asset('js/btn-submit.js') }}"></script>

    <script>
        (function(){
            let productSelected, totalAmount = 0;

            $(document).ready(function(){
                $('#change-message-credito').hide();
                $('#change-message').hide();
            
                $('<style>.select2-results__options { max-height: 350px !important; }</style>').appendTo('head');

                $('#select-product_id').select2({
                    placeholder: '<i class="fa fa-search"></i> Buscar...',
                    escapeMarkup : function(markup) {
                        return markup;
                    },
                    language: {
                        inputTooShort: function (data) {
                            return `Por favor ingrese ${data.minimum - data.input.length} o más caracteres`;
                        },
                        noResults: function () {
                            return `<i class="far fa-frown"></i> No hay resultados encontrados`;
                        }
                    },
                    quietMillis: 250,
                    minimumInputLength: 2,
                    ajax: {
                        url: "{{ url('admin/incomes/item/ajax') }}",      
                        processResults: function (data) {
                            let results = [];
                            data.map(data =>{
                                results.push({
                                    ...data,
                                    disabled: false
                                });
                            });
                            return {
                                results
                            };
                        },
                        cache: true
                    },
                    templateResult: formatResultProducts,
                    templateSelection: (opt) => {
                        productSelected = opt;
                        return opt.name;
                    }
                }).change(function(){

                    if($('#select-product_id option:selected').val() && productSelected){
                        let product = productSelected;
                        let image = "{{ asset('images/default.jpg') }}";
                        if(product.image){
                            image = "{{ asset('storage') }}/"+product.image.replace('.avif','-cropped.webp')
                        }

                        if($('.table').find(`#tr-item-${product.id}`).val() === undefined){
                            $('#table-body').append(`
                                <tr class="tr-item" id="tr-item-${product.id}">
                                    <td class="td-item"></td>
                                    <td>
                                        <input type="hidden" name="products[${product.id}][id]" value="${product.id}"/>
                                        <input type="hidden" id="fraction-quantity-${product.id}" value="${product.fractionQuantity || 1}"/>
                                        <div style="display: flex; align-items: center;">
                                            <div style="flex-grow: 1; line-height: 1.5;">
                                                <div style="font-size: 15px; font-weight: bold; color: #000; margin-bottom: 8px;">
                                                    <i class="fa-solid fa-pills" style="color: #22A7F0;"></i> ${product.nameGeneric} ${product.nameTrade ? `<span style="color: #444; font-weight: normal;">| ${product.nameTrade}</span>` : ''}
                                                </div>
                                                <div style="font-size: 12px; color: #555;">
                                                    ${product.observation ? `<div style="font-size: 14px; margin-top: 5px;"><i class="fa-solid fa-clipboard-list" style="color: #f39c12; width: 14px; text-align: center;"></i> <strong style="color: #222;">Detalle:</strong> <span style="font-weight: bold; color: #222;">${product.observation}</span></div>` : ''}
                                                    <div style="margin-top: 5px;"><i class="fa-solid fa-tags" style="color: #2ecc71; width: 14px; text-align: center;"></i> <strong style="color: #444;">Categoría:</strong> ${product.category.name} | ${product.presentation.name}</div>
                                                    <div><i class="fa-solid fa-flask" style="color: #3498db; width: 14px; text-align: center;"></i> <strong style="color: #444;">Laboratorio:</strong> ${product.laboratory ? product.laboratory.name : 'SN'}</div>
                                                    <div><i class="fa-solid fa-copyright" style="color: #9b59b6; width: 14px; text-align: center;"></i> <strong style="color: #444;">Marca:</strong> ${product.brand ? product.brand.name : 'SN'}</div>
                                                    ${product.fraction ? `<div><i class="fa-solid fa-vial" style="color: #e74c3c; width: 14px; text-align: center;"></i> <strong style="color: #444;">Fraccionable:</strong> ${product.fractionQuantity} ${product.fraction_presentation ? product.fraction_presentation.name : ''}</div>` : ''}
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td style="vertical-align: middle; padding: 5px;">
                                        <div class="input-group input-group-item">                                       
                                            <input type="text" name="products[${product.id}][lote]" class="form-control" placeholder="Opcional"/>
                                        </div>
                                        <div class="input-group input-group-item">                                       
                                        <input type="date" name="products[${product.id}][expirationDate]" class="form-control check-expiration-date" min="{{ date('Y-m-d', strtotime('+1 day')) }}"/>
                                        </div>
                                    </td>
                                    <td style="vertical-align: middle; padding: 5px;">
                                        <div class="input-group input-group-item">
                                            <input type="number" name="products[${product.id}][quantity]" step="1" min="1" style="text-align: right" class="form-control" id="input-quantity-${product.id}" value="1" onkeyup="getSubtotal(${product.id})" onchange="getSubtotal(${product.id})" required/>
                                        </div>
                                    </td>
                                    <td style="vertical-align: middle; padding: 5px;">
                                        <div style="margin-bottom: 5px;">
                                            <small>Compra:</small>
                                            <input type="number" name="products[${product.id}][pricePurchase]" step="0.01" min="0.1" style="text-align: right" class="form-control" id="input-pricePurchase-${product.id}" value="${product.pricePurchase || 0}" onkeyup="getSubtotal(${product.id})" onchange="validatePrices(${product.id}); getSubtotal(${product.id})" required/>
                                        </div>
                                        <div>
                                            <small>Venta por ${product.presentation.name}</small>
                                            <input type="number" name="products[${product.id}][priceSale]" step="0.01" min="0.1" style="text-align: right" class="form-control" id="input-priceSale-${product.id}" value="${product.priceSale || 0}" onkeyup="getSubtotal(${product.id})" onchange="validatePrices(${product.id}); getSubtotal(${product.id})" required/>
                                        </div>
                                        
                                        ${product.fraction ? `
                                            <div>
                                                <small>Venta por ${product.fraction_presentation.name}</small>
                                                <input type="number" name="products[${product.id}][priceSaleFraction]" step="0.01" min="0.1" style="text-align: right" class="form-control" id="input-priceSaleFraction-${product.id}" value="${product.priceSale || 0}" onkeyup="getSubtotal(${product.id})" onchange="validatePrices(${product.id}); getSubtotal(${product.id})" required/>
                                            </div>
                                        ` : ''}

                                    </td>
                                    <td class="text-right" style="vertical-align: middle; padding: 5px;">
                                        <input type="hidden" name="products[${product.id}][amountPurchase]" id="subTotalPurchase-${product.id}" />
                                        <input type="hidden" name="products[${product.id}][amountSale]" id="subTotalSale-${product.id}" />
                                        <div style="text-align: right;">
                                            <div style="margin-bottom: 5px;">
                                                <small>Compra:</small><br>
                                                <b class="label-subtotal" id="label-subtotal-purchase-${product.id}" style="font-size: 1.2em;">0.00</b>
                                            </div>
                                            <div>
                                                <small>Venta ${product.presentation.name}:</small><br>
                                                <b id="label-subtotal-sale-${product.id}" style="font-size: 1.2em;">0.00</b>
                                            </div>
                                            ${product.fraction ? `
                                                <input type="hidden" name="products[${product.id}][amountSaleFraction]" id="subTotalSaleFraction-${product.id}" />
                                                <div>
                                                    <small>Venta ${product.fraction_presentation.name}:</small><br>
                                                    <b id="label-subtotal-sale-fraction-${product.id}" style="font-size: 1.2em;">0.00</b>
                                                </div>
                                            ` : ''}
                                            <button type="button" onclick="removeTr(${product.id})" class="btn btn-link" style="padding: 5px 0px 0px 0px; margin-top: 5px;"><i class="voyager-trash text-danger" style="font-size: 1.2em;"></i></button>
                                        </div>
                                    </td>
                                </tr>
                            `);
                            setNumber();
                            getSubtotal(product.id);
                            toastr.success(`+1 ${product.name}`, 'Producto agregado');
                        }else{
                            toastr.info('EL producto ya está agregado', 'Información')
                        }

                        $('#select-product_id').val('').trigger('change');
                    }
                });

                // Eventos que disparan la actualización de la lógica de pago
                $('#typeIncome, #select-payment_type').on('change', updatePaymentLogic);
                $('#amount_cash, #amount_qr').on('keyup change', handleAmountInputs);

                // Validación de fecha de expiración para elementos dinámicos
                $(document).on('change', '.check-expiration-date', function(){
                    let date = $(this).val();
                    if(date){
                        let today = new Date();
                        today.setHours(0,0,0,0);
                        let parts = date.split('-');
                        let selectedDate = new Date(parts[0], parts[1]-1, parts[2]);
                        
                        if(selectedDate <= today){
                            toastr.warning('La fecha de expiración debe ser mayor a la fecha actual.', 'Advertencia');
                            $(this).val('');
                        }
                    }
                });

                // Inicializar la lógica de pago al cargar la página
                updatePaymentLogic();
            });

            // Función para sumar decimales correctamente
            function preciseSum(a, b) {
                return Math.round((a + b) * 100) / 100;
            }

            // Función auxiliar para formatear decimales correctamente
            function formatDecimal(num) {
                return Math.round(num * 100) / 100;
            }

            window.validatePrices = function(id){
                let pricePurchase = parseFloat($(`#input-pricePurchase-${id}`).val()) || 0;
                let priceSale = parseFloat($(`#input-priceSale-${id}`).val()) || 0;
                let priceSaleFraction = parseFloat($(`#input-priceSaleFraction-${id}`).val()) || 0;
                let fractionQuantity = parseFloat($(`#fraction-quantity-${id}`).val()) || 1;

                // Validación: Precio Venta >= Precio Compra
                if(pricePurchase > 0 && priceSale > 0){
                    if(priceSale < pricePurchase){
                        toastr.error('El precio de venta genera pérdidas.', 'Error');
                        $(`#input-priceSale-${id}`).val('');
                    }
                }

                // Validación: Precio Fracción * Cantidad >= Precio Compra
                if(pricePurchase > 0 && priceSaleFraction > 0){
                    if((priceSaleFraction * fractionQuantity) < pricePurchase){
                        toastr.error('El precio fraccionado genera pérdidas.', 'Error');
                        $(`#input-priceSaleFraction-${id}`).val('');
                    }
                }
            }

            window.getSubtotal = function(id){
                let quantity = $(`#input-quantity-${id}`).val() ? parseFloat($(`#input-quantity-${id}`).val()) : 0;
                let pricePurchase = $(`#input-pricePurchase-${id}`).val() ? parseFloat($(`#input-pricePurchase-${id}`).val()) : 0;
                let priceSale = $(`#input-priceSale-${id}`).val() ? parseFloat($(`#input-priceSale-${id}`).val()) : 0;
                let priceSaleFraction = $(`#input-priceSaleFraction-${id}`).val() ? parseFloat($(`#input-priceSaleFraction-${id}`).val()) : 0;
                let fractionQuantity = $(`#fraction-quantity-${id}`).val() ? parseFloat($(`#fraction-quantity-${id}`).val()) : 1;

                let subtotalPurchase = formatDecimal(pricePurchase * quantity);
                let subtotalSale = formatDecimal(priceSale * quantity);

                // Actualizar subtotal de compra
                $(`#label-subtotal-purchase-${id}`).text(subtotalPurchase.toFixed(2));
                $(`#subTotalPurchase-${id}`).val(subtotalPurchase.toFixed(2));

                // Actualizar subtotal de venta normal
                $(`#label-subtotal-sale-${id}`).text(subtotalSale.toFixed(2));
                $(`#subTotalSale-${id}`).val(subtotalSale.toFixed(2));

                if ($(`#input-priceSaleFraction-${id}`).length) {
                    let subtotalSaleFraction = formatDecimal(priceSaleFraction * fractionQuantity * quantity);
                    $(`#label-subtotal-sale-fraction-${id}`).text(subtotalSaleFraction.toFixed(2));
                    $(`#subTotalSaleFraction-${id}`).val(subtotalSaleFraction.toFixed(2));
                }

                getTotal();
            }

            window.function_typeIncome = function() {
                updatePaymentLogic();
            }

            window.getTotal = function(){
                totalAmount = 0;
                $(".label-subtotal").each(function() {
                    totalAmount += parseFloat($(this).text()) || 0;
                });
                totalAmount = formatDecimal(totalAmount);
                $('#label-total').text(totalAmount.toFixed(2));
                $('#amountTotalSale').val(totalAmount.toFixed(2));
                updatePaymentLogic();
            }

            function updatePaymentLogic() {
                let typeIncome = $('#typeIncome').val();
                let paymentType = $('#select-payment_type').val();

                // Resetear y deshabilitar inputs
                $('#amount_cash').prop('readonly', true).val(0);
                $('#amount_qr').prop('readonly', true).val(0);
                $('#change-message-credito, #change-message, #change-message-error, #change-message-error-credito').hide();

                if (typeIncome === 'Compra al Contado') {
                    $('#change-message').show();
                    if (paymentType === 'Efectivo') {
                        $('#amount_cash').prop('readonly', false);
                        $('#amount_cash').val(totalAmount.toFixed(2));
                    } else if (paymentType === 'Qr') {
                        $('#amount_qr').val(totalAmount.toFixed(2)).prop('readonly', true);
                    } else if (paymentType === 'Efectivo y Qr') {
                        $('#amount_cash').prop('readonly', false);
                        $('#amount_qr').prop('readonly', false);
                        $('#amount_cash').val(0);
                        $('#amount_qr').val(0);
                    }
                } else if (typeIncome === 'Compra al Credito') {
                    $('#change-message-credito').show();
                    if (paymentType === 'Efectivo' || paymentType === 'Efectivo y Qr') {
                        $('#amount_cash').prop('readonly', false);
                    }
                    if (paymentType === 'Qr' || paymentType === 'Efectivo y Qr') {
                        $('#amount_qr').prop('readonly', false);
                    }
                    $('#amount_cash').val(0);
                    $('#amount_qr').val(0);
                }

                handleAmountInputs();
            }

            function handleAmountInputs() {
                let typeIncome = $('#typeIncome').val();
                let paymentType = $('#select-payment_type').val();
                
                // Usar parseFloat con manejo de decimales
                let cash = parseFloat($('#amount_cash').val()) || 0;
                let qr = parseFloat($('#amount_qr').val()) || 0;
                
                let totalPaid = preciseSum(cash, qr);

                if (typeIncome === 'Compra al Contado') {
                    if (paymentType === 'Efectivo y Qr' && totalAmount > 0) {
                        // La suma no debe exceder el total
                        if (totalPaid > totalAmount) {
                            // Si el último input modificado fue el de efectivo
                            if ($(document.activeElement).is('#amount_cash')) {
                                let newCash = formatDecimal(totalAmount - qr);
                                if (newCash < 0) newCash = 0;
                                $('#amount_cash').val(newCash.toFixed(2));
                            } else {
                                let newQr = formatDecimal(totalAmount - cash);
                                if (newQr < 0) newQr = 0;
                                $('#amount_qr').val(newQr.toFixed(2));
                            }
                            totalPaid = totalAmount;
                        }
                    }
                } else if (typeIncome === 'Compra al Credito') {
                    // La suma no debe exceder el total
                    if (totalPaid > totalAmount) {
                        if (paymentType === 'Efectivo y Qr' && totalAmount > 0) {
                            if ($(document.activeElement).is('#amount_cash')) {
                                let newCash = formatDecimal(totalAmount - qr);
                                $('#amount_cash').val((newCash < 0 ? 0 : newCash).toFixed(2));
                            } else {
                                let newQr = formatDecimal(totalAmount - cash);
                                $('#amount_qr').val((newQr < 0 ? 0 : newQr).toFixed(2));
                            }
                            totalPaid = totalAmount;
                        }
                    }
                }

                // Usar una función segura para establecer el valor
                $('#amountPayment').val(formatDecimal(totalPaid).toFixed(2));
                calculateChange();
            }

            window.calculateChange = function() {
                let typeIncome = $('#typeIncome').val();
                let totalPaid = parseFloat($('#amountPayment').val()) || 0;

                $('#change-message-error, #change-message-error-credito').hide();

                // Usar comparación con tolerancia para errores de punto flotante
                const tolerance = 0.01; // Tolerancia de 1 centavo
                
                if (Math.abs(totalPaid - totalAmount) < tolerance || totalPaid >= totalAmount) {
                    // Si se paga el total o más (con tolerancia)
                    let change = formatDecimal(totalPaid - totalAmount);
                    $('#change-amount').text(change.toFixed(2));
                    $('#change-message').show();
                    $('#change-message-credito, #change-message-error, #change-message-error-credito').hide();
                } else if (typeIncome === 'Compra al Contado') {
                    // Si es al contado y el pago es menor, muestra error
                    $('#change-message-error').show();
                    $('#change-amount').text('0.00');
                    $('#change-message, #change-message-credito, #change-message-error-credito').hide();
                } else if (typeIncome === 'Compra al Credito') {
                    // Si es a crédito y el pago es menor, muestra la deuda
                    let debt = formatDecimal(totalAmount - totalPaid);
                    $('#change-amount-credito').text(debt.toFixed(2));
                    $('#change-message-credito').show();
                    $('#change-message, #change-message-error, #change-message-error-credito').hide();
                } else {
                    // Si no hay total o no es un tipo válido, oculta todo
                    $('#change-amount').text('0.00');
                    $('#change-amount-credito').text('0.00');
                    $('#change-message, #change-message-credito').hide();
                }
            }

            window.setNumber = function(){
                var length = 0;
                $(".td-item").each(function(index) {
                    $(this).text(index +1);
                    length++;
                });

                if(length > 0){
                    $('#tr-empty').css('display', 'none');
                }else{
                    $('#tr-empty').fadeIn('fast');
                }
            }

            window.removeTr = function(id){
                $(`#tr-item-${id}`).remove();
                $('#select-product_id').val("").trigger("change");
                setNumber();
                getTotal();
                toastr.info('Producto eliminado del carrito', 'Eliminado');
            }

            // Make it global for select2
            window.formatResultProducts = function(option){
                // Si está cargando mostrar texto de carga
                if (option.loading) {
                    return '<span class="text-center"><i class="fas fa-spinner fa-spin"></i> Buscando...</span>';
                }
                let image = window.defaultImage;
                
                if (option.image) {
                    // Remove the extension and add the cropped suffix with webp extension
                    const lastDotIndex = option.image.lastIndexOf('.');
                    const baseName = lastDotIndex !== -1 ? option.image.substring(0, lastDotIndex) : option.image;
                    image = `${window.storagePath}${baseName}-cropped.webp`;
                }

                // Mostrar las opciones encontradas con diseño mejorado
                return $(`<div style="display: flex; align-items: center; padding: 10px 5px;">
                                <div style="flex-shrink: 0; margin-right: 15px;">
                                    <img src="${image}" style="width: 60px; height: 60px; border-radius: 8px; object-fit: cover; box-shadow: 0 2px 4px rgba(0,0,0,0.1);" />
                                </div>
                                <div style="flex-grow: 1; line-height: 1.5;">
                                    <div style="font-size: 16px; font-weight: bold; color: #000; margin-bottom: 8px;">
                                        <i class="fa-solid fa-pills" style="color: #22A7F0;"></i> ${option.nameGeneric} ${option.nameTrade ? `<span style="color: #444; font-weight: normal;">| ${option.nameTrade}</span>` : ''}
                                    </div>
                                    <div style="font-size: 12px; color: #555;">
                                        ${option.observation ? `<div style="font-size: 14px; margin-top: 5px;"><i class="fa-solid fa-clipboard-list" style="color: #f39c12; width: 14px; text-align: center;"></i> <strong style="color: #222;">Detalle:</strong> <span style="font-weight: bold; color: #222;">${option.observation}</span></div>` : ''}
                                        <div style="margin-top: 5px;"><i class="fa-solid fa-tags" style="color: #2ecc71; width: 14px; text-align: center;"></i> <strong style="color: #444;">Categoría:</strong> ${option.category.name} | ${option.presentation.name}</div>
                                        <div><i class="fa-solid fa-flask" style="color: #3498db; width: 14px; text-align: center;"></i> <strong style="color: #444;">Laboratorio:</strong> ${option.laboratory ? option.laboratory.name : 'SN'}</div>
                                        <div><i class="fa-solid fa-copyright" style="color: #9b59b6; width: 14px; text-align: center;"></i> <strong style="color: #444;">Marca:</strong> ${option.brand ? option.brand.name : 'SN'}</div>
                                        ${option.fraction ? `<div><i class="fa-solid fa-vial" style="color: #e74c3c; width: 14px; text-align: center;"></i> <strong style="color: #444;">Fraccionable:</strong> ${option.fractionQuantity} ${option.fraction_presentation ? option.fraction_presentation.name : ''}</div>` : ''}
                                    </div>
                                </div>
                            </div>`);
            }

            // Definir variables globales para select2
            window.storagePath = "{{ asset('storage') }}/";
            window.defaultImage = "{{ asset('images/default.jpg') }}";
        })();
    </script>
@stop