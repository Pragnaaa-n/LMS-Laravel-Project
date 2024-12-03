<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\RoleshavePermissionController;
use App\Http\Controllers\ExamTypeController;
use  App\Http\Controllers\CategoryController;
use App\Http\Controllers\SubCategoryController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\StudentSettingsController;
use App\Http\Controllers\ForgetPasswordController;
use App\Http\Controllers\ResetPasswordController;
use App\Http\Controllers\EnquiryController;
use App\Http\Controllers\TestTypeController;
use App\Http\Controllers\TestController;
use App\Http\Controllers\QuestionController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::group(['prefix' => 'student'], function ($router) {
Route::post('/register', [StudentController::class, 'register']);
Route::post('/login', [StudentController::class, 'login']);
Route::put('update/{id}', [StudentController::class, 'update']);

});

Route::post('password/email',[ForgetPasswordController::class, 'sendResetLinkEmail']);
Route::post('password/reset',[ResetPasswordController::class, 'reset'])->name('password.reset');
Route::get('students/{id}', [StudentController::class, 'getStudentById']);
Route::delete('delete_student_by_id/{id}', [StudentController::class, 'deleteStudentById']);


Route::group(['middleware' => ['jwt.auth', 'auth:student-api'], 'prefix' => 'student'], function () {
Route::post('refresh_token', [StudentController::class, 'refresh']);
Route::post('/logout', [StudentController::class, 'logout']);
    
    
});
Route::get('/user_profile', [StudentController::class, 'userProfile']);

Route::group(['prefix' => 'user'], function ($router) {
Route::post('/login', [UserController::class, 'login']);
Route::post('/register', [UserController::class, 'register']);


Route::get('/students', [StudentController::class, 'index']);

Route::get('/get_all_role', [RoleController::class, 'get_all_role'])->name('Get All Role');
Route::get('/get_role_by_id/{id}', [RoleController::class, 'get_role_by_id'])->name('Get by Role Id');
Route::get('/get_all_permission', [PermissionController::class, 'get_all_permission'])->name('Get all Permission');
Route::get('/get_permission_by_id/{id}', [PermissionController::class, 'get_permission_by_id'])->name('Get by Permission');
Route::get('/get_all_role_have_permission', [RoleshavePermissionController::class, 'get_all_role_have_permission'])->name('Get all Permission to Roles');
Route::get('/get_role_have_permission_by_id/{id}', [RoleshavePermissionController::class, 'get_role_have_permission_by_id'])->name('Get Permission to Roles by Id');
Route::get('/get_all_exam_type', [ExamTypeController::class, 'get_all_exam_type'])->name('Get All Exam Type');
Route::get('/get_exam_type_by_id/{id}', [ExamTypeController::class, 'get_exam_type_by_id'])->name('Get Exam Type by Id');
Route::get('/get_all_categories', [CategoryController::class, 'get_all_categories'])->name('Get All Categories');
Route::get('/get_category_by_id/{id}', [CategoryController::class, 'get_category_by_id'])->name('Get Category by Id');
Route::get('/get_categories_by_exam_id/{exam_type_id}', [CategoryController::class, 'getCategoriesByExamId'])->name('Get Categories by Exam_Id');
Route::get('/get_all_sub_categories', [SubCategoryController::class, 'get_all_sub_categories'])->name('Get All Sub Categories');
Route::get('/get_sub_category_by_id/{id}', [SubCategoryController::class, 'get_sub_category_by_id'])->name('Get Sub Category by Id');
Route::get('/get_sub_categories_by_category_id/{category_id}', [SubCategoryController::class, 'getSubCategoriesByCategoryId'])->name('Get Sub Categories by Category_Id');
Route::get('/get_all_courses', [CourseController::class, 'get_all_courses'])->name('Get All Courses');
Route::get('/get_courses_by_id/{id}', [CourseController::class, 'get_courses_by_id'])->name('Get Course by Id');
Route::get('/get_courses_by_exam_id/{exam_id}', [CourseController::class, 'get_courses_by_exam_id'])->name('Get Courses by Exam Id');
Route::get('/get_all_settings', [SettingsController::class, 'get_all_settings'])->name('Get All Settings');
Route::get('/get_settings_by_id/{id}', [SettingsController::class, 'get_settings_by_id'])->name('Get Settings by Id');
Route::get('/get_all_order', [OrderController::class, 'get_all_order'])->name('Get All Orders');
Route::get('/get_order_by_id/{id}', [OrderController::class, 'get_order_by_id'])->name('Get Orders by Id');
Route::get('/get_all_student_settings', [StudentSettingsController::class, 'get_all_student_settings'])->name('Get All Student Settings');
Route::get('/get_student_settings_by_id/{id}', [StudentSettingsController::class, 'get_student_settings_by_id'])->name('Get Student Settings by Id');
Route::post('/save_Enquiry', [EnquiryController::class, 'save_Enquiry'])->name('Add Enquiry');
Route::delete('/delete_Enquiry_by_id/{id}', [EnquiryController::class, 'delete_Enquiry_by_id'])->name('Delete by Enquiry');
Route::get('/get_Enquiry_by_id/{id}', [EnquiryController::class, 'get_Enquiry_by_id'])->name('Get Enquiry by Id');
Route::get('/get_all_Enquiry', [EnquiryController::class, 'get_all_Enquiry'])->name('Get all Enquiry ');
Route::get('/get_all_test_type', [TestTypeController::class, 'get_all_test_type'])->name('Get All Test Type');
Route::get('/get_test_type_by_id/{id}', [TestTypeController::class, 'get_test_type_by_id'])->name('Get by Test Type Id');
Route::get('/get_all_tests', [TestController::class, 'get_all_tests'])->name('Get All Tests');
Route::get('/get_test_by_id/{id}', [TestController::class, 'get_test_by_id'])->name('Get by Test Id');
Route::get('/get_all_questions', [QuestionController::class, 'get_all_questions'])->name('Get All Questions');

Route::get('/get_question_by_id/{id}', [QuestionController::class, 'get_question_by_id'])->name('Get Questions by Id');
Route::get('update_status_by_id/{id}', [TestController::class, 'updateStatusById'])->name('Update Status by Id');
Route::get('/get_tests_by_test_type/{test_type_id}', [TestController::class, 'get_tests_by_test_type'])->name('Get by Test by Test Type Id');
Route::get('/get_questions_by_test_type_id/{test_type_id}', [QuestionController::class, 'get_questions_by_test_type_id'])->name('Get Questions by Test Type Id');

});


