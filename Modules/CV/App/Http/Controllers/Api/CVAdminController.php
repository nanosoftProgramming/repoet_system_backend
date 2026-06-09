<?php

namespace Modules\CV\App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\CV\Service\CVService;

class CVAdminController extends Controller
{
    public function __construct(private CVService $service)
    {
        $this->middleware('auth:user');
        $this->middleware('role:Super Admin');
    }

    /**
     * Get all CVs with user information and answers
     */
    public function index(Request $request)
    {
        try {
            $cvs = $this->service->findAll($request->all(), ['user']);

            return returnMessage(true, 'CVs Fetched Successfully.', $cvs);
        } catch (\Exception $e) {
            return returnMessage(false, $e->getMessage(), null, 'server_error');
        }
    }

    /**
     * Get a specific CV with detailed answers
     */
    public function show($id)
    {
        try {
            $cv = $this->service->findById($id);

            return returnMessage(true, 'CV Fetched Successfully.', $cv);
        } catch (\Exception $e) {
            return returnMessage(false, $e->getMessage(), null, 'server_error');
        }
    }
}
