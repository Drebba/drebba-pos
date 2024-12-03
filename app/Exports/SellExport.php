<?php
namespace App\Exports;

use App\Invoice;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class SellExport implements FromView
{

    public $sells;

    public function __construct($sells)
    {
        $this->sells = $sells;
    }

    public function view(): View
    {
        return view('backend.export.sell', [
            'sells' => $this->sells
        ]);
    }
}
