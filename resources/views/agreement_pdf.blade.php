<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sozo Properties Agreement</title>
    <style>
        @page {
            size: A4;
            margin: 0.8in;
        }

        body {
            font-family: 'Times New Roman', Times, serif;
            margin: 0;
            padding: 0;
            line-height: 1.6;
            font-size: 11pt;
            color: #000;
        }

        .container {
            width: 100%;
            max-width: 7.5in;
            margin: 0 auto;
        }

        .header {
            text-align: center;
            position: relative;
            margin-bottom: 25px;
        }

        .header img {
            position: absolute;
            top: -30px; /* Adjusted: Pushed higher */
            right: -20px; /* Adjusted: Pushed more to the right corner */
            width: 120px;
            height: auto;
        }

        h1, h2 {
            margin: 0;
            font-weight: bold;
        }

        .republic-heading {
            font-size: 16pt;
            text-transform: uppercase;
        }

        .document-title {
            font-size: 14pt;
            text-decoration: underline;
            margin-top: 10px;
        }

        .property-details {
            text-align: center;
            margin: 20px 0;
        }

        .property-details p {
            margin: 5px 0;
            font-weight: bold;
        }

        .section {
            margin-bottom: 20px;
        }

        .section p {
            text-align: justify;
            margin: 0 0 10px 0;
        }

        .section-heading {
            font-weight: bold;
            text-decoration: underline;
            margin-top: 20px;
            margin-bottom: 10px;
        }

        .list-container {
            padding-left: 20px;
            margin: 0;
        }
        
        .list-container li {
            text-align: justify;
            margin-bottom: 10px;
        }

        .between {
            text-align: center;
            font-weight: bold;
            margin: 15px 0;
        }
        
        .signature-block {
            margin-top: 40px;
        }

        .signature-group {
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
            flex-wrap: wrap;
            margin-top: 30px;
        }

        .signature-item {
            flex-basis: 45%;
            margin-bottom: 20px;
        }

        .signature-line {
            border-bottom: 1px solid #000;
            padding-bottom: 5px;
            margin-top: 50px;
        }

        .role {
            font-style: italic;
            font-size: 10pt;
            margin-top: 5px;
        }

        .witness-signatures {
            margin-top: 30px;
        }

        .witness-list {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 30px;
        }

        .witness-list .witness-item {
            margin-bottom: 20px;
        }

        .witness-line {
            border-bottom: 1px solid #000;
            padding-bottom: 5px;
            margin-bottom: 5px;
        }
    </style>
</head>
<body>

