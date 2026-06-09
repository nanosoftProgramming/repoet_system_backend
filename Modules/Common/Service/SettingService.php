<?php

namespace Modules\Common\Service;

use Modules\Common\App\Models\Setting;

class SettingService
{
    public function findAll()
    {
        $settings = Setting::all();
        return $this->formatSettings($settings);
    }

    public function update(array $data)
    {
        foreach ($data as $key => $value) {
            Setting::where('key', $key)->update(['value' => $value]);
        }

        return $this->findAll();
    }

    public function findByKeys($keys)
    {
        $settings = Setting::whereIn('key', $keys)->get();
        return $this->formatSettings($settings);
    }

    private function formatSettings($settings)
    {
        $formattedSettings = [];

        foreach ($settings as $setting) {
            $formattedSettings[$setting->key] = $setting->value;
        }

        return $formattedSettings;
    }
}
