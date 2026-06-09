<?php

namespace Modules\Enrollment\Service;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\File;
use Modules\Common\Helpers\UploadHelper;
use Modules\Enrollment\App\Models\Enrollment;
use Modules\Enrollment\App\Models\EnrollmentNote;

class EnrollmentNoteService
{
    use UploadHelper;

    public function findAll(array $data = [], array $relations = []): Collection
    {
        $query = EnrollmentNote::query()->available()->with($relations)->latest();
        return getCaseCollection($query, $data);
    }

    function save(Enrollment $enrollment, $data): Enrollment
    {
        if (request()->hasFile('file')) {
            $data['file'] = $this->uploadFile(request()->file('file'), 'enrollment/student/notes');
        }
        $enrollment->notes()->create($data);
        return $enrollment->fresh('notes');
    }

    function update(Enrollment $enrollment, EnrollmentNote $note, $data): Enrollment
    {
        if (request()->hasFile('file')) {
            if ($note->file) {
                File::delete(public_path('uploads/enrollment/student/notes/' . $this->getImageName('enrollment/student/notes', $note->file)));
            }
            $data['file'] = $this->uploadFile(request()->file('file'), 'enrollment/student/notes');
        }
        $note->update($data);
        return $enrollment->fresh('notes');
    }
}
