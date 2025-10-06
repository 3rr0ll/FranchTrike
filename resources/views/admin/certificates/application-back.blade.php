<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Motorized Tricycle Franchising Application Form</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: Arial, sans-serif;
            padding: 20px;
            background: #f5f5f5;
        }
        
        .container {
            max-width: 800px;
            margin: 0 auto;
            background: white;
            padding: 30px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #000;
            padding-bottom: 15px;
        }
        
        .header h3 {
            font-size: 14px;
            margin: 5px 0;
            font-weight: normal;
        }
        
        .header h2 {
            font-size: 16px;
            margin: 8px 0;
            font-weight: bold;
        }
        
        .info-section {
            margin-bottom: 20px;
        }
        
        .info-row {
            display: flex;
            margin-bottom: 8px;
            align-items: center;
        }
        
        .info-label {
            font-weight: bold;
            min-width: 150px;
        }
        
        .info-value {
            border-bottom: 1px solid #000;
            flex: 1;
            padding: 2px 5px;
        }
        
        .fees-section {
            margin-top: 30px;
        }
        
        .fees-title {
            font-weight: bold;
            margin-bottom: 15px;
            border-bottom: 1px solid #000;
            padding-bottom: 5px;
        }
        
        .fee-table {
            width: 100%;
            margin-bottom: 20px;
        }
        
        .fee-row {
            display: flex;
            justify-content: space-between;
            padding: 3px 0;
        }
        
        .fee-item {
            flex: 1;
        }
        
        .fee-amount {
            text-align: right;
            min-width: 100px;
        }
        
        .claim-stub {
            margin-top: 30px;
            border-top: 3px dashed #000;
            padding-top: 20px;
        }
        
        .claim-header {
            background: #ff0000;
            color: white;
            padding: 5px 10px;
            font-weight: bold;
            font-size: 18px;
            display: inline-block;
            margin-bottom: 15px;
        }
        
        .signature-section {
            margin-top: 30px;
            text-align: right;
        }
        
        .signature-line {
            border-top: 1px solid #000;
            width: 300px;
            margin-left: auto;
            margin-top: 50px;
            text-align: center;
            padding-top: 5px;
        }
        
        @media print {
            body {
                background: white;
                padding: 0;
            }
            .container {
                box-shadow: none;
                max-width: 100%;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h3>Republic of the Philippines</h3>
            <h3>Province of Batangas</h3>
            <h2>MUNICIPALITY OF PADRE GARCIA</h2>
            <h2>MOTORIZED TRICYCLE FRANCHISING AND REGULATORY BOARD</h2>
            <h2>APPLICATION FORM</h2>
        </div>
        
        <div class="info-section">
            <div class="info-row">
                <span class="info-label">Franchise</span>
                <span class="info-value">{{ $franchise }}</span>
                <span class="info-label" style="margin-left: 20px;">Sticker N</span>
                <span class="info-value">{{ $sticker }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Route</span>
                <span class="info-value">{{ $route }}</span>
                <span class="info-label" style="margin-left: 20px;">Name</span>
                <span class="info-value">{{ $name }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Motor No.:</span>
                <span class="info-value">{{ $motorNo }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Chasis No.:</span>
                <span class="info-value">{{ $chasisNo }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Plate Number</span>
                <span class="info-value">{{ $plateNumber }}</span>
            </div>
        </div>
        
        <div class="fees-section">
            <div class="fees-title">Filing Fee:</div>
            <div class="fee-table">
                @if(isset($fees) && is_array($fees))
                    @foreach($fees as $fee)
                        <div class="fee-row">
                            <div class="fee-item">{{ $fee['item'] ?? '' }}</div>
                            <div class="fee-amount">{{ $fee['amount'] ?? '' }}</div>
                        </div>
                    @endforeach
                @endif
            </div>
        </div>
        
        <div class="claim-stub">
            <div class="claim-header">CLAIM STUB</div>
            <div style="text-align: right; font-weight: bold; margin-bottom: 15px;">
                ON PROCESS FOR SIGNATURE
            </div>
            
            <div class="info-section">
                <div class="info-row">
                    <span class="info-label">Franchise</span>
                    <span class="info-value">{{ $claimFranchise }}</span>
                    <span class="info-label" style="margin-left: 20px;">Sticker N</span>
                    <span class="info-value">{{ $claimSticker }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Route</span>
                    <span class="info-value">{{ $claimRoute }}</span>
                    <span class="info-label" style="margin-left: 20px;">Name</span>
                    <span class="info-value">{{ $claimName }}</span>
                </div>
            </div>
            
            <div class="signature-section">
                <div style="font-weight: bold;">{{ $verifiedBy }}</div>
                <div style="font-size: 12px;">Verified by</div>
            </div>
        </div>
    </div>
</body>
</html>