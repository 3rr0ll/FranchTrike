<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Motorized Tricycle Franchising Application Form</title>
    <style>
        html, body {
            height: 100%;
            margin: 0;
            padding: 0;
        }
        body {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, sans-serif;
            font-size: 11px;
            line-height: 1.2;
            background-color: #f5f5f5;
        }
        .application-container {
            max-width: 100%;
            width: 100%;
            margin: 0 auto;
            background: #fff;
            padding: 10px;
            box-sizing: border-box;
        }
        @media print {
            html, body {
                width: 210mm;
                height: 297mm;
                background: #fff !important;
            }
            @page {
                size: A4;
                margin: 8mm;
            }
            .no-print {
                display: none !important;
            }
            body {
                margin: 0 !important;
                padding: 0 !important;
                background: #fff !important;
                width: 210mm !important;
                height: 297mm !important;
                box-sizing: border-box;
                /* Remove scaling, use compact font and spacing instead */
            }
            .application-container,
            #application-certificate {
                page-break-inside: avoid;
                break-inside: avoid;
            }
            * {
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
            }
        }
        label, span, th, td, p, div {
            font-size: 11px !important;
            line-height: 1.1 !important;
        }
        table {
            font-size: 10px !important;
        }
        .application-container {
            padding: 5px !important;
        }
        .application-container > * {
            margin-top: 0 !important;
            margin-bottom: 4px !important;
        }
    </style>
</head>