// Route::group([
//     'middleware' => ['jwt.auth', 'jwt.role:user', 'role.permission'],
//     'prefix' => 'user'
// ], function () {


Route::group(['middleware' => ['jwt.auth', 'jwt.role:user'], 'prefix' => 'user'], function () {
Route::post('/logout', [UserController::class, 'logout']);
Route::get('/user_profile', [UserController::class, 'userProfile']);
Route::put('/update/{id}', [UserController::class, 'update']);
Route::get('/get_by_id/{id}', [UserController::class, 'get_by_id']);




// Roles
Route::post('/save_role', [RoleController::class, 'save_role'])->name('Add Role');
Route::put('/update_role_by_id/{id}', [RoleController::class, 'update_role_by_id'])->name('Update Role');
Route::delete('/delete_role_by_id/{id}', [RoleController::class, 'delete_role_by_id'])->name('Delete Role');



// Permission
Route::post('/save_permission', [PermissionController::class, 'save_permission'])->name('Add Permission');
Route::put('/update_permission_by_id/{id}', [PermissionController::class, 'update_permission_by_id'])->name('Update Permission');
Route::delete('/delete_permission_by_id/{id}', [PermissionController::class, 'delete_permission_by_id'])->name('Delete by Permission');



// Roles have Permission
Route::post('/assign_role_have_permission', [RoleshavePermissionController::class, 'assign_role_have_permission'])->name('Assign Permission to Role');
Route::put('/update_role_have_permission_by_id/{id}', [RoleshavePermissionController::class, 'update_role_have_permission_by_id'])->name('Update Permission to Role by Id');
Route::delete('/delete_role_have_permission_by_id/{id}', [RoleshavePermissionController::class, 'delete_role_have_permission_by_id'])->name('Delete Permission to Role by Id');



// Exam Type
Route::post('/save_exam_type', [ExamTypeController::class, 'save_exam_type'])->name('Add Exam Type');
Route::put('/update_exam_type_by_id/{id}', [ExamTypeController::class, 'update_exam_type_by_id'])->name('Update Exam Type by Id');
Route::delete('/delete_exam_type_by_id/{id}', [ExamTypeController::class, 'delete_exam_type_by_id'])->name('Delete Exam Type by Id');



// Category
Route::post('/save_category', [CategoryController::class, 'save_category'])->name('Add Category');
Route::put('/update_category_by_id/{id}', [CategoryController::class, 'update_category_by_id'])->name('Update Category by Id');
Route::delete('/delete_category_by_id/{id}', [CategoryController::class, 'delete_category_by_id'])->name('Delete Category by Id');



// Sub Category
Route::post('/save_sub_category', [SubCategoryController::class, 'save_sub_category'])->name('Add Sub Category');
Route::put('/update_sub_category_by_id/{id}', [SubCategoryController::class, 'update_sub_category_by_id'])->name('Update Sub Category by Id');
Route::delete('/delete_sub_category_by_id/{id}', [SubCategoryController::class, 'delete_sub_category_by_id'])->name('Delete Sub Category by Id');



// Courses
Route::post('/save_course', [CourseController::class, 'save_course'])->name('Add Course');
Route::put('/update_course_id/{id}', [CourseController::class, 'update_course_id'])->name('Update Course by Id');
Route::delete('/delete_course_by_id/{id}', [CourseController::class, 'delete_course_by_id'])->name('Delete Course by Id');



// Settings
Route::post('/save_settings', [SettingsController::class, 'save_settings'])->name('Add Settings');
Route::put('/update_settings_by_id/{id}', [SettingsController::class, 'update_settings_by_id'])->name('Update Settings by Id');
Route::delete('/delete_setting_by_id/{id}', [SettingsController::class, 'delete_setting_by_id'])->name('Delete Settings by Id');



// Orders
Route::post('/save_orders', [OrderController::class, 'save_orders'])->name('Add Orders');
Route::put('/update_order_by_id/{id}', [OrderController::class, 'update_order_by_id'])->name('Update Orders by Id');
Route::delete('/delete_order_by_id/{id}', [OrderController::class, 'delete_order_by_id'])->name('Delete Orders by Id');


// Student Settings
Route::post('/save_student_settings', [StudentSettingsController::class, 'save_student_settings'])->name('Add Student Settings');
Route::put('/update_student_settings_by_id/{id}', [StudentSettingsController::class, 'update_student_settings_by_id'])->name('Update Student Settings by Id');
Route::delete('/delete_student_setting_by_id/{id}', [StudentSettingsController::class, 'delete_student_setting_by_id'])->name('Delete Student Settings by Id');


// Test Types
Route::post('/save_test_type', [TestTypeController::class, 'save_test_type'])->name('Add Test Type');
Route::put('/update_test_type_by_id/{id}', [TestTypeController::class, 'update_test_type_by_id'])->name('Update Test Type by Id');
Route::delete('/delete_test_type_by_id/{id}', [TestTypeController::class, 'delete_test_type_by_id'])->name('Delete Test Type by Id');


// Test
Route::post('/save_test', [TestController::class, 'save_test'])->name('Add Test ');
Route::put('/update_test_by_id/{id}', [TestController::class, 'update_test_by_id'])->name('Update Test by Id');
Route::delete('/delete_test_by_id/{id}', [TestController::class, 'delete_test_by_id'])->name('Delete Test by Id');


// Question
Route::post('/save_question', [QuestionController::class, 'save_question'])->name('Add Question');
Route::put('/update_question_by_id/{id}', [QuestionController::class, 'update_question_by_id'])->name('Update Question by Id');
Route::delete('/delete_question_by_id/{id}', [QuestionController::class, 'delete_question_by_id'])->name('Delete Question by Id');
});




