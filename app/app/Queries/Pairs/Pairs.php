<?php

namespace App\Queries\Pairs;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\DB;
use JetBrains\PhpStorm\ArrayShape;

/**
 * @property array|array[] $quotesSeries
 */
class Pairs
{
    const DEFAULT_PER_PAGE = 10;
    const DEFAULT_PERIOD = 4;

    private int $count = 0;

    private array $quotesSeries;
    private array $quotesCategories;

    public function __construct(private Request $request)
    {
    }

    #[ArrayShape(['start' => "false|int", 'end' => "false|int"])]
    private function getPeriods(): array
    {
        switch ($this->request->get('period', 4)) {
            case 1:
                return [
                    'start' => strtotime('today'),
                    'end' => strtotime('tomorrow') - 1,
                ];
            case 2:
                return [
                    'start' => strtotime('00:00 - 2 days'),
                    'end' => strtotime('tomorrow') - 1,
                ];
            case 3:
                return [
                    'start' => strtotime('00:00 - 7 days'),
                    'end' => strtotime('tomorrow') - 1,
                ];
            case 4:
            default:
                return [
                    'start' => strtotime('00:00 - 1 month'),
                    'end' => strtotime('tomorrow') - 1,
                ];
        }
    }

    public function getData(): array
    {

//SELECT pair, AVG(high - History.low) AS AVERAGE1m, count(id)
//FROM History
//WHERE time >= 1638316800 AND time <= 1640995140
//GROUP BY pair;

        $periods = $this->getPeriods();

        $perPage = $this->request->get('per_page', self::DEFAULT_PER_PAGE);
        $page = Paginator::resolveCurrentPage();

        $query = DB::table('History')
            ->select(DB::raw('pair, AVG(high - History.low) AS average, count(id) AS count'))
            ->whereBetween('time', [$periods['start'], $periods['end']])
            ->groupBy('pair')
            ->forPage($page, $perPage);

        return $query->get()->toArray();
    }

    public function getCount(): int
    {
        if ($this->count) {
            return $this->count;
        }

//SELECT count(DISTINCT pair)
//FROM History
//WHERE time >= 1638316800 AND time <= 1640995140;

        $periods = $this->getPeriods();

        return $this->count = DB::table('History')
            ->whereBetween('time', [$periods['start'], $periods['end']])
            ->count(DB::raw('DISTINCT pair'));
    }

    public function getBeforePage(): ?int
    {
        $page = Paginator::resolveCurrentPage();
        return $page > 1 ? $page - 1 : null;
    }

    public function getNextPage(): ?int
    {
        $page = Paginator::resolveCurrentPage();
        $perPage = (int)$this->request->get('per_page', self::DEFAULT_PER_PAGE);
        $totalPages = (int)ceil(($this->getCount() / $perPage));

        return $page < $totalPages ? $page + 1 : null;
    }

    public function getDynamicsData()
    {
        $periods = $this->getPeriods();

        $sql = <<<SQL
SELECT pair, AVG(high - History.low) AS AVERAGE1m, from_unixtime(time,"%Y-%m-%d %h") AS day_time
FROM History
WHERE time >= :start_time AND time <= :end_time
GROUP BY pair, day_time;
SQL;

        $data = DB::select($sql, [
            ':start_time' => $periods['start'],
            ':end_time' => $periods['end'],
        ]);

        $seriesTemp = $categories = [];
        foreach ($data as $datum) {
//            var_dump([
//                $datum->day_time,
//                $datum->day_time. ':00:00',
//                strtotime($datum->day_time. ':00:00'),
//                date('m d H', strtotime($datum->day_time. ':00:00')),
//                date('m d H', strtotime($datum->day_time))
//            ]);
//            die();
            $dayTime = date('m.d H', strtotime($datum->day_time. ':00:00'));
            $seriesTemp[$datum->pair][] = (float)$datum->AVERAGE1m;
            $categories[$dayTime] = $dayTime;
        }

//        var_dump($categories);
//        die();

        $this->quotesSeries = array_map(function ($name, $data) {
            return [
                'name' => $name,
                'data' => $data,
            ];
        }, array_keys($seriesTemp), $seriesTemp);

        $this->quotesCategories = array_values($categories);
    }

    public function getDynamicsCategories(): array
    {
        return $this->quotesCategories;
    }

    public function getDynamicsSeries(): array
    {
        return $this->quotesSeries;

    }
}
