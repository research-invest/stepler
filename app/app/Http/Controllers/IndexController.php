<?php

namespace App\Http\Controllers;

use App\Queries\Pairs\Pairs;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class IndexController extends Controller
{

    /**
     * @throws \Illuminate\Validation\ValidationException
     */
    public function index(Request $request)
    {
        $this->validate($request, [
            'page' => [
                'integer',
            ],
            'per_page' => [
                'integer',
                Rule::in([10, 25, 50])
            ],
            'period' => [
                'integer',
                Rule::in([1, 2, 3, 4])
            ],
        ]);

        $pairs = new Pairs($request);
        $pairs->getDynamicsData();

        return view($request->ajax() ? 'index._table_pairs' : 'index.index', [
            'pairs' => $pairs->getData(),
            'count' => $pairs->getCount(),
            'paginate' => [
                'prev' => $pairs->getBeforePage(),
                'next' => $pairs->getNextPage(),
            ],
            'dynamics' => [
                'categories' => $pairs->getDynamicsCategories(),
                'series' => $pairs->getDynamicsSeries(),
            ],
        ]);

    }
}
