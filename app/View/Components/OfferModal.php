<?php

namespace App\View\Components;

use Illuminate\View\Component;
use App\Models\Status;

class OfferModal extends Component
{
    public $id;
    public $title;
    public $statuses;
    public $action;
    public $method;
    public $offer;
    public $formClass;

    public function __construct($id, $title, $statuses = null, $action = null, $method = 'POST', $offer = null, $formClass = '')
    {
        $this->id = $id;
        $this->title = $title;
        $this->statuses = $statuses ? $statuses->where('is_active', true) : Status::where('is_active', true)->get();
        $this->action = $action;
        $this->method = $method;
        $this->offer = $offer;
        $this->formClass = $formClass;
    }

    public function render()
    {
        return view('components.offer-modal');
    }
}
