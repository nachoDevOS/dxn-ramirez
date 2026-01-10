<div class="col-md-12">
    <div class="table-responsive">
        <table id="dataTable" class="table table-bordered table-hover">
            <thead>
                <tr>
                    <th style="text-align: center; width: 5%">ID</th>
                    <th style="text-align: center">Proveedor</th>
                    <th style="text-align: center; width: 20%">Detalles</th>                    
                    <th style="text-align: center; width: 10%">Fecha.</th>
                    <th style="text-align: center; width: 20%">Registrado por</th>
                    <th style="text-align: center; width: 10%">Estado</th>
                    <th style="text-align: center; width: 10%">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($data as $item)
                <tr>
                    <td>{{ $item->id }}</td>
                    <td>{{ $item->supplier?$item->supplier->name:'Sin Proveedor'}}</td>
                    <td>
                        <table id="dataTable">                            
                            <tr>
                                <td>Total de la Compra: </td>
                                <td style="text-align: right">
                                    Bs. {{ number_format($item->amount, 2, ',', '.') }}
                                </td>
                            </tr>
                            @if ($item->typeIncome == 'Compra al Credito' && $item->amortization != $item->amount)
                                <tr>
                                    <td>Total Amortizado: </td>
                                    <td style="text-align: right">
                                        Bs. {{ number_format($item->amortization, 2, ',', '.') }}
                                    </td>
                                </tr>
                                <tr>
                                    <td>Deuda Total: </td>
                                    <td style="text-align: right">
                                        Bs. {{ number_format($item->amount-$item->amortization, 2, ',', '.') }}
                                    </td>
                                </tr>
                            @endif
                            <tr>
                                <td>Stock:</td>
                                <td style="text-align: right">
                                    @if ($item->total_stock > 0)
                                        <label class="label label-success">Disponible</label>
                                    @else
                                        <label class="label label-danger">Agotado</label>
                                    @endif
                                </td>
                            </tr>
                        </table>
                    </td>
                    <td style="text-align: center">
                        {{ date('d/m/Y', strtotime($item->date)) }} <br>
                        {{$item->typeIncome}}
                    </td>
                    <td style="text-align: center">
                        Registrado por {{$item->register->name}} <br>
                        {{date('d/m/Y h:i:s a', strtotime($item->created_at))}}<br><small>{{\Carbon\Carbon::parse($item->created_at)->diffForHumans()}}
                    </td>
                    <td style="text-align: center">
                        @if ($item->status=='Pagado')  
                            <label class="label label-success">Pagado</label>
                        @else
                            <label class="label label-warning">En pago</label>
                        @endif

                        
                    </td>
                    <td style="width: 18%" class="no-sort no-click bread-actions text-right">
                        @if (auth()->user()->hasPermission('read_incomes'))
                            <a href="{{ route('incomes.show', ['income' => $item->id]) }}" title="Ver" class="btn btn-sm btn-warning view">
                                <i class="voyager-eye"></i> <span class="hidden-xs hidden-sm">Ver</span>
                            </a>
                        @endif
                        {{-- @if (auth()->user()->hasPermission('edit_people'))
                            <a href="{{ route('voyager.people.edit', ['id' => $item->id]) }}" title="Editar" class="btn btn-sm btn-primary edit">
                                <i class="voyager-edit"></i> <span class="hidden-xs hidden-sm">Editar</span>
                            </a>
                        @endif --}}
                        @if (auth()->user()->hasPermission('delete_incomes'))
                            <a href="#" onclick="deleteItem('{{ route('incomes.destroy', ['income' => $item->id]) }}')" title="Eliminar" data-toggle="modal" data-target="#modal-delete" class="btn btn-sm btn-danger delete">
                                <i class="voyager-trash"></i> <span class="hidden-xs hidden-sm">Eliminar</span>
                            </a>
                        @endif
                    </td>
                </tr>
                @empty
                    <tr>
                        <td colspan="7">
                            <h5 class="text-center" style="margin-top: 50px">
                                <img src="{{ asset('images/empty.png') }}" width="120px" alt="" style="opacity: 0.8">
                                <br><br>
                                No hay resultados
                            </h5>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="col-md-12">
    <div class="col-md-4" style="overflow-x:auto">
        @if(count($data)>0)
            <p class="text-muted">Mostrando del {{$data->firstItem()}} al {{$data->lastItem()}} de {{$data->total()}} registros.</p>
        @endif
    </div>
    <div class="col-md-8" style="overflow-x:auto">
        <nav class="text-right">
            {{ $data->links() }}
        </nav>
    </div>
</div>

<script>
   
   var page = "{{ request('page') }}";
    $(document).ready(function(){
        $('.page-link').click(function(e){
            e.preventDefault();
            let link = $(this).attr('href');
            if(link){
                page = link.split('=')[1];
                list(page);
            }
        });
    });
</script>