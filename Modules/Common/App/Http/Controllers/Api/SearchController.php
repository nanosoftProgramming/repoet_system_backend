<?php

namespace Modules\Common\App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Modules\Common\App\Http\Requests\SearchRequest;
use Modules\Common\Service\SearchService;

class SearchController extends Controller
{
    protected $searchService;

    public function __construct(SearchService $searchService)
    {
        $this->searchService = $searchService;
    }

    public function search(SearchRequest $request)
    {
        try {
            $query = $request->get('query') ?? '';
            // $limit = $request->get('limit', 50);

            if ($query === '') {
                return returnMessage(true, 'Search completed successfully', ['total_results' => 0, 'query' => $query, 'results' => collect()]);
            }

            $results = $this->searchService->search($query);
            $groupedResults = $results->groupBy('type')->map(function ($items) {
                return [
                    'count' => $items->count(),
                    'items' => $items->values(),
                ];
            });

            return returnMessage(true, 'Search completed successfully', ['total_results' => $results->count(), 'query' => $query, 'results' => $groupedResults]);

        } catch (\Exception $e) {
            return returnMessage(false, 'Search failed: ' . $e->getMessage(), null, 'server_error');
        }
    }
}