<body
    style="margin: 0; padding: 0; box-sizing: border-box; font-family: Arial, sans-serif; font-size: 12px; line-height: 1.4; background-color: #f5f5f5;">
    @php
        $route = $franchiseApplication->route->name ?? '';
        $todaPresident = $franchiseApplication->toda_president ?? '';
        $trafficDivision = $franchiseApplication->traffic_division ?? '';
        $publicFacilitiesChair = $franchiseApplication->public_facilities_chair ?? '';
        $franchiseNo = $franchiseApplication->franchise_no ?? '';
        $stickerNo = $franchiseApplication->sticker_no ?? '';
        $unitType = $motorDetail->unit_type ?? '';
        $unitMake = $motorDetail->unitMake->name ?? '';
        $motorNo = $motorDetail->motorno ?? '';
        $chasisNo = $motorDetail->chasisno ?? '';
        $plateNumber = $motorDetail->platenumber ?? '';
        $applicationType = $franchiseApplication->application_type ?? '';
        $ownerFirstName = $operator->first_name ?? '';
        $ownerMiddleName = $operator->middle_initial ?? '';
        $ownerLastName = $operator->last_name ?? '';
        $ownerName = trim("{$ownerFirstName} {$ownerMiddleName} {$ownerLastName}");
        $ownerAddress = trim(($operator->barangay ?? '') . ', ' . ($operator->municipality ?? '') . ', ' . ($operator->province ?? ''));
        $ownerBirthdate = $operator->birth_date ? date('F d, Y', strtotime($operator->birth_date)) : '';
        $ownerAge = $operator->age ?? '';
        $ownerSex = $operator->sex ?? '';
        $driverName = trim(($driver->first_name ?? '') . ' ' . ($driver->middle_initial ?? '') . ' ' . ($driver->last_name ?? ''));
        $driverAddress = $driver->barangay . ', ' . $driver->municipality . ', ' . $driver->province;
        $driverBirthdate = $driver->birth_date ? date('F d, Y', strtotime($driver->birth_date)) : '';
        $driverAge = $driver->age ?? '';
        $driverCivilStatus = $driver->civil_status ?? '';
        $driverSex = $driver->sex ?? '';
        $driverLicenseNo = $driver->license_no ?? '';
        $driverLicenseValidity = $driver->license_validity ? date('F d, Y', strtotime($driver->license_validity)) : '';
        $driverLicenseNature = $driver->license_nature ?? '';
        $inspectedBy = $inspection->inspected_by ?? '';
        $inspectorSignature = $inspection->signature ?? '';
        $inspectorPosition = $inspection->position ?? '';
        $inspectionDate = $inspection->date ?? '';
        $grantedBy = $franchiseApplication->granted_by ?? '';
        $grantedUnits = $granted->units ?? '';
        $grantedUntil = \Carbon\Carbon::now()->addYear()->format('F Y');
        $grantedDate = $granted->date ?? '';
        $applicantName = $ownerName;
        $applicantSignature = $franchiseApplication->applicant_signature ?? '';
        $ctcNo = $franchiseApplication->ctc_no ?? '';
        $ctcIssuedOn = $franchiseApplication->ctc_issued_on ?? '';
        $ctcIssuedAt = $franchiseApplication->ctc_issued_at ?? '';
        // Requirements
        $ownerReqs = $requirements['owner'] ?? [];
        $driverReqs = $requirements['driver'] ?? [];
        // Inspection checklist
        $inspectionChecklist = $inspection->checklist ?? [];
    @endphp

    <!-- Print Controls -->
    <div class="no-print" style="text-align: center; margin: 20px 0; padding: 20px; border-radius: 8px;">
        <h1 style="margin-bottom: 20px; color: #333;">Application Form Preview</h1>
        <div style="display: flex; gap: 15px; justify-content: center; flex-wrap: wrap;">
            <button id="printBtn"
                style="background-color: #1a237e; color: #fff; border: none; padding: 10px 24px; border-radius: 5px; font-weight: bold; font-size: 16px; cursor: pointer; box-shadow: 0 2px 6px rgba(26,35,126,0.08); transition: background 0.2s;">
                Print
            </button>
        </div>
    </div>

    <div id="application-certificate" style="max-width: 800px; margin: 0 auto; background: white; padding: 15px; ">

        <div
            style="display: flex; flex-direction: row; align-items: flex-start; justify-content: center; gap: 30px; margin-bottom: 20px;">
            <div style="text-align: center; flex: 1;">
                <h2 style="font-size: 11px; ">Republic of the Philippines</h2>
                <h2 style="font-size: 11px; font-weight: bold; ">MUNICIPALITY OF PADRE GARCIA</h1>
                <h1 style="font-size: 12px; font-weight: bold; ">MOTORIZED TRICYCLE FRANCHISING AND REGULATORY BOARD</h1>
                <h1 style="font-size: 12px; font-weight: bold; ">APPLICATION FORM</h1>
            </div>
            <div style="border: 2px solid #000; padding: 10px; width: 300px; margin: 0;">
                <div>
                    <label style="font-weight: bold; display: inline-block; width: 120px;">ROUTE</label>
                    <span
                        style="border-bottom: 1px solid #000; min-width: 150px; display: inline-block;">{{ strtoupper($route) }}</span>
                </div>
                <div>
                    <label style="font-weight: bold; display: inline-block; width: 120px;">TODA President:</label>
                    <span
                        style="border-bottom: 1px solid #000; min-width: 150px; display: inline-block;">{{ $todaPresident }}</span>
                </div>
                <div>
                    <label style="font-weight: bold; display: inline-block; width: 120px;">MTFRB Secretariat:</label>
                    <span
                        style="border-bottom: 1px solid #000; min-width: 150px; display: inline-block;">{{ $trafficDivision }}</span>
                </div>
                <div>
                    <div style="font-weight: bold; width: 100%; display: block;">
                        Public Facilities and Utilities Chairperson:
                    </div>
                    <div
                        style="border-bottom: 1px solid #000; width: 100%; min-height: 10px; text-align: left; display: block;">
                        {{ $publicFacilitiesChair }}
                    </div>
                </div>
            </div>

        </div>
        <div style="display: flex; gap: 30px; margin-bottom: 20px; align-items: flex-end;">

            <div style="display: flex; align-items: center; gap: 10px;">
                <label style="font-weight: bold;">Franchise No.:</label>
                <span
                    style="border: 0.5px solid #000; padding: 2px 5px; width: 80px; display: inline-block;">{{ $franchiseNo }}</span>
            </div>
            <div style="display: flex; align-items: center; gap: 10px;">
                <label style="font-weight: bold;">Sticker No.:</label>
                <span
                    style="border: 0.5px solid #000; padding: 2px 5px; width: 80px; display: inline-block;">{{ $stickerNo }}</span>
            </div>
            <div style="display: flex; align-items: center; gap: 10px;">
                <label style="font-weight: bold;">Route</label>
                <span
                    style="border-bottom: 1px solid #000; min-width: 150px; display: inline-block;">{{ strtoupper($route) }}</span>
            </div>
        </div>

        <div style="display: flex; gap: 20px;">
            <div style="flex: 2;">
                <div style="display: flex; justify-content: space-between; align-items: flex-start; gap: 20px;">

                    <!-- LEFT SIDE -->
                    <div style="flex: 1;">
                        <div style="display: flex; align-items: center; margin-bottom: 6px;">
                            <label style="font-weight: bold; width: 100px; display: inline-block;">Unit Type:</label>
                            <span style="border-bottom: 0.5px solid #000; flex: 1; display: inline-block;">
                                {{ $unitType }}
                            </span>
                        </div>
                
                        <div style="display: flex; align-items: center; margin-bottom: 6px;">
                            <label style="font-weight: bold; width: 100px; display: inline-block;">Unit Make:</label>
                            <span style="border-bottom: 1px solid #000; flex: 1; display: inline-block;">
                                {{ $unitMake }}
                            </span>
                        </div>
                
                        <div style="display: flex; align-items: center; margin-bottom: 6px;">
                            <label style="font-weight: bold; width: 100px; display: inline-block;">Motor No.:</label>
                            <span style="border-bottom: 1px solid #000; flex: 1; display: inline-block;">
                                {{ $motorNo }}
                            </span>
                        </div>
                
                        <div style="display: flex; align-items: center; margin-bottom: 6px;">
                            <label style="font-weight: bold; width: 100px; display: inline-block;">Chassis No.:</label>
                            <span style="border-bottom: 1px solid #000; flex: 1; display: inline-block;">
                                {{ $chasisNo }}
                            </span>
                        </div>
                
                        <div style="display: flex; align-items: center; margin-bottom: 6px;">
                            <label style="font-weight: bold; width: 100px; display: inline-block;">Plate Number:</label>
                            <span style="border-bottom: 1px solid #000; flex: 1; display: inline-block;">
                                {{ $plateNumber }}
                            </span>
                        </div>
                    </div>
                
                    <!-- RIGHT SIDE -->
                    <div style="flex: 1; padding-left: 20px;">
                        <label style="font-weight: bold; display: block; margin-bottom: 5px;">Application Type:</label>
                        <div style="display: grid; border: 0.1px solid #000; grid-template-columns: 1fr 1fr; gap: 8px;">
                            <div style="display: flex; align-items: center; gap: 5px;">
                                <input type="checkbox" id="new-app" {{ $applicationType == 'new' ? 'checked' : '' }} disabled>
                                <label for="new-app" style="font-weight: bold; color: #ff6600;">New Application</label>
                            </div>
                            <div style="display: flex; align-items: center; gap: 5px;">
                                <input type="checkbox" id="change-motor" {{ $applicationType == 'change_motor' ? 'checked' : '' }} disabled>
                                <label for="change-motor" style="font-weight: bold; color: #ff6600;">Change Motor</label>
                            </div>
                            <div style="display: flex; align-items: center; gap: 5px;">
                                <input type="checkbox" id="renewal" {{ $applicationType == 'renewal' ? 'checked' : '' }} disabled>
                                <label for="renewal" style="font-weight: bold; color: #ff6600;">Renewal</label>
                            </div>
                            <div style="display: flex; align-items: center; gap: 5px;">
                                <input type="checkbox" id="change-ownership" {{ $applicationType == 'change_ownership' ? 'checked' : '' }} disabled>
                                <label for="change-ownership" style="font-weight: bold; color: #ff6600;">Change Ownership</label>
                            </div>
                        </div>
                    </div>
                
                </div>
                

                <div style="margin-bottom: 10px;">
                    <div style="font-weight: bold; margin-bottom: 6px; text-decoration: underline;">OWNER'S
                        INFORMATION:
                    </div>
                    <div style="display: flex; margin-bottom: 4px; align-items: center;">
                        <label style="font-weight: bold; width: 100px; display: inline-block;">Name:</label>
                        <span
                            style="border-bottom: 1px solid #000; flex: 1; margin-right: 10px; display: inline-block;">{{ $ownerName }}</span>
                    </div>
                    <div style="display: flex; margin-bottom: 4px; align-items: center;">
                        <label style="font-weight: bold; width: 100px; display: inline-block;">Address:</label>
                        <span
                            style="border-bottom: 1px solid #000; flex: 1; margin-right: 10px; display: inline-block;">{{ $ownerAddress }}</span>
                    </div>
                    <div style="display: flex; margin-bottom: 4px; align-items: center;">
                        <label style="font-weight: bold; width: 100px; display: inline-block;">Birthdate:</label>
                        <span
                            style="border-bottom: 1px solid #000; flex: 1; margin-right: 10px; display: inline-block;">{{ $ownerBirthdate }}</span>
                    </div>
                    <div style="display: flex; margin-bottom: 4px; align-items: center;">
                        <label style="font-weight: bold; width: 100px; display: inline-block;">Age:</label>
                        <span
                            style="border-bottom: 1px solid #000; width: 60px; display: inline-block;">{{ $ownerAge }}</span>
                    </div>
                    <div style="display: flex; margin-bottom: 4px; align-items: center;">
                        <label style="font-weight: bold; width: 100px; display: inline-block;">Sex:</label>
                        <span style="border-bottom: 1px solid #000; min-width: 60px; display: inline-block;">
                            {{ ucfirst($ownerSex) }}
                        </span>
                    </div>
                </div>

                <div style="margin-bottom: 10px;">
                    <div style="font-weight: bold; margin-bottom: 6px; text-decoration: underline;">DRIVER'S
                        INFORMATION:
                    </div>
                    <div style="display: flex; margin-bottom: 4px; align-items: center;">
                        <label style="font-weight: bold; width: 100px; display: inline-block;">Name:</label>
                        <span
                            style="border-bottom: 1px solid #000; flex: 1; margin-right: 10px; display: inline-block;">{{ $driverName }}</span>
                    </div>
                    <div style="display: flex; margin-bottom: 4px; align-items: center;">
                        <label style="font-weight: bold; width: 100px; display: inline-block;">Address:</label>
                        <span
                            style="border-bottom: 1px solid #000; flex: 1; margin-right: 10px; display: inline-block;">{{ $driverAddress }}</span>
                    </div>
                    <div style="display: flex; margin-bottom: 4px; align-items: center;">
                        <label style="font-weight: bold; width: 100px; display: inline-block;">Birthdate:</label>
                        <span
                            style="border-bottom: 1px solid #000; flex: 1; margin-right: 10px; display: inline-block;">{{ $driverBirthdate }}</span>
                        <label
                            style="margin-left: 50px; font-weight: bold; width: 100px; display: inline-block;">Age:</label>
                        <span
                            style="border-bottom: 1px solid #000; width: 60px; display: inline-block;">{{ $driverAge }}</span>
                    </div>
                    <div style="display: flex; margin-bottom: 4px; align-items: center;">
                        <label style="font-weight: bold; width: 100px; display: inline-block;">Civil Status:</label>
                        <span
                            style="border-bottom: 1px solid #000; flex: 1; margin-right: 10px; display: inline-block;">{{ $driverCivilStatus }}</span>
                        <label style="margin-left: 30px; font-weight: bold;">Sex:</label>
                        <span
                            style="border-bottom: 1px solid #000; min-width: 60px; display: inline-block; margin-left: 10px;">
                            {{ ucfirst($driverSex) }}
                        </span>
                    </div>
                    <div style="display: flex; margin-bottom: 4px; align-items: center;">
                        <label style="font-weight: bold; width: 100px; display: inline-block;">License No.:</label>
                        <span
                            style="border-bottom: 1px solid #000; flex: 1; margin-right: 10px; display: inline-block;">{{ $driverLicenseNo }}</span>
                    </div>
                    <div style="display: flex; margin-bottom: 4px; align-items: center;">
                        <label style="font-weight: bold; width: 100px; display: inline-block;">Validity:</label>
                        <span
                            style="border-bottom: 1px solid #000; flex: 1; margin-right: 10px; display: inline-block;">{{ $driverLicenseValidity }}</span>
                    </div>
                    <div style="display: flex; margin-bottom: 4px; align-items: center;">
                        <label style="font-weight: bold; width: 100px; display: inline-block;">Nature of
                            License:</label>
                        <span
                            style="border-bottom: 1px solid #000; flex: 1; margin-right: 10px; display: inline-block;">{{ $driverLicenseNature }}</span>
                    </div>
                </div>

                <p style="margin: 15px 0; font-weight: bold;">
                    This is to certify that the tricycle unit described above was inspected to have the following
                    condition:
                </p>

                <table style="width: 100%; border-collapse: collapse;">
                    <thead>
                        <tr>
                            <th style="padding: 3px; text-align: center; font-weight: bold;">
                            </th>
                            <th style="padding: 3px; text-align: center; font-weight: bold;">
                                Functional</th>
                            <th style="padding: 3px; text-align: center; font-weight: bold;">
                                Not
                                Functional</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $checklistItems = [
                                'Brake Light',
                                'Signal Light',
                                'Head Light',
                                'Inside Light',
                                'Clutch',
                                'Hand Brake',
                                'Foot Brake',
                                'Side Car Brake',
                                'Side Car Flooring',
                                'Suspension',
                                'Wind Shield of Motorcycle',
                                'Wind Shield of Side Car',
                                'Trash Can 0.10 m x 0.10 m',
                                'Muffler'
                            ];
                        @endphp
                        @foreach($checklistItems as $item)
                            <tr style="text-align: right;">
                                <td style="padding: 1px 3px 1px 8px; text-align: right; font-weight: bold;">{{ $item }}</td>
                                @if($item == 'Muffler')
                                    <td style="padding: 1px 3px; text-align: center;">
                                        <input type="checkbox" style="margin-left: 65px; vertical-align: middle;"  {{ ($inspectionChecklist['Muffler'] ?? '') == 'with_silencer' ? 'checked' : '' }} disabled>
                                        <span style=" font-size: 10px; font-weight: bold;">With silencer</span>
                                    </td>
                                    <td style="padding: 1px 3px; text-align: center;">
                                        <input type="checkbox" style="margin-left: 80px; vertical-align: middle;" {{ ($inspectionChecklist['Muffler'] ?? '') == 'without_silencer' ? 'checked' : '' }} disabled>
                                        <span style="font-size: 10px; font-weight: bold;">Without silencer</span>
                                    </td>
                                @else
                                    <td style="padding: 1px 3px; text-align: center;">
                                        <input type="checkbox" style="margin-left: 0px; vertical-align: middle;" {{ ($inspectionChecklist[$item] ?? '') == 'functional' ? 'checked' : '' }} disabled>
                                    </td>
                                    <td style="padding: 1px 3px; text-align: center;">
                                        <input type="checkbox" style="margin-left: 0px; vertical-align: middle;" {{ ($inspectionChecklist[$item] ?? '') == 'not_functional' ? 'checked' : '' }} disabled>
                                    </td>
                                @endif
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <p style="text-align: center; font-size: 12px;">
                    Therefore as authorized by this office, I certify that the tricycle unit was found to be road
                    worthy.
                </p>

                <div style="display: flex; flex-direction: column; align-items: center; justify-content: center;">
                    <div style="display: flex; align-items: center; justify-content: center; margin-bottom: 4px;">
                        <label style="font-weight: bold; width: 120px; display: inline-block; text-align: right;">Inspected by:</label>
                        <span
                            style="border-bottom: 1px solid #000; min-width: 120px; display: inline-block; margin-left: 10px;">{{ $inspectedBy }}</span>
                    </div>
                    <div style="display: flex; align-items: center; justify-content: center; margin-bottom: 4px;">
                        <label style="font-weight: bold; width: 120px; display: inline-block; text-align: right;">Signature:</label>
                        <span
                            style="border-bottom: 1px solid #000; min-width: 120px; display: inline-block; margin-left: 10px;">{{ $inspectorSignature }}</span>
                    </div>
                    <div style="display: flex; align-items: center; justify-content: center; margin-bottom: 4px;">
                        <label style="font-weight: bold; width: 120px; display: inline-block; text-align: right;">Position Title:</label>
                        <span
                            style="border-bottom: 1px solid #000; min-width: 120px; display: inline-block; margin-left: 10px;">{{ $inspectorPosition }}</span>
                    </div>
                    <div style="display: flex; align-items: center; justify-content: center;">
                        <label style="font-weight: bold; width: 120px; display: inline-block; text-align: right;">Date Inspected:</label>
                        <span
                            style="border-bottom: 1px solid #000; min-width: 120px; display: inline-block; margin-left: 10px;">{{ $inspectionDate }}</span>
                    </div>
                </div>
            </div>

            <div style="flex: 1;">
                <div style="border: 2px solid #000; padding: 5px; margin-bottom: 20px;">
                    <div style="font-weight: bold;">OWNER'S REQUIREMENTS:</div>
                    @php
                        $ownerReqList = [
                            'OR' => 'or',
                            'CR' => 'cr',
                            'Proof of Ownership' => 'proof_of_ownership',
                            "Old MTOP and Mayor's Permit" => 'old_mtop_mayors_permit',
                            'Brgy Clearance' => 'brgy_clearance',
                            'Cedula' => 'cedula'
                        ];
                    @endphp
                    @foreach($ownerReqList as $label => $key)
                        <div
                            style="display: flex; justify-content: space-between; align-items: center;">
                            <span>{{ $label }}</span>
                            <input type="checkbox" style="width: 15px; height: 15px; accent-color: #000;" checked disabled>
                        </div>
                    @endforeach

                    <div style="font-weight: bold; margin-top: 8px; ">DRIVER'S REQUIREMENTS:</div>
                    @php
                        $driverReqList = [
                            'Barangay Clearance' => 'barangay_clearance',
                            'Medical Clearance' => 'medical_clearance',
                            'Drug Test' => 'drug_test',
                            'Police Clearance' => 'police_clearance',
                            "Driver's License" => 'drivers_license',
                            'Cedula' => 'cedula'
                        ];
                    @endphp
                    @foreach($driverReqList as $label => $key)
                        <div
                            style="display: flex; justify-content: space-between; align-items: center;">
                            <span>{{ $label }}</span>
                            <input type="checkbox" style="width: 15px; height: 15px; accent-color: #000;" checked disabled>
                        </div>
                    @endforeach
                </div>

             
                <div
                    style="border: 3px solid #4472C4; background-color: #E7F3FF; padding: 2px; text-align: center; margin: 20px 0;">
                    <div style="font-size: 120px; font-weight: bold; color: #4472C4; letter-spacing: 3px; line-height: 0.8;">GRANTED</div>
                    <div style="margin-top: 10px; font-weight: bold; display: flex; align-items: center; gap: 10px;">
                        <label>FOR: _____</label>
                        <label>FOR UNITS UP TO</label>
                        <span style="border-bottom: 1px solid #000; display: inline-block; ">{{ $grantedUntil ?: '_____' }}</span>
                    </div>
                </div>
                
                <div style="margin-top: 20px;">
                    <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 15px;">
                        <div style="flex: 1; min-width: 0;">
                            <div style="display: flex; align-items: center; margin-bottom: 8px;">
                                <label style="font-weight: bold;">OR No.:</label>
                                <span style="border-bottom: 1px solid #000; flex: 1; margin-left: 10px; display: inline-block;">{{ $or_no ?? 'N/A' }}</span>
                            </div>
                            <div style="display: flex; align-items: center; margi   n-bottom: 8px;">
                                <label style="font-weight: bold;">Date:</label>
                                <span style="border-bottom: 1px solid #000; flex: 1; margin-left: 10px; display: inline-block;">{{ $grantedDate }}</span>
                            </div>
                            <div style="display: flex; align-items: center;">
                                <label style="font-weight: bold;">Amount:</label>
                                <span style="border-bottom: 1px solid #000; width: 100px; display: inline-block; margin-left: 10px;">{{ $amount }}</span>
                            </div>
                        </div>
                        
                        <div style="flex: 1;  text-align: right; font-size: 5px;">
                            <span style="font-weight: bold;">GRANTED BY:</span>
                            <div style="text-decoration: underline; "> ENGR. KHRISTINE Z. TAPALLA, EnP</div>
                            <div>MPDC/ZA</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div style="border: 2px solid #000; padding: 4px; margin-top: 10px;">
            <p style="margin-bottom: 15px; text-align: justify; font-size: 10px;">
                I hereby certify that the information provided herein are true and correct to the best of my knowledge
                and
                belief and any misrepresentation made herein shall be ground for disapproval of this application without
                prejudice to the filing of the corresponding criminal case for perjury.
            </p>

            <div style="text-align: right;  margin-bottom: 10px;">
                <div style="font-weight: bold; margin-right: 60px;">{{ $applicantName }}</div>
                <div
                    style="font-size: 11px; border-top: 1px solid #000; width: 200px; margin-left: auto; text-align: center; padding-bottom: 2px;">
                    Signature of Applicant/Date</div>
            </div>

            <div style="display: flex; font-size: 3px;  align-items: center; gap: 10px; margin-bottom: 10px;">
                <span>Subscribed and sworn to before me this</span>
                <span
                    style="border-bottom: 1px solid #000; width: 40px; text-align: center; background: transparent; display: inline-block; font-size: 8px;">{{ $franchiseApplication->sworn_day ?? '' }}</span>
                <span>day of</span>
                <span
                    style="border-bottom: 1px solid #000; width: 80px; text-align: center; background: transparent; display: inline-block; font-size: 8px;">{{ $franchiseApplication->sworn_month ?? '' }}</span>
                <span>,</span>
                <span
                    style="border-bottom: 1px solid #000; width: 60px; text-align: center; background: transparent; display: inline-block; font-size: 8px;">{{ $franchiseApplication->sworn_year ?? '' }}</span>
                <span>at Padre Garcia, Batangas. Affiant exhibited his/her</span>
                <span >CTC No.</span>

            </div>

            <div style="display: flex; align-items: center; gap: 10px;">
                <span style="font-size: 11px;">issued on</span>
                <span
                    style="border-bottom: 1px solid #000; width: 80px; text-align: center; background: transparent; display: inline-block;">{{ $ctcIssuedOn }}</span>
                <span style="font-size: 11px;">at</span>
                <span
                    style="border-bottom: 1px solid #000; width: 120px; text-align: center; background: transparent; display: inline-block;">{{ $ctcIssuedAt }}</span>

            </div>
            <div style="text-align: right; margin-top: 20px;">
                <div style="font-weight: bold;">ATTY. MARK LESTER G. MANALO</div>
                <div
                    style="font-size: 11px; border-top: 1px solid #000; width: 200px; margin-left: auto; text-align: center; padding-bottom: 2px;">
                    Municipal Administrator</div>
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
                    certificate_type: "Application"
                })
            }).then(res => {
                window.print();
            });
        });
    </script>
</body>

</html>