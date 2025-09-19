<?php

namespace App\Events;

use App\Models\Procurement;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ProcurementApproved
{
    use Dispatchable, SerializesModels;

    public $procurement;

    public function __construct(Procurement $procurement)
    {
        $this->procurement = $procurement;
    }
}
