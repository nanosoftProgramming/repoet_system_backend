<?php

namespace Modules\User\App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\User\DTO\UserDto;
use Modules\User\App\Http\Requests\UserRequest;
use Modules\User\Service\UserService;
use App\Models\Academy; // تأكدي أن هذا هو المسار الصحيح بناءً على مكان الموديل

use Illuminate\Http\JsonResponse;
use Modules\User\App\Models\User;
use Maatwebsite\Excel\Facades\Excel;
use Modules\User\App\Http\Requests\UserImportRequest;

class AcademyController extends Controller
{
    public function __construct(private UserService $userService)
    {
        $this->middleware('auth:user');
$this->middleware('role:Super Admin|Trainer|Academy');
    }
    // عرض جميع الأكاديميات

    // public function index()
    // {
    //     return Academy::all();
    // }
    public function index(Request $request): JsonResponse
    {
        $data = $request->all();
        $academies = $this->userService->findAll($data,[], 'Academy');
        return returnMessage(true, 'Academies Fetched Successfully.', $academies);
    }
    // إضافة أكاديمية جديدة
    // public function store(Request $request)
    // {
    //     $validated = $request->validate([
    //         'name' => 'required|string|max:255',
    //     ]);

    //     $academy = Academy::create($validated);
    //     return response()->json($academy, 201);
    // }


        public function store(UserRequest $request): JsonResponse
    {
        $data = (new UserDto($request))->dataFromRequest();
        $data['role'] = 'Academy';
        $aca = $this->userService->save($data);
        return returnMessage(true, 'Academy Created Successfully.', $aca);
    }


    // عرض أكاديمية واحدة بالتفصيل
    public function show($id)
    {
        try {
$academy = \Modules\User\App\Models\User::find($id); 
    
    if (!$academy) {
        return response()->json([
            'error' => 'Academy not found',
            'id_searched' => $id,
            'namespace' => 'Modules\User\App\Models\User'
        ], 404);
    }
            return returnMessage(true, 'Academy Fetched Successfully.', $academy);
        } catch (\Exception $e) {
            return returnMessage(false, $e->getMessage(), null, 'server_error');
        }
    }

    // تحديث بيانات أكاديمية
    // public function update(Request $request, $id)
    // {
    //     $academy = Academy::findOrFail($id);
        
    //     $validated = $request->validate([
    //         'name' => 'sometimes|string|max:255',
    //     ]);

    //     $academy->update($validated);
    //     return response()->json($academy, 200);
    // }

        public function update(UserRequest $request, User $user): JsonResponse
    {
        $data = (new UserDto($request))->dataFromRequest();
        $user = $this->userService->update($user, $data);
        return returnMessage(true, 'Academy Updated Successfully.', $user);
    }

    // حذف أكاديمية
public function destroy($id)
{
    $academy = User::findOrFail($id);

    $academy->delete();

    return response()->json([
        'status' => true,
        'message' => 'Academy deleted successfully'
    ]);
}
}