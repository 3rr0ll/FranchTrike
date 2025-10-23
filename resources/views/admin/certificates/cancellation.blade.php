<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">

    <title>Certificate of Franchise Cancellation</title>
    <style>
        @media print {
            .no-print { display: none !important; }
            body { margin: 0; padding: 0; }
            .mtop-container { margin: 0; padding: 0; }
            #watermark {
                /* Ensure consistent positioning while printing (fixes watermark drifting) */
                position: fixed !important;
                left: 50% !important;
                top: 47% !important;
                transform: translate(-50%, -50%) !important;
                opacity: 0.9 !important;
                z-index: 0 !important;
                pointer-events: none !important;
            }
        }
    </style>
</head>

<body style="background: #e5e5e4;">
    <!-- Print Controls (similar to Mayor's Permit) -->
    <div class="no-print" style="text-align: center; margin: 20px 0; padding: 20px; border-radius: 8px;">
        <h1 style="margin-bottom: 20px; color: #333;">Certificate of Franchise Cancellation Preview</h1>
        <div style="display: flex; gap: 15px; justify-content: center; flex-wrap: wrap;">
            <button id="printBtn"
                style="background-color: #1a237e; color: #fff; border: none; padding: 10px 24px; border-radius: 5px; font-weight: bold; font-size: 16px; cursor: pointer; box-shadow: 0 2px 6px rgba(26,35,126,0.08); transition: background 0.2s;">
                Print
            </button>
        </div>
    </div>

    <div id="cancellation-certificate" class="mtop-container"
        style="width: 700px; margin: 0 auto; padding: 70px; background: #fff; font-family: 'Times New Roman', Times, serif; position: relative;">

        <!-- Header Logos -->
        <div style="display: flex; justify-content: space-between; align-items: center;">
            <img src="{{ asset('images/logo.png') }}" alt="Garcia" style="width: 70px; height: 70px;">
            <img src="{{ asset('images/batangs_seal.png') }}" alt="Batangas" style="width: 70px; height: 70px;">
        </div>

        <!-- Republic and Municipality Info -->
        <div style="text-align: center; margin-top: -70px;">
            <div style="font-size: 14px; font-weight: bold;">
                REPUBLIC OF THE PHILIPINES<br>
                Province of Batangas<br>
                <span style="font-weight: bold;">MUNICIPALITY OF PADRE GARCIA</span>
            </div>
            <div style="font-size: 20px; font-weight: bold; margin-top: 15px;">
                OFFICE OF THE MUNICIPAL MAYOR
            </div>
        </div>

        <hr style="border: 1.5px solid #000; margin: 10px 0 5px 0;">

        <!-- Title -->
        <h1
            style="text-align: center; font-size: 28px; font-weight: bold; margin: 70px 0 40px 0; font-family: 'Times New Roman', serif; position: relative; z-index: 2;">
            CERTIFICATE OF FRANCHISE <br>CANCELLATION</br>
        </h1>

        <!-- Watermark -->
        <div id="watermark" style="position: absolute; left: 50%; top:48%; transform: translate(-50%, -50%); opacity: 0.9; z-index: 0; pointer-events: none;">
             <img src="{{ asset('images/watermark.jpg') }}" alt="Watermark" style="width: 600px; height: auto;">
        </div>

        <!-- Content Section -->
        <div style="position: relative; z-index: 1; line-height: 2; font-size: 14px;">

            <p style="margin-bottom: 20px; text-align: justify;">
                <span style="margin-left: 60px;">THIS IS TO CERTIFY THAT</span>
                <strong>{{ $operator->first_name ?? '' }} {{ $operator->last_name ?? 'N/A' }}</strong>
                @if($operator && isset($operator->address))
                    of <strong>{{ $operator->address }}</strong>
                @else
                    of Barangay <strong>{{ $operator->barangay ?? 'N/A' }}, Padre Garcia, Batangas</strong>
                @endif
                registered owner of the
                <span style="display: inline-block; min-width: 50px; text-align: center;">
                    <strong>{{ ucfirst($motorDetail->unit_type ?? 'Tricycle') }}</strong>
                </span>
                with motor model
                <span style="display: inline-block; min-width: 50px; text-align: center;">
                    <strong>{{ $unitMake ?? 'N/A' }}</strong>
                </span>
                Motor Number,
                <span style="display: inline-block; min-width: 30px; text-align: center;">
                    <strong>{{ $motorDetail->motorno ?? $motorDetail->motor_no ?? 'N/A' }}</strong>
                </span>
                Chassis Number,
                <span style="display: inline-block; min-width: 40px; text-align: center;">
                    <strong>{{ $motorDetail->chasisno ?? $motorDetail->chassis_no ?? 'N/A' }}</strong>
                </span>
                Plate Number,
                <span style="display: inline-block; min-width: 40px; text-align: center;">
                    <strong>{{ $motorDetail->platenumber ?? $motorDetail->plate_number ?? 'N/A' }}</strong>
                </span>
                and Franchise Number
                <span style="display: inline-block; min-width: 40px; text-align: center;">
                    <strong>{{ $franchise_no ?? 'N/A' }}</strong>
                </span>
                issued by the Padre Garcia Franchise and Regulatory
                Board and has been cancelled this
                <span style="display: inline-block; min-width: 120px; text-align: center;">
                    <strong>{{ \Carbon\Carbon::now()->format('F j, Y') }}.</strong>
                </span>

            </p>
        </div>

        <!-- Signature Section -->
        <div style="display: flex; justify-content: space-between; margin-top: 20px; position: relative; z-index: 1;">
            <!-- Left Signature -->
            <div style="width: 45%; text-align: left;">
                <div style="font-weight: bold; margin-bottom: 5px;">Conforme:</div>
                <div style="margin-top: 30px; text-align: left;">
                    <div
                        style="display: inline-block; border-top: 2px solid #000; width: 60%; padding-top: 5px; font-weight: bold;  text-align: center;">
                        {{ $operator->first_name ?? '' }} {{ $operator->last_name ?? 'N/A' }}
                    </div>
                    <div style="margin-left: 40px;">
                        Operator/Owner
                    </div>
                </div>
            </div>

            <!-- Right Signature, placed lower than left -->
            <div style="width: 45%; display: flex; flex-direction: column; align-items: center;">
                <div style="height: 80px;"></div> <!-- Add space above right signature -->
                <div style="font-weight: bold; margin-bottom: 5px;">Approved by:</div>
                <div style="margin-top: 30px; text-align: center;">
                    <div style="border-top: 2px solid #000; padding-top: 5px; font-weight: bold; ">
                        {{ $mpdc?->name ?? '______' }}
                    </div>
                    <div style="font-size: 12px;">MPDC/ZA</div>
                </div>
            </div>
        </div>
        <div style="display: flex; justify-content: flex-start; margin-top: 30px;   position: relative; z-index: 1;">
            <table style="border-collapse: collapse; font-size: 16px; min-width: 300px;">
                <tr>
                    <td style="font-weight: bold; padding: 4px 12px 4px 0;">OR Number:</td>
                    <td style="padding: 4px 0;">{{ $or_no ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <td style="font-weight: bold; padding: 4px 12px 4px 0;">Amount:</td>
                    <td style="padding: 4px 0;">
                        {{ is_numeric($amount) ? '₱ ' . number_format($amount, 2) : $amount }}
                    </td>
                </tr>
                <tr>
                    <td style="font-weight: bold; padding: 4px 12px 4px 0;">Date:</td>
                    <td style="padding: 4px 0;">{{ $date_today ?? (\Carbon\Carbon::now()->format('F d, Y')) }}</td>
                </tr>
            </table>
        </div>
        <!-- Footer -->
        <div style="margin-top: 50px; position: relative; z-index: 1; display: flex; justify-content: center;">
            <img src="{{ asset('images/mtop_footer.jpg') }}" alt="Footer Logo" style="max-width: 750px; height: auto;">
        </div>
    </div>

    <script>
        document.getElementById('printBtn').addEventListener('click', function () {
            fetch("{{ route('admin.certificates.print.log', $motorDetail->id) }}", {
                method: "POST",
                headers: {
                    "X-CSRF-TOKEN": "{{ csrf_token() }}",
                    "Content-Type": "application/json"
                },
                body: JSON.stringify({ certificate_type: "Cancellation" })
            }).then(res => {
                window.print();
            });
        });
    </script>
</body>

</html>