<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class ChatDetailExport implements FromView
{
    /**
     * @return \Illuminate\Support\Collection
     */

    public function __construct($datas)
    {
        $this->datas=$datas;

    }

    public function view(): View
    {
        //var_dump($this->datas->toArray());die;
        return view('admin.chat.details-export', [
            'datas' => $this->datas
        ]);
    }
}
