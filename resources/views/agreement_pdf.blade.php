<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
    <title>Sozo Land | Land Sale Agreement</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
            color: #333;
        }

        header {
            background-color: #333;
            color: #fff;
            padding: 0rem;
            text-align: center;
        }

        section {
            padding: 2rem;
            margin: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        h2 {
            color: #333;
        }

        p {
            line-height: 1.5;
        }

        footer {
            background-color: #333;
            color: #fff;
            text-align: center;
            padding: 0rem;
            position: fixed;
            bottom: 0;
            width: 100%;
        }

        .signature-section {
            margin-top: 20px;
        }

        .signatory {
            margin-bottom: 20px;
        }

        .name-signature {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <header>
        <h1>Sozo Land Sale Agreement</h1>
    </header>

    <section>

        <center>
            <h2>THE REPUBLIC OF UGANDA</h2>
            </center>

	<p>LAND (KIBANJA) AT .............{{$user_info->estate}}.,.. plot number {{$user_info->plot_number}}.............DISTRICT</p>

        <p>MEASURING ..........{{$user_info->width_1}} ,{{$user_info->width_2}} ,{{$user_info->height_1}} ,{{$user_info->height_2}}..... </p>

        <center>
        <h2>LAND (KIBANJA) SALE AGREEMENT</h2>
        </center>
        <p>This agreement is made this day of {{$Date_of_payment}}</p>

        <p><strong>BETWEEN</strong></p>
        <p>SOZO PROPERTY CONSULTANTS LIMITED of Kireka, Wakiso District Tel: ..0788962707...(Hereinafter referred to as “the Vendor” which expression shall where the context so admit bind all its successors, legal representatives and assignees) of the one part;</p>
     <p><strong>AND</strong></p>   <p>............{{$user_info->firstname}} {{$user_info->lastname}}.................Tel:.....................of ..................DISTRICT
(Hereinafter called the “the Purchaser/s” which expression shall where the context so admit include and bind all her/his/their successors in title, personal representatives and assignees) of the other part.
</p>
        <p>The Vendor and the Purchaser/s shall jointly be referred to as "the Parties".</p>

        <p><strong>WHEREAS:</strong></p>
        <p>a. The Vendor is the Kibanja holder of land located at ..{{$user_info->location}}......... District measuring ...... {{$user_info->width_1}} ,{{$user_info->width_2}} ,{{$user_info->height_1}} ,{{$user_info->height_2}}..... (said kibanja).</p>
        <p>b. The vendor is desirous of selling the said kibanja to the Purchaser/s. This kibanja shall have an access road.</p>
        <p>c. The Purchaser/s is willing and ready to purchase the same from the Vendor in accordance with the terms stipulated here below;</p>

        <p><strong>NOW THEREFORE THIS AGREEMENT WITNESSES as follows:</strong></p>
        <ol>
            <li><strong>SALE AND PURCHASE</strong></li>
            <p>IN CONSIDERATION of the sum of Ugx...... {{$all_cash}}....../=(Uganda Shillings............................................................................................................................................. Only) to be paid by the Purchaser/s to the Vendor as hereinafter provided, the Vendor agrees to sell and convey to the Purchaser/s and the Purchaser/s agrees to buy and take from the Vendor part of /the said kibanja upon the terms and conditions herein contained.</p>

            <!-- Add other content here -->

            <li><strong>PAYMENT OF THE CONSIDERATION</strong></li>
            <p>a) The Purchaser/s shall pay to the Vendor the sum of Ugx............................................................../=(Uganda Shillings ............................................................................................... Only) in cash upon signing this agreement, receipt of which the Vendor shall acknowledge by signing hereon and there shall be no further proof required to prove payment of the consideration herein.) </p>
            <p>b) The purchaser/s shall pay the balance of Ugx................................................/= (Uganda Shillings...................................................... Only) shall be paid on or before the.......................day of................................20....................</p>

            <p>c) Upon payment of the entire purchase price by the purchaser to the vendor, the vendor will deliver possession of the kibanja to the Purchaser/s.</p>
            <p>d) The Purchaser/s shall have no right to use the land/kibanja for anything unless and until she/he/they have/has paid the entire purchase price in full.</p>
            <p>e) If the purchaser/s does not pay the balance by the..............day of...............................20....................., the vendor shall be at liberty to put back the property on market and shall be given alternative kibanja by the seller whose value is equivalent to the amount so far paid by the purchaser to the vendor.</p>
        </ol>

        <p><strong>IN WITNESS WHEREOF the parties hereto have executed/signed these presents the day, month, and year first above written.</strong></p>

        <div class="signatory">
            <p>In the presence of:</p>
            <div class="name-signature">
                <span>1. …………………………………..</span>
                <span>..........................................</span>
            </div>
            <div class="name-signature">
                <span>2. …………………………………..</span>
                <span>..........................................</span>
            </div>
            <div class="name-signature">
                <span>3. …………………………………..</span>
                <span>..........................................</span>
            </div>
            <div class="name-signature">
                <span>4. …………………………………..</span>
                <span>..........................................</span>
            </div>
        </div>
    </div>

    <div class="signature-section">
        <div class="signatory">
            <p>Signed by the said:</p>
        </div>
        <div class="signatory">
            <div class="name-signature">
                <span>1. .............................................</span>
                <span>Purchaser............................................</span>
            </div>
            <p>PURCHASER</p>
            <div class="name-signature">
                <span>2. ....................................................</span>
                <span>Purchaser............................................</span>
            </div>
            <p>PURCHASER</p>
        </div>
        <div class="signatory">
            <p>In the presence of:</p>
            <div class="name-signature">
                <span>1. …………………………………..</span>
                <span>..........................................</span>
            </div>
            <div class="name-signature">
                <span>2. …………………………………..</span>
                <span>..........................................</span>
            </div>
            <div class="name-signature">
                <span>3. …………………………………..</span>
                <span>..........................................</span>
            </div>
            <div class="name-signature">
                <span>4. …………………………………..</span>
                <span>..........................................</span>
            </div>
        </div>
    </div>
</section>

<footer>
    <p>&copy; 2023 Sozo Land. All rights reserved.</p>
</footer>
</body>
</html>
