<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\ApprovalTbl; // ✅ GANTI
use Livewire\WithPagination;
use Illuminate\Support\Facades\File;
use App\Models\Absensi;
use Carbon\Carbon;

class ListPengajuan extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';
    protected $paginationName = 'page';

    public $searchNama = '';
    public $filterStatus = '';
    public $filterKeterangan = '';
    public $filterTanggal = '';

    public $approval_id;
    public $name;
    public $status;
    public $keterangan;
    public $tanggal_awal;
    public $tanggal_akhir;
    public $approval;

    public $updateMode = false;

    public function updated($property)
    {
        if (in_array($property, ['searchNama', 'filterStatus', 'filterKeterangan', 'filterTanggal'])) {
            $this->resetPage($this->paginationName);
        }
    }

    public function paginationView()
    {
        return 'components.pagination_custom';
    }

    public function render()
    {
        $query = ApprovalTbl::query();

        if ($this->searchNama !== '') {
            $query->where('name', 'like', '%' . $this->searchNama . '%');
        }

        if ($this->filterStatus !== '') {
            $query->where('status', $this->filterStatus);
        }

        if ($this->filterKeterangan !== '') {
            $query->where('keterangan', $this->filterKeterangan);
        }

        if ($this->filterTanggal !== '') {
            $query->whereDate('tanggal_awal', $this->filterTanggal);
        }

        return view('livewire.admin.list-pengajuan', [
            'approvals' => $query->orderBy('id', 'desc')->paginate(5, ['*'], $this->paginationName),
        ]);
    }

    public function approve($id)
    {
        $approval = ApprovalTbl::findOrFail($id);

        // Cegah approve 2x
        if ($approval->approval === 'Approved') {
            session()->flash('message', 'Pengajuan sudah pernah di-approve');
            return;
        }

        // Update status approval
        $approval->update([
            'approval' => 'Approved',
        ]);

        $start = Carbon::parse($approval->tanggal_awal);
        $end = Carbon::parse($approval->tanggal_akhir);

        while ($start->lte($end)) {
            // Cek duplicate berdasarkan tanggal di waktu_masuk
            $exists = Absensi::where('name', $approval->name)->whereDate('waktu_masuk', $start->toDateString())->where('status', $approval->status)->exists();

            if (!$exists) {
                Absensi::create([
                    'name' => $approval->name,
                    'photo_name' => $approval->photo_name,
                    'status' => $approval->status,
                    'keterangan' => $approval->status,
                    'waktu_masuk' => $start->copy(),
                ]);
            }

            $start->addDay();
        }

        session()->flash('message', 'Pengajuan berhasil di-approve & absensi dibuat otomatis');
    }

    public function reject($id)
    {
        ApprovalTbl::where('id', $id)->update([
            'approval' => 'Rejected',
        ]);

        session()->flash('message', 'Pengajuan berhasil di-reject');
    }

    public function destroy($id)
    {
        $approval = ApprovalTbl::findOrFail($id);

        if ($approval->photo_name) {
            $photoPath = public_path('storage/absensi/' . $approval->photo_name);
            if (File::exists($photoPath)) {
                File::delete($photoPath);
            }
        }

        $approval->delete();
        session()->flash('message', 'Data berhasil dihapus');
    }
}
