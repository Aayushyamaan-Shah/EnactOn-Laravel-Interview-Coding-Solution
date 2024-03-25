<?php

namespace App\Http\Controllers;

use App\Models\Prize;
use Illuminate\Http\Request;
use App\Models\DistributedPrize;

use App\Models\DistributedRecords;
use App\Http\Requests\PrizeRequest;
use App\Http\Controllers\Controller;
use App\Models\TrulyDistributedPrize;
use Illuminate\Support\Facades\Redirect;



class PrizesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function index()
    {
        $prizes = Prize::all();

        return view('prizes.index', ['prizes' => $prizes]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function create()
    {
        // The following variable is used to denote the percentage of the prize that is already utilized
        $usedPercentage = (float) Prize::sum('probability');

        // The following variable is used to denote the percentage of the prize that is yet to be utilized
        $remainingPercentage = 100.0 - $usedPercentage;

        return view('prizes.create', [
            "usedPercentage"=>$usedPercentage,
            "remainingPercentage"=>$remainingPercentage,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  PrizeRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(PrizeRequest $request)
    {

        $probabilitySum = Prize::sum('probability');
        $parsedProbabilityVal = floatval($request->input('probability'));
        if($parsedProbabilityVal < 0){
            return Redirect::back()->withErrors(['msg' => 'The probability field must not be lesser than 0%']);
        }if($parsedProbabilityVal > (100 - $probabilitySum)){
            return Redirect::back()->withErrors(['msg' => 'The probability field must not be greater than '.(100.0 - $probabilitySum).'%']);
        }

        $prize = new Prize;
        $prize->title = $request->input('title');
        $prize->probability = $parsedProbabilityVal;
        $prize->save();

        return to_route('prizes.index');
    }



    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Contracts\View\View
     */
    public function edit($id)
    {
        $prize = Prize::findOrFail($id);
        return view('prizes.edit', ['prize' => $prize]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  PrizeRequest  $request
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(PrizeRequest $request, $id)
    {
        $prize = Prize::findOrFail($id);

        $newProbability = floatval($request->input('probability'));

        if($newProbability != $prize->probability){
            $this::reset();
        }

        $prize->title = $request->input('title');
        $prize->probability = $newProbability;
        $prize->save();

        // Reset only if the prize is updated

        return to_route('prizes.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        $prize = Prize::findOrFail($id);
        $prize->delete();

        $this::reset();

        return to_route('prizes.index');
    }

    // public function simulateRandom(Request $request){
    //     for ($i = 0; $i < $request->number_of_prizes ?? 10; $i++) {
    //         Prize::nextPrizeRandom();
    //     }
    //     return to_route('prizes.index');
    // }

    public function simulate(Request $request)
    {


        $prizeCount = $request->number_of_prizes;
        $nextPrize = Prize::nextPrize();
        if(is_null($nextPrize)){
            Prize::distributePrizes($prizeCount);
        }

        for ($i = 0; $i < $prizeCount ?? 10; $i++) {
            Prize::nextPrize();
        }

        return to_route('prizes.index');
    }

    public function reset()
    {
        // TODO : Write logic here
        DistributedPrize::truncate();
        return to_route('prizes.index');
    }

    public static function getIdealProbabilityDetails(){
        $prizes = Prize::all();

        $arrayToReturn = [];

        foreach($prizes as $prize){
            $arrayToReturn[] = [
                'label' => $prize->title,
                'y' => $prize->probability,
            ];
        }

        return $arrayToReturn;
    }

    public static function getActualProbabilityDetails(){
        $prizes = Prize::all();
        $arrayToReturn = [];

        foreach($prizes as $prize){
            $distributedPrizeCount = 0;

            if(!is_null($prize->distributedPrizes->count)){
                $distributedPrizeCount = $prize->distributedPrizes->count;
            }

            $arrayToReturn[] = [
                'label' => $prize->title,
                'y' => $distributedPrizeCount,
            ];
        }

        return $arrayToReturn;
    }

    // public static function thyTrulyRandom(){
    //     $theySlector = (float)rand(0,10000);
    //     $prizes = Prize::all();

    //     $percentTotal = 0;

    //     foreach($prizes as $prize){

    //         if($theySlector > $percentTotal && ($theySlector <= $percentTotal + ($prize->probability*100))){


    //             $percentTotal += ($prize->probability*100);

    //             $newPrizeToGive = TrulyDistributedPrize::where('prize_id', '=', $prize->id)->first();

    //             // dd($newPrizeToGive);
    //             if(is_null($newPrizeToGive)){

    //                 $newPrizeToGive = new TrulyDistributedPrize;
    //                 $newPrizeToGive->prize_id = $prize->id;
    //                 $newPrizeToGive->count = 1;
    //                 $newPrizeToGive->save();
    //             }else{
    //                 $newPrizeToGive->count += 1;
    //                 $newPrizeToGive->update();
    //             }

    //             return $prize;
    //         }
    //     }

    // }

    // public static function getActualTrulyRandomProbabilityDetails(){
    //     $prizes = Prize::all();
    //     $arrayToReturn = [];

    //     foreach($prizes as $prize){
    //         $distributedPrizeCount = 0;

    //         if(!is_null($prize->trulyDistributedPrizes->count)){
    //             $distributedPrizeCount = $prize->distributedPrizes->count;
    //         }

    //         $arrayToReturn[] = [
    //             'label' => $prize->title,
    //             'y' => $distributedPrizeCount,
    //         ];
    //     }

    //     return $arrayToReturn;
    // }

}
