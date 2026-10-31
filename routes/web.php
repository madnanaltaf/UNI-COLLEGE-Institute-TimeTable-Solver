<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TimetableController;

Route::get('/', [TimetableController::class, 'dashboard'])->name('dashboard');
Route::post('/solve', [TimetableController::class, 'solve'])->name('solve');

// Interactive Timetable Explorer
Route::get('/timetable', [TimetableController::class, 'timetable'])->name('timetable');
Route::post('/slot/update', [TimetableController::class, 'updateSlot'])->name('slot.update');

// Curriculum Period Allotment Center
Route::get('/allotments', [TimetableController::class, 'allotments'])->name('allotments');
Route::post('/allotments', [TimetableController::class, 'storeAllotment'])->name('allotments.store');
Route::match(['POST', 'PUT'], '/allotments/{id}/update', [TimetableController::class, 'updateAllotment'])->name('allotments.update')->whereNumber('id');
Route::match(['POST', 'PUT'], '/allotments/{id}', [TimetableController::class, 'updateAllotment'])->name('allotments.update.put')->whereNumber('id');
Route::delete('/allotments/{id}', [TimetableController::class, 'deleteAllotment'])->name('allotments.delete')->whereNumber('id');

// Faculty Directory & Availability Matrix
Route::get('/faculty', [TimetableController::class, 'faculty'])->name('faculty');
Route::post('/faculty', [TimetableController::class, 'storeTeacher'])->name('faculty.store');
Route::post('/faculty/toggle-availability', [TimetableController::class, 'toggleAvailability'])->name('faculty.toggle');
Route::post('/faculty/preset', [TimetableController::class, 'applyAvailabilityPreset'])->name('faculty.preset');
Route::match(['POST', 'PUT'], '/faculty/{id}/update', [TimetableController::class, 'updateTeacher'])->name('faculty.update')->whereNumber('id');
Route::match(['POST', 'PUT'], '/faculty/{id}', [TimetableController::class, 'updateTeacher'])->name('faculty.update.put')->whereNumber('id');
Route::delete('/faculty/{id}', [TimetableController::class, 'deleteTeacher'])->name('faculty.delete')->whereNumber('id');

// Classes & Sections
Route::get('/classes', [TimetableController::class, 'classes'])->name('classes');
Route::post('/classes/incharge', [TimetableController::class, 'updateSectionIncharge'])->name('classes.incharge');

// Subjects Directory
Route::get('/subjects', [TimetableController::class, 'subjects'])->name('subjects');
Route::post('/subjects', [TimetableController::class, 'storeSubject'])->name('subjects.store');
Route::match(['POST', 'PUT'], '/subjects/{id}/update', [TimetableController::class, 'updateSubject'])->name('subjects.update')->whereNumber('id');
Route::match(['POST', 'PUT'], '/subjects/{id}', [TimetableController::class, 'updateSubject'])->name('subjects.update.put')->whereNumber('id');
Route::delete('/subjects/{id}', [TimetableController::class, 'deleteSubject'])->name('subjects.delete')->whereNumber('id');

// Classrooms & Laboratories
Route::get('/rooms', [TimetableController::class, 'rooms'])->name('rooms');
Route::post('/rooms', [TimetableController::class, 'storeRoom'])->name('rooms.store');
Route::match(['POST', 'PUT'], '/rooms/{id}/update', [TimetableController::class, 'updateRoom'])->name('rooms.update')->whereNumber('id');
Route::match(['POST', 'PUT'], '/rooms/{id}', [TimetableController::class, 'updateRoom'])->name('rooms.update.put')->whereNumber('id');
Route::delete('/rooms/{id}', [TimetableController::class, 'deleteRoom'])->name('rooms.delete')->whereNumber('id');

// Institution & Calendar
Route::get('/institution', [TimetableController::class, 'institution'])->name('institution');
Route::post('/institution', [TimetableController::class, 'updateInstitution'])->name('institution.update');

// Campus Duty Roster
Route::get('/duties', [TimetableController::class, 'duties'])->name('duties');
Route::post('/duties/optimize', [TimetableController::class, 'optimizeDuties'])->name('duties.optimize');

// Export Center
Route::get('/exports', [TimetableController::class, 'exports'])->name('exports');
Route::get('/exports/pdf/{type}', [TimetableController::class, 'downloadPdf'])->name('exports.pdf');
Route::get('/exports/csv/{type}', [TimetableController::class, 'downloadCsv'])->name('exports.csv');
Route::get('/exports/json', [TimetableController::class, 'downloadProjectJson'])->name('exports.json');
