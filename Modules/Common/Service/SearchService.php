<?php

namespace Modules\Common\Service;

use Modules\User\App\Models\User;
use Illuminate\Support\Collection;
use Modules\Course\App\Models\Course;

class SearchService
{
    public function searchEverything(string $query)
    {
        $results = collect();

        $courses = $this->searchCourses($query);
        $results = $results->merge($courses);

        $students = $this->searchStudents($query);
        $results = $results->merge($students);

        $trainers = $this->searchTrainers($query);
        $results = $results->merge($trainers);

        $instructors = $this->searchInstructors($query);
        $results = $results->merge($instructors);

        return $results;
    }

    public function search(string $query)
    {
        $results = collect();

        $courses = $this->searchCourses($query);
        $results = $results->merge($courses);

        $trainers = $this->searchTrainers($query);
        $results = $results->merge($trainers);

        $instructors = $this->searchInstructors($query);
        $results = $results->merge($instructors);

        return $results;
    }

    private function searchCourses(string $query)
    {
        return Course::query()
            ->with('trainer')
            ->where(function ($q) use ($query) {
                $q->where('title', 'like', "%{$query}%");
            })
            ->when(!auth('user')->check() || !auth('user')->user()->hasRole('Super Admin'), function ($query) {
                $query->active();
            })
            ->get()
            ->map(function ($item) {
                $item->type = 'course';

                return $item;
            });
    }

    private function searchStudents(string $query): Collection
    {
        return User::query()
            ->with('enrollments')
            ->where('role', 'Student')
            ->where(function ($q) use ($query) {
                $q->where('name', 'like', "%{$query}%")
                    ->orWhere('email', 'like', "%{$query}%")
                    ->orWhere('phone', 'like', "%{$query}%")
                    ->orWhere('username', 'like', "%{$query}%")
                    ->orWhere('identity_number', 'like', "%{$query}%")
                    ->orWhere('national_number', 'like', "%{$query}%");
            })
            ->when(!auth('user')->check() || !auth('user')->user()->hasRole('Super Admin'), function ($query) {
                $query->active();
            })
            ->get()
            ->map(function ($item) {
                $item->type = 'student';

                return $item;
            });
    }

    private function searchTrainers(string $query)
    {
        return User::query()
            ->where('role', 'Trainer')
            ->where(function ($q) use ($query) {
                $q->where('name', 'like', "%{$query}%");
            })
            ->when(!auth('user')->check() || !auth('user')->user()->hasRole('Super Admin'), function ($query) {
                $query->active();
            })
            ->get()
            ->map(function ($item) {
                $item->type = 'trainer';

                return $item;
            });
    }

    private function searchInstructors(string $query)
    {
        return User::query()
            ->where('role', 'Instructor')
            ->where(function ($q) use ($query) {
                $q->where('name', 'like', "%{$query}%");
            })
            ->when(!auth('user')->check() || !auth('user')->user()->hasRole('Super Admin'), function ($query) {
                $query->active();
            })
            ->get()
            ->map(function ($item) {
                $item->type = 'instructor';

                return $item;
            });
    }
}
