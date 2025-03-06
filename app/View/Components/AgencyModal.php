<?php

namespace App\View\Components;

use Illuminate\View\Component;

class AgencyModal extends Component
{
    public $id;
    public $title;
    public $action;
    public $method;
    public $agency;

    public function __construct($id, $title, $action, $method = 'POST', $agency = null)
    {
        $this->id = $id;
        $this->title = $title;
        $this->action = $action;
        $this->method = $method;
        $this->agency = $agency;
    }

    public function render()
    {
        return view('components.agency-modal');
    }
} 