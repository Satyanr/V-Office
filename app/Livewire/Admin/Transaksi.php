<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\ProjectTbl;
use App\Models\LayananTbl;
use App\Models\KonsumenTbl;
use Livewire\WithPagination;
use App\Models\PembayaranTbl;
use Illuminate\Validation\Rule;
use App\Models\MetodePembayaranTbl;
use Jantinnerezo\LivewireAlert\LivewireAlert;

class Transaksi extends Component
{
    public $id_konsumen, $id_layanan, $nama, $email, $jumlah, $total_harga, $searchproject, $project_id, $status, $uang_bayar, $sisa_pembayaran, $layanan, $laundrycode, $sttsbyr, $mtdbyr, $filterorder, $filterbyr, $tanggal_pengingat, $keterangan, $kembalian, $total;
    public $updatemode = false,
        $listmode = false,
        $detailon = false;

    public $konsumenlist,
        $searchkonsumen,
        $konsumen_id,
        $showresult = false;

    use WithPagination;
    use LivewireAlert;
    protected $paginationTheme = 'bootstrap';
    protected $paginationName = 'Page';
    public function paginationView()
    {
        return 'components.pagination_custom';
    }
    public function resetPageOrder()
    {
        $this->gotoPage(1, 'Page');
    }
    public function setValueStatus()
    {
        if ($this->filterbyr != 'Belum Lunas') {
            $this->filterbyr = 'Belum Lunas';
        } else {
            $this->filterbyr = '';
        }
    }
    public function render()
    {
        $searchproject = '%' . $this->searchproject . '%';
        $filterorder = '%' . $this->filterorder . '%';

        return view('livewire.admin.transaksi', [
            'projects' => ProjectTbl::where('kode_project', 'LIKE', $searchproject)
                ->where('status', 'LIKE', $filterorder)
                ->whereHas('konsumen', function ($query) {
                    $query->where('nama', 'LIKE', '%' . $this->searchkonsumen . '%');
                })
                ->whereHas('pembayaran', function ($query) {
                    $query->where('status_pembayaran', 'LIKE', '%' . $this->filterbyr . '%');
                })
                ->orderBy('id', 'DESC')
                ->paginate(5, ['*'], $this->paginationName),
        ]);
    }
    public function liston()
    {
        $this->listmode = true;
    }
    public function listoff()
    {
        $this->listmode = false;
    }
    public function resetInput()
    {
        $this->id_konsumen = '';
        $this->id_layanan = '';
        $this->nama = '';
        $this->email = '';
        $this->jumlah = '';
        $this->total_harga = 0;
        $this->project_id = null;
        $this->uang_bayar = '';
        $this->mtdbyr = '';
        $this->kembalian = 0;
    }
    public function store()
    {
        $this->validate([
            'nama' => 'required',
            'email' => 'required',
        ]);

        $konsumen_terdaftar = KonsumenTbl::where('nama', $this->nama)->where('email', $this->email)->first();
        if ($konsumen_terdaftar) {
            $konsumen = $konsumen_terdaftar;
        } else {
            $konsumen = KonsumenTbl::create([
                'nama' => $this->nama,
                'email' => $this->email,
            ]);
        }

        $base_code = 'PRJ-' . $konsumen->id . date('mdy');

        $existing_orders_count = ProjectTbl::where('kode_project', 'like', $base_code . '%')->count();

        $suffix = $existing_orders_count > 0 ? rand(10, 99) : '';

        $project = ProjectTbl::create([
            'kode_project' => $base_code . $suffix,
            'id_konsumens' => $konsumen->id,
            'id_layanans' => $this->id_layanan,
            'status' => 'baru',
            'total_harga' => $this->total_harga,
            'sisa_pembayaran' => $this->total_harga,
            'tanggal_pengingat' => $this->tanggal_pengingat,
            'keterangan' => $this->keterangan,
        ]);

        PembayaranTbl::create([
            'id_project' => $project->id,
            'status_pembayaran' => 'belum lunas',
        ]);

        $this->resetInput();
        $this->total_harga = 0;
        $this->alert('success', 'Berhasil Ditambahkan!', [
            'position' => 'center',
            'timer' => 3000,
            'toast' => false,
            'timerProgressBar' => true,
        ]);

        return redirect()->to('/barcode/' . $project->id);
    }
    public function edit($id)
    {
        $project = ProjectTbl::find($id);
        $this->project_id = $project->id;
        $this->id_konsumen = $project->id_konsumens;
        $this->id_layanan = $project->id_layanans;
        $this->jumlah = $project->jumlah;
        $this->total_harga = $project->total_harga;

        $konsumen = KonsumenTbl::find($project->id_konsumens);
        $this->nama = $konsumen->nama;
        $this->email = $konsumen->email;

        $this->updatemode = true;
        $this->listmode = false;
    }
    public function update()
    {
        $this->validate([
            'id_layanan' => 'required',
            'jumlah' => 'required|numeric|min:0',
            'nama' => 'required',
            'email' => 'required',
        ]);

        $project = ProjectTbl::find($this->project_id);
        $project->id_konsumens = $this->id_konsumen;
        $project->id_layanans = $this->id_layanan;
        $project->jumlah = $this->jumlah;
        $project->total_harga = $this->total_harga;
        $project->save();

        $konsumen = KonsumenTbl::find($this->id_konsumen);
        $konsumen->nama = $this->nama;
        $konsumen->email = $this->email;
        $konsumen->save();

        $this->resetInput();
        $this->updatemode = false;
        $this->alert('success', 'Berhasil Diubah!', [
            'position' => 'center',
            'timer' => 3000,
            'toast' => false,
            'timerProgressBar' => true,
        ]);
    }
    public function cancel()
    {
        $this->resetInput();
        $this->updatemode = false;
        $this->detailon = false;
        $this->listmode = true;
    }
    public function destroy($id)
    {
        $project = ProjectTbl::find($id);
        $project->delete();
        $this->alert('success', 'Berhasil Dihapus!', [
            'position' => 'center',
            'timer' => 3000,
            'toast' => false,
            'timerProgressBar' => true,
        ]);
    }
    public function show($id)
    {
        $project = ProjectTbl::findOrFail($id);

        $this->project_id = $project->id;
        $this->id_konsumen = $project->id_konsumens;
        $this->id_layanan = $project->id_layanans;
        $this->jumlah = $project->jumlah;
        $this->total_harga = $project->total_harga;
        $this->laundrycode = $project->kode_project;
        $this->status = $project->status;

        $payment = $project->pembayaran;
        $this->uang_bayar = $payment->uang_bayar;
        $this->mtdbyr = $payment->metode_pembayaran;
        $this->kembalian = $payment->kembalian;
        $this->sttsbyr = $payment->status_pembayaran;
    }

