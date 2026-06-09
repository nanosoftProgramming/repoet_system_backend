<?php

namespace Modules\Survey\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SurveyDatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $now = now();

        // Insert categories
        $categories = [
            ['name' => 'المادة العلمية', 'order' => 1, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'المدرب (المحاضر)', 'order' => 2, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'البيئة التدريبية', 'order' => 3, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'المتدربين', 'order' => 4, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'ملاحظات عامة', 'order' => 5, 'created_at' => $now, 'updated_at' => $now],
        ];

        DB::table('survey_question_categories')->insert($categories);

        // Get category IDs
        $categoryIds = DB::table('survey_question_categories')
            ->orderBy('order')
            ->pluck('id')
            ->toArray();

        // Insert questions
        $questions = [
            // المادة العلمية (Category 1)
            [
                'survey_question_category_id' => $categoryIds[0],
                'question' => 'مدى وضوح المادة التدريبية وأهداف البرنامج التدريبي.',
                'type' => 'rating',
                'order' => 1,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'survey_question_category_id' => $categoryIds[0],
                'question' => 'مدى تنظيم وتسلسل المحاور والمواضيع التدريبية.',
                'type' => 'rating',
                'order' => 2,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'survey_question_category_id' => $categoryIds[0],
                'question' => 'مدى تضمن المادة التدريبية للتمارين والانشطة التدريبية والامثلة الواقعية واتساقها مع واقع العمل.',
                'type' => 'rating',
                'order' => 3,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'survey_question_category_id' => $categoryIds[0],
                'question' => 'مدى مواءمة الفترة الزمنية وكفايتها للمقررات التدريبية.',
                'type' => 'rating',
                'order' => 4,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            // المدرب (Category 2)
            [
                'survey_question_category_id' => $categoryIds[1],
                'question' => 'الإلمام المعرفي لدى المدرب بمواضيع البرنامج التدريبي وخبراته السابقة.',
                'type' => 'rating',
                'order' => 1,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'survey_question_category_id' => $categoryIds[1],
                'question' => 'مدى قدرة المدرب على توصيل المعلومة وعرض الافكار بطريقة منظمة وسهلة.',
                'type' => 'rating',
                'order' => 2,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'survey_question_category_id' => $categoryIds[1],
                'question' => 'قدرة المدرب على التنويع في الاساليب التدريبية (الانشطة، التمارين).',
                'type' => 'rating',
                'order' => 3,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'survey_question_category_id' => $categoryIds[1],
                'question' => 'قدرة المدرب على تحفيز المشاركين على التفاعل والمشاركة.',
                'type' => 'rating',
                'order' => 4,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'survey_question_category_id' => $categoryIds[1],
                'question' => 'قدرة المدرب على إدارة المداخلات والمناقشات والحوارات ما بين المتدربين.',
                'type' => 'rating',
                'order' => 5,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            // البيئة التدريبية (Category 3)
            [
                'survey_question_category_id' => $categoryIds[2],
                'question' => 'مدى اريحية البيئة التدريبية للمتدرب من حيث تناسب القاعة والإضاءة والتهوية والنظافة.',
                'type' => 'rating',
                'order' => 1,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'survey_question_category_id' => $categoryIds[2],
                'question' => 'مدى توافر المعينات والوسائل التدريبية وكفاءتها للفعاليات المصاحبة للبرنامج التدريبي.',
                'type' => 'rating',
                'order' => 2,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'survey_question_category_id' => $categoryIds[2],
                'question' => 'مدى مواءمة توقيت ومكان انعقاد البرنامج للمشاركين.',
                'type' => 'rating',
                'order' => 3,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'survey_question_category_id' => $categoryIds[2],
                'question' => 'مدى توفر الخدمات والتجهيزات الإدارية للمتدربين بالجودة المناسبة (التغذية والمعيشة).',
                'type' => 'rating',
                'order' => 4,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            // المتدربين (Category 4)
            [
                'survey_question_category_id' => $categoryIds[3],
                'question' => 'التفاعل و المشاركة الجماعية ما بين المتدربين.',
                'type' => 'rating',
                'order' => 1,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'survey_question_category_id' => $categoryIds[3],
                'question' => 'مدى تقارب المستوى العلمي والوظيفي للمشاركين بما يتناسب مع قدراتهم وإمكانيتهم لهذا البرنامج التدريبي.',
                'type' => 'rating',
                'order' => 2,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'survey_question_category_id' => $categoryIds[3],
                'question' => 'مدى رضا المتدربين والاستفادة المعرفية والمهارية من البرنامج التدريبي.',
                'type' => 'rating',
                'order' => 3,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            // ملاحظات عامة (Category 5)
            [
                'survey_question_category_id' => $categoryIds[4],
                'question' => 'مقترحات لتطوير البرنامج التدريبي بشكل عام',
                'type' => 'text',
                'order' => 1,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'survey_question_category_id' => $categoryIds[4],
                'question' => 'تقييمك للمحاضر',
                'type' => 'text',
                'order' => 2,
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ];

        DB::table('survey_questions')->insert($questions);
    }
}
