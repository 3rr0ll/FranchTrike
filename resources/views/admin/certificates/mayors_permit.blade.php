<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">
    <title>Mayor's Permit</title>
    <style>
        @media print {
            .no-print {
                display: none !important;
            }

            body {
                margin: 0;
                padding: 0;
            }

            .permit-container {
                margin: 0;
                padding: 0;
            }
        }
    </style>
</head>

<body style="font-family: Arial, sans-serif; margin: 0; padding: 0; background: #e5e5e4;">
    @php
    $ownerFirstName = $operator->first_name ?? '';
    $ownerMiddleName = $operator->middle_initial ?? '';
    $ownerLastName = $operator->last_name ?? '';
    $ownerName = trim("{$ownerFirstName} {$ownerMiddleName} {$ownerLastName}");
    $ownerBarangay = $operator->barangay ?? '';
    $unitType = $motorDetail->unit_type ?? '';
    $franchiseNo = $franchiseApplication->franchise_no ?? '';
    $ctcNo = $franchiseApplication->ctc_no ?? '';
    $ctcIssuedOn = $franchiseApplication->ctc_date_issued ? date('F d, Y', strtotime($franchiseApplication->ctc_date_issued)) : '';
    $ctcIssuedAt = $franchiseApplication->ctc_place_issued ?? '';

    @endphp
    <!-- Print Controls -->
    <div class="no-print" style="text-align: center; margin: 20px 0; padding: 20px; border-radius: 8px;">
        <h1 style="margin-bottom: 20px; color: #333;">Application Form Preview</h1>
        <div style="display: flex; gap: 15px; justify-content: center; flex-wrap: wrap;">
            <button id="printBtn" style="background-color: #1a237e; color: #fff; border: none; padding: 10px 24px; border-radius: 5px; font-weight: bold; font-size: 16px; cursor: pointer; box-shadow: 0 2px 6px rgba(26,35,126,0.08); transition: background 0.2s;">
                Print
            </button>
        </div>
    </div>

    <div id="mayor-certificate" style="position: relative; width: 800px; height: 1100px; margin: auto; padding: 40px; background: #fff; overflow: hidden;">

        <!-- Watermark -->
        <div style="position: absolute; left: 0; top: 100px; width: 100%; height: auto; opacity: 0.8; z-index: 0; pointer-events: none; display: flex; justify-content: center; align-items: center;">
        <img src="{{ asset('images/mayors_permit.jpg') }}" alt="Watermark" style="width: 870px; height: 530px;">
            </div>

        <!-- All content above watermark -->
        <div style="position: relative; z-index: 1; height: 100%;">
            <!-- Header -->
            <div style="text-align: center; position: relative;">
                <img src="{{ asset('images/logo.png') }}" alt="Garcia" style="position: absolute; left: 20px; top: 0; width: 90px; z-index: 2;">
                <img src="{{ asset('images/batangs_seal.png') }}" alt="Batangas" style="position: absolute; right: 20px; top: 0; width: 90px; z-index: 2;">

                <p style="margin: 0; font-size: 14px;">Republic of the Philippines<br>
                    Province of Batangas<br>
                    MUNICIPALITY OF PADRE GARCIA</p>
                <h2 style="margin: 10px 0; font-weight: bold;">OFFICE OF THE MUNICIPAL MAYOR</h2>
            </div>

            <!-- Body Content -->
            <div style="margin-top: 100px; font-size: 14px; line-height: 1.6;">
                <p>
                    <span style="display: block; text-align: left;">
                        To Whom It May Concern: <br><br>
                    </span>
                    Permit is hereby GRANTED to
                    <span style="display: inline-block; min-width: 100px; text-align: center; font-weight: bold;">
                        {{ $ownerName }}
                    </span>
                    of Barangay
                    <span style="display: inline-block; min-width: 100px; text-align: center;font-weight: bold;">
                        {{ $ownerBarangay }}
                    </span>
                    to operate a
                    <span style="display: inline-block; min-width: 80px; text-align: center;font-weight: bold;">
                        {{ $unitType }}
                    </span>
                    within
                    <span style="display: inline-block; min-width: 70px; text-align: center; font-weight: bold;">
                        {{ $route->name ?? '' }}
                    </span>
                    and vice versa of the municipality provided that the provision of existing ordinances of the Local Tax Code are complied with.
                </p>
                <p>
                    Granted this at Padre Garcia, Batangas.
                </p>
            </div>

            <!-- Mayor Signature -->
            <div style="text-align: right;  margin-right: 40px;">
                <p style="margin: 0; font-weight: bold;">
                    {{ $municipal_mayor?->name }}
                </p>
                <p style="margin: 0;">Municipal Mayor</p>
            </div>

            <!-- Footer Info and Motto -->
            <div style="margin-top: 5px; display: flex; justify-content: space-between; align-items: flex-start;">
                <!-- Footer Info -->
                <div style="font-size: 14px;">
                    <p><strong>Franchise No.:</strong> <span style="min-width: 80px; display: inline-block;">{{ $franchiseNo }}</span></p>
                    <p><strong>OR No.:</strong> <span style="min-width: 80px; display: inline-block;">{{ $or_no ?? 'N/A' }}</span></p>
                    <p><strong>Amount:</strong> <span style="min-width: 80px; display: inline-block;">{{ $amount }}</span></p>
                    <p><strong>CTC No.:</strong> <span style="min-width: 80px; display: inline-block;">{{ $ctcNo }}</span></p>
                    <p><strong>Date Issued:</strong> <span style="min-width: 80px; display: inline-block;">{{ $ctcIssuedOn }}</span></p>
                    <p><strong>Place Issued:</strong> <span style="min-width: 80px; display: inline-block;">{{ $ctcIssuedAt }}</span></p>
                </div>

                <!-- Motto -->
                <div style="text-align: right; margin-top: 40px;">
                    <img src="{{ asset('images/cbr.png') }}" alt="CBR Motto" style="height: 100px;">
                </div>
            </div>
        </div>
    </div>

   <script>
        document.getElementById('printBtn').addEventListener('click', function() {
            fetch("{{ route('admin.certificates.print.log', $motorDetail->id) }}", {
                method: "POST",
                headers: {
                    "X-CSRF-TOKEN": "{{ csrf_token() }}",
                    "Content-Type": "application/json"
                },
               body: JSON.stringify({ certificate_type: "Mayor's Permit" })
            }).then(res => {
                window.print();
            });
        });
    </script>
</body>

</html>