    public function updatestatus()
    {
        $project = ProjectTbl::find($this->project_id);
        $project->status = $this->status;
        $project->save();
        session()->flash('message', 'Status orderan berhasil diupdate.');
    }

    public function bayarupdate()
    {
        $paymentord = PembayaranTbl::where('id_project', $this->project_id)->first();
        $project = ProjectTbl::find($this->project_id);

        $this->total_harga = $project->total_harga;

        // VALIDASI: boleh kurang dari total
        $this->validate(
            [
                'uang_bayar' => 'required|numeric|min:0',
            ],
            [
                'uang_bayar.required' => 'Uang bayar harus diisi.',
                'uang_bayar.numeric' => 'Uang bayar harus berupa angka.',
            ],
        );

        if ($this->mtdbyr == null) {
            $this->mtdbyr = 'cash';
        }

        // LOGIKA CREDIT / LUNAS
        if ($this->uang_bayar >= $this->total_harga) {
            // LUNAS
            $paymentord->status_pembayaran = 'lunas';
            $paymentord->kembalian = $this->uang_bayar - $this->total_harga;
            $project->sisa_pembayaran = 0;
        } else {
            // BELUM LUNAS / CREDIT
            $paymentord->status_pembayaran = 'belum lunas';
            $paymentord->kembalian = 0;
            $project->sisa_pembayaran = $this->total_harga - $this->uang_bayar;
        }

        $paymentord->uang_bayar = $this->uang_bayar;
        $paymentord->metode_pembayaran = $this->mtdbyr;
        $paymentord->save();

        $project->save();

        $this->alert('success', 'Pembayaran berhasil disimpan!', [
            'position' => 'center',
            'timer' => 3000,
            'toast' => false,
            'timerProgressBar' => true,
        ]);
    }

    public function searchResult()
    {
        if (!empty($this->nama)) {
            $this->konsumenlist = KonsumenTbl::orderby('nama', 'asc')
                ->select('*')
                ->where('nama', 'like', '%' . $this->nama . '%')
                ->limit(5)
                ->get();

            $this->showresult = true;
        } else {
            $this->showresult = false;
        }
    }

    public function pilihkonsumen($id = 0)
    {
        $konsumenlist = KonsumenTbl::select('*')->where('id', $id)->first();

        $this->nama = $konsumenlist->nama;
        $this->email = $konsumenlist->email;
        $this->showresult = false;
    }
}
