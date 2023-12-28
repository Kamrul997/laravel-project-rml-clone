<?php

namespace App\Http\Traits;

use App\Models\Bank;

use App\Models\Branch;

use App\Models\Target;

use App\Models\SubUnit;

use App\Models\Collection;

use App\Models\DepositType;

use App\Models\MakePayment;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

trait MakePaymentTrait
{
    // public function savePayment($collection_id)
    // {

    //     if (Auth::user()->hasRole('Accounts')) {
    //         $data = Collection::whereIn('id', $collection_id)
    //             ->where('status', 1)
    //             ->where('hod_approved_status', 1)
    //             ->get();

    //         foreach ($data as $item) {

    //             $subunit = SubUnit::where('id', $item->unit)->first();

    //             $customer = Target::where('id', $item->target_sheet_id)->first();

    //             $deposit = DepositType::where('id', $item->deposit_type)->first();

    //             $bank = Bank::where('id', $item->bank_name)->first();

    //             $branch = Branch::where('id', $item->branch_name)->first();

    //             $emi = 0;

    //             $InsertData = [
    //                 'sub_unit_id' => $subunit ? $subunit->id : null,
    //                 'sub_unit_name' => $subunit ? $subunit->name : null,
    //                 'customer_id' => $customer ? $customer->cutomer_id : null,
    //                 'customer_name' => $customer ? $customer->cutomer_name : null,
    //                 'order_no' => $customer ? $customer->ord_no : null,
    //                 'deposit_date' => $item->deposit_date,
    //                 'emi' => null,
    //                 'vts' => $item->vts_charge,
    //                 'name_transfer_fees' => $item->name_transfer_fees,
    //                 'ownership_others' => $item->ownership_charge,
    //                 'brta' => $item->brta_charge,
    //                 'bank_charge' => null,
    //                 'panalty' => $item->rotl,
    //                 'total' => $item->collection_amount + $item->vts_charge + $item->ownership_charge + $item->name_transfer_fees + $item->rotl + $item->brta_charge + $item->others,
    //                 'deposit_type_id' => $deposit ? $deposit->id : null,
    //                 'deposit_type' => $deposit ? $deposit->deposit_type : null,
    //                 'branch_id' => $branch ? $branch->id : null,
    //                 'branch_name' => $branch ? $branch->branch_name : null,
    //                 'bank_id' => $bank ? $bank->id : null,
    //                 'bank_name' => $bank ? $bank->bank_name : null,
    //                 'naration' => ($subunit ? $subunit->name : null) . "+" .
    //                     ($customer ? $customer->ord_no : null) . "+" .
    //                     ($bank ? $bank->bank_name : null) . "+" .
    //                     ($branch ? $branch->branch_name : null) . "+" .
    //                     ($deposit ? $deposit->deposit_type : null) . "+" .
    //                     $item->deposit_date . "+" .
    //                     ($customer ? $customer->ord_no : null) . "+" .
    //                     ($emi + $item->vts_charge),
    //                 'created_by' => auth()->user()->id,
    //                 'created_at' => date('Y-m-d H:i:s'),
    //                 'updated_at' => null,
    //             ];
    //             MakePayment::insert($InsertData);
    //         }
    //     }
    // }

