<?php

namespace App\Http\Controllers;

use App\Models\Prize;

use Illuminate\Http\Request;
use App\Http\Requests\PrizeRequest;
use App\Http\Controllers\Controller;
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
            "msg"=>$msg,
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
        }if($parsedProbabilityVal > $probabilitySum){
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
        $prize->title = $request->input('title');
        $prize->probability = floatval($request->input('probability'));
        $prize->save();

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

        return to_route('prizes.index');
    }


    public function simulate(Request $request)
    {


        for ($i = 0; $i < $request->number_of_prizes ?? 10; $i++) {
            Prize::nextPrize();
        }

        return to_route('prizes.index');
    }

    public function reset()
    {
        // TODO : Write logic here
        return to_route('prizes.index');
    }
}
