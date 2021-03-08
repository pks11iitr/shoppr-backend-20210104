<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class CommissionExport implements FromView
{
    /**
     * @return \Illuminate\Support\Collection
     */

    public function __construct($historyobj,)
    {
        $this->historyobj=$historyobj;

    }

    public function view(): View
    {
        return view('admin.commission.invoice', [
            'historyobj' => $this->historyobj
        ]);
    }
}
