<!DOCTYPE html>
<html>
<head>
    <title>Sozo Properties</title>
</head>
<style type="text/css">
    body{
        font-family: 'Roboto Condensed', sans-serif;
    }
    .m-0{
        margin: 0px;
    }
    .p-0{
        padding: 0px;
    }
    .pt-5{
        padding-top:5px;
    }
    .mt-10{
        margin-top:10px;
    }
    .text-center{
        text-align:center !important;
    }
    .w-100{
        width: 100%;
    }
    .w-50{
        width:50%;   
    }
    .w-85{
        width:85%;   
    }
    .w-15{
        width:15%;   
    }
    .logo img{
        width:200px;
        height:60px;        
    }
    .gray-color{
        color:#5D5D5D;
    }
    .text-bold{
        font-weight: bold;
    }
    .border{
        border:1px solid black;
    }
    table tr,th,td{
        border: 1px solid #d2d2d2;
        border-collapse:collapse;
        padding:7px 8px;
    }
    table tr th{
        background: #F4F4F4;
        font-size:15px;
    }
    table tr td{
        font-size:13px;
    }
    table{
        border-collapse:collapse;
    }
    .box-text p{
        line-height:10px;
    }
    .float-left{
        float:left;
    }
    .total-part{
        font-size:16px;
        line-height:12px;
    }
    .total-right p{
        padding-right:20px;
    }
</style>
<body>
<div class="head-title">
    <h1 class="text-center m-0 p-0">Sozo Properties Consultants LTD</h1>
        <br>
    <h2 class="text-center m-0 p-0">Receipt Paymnent</h2>

</div>
<div class="add-detail mt-10">
    <div class="w-50 float-left mt-10" style="padding-top: 1rem;">
        <br> 
        <p class="m-0 pt-5 text-bold w-100">Receipt Id - <span class="gray-color">#{{$receipt_no}}</span></p>
        <p class="m-0 pt-5 text-bold w-100">Receipt Date - <span class="gray-color">{{$formattedDate}}</span></p>
    </div>
    
    <div class="w-20 float-left logo mt-10">
        <img src="logo.jpg" alt="Logo" style="height: 6rem;padding-left:10rem;">
    </div>
    <div style="clear: both;"></div>
</div>
<div class="table-section bill-tbl w-100 mt-10">
    <table class="table w-100 mt-10">
        <tr>
            <th class="w-50">Buyer</th>
            <th class="w-50">On behalf of Sozo Properties</th>
        </tr>
        <tr>
            <td>
                <div class="box-text">
                    <p>Name: {{$user_info->firstname}}  {{$user_info->lastname}}</p>
                    <p>NIN :{{$user_info->NIN}}</p>
                    <p>Date of birth : {{$user_info->date_of_birth}}</p>
                </div>
            </td>
            <td>
                <div class="box-text">
                    <p>Name: {{$admin_user_info->firstname}}  {{$admin_user_info->lastname}}</p>
                    <p>Email : {{$user_email}}</p>                    
                    <p>Date  : {{$formattedDate}}</p>
                </div>
            </td>
        </tr>
    </table>
</div>
<div class="table-section bill-tbl w-100 mt-10">
    <table class="table w-100 mt-10">
        <tr>
            <th class="w-50">Payment Method</th>
            <th class="w-50">Reason for payment</th>
        </tr>
        <tr>
            <td style="text-align: center;">Cash </td>
            <td style="text-align: center;">Deposit</td>
            {{-- <td> <span style="font-weight: bold">Plot number</span> {{$user_info->plot_number}} , <span style="font-weight: bold">Estate</span> {{$user_info->estate}} , {{$user_info->width_1}},{{$user_info->width_2}},{{$user_info->height_1}},{{$user_info->height_2}},</td> --}}
        </tr>
    </table>
</div>

<div class="table-section bill-tbl w-100 mt-10">
    <table class="table w-100 mt-10">
        <tr>
            <th class="w-100">Amount paid</th>
        </tr>
        <tr>
            <td>{{$amount_in_words}}</td>
        </tr>
    </table>
</div>


<div class="table-section bill-tbl w-100 mt-10">
    <table class="table w-100 mt-10">
        <tr>
            <th class="w-50">Phone number</th>
            <th class="w-50">Amount paid</th>
            <th class="w-50">Balance</th>
            <th class="w-50">Grand Total</th>
        </tr>
        <tr align="center">
            <td>{{$Phonenumber}}</td>
            <td>{{$Amount}}</td>
            <td>{{$Balance}}</td>
            <td>{{$Amount}}</td>
        </tr>
        <tr>
            <td colspan="7">
                <div class="total-part">
                    <div class="total-left w-85 float-left" align="right">
                        <p>Total Paid</p>
                    </div>
                    <div class="total-right w-15 float-left text-bold" align="right">
                        <p>{{$Amount}}/=</p>
                    </div>
                    <div style="clear: both;"></div>
                </div> 
            </td>
        </tr>
    </table>

    {{-- <p style="font-weight: bold;text-align:center;">Beyond this balance payment date we put the item back on market</p>
    <p style="font-style: italic;text-align:center;">Item returnable in the first two days of purchase not returnable thereafter</p> --}}

      <p style="font-weight: bold;text-align:center;">Thank you for your payment</p> 
      <p style="font-style: italic;text-align:center;"> We are pleased to confirm that upon the successful completion of your payment, you now have full possession and ownership of the plot, till now is an open payment </p>

</div>
</html>