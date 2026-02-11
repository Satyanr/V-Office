<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\KaryawanTbl;

class Dashboard extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public $name, $cuti, $karyawan_id;
    public $search = '';
    public $isEdit = false;

    protected $rules = [
        'name' => 'required|string|min:3',
        'cuti' => 'nullable|string',
    ];

    public function resetForm()
    {
        $this->name = '';
        $this->cuti = '';
        $this->karyawan_id = null;
        $this->isEdit = false;
    }

    public function store()
    {
        $this->validate();

        KaryawanTbl::create([
            'name' => $this->name,
            'cuti' => $this->cuti,
        ]);

        session()->flash('success', 'Karyawan berhasil ditambahkan');
        $this->resetForm();
    }

    public function edit($id)
    {
        $data = KaryawanTbl::findOrFail($id);

        $this->karyawan_id = $data->id;
        $this->name = $data->name;
        $this->cuti = $data->cuti;
        $this->isEdit = true;
    }

    public function update()
    {
        $this->validate();

        KaryawanTbl::where('id', $this->karyawan_id)->update([
            'name' => $this->name,
            'cuti' => $this->cuti,
        ]);

        session()->flash('success', 'Data karyawan berhasil diupdate');
        $this->resetForm();
    }

    public function delete($id)
    {
        KaryawanTbl::findOrFail($id)->delete();
        session()->flash('success', 'Data karyawan berhasil dihapus');
    }

    public function render()
    {
        return view('livewire.admin.dashboard', [
            'karyawans' => KaryawanTbl::where('name', 'like', '%' . $this->search . '%')
                ->latest()
                ->paginate(5),
        ]);
    }
}
