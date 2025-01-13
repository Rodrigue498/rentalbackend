<!DOCTYPE html>
<html>

<head>
    <title>Rental Contract</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.5;
        }

        .header,
        .footer {
            text-align: center;
        }

        .content {
            margin: 20px;
        }

        .section {
            margin-bottom: 20px;
        }

        .clause {
            margin-top: 10px;
            padding: 10px;
            border: 1px solid #000;
            background-color: #f9f9f9;
        }
    </style>
</head>

<body>
    <div class="header">
        <h1>Rental Contract</h1>
    </div>
    <div class="content">
        <div class="section">
            <h2>Contract Details</h2>
            <p><strong>Renter Name:</strong> {{ $renterName }}</p>
            <p><strong>Owner Name:</strong> {{ $ownerName }}</p>
        </div>
        <div class="section">
            <h2>Trailer Details</h2>
            <p><strong>Title:</strong> {{ $trailerTitle }}</p>
            <p><strong>Type:</strong> {{ $trailerType }}</p>
            <p><strong>Features:</strong> {{ $trailerFeatures }}</p>
        </div>
        <div class="section">
            <h2>Rental Dates</h2>
            <p><strong>Start Date:</strong> {{ $startDate }}</p>
            <p><strong>End Date:</strong> {{ $endDate }}</p>
        </div>
        <div class="section">
            <h2>Fees</h2>
            <p><strong>Total Price:</strong> ${{ $totalPrice }}</p>
        </div>
        <div class="section">
            <h2>Conditions</h2>
            <p>{{ $conditions }}</p>
        </div>
        <div class="section">
            <h2>Mandatory Clauses</h2>
            <div class="clause">
                <h4>Endorsement 27</h4>
                <p>This rental agreement adheres to the terms and conditions of Endorsement 27, which includes:</p>
                <ul>
                    <li>The renter assumes full liability for the trailer and its use during the rental period.</li>
                    <li>Any damages caused to the trailer must be reported immediately to the owner.</li>
                    <li>The renter must provide proof of valid insurance coverage before the rental period begins.</li>
                </ul>
            </div>
            <div class="clause">
                <h4>Additional Terms</h4>
                <p>The renter agrees to return the trailer in the same condition it was rented, clean and without
                    damage. Late returns will incur additional fees as specified in the rental terms.</p>
            </div>
        </div>
    </div>
    <div class="footer">
        <p>Thank you for using our rental services.</p>
    </div>
</body>
<canvas id="signature-pad" width="600" height="200" style="border:1px solid #000;"></canvas>
<button id="save-signature">Save Signature</button>

<script>
    const canvas = document.getElementById('signature-pad');
    const signaturePad = new SignaturePad(canvas);

    document.getElementById('save-signature').addEventListener('click', function() {
        const signature = signaturePad.toDataURL('image/png');
        console.log(signature);
        // Send the signature to the server
    });
</script>

</html>