    public function storeArrayPayment($collection_id)
    {

        if (Auth::user()->hasRole('Accounts')) {
            $data = Collection::whereIn('id', $collection_id)
                ->where('status', 1)
                ->where('hod_approved_status', 1)
                ->get();

            foreach ($data as $item) {

                $subunit = SubUnit::where('id', $item->unit)->first();

                $customer = Target::where('id', $item->target_sheet_id)->first();

                $deposit = DepositType::where('id', $item->deposit_type)->first();

                $bank = Bank::where('id', $item->bank_name)->first();

                $branch = Branch::where('id', $item->branch_name)->first();

                // Collection Amount
                if ((float)$item->collection_amount > 0) {
                    MakePayment::insert([
                        'order_no' => $customer ? $customer->ord_no : null,
                        'customer_name' => $customer ? $customer->cutomer_name : null,
                        'customer_id' => $customer ? $customer->cutomer_id : null,
                        'gl_code' => $customer ? $customer->ord_no : null,
                        'sub_unit_id' => $subunit ? $subunit->id : null,
                        'sub_unit_name' => $subunit ? $subunit->name : null,
                        'total' => $item->collection_amount,
                        'deposit_date' => $item->deposit_date,
                        'deposit_type_id' => $deposit ? $deposit->id : null,
                        'deposit_type' => $deposit ? $deposit->deposit_type : null,
                        'branch_id' => $branch ? $branch->id : null,
                        'branch_name' => $branch ? $branch->branch_name : null,
                        'bank_id' => $bank ? $bank->id : null,
                        'bank_name' => $bank ? $bank->bank_name : null,
                        'naration' => ($subunit ? $subunit->name : null) . "+" .
                            ($customer ? $customer->ord_no : null) . "+" .
                            ($bank ? $bank->bank_name : null) . "+" .
                            ($branch ? $branch->branch_name : null) . "+" .
                            ($deposit ? $deposit->deposit_type : null) . "+" .
                            $item->deposit_date . "+" .
                            ($customer ? $customer->ord_no : null) . "+" .
                            ' (Collection Amount)',
                        'created_by' => auth()->user()->id,
                        'created_at' => date('Y-m-d H:i:s'),
                        'updated_at' => null,
                    ]);

                    DB::connection('remoteConnection')->table('sbe_make_payments')->insert([
                        'order_no' => $customer ? $customer->ord_no : null,
                        'customer_name' => $customer ? $customer->cutomer_name : null,
                        'gl_code' => $customer ? $customer->ord_no : null,
                        'amount' => $item->collection_amount,
                        'deposit_date' => $item->deposit_date,
                        'deposit_type' => $deposit ? $deposit->deposit_type : null,
                        'branch_name' => $branch ? $branch->branch_name : null,
                        'bank_name' => $bank ? $bank->bank_name : null,
                        'naration' => ($subunit ? $subunit->name : null) . "+" .
                            ($customer ? $customer->ord_no : null) . "+" .
                            ($bank ? $bank->bank_name : null) . "+" .
                            ($branch ? $branch->branch_name : null) . "+" .
                            ($deposit ? $deposit->deposit_type : null) . "+" .
                            $item->deposit_date . "+" .
                            ($customer ? $customer->ord_no : null) . "+" .
                            ' (Collection Amount)',
                        'created_at' => date('Y-m-d H:i:s'),
                    ]);
                }

                // VTS Charge
                if ((float)$item->vts_charge > 0) {
                    MakePayment::insert([
                        'order_no' => $customer ? $customer->ord_no : null,
                        'customer_name' => $customer ? $customer->cutomer_name : null,
                        'customer_id' => $customer ? $customer->cutomer_id : null,

                        'gl_code' => $customer ? $customer->ord_no : null,

                        'sub_unit_id' => $subunit ? $subunit->id : null,
                        'sub_unit_name' => $subunit ? $subunit->name : null,

                        'total' => $item->vts_charge,

                        'deposit_date' => $item->deposit_date,
                        'deposit_type_id' => $deposit ? $deposit->id : null,
                        'deposit_type' => $deposit ? $deposit->deposit_type : null,
                        'branch_id' => $branch ? $branch->id : null,
                        'branch_name' => $branch ? $branch->branch_name : null,
                        'bank_id' => $bank ? $bank->id : null,
                        'bank_name' => $bank ? $bank->bank_name : null,
                        'naration' => ($subunit ? $subunit->name : null) . "+" .
                            ($customer ? $customer->ord_no : null) . "+" .
                            ($bank ? $bank->bank_name : null) . "+" .
                            ($branch ? $branch->branch_name : null) . "+" .
                            ($deposit ? $deposit->deposit_type : null) . "+" .
                            $item->deposit_date . "+" .
                            ($customer ? $customer->ord_no : null) . "+" .
                            ' (VTS)',
                        'created_by' => auth()->user()->id,
                        'created_at' => date('Y-m-d H:i:s'),
                        'updated_at' => null,
                    ]);

                    DB::connection('remoteConnection')->table('sbe_make_payments')->insert([
                        'order_no' => $customer ? $customer->ord_no : null,
                        'customer_name' => $customer ? $customer->cutomer_name : null,
                        'gl_code' => $customer ? $customer->ord_no : null,
                        'amount' => $item->vts_charge,
                        'deposit_date' => $item->deposit_date,
                        'deposit_type' => $deposit ? $deposit->deposit_type : null,
                        'branch_name' => $branch ? $branch->branch_name : null,
                        'bank_name' => $bank ? $bank->bank_name : null,
                        'naration' => ($subunit ? $subunit->name : null) . "+" .
                            ($customer ? $customer->ord_no : null) . "+" .
                            ($bank ? $bank->bank_name : null) . "+" .
                            ($branch ? $branch->branch_name : null) . "+" .
                            ($deposit ? $deposit->deposit_type : null) . "+" .
                            $item->deposit_date . "+" .
                            ($customer ? $customer->ord_no : null) . "+" .
                            ' (VTS)',
                        'created_at' => date('Y-m-d H:i:s'),
                    ]);
                }

                // Ownership Charge
                if ((float)$item->ownership_charge > 0) {
                    MakePayment::insert([
                        'order_no' => $customer ? $customer->ord_no : null,
                        'customer_name' => $customer ? $customer->cutomer_name : null,
                        'customer_id' => $customer ? $customer->cutomer_id : null,

                        'gl_code' => 'RML-10001',

                        'sub_unit_id' => $subunit ? $subunit->id : null,
                        'sub_unit_name' => $subunit ? $subunit->name : null,

                        'total' => $item->ownership_charge,

                        'deposit_date' => $item->deposit_date,
                        'deposit_type_id' => $deposit ? $deposit->id : null,
                        'deposit_type' => $deposit ? $deposit->deposit_type : null,
                        'branch_id' => $branch ? $branch->id : null,
                        'branch_name' => $branch ? $branch->branch_name : null,
                        'bank_id' => $bank ? $bank->id : null,
                        'bank_name' => $bank ? $bank->bank_name : null,
                        'naration' => ($subunit ? $subunit->name : null) . "+" .
                            ($customer ? $customer->ord_no : null) . "+" .
                            ($bank ? $bank->bank_name : null) . "+" .
                            ($branch ? $branch->branch_name : null) . "+" .
                            ($deposit ? $deposit->deposit_type : null) . "+" .
                            $item->deposit_date . "+" .
                            ($customer ? $customer->ord_no : null) . "+" .
                            ' (Ownership Charge)',
                        'created_by' => auth()->user()->id,
                        'created_at' => date('Y-m-d H:i:s'),
                        'updated_at' => null,
                    ]);

                    DB::connection('remoteConnection')->table('sbe_make_payments')->insert([
                        'order_no' => $customer ? $customer->ord_no : null,
                        'customer_name' => $customer ? $customer->cutomer_name : null,
                        'gl_code' => 'RML-10001',
                        'amount' => $item->ownership_charge,
                        'deposit_date' => $item->deposit_date,
                        'deposit_type' => $deposit ? $deposit->deposit_type : null,
                        'branch_name' => $branch ? $branch->branch_name : null,
                        'bank_name' => $bank ? $bank->bank_name : null,
                        'naration' => ($subunit ? $subunit->name : null) . "+" .
                            ($customer ? $customer->ord_no : null) . "+" .
                            ($bank ? $bank->bank_name : null) . "+" .
                            ($branch ? $branch->branch_name : null) . "+" .
                            ($deposit ? $deposit->deposit_type : null) . "+" .
                            $item->deposit_date . "+" .
                            ($customer ? $customer->ord_no : null) . "+" .
                            ' (Ownership Charge)',
                        'created_at' => date('Y-m-d H:i:s'),
                    ]);
                }

                // Name Transfer Fees
                if ((float)$item->name_transfer_fees > 0) {
                    MakePayment::insert([
                        'order_no' => $customer ? $customer->ord_no : null,
                        'customer_name' => $customer ? $customer->cutomer_name : null,
                        'customer_id' => $customer ? $customer->cutomer_id : null,

                        'gl_code' => 'RML-10002',

                        'sub_unit_id' => $subunit ? $subunit->id : null,
                        'sub_unit_name' => $subunit ? $subunit->name : null,

                        'total' => $item->name_transfer_fees,

                        'deposit_date' => $item->deposit_date,
                        'deposit_type_id' => $deposit ? $deposit->id : null,
                        'deposit_type' => $deposit ? $deposit->deposit_type : null,
                        'branch_id' => $branch ? $branch->id : null,
                        'branch_name' => $branch ? $branch->branch_name : null,
                        'bank_id' => $bank ? $bank->id : null,
                        'bank_name' => $bank ? $bank->bank_name : null,
                        'naration' => ($subunit ? $subunit->name : null) . "+" .
                            ($customer ? $customer->ord_no : null) . "+" .
                            ($bank ? $bank->bank_name : null) . "+" .
                            ($branch ? $branch->branch_name : null) . "+" .
                            ($deposit ? $deposit->deposit_type : null) . "+" .
                            $item->deposit_date . "+" .
                            ($customer ? $customer->ord_no : null) . "+" .
                            ' (Name Transfer Fees)',
                        'created_by' => auth()->user()->id,
                        'created_at' => date('Y-m-d H:i:s'),
                        'updated_at' => null,
                    ]);

                    DB::connection('remoteConnection')->table('sbe_make_payments')->insert([
                        'order_no' => $customer ? $customer->ord_no : null,
                        'customer_name' => $customer ? $customer->cutomer_name : null,
                        'gl_code' => 'RML-10002',
                        'amount' => $item->name_transfer_fees,
                        'deposit_date' => $item->deposit_date,
                        'deposit_type' => $deposit ? $deposit->deposit_type : null,
                        'branch_name' => $branch ? $branch->branch_name : null,
                        'bank_name' => $bank ? $bank->bank_name : null,
                        'naration' => ($subunit ? $subunit->name : null) . "+" .
                            ($customer ? $customer->ord_no : null) . "+" .
                            ($bank ? $bank->bank_name : null) . "+" .
                            ($branch ? $branch->branch_name : null) . "+" .
                            ($deposit ? $deposit->deposit_type : null) . "+" .
                            $item->deposit_date . "+" .
                            ($customer ? $customer->ord_no : null) . "+" .
                            ' (Name Transfer Fees)',
                        'created_at' => date('Y-m-d H:i:s'),
                    ]);
                }

                // Penalty Charge
                if ((float)$item->rotl > 0) {
                    MakePayment::insert([
                        'order_no' => $customer ? $customer->ord_no : null,
                        'customer_name' => $customer ? $customer->cutomer_name : null,
                        'customer_id' => $customer ? $customer->cutomer_id : null,

                        'gl_code' => 'RML-616004',

                        'sub_unit_id' => $subunit ? $subunit->id : null,
                        'sub_unit_name' => $subunit ? $subunit->name : null,

                        'total' => $item->rotl,

                        'deposit_date' => $item->deposit_date,
                        'deposit_type_id' => $deposit ? $deposit->id : null,
                        'deposit_type' => $deposit ? $deposit->deposit_type : null,
                        'branch_id' => $branch ? $branch->id : null,
                        'branch_name' => $branch ? $branch->branch_name : null,
                        'bank_id' => $bank ? $bank->id : null,
                        'bank_name' => $bank ? $bank->bank_name : null,
                        'naration' => ($subunit ? $subunit->name : null) . "+" .
                            ($customer ? $customer->ord_no : null) . "+" .
                            ($bank ? $bank->bank_name : null) . "+" .
                            ($branch ? $branch->branch_name : null) . "+" .
                            ($deposit ? $deposit->deposit_type : null) . "+" .
                            $item->deposit_date . "+" .
                            ($customer ? $customer->ord_no : null) . "+" .
                            ' (Penalty Charge)',
                        'created_by' => auth()->user()->id,
                        'created_at' => date('Y-m-d H:i:s'),
                        'updated_at' => null,
                    ]);

                    DB::connection('remoteConnection')->table('sbe_make_payments')->insert([
                        'order_no' => $customer ? $customer->ord_no : null,
                        'customer_name' => $customer ? $customer->cutomer_name : null,
                        'gl_code' => 'RML-616004',
                        'amount' => $item->rotl,
                        'deposit_date' => $item->deposit_date,
                        'deposit_type' => $deposit ? $deposit->deposit_type : null,
                        'branch_name' => $branch ? $branch->branch_name : null,
                        'bank_name' => $bank ? $bank->bank_name : null,
                        'naration' => ($subunit ? $subunit->name : null) . "+" .
                            ($customer ? $customer->ord_no : null) . "+" .
                            ($bank ? $bank->bank_name : null) . "+" .
                            ($branch ? $branch->branch_name : null) . "+" .
                            ($deposit ? $deposit->deposit_type : null) . "+" .
                            $item->deposit_date . "+" .
                            ($customer ? $customer->ord_no : null) . "+" .
                            ' (Penalty Charge)',
                        'created_at' => date('Y-m-d H:i:s'),
                    ]);
                }

                // BRTA Charge
                if ((float)$item->brta_charge > 0) {
                    MakePayment::insert([
                        'order_no' => $customer ? $customer->ord_no : null,
                        'customer_name' => $customer ? $customer->cutomer_name : null,
                        'customer_id' => $customer ? $customer->cutomer_id : null,

                        'gl_code' => 'RML-10001',

                        'sub_unit_id' => $subunit ? $subunit->id : null,
                        'sub_unit_name' => $subunit ? $subunit->name : null,

                        'total' => $item->brta_charge,

                        'deposit_date' => $item->deposit_date,
                        'deposit_type_id' => $deposit ? $deposit->id : null,
                        'deposit_type' => $deposit ? $deposit->deposit_type : null,
                        'branch_id' => $branch ? $branch->id : null,
                        'branch_name' => $branch ? $branch->branch_name : null,
                        'bank_id' => $bank ? $bank->id : null,
                        'bank_name' => $bank ? $bank->bank_name : null,
                        'naration' => ($subunit ? $subunit->name : null) . "+" .
                            ($customer ? $customer->ord_no : null) . "+" .
                            ($bank ? $bank->bank_name : null) . "+" .
                            ($branch ? $branch->branch_name : null) . "+" .
                            ($deposit ? $deposit->deposit_type : null) . "+" .
                            $item->deposit_date . "+" .
                            ($customer ? $customer->ord_no : null) . "+" .
                            ' (BRTA)',
                        'created_by' => auth()->user()->id,
                        'created_at' => date('Y-m-d H:i:s'),
                        'updated_at' => null,
                    ]);

                    DB::connection('remoteConnection')->table('sbe_make_payments')->insert([
                        'order_no' => $customer ? $customer->ord_no : null,
                        'customer_name' => $customer ? $customer->cutomer_name : null,
                        'gl_code' => 'RML-10001',
                        'amount' => $item->brta_charge,
                        'deposit_date' => $item->deposit_date,
                        'deposit_type' => $deposit ? $deposit->deposit_type : null,
                        'branch_name' => $branch ? $branch->branch_name : null,
                        'bank_name' => $bank ? $bank->bank_name : null,
                        'naration' => ($subunit ? $subunit->name : null) . "+" .
                            ($customer ? $customer->ord_no : null) . "+" .
                            ($bank ? $bank->bank_name : null) . "+" .
                            ($branch ? $branch->branch_name : null) . "+" .
                            ($deposit ? $deposit->deposit_type : null) . "+" .
                            $item->deposit_date . "+" .
                            ($customer ? $customer->ord_no : null) . "+" .
                            ' (BRTA)',
                        'created_at' => date('Y-m-d H:i:s'),
                    ]);
                }

                // Other Charge
                if ((float)$item->others > 0) {
                    MakePayment::insert([
                        'order_no' => $customer ? $customer->ord_no : null,
                        'customer_name' => $customer ? $customer->cutomer_name : null,
                        'customer_id' => $customer ? $customer->cutomer_id : null,

                        'gl_code' => 'RML-10001',

                        'sub_unit_id' => $subunit ? $subunit->id : null,
                        'sub_unit_name' => $subunit ? $subunit->name : null,

                        'total' => $item->others,

                        'deposit_date' => $item->deposit_date,
                        'deposit_type_id' => $deposit ? $deposit->id : null,
                        'deposit_type' => $deposit ? $deposit->deposit_type : null,
                        'branch_id' => $branch ? $branch->id : null,
                        'branch_name' => $branch ? $branch->branch_name : null,
                        'bank_id' => $bank ? $bank->id : null,
                        'bank_name' => $bank ? $bank->bank_name : null,
                        'naration' => ($subunit ? $subunit->name : null) . "+" .
                            ($customer ? $customer->ord_no : null) . "+" .
                            ($bank ? $bank->bank_name : null) . "+" .
                            ($branch ? $branch->branch_name : null) . "+" .
                            ($deposit ? $deposit->deposit_type : null) . "+" .
                            $item->deposit_date . "+" .
                            ($customer ? $customer->ord_no : null) . "+" .
                            ' (Other)',
                        'created_by' => auth()->user()->id,
                        'created_at' => date('Y-m-d H:i:s'),
                        'updated_at' => null,
                    ]);

                    DB::connection('remoteConnection')->table('sbe_make_payments')->insert([
                        'order_no' => $customer ? $customer->ord_no : null,
                        'customer_name' => $customer ? $customer->cutomer_name : null,
                        'gl_code' => 'RML-10001',
                        'amount' => $item->others,
                        'deposit_date' => $item->deposit_date,
                        'deposit_type' => $deposit ? $deposit->deposit_type : null,
                        'branch_name' => $branch ? $branch->branch_name : null,
                        'bank_name' => $bank ? $bank->bank_name : null,
                        'naration' => ($subunit ? $subunit->name : null) . "+" .
                            ($customer ? $customer->ord_no : null) . "+" .
                            ($bank ? $bank->bank_name : null) . "+" .
                            ($branch ? $branch->branch_name : null) . "+" .
                            ($deposit ? $deposit->deposit_type : null) . "+" .
                            $item->deposit_date . "+" .
                            ($customer ? $customer->ord_no : null) . "+" .
                            ' (Other)',
                        'created_at' => date('Y-m-d H:i:s'),
                    ]);
                }
            }
        }
    }

