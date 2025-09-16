<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Motorized Tricycle Franchising Application Form</title>
    <style>
        @media print {
            .no-print { display: none !important; }
            body { margin: 0; padding: 0; }
            .application-container { margin: 0; padding: 0; }
        }
    </style>
</head>

<body style="margin: 0; padding: 0; box-sizing: border-box; font-family: Arial, sans-serif; font-size: 12px; line-height: 1.4; padding: 20px; background-color: #f5f5f5;">
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
    $orNo = $franchiseApplication->or_no ?? '';
    $grantedBy = $franchiseApplication->granted_by ?? '';
    $grantedUnits = $granted->units ?? '';
    $grantedUntil = $granted->until ?? '';
    $grantedDate = $granted->date ?? '';
    $grantedAmount = $granted->amount ?? '';
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
    <div class="no-print" style="text-align: center; margin: 20px 0; padding: 20px; background: #f5f5f5; border-radius: 8px;">
        <h2 style="margin-bottom: 20px; color: #333;">Application Form Preview</h2>
        <div style="display: flex; gap: 15px; justify-content: center; flex-wrap: wrap;">
            <button onclick="window.print()" style="background: #28a745; color: white; border: none; padding: 12px 24px; border-radius: 6px; cursor: pointer; font-size: 16px; font-weight: bold;">
                🖨️ Print Certificate
            </button>
        </div>
    </div>

    <div id="application-certificate" style="max-width: 800px; margin: 0 auto; background: white; padding: 20px; ">
        <div style="text-align: center; margin-bottom: 20px;">
            <div style="display: flex; gap: 20px; margin-bottom: 20px;">
                <div style="border: 2px solid #000; padding: 10px; width: 300px;">
                    <div style="margin-bottom: 8px;">
                        <label style="font-weight: bold; display: inline-block; width: 120px;">Route</label>
                        <span style="border-bottom: 1px solid #000; min-width: 150px; display: inline-block;">{{ $route }}</span>
                    </div>
                    <div style="margin-bottom: 8px;">
                        <label style="font-weight: bold; display: inline-block; width: 120px;">TODA President:</label>
                        <span style="border-bottom: 1px solid #000; min-width: 150px; display: inline-block;">{{ $todaPresident }}</span>
                    </div>
                    <div style="margin-bottom: 8px;">
                        <label style="font-weight: bold; display: inline-block; width: 120px;">Traffic Division:</label>
                        <span style="border-bottom: 1px solid #000; min-width: 150px; display: inline-block;">{{ $trafficDivision }}</span>
                    </div>
                    <div style="margin-bottom: 8px;">
                        <label style="font-weight: bold; display: inline-block; width: 120px;">Public Facilities and Utilities Chairperson:</label>
                        <span style="border-bottom: 1px solid #000; min-width: 150px; display: inline-block;">{{ $publicFacilitiesChair }}</span>
                    </div>
                </div>
            </div>
            <div>
                <h2 style="font-size: 12px; font-weight: bold; margin-bottom: 3px;">Republic of the Philippines</h2>
                <h2 style="font-size: 12px; font-weight: bold; margin-bottom: 3px;">Province of Batangas</h2>
                <h1 style="font-size: 14px; font-weight: bold; margin-bottom: 5px;">MUNICIPALITY OF PADRE GARCIA</h1>
                <h1 style="font-size: 14px; font-weight: bold; margin-bottom: 5px;">MOTORIZED TRICYCLE FRANCHISING AND REGULATORY BOARD</h1>
                <h1 style="font-size: 14px; font-weight: bold; margin-bottom: 5px;">APPLICATION FORM</h1>
            </div>
        </div>
        <div style="flex: 1;">
            <div style="text-align: right; margin-bottom: 20px;">
                <label style="font-weight: bold;">Route</label>
                <span style="border-bottom: 1px solid #000; min-width: 200px; display: inline-block; text-align: center;">{{ $route }}</span>
            </div>
        </div>

        <div style="display: flex; gap: 30px; margin-bottom: 20px;">
            <div style="display: flex; align-items: center; gap: 10px;">
                <label style="font-weight: bold;">Franchise No.:</label>
                <span style="border: 2px solid #000; padding: 2px 5px; width: 80px; display: inline-block;">{{ $franchiseNo }}</span>
            </div>
            <div style="display: flex; align-items: center; gap: 10px;">
                <label style="font-weight: bold;">Sticker No.:</label>
                <span style="border: 2px solid #000; padding: 2px 5px; width: 80px; display: inline-block;">{{ $stickerNo }}</span>
            </div>
        </div>

        <div style="display: flex; gap: 20px;">
            <div style="flex: 2;">
                <div style="display: flex; margin-bottom: 8px; align-items: center;">
                    <label style="font-weight: bold; width: 100px; display: inline-block;">Unit Type:</label>
                    <span style="border-bottom: 1px solid #000; flex: 1; margin-right: 10px; display: inline-block;">{{ $unitType }}</span>
                    <label style="margin-left: 50px; font-weight: bold;">Application Type</label>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px; margin-bottom: 20px;">
                    <div style="display: flex; align-items: center; gap: 5px;">
                        <input type="checkbox" id="new-app" style="margin-right: 5px;" {{ $applicationType == 'new' ? 'checked' : '' }} disabled>
                        <label for="new-app" style="font-weight: bold; color: #ff6600;">New Application</label>
                    </div>
                    <div style="display: flex; align-items: center; gap: 5px;">
                        <input type="checkbox" id="change-motor" style="margin-right: 5px;" {{ $applicationType == 'change_motor' ? 'checked' : '' }} disabled>
                        <label for="change-motor" style="font-weight: bold; color: #ff6600;">Change Motor</label>
                    </div>
                    <div style="display: flex; align-items: center; gap: 5px;">
                        <input type="checkbox" id="renewal" style="margin-right: 5px;" {{ $applicationType == 'renewal' ? 'checked' : '' }} disabled>
                        <label for="renewal" style="font-weight: bold; color: #ff6600;">Renewal</label>
                    </div>
                    <div style="display: flex; align-items: center; gap: 5px;">
                        <input type="checkbox" id="change-ownership" style="margin-right: 5px;" {{ $applicationType == 'change_ownership' ? 'checked' : '' }} disabled>
                        <label for="change-ownership" style="font-weight: bold; color: #ff6600;">Change Ownership</label>
                    </div>
                </div>

                <div style="display: flex; margin-bottom: 8px; align-items: center;">
                    <label style="font-weight: bold; width: 100px; display: inline-block;">Unit Make:</label>
                    <span style="border-bottom: 1px solid #000; flex: 1; margin-right: 10px; display: inline-block;">{{ $unitMake }}</span>
                </div>

                <div style="display: flex; margin-bottom: 8px; align-items: center;">
                    <label style="font-weight: bold; width: 100px; display: inline-block;">Motor No.:</label>
                    <span style="border-bottom: 1px solid #000; flex: 1; margin-right: 10px; display: inline-block;">{{ $motorNo }}</span>
                </div>

                <div style="display: flex; margin-bottom: 8px; align-items: center;">
                    <label style="font-weight: bold; width: 100px; display: inline-block;">Chassis No.:</label>
                    <span style="border-bottom: 1px solid #000; flex: 1; margin-right: 10px; display: inline-block;">{{ $chasisNo }}</span>
                </div>

                <div style="display: flex; margin-bottom: 8px; align-items: center;">
                    <label style="font-weight: bold; width: 100px; display: inline-block;">Plate Number:</label>
                    <span style="border-bottom: 1px solid #000; flex: 1; margin-right: 10px; display: inline-block;">{{ $plateNumber }}</span>
                </div>

                <div style="margin-bottom: 20px;">
                    <div style="font-weight: bold; margin-bottom: 10px; text-decoration: underline;">OWNER'S INFORMATION:</div>
                    <div style="display: flex; margin-bottom: 8px; align-items: center;">
                        <label style="font-weight: bold; width: 100px; display: inline-block;">Name:</label>
                        <span style="border-bottom: 1px solid #000; flex: 1; margin-right: 10px; display: inline-block;">{{ $ownerName }}</span>
                    </div>
                    <div style="display: flex; margin-bottom: 8px; align-items: center;">
                        <label style="font-weight: bold; width: 100px; display: inline-block;">Address:</label>
                        <span style="border-bottom: 1px solid #000; flex: 1; margin-right: 10px; display: inline-block;">{{ $ownerAddress }}</span>
                    </div>
                    <div style="display: flex; margin-bottom: 8px; align-items: center;">
                        <label style="font-weight: bold; width: 100px; display: inline-block;">Birthdate:</label>
                        <span style="border-bottom: 1px solid #000; flex: 1; margin-right: 10px; display: inline-block;">{{ $ownerBirthdate }}</span>
                    </div>
                    <div style="display: flex; margin-bottom: 8px; align-items: center;">
                        <label style="font-weight: bold; width: 100px; display: inline-block;">Age:</label>
                        <span style="border-bottom: 1px solid #000; width: 60px; display: inline-block;">{{ $ownerAge }}</span>
                    </div>
                    <div style="display: flex; margin-bottom: 8px; align-items: center;">
                        <label style="font-weight: bold; width: 100px; display: inline-block;">Sex:</label>
                        <span style="border-bottom: 1px solid #000; min-width: 60px; display: inline-block;">
                            {{ ucfirst($ownerSex) }}
                        </span>
                    </div>
                </div>

                <div style="margin-bottom: 20px;">
                    <div style="font-weight: bold; margin-bottom: 10px; text-decoration: underline;">DRIVER'S INFORMATION:</div>
                    <div style="display: flex; margin-bottom: 8px; align-items: center;">
                        <label style="font-weight: bold; width: 100px; display: inline-block;">Name:</label>
                        <span style="border-bottom: 1px solid #000; flex: 1; margin-right: 10px; display: inline-block;">{{ $driverName }}</span>
                    </div>
                    <div style="display: flex; margin-bottom: 8px; align-items: center;">
                        <label style="font-weight: bold; width: 100px; display: inline-block;">Address:</label>
                        <span style="border-bottom: 1px solid #000; flex: 1; margin-right: 10px; display: inline-block;">{{ $driverAddress }}</span>
                    </div>
                    <div style="display: flex; margin-bottom: 8px; align-items: center;">
                        <label style="font-weight: bold; width: 100px; display: inline-block;">Birthdate:</label>
                        <span style="border-bottom: 1px solid #000; flex: 1; margin-right: 10px; display: inline-block;">{{ $driverBirthdate }}</span>
                        <label style="margin-left: 50px; font-weight: bold; width: 100px; display: inline-block;">Age:</label>
                        <span style="border-bottom: 1px solid #000; width: 60px; display: inline-block;">{{ $driverAge }}</span>
                    </div>
                    <div style="display: flex; margin-bottom: 8px; align-items: center;">
                        <label style="font-weight: bold; width: 100px; display: inline-block;">Civil Status:</label>
                        <span style="border-bottom: 1px solid #000; flex: 1; margin-right: 10px; display: inline-block;">{{ $driverCivilStatus }}</span>
                        <label style="margin-left: 30px; font-weight: bold;">Sex:</label>
                        <span style="border-bottom: 1px solid #000; min-width: 60px; display: inline-block; margin-left: 10px;">
                            {{ ucfirst($driverSex) }}
                        </span>
                    </div>
                    <div style="display: flex; margin-bottom: 8px; align-items: center;">
                        <label style="font-weight: bold; width: 100px; display: inline-block;">License No.:</label>
                        <span style="border-bottom: 1px solid #000; flex: 1; margin-right: 10px; display: inline-block;">{{ $driverLicenseNo }}</span>
                    </div>
                    <div style="display: flex; margin-bottom: 8px; align-items: center;">
                        <label style="font-weight: bold; width: 100px; display: inline-block;">Validity:</label>
                        <span style="border-bottom: 1px solid #000; flex: 1; margin-right: 10px; display: inline-block;">{{ $driverLicenseValidity }}</span>
                    </div>
                    <div style="display: flex; margin-bottom: 8px; align-items: center;">
                        <label style="font-weight: bold; width: 100px; display: inline-block;">Nature of License:</label>
                        <span style="border-bottom: 1px solid #000; flex: 1; margin-right: 10px; display: inline-block;">{{ $driverLicenseNature }}</span>
                    </div>
                </div>

                <p style="margin: 15px 0; font-weight: bold;">
                    This is to certify that the tricycle unit described above was inspected to have the following condition:
                </p>

                <table style="width: 100%; margin-bottom: 20px; border-collapse: collapse;">
                    <thead>
                        <tr>
                            <th style="padding: 5px; text-align: center; background-color: #f0f0f0; font-weight: bold;"></th>
                            <th style="padding: 5px; text-align: center; background-color: #f0f0f0; font-weight: bold;">Functional</th>
                            <th style="padding: 5px; text-align: center; background-color: #f0f0f0; font-weight: bold;">Not Functional</th>
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
                        <tr>
                            <td style="padding: 5px; text-align: left; padding-left: 10px;">{{ $item }}</td>
                            @if($item == 'Muffler')
                            <td style="padding: 5px; text-align: center;">
                                <input type="checkbox" {{ ($inspectionChecklist['Muffler'] ?? '') == 'with_silencer' ? 'checked' : '' }} disabled> With silencer
                            </td>
                            <td style="padding: 5px; text-align: center;">
                                <input type="checkbox" {{ ($inspectionChecklist['Muffler'] ?? '') == 'without_silencer' ? 'checked' : '' }} disabled> Without silencer
                            </td>
                            @else
                            <td style="padding: 5px; text-align: center;">
                                <input type="checkbox" {{ ($inspectionChecklist[$item] ?? '') == 'functional' ? 'checked' : '' }} disabled>
                            </td>
                            <td style="padding: 5px; text-align: center;">
                                <input type="checkbox" {{ ($inspectionChecklist[$item] ?? '') == 'not_functional' ? 'checked' : '' }} disabled>
                            </td>
                            @endif
                        </tr>
                        @endforeach
                    </tbody>
                </table>

                <p style="font-weight: bold; text-align: center; margin: 15px 0;">
                    Therefore as authorized by this office, I certify that the tricycle unit was found to be road worthy.
                </p>

                <div style="margin-top: 20px;">
                    <div style="display: flex; margin-bottom: 10px; align-items: center;">
                        <label style="font-weight: bold; width: 120px; display: inline-block;">Inspected by:</label>
                        <span style="border-bottom: 1px solid #000; flex: 1; display: inline-block;">{{ $inspectedBy }}</span>
                    </div>
                    <div style="display: flex; margin-bottom: 10px; align-items: center;">
                        <label style="font-weight: bold; width: 120px; display: inline-block;">Signature:</label>
                        <span style="border-bottom: 1px solid #000; flex: 1; display: inline-block;">{{ $inspectorSignature }}</span>
                    </div>
                    <div style="display: flex; margin-bottom: 10px; align-items: center;">
                        <label style="font-weight: bold; width: 120px; display: inline-block;">Position Title:</label>
                        <span style="border-bottom: 1px solid #000; flex: 1; display: inline-block;">{{ $inspectorPosition }}</span>
                    </div>
                    <div style="display: flex; margin-bottom: 10px; align-items: center;">
                        <label style="font-weight: bold; width: 120px; display: inline-block;">Date Inspected:</label>
                        <span style="border-bottom: 1px solid #000; flex: 1; display: inline-block;">{{ $inspectionDate }}</span>
                    </div>
                </div>
            </div>

            <div style="flex: 1;">
                <div style="border: 2px solid #000; padding: 10px; margin-bottom: 20px;">
                    <div style="font-weight: bold; margin-bottom: 10px;">OWNER'S REQUIREMENTS:</div>
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
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 5px;">
                        <span>{{ $label }}</span>
                        <input type="checkbox" style="width: 15px; height: 15px; accent-color: #000;" checked disabled>
                    </div>
                    @endforeach
                </div>

                <div style="border: 2px solid #000; padding: 10px; margin-bottom: 20px;">
                    <div style="font-weight: bold; margin-bottom: 10px;">DRIVER'S REQUIREMENTS:</div>
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
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 5px;">
                        <span>{{ $label }}</span>
                        <input type="checkbox" style="width: 15px; height: 15px; accent-color: #000;" checked disabled>
                    </div>
                    @endforeach
                </div>

                <div style="border: 3px solid #4472C4; background-color: #E7F3FF; padding: 15px; text-align: center; margin: 20px 0;">
                    <div style="font-size: 24px; font-weight: bold; color: #4472C4; letter-spacing: 3px;">GRANTED</div>
                    <div style="margin-top: 10px; font-weight: bold;">
                        FOR {{ $grantedUnits ?: '_____' }} UNITS UP TO {{ $grantedUntil ?: '_____' }}
                    </div>
                </div>

                <div style="margin-top: 20px;">
                    <div style="display: flex; margin-bottom: 15px; align-items: center;">
                        <label style="font-weight: bold;">OR No.:</label>
                        <span style="border-bottom: 1px solid #000; flex: 1; margin-left: 10px; display: inline-block;">{{ $orNo }}</span>
                    </div>

                    <div style="text-align: right; margin-bottom: 10px;">
                        <span style="font-weight: bold;">GRANTED BY:</span>
                    </div>

                    <div style="display: flex; margin-bottom: 8px; align-items: center;">
                        <label style="font-weight: bold;">Date:</label>
                        <span style="border-bottom: 1px solid #000; flex: 1; margin-left: 10px; display: inline-block;">{{ $grantedDate }}</span>
                    </div>

                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px;">
                        <label style="font-weight: bold;">Amount:</label>
                        <span style="border-bottom: 1px solid #000; width: 100px; display: inline-block;">{{ $grantedAmount }}</span>
                    </div>

                    <div style="text-align: right; font-weight: bold; font-size: 10px;">
                        <div>ENGR. KHRISTINE Z. TAPALLA, EnP</div>
                        <div>OIC-MPDC/ZA</div>
                    </div>
                </div>
            </div>
        </div>
        <div style="border: 2px solid #000; padding: 15px; margin-top: 20px;">
            <p style="margin-bottom: 15px; text-align: justify; font-size: 11px;">
                I hereby certify that the information provided herein are true and correct to the best of my knowledge and belief and any misrepresentation made herein shall be ground for disapproval of this application without prejudice to the filing of the corresponding criminal case for perjury.
            </p>

            <div style="text-align: right; margin-bottom: 10px;">
                <div style="font-weight: bold;">{{ $applicantName }}</div>
                <div style="font-size: 11px; border-bottom: 1px solid #000; width: 200px; margin-left: auto; text-align: center; padding-bottom: 2px;">Signature of Applicant/Date</div>
            </div>

            <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 15px;">
                <span style="font-size: 11px;">Subscribed and sworn to before me this</span>
                <span style="border-bottom: 1px solid #000; width: 40px; text-align: center; background: transparent; display: inline-block;">{{ $franchiseApplication->sworn_day ?? '' }}</span>
                <span style="font-size: 11px;">day of</span>
                <span style="border-bottom: 1px solid #000; width: 80px; text-align: center; background: transparent; display: inline-block;">{{ $franchiseApplication->sworn_month ?? '' }}</span>
                <span style="font-size: 11px;">,</span>
                <span style="border-bottom: 1px solid #000; width: 60px; text-align: center; background: transparent; display: inline-block;">{{ $franchiseApplication->sworn_year ?? '' }}</span>
                <span style="font-size: 11px;">at Padre Garcia, Batangas. Affiant exhibited his/her</span>
                <span style="font-size: 11px; margin-left: 20px;">CTC No.</span>
            </div>

            <div style="display: flex; align-items: center; gap: 10px;">
                <span style="font-size: 11px;">issued on</span>
                <span style="border-bottom: 1px solid #000; width: 80px; text-align: center; background: transparent; display: inline-block;">{{ $ctcIssuedOn }}</span>
                <span style="font-size: 11px;">at</span>
                <span style="border-bottom: 1px solid #000; width: 120px; text-align: center; background: transparent; display: inline-block;">{{ $ctcIssuedAt }}</span>
            </div>

            <div style="text-align: right; margin-top: 20px;">
                <div style="font-weight: bold;">ATTY. MARK LESTER G. MANALO</div>
                <div style="font-size: 11px; border-bottom: 1px solid #000; width: 200px; margin-left: auto; text-align: center; padding-bottom: 2px;">Municipal Administrator</div>
            </div>
        </div>
    </div>

    <script>
        function downloadPDF() {
            window.location.href = "{{ route('admin.certificates.application.generate', $motorDetail->id) }}";
        }
    </script>
</body>

</html>