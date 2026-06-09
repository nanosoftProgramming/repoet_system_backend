<?php

namespace Modules\CV\App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Modules\CV\App\Http\Requests\CVRequest;
use Modules\CV\DTO\CVDto;
use Modules\CV\Service\CVService;

class CVController extends Controller
{
    public function __construct(private CVService $service)
    {
        $this->middleware('auth:user');
    }

    public function show()
    {
        try {
            $user = auth('user')->user();
            $formData = $this->service->getCVFormData($user);

            return returnMessage(true, 'CV Form Fetched Successfully.', $formData);
        } catch (\Exception $e) {
            return returnMessage(false, $e->getMessage(), null, 'server_error');
        }
    }

    public function store(CVRequest $request)
    {
        try {
            DB::beginTransaction();
            $data = (new CVDto($request))->dataFromRequest();
            $this->service->save($data);
            $user = auth('user')->user();
            $formData = $this->service->getCVFormData($user);
            DB::commit();

            return returnMessage(true, 'CV Saved Successfully.', $formData);
        } catch (\Exception $e) {
            DB::rollBack();

            return returnMessage(false, $e->getMessage(), null, 'server_error');
        }
    }

    public function update(CVRequest $request)
    {
        try {
            DB::beginTransaction();
            $data = (new CVDto($request))->dataFromRequest();
            $this->service->save($data);
            $user = auth('user')->user();
            $formData = $this->service->getCVFormData($user);
            DB::commit();

            return returnMessage(true, 'CV Updated Successfully.', $formData);
        } catch (\Exception $e) {
            DB::rollBack();

            return returnMessage(false, $e->getMessage(), null, 'server_error');
        }
    }
}
