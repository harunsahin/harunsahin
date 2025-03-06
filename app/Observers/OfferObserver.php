<?php

namespace App\Observers;

use App\Models\Offer;
use App\Models\Status;

class OfferObserver
{
    public function creating(Offer $offer)
    {
        if (!$offer->status_id) {
            $defaultStatus = Status::where('name', 'Devam Ediyor')->first();
            $offer->status_id = $defaultStatus ? $defaultStatus->id : null;
        }
    }
} 