    public function storePayment($collection_id)
    {

        if (Auth::user()->hasRole('Accounts')) {
            $item = Collection::where('id', $collection_id)
                ->where('status', 1)
                ->where('hod_approved_status', 2)
                ->first();

            $subunit = SubUnit::where('id', $item->unit)->first();

            $customer = Target::where('id', $item->target_sheet_id)->first();

            $deposit = DepositType::where('id', $item->deposit_type)->first();

            $bank = Bank::where('id', $item->bank_name)->first();

            $branch = Branch::where('id', $item->branch_name)->first();

            // Collection Amount
            if ((float)$item->collection_amount > 0) {
                MakePayment::insert([
                    'order_no' => $customer ? $customer->ord_no : null,
                    'customer_name' => $customer ? $customer->cutomer_name : null,
                    'customer_id' => $customer ? $customer->cutomer_id : null,
                    'gl_code' => $customer ? $customer->ord_no : null,
                    'sub_unit_id' => $subunit ? $subunit->id : null,
                    'sub_unit_name' => $subunit ? $subunit->name : null,
                    'total' => $item->collection_amount,
                    'deposit_date' => $item->deposit_date,
                    'deposit_type_id' => $deposit ? $deposit->id : null,
                    'deposit_type' => $deposit ? $deposit->deposit_type : null,
                    'branch_id' => $branch ? $branch->id : null,
                    'branch_name' => $branch ? $branch->branch_name : null,
                    'bank_id' => $bank ? $bank->id : null,
                    'bank_name' => $bank ? $bank->bank_name : null,
                    'naration' => ($subunit ? $subunit->name : null) . "+" .
                        ($customer ? $customer->ord_no : null) . "+" .
                        ($bank ? $bank->bank_name : null) . "+" .
                        ($branch ? $branch->branch_name : null) . "+" .
                        ($deposit ? $deposit->deposit_type : null) . "+" .
                        $item->deposit_date . "+" .
                        ($customer ? $customer->ord_no : null) . "+" .
                        ' (Collection Amount)',
                    'created_by' => auth()->user()->id,
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => null,
                ]);

                DB::connection('remoteConnection')->table('sbe_make_payments')->insert([
                    'order_no' => $customer ? $customer->ord_no : null,
                    'customer_name' => $customer ? $customer->cutomer_name : null,
                    'gl_code' => $customer ? $customer->ord_no : null,
                    'amount' => $item->collection_amount,
                    'deposit_date' => $item->deposit_date,
                    'deposit_type' => $deposit ? $deposit->deposit_type : null,
                    'branch_name' => $branch ? $branch->branch_name : null,
                    'bank_name' => $bank ? $bank->bank_name : null,
                    'naration' => ($subunit ? $subunit->name : null) . "+" .
                        ($customer ? $customer->ord_no : null) . "+" .
                        ($bank ? $bank->bank_name : null) . "+" .
                        ($branch ? $branch->branch_name : null) . "+" .
                        ($deposit ? $deposit->deposit_type : null) . "+" .
                        $item->deposit_date . "+" .
                        ($customer ? $customer->ord_no : null) . "+" .
                        ' (Collection Amount)',
                    'created_at' => date('Y-m-d H:i:s'),
                ]);
            }

            // VTS Charge
            if ((float)$item->vts_charge > 0) {
                MakePayment::insert([
                    'order_no' => $customer ? $customer->ord_no : null,
                    'customer_name' => $customer ? $customer->cutomer_name : null,
                    'customer_id' => $customer ? $customer->cutomer_id : null,

                    'gl_code' => $customer ? $customer->ord_no : null,

                    'sub_unit_id' => $subunit ? $subunit->id : null,
                    'sub_unit_name' => $subunit ? $subunit->name : null,

                    'total' => $item->vts_charge,

                    'deposit_date' => $item->deposit_date,
                    'deposit_type_id' => $deposit ? $deposit->id : null,
                    'deposit_type' => $deposit ? $deposit->deposit_type : null,
                    'branch_id' => $branch ? $branch->id : null,
                    'branch_name' => $branch ? $branch->branch_name : null,
                    'bank_id' => $bank ? $bank->id : null,
                    'bank_name' => $bank ? $bank->bank_name : null,
                    'naration' => ($subunit ? $subunit->name : null) . "+" .
                        ($customer ? $customer->ord_no : null) . "+" .
                        ($bank ? $bank->bank_name : null) . "+" .
                        ($branch ? $branch->branch_name : null) . "+" .
                        ($deposit ? $deposit->deposit_type : null) . "+" .
                        $item->deposit_date . "+" .
                        ($customer ? $customer->ord_no : null) . "+" .
                        ' (VTS)',
                    'created_by' => auth()->user()->id,
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => null,
                ]);

                DB::connection('remoteConnection')->table('sbe_make_payments')->insert([
                    'order_no' => $customer ? $customer->ord_no : null,
                    'customer_name' => $customer ? $customer->cutomer_name : null,
                    'gl_code' => $customer ? $customer->ord_no : null,
                    'amount' => $item->vts_charge,
                    'deposit_date' => $item->deposit_date,
                    'deposit_type' => $deposit ? $deposit->deposit_type : null,
                    'branch_name' => $branch ? $branch->branch_name : null,
                    'bank_name' => $bank ? $bank->bank_name : null,
                    'naration' => ($subunit ? $subunit->name : null) . "+" .
                        ($customer ? $customer->ord_no : null) . "+" .
                        ($bank ? $bank->bank_name : null) . "+" .
                        ($branch ? $branch->branch_name : null) . "+" .
                        ($deposit ? $deposit->deposit_type : null) . "+" .
                        $item->deposit_date . "+" .
                        ($customer ? $customer->ord_no : null) . "+" .
                        ' (VTS)',
                    'created_at' => date('Y-m-d H:i:s'),
                ]);
            }

            // Ownership Charge
            if ((float)$item->ownership_charge > 0) {
                MakePayment::insert([
                    'order_no' => $customer ? $customer->ord_no : null,
                    'customer_name' => $customer ? $customer->cutomer_name : null,
                    'customer_id' => $customer ? $customer->cutomer_id : null,

                    'gl_code' => 'RML-10001',

                    'sub_unit_id' => $subunit ? $subunit->id : null,
                    'sub_unit_name' => $subunit ? $subunit->name : null,

                    'total' => $item->ownership_charge,

                    'deposit_date' => $item->deposit_date,
                    'deposit_type_id' => $deposit ? $deposit->id : null,
                    'deposit_type' => $deposit ? $deposit->deposit_type : null,
                    'branch_id' => $branch ? $branch->id : null,
                    'branch_name' => $branch ? $branch->branch_name : null,
                    'bank_id' => $bank ? $bank->id : null,
                    'bank_name' => $bank ? $bank->bank_name : null,
                    'naration' => ($subunit ? $subunit->name : null) . "+" .
                        ($customer ? $customer->ord_no : null) . "+" .
                        ($bank ? $bank->bank_name : null) . "+" .
                        ($branch ? $branch->branch_name : null) . "+" .
                        ($deposit ? $deposit->deposit_type : null) . "+" .
                        $item->deposit_date . "+" .
                        ($customer ? $customer->ord_no : null) . "+" .
                        ' (Ownership Charge)',
                    'created_by' => auth()->user()->id,
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => null,
                ]);

                DB::connection('remoteConnection')->table('sbe_make_payments')->insert([
                    'order_no' => $customer ? $customer->ord_no : null,
                    'customer_name' => $customer ? $customer->cutomer_name : null,
                    'gl_code' => 'RML-10001',
                    'amount' => $item->ownership_charge,
                    'deposit_date' => $item->deposit_date,
                    'deposit_type' => $deposit ? $deposit->deposit_type : null,
                    'branch_name' => $branch ? $branch->branch_name : null,
                    'bank_name' => $bank ? $bank->bank_name : null,
                    'naration' => ($subunit ? $subunit->name : null) . "+" .
                        ($customer ? $customer->ord_no : null) . "+" .
                        ($bank ? $bank->bank_name : null) . "+" .
                        ($branch ? $branch->branch_name : null) . "+" .
                        ($deposit ? $deposit->deposit_type : null) . "+" .
                        $item->deposit_date . "+" .
                        ($customer ? $customer->ord_no : null) . "+" .
                        ' (Ownership Charge)',
                    'created_at' => date('Y-m-d H:i:s'),
                ]);
            }

            // Name Transfer Fees
            if ((float)$item->name_transfer_fees > 0) {
                MakePayment::insert([
                    'order_no' => $customer ? $customer->ord_no : null,
                    'customer_name' => $customer ? $customer->cutomer_name : null,
                    'customer_id' => $customer ? $customer->cutomer_id : null,

                    'gl_code' => 'RML-10002',

                    'sub_unit_id' => $subunit ? $subunit->id : null,
                    'sub_unit_name' => $subunit ? $subunit->name : null,

                    'total' => $item->name_transfer_fees,

                    'deposit_date' => $item->deposit_date,
                    'deposit_type_id' => $deposit ? $deposit->id : null,
                    'deposit_type' => $deposit ? $deposit->deposit_type : null,
                    'branch_id' => $branch ? $branch->id : null,
                    'branch_name' => $branch ? $branch->branch_name : null,
                    'bank_id' => $bank ? $bank->id : null,
                    'bank_name' => $bank ? $bank->bank_name : null,
                    'naration' => ($subunit ? $subunit->name : null) . "+" .
                        ($customer ? $customer->ord_no : null) . "+" .
                        ($bank ? $bank->bank_name : null) . "+" .
                        ($branch ? $branch->branch_name : null) . "+" .
                        ($deposit ? $deposit->deposit_type : null) . "+" .
                        $item->deposit_date . "+" .
                        ($customer ? $customer->ord_no : null) . "+" .
                        ' (Name Transfer Fees)',
                    'created_by' => auth()->user()->id,
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => null,
                ]);

                DB::connection('remoteConnection')->table('sbe_make_payments')->insert([
                    'order_no' => $customer ? $customer->ord_no : null,
                    'customer_name' => $customer ? $customer->cutomer_name : null,
                    'gl_code' => 'RML-10002',
                    'amount' => $item->name_transfer_fees,
                    'deposit_date' => $item->deposit_date,
                    'deposit_type' => $deposit ? $deposit->deposit_type : null,
                    'branch_name' => $branch ? $branch->branch_name : null,
                    'bank_name' => $bank ? $bank->bank_name : null,
                    'naration' => ($subunit ? $subunit->name : null) . "+" .
                        ($customer ? $customer->ord_no : null) . "+" .
                        ($bank ? $bank->bank_name : null) . "+" .
                        ($branch ? $branch->branch_name : null) . "+" .
                        ($deposit ? $deposit->deposit_type : null) . "+" .
                        $item->deposit_date . "+" .
                        ($customer ? $customer->ord_no : null) . "+" .
                        ' (Name Transfer Fees)',
                    'created_at' => date('Y-m-d H:i:s'),
                ]);
            }

            // Penalty Charge
            if ((float)$item->rotl > 0) {
                MakePayment::insert([
                    'order_no' => $customer ? $customer->ord_no : null,
                    'customer_name' => $customer ? $customer->cutomer_name : null,
                    'customer_id' => $customer ? $customer->cutomer_id : null,

                    'gl_code' => 'RML-616004',

                    'sub_unit_id' => $subunit ? $subunit->id : null,
                    'sub_unit_name' => $subunit ? $subunit->name : null,

                    'total' => $item->rotl,

                    'deposit_date' => $item->deposit_date,
                    'deposit_type_id' => $deposit ? $deposit->id : null,
                    'deposit_type' => $deposit ? $deposit->deposit_type : null,
                    'branch_id' => $branch ? $branch->id : null,
                    'branch_name' => $branch ? $branch->branch_name : null,
                    'bank_id' => $bank ? $bank->id : null,
                    'bank_name' => $bank ? $bank->bank_name : null,
                    'naration' => ($subunit ? $subunit->name : null) . "+" .
                        ($customer ? $customer->ord_no : null) . "+" .
                        ($bank ? $bank->bank_name : null) . "+" .
                        ($branch ? $branch->branch_name : null) . "+" .
                        ($deposit ? $deposit->deposit_type : null) . "+" .
                        $item->deposit_date . "+" .
                        ($customer ? $customer->ord_no : null) . "+" .
                        ' (Penalty Charge)',
                    'created_by' => auth()->user()->id,
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => null,
                ]);

                DB::connection('remoteConnection')->table('sbe_make_payments')->insert([
                    'order_no' => $customer ? $customer->ord_no : null,
                    'customer_name' => $customer ? $customer->cutomer_name : null,
                    'gl_code' => 'RML-616004',
                    'amount' => $item->rotl,
                    'deposit_date' => $item->deposit_date,
                    'deposit_type' => $deposit ? $deposit->deposit_type : null,
                    'branch_name' => $branch ? $branch->branch_name : null,
                    'bank_name' => $bank ? $bank->bank_name : null,
                    'naration' => ($subunit ? $subunit->name : null) . "+" .
                        ($customer ? $customer->ord_no : null) . "+" .
                        ($bank ? $bank->bank_name : null) . "+" .
                        ($branch ? $branch->branch_name : null) . "+" .
                        ($deposit ? $deposit->deposit_type : null) . "+" .
                        $item->deposit_date . "+" .
                        ($customer ? $customer->ord_no : null) . "+" .
                        ' (Penalty Charge)',
                    'created_at' => date('Y-m-d H:i:s'),
                ]);
            }

            // BRTA Charge
            if ((float)$item->brta_charge > 0) {
                MakePayment::insert([
                    'order_no' => $customer ? $customer->ord_no : null,
                    'customer_name' => $customer ? $customer->cutomer_name : null,
                    'customer_id' => $customer ? $customer->cutomer_id : null,

                    'gl_code' => 'RML-10001',

                    'sub_unit_id' => $subunit ? $subunit->id : null,
                    'sub_unit_name' => $subunit ? $subunit->name : null,

                    'total' => $item->brta_charge,

                    'deposit_date' => $item->deposit_date,
                    'deposit_type_id' => $deposit ? $deposit->id : null,
                    'deposit_type' => $deposit ? $deposit->deposit_type : null,
                    'branch_id' => $branch ? $branch->id : null,
                    'branch_name' => $branch ? $branch->branch_name : null,
                    'bank_id' => $bank ? $bank->id : null,
                    'bank_name' => $bank ? $bank->bank_name : null,
                    'naration' => ($subunit ? $subunit->name : null) . "+" .
                        ($customer ? $customer->ord_no : null) . "+" .
                        ($bank ? $bank->bank_name : null) . "+" .
                        ($branch ? $branch->branch_name : null) . "+" .
                        ($deposit ? $deposit->deposit_type : null) . "+" .
                        $item->deposit_date . "+" .
                        ($customer ? $customer->ord_no : null) . "+" .
                        ' (BRTA)',
                    'created_by' => auth()->user()->id,
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => null,
                ]);

                DB::connection('remoteConnection')->table('sbe_make_payments')->insert([
                    'order_no' => $customer ? $customer->ord_no : null,
                    'customer_name' => $customer ? $customer->cutomer_name : null,
                    'gl_code' => 'RML-10001',
                    'amount' => $item->brta_charge,
                    'deposit_date' => $item->deposit_date,
                    'deposit_type' => $deposit ? $deposit->deposit_type : null,
                    'branch_name' => $branch ? $branch->branch_name : null,
                    'bank_name' => $bank ? $bank->bank_name : null,
                    'naration' => ($subunit ? $subunit->name : null) . "+" .
                        ($customer ? $customer->ord_no : null) . "+" .
                        ($bank ? $bank->bank_name : null) . "+" .
                        ($branch ? $branch->branch_name : null) . "+" .
                        ($deposit ? $deposit->deposit_type : null) . "+" .
                        $item->deposit_date . "+" .
                        ($customer ? $customer->ord_no : null) . "+" .
                        ' (BRTA)',
                    'created_at' => date('Y-m-d H:i:s'),
                ]);
            }

            // Other Charge
            if ((float)$item->others > 0) {
                MakePayment::insert([
                    'order_no' => $customer ? $customer->ord_no : null,
                    'customer_name' => $customer ? $customer->cutomer_name : null,
                    'customer_id' => $customer ? $customer->cutomer_id : null,

                    'gl_code' => 'RML-10001',

                    'sub_unit_id' => $subunit ? $subunit->id : null,
                    'sub_unit_name' => $subunit ? $subunit->name : null,

                    'total' => $item->others,

                    'deposit_date' => $item->deposit_date,
                    'deposit_type_id' => $deposit ? $deposit->id : null,
                    'deposit_type' => $deposit ? $deposit->deposit_type : null,
                    'branch_id' => $branch ? $branch->id : null,
                    'branch_name' => $branch ? $branch->branch_name : null,
                    'bank_id' => $bank ? $bank->id : null,
                    'bank_name' => $bank ? $bank->bank_name : null,
                    'naration' => ($subunit ? $subunit->name : null) . "+" .
                        ($customer ? $customer->ord_no : null) . "+" .
                        ($bank ? $bank->bank_name : null) . "+" .
                        ($branch ? $branch->branch_name : null) . "+" .
                        ($deposit ? $deposit->deposit_type : null) . "+" .
                        $item->deposit_date . "+" .
                        ($customer ? $customer->ord_no : null) . "+" .
                        ' (Other)',
                    'created_by' => auth()->user()->id,
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => null,
                ]);

                DB::connection('remoteConnection')->table('sbe_make_payments')->insert([
                    'order_no' => $customer ? $customer->ord_no : null,
                    'customer_name' => $customer ? $customer->cutomer_name : null,
                    'gl_code' => 'RML-10001',
                    'amount' => $item->others,
                    'deposit_date' => $item->deposit_date,
                    'deposit_type' => $deposit ? $deposit->deposit_type : null,
                    'branch_name' => $branch ? $branch->branch_name : null,
                    'bank_name' => $bank ? $bank->bank_name : null,
                    'naration' => ($subunit ? $subunit->name : null) . "+" .
                        ($customer ? $customer->ord_no : null) . "+" .
                        ($bank ? $bank->bank_name : null) . "+" .
                        ($branch ? $branch->branch_name : null) . "+" .
                        ($deposit ? $deposit->deposit_type : null) . "+" .
                        $item->deposit_date . "+" .
                        ($customer ? $customer->ord_no : null) . "+" .
                        ' (Other)',
                    'created_at' => date('Y-m-d H:i:s'),
                ]);
            }
        }
    }
}
