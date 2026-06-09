<?php

namespace Modules\User\App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Modules\User\DTO\UserDto;
use App\Imports\TrainersImport;
use Illuminate\Http\JsonResponse;
use Modules\User\App\Models\User;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;
use Modules\User\App\Http\Requests\UserImportRequest;
use Modules\User\Service\UserService;
use Modules\User\App\Http\Requests\UserRequest;

class TrainerAdminController extends Controller
{
    public function __construct(private UserService $userService)
    {
        $this->middleware('auth:user');
        $this->middleware('role:Super Admin');
        $this->userService = $userService;
    }
    public function index(Request $request): JsonResponse
    {
        $data = $request->all();
        $trainers = $this->userService->findAll($data, ['courses'], 'Trainer');
        return returnMessage(true, 'Trainers Fetched Successfully.', $trainers);
    }
    public function store(UserRequest $request): JsonResponse
    {
        $data = (new UserDto($request))->dataFromRequest();
        $data['role'] = 'Trainer';
        $trainer = $this->userService->save($data);
        return returnMessage(true, 'Trainer Created Successfully.', $trainer);
    }

    public function update(UserRequest $request, User $user): JsonResponse
    {
        $data = (new UserDto($request))->dataFromRequest();
        $user = $this->userService->update($user, $data);
        return returnMessage(true, 'Trainer Updated Successfully.', $user);
    }

    public function import(UserImportRequest $request)
    {
        try {
            $import = new TrainersImport();
            Excel::import($import, $request->file('file'));
            if (!empty($import->getFailures()))
                return returnValidationMessage(false, 'Validation Errors', $import->getFailures());
            return returnMessage(true, 'Trainers Imported Successfully', null);
        } catch (\Exception $e) {
            return returnMessage(false, $e->getMessage(), null, 'server_error');
        }
    }
}
