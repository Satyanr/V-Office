<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\Absensi;
use Livewire\WithPagination;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\File;

class ListPengajuan extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';
    protected $paginationName = 'page';

    public $searchNama = '';
    public $filterStatus = '';
    public $filterKeterangan = '';
    public $filterTanggal = '';

    public $exportFromDate;
    public $exportToDate;

    public $absensi_id;
    public $name;
    public $status;
    public $keterangan;

    public $updateMode = false;

    protected $rules = [
        'exportFromDate' => 'required|date',
        'exportToDate' => 'required|date|after_or_equal:exportFromDate',
    ];

    /* ================= RESET PAGINATION SAAT FILTER BERUBAH ================= */
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
        $query = Absensi::query();

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
            $query->whereDate('waktu_masuk', $this->filterTanggal);
        }

        return view('livewire.admin.list-pengajuan', [
            'absensis' => $query->orderBy('id', 'desc')->paginate(5, ['*'], $this->paginationName),
        ]);
    }

    public function edit($id)
    {
        $absensi = Absensi::findOrFail($id);

        $this->absensi_id = $absensi->id;
        $this->name = $absensi->name;
        $this->status = $absensi->status;
        $this->keterangan = $absensi->keterangan;

        $this->updateMode = true;
    }

    public function update()
    {
        $this->validate([
            'name' => 'required',
            'status' => 'required',
            'keterangan' => 'required',
        ]);

        Absensi::where('id', $this->absensi_id)->update([
            'name' => $this->name,
            'status' => $this->status,
            'keterangan' => $this->keterangan,
        ]);

        $this->cancel();
        session()->flash('message', 'Data berhasil diperbarui');
    }

    public function destroy($id)
    {
        $absensi = Absensi::findOrFail($id);

        if ($absensi->photo_name) {
            $photoPath = public_path('storage/absensi/' . $absensi->photo_name);
            if (File::exists($photoPath)) {
                File::delete($photoPath);
            }
        }

        $absensi->delete();
        session()->flash('message', 'Data & foto absensi berhasil dihapus');
    }

    public function cancel()
    {
        $this->reset(['absensi_id', 'name', 'status', 'keterangan', 'updateMode']);
    }
}
