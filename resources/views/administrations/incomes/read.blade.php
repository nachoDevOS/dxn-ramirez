@extends('voyager::master')

@section('page_title', 'Viendo Orden de Ingreso')

@section('page_header')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-8">
                <h1 class="page-title">
                    <i class="voyager-receipt"></i> Viendo Orden de Ingreso
                </h1>
            </div>
            <div class="col-md-4 text-right">
                <a href="{{ route('incomes.index') }}" class="btn btn-warning"><i class="voyager-list"></i> Volver a la
                    lista</a>
                <button class="btn btn-primary btn-transfer" data-target="#modal-register-transfer" data-toggle="modal"><i
                        class="voyager-forward"></i> Transferir</button>
            </div>
        </div>
    </div>
@stop

@section('content')
    <div class="page-content read container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-sm-9">
                                <div class="row">
                                    <div class="col-md-4 info-item">
                                        <small>Proveedor</small>
                                        <p>{{ $income->supplier ? $income->supplier->name : 'Sin Proveedor' }}</p>
                                    </div>
                                    <div class="col-md-3 info-item">
                                        <small>NIT</small>
                                        <p>{{ $income->supplier ? $income->supplier->nit : 'Sin NIT' }}</p>
                                    </div>
                                    <div class="col-md-3 info-item">
                                        <small>Fecha de Ingreso</small>
                                        <p>{{ date('d/m/Y', strtotime($income->date)) }}</p>
                                    </div>
                                    <div class="col-md-2 info-item">
                                        <small>Tipo</small>
                                        <p>{{ $income->typeIncome }}</p>
                                    </div>
                                    <div class="col-md-12 info-item">
                                        <small>Observación</small>
                                        <p>{{ $income->observation ?? 'Ninguna' }}</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-3 text-right">
                                <small>Estado</small>
                                <p><span
                                        class="badge badge-{{ $income->status == 'Pendiente' ? 'danger' : 'success' }}">{{ $income->status }}</span>
                                </p>
                                <small>Atendido por</small>
                                <p>{{ $income->register->name }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title">Detalles de Adquisición</h5>
                        @if ($income->file)
                            <a href="{{ route('incomes.file.download', ['id' => $income->id]) }}" download target="_blank"
                                class="btn btn-sm btn-info">
                                <i class="voyager-download"></i> Descargar Archivo
                            </a>
                        @endif
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="dataTable" class="table table-bordered table-hover zebra-striped">
                                <thead>
                                    <tr>
                                        <th style="width:5%">N&deg;</th>
                                        <th>Descripción</th>
                                        <th style="text-align: center; width:12%">Lote <br> F. Vencimiento</th>
                                        <th style="text-align: center; width:10%">Stock</th>
                                        <th style="text-align: center; width:10%">Cant.</th>
                                        <th style="text-align: center; width:10%">P/Compra</th>
                                        <th style="text-align: center; width:12%">P/Venta</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $i = 1;
                                        $totalPurchase = 0;
                                    @endphp
                                    @forelse ($income->incomeDetails as $item)
                                        <tr>
                                            <td>{{ $i }}</td>

                                            <td>
                                                <strong style="font-size: 12px">{{ $item->item->nameGeneric }}
                                                    {{ $item->item->nameTrade ? ' | ' . $item->item->nameTrade : '' }}</strong>
                                                <br>
                                                <div style="font-size: 10px; color: #555; margin-top: 5px;">
                                                    <span>CATEGORÍA:</span>
                                                    {{ $item->item->category ? strtoupper($item->item->category->name) : 'SN' }} <br>
                                                    <span>PRESENTACIÓN:</span>
                                                    {{ $item->item->presentation ? strtoupper($item->item->presentation->name) : 'SN' }}
                                                    <br>
                                                    <span>LABORATORIO:</span>
                                                    {{ $item->item->laboratory ? strtoupper($item->item->laboratory->name) : 'SN' }}
                                                    <br>
                                                    <span>MARCA:</span>
                                                    {{ $item->item->brand ? strtoupper($item->item->brand->name) : 'SN' }}
                                                    @if ($item->dispensed == 'Fraccionado')
                                                        <br>
                                                        <span class="label label-success" style="margin-top: 5px;">Fraccionado</span>
                                                    @endif
                                                </div>
                                            </td>

                                            <td style="text-align: center">
                                                {{ $item->lote ? $item->lote : 'SN' }}
                                                <br>
                                                @if ($item->expirationDate)
                                                    @php
                                                        $expiration = \Carbon\Carbon::parse($item->expirationDate)->startOfDay();
                                                        $today = \Carbon\Carbon::now()->startOfDay();
                                                        $diffDays = $today->diffInDays($expiration, false);
                                                        $settingExpiration = setting('items-productos.notificateExpiration');
                                                        $daysExpiration = is_numeric($settingExpiration) ? (int)$settingExpiration : 15;
                                                    @endphp
                                                    @if ($diffDays < 0)
                                                        <span style="color: #e74c3c; font-weight: bold" data-toggle="tooltip" title="Vencido">
                                                            {{ date('d/m/Y', strtotime($item->expirationDate))}}
                                                        </span>
                                                    @elseif ($diffDays <= $daysExpiration)
                                                        <span style="color: #e67e22; font-weight: bold" data-toggle="tooltip" title="Por vencer">
                                                            {{ date('d/m/Y', strtotime($item->expirationDate))}}
                                                        </span>
                                                    @else
                                                        {{ date('d/m/Y', strtotime($item->expirationDate))}}
                                                    @endif
                                                @else
                                                    <label class="label label-warning">Sin Fecha de Expiración</label>
                                                @endif
                                            </td>
                                            <td style="text-align: right">
                                                {{ number_format($item->stock, 2, ',', '.') }}
                                            </td>
                                            <td style="text-align: right">
                                                {{ number_format($item->quantity, 2, ',', '.') }}
                                            </td>
                                            <td style="text-align: right">
                                                {{ number_format($item->pricePurchase, 2, ',', '.') }}
                                            </td>
                                            <td style="text-align: right">
                                                {{ number_format($item->priceSale, 2, ',', '.') }}
                                                @if ($item->dispensed == 'Fraccionado')
                                                    <br>
                                                    <small class="text-success" title="Precio Fraccionado">
                                                        (Frac: {{ number_format($item->dispensedPrice, 2, ',', '.') }})
                                                    </small>
                                                @endif
                                            </td>
                                        </tr>
                                        @php
                                            $i++;
                                            $totalPurchase += $item->amountPurchase;
                                        @endphp
                                    @empty
                                        <tr>
                                            <td colspan="7" class="text-center">
                                                <img src="{{ asset('images/empty.png') }}" width="120px" alt="vacio"
                                                    style="opacity: 0.8; margin-top: 20px">
                                                <p style="margin: 20px 0px">No hay detalles de adquisición</p>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                    @php
                        $totalPayment = $income->incomeTransactions->sum('amount');
                    @endphp
                    <div class="card-footer">
                        <div class="row">
                            <div class="col-md-6">
                                <form id="form-file" action="{{ route('incomes-file.store', ['id' => $income->id]) }}"
                                    method="post" enctype="multipart/form-data" class="form-upload">
                                    @csrf
                                    <div class="input-group">
                                        <input type="file" name="file" accept=".jpg,.jpeg,.png,.gif,.bmp,.webp,.pdf"
                                            class="form-control" />
                                        <span class="input-group-btn">
                                            <button type="submit" id="btn-submit" class="btn btn-primary"
                                                data-toggle="modal" data-target="#modal-confirm">
                                                <i class="voyager-upload"></i> Subir
                                            </button>
                                        </span>
                                    </div>
                                    <small>Subir archivo de respaldo (imagen o PDF).</small>
                                </form>
                            </div>
                            <div class="col-md-6 summary-container">
                                <div>
                                    <div class="summary-item"><span>Total a Pagar:</span> <span>Bs.
                                            {{ number_format($totalPurchase, 2, ',', '.') }}</span></div>
                                    <div class="summary-item"><span>Total Pagado:</span> <span>Bs.
                                            {{ number_format($totalPayment, 2, ',', '.') }}</span></div>
                                    <div class="summary-item total"><span>Deuda Total:</span> <span>Bs.
                                            {{ number_format($totalPurchase - $totalPayment, 2, ',', '.') }}</span></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-7">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title">Historial de Pagos</h5>
                        @if (auth()->user()->hasPermission('read_incomes') && $income->status == 'Pendiente')
                            <button class="btn btn-sm btn-success" data-target="#modal-register-stock" data-toggle="modal">
                                <i class="voyager-dollar"></i> Amortizar
                            </button>
                        @endif
                    </div>
                    <div class="card-body">
                        <div class="row">
                        </div>
                        <div class="row" id="div-results" style="min-height: 120px">
                            <div class="form-group col-md-12">
                                <div class="table-responsive">
                                    <table id="dataTable" class="table table-bordered table-hover">
                                        <thead>
                                            <tr>
                                                <th>N&deg;</th>
                                                <th style="text-align: center; width:50%">Registrado por.</th>
                                                <th style="text-align: center; width:10%">Tipo</th>
                                                <th style="text-align: center; width:15%">Monto</th>

                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php
                                                $i = 1;
                                                $total = 0;
                                            @endphp
                                            @forelse ($income->incomeTransactions as $item)
                                                <tr>
                                                    <td>{{ $i }}</td>
                                                    <td style="text-align: center">
                                                        {{ $item->register->name }} <br>
                                                        {{ date('d/m/Y', strtotime($item->created_at)) }}<br>
                                                        {{ date('h:i:s a', strtotime($item->created_at)) }}<br>
                                                        <small>{{ \Carbon\Carbon::parse($item->created_at)->diffForHumans() }}

                                                    </td>
                                                    <td style="text-align: center">
                                                        {{ $item->paymentType }}
                                                    </td>
                                                    <td style="text-align: right">
                                                        {{ number_format($item->amount, 2, ',', '.') }}
                                                    </td>
                                                </tr>
                                                @php
                                                    $i++;
                                                    $total += $item->amount;
                                                @endphp
                                            @empty
                                                <tr>
                                                    <td colspan="4">
                                                        <p class="text-center" style="margin: 20px 0px">No hay pagos
                                                            registrados.</p>
                                                    </td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <td colspan="3" style="text-align: right">TOTAL</td>
                                                <td style="text-align: right">{{ number_format($total, 2, ',', '.') }}
                                                </td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title">Historial de Transferencias</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                        </div>
                        <div class="row" id="div-results" style="min-height: 120px">
                            <div class="form-group col-md-12">
                                <div class="table-responsive">
                                    <table id="dataTable" class="table table-bordered table-hover">
                                        <thead>
                                            <tr>
                                                <th>N&deg;</th>
                                                <th style="text-align: center;">Items</th>
                                                <th style="text-align: center; width: 5%;">Lote</th>
                                                <th style="text-align: center; width:7%">Cant. Tansferida</th>
                                                <th style="text-align: center; width:7%">Stock <br>Disponible</th>
                                                <th style="text-align: center; width:17%">Registrado por</th>
                                                <th style="text-align: center; width:5%">Acciones</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php
                                                $i = 1;
                                                $total = 0;
                                            @endphp
                                            @forelse ($history as $item)
                                                <tr>
                                                    <td>{{ $i }}</td>
                                                    <td>
                                                        <strong style="font-size: 12px">{{ $item->item->nameGeneric }}
                                                            {{ $item->item->nameTrade ? ' | ' . $item->item->nameTrade : '' }}</strong>
                                                        <br>
                                                        <div style="font-size: 10px; color: #555; margin-top: 5px;">
                                                            <span>CATEGORÍA:</span>
                                                            {{ $item->item->category ? strtoupper($item->item->category->name) : 'SN' }}
                                                            <br>
                                                            <span>PRESENTACIÓN:</span>
                                                            {{ $item->item->presentation ? strtoupper($item->item->presentation->name) : 'SN' }}
                                                            <br>
                                                            <span>LABORATORIO:</span>
                                                            {{ $item->item->laboratory ? strtoupper($item->item->laboratory->name) : 'SN' }}
                                                            <br>
                                                            <span>MARCA:</span>
                                                            {{ $item->item->brand ? strtoupper($item->item->brand->name) : 'SN' }}
                                                        </div>
                                                    </td>
                                                    <td>{{ $item->lote }}</td>

                                                    <td style="text-align: right">
                                                        {{ number_format($item->quantity, 2, ',', '.') }}
                                                    </td>
                                                    <td style="text-align: right">
                                                        @if ($item->stock > 0)
                                                            {{ number_format($item->stock, 2, ',', '.') }}
                                                        @else
                                                            <label class="label label-danger">Agotado</label>
                                                        @endif
                                                    </td>

                                                    <td style="text-align: center">
                                                        {{ date('d/m/Y h:i:s a', strtotime($item->created_at)) }}<br>
                                                        <small>{{ \Carbon\Carbon::parse($item->created_at)->diffForHumans() }}
                                                            <br>
                                                            {{ $item->register->name }}
                                                    </td>
                                                    <td style="text-align: center">
                                                        @if ($item->quantity == $item->stock)
                                                            <a href="#"
                                                                onclick="deleteItem('{{ route('incomes-incomeDetail-transfer.destroy', ['id' => $income->id, 'transfer' => $item->id]) }}')"
                                                                title="Eliminar" data-toggle="modal"
                                                                data-target="#modal-delete"
                                                                class="btn btn-sm btn-danger delete">
                                                                <i class="voyager-trash"></i>
                                                            </a>
                                                        @endif
                                                    </td>


                                                </tr>
                                                @php
                                                    $i++;
                                                    $total += $item->amount;
                                                @endphp
                                            @empty
                                                <tr>
                                                    <td colspan="7">
                                                        <p class="text-center" style="margin: 20px 0px">
                                                            No se han realizado transferencias desde este ingreso.
                                                        </p>
                                                    </td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>



    <form action="{{ route('incomes-payment.store', ['id' => $income->id]) }}" class="form-submit" method="POST">
        <div class="modal fade" data-backdrop="static" id="modal-register-stock" role="dialog">
            <div class="modal-dialog modal-success">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar"><span
                                aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" style="color: #ffffff !important"><i class="voyager-plus"></i> Registrar
                            Pagos</h4>
                    </div>
                    <div class="modal-body">
                        @csrf
                        <div class="row">
                            <div class="form-group col-md-12">
                                <label for="payment_type">Método de pago</label>
                                <select name="payment_type" id="select-payment_type" class="form-control" required>
                                    <option value="" disabled selected>--Seleccione una opción--</option>
                                    <option value="Efectivo">Efectivo</option>
                                    <option value="Qr">Qr/Transferencia</option>
                                    <option value="Efectivo y Qr">Efectivo y Qr</option>
                                </select>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="amount_cash_modal">Monto en Efectivo</label>
                                <input type="number" name="amount_cash" id="amount_cash_modal" class="form-control"
                                    value="0" step="0.01" style="text-align: right" placeholder="0.00">
                            </div>
                            <div class="form-group col-md-6">
                                <label for="amount_qr_modal">Monto en Qr/Transferencia</label>
                                <input type="number" name="amount_qr" id="amount_qr_modal" class="form-control"
                                    value="0" step="0.01" style="text-align: right" placeholder="0.00">
                            </div>
                            <input type="hidden" name="amount" id="amount_modal" value="0">
                        </div>
                        <div class="form-group">
                            <label for="observation">Observación / Detalles</label>
                            <textarea name="observation" class="form-control" rows="3"></textarea>
                        </div>

                        <label class="checkbox-inline">
                            <input type="checkbox" required>Confirmar..!
                        </label>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default btn-cancel" data-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-success btn-submit">Guardar</button>
                    </div>
                </div>
            </div>
        </div>
    </form>
    <form action="{{ route('incomes-incomeDetail.transfer', ['id' => $income->id]) }}" class="form-edit-add"
        method="POST">
        <div class="modal fade" data-backdrop="static" id="modal-register-transfer" role="dialog"
            style="padding-top: 0px;">
            <div class="modal-dialog modal-primary modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar"><span
                                aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" style="color: #ffffff !important"><i
                                class="voyager-receipt"></i> Transferir Stock</h4>
                    </div>
                    <div class="modal-body" style="max-height: 75vh; overflow-y: auto;">
                        @csrf
                        <div class="row">
                            {{-- <div class="form-group col-md-12">
                                <label for="branch_id">Surcursal</label>
                                <select name="branch_id" id="branch_id" class="form-control select2" required>
                                    <option value="" selected disabled>--Seleccione una opción--</option>
                                    @foreach ($branches as $item)
                                        <option value="{{ $item->id }}">{{ $item->name }}</option>
                                    @endforeach
                                </select>
                            </div> --}}
                            <div class="form-group col-md-12">
                                <div class="table-responsive">
                                    <table id="dataTable" class="table table-bordered table-hover">
                                        <thead>
                                            <tr>
                                                <th style="width: 3%;">N&deg;</th>
                                                <th>Descripción</th>
                                                <th style="text-align: center; width: 15%;">Stock</th>
                                                <th style="text-align: center; width: 15%;">P. Compra</th>
                                                <th style="text-align: center; width: 13%;">P. Venta</th>
                                                <th style="text-align: center; width: 10%;">Cant.</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php
                                                $i = 1;
                                                $total = 0;
                                            @endphp
                                            @foreach ($income->incomeDetails->where('stock', '>', 0) as $item)
                                                <tr>
                                                    <td>
                                                        {{ $i }}
                                                        <input type="hidden" name="products[{{ $item->id }}][id]"
                                                            value="{{ $item->id }}">
                                                        <input type="hidden" name="products[{{ $item->id }}][item]"
                                                            value="{{ $item->item_id }}">
                                                        <input type="hidden" name="products[{{ $item->id }}][lote]"
                                                            value="{{ $item->lote }}">
                                                        <input type="hidden" name="products[{{ $item->id }}][expirationDate]"
                                                            value="{{ $item->expirationDate }}">
                                                        <input type="hidden" name="products[{{ $item->id }}][dispensed]"
                                                            value="{{ $item->dispensed }}">

                                                    </td>
                                             
                                                    <td>
                                                        <strong style="font-size: 12px">{{ $item->item->nameGeneric }}
                                                            {{ $item->item->nameTrade ? ' | ' . $item->item->nameTrade : '' }}</strong>
                                                        <br>
                                                        <div style="font-size: 10px; color: #555; margin-top: 5px;">
                                                            <span><i class="fa-solid fa-tags" style="color: #2ecc71; width: 14px;"></i> CATEGORÍA:</span>
                                                            {{ $item->item->category ? strtoupper($item->item->category->name) : 'SN' }}
                                                            <br>
                                                            <span><i class="fa-solid fa-flask" style="color: #3498db; width: 14px;"></i> LABORATORIO:</span>
                                                            {{ $item->item->laboratory ? strtoupper($item->item->laboratory->name) : 'SN' }}
                                                            <br>
                                                            <span><i class="fa-solid fa-copyright" style="color: #9b59b6; width: 14px;"></i> MARCA:</span>
                                                            {{ $item->item->brand ? strtoupper($item->item->brand->name) : 'SN' }}
                                                            <br>
                                                            @if ($item->dispensed == 'Fraccionado')
                                                                <span><i class="fa-solid fa-vial" style="color: #e74c3c; width: 14px;"></i> Contenido:</span>
                                                                {{number_format($item->dispensedQuantity, 2, ',', '.')}} {{strtoupper($item->item->fractionPresentation->name)}} / {{strtoupper($item->item->presentation->name)}}
                                                            @endif
                                                        </div>
                                                        @if ($item->dispensed == 'Fraccionado')
                                                            <br>
                                                            <span class="label label-success" style="margin-top: 5px;">Fraccionado</span>
                                                        @endif
                                                    </td>
                                                    <td style="text-align: right; vertical-align: middle;">
                                                        {{ number_format($item->stock, 2, ',', '.') }} {{strtoupper($item->item->presentation->name)}}
                                                    </td>
                                                    <td style="text-align: right; vertical-align: middle;">
                                                        <input type="hidden"
                                                            name="products[{{ $item->id }}][pricePurchase]"
                                                            value="{{ $item->pricePurchase }}">
                                                        Bs. {{ number_format($item->pricePurchase, 2, ',', '.') }} / {{strtoupper($item->item->presentation->name)}}
                                                    </td>
                                                    <td style="text-align: center; vertical-align: middle;">
                                                        <label for="">Bs. / {{strtoupper($item->item->presentation->name)}}.</label>
                                                        <input type="number"
                                                            name="products[{{ $item->id }}][priceSale]"
                                                            value="{{ $item->priceSale }}" min="0.01" step="0.01"
                                                            class="form-control" style="text-align: right" required>
                                                        @if ($item->dispensed == 'Fraccionado')                                                            
                                                            <label for="">Bs. / {{strtoupper($item->item->fractionPresentation->name)}}.</label>
                                                            <input type="number"
                                                                name="products[{{ $item->id }}][dispensedPrice]"
                                                                value="{{ $item->dispensedPrice }}" min="0.01" step="0.01"
                                                                class="form-control" style="text-align: right" required>

                                                            <input type="hidden" name="products[{{ $item->id }}][dispensedQuantity]"
                                                                value="{{ $item->dispensedQuantity }}">
                                                        @endif

                                                    </td>
                                                    <td style="text-align: center; vertical-align: middle;">
                                                        <input type="number"
                                                            name="products[{{ $item->id }}][quantity]" value=""
                                                            min="1" step="1" max="{{ $item->stock }}"
                                                            class="form-control" style="text-align: right">
                                                    </td>
                                                </tr>
                                                @php
                                                    $i++;
                                                @endphp
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <label class="checkbox-inline">
                            <input type="checkbox" required>Confirmar..!
                        </label>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default btn-cancel" data-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary btn-submit">Transferir</button>
                    </div>
                </div>
            </div>
        </div>
    </form>

    @include('partials.modal-delete')
@stop

@section('css')
    <style>
        :root {
            --primary-color: #3a7bd5;
            --secondary-color: #3a6073;
            --success-color: #2ecc71;
            --danger-color: #e74c3c;
            --text-color: #333333;
            --text-color-light: #888;
            --background-color: #f9fbfd;
            --panel-bg-color: #ffffff;
            --border-color: #eef2f7;
            --shadow-color: rgba(0, 0, 0, 0.08);
        }

        .page-title {
            color: var(--secondary-color);
        }

        .btn-primary {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            background-color: #326ab1;
            border-color: #326ab1;
        }

        .card {
            background-color: var(--panel-bg-color);
            border-radius: 8px;
            border: 1px solid var(--border-color);
            box-shadow: 0 2px 15px var(--shadow-color);
            margin-bottom: 25px;
            overflow: hidden;
        }

        .card-header {
            background-color: #fafafa;
            padding: 15px 25px;
            border-bottom: 1px solid var(--border-color);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .card-title,
        .card-header h5 {
            margin: 0;
            font-weight: 600;
            font-size: 1.1em;
            color: var(--secondary-color);
        }

        .card-body {
            padding: 25px;
        }

        .card-footer {
            background-color: #fdfdfd;
            padding: 15px 25px;
            border-top: 1px solid var(--border-color);
        }

        .info-item {
            margin-bottom: 10px;
        }

        .info-item small {
            display: block;
            color: var(--text-color-light);
            font-size: 0.9em;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .info-item p {
            margin: 0;
            font-size: 1.05em;
            color: var(--text-color);
            font-weight: 500;
        }

        .badge {
            font-size: 0.9em;
            padding: 5px 10px;
            border-radius: 20px;
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .badge-danger {
            background-color: var(--danger-color);
            color: white;
        }

        .badge-success {
            background-color: var(--success-color);
            color: white;
        }

        .table thead th {
            background-color: #f5f7fa;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.85em;
        }

        .table.zebra-striped tbody tr:nth-of-type(odd) {
            background-color: rgba(0, 0, 0, 0.02);
        }

        .summary-item {
            display: flex;
            justify-content: space-between;
            padding: 5px 0;
            font-size: 1.1em;
        }

        .summary-item.total {
            font-weight: bold;
            border-top: 1px solid var(--border-color);
            padding-top: 10px;
            margin-top: 5px;
            font-size: 1.2em;
            color: var(--primary-color);
        }

        .form-upload .input-group {
            display: flex;
        }

        .summary-container {
            display: flex;
            justify-content: flex-end;
        }
    </style>
@stop

@section('javascript')
    <script src="{{ asset('js/btn-submit.js') }}"></script>
    <script>
        $(document).ready(function() {

            $('#delete_form').submit(function(e) {
                $('.btn-form-delete').attr('disabled', true);
                $('.btn-form-delete').val('Eliminando...');
            });
        });
        $('.form-submit').submit(function(e) {
            $('.btn-submit').attr('disabled', true);
            $('.btn-submit').val('Guardando...');
        });

        function deleteItem(url) {
            $('#delete_form').attr('action', url);
        }
    </script>

@stop
