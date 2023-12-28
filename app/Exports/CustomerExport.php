<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;

class CustomerExport implements FromCollection, WithHeadings
{
    use Exportable;

    protected $results;

    public function __construct(Collection $results)
    {
        $this->results = $results;
    }

    public function collection()
    {
        return $this->results;
    }

    public function headings(): array
    {
        return [
            'id',
            'tally_ref_id',
            'order_no',
            'customer_name',
            'customer_father_name',
            'mobile_no',
            'company',
            'model',
            'delivery_date',
            'installment_due_date',
            'sub_unit',
            'installment_id',
            'terms',
            'delear_name',
            'market_receivable',
            'target_current',
            'target_arrear',
            'target_total',
            'penalty_target',
            'coll_current',
            'collection_arrear',
            'collection_total_no_adv',
            'collection_advance',
            'collection_total_with_adv',
            'penalty_coll',
            'close_balance',
            'due_date_current_coll',
            'employee_id',
            'finance_by',
            'no_of_dues_installment',
            'four_above_due',
            'first_second_due',
            'name_transfer_qty',
            'dp_percentage',
            'term_changed',
            'customer_change',
            'resold',
            'seize_v',
            'thana_upazila_name',
            'district_name',
            'division_name',
            'vtd_status',
            'dealer_commission_status',
            'registration_no',
            'vehicle_type',
            'term_changed_date',
            'resold_date',
            'profit_type',
            'payment_term',
            'sales_condition',
            'seize_v_date',
            'emi_change',
            'eft_status',
            'salesman_id',
            'salesman_name',
            'installment_end_date',
            'last_pay_date',
            'customer_address',
            'nid',
            'created_at',
            'updated_at',
        ];
    }
}
