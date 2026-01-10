<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ErrorController;
use App\Http\Controllers\PersonController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AnamnesisFormController;
use App\Http\Controllers\AjaxController;
use App\Http\Controllers\AnimalController;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\CashierController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\IncomeController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\MicroServiceController;
use App\Http\Controllers\PetController;
use App\Http\Controllers\PublicController;
use App\Http\Controllers\RaceController;
use App\Http\Controllers\ReminderController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\VaccinationRecordController;
use App\Http\Controllers\WhatsappController;
use App\Http\Controllers\WorkerController;
use App\Models\Reminder;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return redirect('admin/login');
})->name('login');

// Route::get('/', [HomeController::class, 'index']);
// Route::post('/appointment', [HomeController::class, 'storeAppointment'])->name('appointment.store');
// // Ruta para obtener las razas de un animal vía AJAX
// Route::get('/api/races/{animal}', [HomeController::class, 'getRaces'])->name('api.races');


Route::get('/info/{id?}', [ErrorController::class , 'error'])->name('errors');
// Route::get('/development', [ErrorController::class , 'error503'])->name('development');

Route::get('/reminder/notificate', [PublicController::class, 'reminderNotificate']);

Route::group(['prefix' => 'admin', 'middleware' => ['loggin', 'system']], function () {
    Voyager::routes();

    Route::resource('cashiers', CashierController::class);
    Route::get('cashiers/list/ajax', [CashierController::class, 'list'])->name('cashiers.list');

    Route::post('cashiers/{cashier}/change/status', [CashierController::class, 'change_status'])->name('cashiers.change.status');//*** Para que los cajeros Acepte o rechase el dinero dado por Boveda o gerente
    Route::get('cashiers/{cashier}/close/', [CashierController::class, 'close'])->name('cashiers.close');//***para cerrar la caja el cajero vista 
    Route::post('cashiers/{cashier}/close/store', [CashierController::class, 'close_store'])->name('cashiers.close.store'); //para que el cajerop cierre la caja 
    Route::post('cashiers/{cashier}/close/revert', [CashierController::class, 'close_revert'])->name('cashiers.close.revert'); //para revertir el cajero para q su caja vuelva 
    Route::get('cashiers/{cashier}/confirm_close', [CashierController::class, 'confirm_close'])->name('cashiers.confirm_close'); //Para confirmar el cierre de caja
    Route::post('cashiers/{cashier}/confirm_close/store', [CashierController::class, 'confirm_close_store'])->name('cashiers.confirm_close.store');

    Route::get('cashiers/print/open/{id?}', [CashierController::class, 'print_open'])->name('print.open');//para imprimir el comprobante cuando se abre una caja
    Route::get('cashiers/print/close/{id?}', [CashierController::class, 'print_close'])->name('print.close');//Para imprimir cierre de caja
    Route::get('cashiers/{id}/print', [CashierController::class, 'print'])->name('cashiers.print');//Para el cierre pendiente de caja por el cajero

    Route::resource('expenses', ExpenseController::class);


    Route::resource('sales', SaleController::class);
    Route::get('sales/ajax/list', [SaleController::class, 'list']);
    Route::get('sales/item/stock/ajax', [AjaxController::class, 'itemStockList']);//Para obtener los item que hay disponible en el inventario

    // Route::delete('sales/{id}/payment/{payment}', [SaleController::class, 'destroyPayment'])->name('sales-payment.destroy');
    // Route::get('sales/{id}/payment/{payment}/prinf', [SaleController::class, 'prinfPayment'])->name('sales-payment.prinf');
    Route::get('sales/{id}/prinf', [SaleController::class, 'prinf'])->name('sales.prinf');
    Route::get('sales/ticket/{id}', [SaleController::class, 'printTicket'])->name('sales-ticket.print');



    Route::get('people', [PersonController::class, 'index'])->name('voyager.people.index');
    Route::get('people/ajax/list', [PersonController::class, 'list']);
    Route::get('people/{id}', [PersonController::class, 'show'])->name('voyager.people.show');
    Route::post('people', [PersonController::class, 'store'])->name('voyager.people.store');
    Route::put('people/{id}', [PersonController::class, 'update'])->name('voyager.people.update');

    // Route::get('workers', [WorkerController::class, 'index'])->name('voyager.workers.index');
    // Route::get('workers/ajax/list', [WorkerController::class, 'list']);
    // Route::get('workers/{id}', [WorkerController::class, 'show'])->name('voyager.workers.show');
    // Route::get('workers/{id}/history/list', [WorkerController::class, 'listHistory'])->name('workers.history.list');
    // Route::post('workers/file/store', [WorkerController::class, 'storeFile'])->name('workers.file.store');
    // Route::get('workers/{id}/file/list', [WorkerController::class, 'listFiles'])->name('workers.file.list');


    // Route::post('workers', [WorkerController::class, 'store'])->name('voyager.workers.store');
    // Route::put('workers/{id}', [WorkerController::class, 'update'])->name('voyager.workers.update');

    // Route::get('pets', [PetController::class, 'index'])->name('voyager.pets.index');
    // Route::get('pets/ajax/list', [PetController::class, 'list']);
    // Route::get('pets/create', [PetController::class, 'create'])->name('voyager.pets.create');
    // Route::get('pets/{id}', [PetController::class, 'show'])->name('voyager.pets.show');
    // Route::get('pets/{id}/edit', [PetController::class, 'edit'])->name('voyager.pets.edit');
    // Route::post('pets', [PetController::class, 'store'])->name('voyager.pets.store');
    // Route::put('pets/{id}', [PetController::class, 'update'])->name('voyager.pets.update');
    // Route::delete('pets/{id}', [PetController::class, 'destroy'])->name('voyager.pets.destroy');

    // Route::get('pets/{id}/history/create', [PetController::class, 'createHistory'])->name('voyager.pets.history.create');
    // Route::get('pets/{pet}/history/list', [AnamnesisFormController::class, 'listByPet'])->name('voyager.pets.history.list');
    // Route::get('pets/history/{anamnesis}/edit', [AnamnesisFormController::class, 'edit'])->name('voyager.pets.history.edit');
    // Route::post('pets/{pet}/history', [AnamnesisFormController::class, 'store'])->name('voyager.pets.history.store');
    // Route::put('pets/history/{anamnesis}', [AnamnesisFormController::class, 'update'])->name('voyager.pets.history.update');
    // Route::get('pets/history/{history}', [AnamnesisFormController::class, 'show'])->name('voyager.pets.history.show');
    // Route::delete('pets/history/{anamnesis}', [AnamnesisFormController::class, 'destroy'])->name('voyager.pets.history.destroy');
    // Route::get('pets/history/{history}/print', [AnamnesisFormController::class, 'print'])->name('voyager.pets.history.print');


    // Route::get('pets/{id}/vaccinationrecords/create', [PetController::class, 'createVaccinationRecords'])->name('voyager.pets.vaccinationrecords.create'); //crear vacunas en la mascota
    // Route::get('pets/{pet}/vaccinationrecords/list', [VaccinationRecordController::class, 'listByPet'])->name('voyager.pets.vaccinationrecords.list');
    // Route::post('pets/{pet}/vaccinationrecords', [VaccinationRecordController::class, 'store'])->name('voyager.pets.vaccinationrecords.store');
    // Route::get('pets/vaccinationrecords/{vaccine}/edit', [VaccinationRecordController::class, 'edit'])->name('voyager.pets.vaccinationrecords.edit');
    // Route::put('pets/vaccinationrecords/{vaccine}', [VaccinationRecordController::class, 'update'])->name('voyager.pets.vaccinationrecords.update');
    // Route::get('pets/vaccinationrecords/{vaccine}', [VaccinationRecordController::class, 'show'])->name('voyager.pets.vaccinationrecords.show');
    // Route::delete('pets/vaccinationrecords/{vaccine}', [VaccinationRecordController::class, 'destroy'])->name('voyager.pets.vaccinationrecords.destroy');
    // Route::post('pets/vaccinationrecords/{vaccine}/send-whatsapp', [VaccinationRecordController::class, 'sendWhatsApp']); //Notificacion de proximas atenciones


    // // Reminders
    // Route::post('pets/reminders/store', [ReminderController::class, 'store'])->name('pets.reminders.store');
    // Route::get('pets/reminders/list/{pet_id}', [ReminderController::class, 'list'])->name('pets.reminders.list');
    // Route::post('pets/reminders/{reminder}/send-whatsapp', [ReminderController::class, 'sendWhatsApp']); //Notificacion de Recordatorios
    // Route::delete('pets/reminders/{reminder}', [ReminderController::class, 'destroy'])->name('voyager.pets.reminders.destroy');


    // --- NUEVAS RUTAS PARA EDITAR HISTORIAL ---
    
    
    

    Route::get('whatsapp', [MicroServiceController::class, 'message'])->name('whatsapp.message');


    // Route::get('animals', [AnimalController::class, 'index'])->name('voyager.animals.index');
    // Route::get('animals/ajax/list', [AnimalController::class, 'list']);
    // Route::get('animals/{id}', [AnimalController::class, 'show'])->name('voyager.animals.show');

    // // --- Rutas para Razas (Races) ---
    // Route::post('races', [RaceController::class, 'store'])->name('voyager.races.store');
    // Route::get('races/{id}/edit', [RaceController::class, 'edit'])->name('voyager.races.edit');
    // Route::put('races/{id}', [RaceController::class, 'update'])->name('voyager.races.update');
    // Route::delete('races/{id}', [RaceController::class, 'destroy'])->name('voyager.races.destroy');
    // // Ruta para la lista AJAX de razas por animal
    // Route::get('animals/{id}/races/ajax', [RaceController::class, 'ajaxList'])->name('animals.races.ajax');
    // Route::post('races/ajax-store', [PetController::class, 'ajaxStoreRace'])->name('voyager.races.ajax.store');


    Route::resource('incomes', IncomeController::class);
    Route::get('incomes/ajax/list', [IncomeController::class, 'list']);
    Route::get('incomes/item/ajax', [AjaxController::class, 'itemList']);//Para obtener los item que esten registrado
    Route::post('incomes/{id}/payment', [IncomeController::class, 'storePayment'])->name('incomes-payment.store');
    Route::post('incomes/{id}/file', [IncomeController::class, 'fileStore'])->name('incomes-file.store');
    Route::get('incomes/{id}/file/download', [IncomeController::class, 'downloadFile'])->name('incomes.file.download');

    Route::post('incomes/{id}/incomeDetail/transfer', [IncomeController::class, 'transferIncomeDetail'])->name('incomes-incomeDetail.transfer');//Para transferir los item a las sucursale que pue tengan stop  los item
    Route::delete('incomes/{id}/incomeDetail/transfer/{transfer}', [IncomeController::class, 'destroyTransferIncomeDetail'])->name('incomes-incomeDetail-transfer.destroy');

    Route::get('items', [ItemController::class, 'index'])->name('voyager.items.index');
    Route::get('items/ajax/list', [ItemController::class, 'list']);
    Route::post('items', [ItemController::class, 'store'])->name('voyager.items.store');
    Route::put('items/{id}', [ItemController::class, 'update'])->name('voyager.items.update');
    Route::get('items/{id}', [ItemController::class, 'show'])->name('voyager.items.show');
    Route::get('items/{id}/stock/ajax/list', [ItemController::class, 'listStock']);

    Route::get('item/stock/ajax', [AjaxController::class, 'itemStockList']);//
    // Route::get('items/{id}/dispensations/ajax/list', [ItemController::class, 'listDispensations']);
    Route::get('items/{id}/sales/ajax/list', [ItemController::class, 'listSales']);

    Route::post('items/{id}/stock', [ItemController::class, 'storeStock'])->name('items-stock.store');
    Route::delete('items/{id}/stock/{stock}', [ItemController::class, 'destroyStock'])->name('items-stock.destroy');




    // Users
    Route::get('users/ajax/list', [UserController::class, 'list']);
    Route::post('users/store', [UserController::class, 'store'])->name('voyager.users.store');
    Route::put('users/{id}', [UserController::class, 'update'])->name('voyager.users.update');
    Route::delete('users/{id}/deleted', [UserController::class, 'destroy'])->name('voyager.users.destroy');

    // Roles
    Route::get('roles/ajax/list', [RoleController::class, 'list']);


    Route::get('ajax/personList', [AjaxController::class, 'personList']);
    Route::post('ajax/person/store', [AjaxController::class, 'personStore']);

    Route::get('ajax/workerList', [AjaxController::class, 'workerList']);
    Route::post('ajax/worker/store', [AjaxController::class, 'workerStore']);

    Route::get('ajax/item-stock/list', [AjaxController::class, 'itemStockList'])->name('ajax.item-stock.list');

    


});


// Clear cache
Route::get('/admin/clear-cache', function() {
    Artisan::call('optimize:clear');

    // Artisan::call('db:seed', ['--class' => 'UpdateBreadSeeder']);
    // Artisan::call('db:seed', ['--class' => 'UpdatePermissionsSeeder']);
    
    return redirect('/admin/profile')->with(['message' => 'Cache eliminada.', 'alert-type' => 'success']);
})->name('clear.cache');    