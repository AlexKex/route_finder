<?php
/**
 * Created by PhpStorm.
 * User: apryakhin
 * Date: 26.01.16
 * Time: 17:23
 */

namespace AlexKex\route_finder;


class route {
    const START_INDEX = 0;
    const FINISH_INDEX = 1;
    const WEIGHT_INDEX = 2;

    protected $start;
    protected $finish;
    protected $map = [];

    protected $result_price = 0;
    protected $result_route;

    protected $route_points = []; // array of shortest routes for each node
    protected $route_prices = []; // array of prices
    protected $nodes_queue; // nodes to be analyzed

    public function getRouteInfo(){
        $info = [
            'price' => $this->result_price,
            'route' => []
        ];

        $this->result_route->rewind();
        while($this->result_route->valid()){
            array_push($info['route'], $this->result_route->current());
            $this->result_route->next();
        }

        return $info;
    }

    public function setStart($startPoint)
    {
        $this->start = $startPoint;
    }

    public function setFinish($finishPoint)
    {
        $this->finish = $finishPoint;
    }

    /**
     * Transforms array to a links matrix
     * @param $map
     * @throws routeFinderException
     */
    public function setMap($map)
    {
        if(!empty($map)){
            foreach($map as $rib){
                if(!isset($this->map[$rib[self::START_INDEX]])){
                    $this->map[$rib[self::START_INDEX]] = [];
                }

                if(!isset($this->map[$rib[self::FINISH_INDEX]])){
                    $this->map[$rib[self::FINISH_INDEX]] = [];
                }

                $this->map[$rib[self::START_INDEX]][$rib[self::FINISH_INDEX]] = $rib[self::WEIGHT_INDEX];
                $this->map[$rib[self::FINISH_INDEX]][$rib[self::START_INDEX]] = $rib[self::WEIGHT_INDEX];
            }
        }
        else{
            throw new routeFinderException("Empty map array given");
        }
    }

    /**
     * Dijkstra algorythm implementation
     */
    public function calculateRouteData()
    {
        if($this->vortexIsReachableInOneStep()){
            // maybe we are lucky =)
            $this->result_price = $this->map[$this->start][$this->finish];
            $this->result_route = null;
        }
        else{
            $this->prepareMap();
            $this->dijkstraParse();
            $this->findShortestRoute();
        }
    }

    protected function prepareMap(){
        $this->nodes_queue = new \SplPriorityQueue();

        foreach ($this->map as $node => $neighbours) {
            $this->route_prices[$node] = INF;
            $this->route_points[$node] = null;
            foreach ($neighbours as $n_node => $n_cost) {
                $this->nodes_queue->insert($n_node, $n_cost);
            }
        }

        $this->route_prices[$this->start] = 0;
    }

    /**
     * parse each node one-bu-one to find shortest routes and neighbours
     */
    protected function dijkstraParse(){
        while (!$this->nodes_queue->isEmpty()) {
            $node = $this->nodes_queue->extract();
            if (!empty($this->map[$node])) {
                foreach ($this->map[$node] as $v_node => $v_cost) {
                    $rib_price = $this->route_prices[$node] + $v_cost;
                    if ($rib_price < $this->route_prices[$v_node]) {
                        $this->route_prices[$v_node] = $rib_price;
                        $this->route_points[$v_node] = $node;
                    }
                }
            }
        }
    }

    /**
     * restore shortest route after Dijkstra parse
     * @throws routeFinderException
     */
    protected function findShortestRoute(){
        $this->result_route = new \SplStack();
        $tmp_finish = $this->finish;
        while (isset($this->route_points[$tmp_finish]) && $this->route_points[$tmp_finish]) {
            if($tmp_finish != $this->finish){
                // we need to return only transit points
                $this->result_route->push($tmp_finish);
            }

            $this->result_price += $this->map[$tmp_finish][$this->route_points[$tmp_finish]];
            $tmp_finish = $this->route_points[$tmp_finish];
        }

        if ($this->result_route->isEmpty()) {
            throw new routeFinderException("Can't find route from $this->start to $this->finish \n");
        }
    }

    /**
     * Check if we can reach one vortex from another in one step
     * @param null $start_point
     * @param null $finish_point
     * @return bool
     */
    protected function vortexIsReachableInOneStep($start_point = NULL, $finish_point = NULL){
        $is_reachable = false;

        if(empty($start_point)){
            $start_point = $this->start;
        }

        if(empty($finish_point)){
            $finish_point = $this->finish;
        }

        if(isset($this->map[$start_point][$finish_point])){
            $is_reachable = true;
        }

        return $is_reachable;
    }
}