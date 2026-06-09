<?php

namespace Modules\Common\App\Http\Controllers\Api;

use Exception;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Modules\Common\Service\SettingService;

class SettingController extends Controller
{
    protected $settingService;

    public function __construct(SettingService $settingService)
    {
        $this->middleware('auth:user')->except('index');
        $this->middleware('role:Super Admin')->except('index');
        $this->settingService = $settingService;
    }

    public function index()
    {
        try {
            $settings = $this->settingService->findAll();
            return returnMessage(true, 'Settings retrieved successfully', $settings);
        } catch (Exception $e) {
            return returnMessage(false, $e->getMessage(), null, 'server_error');
        }
    }

    public function update(Request $request)
    {
        try {
            $settings = $this->settingService->update($request->all());
            return returnMessage(true, 'Settings updated successfully', $settings);
        } catch (Exception $e) {
            return returnMessage(false, $e->getMessage(), null, 'server_error');
        }
    }
}
