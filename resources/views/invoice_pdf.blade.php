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
        <p class="m-0 pt-5 text-bold w-100">Receipt Id - <span class="gray-color">#1</span></p>
        <p class="m-0 pt-5 text-bold w-100">Receipt order - <span class="gray-color">AB123456A</span></p>
        <p class="m-0 pt-5 text-bold w-100">Receipt Date - <span class="gray-color">22-01-2023</span></p>
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
            <th class="w-50">Seller</th>
        </tr>
        <tr>
            <td>
                <div class="box-text">
                    <p>Name:</p>
                    <p>NIN :</p>
                    <p>Gender:</p>                    
                    <p>Date of birth : </p>
                </div>
            </td>
            <td>
                <div class="box-text">
                    <p>Name  : Sozo Properties</p>
                    <p>Admin :</p>
                    <p>Email :</p>                    
                    <p>Date  : </p>
                </div>
            </td>
        </tr>
    </table>
</div>
<div class="table-section bill-tbl w-100 mt-10">
    <table class="table w-100 mt-10">
        <tr>
            <th class="w-50">Payment Method</th>
            <th class="w-50">Item purchased</th>
        </tr>
        <tr>
            <td>Cash On Delivery </td>
            <td>Free Shipping - Free Shipping</td>
        </tr>
    </table>
</div>

<div class="table-section bill-tbl w-100 mt-10">
    <table class="table w-100 mt-10">
        <tr>
            <th class="w-100">Amount paid</th>
        </tr>
        <tr>
            <td>Free Shipping - Free Shipping</td>
        </tr>
    </table>
</div>


<div class="table-section bill-tbl w-100 mt-10">
    <table class="table w-100 mt-10">
        <tr>
            <th class="w-50">Id</th>
            <th class="w-50">Land/Plot Name</th>
            <th class="w-50">Sale Price</th>
            <th class="w-50">Phone number</th>
            <th class="w-50">Amount paid</th>
            <th class="w-50">Balance</th>
            <th class="w-50">Grand Total</th>
        </tr>
        <tr align="center">
            <td>M101</td>
            <td>Andoid Smart Phone</td>
            <td>$500.2</td>
            <td>3</td>
            <td>$1500</td>
            <td>$50</td>
            <td>$1550.20</td>
        </tr>
        <tr>
            <td colspan="7">
                <div class="total-part">
                    <div class="total-left w-85 float-left" align="right">
                        <p>Sub Total</p>
                        <p>Tax (18%)</p>
                        <p>Total Payable</p>
                    </div>
                    <div class="total-right w-15 float-left text-bold" align="right">
                        <p>$7600</p>
                        <p>$400</p>
                        <p>$8000.00</p>
                    </div>
                    <div style="clear: both;"></div>
                </div> 
            </td>
        </tr>
    </table>
</div>
</html>