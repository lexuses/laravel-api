<?php

namespace App\Services\Core\Debugger;

use DB;
use Event;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Symfony\Component\HttpFoundation\Response;

class Debugger {

    /**
     * @var Collection
     */
    private $queries;

    /**
     * @var Collection
     */
    private $debug;

    /**
     * @var bool
     */
    private $collectQueries = false;

    /**
     * Create a new Debugger service.
     */
    public function __construct()
    {
        $this->queries = new Collection();
        $this->debug   = new Collection();

        Event::listen('Illuminate\Foundation\Http\Events\RequestHandled', function ($event) {
            $this->updateRequest($event->request, $event->response);
        });
    }

    /**
     * Listen database queries events.
     */
    public function collectDatabaseQueries()
    {
        $this->collectQueries = true;

        DB::listen(function ($query) {
            // $query->sql
            // $query->bindings
            // $query->time
            $this->logQuery($query->sql, $query->bindings, $query->time);
        });
    }

    /**
     * Log DB query.
     *
     * @param string $query
     * @param array $attributes
     * @param float $time
     */
    private function logQuery($query, $attributes, $time)
    {
        foreach ($attributes as $k => $attr)
        {
            if($attr instanceof \DateTime)
                $attributes[$k] = date_format($attr, 'Y-m-d H:i:s');
        }

        $query = vsprintf(str_replace(['%', '?'], ['%%', "'%s'"], $query), $attributes) . ';';

        $this->queries->push([
            'query' => $query,
            'time' 	=> $time,
        ]);
    }

    /**
     * Add vars to debug output.
     */
    public function dump()
    {
        foreach (func_get_args() as $var)
        {
            $this->debug->push($var);
        }
    }

    /**
     * Update final response.
     *
     * @param Request $request
     * @param Response $response
     */
    private function updateRequest(Request $request, Response $response)
    {
        if ($this->needToUpdateResponse() AND $response->headers->get('content-type') == 'application/json')
        {
            $data = \GuzzleHttp\json_decode($response->getContent());
            $data = new Collection($data);
            $sql = null;
            $dump = null;
            if ($this->collectQueries)
            {
                $sql = [
                    'total_queries' => $this->queries->count(),
                    'queries' => $this->queries,
                ];
            }
            if (!$this->debug->isEmpty())
            {
                $dump = $this->debug;
            }
            $data->put('debug', [
                'sql' => $sql,
                'dump' => $dump
            ]);
            $response->setContent(\GuzzleHttp\json_encode($data));
        }
    }

    /**
     * Check if debugger has to update the response.
     *
     * @return bool
     */
    private function needToUpdateResponse()
    {
        return $this->collectQueries || !$this->debug->isEmpty();
    }
}