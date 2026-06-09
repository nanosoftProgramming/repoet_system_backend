<?php

namespace Modules\User\App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Imports\InstructorsImport;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Modules\User\App\Http\Requests\UserImportRequest;
use Modules\User\App\Http\Requests\UserRequest;
use Modules\User\App\Models\User;
use Modules\User\DTO\UserDto;
use Modules\User\Service\UserService;

class InstructorAdminController extends Controller
{
    public function __construct(private UserService $userService)
    {
         $this->middleware('auth:user')->except('index');
        $this->middleware('role:Super Admin')->except('index');
        $this->userService = $userService;
    }

    public function index(Request $request): JsonResponse
    {
        $data = $request->all();
        $instructors = $this->userService->findAll($data, [], 'Instructor');

        return returnMessage(true, 'Instructors Fetched Successfully.', $instructors);
    }

    public function store(UserRequest $request): JsonResponse
    {
        $data = (new UserDto($request))->dataFromRequest();
        $data['role'] = 'Instructor';
        $instructor = $this->userService->save($data);

        return returnMessage(true, 'Instructor Created Successfully.', $instructor);
    }

    public function update(UserRequest $request, User $user): JsonResponse
    {
        $data = (new UserDto($request))->dataFromRequest();
        $user = $this->userService->update($user, $data);

        return returnMessage(true, 'Instructor Updated Successfully.', $user);
    }

    public function import(UserImportRequest $request)
    {
        try {
            $import = new InstructorsImport;
            Excel::import($import, $request->file('file'));
            if (! empty($import->getFailures())) {
                return returnValidationMessage(false, 'Validation Errors', $import->getFailures());
            }

            return returnMessage(true, 'Instructors Imported Successfully', null);
        } catch (\Exception $e) {
            return returnMessage(false, $e->getMessage(), null, 'server_error');
        }
    }
}
