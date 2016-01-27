<?php
/**
 * Created by PhpStorm.
 * User: apryakhin
 * Date: 26.01.16
 * Time: 17:19
 */

namespace src;


class routeFinder {
    protected $route; // instance of the route class

    public function findRoute($startPoint, $finishPoint, $map){
        try{
            $this->route = new route();
            $this->route->setStart($startPoint);
            $this->route->setFinish($finishPoint);
            $this->route->setMap($map);
            $this->route->calculateRouteData();
        }
        catch(routeFinderException $e){
            $e->getMessage();
        }

        return $this->route->getRouteInfo();
    }
}

