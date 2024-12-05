<?php
namespace App\Exports;

use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithHeadings;

class TableExport implements FromCollection, WithTitle, WithHeadings
{
    protected $table;
    protected $businessId;

    public function __construct($table, $businessId)
    {
        $this->table = $table;
        $this->businessId = $businessId;
    }

    public function collection()
    {
        // Retrieve all records from the table where business_id = 9
        return collect(DB::table($this->table)
            ->where('business_id', $this->businessId) // Filter by business_id
            ->get()
            ->toArray());
    }

    public function title(): string
    {
        // Set the sheet title to the table name
        return $this->table;
    }

    public function headings(): array
    {
        // Get the column names for the table to use as headers
        $columns = DB::getSchemaBuilder()->getColumnListing($this->table);
        return $columns;
    }
}
