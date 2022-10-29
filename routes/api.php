<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\RowController;
use App\Http\Controllers\Admin\BookController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\TypeController;
use App\Http\Controllers\Admin\ColumnController;
use App\Http\Controllers\User\DepositController;
use App\Http\Controllers\User\PaymentController;
use App\Http\Controllers\ValidateTokenController;
use App\Http\Controllers\Admin\BookListController;
use App\Http\Controllers\Admin\UserRoleController;
use App\Http\Controllers\User\BookApplyController;
use App\Http\Controllers\Admin\Auth\AuthController;
use App\Http\Controllers\Admin\BookStatusController;
use App\Http\Controllers\Admin\ConfirmWithdrawalController;
use App\Http\Controllers\Admin\SmsControoler;
use App\Http\Controllers\User\UserEditProfileController;
use App\Http\Controllers\User\Auth\AuthController as UserAuth;
use App\Http\Controllers\User\WalletController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });
Route::prefix('auth')->group(function () {
    Route::prefix('user')->group(function () {
        Route::post('login-register', [UserAuth::class, 'loginRegister'])->name('auth.user.login-register');
        Route::post('login-confirm', [UserAuth::class, 'loginConfirm'])->name('auth.user.login-confirm');
        Route::post('logout', [UserAuth::class, 'logout'])->middleware('auth:custome_user_api')->name('auth.user.logout');
    });
    Route::prefix('back-office-user')->group(function () {
        Route::post('login', [AuthController::class, 'login'])->name('auth.back-office-user.login');
        Route::post('login-confirm', [AuthController::class, 'loginConfirm'])->name('auth.back-office-user.login-confirm');
        Route::post('logout', [AuthController::class, 'logout'])->middleware('auth:back_office_user_api')->name('auth.back-office-user.logout');
    });
});
Route::middleware('auth:back_office_user_api')->prefix('admin')->group(function () {
    Route::prefix('role')->group(function () {
        Route::get('/', [RoleController::class, 'index'])->name('admin.role.index');
        Route::get('/show/{role}', [RoleController::class, 'show'])->name('admin.role.show');
        Route::post('/store', [RoleController::class, 'store'])->name('admin.role.store');
        Route::put('/update/{role}', [RoleController::class, 'update'])->name('admin.role.update');
        Route::delete('/destroy/{role}', [RoleController::class, 'destroy'])->name('admin.role.destroy');
    });
    Route::prefix('book')->group(function () {
        Route::get('/', [BookController::class, 'index'])->name('admin.book.index');
        Route::get('/show/{book}', [BookController::class, 'show'])->name('admin.book.show');
        Route::post('/store', [BookController::class, 'store'])->name('admin.book.store');
        Route::put('/update/{book}', [BookController::class, 'update'])->name('admin.book.update');
        Route::delete('/destroy{book}', [BookController::class, 'destroy'])->name('admin.book.destroy');
    });
    Route::prefix('user-role')->group(function () {
        Route::get('/', [UserRoleController::class, 'backOfficeUserList'])->name('admin.user-role.back-office-user-list');
        Route::post('assign-role', [UserRoleController::class, 'assignRoleToUser'])->name('admin.user-role.assign-role-to-user');
        Route::put('edit-role/{backOfficeUser}', [UserRoleController::class, 'editUserRole'])->name('admin.user-role.edit-user-role');
        Route::delete('delete-user/{backOfficeUser}', [UserRoleController::class, 'destroy'])->name('admin.user-role.destroy');
    });
    Route::prefix('type')->group(function () {
        Route::get('/', [TypeController::class, 'index'])->name('admin.type.index');
        Route::get('/show/{type}', [TypeController::class, 'show'])->name('admin.type.show');
        Route::post('/store', [TypeController::class, 'store'])->name('admin.type.store');
        Route::put('/update/{type}', [TypeController::class, 'update'])->name('admin.type.update');
        Route::delete('/destroy/{type}', [TypeController::class, 'destroy'])->name('admin.type.destroy');
        Route::get('/column-row-list', [TypeController::class, 'columnRowList'])->name('admin.type.column-row-list');
    });
    Route::prefix('column')->group(function () {
        Route::get('/', [ColumnController::class, 'index'])->name('admin.column.index');
        Route::get('/show/{column}', [ColumnController::class, 'show'])->name('admin.column.show');
        Route::post('/store', [ColumnController::class, 'store'])->name('admin.column.store');
        Route::put('/update/{column}', [ColumnController::class, 'update'])->name('admin.column.update');
        Route::delete('/destroy/{column}', [ColumnController::class, 'destroy'])->name('admin.column.destroy');
    });
    Route::prefix('row')->group(function () {
        Route::get('/', [RowController::class, 'index'])->name('admin.row.index');
        Route::get('/show/{row}', [RowController::class, 'show'])->name('admin.row.show');
        Route::post('/store', [RowController::class, 'store'])->name('admin.row.store');
        Route::put('/update/{row}', [RowController::class, 'update'])->name('admin.row.update');
        Route::delete('/destroy/{row}', [RowController::class, 'destroy'])->name('admin.row.destroy');
        Route::post('/column-row/{row}', [RowController::class, 'columnRow'])->name('admin.row.column-row');
    });
    Route::post('change-book-status/{book}/{user}', [BookStatusController::class, 'changeStatus']);
    Route::post('book-delivery/{book}/{user}', [BookStatusController::class, 'bookDelivery']);
    Route::get('reserved-books', [BookListController::class, 'reservedBooks']);
    Route::get('not-confirmed-withdrawals', [ConfirmWithdrawalController::class, 'notConfirmedWithdrawals']);
    Route::post('confirm-withdrawal/{withdrawal}', [ConfirmWithdrawalController::class, 'confirmWithdrawal']);
    Route::post('send-sms', [SmsControoler::class, 'send']);
});
Route::middleware('auth:custom_user_api')->prefix('user')->group(function () {
    Route::prefix('wallet')->group(function () {
        Route::post('/deposit', [DepositController::class, 'deposit'])->name('admin.wallet.deposit');
        Route::post('/withdrawal', [WalletController::class, 'withdrawal'])->name('admin.wallet.withdrawal');
        Route::get('/wallet-inventory', [WalletController::class, 'walletInventory'])->name('admin.wallet-inventory');
    });
    Route::post('book-apply/{book}', [BookApplyController::class, 'bookApply']);
    Route::put('edit-profile', [UserEditProfileController::class, 'edit']);
    Route::get('profile', [UserEditProfileController::class, 'profile']);
});
Route::get('/verify', [PaymentController::class, 'verification']);
// Route::post('validate-token', [ValidateTokenController::class , 'validateToken']);