<div class="container">
    <div class="header">
        <img src="{{$profile_pic}}" alt="Company Logo">
        <p class="republic-heading">THE REPUBLIC OF UGANDA</p>
    </div>
    
    <div class="property-details">
        <p>LAND (KIBANJA) AT {{ $user_info->estate }} Estate, PLOT {{ $user_info->plot_number }}, {{ $user_info->location }} district</p>
        <p>MEASURING ({{ $user_info->width_1 }}, {{ $user_info->width_2 }}, {{ $user_info->height_1 }}, {{ $user_info->height_2 }}) FT</p>
    </div>
    
    <div class="header">
        <p class="document-title">LAND (KIBANJA) SALE AGREEMENT</p>
    </div>

    <div class="section">
        <p><span style="font-weight: bold;">THIS AGREEMENT</span> is made this day of <span style="font-weight: bold;">{{ $day }}</span>, <span style="font-weight: bold;">{{ $month }}</span>, <span style="font-weight: bold;">{{ $year }}</span>.</p>
    </div>
    
    <p class="between">BETWEEN</p>

    <div class="section">
        <p><span style="font-weight: bold;">Sozo Properties Uganda Ltd</span> of Kireka, Wakiso District, Tel: <span style="font-weight: bold;">0200 407 700</span> (Hereinafter referred to as “the Vendor” which expression shall where the context so admits bind its successors, legal representatives and assignees) of the one part;</p>
    </div>
    
    <p class="between">AND</p>

    <div class="section">
        <p><span style="font-weight: bold;">{{ $user_info->firstname }} {{ $user_info->lastname }}</span>, Tel: <span style="font-weight: bold;">{{ $user_info->phonenumber }}</span>, of <span style="font-weight: bold;">{{ $user_info->location }}</span> District (Hereinafter called “the Purchaser/s” which expression shall where the context so admits include and bind her/his/their successors in title, personal representatives and assignees) of the other part.</p>
        <p>The Vendor and the Purchaser/s shall jointly be referred to as "the Parties".</p>
    </div>

    <div class="section">
        <p><span class="section-heading">WHEREAS:</span></p>
        <p>a. The Vendor is the Kibanja holder of land located at {{ $user_info->location }} District, measuring Plot {{ $user_info->plot_number }}, ({{ $user_info->width_1 }}, {{ $user_info->width_2 }}, {{ $user_info->height_1 }}, {{ $user_info->height_2 }}) Ft, in {{ $user_info->estate }} Estate (hereinafter referred to as "the said kibanja").</p>
        <p>b. The Vendor is desirous of selling the said kibanja to the Purchaser/s, which kibanja shall have an access road.</p>
        <p>c. The Purchaser/s is willing and ready to purchase the same from the Vendor in accordance with the terms stipulated herein.</p>
    </div>

    <div class="section">
        <p><span class="section-heading">NOW THEREFORE THIS AGREEMENT WITNESSES as follows:</span></p>
        <ol class="list-container">
            <li>
                <p><strong>SALE AND PURCHASE</strong></p>
                <p>The Vendor agrees to sell and convey to the Purchaser/s and the Purchaser/s agrees to buy and take from the Vendor the said kibanja/bibanja upon the terms and conditions herein contained.</p>
            </li>
            <li>
                <p><strong>PAYMENT OF THE CONSIDERATION</strong></p>
                <p>a) The Purchaser/s shall pay to the Vendor the entire purchase price indicated on the receipt of payment in cash upon signing this agreement, receipt of which the Vendor shall acknowledge by signing hereon. No further proof shall be required to prove payment of the consideration.</p>
                <p>b) Upon payment of the entire purchase price, the Vendor shall deliver possession of the kibanja to the Purchaser/s.</p>
                <p>c) The Purchaser/s shall not have the right to use the land/kibanja for any purpose, including cultivation or fencing, unless and until they have paid the entire purchase price in full.</p>
            </li>
            <li>
                <p><strong>THE ESTATE CONSERVATION</strong></p>
                <p>It is mutually agreed as follows;</p>
                <p>a) Any trees and flowers along the roads in the estate shall not be cut down, for the beautification of the estate.</p>
                <p>b) Plots shall not be further subdivided into smaller plots but maintained as stated in this agreement for uniformity and beauty.</p>
                <p>c) All construction in the estate shall be done in accordance with the Architectural plan developed by  Architects. The Purchaser shall be bound by the external plan of the house but may alter the interior plan through Sozo Properties Uganda Ltd’s Architect at an extra cost to be met by the Purchaser.</p>
                <p>d) The Architectural plans for different plot dimensions have been shown to the Purchaser prior to the signing of this agreement, and the Purchaser agrees to be bound by them.</p>
                <p>e) The color of the roof shall be ____________________ for beauty and uniformity.</p>
                <p>f) All construction in the Estate shall ONLY be done by the Construction department of Sozo Properties Uganda Ltd to avoid tragedies associated with using unprofessional builders. The Purchaser shall meet the cost of the building plan, approval of the plan, and construction, which are not included in the land purchase price.</p>
                <p>g) The cost of construction and materials shall be subject to prevailing market prices at the time of construction and may be varied by the seller from time to time.</p>
                <p>h) Until the Purchaser is ready to develop the kibanja/land, he/she/they shall regularly weed the grass to avoid pests such as snakes, and shall not keep open holes/pits on their plots to avoid accidents.</p>
                <p>i) The Purchaser, by accepting this Agreement, agrees for themselves and their successors and assigns that the Property shall be used in accordance with the terms herein, such as the type of house constructed, the roofing, and the construction company. The Purchaser also agrees not to install, construct, or erect any structure not in accordance with the terms of this agreement.</p>
                <p>j) Prior to any alteration or demolition of the exterior of the Property, the Owner shall obtain the consent of Sozo Properties Uganda Ltd.</p>
                <p>k) The property shall not be turned into a public worship place (mosques, shrines, or churches) but may be used for private indoor worship. The plot shall also not be used as a burial ground, for piggery or poultry farming, or for the cultivation of narcotics. The Purchaser may, however, use it for the cultivation of seasonal crops.</p>
                <p>l) If the Purchaser desires to resell the property, such sale shall be done through Sozo Properties Uganda Ltd at a commission of 15% of the resale value.</p>
            </li>
        </ol>
    </div>

    <div class="section signature-block">
        <p style="font-weight: bold;">IN WITNESS WHEREOF the parties hereto have executed/signed these presents the day, month, and year first above written.</p>
        <p style="font-weight: bold; margin-top: 20px;">SIGNED by the said:</p>
        <div class="signature-group">
            <div class="signature-item">
                <div class="signature-line"></div>
                <p class="role">THE VENDOR</p>
            </div>
            <div class="signature-item">
                <div class="signature-line"></div>
                <p class="role">THE PURCHASER</p>
            </div>
        </div>
    </div>
    
    <div class="section witness-signatures">
        <p style="font-weight: bold;">IN THE PRESENCE OF:</p>
        <div class="witness-list">
            <div class="witness-item">
                <p>1. Name: __________________________</p>
                <div class="witness-line"></div>
                <p>Signature: _________________________</p>
            </div>
            <div class="witness-item">
                <p>2. Name: __________________________</p>
                <div class="witness-line"></div>
                <p>Signature: _________________________</p>
            </div>
            <div class="witness-item">
                <p>3. Name: __________________________</p>
                <div class="witness-line"></div>
                <p>Signature: _________________________</p>
            </div>
            <div class="witness-item">
                <p>4. Name: __________________________</p>
                <div class="witness-line"></div>
                <p>Signature: _________________________</p>
            </div>
        </div>
    </div>
</div>

</body>
</html>