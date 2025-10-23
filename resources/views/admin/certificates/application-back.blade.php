<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">
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
            background: #e5e5e4;
        }

        .container {
            max-width: 800px;
            margin: 0 auto;
            background: white;
            padding: 20px 120px 20px 120px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .header {
            text-align: center;
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

        .fees-section {
            margin-top: 30px;
        }

        .fees-title {
            font-weight: bold;
            margin-bottom: 8px;
        }

        .fee-table {
            width: 50%;
            margin-bottom: 10px;
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
            margin-top: 10px;
            border-top: 3px dashed #000;
            padding-top: 10px;
        }

        .claim-header {
            font-weight: bold;
            font-size: 18px;
            margin-bottom: 15px;
        }

        .no-print {
            display: inline-block;
            margin-bottom: 15px;
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
            .no-print {
                display: none !important;
            }
        }
    </style>
</head>

<body> 
    
    @php
        $motorNo = $motorDetail->motorno ?? '';
        $chasisNo = $motorDetail->chasisno ?? '';
        $plateNumber = $motorDetail->platenumber ?? '';
    @endphp

    <div class="no-print" style="display: flex; flex-direction: column; align-items: center; justify-content: center; margin: 20px 0; padding: 20px; border-radius: 8px;">
        <h1 style="margin-bottom: 20px; color: #333; text-align: center;">Back Application Form Preview</h1>
        <div style="display: flex; gap: 15px; justify-content: center; flex-wrap: wrap;">
            <button id="printBtn"
                style="background-color: #1a237e; color: #fff; border: none; padding: 10px 24px; border-radius: 5px; font-weight: bold; font-size: 16px; cursor: pointer; box-shadow: 0 2px 6px rgba(26,35,126,0.08); transition: background 0.2s;">
                Print
            </button>
        </div>
    </div>
        
    <div class="container">
        <div class="header">
            <h3>Republic of the Philippines</h3>
            <h3>Province of Batangas</h3>
            <h2>MUNICIPALITY OF PADRE GARCIA</h2>
            <h2>MOTORIZED TRICYCLE FRANCHISING AND REGULATORY BOARD</h2>
            <h2>APPLICATION FORM</h2>
        </div>

        <div class="info-section">
            <div style="display: flex; align-items: flex-start; margin-bottom: 8px;">
                <div style="display: flex; align-items: center; flex: 1;">
                    <span class="info-label" style="font-weight: bold;">Franchise No.:</span>
                    <span style="border: 1px solid #000; min-width: 140px; padding: 2px 6px; margin-left: 4px; font-size: 15px; background: #fff; display: inline-block;">{{ $franchise }}</span>
                </div>
                <div style="display: flex; align-items: center; flex: 1; margin-left: 24px;">
                    <span class="info-label" style="font-weight: bold;">Sticker No.:</span>
                    <span style="border: 1px solid #000; min-width: 140px; padding: 2px 6px; margin-left: 4px; font-size: 15px; background: #fff; display: inline-block;">{{ $sticker }}</span>
                </div>
            </div>
            <div style="display: flex; align-items: flex-start; margin-bottom: 8px;">
                <div style="display: flex; align-items: center; flex: 1;">
                    <span class="info-label" style="font-weight: bold;">Route:</span>
                    <span style="border-bottom: 1px solid #000; min-width: 90px; padding: 2px 6px; margin-left: 4px; font-size: 15px; display: inline-block;">{{ $route?->name ?? $route ?? '' }}</span>
                </div>
                <div style="display: flex; align-items: center; flex: 2; margin-left: 24px;">
                    <span class="info-label" style="font-weight: bold;">Name</span>
                    <span style="border-bottom: 1px solid #000; min-width: 220px; padding: 2px 6px; margin-left: 4px; font-size: 15px; display: inline-block;">{{ $name }}</span>
                </div>
            </div>
            <div style="display: flex; flex-direction: column; align-items: center; margin-bottom: 4px;">
                <div style="display: flex; align-items: center; margin-bottom: 4px; width: 400px; max-width: 100%; justify-content: center;">
                    <span class="info-label" style="font-weight: bold; min-width: 140px; text-align: right;">Motor No.:</span>
                    <span style="border-bottom: 1px solid #aaa; flex: 1; padding: 2px 6px; font-size: 15px; margin-left: 8px;">{{ $motorNo }}</span>
                </div>
                <div style="display: flex; align-items: center; margin-bottom: 4px; width: 400px; max-width: 100%; justify-content: center;">
                    <span class="info-label" style="font-weight: bold; min-width: 140px; text-align: right;">Chasis No.:</span>
                    <span style="border-bottom: 1px solid #aaa; flex: 1; padding: 2px 6px; font-size: 15px; margin-left: 8px;">{{ $chasisNo }}</span>
                </div>
                <div style="display: flex; align-items: center; width: 400px; max-width: 100%; justify-content: center;">
                    <span class="info-label" style="font-weight: bold; min-width: 140px; text-align: right;">Plate Number:</span>
                    <span style="border-bottom: 1px solid #aaa; flex: 1; padding: 2px 6px; font-size: 15px; margin-left: 8px;">{{ $plateNumber }}</span>
                </div>
            </div>
        </div>

        <div class="fees-section">
            <div class="fees-title">Filing Fee:</div>
            <div class="fee-table">
                @if(isset($fees) && count($fees) > 0)
                    @php
                        $totalAmount = 0;
                    @endphp

                    @foreach($fees as $fee)
                        <div class="fee-row">
                            <div class="fee-item">{{ $fee['item'] ?? '' }}</div>
                            <div class="fee-amount">
                                ₱ {{ $fee['amount'] ?? '0.00' }}
                            </div>
                        </div>
                        @php
                            $totalAmount += floatval(str_replace(',', '', $fee['amount'] ?? 0));
                        @endphp
                    @endforeach

                    <div class="fee-row" style="font-weight: bold;">
                        <div class="fee-item mr-4" style="text-align: right;  width: 100%;">Total</div>
                        <div class="fee-amount">₱ {{ number_format($totalAmount, 2) }}</div>
                    </div>
                @else
                    <div class="fee-row">
                        <div class="fee-item">No fees found for this OR number.</div>
                    </div>
                @endif
            </div>
            <div style="text-align: left;">
                <div style="font-weight: bold; text-decoration: underline; font-size: 15px; margin-left: 30px;">
                    {{ $admin?->name ?? '' }}
                </div>
                <div style="font-size: 12px; margin-top: 2px; text-align:center; display: flex; justify-content: flex-start;">
                    Signature over Printed Name Authorized Permit<br>
                    and License Personnel
                </div>
            </div>
        </div>

        <div class="claim-stub">
            <div style="display: flex; justify-content: space-between; align-items: flex-start;">
                <div style="flex: 1;">
                    <div class="claim-header" style="font-weight: bold; font-size: 20px; margin-bottom: 2px; color: #d00;">CLAIM STUB</div>
                    <div style="display: flex; align-items: center; margin-bottom: 2px;">
                        <span style="font-weight: bold; min-width: 100px; font-size: 14px;">Franchise No.:</span>
                        <span
                            style="display: inline-block; min-width: 50px; border: 1.5px solid #d00; color: #d00; background: #fff; font-weight: bold; font-size: 16px; text-align: center; margin: 0 8px 0 4px; padding: 0 8px;">
                            {{ $franchise }}
                        </span>
                        <span style="font-weight: bold; min-width: 90px; font-size: 14px; margin-left: 18px;">Sticker No.:</span>
                        <span
                            style="display: inline-block; min-width: 50px; border: 1.5px solid #d00; color: #d00; background: #fff; font-weight: bold; font-size: 16px; text-align: center; margin: 0 0 0 4px; padding: 0 8px;">
                            {{ $sticker }}
                        </span>
                    </div>
                    <div style="display: flex; align-items: center;">
                        <span style="font-weight: bold; font-size: 13px;">Route:</span>
                        <span style="border-bottom: 1px solid #000; min-width: 80px; font-size: 13px; padding: 0 2px 1px 2px; margin-left: 4px; margin-right: 24px;">
                            {{ $route?->name ?? $route ?? '' }}
                        </span>
                        <span style="font-weight: bold; font-size: 13px;">Name:</span>
                        <span style="border-bottom: 1px solid #000; min-width: 120px; font-size: 13px; padding: 0 2px 1px 2px; margin-left: 4px;">
                            {{ $name }}
                        </span>
                    </div>
                </div>
                <div style="flex: 1; text-align: right;">
                    <div style="font-size: 15px; font-weight: bold; margin-bottom: 18px;">ON PROCESS/FOR SIGNATURE</div>
                    <div style="font-weight: bold; text-decoration: underline; font-size: 15px; margin-bottom: 0; text-align: center;">
                        {{ $admin_name ?? '' }}
                    </div>
                    <div style="font-size: 12px; margin-top: 2px; text-align: center;">Verified by</div>
                </div>
            </div>
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
                body: JSON.stringify({
                    certificate_type: "Application Back"
                })
            }).then(res => {
                window.print();
            });
        });
    </script>
</body>

</html>