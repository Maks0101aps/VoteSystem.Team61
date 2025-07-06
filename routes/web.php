<?php

use Inertia\Inertia;

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\ContactsController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ImagesController;
use App\Http\Controllers\OrganizationsController;
use App\Http\Controllers\ReportsController;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\VotingController;
use App\Http\Controllers\PetitionsController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\DirectorController;
use Illuminate\Support\Facades\Route;

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

// Auth

Route::get('login', [AuthenticatedSessionController::class, 'create'])
    ->name('login')
    ->middleware('guest');

Route::post('login', [AuthenticatedSessionController::class, 'store'])
    ->name('login.store')
    ->middleware('guest');

Route::get('register', [RegisteredUserController::class, 'create'])
    ->middleware('guest')
    ->name('register');

Route::post('register', [RegisteredUserController::class, 'store'])
    ->middleware('guest');

Route::delete('logout', [AuthenticatedSessionController::class, 'destroy'])
    ->name('logout');

// Dashboard

Route::get('/', [DashboardController::class, 'index'])
    ->name('dashboard')
    ->middleware('auth');

// Voting

Route::get('/votings/create', [VotingController::class, 'create'])
    ->name('voting.create')
    ->middleware('auth');

Route::post('/votings', [VotingController::class, 'store'])
    ->name('voting.store')
    ->middleware('auth');

Route::get('/votings', [VotingController::class, 'index'])
    ->name('voting.index')
    ->middleware('auth');

Route::post('/votings/{voting}/vote', [VotingController::class, 'vote'])
    ->name('voting.vote')
    ->middleware('auth');

Route::delete('/votings/{voting}', [VotingController::class, 'destroy'])
    ->name('voting.destroy')
    ->middleware('auth');

// Petitions

Route::get('petitions', [PetitionsController::class, 'index'])
    ->name('petitions')
    ->middleware('auth');

Route::get('petitions/create', [PetitionsController::class, 'create'])
    ->name('petitions.create')
    ->middleware('auth');

Route::post('petitions', [PetitionsController::class, 'store'])
    ->name('petitions.store')
    ->middleware('auth');

Route::post('petitions/{petition}/sign', [PetitionsController::class, 'sign'])
    ->name('petitions.sign')
    ->middleware('auth');

Route::post('petitions/{petition}/comments', [CommentController::class, 'store'])
    ->name('petitions.comments.store')
    ->middleware('auth');

Route::delete('petitions/{petition}', [PetitionsController::class, 'destroy'])
    ->name('petitions.destroy')
    ->middleware('auth');

// Director

Route::middleware(['auth', 'role:director'])->group(function () {
    Route::get('director/petitions', [DirectorController::class, 'index'])
        ->name('director.petitions.index');

    Route::post('director/petitions/{petition}/approve', [DirectorController::class, 'approve'])
        ->name('director.petitions.approve');

    Route::post('director/petitions/{petition}/reject', [DirectorController::class, 'reject'])
        ->name('director.petitions.reject');
});

// Users

Route::get('users', [UsersController::class, 'index'])
    ->name('users')
    ->middleware('auth');

Route::get('users/create', [UsersController::class, 'create'])
    ->name('users.create')
    ->middleware('auth');

Route::post('users', [UsersController::class, 'store'])
    ->name('users.store')
    ->middleware('auth');

Route::get('users/{user}/edit', [UsersController::class, 'edit'])
    ->name('users.edit')
    ->middleware('auth');

Route::put('users/{user}', [UsersController::class, 'update'])
    ->name('users.update')
    ->middleware('auth');

Route::delete('users/{user}', [UsersController::class, 'destroy'])
    ->name('users.destroy')
    ->middleware('auth');

Route::put('users/{user}/restore', [UsersController::class, 'restore'])
    ->name('users.restore')
    ->middleware('auth');

// Organizations

Route::get('organizations', [OrganizationsController::class, 'index'])
    ->name('organizations')
    ->middleware('auth');

Route::get('organizations/create', [OrganizationsController::class, 'create'])
    ->name('organizations.create')
    ->middleware('auth');

Route::post('organizations', [OrganizationsController::class, 'store'])
    ->name('organizations.store')
    ->middleware('auth');

Route::get('organizations/{organization}/edit', [OrganizationsController::class, 'edit'])
    ->name('organizations.edit')
    ->middleware('auth');

Route::put('organizations/{organization}', [OrganizationsController::class, 'update'])
    ->name('organizations.update')
    ->middleware('auth');

Route::delete('organizations/{organization}', [OrganizationsController::class, 'destroy'])
    ->name('organizations.destroy')
    ->middleware('auth');

Route::put('organizations/{organization}/restore', [OrganizationsController::class, 'restore'])
    ->name('organizations.restore')
    ->middleware('auth');

// Contacts

Route::get('contacts', [ContactsController::class, 'index'])
    ->name('contacts')
    ->middleware('auth');

Route::get('contacts/create', [ContactsController::class, 'create'])
    ->name('contacts.create')
    ->middleware('auth');

Route::post('contacts', [ContactsController::class, 'store'])
    ->name('contacts.store')
    ->middleware('auth');

Route::get('contacts/{contact}/edit', [ContactsController::class, 'edit'])
    ->name('contacts.edit')
    ->middleware('auth');

Route::put('contacts/{contact}', [ContactsController::class, 'update'])
    ->name('contacts.update')
    ->middleware('auth');

Route::delete('contacts/{contact}', [ContactsController::class, 'destroy'])
    ->name('contacts.destroy')
    ->middleware('auth');

Route::put('contacts/{contact}/restore', [ContactsController::class, 'restore'])
    ->name('contacts.restore')
    ->middleware('auth');

// Reports

Route::get('reports', [ReportsController::class, 'index'])
    ->name('reports')
    ->middleware('auth');

Route::get('messages', function () {
    return Inertia::render('Messages/Index');
})->name('messages')->middleware(['auth', 'role:director']);

// Images

Route::get('/img/{path}', [ImagesController::class, 'show'])
    ->where('path', '.*')
    ->name('image');
