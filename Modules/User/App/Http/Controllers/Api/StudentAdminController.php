<?php

namespace Modules\User\App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Modules\User\DTO\UserDto;
use App\Imports\StudentsImport;
use Illuminate\Http\JsonResponse;
use Modules\User\App\Models\User;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;
use Modules\User\Service\UserService;
use Modules\User\App\Http\Requests\UserRequest;
use Modules\User\App\Http\Requests\UserImportRequest;

class StudentAdminController extends Controller
{
    public function __construct(private UserService $userService)
    {
        $this->middleware('auth:user');
        $this->middleware('role:Super Admin|Trainer|Instructor|Academy');
    }
// public function index(Request $request): JsonResponse
// {
//     $data = $request->all();
    
//     // إضافة 'academy' إلى مصفوفة العلاقات المراد تحميلها
//     $students = $this->userService->findAll($data, ['enrollments', 'academy'], 'Student');
    
//     return returnMessage(true, 'Students Fetched Successfully.', $students);
// }


public function index(Request $request): JsonResponse
{
    $query = User::with(['enrollments', 'academy'])
        ->role('Student');

    if (auth()->user()->hasRole('Academy')) {
        $query->where('academy_id', auth()->id());
    }

    $students = $query->get();

    return returnMessage(true, 'Students Fetched Successfully.', $students);
}
    public function store(UserRequest $request): JsonResponse
    {
        $data = (new UserDto($request))->dataFromRequest();
        $data['role'] = 'Student';
        if (auth()->user()->hasRole('Academy')) {
        $data['academy_id'] = auth()->id();
    }
        $student = $this->userService->save($data);
        return returnMessage(true, 'Student Created Successfully.', $student);
    }
// في Controller الخاص بالطلاب
public function update(UserRequest $request, User $user): JsonResponse
{
    // تحقق إضافي: هل الطالب ينتمي لهذه الأكاديمية؟
    if (auth()->user()->hasRole('Academy') && $user->academy_id !== auth()->id()) {
        return returnMessage(false, 'Unauthorized', null, 'forbidden');
    }
    
    $data = (new UserDto($request))->dataFromRequest();
    $user = $this->userService->update($user, $data);
    return returnMessage(true, 'Student Updated Successfully.', $user);
}
    // public function update(UserRequest $request, User $user): JsonResponse
    // {
    //     $data = (new UserDto($request))->dataFromRequest();
    //     $user = $this->userService->update($user, $data);
    //     return returnMessage(true, 'Student Updated Successfully.', $user);
    // }

    public function import(UserImportRequest $request)
    {
        try {
            $import = new StudentsImport();
            Excel::import($import, $request->file('file'));
            if (!empty($import->getFailures()))
                return returnValidationMessage(false, 'Validation Errors', $import->getFailures());
            return returnMessage(true, 'Students Imported Successfully', null);
        } catch (\Exception $e) {
            return returnMessage(false, $e->getMessage(), null, 'server_error');
        }
    }


        // عرض أكاديمية واحدة بالتفصيل
    public function show($id)
    {
        try {
$student = \Modules\User\App\Models\User::find($id); 
    
    if (!$student) {
        return response()->json([
            'error' => 'Student not found',
            'id_searched' => $id,
            'namespace' => 'Modules\User\App\Models\User'
        ], 404);
    }
            return returnMessage(true, 'Student Fetched Successfully.', $student);
        } catch (\Exception $e) {
            return returnMessage(false, $e->getMessage(), null, 'server_error');
        }
    }




    public function destroy($id)
{
    $student = User::findOrFail($id);

    $student->delete();

    return response()->json([
        'status' => true,
        'message' => 'student deleted successfully'
    ]);
}
}
