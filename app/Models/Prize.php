<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\Model;
use App\Http\Controllers\PrizesController;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Prize extends Model
{

    protected $guarded = ['id'];

    // The following static function is sued to distribute all the prizes
    // This creates a pool from which, at random prizes can be fetched
    public static function distributePrizes($prizeCounter = 10){

        $prizes = Prize::select('id','title','probability')->get(); // Fetch the prize total
        $prizesCategories = count($prizes); // Count the different number of prizes

        $generativeTable = []; // Initialize the array that will store the prizes

        $prizesInserted = 0; // Keeps the track of already alloted prizes

        // Iterate over the prize to calculate the amount of prizes to be distributed among the categories
        // This function allocates the first batch of prizes
        foreach($prizes as $prize){
            $prizeId = $prize->id;
            $prizeProbability = $prize->probability;

            $countingProbability = $prizeCounter*$prizeProbability/100;
            $distribution = floor($countingProbability);

            $prizesInserted += $distribution;

            $generativeTable[] = [
                "id"=>$prizeId,
                "distribution"=>$distribution,
                "remainingProbability"=>$countingProbability - $distribution,
            ];

        }

        // Sort the array based on the remaining probability
        usort($generativeTable, function($a, $b) {
            if ($a['remainingProbability'] > $b['remainingProbability']) {
                return -1;
            } elseif ($a['remainingProbability'] < $b['remainingProbability']) {
                return 1;
            }
            return 0;
        });

        $iterator = 0;

        $totalPrizeCount = $prizes->count() - 1;

        $initialDifference = $prizeCounter - $prizesInserted;

        // The following loop will allocate all the remaining prizes based on the "remainingProbability" variable
        while($prizesInserted < $prizeCounter){

            // In future, also add a condition that checks weather the following has a 0 probability or not
            // Only if the item has the 0  probability, add the item to the chache

            $temp = $iterator;

            // The following is used to prevent the starving condition of certain categories whoes percentage is too low
            // to actually allocate by the above iterators

            // NOTE: In case of some specific number of prizes, this may fail
            // If the number of prizes is distributed as a whole, and no prizes are remaining after the first pass,
            // the prize randomizer will never come to action and categories with extremely low prizes may fail
            // Further testing is required to actually prove the same
            if(rand(1,100) < 20 && $iterator < $initialDifference){
                $temp = $totalPrizeCount - rand(0, $initialDifference);
            }

            // Increment the selected prize by 1
            $generativeTable[$temp]['distribution'] = $generativeTable[$temp]['distribution'] + 1;

            $prizesInserted += 1;
            $iterator += 1;
        }

        // Finally add all the generated details to the Database
        foreach($generativeTable as $toInsert){

            $objectToCreate = DistributedPrize::where('prize_id', $toInsert['id'])->first();

            // Update the count if exist, else insert new record
            if(is_null($objectToCreate)) {
                $objectToCreate = new DistributedPrize([
                    'prize_id' => $toInsert['id'],
                    'count' => $toInsert['distribution'],
                    'remaining' => $toInsert['distribution'],
                ]);
                $objectToCreate->save();
            } else {
                $objectToCreate->count = $objectToCreate->count + $toInsert['distribution'];
                $objectToCreate->remaining = $objectToCreate->remaining + $toInsert['distribution'];
                $objectToCreate->update();
            }

        }

    }

    // Fetches the enxt prize at random from the prealloted prize pool
    public static function nextPrize()
    {
        // Fetch a random prize from the prizepool
        $prizeToDistribute = DistributedPrize::where('remaining', '>', 0)->inRandomOrder()->first();

        // If null is returned, return it null
        if(is_null($prizeToDistribute)){
            return null;
        }

        // Update the prize returned reducing its count by 1 and save it to database
        $prizeToDistribute->remaining -= 1;
        $prizeToDistribute->save();

        return $prizeToDistribute; // Return the selected prize

    }

    // public static function nextPrizeRandom()
    // {

    //     return PrizesController::thyTrulyRandom();

    // }


    // Creates the relation to the DistributionPrize Model
    public function distributedPrizes()
    {
        return $this->hasOne(DistributedPrize::class)->withDefault();
    }

    // Creates the relation to the Random TrulyDistributedPrize Model
    // public function trulyDistributedPrizes()
    // {
    //     return $this->hasOne(trulyDistributedPrize::class)->withDefault();
    // }
}
