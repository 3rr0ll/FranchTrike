<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MTOP Certificate</title>
    <style>
        @media print {
            .no-print { display: none !important; }
            body { margin: 0; padding: 0; }
            .mtop-container { margin: 0; padding: 0; }
        }
    </style>
</head>
<body>
    <!-- Print Controls -->
    <div class="no-print" style="text-align: center; margin: 20px 0; padding: 20px; background: #f5f5f5; border-radius: 8px;">
        <h2 style="margin-bottom: 20px; color: #333;">MTOP Certificate Preview</h2>
        <div style="display: flex; gap: 15px; justify-content: center; flex-wrap: wrap;">
            <button onclick="window.print()" style="background: #28a745; color: white; border: none; padding: 12px 24px; border-radius: 6px; cursor: pointer; font-size: 16px; font-weight: bold;">
                🖨️ Print Certificate
            </button>
            <button onclick="downloadPDF()" style="background: #007bff; color: white; border: none; padding: 12px 24px; border-radius: 6px; cursor: pointer; font-size: 16px; font-weight: bold;">
                📄 Download PDF
            </button>
            <a href="{{ route('admin.motor-details.show', $motorDetail->id) }}" style="background: #6c757d; color: white; border: none; padding: 12px 24px; border-radius: 6px; cursor: pointer; font-size: 16px; font-weight: bold; text-decoration: none; display: inline-block;">
                ← Back to Motor Details
            </a>
        </div>
    </div>

    <div id="mtop-certificate" class="mtop-container" style="width: 700px; margin: 0 auto; padding: 30px; background: #fff; font-family: 'Times New Roman', Times, serif; position: relative;">
        <!-- Header Logos -->
        <div style="display: flex; justify-content: space-between; align-items: center;">
            <img src="{{ asset('images/logo.png') }}" alt="Garcia" style="width: 70px; height: 70px;">
            <img src="{{ asset('images/batangs_seal.png') }}" alt="Batangas" style="width: 70px; height: 70px;">
        </div>

        <!-- Republic and Municipality Info -->
        <div style="text-align: center; margin-top: -70px;">
            <div style="font-size: 14px;">
                Republic of the Philippines<br>
                Province of Batangas<br>
                <span style="font-weight: bold;">MUNICIPALITY OF PADRE GARCIA</span>
            </div>
            <div style="font-size: 20px; font-weight: bold; margin-top: 5px;">
                OFFICE OF THE MUNICIPAL MAYOR
            </div>
        </div>

        <hr style="border: 1.5px solid #000; margin: 10px 0 5px 0;">

        <!-- Certificate Title -->
        <div style="text-align: center; font-size: 22px; font-weight: bold; margin-bottom: 10px;">
            MOTORIZED TRICYCLE OPERATOR'S PERMIT (MTOP)
        </div>
        
        <!-- Watermark -->
        <div style="position: absolute; left: 50%; top:50%; transform: translate(-50%, -50%); opacity: 0.9; z-index: 0; pointer-events: none;">
            <img src="{{ asset('images/watermark.jpg') }}" alt="Watermark" style="width: 350px; height: auto;">
        </div>
        
        <!-- Permit Body -->
        <div style="position: relative; z-index: 1; font-size: 16px; margin-top: 10px;">
            <div style="margin-bottom: 10px;">
                PERMIT IS HEREBY GRANTED TO
                <span style="display: inline-block; min-width: 200px; border-bottom: 1px solid #000;">{{ $operator->first_name ?? '' }} {{ $operator->last_name ?? 'N/A' }}</span>
                of Barangay
                <span style="display: inline-block; min-width: 120px; border-bottom: 1px solid #000;">{{ $operator->barangay ?? 'N/A' }}</span>
                <br>
                to operate a <span style="display: inline-block; min-width: 60px; border-bottom: 1px solid #000;">{{ ucfirst($motorDetail->unit_type) }}</span>
                service for hire on the route:
                <span style="display: inline-block; min-width: 180px; border-bottom: 1px solid #000;">{{ $route->name ?? 'N/A' }}</span>
                of Padre Garcia, Batangas using one tricycle described as follows:
            </div>

            <!-- Tricycle Details Table -->
            <table style="width: 100%; margin-bottom: 10px; font-size: 16px;">
                <tr>
                    <th style="text-align: left; width: 25%;">MADE</th>
                    <th style="text-align: left; width: 25%;">MOTOR NO.</th>
                    <th style="text-align: left; width: 25%;">CHASSIS NO.</th>
                    <th style="text-align: left; width: 25%;">PLATE NUMBER</th>
                </tr>
                <tr>
                    <td style="text-align: left; width: 25%; padding: 5px 0;">{{ $motorDetail->unitMake->name ?? 'N/A' }}</td>
                    <td style="text-align: left; width: 25%; padding: 5px 0;">{{ $motorDetail->motorno ?? 'N/A' }}</td>
                    <td style="text-align: left; width: 25%; padding: 5px 0;">{{ $motorDetail->chasisno ?? 'N/A' }}</td>
                    <td style="text-align: left; width: 25%; padding: 5px 0;">{{ $motorDetail->platenumber ?? 'N/A' }}</td>
                </tr>
            </table>

            <div style="margin-bottom: 10px;">
                Provided that no existing laws and ordinances shall be violated.
            </div>

            <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 10px;">
                <div style="font-size: 15px;">
                    Issued this
                    <span style="display: inline-block; min-width: 120px; border-bottom: 1px solid #000;">
                        {{ \Carbon\Carbon::parse($date_issued)->format('F j, Y') }}
                    </span>
                    in Padre Garcia, Batangas.
                </div>
                <div style="text-align: center; font-size: 15px;">
                    <span style="font-weight: bold;">HON. CELSA B. RIVERA</span><br>
                    <span style="font-size: 13px;">Municipal Mayor</span>
                </div>
            </div>

            <div style="margin-top: 30px; margin-bottom: 10px;">
                <div style="display: flex; align-items: center;">
                    <span style="font-size: 18px; font-weight: bold;">Case No:</span>
                    <span style="display: inline-block; min-width: 180px; border-bottom: 2px solid #000; margin-left: 8px; margin-right: 40px;">{{ $case_no }}</span>
                    <span style="font-size: 18px; font-weight: bold;">Sticker No:</span>
                    <span style="display: inline-block; min-width: 180px; border-bottom: 2px solid #000; margin-left: 8px;">{{ $sticker_no }}</span>
                </div>
            </div>

            <div style="font-size: 15px; margin-bottom: 2px;">
                <span style="display: inline-block; width: 80px;">OR No.:</span>
                <span style="display: inline-block; min-width: 120px; border-bottom: 1px solid #000;">{{ $or_no }}</span>
            </div>
            <div style="font-size: 15px; margin-bottom: 2px;">
                <span style="display: inline-block; width: 80px;">Amount:</span>
                <span style="display: inline-block; min-width: 120px; border-bottom: 1px solid #000;">{{ $amount }}</span>
            </div>
            <div style="font-size: 15px; margin-bottom: 2px;">
                <span style="display: inline-block; width: 80px;">CTC No:</span>
                <span style="display: inline-block; min-width: 120px; border-bottom: 1px solid #000;">{{ $ctc_no }}</span>
            </div>
            <div style="font-size: 15px; margin-bottom: 2px;">
                <span style="display: inline-block; width: 80px;">Date Issued</span>
                <span style="display: inline-block; min-width: 120px; border-bottom: 1px solid #000;">
                    {{ \Carbon\Carbon::parse($date_issued)->format('F d, Y') }}
                </span>
            </div>
            <div style="font-size: 15px; margin-bottom: 2px;">
                <span style="display: inline-block; width: 80px;">Place Issued</span>
                <span style="display: inline-block; min-width: 120px; border-bottom: 1px solid #000;">
                    {{ $place_issued ?? 'PADRE GARCIA' }}
                </span>
            </div>
            <div style="font-size: 16px; margin-top: 6px;">
                <span style="color: #d32f2f; font-weight: bold;">Validity:</span>
                <span style="display: inline-block; min-width: 120px; border-bottom: 1px solid #000;">{{ $validity }}</span>
            </div>

        </div>
        
        <!-- Footer -->
        <div style="text-align: center; margin-top: 55px; position: relative; z-index: 1;">
            <img src="{{ asset('images/mtop_footer.jpg') }}" alt="Footer Logo" style="max-width: 650px; height: auto;">
        </div>
    </div>

    <script>
        function downloadPDF() {
            window.location.href = "{{ route('admin.certificates.mtop.generate', $motorDetail->id) }}";
        }
    </script>
</body>
</html>