<?php

namespace Modules\Enrollment\Service;

use Modules\Common\Helpers\UploadHelper;
use Modules\Enrollment\App\Models\Enrollment;
use Illuminate\Support\Facades\File;

class EnrollmentService
{
    use UploadHelper;
    function findAll($data, $relations = [])
    {
        $query = Enrollment::with($relations)
        ->available()->filter($data)->latest();
        return getCaseCollection($query, $data);

    }

    function save($data)
    {
        $data['student_id'] = auth('user')->id();
        return Enrollment::create($data);
    }


}
