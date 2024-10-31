<html>
<head>
    <style type="text/css">

        @media print {
            body {
                -webkit-print-color-adjust: exact;
            }

            table.details tr th {
                background-color: #F2F2F2 !important;
            }

            .print_bg {
                background-color: #F2F2F2 !important;
            }

        }

        .print_bg {
            background-color: #F2F2F2 !important;
        }

        body {
            font-family: "Open Sans", helvetica, sans-serif;
            font-size: 13px;
            color: #000000;
        }

        table.logo {
            -webkit-print-color-adjust: exact;
            border-collapse: inherit;
            width: 100%;
            margin-bottom: 10px;
            padding: 10px;
            border-bottom: 2px solid #25221F;

        }

        table.emp {
            width: 100%;
            margin-bottom: 10px;
            padding: 40px;
        }

        table.details, table.payment_details {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
        }

        table.payment_total {
            border-collapse: collapse;
            width: 100%;
            margin-bottom: 10px;
        }

        table.emp tr td {
            width: 30%;
            padding: 10px
        }

        table.details tr th {
            border: 1px solid #000000;
            background-color: #F2F2F2;
            font-size: 15px;
            padding: 10px
        }

        table.details tr td {
            vertical-align: top;
            width: 30%;
            padding: 3px
        }

        table.payment_details > tbody > tr > td {
            border: 1px solid #000000;
            padding: 5px;
        }

        table.payment_total > tbody > tr > td {
            padding: 5px;
            width: 60%
        }

        table.logo > tbody > tr > td {
            border: 1px solid transparent;
        }
    </style>
</head>
<body>
    <table class="logo">
        <tr>
            <td>
    
            </td>
            <td><p style="text-align: right;">

                <img src="data:image/png;base64,{{ base64_encode(file_get_contents(env('APP_URL'). $generalSetting->shop_logo)) }}" height="40px" class="logo-default"/>

            </p>

            <p style="text-align: right;">

                <b>{{$generalSetting->shop_name}}</b><br/>
                {{$generalSetting->shop_address}}<br/>
                <b>Contact</b>: {{$generalSetting->shop_phone}}
                {{$generalSetting->office_email}}

            </p>
        </td>
        </tr>
    </table>
    <table class="emp">
        <tbody>
        <tr>
            <td colspan="3" style="text-align: center; font-size: 18px;"><strong>Payslip <br>
                Salary Month: {{ date('F', mktime(0, 0, 0, $payroll->month, 10))}}, {{$payroll->year}}
                </strong></td>
        </tr>
        <tr class="flex">
            <td><strong>EmployeeID:</strong> {{ $payroll->employeeInfo->employeeID }} </td>
            <td><strong>Name:</strong> {{$payroll->employeeInfo->full_name}}</td>
            <td><strong>Payslip No:</strong> </td>
        </tr>
    
        <tr>
            <td><strong>Department:</strong> </td>
            <td><strong>Designation:</strong> </td>
            <td><strong>Joining Date
                    :</strong> {!! date('d-M,Y',strtotime($payroll->employeeInfo->joining_date)) !!}</td>
        </tr>
        </tbody>
    </table>
    
    <!-- Table for Details -->
    <table class="details">
    
        <tr>
          
            <td>
    
                <table class="payment_details">
                    <tr>
                        <th colspan="2">Payment Type</th>
                    </tr>
                    <tr>
                        <td><strong>Pay Type</strong></td>
    
                        <td><strong>Amount</strong></td>
                    </tr>
                    <tr>
                        <td>Basic</td>
                        <td>  {{number_format($payroll->basic, 2)}}</td>
                    </tr>
                    <tr>
                        <td>Hourly Payment</td>
    
                        <td>  {{number_format($payroll->overtime_pay, 2)}} </td>
                    </tr>
                    <tr>
                        <td>Expense Claim</td>
                        <td>  {{number_format($payroll->expense, 2)}} </td>
                    </tr>
                    @foreach(json_decode($payroll->allowances) as $index=>$value)
                        <tr>
                            <td> {{ $index }}</td>
    
                            <td>  {{number_format($value, 2)}} </td>
                        </tr>
                    @endforeach
                </table>
                <!-- Table for Details -->
            </td>
           
            <td>
                <table class="payment_details">
                    <tr>
                        <th colspan="2">Deductions</th>
                    </tr>
                    <tr>
                        <td><strong>Pay Type</strong></td>
    
                        <td><strong>Amount</strong></td>
                    </tr>
                    @foreach(json_decode($payroll->deductions) as $index=>$value)
    
                        <tr>
                            <td> {{ $index }}</td>
    
                            <td>  {{number_format($value, 2)}} </td>
                        </tr>
                    @endforeach
    
                </table>
            </td>
            <!--  Deductions End-->
        </tr>
    
    </table>
    <!-- Table for Details -->
    <hr>
    <!-- TotalTotal -->
    <table class="payment_total">
    
        <tr>
            <td><strong>&nbsp;</strong></td>
    
            <td>
                <table class="payment_details">
                    <tr>
                        <th colspan="2">Total</th>
                    </tr>
                    <tr>
                        <td>Total Allowances</td>
    
                        <td>  {{number_format($payroll->total_allowance, 2)}} </td>
                    </tr>
    
                    <tr>
                        <td>Total Deductions</td>
    
                        <td>  {{number_format($payroll->total_deduction, 2)}}</td>
                    </tr>
                    <tr class="print_bg">
                        <td><b>Net Salary</b></td>
    
                        <td>   {{number_format($payroll->net_salary, 2)}}</td>
                    </tr>
                </table>
    
            </td>
        </tr>
    
    
    </table>
</body>
</html>
