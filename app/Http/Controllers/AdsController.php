<?php

namespace App\Http\Controllers;

use App\Models\Ad;
use App\Models\Rate;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AdsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api')->except(['index', 'show']);
    }

    public function index()
    {
        $ad = Ad::with(['rates'])->get();

        return $this->customResponse($ad, 200);
    }

    public function store()
    {
        $valid = Carbon::now()->addMonth(1);

        Ad::create([
            'user_id'     => auth()->id(),
            'text'        => request('text'),
            'valid_until' => $valid,
        ]);

        return $this->customResponse([
            'message' => 'Ad Successfully Created!'
        ], 201);
    }

    public function show(Ad $ad)
    {
        return $this->customResponse([
            $ad
        ]);
    }

    public function update(Ad $ad)
    {
        $this->authorize('update', $ad);

        if(Carbon::now() >= $ad->valid_until){ // 01.02 >= 01.01
            return $this->customResponse([
                'message' => 'Your cant update expired ad!'
            ], 403);
        }

        $ad->update([
            'text' => request('text')
        ]);

        return $this->customResponse([
            'message' => 'Ad Successfully Updated!'
        ], 200);
    }

    public function destroy(Ad $ad)
    {
        $this->authorize('delete', $ad);

        if(Carbon::now() >= $ad->valid_until){
            return $this->extendAd(
                'Cant delete expired ad!'
            );
        }

        $ad->delete();

        return response()->json([
            'data' => [
                'message' => 'Ad Successfully Deleted!'
            ]
        ], 200);
    }

    public function getUser()
    {
        return request()->user();
    }

    public function extend(Ad $ad)
    {
        $this->authorize('extend', $ad);

        $now = Carbon::now();

        // if($now >= $ad->valid_until->subDays(3) && $now < $ad->valid_until){
        if (($now->diffInDays($ad->valid_until) ) >= 3){ // today + 3 days
            $ad->valid_until = $now->addMonths(1);
            $ad->save(); 

            return $this->customResponse([
                'message'  => 'Ad Successfully Extended!',
                'valid_to' => $ad->valid_until->format('d-m-Y H:i:s')
            ], 200);
        }

        return $this->customResponse([
            'message' => 'You can only extend ad 3 days before expiration!'
        ], 400);
    }

    public function rate(Ad $ad)
    {
        if(Carbon::now() >= $ad->valid_until){
            return $this->customResponse([
                'message' => 'You cant rate expired ad!'
            ], 400);
        }

        // $anotherRate = Rate::query()
        //     ->where('ad_id', '=', $ad->id)
        //     ->where('user_id', '=', auth()->id())
        //     ->get();

        $anotherRate = auth()->user()->rates()
            ->where('ad_id', $ad->id)
            ->get();

        if(count($anotherRate) >= 1){
            return $this->customResponse([
                'message' => 'You can rate only once per month!'
            ], 409);
        }      

        $Rate = Rate::create([
            'ad_id'   => $ad->id,
            'user_id' => auth()->id(),
            'rating'  => request('rating')
        ]);

        return $this->customResponse([
            'message' => 'Rating Successfully Added!'
        ], 201);
    }

    public function customResponse($message = '', $code = 200)
    {
        return response()->json([
            'data' => $message,
        ], $code);
    }
}
