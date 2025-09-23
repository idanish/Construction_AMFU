<?PHP

namespace App\Exports;

use App\Models\RequestModel;
use Maatwebsite\Excel\Concerns\FromCollection;

class RequestReportExport implements FromCollection
{
    public function collection()
    {
        return RequestModel::with(['user', 'department'])->get();
    }
}
