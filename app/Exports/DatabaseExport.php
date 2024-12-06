<?php
namespace App\Exports;

use Illuminate\Contracts\Queue\ShouldQueue;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class DatabaseExport implements WithMultipleSheets, ShouldAutoSize,ShouldQueue
{
    use Exportable;

   protected $business_id;

   public function __construct($business_id )
   {
    $this->business_id = $business_id;
   }


    // Define the tables you want to export
    protected $tables = ['users','employees', 'categories','currencies','customers','expense_categories','expenses','payment_from_customers','payment_to_suppliers','products','purchases','purchase_products','sells','sell_products','suppliers','tables','taxes','units'];

    public function sheets(): array
    {
        $sheets = [];

        foreach ($this->tables as $table) {
            // For each specified table, create a new sheet
            $sheets[] = new TableExport($table,$this->business_id);
        }

        return $sheets;
    }
}
