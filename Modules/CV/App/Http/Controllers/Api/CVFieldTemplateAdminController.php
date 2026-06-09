<?php

namespace Modules\CV\App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Modules\CV\App\Http\Requests\CVFieldTemplateRequest;
use Modules\CV\App\Models\CVFieldTemplate;
use Modules\CV\Service\CVFieldTemplateService;

class CVFieldTemplateAdminController extends Controller
{
    public function __construct(private CVFieldTemplateService $service)
    {
        $this->middleware('auth:user');
        $this->middleware('role:Super Admin');
    }

    public function index(Request $request)
    {
        $templates = $this->service->findAll($request->all());

        return returnMessage(true, 'CV Field Templates Fetched Successfully.', $templates);
    }

    public function store(CVFieldTemplateRequest $request)
    {
        try {
            DB::beginTransaction();
            $role = $request->get('role');
            $templates = $request->get('templates');
            $createdTemplates = $this->service->save($role, $templates);
            DB::commit();

            return returnMessage(true, 'CV Field Templates Created Successfully.', $createdTemplates);
        } catch (\Exception $e) {
            DB::rollBack();

            return returnMessage(false, $e->getMessage(), null, 'server_error');
        }
    }

    public function update(CVFieldTemplateRequest $request)
    {
        try {
            DB::beginTransaction();
            $role = $request->get('role');
            $templates = $request->get('templates');
            $result = $this->service->update($role, $templates);
            DB::commit();

            return returnMessage(true, 'CV Field Templates Updated Successfully.', $result);
        } catch (\Exception $e) {
            DB::rollBack();

            return returnMessage(false, $e->getMessage(), null, 'server_error');
        }
    }

    public function destroy(CVFieldTemplate $cvFieldTemplate)
    {
        try {
            DB::beginTransaction();
            $this->service->delete($cvFieldTemplate);
            DB::commit();

            return returnMessage(true, 'CV Field Template Deleted Successfully.');
        } catch (\Exception $e) {
            DB::rollBack();

            return returnMessage(false, $e->getMessage(), null, 'server_error');
        }
    }

    public function toggleActivate(CVFieldTemplate $cvFieldTemplate)
    {
        try {
            DB::beginTransaction();
            $template = $this->service->toggleActivate($cvFieldTemplate);
            DB::commit();

            return returnMessage(true, 'CV Field Template Updated Successfully', $template);
        } catch (\Exception $e) {
            DB::rollBack();

            return returnMessage(false, $e->getMessage(), null, 'server_error');
        }
    }
}
