<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class ChatExport implements FromView
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
        return view('admin.chat.export', [
            'datas' => $this->datas
        ]);
    }
}
