<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class CustomerExport implements FromView
{
    /**
     * @return \Illuminate\Support\Collection
     */

    public function __construct($customers)
    {
        $this->customers=$customers;

    }

    public function view(): View
    {
        return view('admin.customer.invoice', [
            'customers' => $this->customers
        ]);
    }
}
