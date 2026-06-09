<?php

namespace Modules\Course\Service;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\File;
use Modules\Common\Helpers\UploadHelper;
use Modules\Course\App\Models\Course;
use Modules\Course\App\Models\CourseNote;

class CourseNoteService
{
    use UploadHelper;

    public function findAll(array $data = [], array $relations = []): Collection
    {
        $query = CourseNote::query()->available()->with($relations)->latest();

        return getCaseCollection($query, $data);
    }

    public function save(Course $course, $data): Course
    {
        if (request()->hasFile('file')) {
            $data['file'] = $this->uploadFile(request()->file('file'), 'course/notes');
        }
        $data['trainer_id'] = auth('user')->id();
        $course->notes()->create($data);

        return $course->fresh('notes');
    }

    public function update(Course $course, CourseNote $note, $data): Course
    {
        if (request()->hasFile('file')) {
            if ($note->file) {
                File::delete(public_path('uploads/course/notes/' . $this->getImageName('course/notes', $note->file)));
            }
            $data['file'] = $this->uploadFile(request()->file('file'), 'course/notes');
        }
        $note->update($data);

        return $course->fresh('notes');
    }

    public function delete(CourseNote $note): void
    {
        if ($note->file) {
            File::delete(public_path('uploads/course/notes/' . $this->getImageName('course/notes', $note->file)));
        }
        $note->delete();
    }
}
