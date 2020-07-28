<?php

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
// Main
Route::get('/', 'PageController@home')->name('home.show');
Route::post('search', 'EventController@search')->name('event.search');

// Static pages
Route::get('faq', 'PageController@faq')->name('faq.index');
Route::get('about', 'PageController@about')->name('about.index');

// Contact
Route::get('contact', 'ContactController@index')->name("contact.index");
Route::post('contact', 'ContactController@send')->name('contact.send');


// User profile
Route::get('profile/{id}', 'UserController@show')->name('profile.show');
Route::get('profile/{id}/edit', 'EditProfileController@index')->name('edit.show');
Route::post('profile/{id}/edit', 'EditProfileController@update')->name('profile.update');
Route::post('profilePic', 'UserController@profilePic')->name('profilePic');
Route::post('delete', 'UserController@delete')->name('user.delete');


// Event
Route::get('profile/{id}/create', 'EventController@index')->name('create.show');
Route::post('profile/{id}/create', 'EventController@create')->name('create');
Route::post('event/{id}', 'EventController@delete')->name('event.delete');
Route::get('event/{id}/edit', 'EventController@editPage')->name('event.edit');
Route::post('event/{id}/edit', 'EventController@update')->name('event.update');
Route::get('event/{id}', 'EventController@show')->name('event.show');
Route::get('participate', 'TicketController@index')->name('participate');
Route::post('participate', 'TicketController@create');
Route::post('cancelParticipation', 'TicketController@delete')->name('cancel');
Route::post('eventPic', 'PhotoController@create')->name('eventPic');
Route::post('event/{id}/invite', 'InviteController@send')->name('event.invite');

// Comment
Route::get('event/{id}/comment', 'CommentController@show')->name('comment.show');
Route::post('event/{id}/comment', 'CommentController@create')->name('comment');
Route::post('deletecomment/{id}', 'CommentController@delete')->name('comment.delete');

// Admin
Route::get('admin', 'AdminController@show')->name('admin.show');
Route::get('admin/reports', 'AdminController@reports')->name('admin.reports');
Route::get('admin/promotions', 'AdminController@promotions')->name('admin.promotions');
Route::get('admin/bans', 'AdminController@bans')->name('admin.bans');
Route::post('promotions/promote/{id}', 'AdminController@promote')->name('admin.promote');
Route::post('promotions/demote/{id}', 'AdminController@demote')->name('admin.demote');
Route::post('bans/ban/{id}', 'AdminController@ban')->name('admin.ban');
Route::post('bans/unban/{id}', 'AdminController@unban')->name('admin.unban');

// Report
Route::get('event/{id}/report', 'ReportController@index')->name('report.show');
Route::post('event/{id}/reportEvent', 'ReportController@reportEvent')->name('event.report');
Route::post('event/{id}/reportComment', 'ReportController@reportComment')->name('reportComment');

// Authentication

Route::get('login', 'Auth\LoginController@showLoginForm')->name('login');
Route::post('login', 'Auth\LoginController@login');
Route::get('logout', 'Auth\LoginController@logout')->name('logout');
Route::get('register', 'Auth\RegisterController@showRegistrationForm')->name('register');
Route::post('register', 'Auth\RegisterController@register');
Route::get('recover', 'UserController@recoverform')->name('password.recover.form');
Route::post('recover', 'UserController@recover')->name( 'password.recover');
Route::get('{id}/reset', 'UserController@resetform')->name('password.reset.form');
Route::post('{id}/reset', 'UserController@reset')->name('password.reset');

// AJAX
Route::get('city/ajax/', 'CountryController@cities')->name('cities.get');
Route::post('events/filter', 'EventController@filter')->name('events.filter');

// PayPal
Route::post('/paypal/pay', 'PaymentController@payWithPayPal')->name('paypal.pay');
Route::get('/paypal/status/{event}/{user}', 'PaymentController@payPalStatus')->name('paypal.status');