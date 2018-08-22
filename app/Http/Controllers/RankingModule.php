<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Redis;


class RankingModule extends Controller
{
    public function increment_view_ranking($id){
        Redis::zincrby("ranking/".date("Y-m-d"), 1, $id);
    }

	public function get_ranking_all(){
        $dates = array();
        $keys = array();
        for ($i=0; $i <= 6; $i++){
            array_push($dates, date('Y-m-d', strtotime('-'.$i.' day', time())));
        }
        foreach ($dates as $date) {
            array_push($keys, "ranking/{$date}");
        }
        Redis::zunionstore("ranking/weekly", $keys);
        return Redis::zrevrange("ranking/weekly", 0, -1, "withscores");
    }

}
