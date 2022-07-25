<?php

use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\AttendanceRecordController;
use App\Http\Controllers\AttendanceTypeController;
use App\Http\Controllers\ClassController;
use App\Http\Controllers\ImageUploadController;
use App\Http\Controllers\ProfileTestController;
use App\Models\Classes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SectionController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\FilterController;



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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
Route::get('/blogs', [
    BlogController::class, 'index'
]);
Route::get("admin", [UserController::class, 'create']);
Route::post('/register', ['App\Http\Controllers\AuthController', 'register']);

Route::get('/blogs', ['App\Http\Controllers\BlogController', 'index']);
Route::get('/admin_email/{email}',['App\Http\Controllers\AuthController', 'show']);
Route::get('/getAdminID/{id}',['App\Http\Controllers\AuthController', 'getAdminID']);
Route::post('/login', ['App\Http\Controllers\AuthController', 'login']);
Route::get('/getAdmin', ['App\Http\Controllers\AuthController', 'index']);
Route::put('/updateAdmin/{id}', ['App\Http\Controllers\AuthController', 'update']);
Route::delete('/deleteAdmin/{id}', ['App\Http\Controllers\AuthController', 'destroy']);




Route::resource('profile','App\Http\Controllers\ProfileController')->only(['index','store','show']);
Route::resource('student','App\Http\Controllers\StudentController')->only(['index','store','show']);
Route::post('/getStudent/{id}', ['App\Http\Controllers\StudentController', 'registerStudents']);

Route::group(['middleware' => ['jwt.verify']], function() {

    Route::post('/logout', [AuthController::class, 'logout']);

});
Route::get('/test/{class_id}', [SectionController::class, 'test']);

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::resource('classes','App\Http\Controllers\ClassController')->only(['index','store',
    'show', 'update', 'destroy']);
Route::get('/count-classes', ['App\Http\Controllers\ClassController', 'countClasses']);

Route::resource('sections','App\Http\Controllers\SectionController')->only(['index','store',
    'show', 'update', 'destroy']);
Route::get('/count-sections', ['App\Http\Controllers\SectionController', 'countSections']);

Route::get('/getClass/{id}',['App\Http\Controllers\ClassController', 'getSectionByClassID']);

Route::get('image-upload', [ ImageUploadController::class, 'imageUpload' ])->name('image.upload');
Route::post('image-upload', [ ImageUploadController::class, 'imageUploadPost' ])->name('image.upload.post');
Route::resource('profiles',ProfileController::class)->only(['index','store','show', 'update', 'destroy']);


Route::resource('attendance-type',AttendanceTypeController::class)->only(['index','store','show', 'update', 'destroy']);
Route::resource('attendance-record',AttendanceRecordController::class)->only(['index','store','show', 'update', 'destroy']);
Route::put('/attendance-record-update/{id}', ['App\Http\Controllers\AttendanceRecordController', 'update']);

Route::resource('attendance',AttendanceController::class)->only(['index','store','show', 'destroy']);
Route::get('/profiles/filter/{name}', ['App\Http\Controllers\ProfileController', 'filterStudents']);
Route::get('/count-students', [ProfileController::class, 'countStudents']);
Route::get('/count-student-records', [AttendanceRecordController::class, 'countRecordOfStudent']);

Route::get('/count-all', [ClassController::class, 'countAll']);

Route::resource('filter',FilterController::class)->only(['index','store','show', 'update', 'destroy']);
