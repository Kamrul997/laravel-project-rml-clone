<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <title>Customer Account Statement</title>
    <style>
        body {
            font-family: Arial, sans-serif !important;
            padding: 0 30px;
        }
    </style>
</head>

<body style="padding: 0 10px;">
    <div style="border:1px solid none; padding: 20px 10px;">
        <img style="width: 150px;" src="./assets/images/runner.png" alt="">
        <div style="text-align: center;">
            <h1 style="font-size: 16px;">Runner Motors Ltd.</h1>
            <h3 style="font-size: 12px;">138/1, Tejgaon Industrial Area, Dhaka-1208</h3>
            <h1 style="font-size: 16px;">Customer Account Statement</h1>
            <br>
            <hr>
        </div>
        <div style="width: 100%;">
            <!-- table left -->
            <div style="width: 50%; float: left;">

                <table>
                    <tr>
                        <td style="font-size: 12px; vertical-align: top;">Order No.</td>
                        <td style="font-size: 12px; vertical-align: top;">: {{ $customer->order_no }}</td>
                    </tr>
                    <tr>
                        <td style="font-size: 12px; vertical-align: top;">Customer no</td>
                        <td style="font-size: 12px; vertical-align: top;">: {{ $customer->order_no }}</td>
                    </tr>
                    <tr>
                        <td style="font-size: 12px; vertical-align: top;">Customer Name</td>
                        <td style="font-size: 12px; vertical-align: top;">: {{ $customer->customer_name }}</td>
                    </tr>
                    <tr>
                        <td style="font-size: 12px; vertical-align: top;">Address</td>
                        <td style="font-size: 12px; vertical-align: top;">: {{ $customer->customer_address }}</td>
                    </tr>
                    <tr>
                        <td style="font-size: 12px; vertical-align: top;">Area</td>
                        <td style="font-size: 12px; vertical-align: top;">: {{ $customer->sub_unit }}</td>
                    </tr>
                    <tr>
                        <td style="font-size: 12px; vertical-align: top;">Dealer</td>
                        <td style="font-size: 12px; vertical-align: top;">: {{ $customer->delear_name }}</td>
                    </tr>
                    <tr>
                        <td style="font-size: 12px; vertical-align: top;">Product Name</td>
                        <td style="font-size: 12px; vertical-align: top;">: </td>
                    </tr>
                    <tr>
                        <td style="font-size: 12px; vertical-align: top;">Phone</td>
                        <td style="font-size: 12px; vertical-align: top;">: {{ $customer->mobile_no }}</td>
                    </tr>
                    <tr>
                        <td style="font-size: 12px; vertical-align: top;">Promotions</td>
                        <td style="font-size: 12px; vertical-align: top;">: </td>
                    </tr>
                </table>
            </div>


            <!-- table right -->
            <div style="width: 50%; float: right;">
                <table>
                    <tr>
                        <td style="font-size: 12px; vertical-align: top;">Delivery Note No Date</td>
                        <td style="font-size: 12px; vertical-align: top;">: {{ $customer->delivery_date }}</td>
                    </tr>
                    <tr>
                        <td style="font-size: 12px; vertical-align: top;">Total Installment No </td>
                        <td style="font-size: 12px; vertical-align: top;">: </td>
                    </tr>
                    <tr>
                        <td style="font-size: 12px; vertical-align: top;">Engine No</td>
                        <td style="font-size: 12px; vertical-align: top;">: {{ $customer->engine_no }}</td>
                    </tr>
                    <tr>
                        <td style="font-size: 12px; vertical-align: top;">Chassis No</td>
                        <td style="font-size: 12px; vertical-align: top;">: {{ $customer->chesis_no }}</td>
                    </tr>
                    <tr>
                        <td style="font-size: 12px; vertical-align: top;">Registration No</td>
                        <td style="font-size: 12px; vertical-align: top;">: {{ $customer->registration_no }}</td>
                    </tr>
                    <tr>
                        <td style="font-size: 12px; vertical-align: top;">Inst Start Date</td>
                        <td style="font-size: 12px; vertical-align: top;">: {{ $customer->installment_start_date }}
                        </td>
                    </tr>
                    <tr>
                        <td style="font-size: 12px; vertical-align: top;">Last Inst Date</td>
                        <td style="font-size: 12px; vertical-align: top;">: {{ $customer->installment_end_date }}</td>
                    </tr>
                    <tr>
                        <td style="font-size: 12px; vertical-align: top;">Installment</td>
                        <td style="font-size: 12px; vertical-align: top;">: </td>
                    </tr>
                </table>
            </div>
        </div>
        <br>
        <br>
        <br>
        <br>
        <br>
        <br>
        <br>
        <br>
        <br>
        <br>
        <br>
        <br>
        <br>
        <br>
        <div>
            <table
                style="border-top: 1px solid black; overflow: hidden; border-collapse: collapse; width: 100%; font-size: 12px;">

                <tr>
                    <th
                        style="border-top: 1px solid black; border-collapse: collapse; padding: 5px 0px; font-weight: 800;">
                        Date</th>
                    <th style="font-weight: 800;">Particulars</th>
                    <th style="font-weight: 800;">Voucher type</th>
                    <th style="font-weight: 800;">Debit Amount</th>
                    <th style="font-weight: 800;">Credit Amount</th>
                </tr>
                @foreach ($accountStatement as $item)
                    <tr>
                        <th style="border-top: 1px solid black; border-collapse: collapse; padding: 5px 0px;">
                            {{ $item->vch_date }}</th>
                        <th style="border-top: 1px solid black;">{{ $item->particulars }}</th>
                        <th style="border-top: 1px solid black;">{{ $item->vch_type }}</th>
                        <th style="border-top: 1px solid black;">{{ $item->debit_amount }}</th>
                        <th style="border-top: 1px solid black;">{{ $item->credit_amount }}</th>
                    </tr>
                @endforeach

                <tr>
                    <th colspan="3"
                        style="border-top: 1px solid black; border-bottom: 1px solid black; border-collapse: collapse; padding: 5px 0px;">
                    </th>

                    <th style="border-top: 1px solid black; border-bottom: 1px solid black;">
                        {{ number_format($debit) }}</th>
                    <th style="border-top: 1px solid black; border-bottom: 1px solid black;">
                        {{ number_format($credit) }}</th>
                </tr>
                <tr>
                    <th colspan="3"
                        style="border-top: 1px solid black; border-bottom: 1px solid black; border-collapse: collapse; padding: 8px 0px;">
                    </th>

                    <th style="border-top: 1px solid black; border-bottom: 1px solid black;">Net Receivable</th>
                    <th style="border-top: 1px solid black; border-bottom: 1px solid black;">
                        {{ number_format(max($debit - $credit, 0)) }}</th>
                </tr>

            </table>
        </div>


    </div>
</body>

</html>
