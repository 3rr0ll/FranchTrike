<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Mayor's Permit</title>
</head>
<body style="font-family: Arial, sans-serif; margin: 0; padding: 0;">

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
                <span style="border-bottom: 1px solid #000; display: inline-block; min-width: 200px;"></span> 
                of Barangay 
                <span style="border-bottom: 1px solid #000; display: inline-block; min-width: 150px;"></span> 
                to operate a 
                <span style="border-bottom: 1px solid #000; display: inline-block; min-width: 100px;"></span> 
                within Padre Garcia, Batangas provided that the provisions of existing ordinances of the Local Tax Code are complied with.
            </p>

            <p>
                Granted this <span style="border-bottom: 1px solid #000; display: inline-block; min-width: 150px;"></span> 
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
                <p><strong>Franchise No.:</strong> ___________</p>
                <p><strong>OR No.:</strong> ___________</p>
                <p><strong>Amount:</strong> ___________</p>
                <p><strong>CTC No.:</strong> ___________</p>
                <p><strong>Date Issued:</strong> ___________</p>
                <p><strong>Place Issued:</strong> ___________</p>
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
