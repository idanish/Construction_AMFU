namespace App\Exports;

use App\Models\Procurement;
use Maatwebsite\Excel\Concerns\FromCollection;

class ProcurementReportExport implements FromCollection
{
    public function collection()
    {
        return Procurement::with(['supplier', 'items'])->get();
    }
}
