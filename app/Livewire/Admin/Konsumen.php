<?php

namespace App\Livewire\Admin;

use App\Models\Absensi;
use Livewire\Component;
use Livewire\WithPagination;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\AbsensiExport;

class Konsumen extends Component
{
    public $searchabsensi, $nama, $f_absensi, $absensi_id;
    public $updateMode = false;
    use WithPagination;
    protected $paginationTheme = 'bootstrap';
    protected $paginationName = 'Page';
    public function paginationView()
    {
        return 'components.pagination_custom';
    }

    public function resetKonsumenPage()
    {
        $this->gotoPage(1, 'Page');
    }

    public function export()
    {
        return Excel::download(new AbsensiExport, 'Rekap-Absen.xlsx');
    }

    public function render()
    {
        $searchabsensi = '%' . $this->searchabsensi . '%';
        return view('livewire.admin.konsumen', [
            'absensis' => Absensi::where('name', 'LIKE', $searchabsensi)
                ->orderBy('id', 'DESC')
                ->paginate(5, ['*'], $this->paginationName),
        ]);
    }

    public function resetInput()
    {
        $this->nama = '';
        $this->f_absensi = '';
        $this->absensi_id = '';
    }
    public function edit($id)
    {
        $absensi = Absensi::find($id);
        $this->nama = $absensi->nama;
        $this->f_absensi = $absensi->f_absensi;
        $this->absensi_id = $absensi->id;

        $this->updateMode = true;
    }

    public function update()
    {
        $this->validate([
            'name' => 'required',
            'f_absensi' => 'required',
        ]);
        if ($this->absensi_id) {
            $absensi = Absensi::find($this->absensi_id);
            $absensi->update([
                'name' => $this->nama,
                'f_absensi' => $this->f_absensi,
            ]);
        }
        $this->resetInput();
        $this->updateMode = false;
        // $this->alert('success', 'Berhasil Diubah!', [
        //     'position' => 'center',
        //     'timer' => 3000,
        //     'toast' => false,
        //     'timerProgressBar' => true,
        // ]);
    }
    public function destroy($id)
    {
        $absensi = Absensi::find($id);
        $absensi->delete();
        // $this->alert('success', 'Berhasil Dihapus!', [
        //     'position' => 'center',
        //     'timer' => 3000,
        //     'toast' => false,
        //     'timerProgressBar' => true,
        // ]);
    }

    public function cancel()
    {
        $this->updateMode = false;
        $this->resetInput();
    }
}
