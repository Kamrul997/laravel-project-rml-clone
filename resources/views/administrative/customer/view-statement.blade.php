<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Runner Invoice HTML</title>
</head>
<body style="font-family: Arial, Helvetica, sans-serif; padding: 0 30px;">
    <div style="border:1px solid none; padding: 50px 20px;">
        <img
        style="width: 150px;"
        src="{{asset('assets/images/runner.png')}}" alt="">
        <div style="text-align: center;">
            <h1 style="font-size: 12px;">Runner Motor Ltd.</h1>
            <h3 style="font-size: 9px;">138/1, Tejgaon Industrial Area, Dhaka-1208</h3>
            <h1 style="font-size: 12px;">Customer Account Statement</h1>
            <br>
            <hr>
        </div>
        <div style="display: flex; justify-content: space-between;">
            <!-- table left -->
            <table>
                <tr>
                    <td style="font-size: 9px;">Order No.</td>
                    <td style="font-size: 9px;">: L12480</td>
                </tr>
                <tr>
                    <td style="font-size: 9px;">Customer no</td>
                    <td style="font-size: 9px;">: L12480 (UVR)</td>
                </tr>
                <tr>
                    <td style="font-size: 9px;">Customer Name</td>
                    <td style="font-size: 9px;">: Nur Mohammad</td>
                </tr>
                <tr>
                    <td style="font-size: 9px;">Address</td>
                    <td style="font-size: 9px;">: House#Road#
                        Mymensing
                        POSTAL CODE</td>
                </tr>
                <tr>
                    <td style="font-size: 9px;">Area</td>
                    <td style="font-size: 9px;">: F2-1</td>
                </tr>
                <tr>
                    <td style="font-size: 9px;">Dealer</td>
                    <td style="font-size: 9px;">: RML-SCV</td>
                </tr>
                <tr>
                    <td style="font-size: 9px;">Product Name</td>
                    <td style="font-size: 9px;">: FREEDOM KMC1048D3 PICK-UP IN CBU 2771CC</td>
                </tr>
                <tr>
                    <td style="font-size: 9px;">Phone</td>
                    <td style="font-size: 9px;">: 01703483738</td>
                </tr>
                <tr>
                    <td style="font-size: 9px;">Promotions</td>
                    <td style="font-size: 9px;">: Free Insurance, Free Registration</td>
                </tr>
            </table>

            <!-- table right -->
            <table>
                <tr>
                    <td style="font-size: 9px;">Delivery Note No Date</td>
                    <td style="font-size: 9px;">: 365773 & 27-Feb-2023</td>
                </tr>
                <tr>
                    <td style="font-size: 9px;">Total Installment No :</td>
                    <td style="font-size: 9px;">: 36</td>
                </tr>
                <tr>
                    <td style="font-size: 9px;">Engine No</td>
                    <td style="font-size: 9px;">: 35102912</td>
                </tr>
                <tr>
                    <td style="font-size: 9px;">Chassis No</td>
                    <td style="font-size: 9px;">: LWU2DM2C4LKM68363</td>
                </tr>
                <tr>
                    <td style="font-size: 9px;">Registration No</td>
                    <td style="font-size: 9px;">: DM-NA-20-8193</td>
                </tr>
                <tr>
                    <td style="font-size: 9px;">Inst Start Date</td>
                    <td style="font-size: 9px;">: 15-Apr-2023</td>
                </tr>
                <tr>
                    <td style="font-size: 9px;">Last Inst Date</td>
                    <td style="font-size: 9px;">: 15-Mar-2026</td>
                </tr>
                <tr>
                    <td style="font-size: 9px;">Installment</td>
                    <td style="font-size: 9px;">: 41,600</td>
                </tr>
            </table>
        </div>
        <br>
        <br>

        <!-- amount breakdown section ----------->
        <div style="font-size: 9px;">
           <table style="border-top: 1px solid black; overflow: hidden; border-collapse: collapse; width: 100%;">

            <tr>
                <th style="border-top: 1px solid black; border-collapse: collapse; padding: 5px 0px; font-weight: 800;" >Date</th>
                <th style="font-weight: 800;">MRN No</th>
                <th style="font-weight: 800;">Description</th>
                <th style="font-weight: 800;">Debit Amount</th>
                <th style="font-weight: 800;">Credit Amount</th>
            </tr>
            <tr >
                <th style="border-top: 1px solid black; border-collapse: collapse; padding: 5px 0px;" >30-Oct-2021</th>
                <th style="border-top: 1px solid black;"></th>
                <th style="border-top: 1px solid black;">VTS</th>
                <th style="border-top: 1px solid black;">30,000.00</th>
                <th style="border-top: 1px solid black;">8,000.00</th>
            </tr>
            <tr >
                <th style=" padding: 5px 0px;" >31-Aug-2021</th>
                <th ></th>
                <th >CI</th>
                <th >7,685.00</th>
                <th >7,685.00</th>
            </tr>
            <tr >
                <th style=" padding: 5px 0px;" >27-Feb-2023</th>
                <th ></th>
                <th >Down Payment</th>
                <th ></th>
                <th >100,000.00</th>
            </tr>
            <tr >
                <th style=" padding: 5px 0px;" >27-Feb-2023</th>
                <th ></th>
                <th >Estimated Profit</th>
                <th >396,415.84</th>
                <th ></th>
            </tr>
            <tr >
                <th style=" padding: 5px 0px;" >07-May-2023</th>
                <th >LM555936</th>
                <th >Collection</th>
                <th ></th>
                <th >42,000.00</th>
            </tr>
            <tr >
                <th colspan="3" style="border-top: 1px solid black; border-bottom: 1px solid black; border-collapse: collapse; padding: 5px 0px;" ></th>

                <th style="border-top: 1px solid black; border-bottom: 1px solid black;">1,651,070.78</th>
                <th style="border-top: 1px solid black; border-bottom: 1px solid black;">157,685.00</th>
            </tr>
            <tr >
                <th colspan="3" style="border-top: 1px solid black; border-bottom: 1px solid black; border-collapse: collapse; padding: 8px 0px;" ></th>

                <th style="border-top: 1px solid black; border-bottom: 1px solid black;">Net Receivable</th>
                <th style="border-top: 1px solid black; border-bottom: 1px solid black;">1,493,385.78</th>
            </tr>

           </table>


        </div>
    </div>
</body>
</html>
