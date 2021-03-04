<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class CheckinExport implements FromView
{
    /**
     * @return \Illuminate\Support\Collection
     */

    public function __construct($checkins)
    {
        $this->checkins=$checkins;
    }

    public function view(): View
    {
        //var_dump($this->checkins->toArray());die;
        return view('admin.checkin.invoice', [
            'checkins' => $this->checkins
        ]);
    }
}
