<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Mayor's Permit</title>
</head>
<body style="font-family: Arial, sans-serif; margin: 0; padding: 0;">
    @php
        // Expecting $motorDetail, $franchiseApplication, $operator, $granted, etc. from controller
        $ownerFirstName = $operator->first_name ?? '';
        $ownerMiddleName = $operator->middle_initial ?? '';
        $ownerLastName = $operator->last_name ?? '';
        $ownerName = trim("{$ownerFirstName} {$ownerMiddleName} {$ownerLastName}");
        $ownerBarangay = $operator->barangay ?? '';
        $unitType = $motorDetail->unit_type ?? '';
        $franchiseNo = $franchiseApplication->franchise_no ?? '';
        $orNo = $franchiseApplication->or_no ?? '';
        $grantedAmount = $granted->amount ?? '';
        $ctcNo = $franchiseApplication->ctc_no ?? '';
        $ctcIssuedOn = $franchiseApplication->ctc_issued_on ? date('F d, Y', strtotime($franchiseApplication->ctc_issued_on)) : '';
        $ctcIssuedAt = $franchiseApplication->ctc_issued_at ?? '';
       
    @endphp

    <!-- Background -->
    <div style="position: relative; width: 800px; height: 1100px; margin: auto; padding: 40px;  background: #fff;">

        <!-- Header -->
        <div style="text-align: center; position: relative;">
            <img src="{{ asset('images/logo.png') }}" alt="Garcia" style="position: absolute; left: 20px; top: 0; width: 90px;">
            <img src="{{ asset('images/batangs_seal.png') }}" alt="Batangas" style="position: absolute; right: 20px; top: 0; width: 90px;">

            <p style="margin: 0; font-size: 14px;">Republic of the Philippines<br>
                Province of Batangas<br>
                MUNICIPALITY OF PADRE GARCIA</p>
            <h2 style="margin: 10px 0; font-weight: bold;">OFFICE OF THE MUNICIPAL MAYOR</h2>
        </div>
        <!-- Watermark -->
        <div style="position: absolute; left: 50%; top: 45%; transform: translate(-50%, -50%); opacity: 0.6; z-index: 0; pointer-events: none;">
            <img src="{{ asset('images/mayors_permit.jpg') }}" alt="Watermark" style="width: 870px; height: auto;">
        </div>

        <!-- Body Content -->
        <div style="margin-top: 40px; font-size: 14px; line-height: 1.6;">
            <p style="margin-bottom: 20px;">
                To Whom It May Concern: <br><br>
                Permit is hereby <strong>GRANTED</strong> to 
                <span style="border-bottom: 1px solid #000; display: inline-block; min-width: 200px;">
                    {{ $ownerName }}
                </span> 
                of Barangay 
                <span style="border-bottom: 1px solid #000; display: inline-block; min-width: 150px;">
                    {{ $ownerBarangay }}
                </span> 
                to operate a 
                <span style="border-bottom: 1px solid #000; display: inline-block; min-width: 100px;">
                    {{ $unitType }}
                </span> 
                within Padre Garcia, Batangas provided that the provisions of existing ordinances of the Local Tax Code are complied with.
            </p>

            <p>
                Granted this 
               
                at Padre Garcia, Batangas.
            </p>
        </div>
    
         <!-- Mayor Signature -->
         <div style="text-align: right; margin-top: 60px; margin-right: 40px;">
            <p style="margin: 0; font-weight: bold;">HON. CELSA B. RIVERA</p>
            <p style="margin: 0;">Municipal Mayor</p>
        </div>

        <!-- Footer Info and Motto -->
        <div style="margin-top: 50px; display: flex; justify-content: space-between; align-items: flex-start;">
            <!-- Footer Info -->
            <div style="font-size: 14px;">
                <p><strong>Franchise No.:</strong> <span style="border-bottom: 1px solid #000; min-width: 80px; display: inline-block;">{{ $franchiseNo }}</span></p>
                <p><strong>OR No.:</strong> <span style="border-bottom: 1px solid #000; min-width: 80px; display: inline-block;">{{ $orNo }}</span></p>
                <p><strong>Amount:</strong> <span style="border-bottom: 1px solid #000; min-width: 80px; display: inline-block;">{{ $grantedAmount }}</span></p>
                <p><strong>CTC No.:</strong> <span style="border-bottom: 1px solid #000; min-width: 80px; display: inline-block;">{{ $ctcNo }}</span></p>
                <p><strong>Date Issued:</strong> <span style="border-bottom: 1px solid #000; min-width: 80px; display: inline-block;">{{ $ctcIssuedOn }}</span></p>
                <p><strong>Place Issued:</strong> <span style="border-bottom: 1px solid #000; min-width: 80px; display: inline-block;">{{ $ctcIssuedAt }}</span></p>
            </div>
            
            <!-- Motto -->
            <div style="font-size: 14px; font-weight: bold; text-align: right; line-height: 1.4;">
                <span style="color: red;">C</span>ontinuously <br>
                <span style="color: red;">B</span>ringing-up <br>
                <span style="color: red;">R</span>eforms
            </div>
        </div>
    </div>
</body>
</html>
