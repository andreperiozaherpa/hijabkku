<?php

namespace App\Http\Controllers;

use App\Models\RekeningClient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RekeningClientController extends Controller
{
    public function index()
    {
        return view('transfer.rekening');
    }

    public function show()
    {
        $data = RekeningClient::query()->orderBy('created_at', 'desc');

        return DataTables()->of($data)
            ->addIndexColumn()
            ->editColumn('bank_name', function ($data) {
                return $data->bank_name ?? '-';
            })
            ->addColumn('aksi', function ($data) {
                $group = '<button data-id="'.$data->id.'" type="button" class="edit btn btn-quaternary"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil-square" viewBox="0 0 16 16">
                <path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z"/>
                <path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5v11z"/>
              </svg></button>';
                $group .= '<button data-id="'.$data->id.'" type="button" class="destroy btn btn-danger"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash" viewBox="0 0 16 16">
                <path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5Zm2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5Zm3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0V6Z"/>
                <path d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1v1ZM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4H4.118ZM2.5 3h11V2h-11v1Z"/>
              </svg></button>';

                return '<div class="btn-group" role="group">'.$group.'</div>';
            })
            ->rawColumns(['aksi'])
            ->make(true);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_client' => 'required|string|max:255',
            'bank_code' => 'required|string|max:20',
            'bank_name' => 'nullable|string|max:255',
            'account_number' => 'required|string|max:50',
            'account_holder_name' => 'required|string|max:255',
            'recipient_type' => 'required|in:INDIVIDUAL,BUSINESS',
            'relationship' => 'required|string|max:30',
            'channel_type' => 'required|in:BANK,EWALLET',
            'city' => 'nullable|string|max:100',
            'street_line_1' => 'nullable|string|max:255',
            'keterangan' => 'nullable|string|max:255',
        ]);

        RekeningClient::create([
            ...$validated,
            'created_by' => Auth::id(),
        ]);

        return response()->json([
            'icon' => 'success',
            'title' => 'Sukses',
            'text' => 'Data Rekening Client Berhasil Disimpan',
        ]);
    }

    public function edit(Request $request)
    {
        $data = RekeningClient::findOrFail($request->id);

        return response()->json(['data' => $data]);
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'id' => 'required|exists:rekening_clients,id',
            'nama_client' => 'required|string|max:255',
            'bank_code' => 'required|string|max:20',
            'bank_name' => 'nullable|string|max:255',
            'account_number' => 'required|string|max:50',
            'account_holder_name' => 'required|string|max:255',
            'recipient_type' => 'required|in:INDIVIDUAL,BUSINESS',
            'relationship' => 'required|string|max:30',
            'channel_type' => 'required|in:BANK,EWALLET',
            'city' => 'nullable|string|max:100',
            'street_line_1' => 'nullable|string|max:255',
            'keterangan' => 'nullable|string|max:255',
        ]);

        $rekening = RekeningClient::findOrFail($validated['id']);
        $rekening->update($validated);

        return response()->json([
            'icon' => 'success',
            'title' => 'Sukses',
            'text' => 'Data Rekening Client Berhasil Diubah',
        ]);
    }

    public function destroy(Request $request)
    {
        $rekening = RekeningClient::findOrFail($request->id);

        if ($rekening->transfers()->exists()) {
            return response()->json([
                'icon' => 'error',
                'title' => 'Gagal',
                'text' => 'Rekening ini sudah dipakai pada transfer, tidak dapat dihapus.',
            ], 422);
        }

        $rekening->delete();

        return response()->json([
            'icon' => 'success',
            'title' => 'Sukses',
            'text' => 'Data Rekening Client Berhasil Dihapus',
        ]);
    }
}
