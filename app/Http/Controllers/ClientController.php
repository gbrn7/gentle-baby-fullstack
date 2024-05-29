<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\TransactionDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Crypt;
use App\Models\User;
use Illuminate\Support\Collection;

class ClientController extends Controller
{
    public function index()
    {

        if (auth()->user()->role === 'super_admin') {
            $invoice = Invoice::all();

            $paidInvoice = $invoice->where('payment_status', 1)
                ->pluck('amount')->sum();

            $unPaidInvoice = $invoice->where('payment_status', 0)
                ->pluck('amount')->sum();

            $unfinishedItem = TransactionDetail::with('transaction.company')
                ->with('product')
                ->whereIn('process_status', ['processing', 'unprocessed'])
                ->orderBy('id', 'desc')
                ->get();

            $unprocessedCount = $unfinishedItem->where('process_status', 'unprocessed')->count();

            $productPerfomance = DB::table('transactions as t')
                ->join('transactions_detail as td', 'td.transaction_id', '=', 't.id')
                ->join('products as p', 'p.id', '=', 'td.product_id')
                ->selectRaw('p.id as productId, p.name as productName ,sum(td.qty) as totalQty, sum(td.qty * td.price) as totalValue')
                ->where('td.process_status', '!=', 'cancel')
                ->groupBy('p.id')
                ->groupBy('p.name')
                ->get();

            $highPerfomanceProducts = (collect($productPerfomance))->sortByDesc('totalQty');
            $lowPerfomanceProducts = (collect($productPerfomance))->sortBy('totalQty');

            return view(
                'home',
                [
                    'paidInvoice' => $paidInvoice,
                    'unpaidInvoice' => $unPaidInvoice,
                    'unprocessedCount' => $unprocessedCount,
                    'highPerfomanceProducts' => $highPerfomanceProducts,
                    'lowPerfomanceProducts' => $lowPerfomanceProducts,
                    'unfinishedItem' => $unfinishedItem
                ]
            );
        }
        if (auth()->user()->role === 'admin') {
            $unfinishedItem = TransactionDetail::with('transaction.company')
                ->with('product')
                ->whereIn('process_status', ['processing', 'unprocessed'])
                ->orderBy('id', 'desc')
                ->get();

            return view('home', ['unfinishedItem' => $unfinishedItem]);
        }

        return view('home');
    }

    public function getCurrentUser()
    {
        $currentUser = auth()->user();
        $currentUser['password'] = Crypt::decryptString($currentUser['password']);
        if ($currentUser) {
            return view(
                'modal.data-profile.data-profile-form',
                ['form' => $currentUser]
            );
        } else {
            return response()->json(["message" => "Access Denied or id not found"], 404);
        }
    }

    public function update(Request $request)
    {
        if (auth()->user()->role == 'super_admin' || auth()->user()->role == 'super_admin_cust' || auth()->user()->id == $request->id) {
            $adminId = $request->id;

            $validation = [
                'name' => 'required|string',
                'email' => 'required|string|email|unique:users,email,' . $adminId . ',id',
                'password' => 'required|string|min:5',
                'image_profile' => 'nullable|image|mimes:png,jpg,jpeg|max:1024',
            ];

            $messages = [
                'required' => 'Kolom :attribute harus diisi',
                'string' => 'Kolom :attribute harus bertipe teks atau string',
                'email' => 'Kolom :attribute harus bertipe email',
                'unique' => ':attribute yang anda berikan sudah dipakai',
                'min' => ':attribute minimal :min digit',
                'image_profile.max' => 'Foto profil maksimal berukuran +-2MB',
                'image' => 'foto profil harus berjenis gambar',
                'mimes' => 'foto profil harus bertipe :values',
            ];

            $validator = Validator::make($request->all(), $validation, $messages);

            if ($validator->fails()) {
                return back()
                    ->with('toast_error', join(', ', $validator->messages()->all()))
                    ->withInput()
                    ->withErrors($validator->messages()->all());
            }

            $oldDataAdmin = User::where('id', $adminId)->first();

            $newAdmin = $request->except('role');
            $newAdmin['password'] = Crypt::encryptString($newAdmin['password']);

            if (!empty($request->image_profile)) {
                $imageProfile = $request->image_profile;
                $imageName = Str::random(10) . '.' . $imageProfile->getClientOriginalExtension();

                $imageProfile->storeAs('public/avatar/', $imageName);
                $newAdmin['image_profile'] = $imageName;

                //delete old image
                Storage::delete('public/avatar/' . $oldDataAdmin->image_profile);
            }

            DB::beginTransaction();
            try {
                $oldDataAdmin->update($newAdmin);
                DB::commit();
                return back()
                    ->with('toast_success', 'Data Profile Diperbarui');
            } catch (\Throwable $th) {
                DB::rollback();
                return back()
                    ->with('toast_error', $th->getMessage())
                    ->withInput()
                    ->withErrors($th->getMessage());
            }
        } else {
            return back()->with('toast_error', 'Akses Ditolak!!');
        }
    }

    public function setModeSession(Request $request)
    {
        if ($request->mode === 'lightMode') {
            $request->session()->put('mode', 'lightMode');
        } else {
            $request->session()->put('mode', 'darkMode');
        }

        return response()->json(['message' => 'now is' . $request->session()->get('mode')], 200);
    }
}
// 
