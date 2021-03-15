<?php

namespace App\Events;

use App\Package;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class PackageCreated
{
    use Dispatchable;
    use InteractsWithSockets;
    use SerializesModels;

    public $package;

    public function __construct(Package $package)
    {
        $this->package = $package;
    }
}
