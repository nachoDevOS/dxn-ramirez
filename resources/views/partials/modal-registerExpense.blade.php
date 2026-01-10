<form class="form-edit-add" action="{{ route('expenses.store') }}"  method="POST" id="form-create-expense">
    <div class="modal fade" id="modal-create-expense" role="dialog">
        <div class="modal-dialog modal-lg modal-success">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar"><span
                            aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" style="color: #ffffff !important"><i class="voyager-plus"></i> Registrar
                        Gastos Extras</h4>
                </div>
                <div class="modal-body">
                    @csrf
                    <input type="hidden" name="amount" id="input-total-amount">
                    <input type="hidden" name="observation" id="input-observation">
                    <div class="row">
                        <div class="col-md-12">
                            <table class="table table-bordered table-hover" id="table-expenses">
                                <thead>
                                    <tr>
                                        <th>Detalle</th>
                                        <th style="width: 100px">Cant.</th>
                                        <th style="width: 120px">Precio</th>
                                        <th style="width: 120px">Total</th>
                                        <th style="width: 50px"></th>
                                    </tr>
                                </thead>
                                <tbody id="tbody-expenses">
                                    <tr class="tr-expense">
                                        <td>
                                            <input type="text" name="details[]" class="form-control input-detail" placeholder="Detalle" required>
                                        </td>
                                        <td>
                                            <input type="number" name="quantities[]" class="form-control input-quantity" value="1" min="1" step="1" onkeyup="calculateTotalExpense()" onchange="calculateTotalExpense()" required>
                                        </td>
                                        <td>
                                            <input type="number" name="prices[]" class="form-control input-price" step="0.5" min="0.5" placeholder="0.00" onkeyup="calculateTotalExpense()" onchange="calculateTotalExpense()" required>
                                        </td>
                                        <td>
                                            <input type="number" class="form-control input-subtotal" value="0.00" readonly>
                                        </td>
                                        <td class="text-center">
                                            <button type="button" class="btn btn-danger btn-sm" onclick="removeRowExpense(this)"><i class="voyager-trash"></i></button>
                                        </td>
                                    </tr>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan="3" class="text-right"><h4>TOTAL</h4></td>
                                        <td>
                                            <input type="number" class="form-control" id="input-grand-total" value="0.00" readonly>
                                        </td>
                                        <td></td>
                                    </tr>
                                </tfoot>
                            </table>
                            <button type="button" class="btn btn-primary btn-sm" onclick="addRowExpense()"><i class="voyager-plus"></i> Agregar fila</button>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default btn-cancel" data-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-success btn-submit">Si, Guardar</button>
                </div>
            </div>
        </div>
    </div>
    <script>
        function addRowExpense() {
            let row = `
                <tr class="tr-expense">
                    <td>
                        <input type="text" name="details[]" class="form-control input-detail" placeholder="Detalle" required>
                    </td>
                    <td>
                        <input type="number" name="quantities[]" class="form-control input-quantity" value="1" min="1" step="1" onkeyup="calculateTotalExpense()" onchange="calculateTotalExpense()" required>
                    </td>
                    <td>
                        <input type="number" name="prices[]" class="form-control input-price" step="0.01" min="0" placeholder="0.00" onkeyup="calculateTotalExpense()" onchange="calculateTotalExpense()" required>
                    </td>
                    <td>
                        <input type="number" class="form-control input-subtotal" value="0.00" readonly>
                    </td>
                    <td class="text-center">
                        <button type="button" class="btn btn-danger btn-sm" onclick="removeRowExpense(this)"><i class="voyager-trash"></i></button>
                    </td>
                </tr>
            `;
            $('#tbody-expenses').append(row);
        }

        function removeRowExpense(btn) {
            if ($('#tbody-expenses tr').length > 1) {
                $(btn).closest('tr').remove();
                calculateTotalExpense();
            }
        }

        function calculateTotalExpense() {
            let total = 0;
            $('.tr-expense').each(function() {
                let quantity = parseFloat($(this).find('.input-quantity').val()) || 0;
                let price = parseFloat($(this).find('.input-price').val()) || 0;
                let subtotal = quantity * price;
                $(this).find('.input-subtotal').val(subtotal.toFixed(2));
                total += subtotal;
            });
            $('#input-grand-total').val(total.toFixed(2));
            $('#input-total-amount').val(total.toFixed(2));
        }

        $('#form-create-expense').on('submit', function() {
            let observation = '';
            $('.tr-expense').each(function() {
                let detail = $(this).find('.input-detail').val();
                let quantity = $(this).find('.input-quantity').val();
                let price = $(this).find('.input-price').val();
                let subtotal = $(this).find('.input-subtotal').val();
                if (detail) {
                    observation += `${detail} (${quantity} x ${price} = ${subtotal}), `;
                }
            });
            if (observation.length > 2) {
                observation = observation.substring(0, observation.length - 2);
            }
            $('#input-observation').val(observation);
        });
    </script>
</form>
