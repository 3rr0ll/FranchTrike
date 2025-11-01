<x-guest-layout>
    <div class="min-h-screen w-full bg-[#e5e5e4] flex flex-col justify-start py-10 px-2 sm:px-0">
        <div class="flex flex-col items-center mb-8 px-2">
            <div>
                <img src="{{ asset('images/logo.png') }}" alt="FranchTrike Logo" class="h-20 w-20">
            </div>
            <h1 class="text-3xl sm:text-4xl font-bold text-primary-navy text-center">Privacy Policy</h1>
            <p class="mt-3 text-gray-600 text-center max-w-2xl">
                This Privacy Policy explains how we collect, use, and protect the information provided through the Online Franchise Application System (“System”). 
                By accessing and using this platform, you consent to the data practices described below.
            </p>
        </div>
        <div class="prose max-w-3xl w-full mx-auto px-2 sm:px-4">
            <h4 class="mt-8 text-primary-navy font-semibold">1. Information We Collect</h4>
            <ul class="list-disc ml-6">
                <li>Personal details such as name, address, contact number, and email address.</li>
                <li>Franchise-related documents including permits, identification, and supporting certifications.</li>
                <li>System usage information such as login activity and application status history.</li>
            </ul>
        
            <h4 class="mt-8 text-primary-navy font-semibold">2. How We Use Your Information</h4>
            <p>
                Collected data is used solely for the purpose of processing franchise applications, renewals, compliance monitoring, and communication related to your account.
                The information also supports record management, payment tracking, and reporting for administrative use.
            </p>
        
            <h4 class="mt-8 text-primary-navy font-semibold">3. Data Security</h4>
            <p>
                We implement appropriate security measures, including encryption and role-based access control, to safeguard your personal and application data.
                Access is limited to authorized personnel only.
            </p>
        
            <h4 class="mt-8 text-primary-navy font-semibold">4. Data Retention</h4>
            <p>
                All submitted data and transaction records are retained to maintain accountability, comply with audit requirements, and prevent loss of historical information.
                Deletion of records is restricted to ensure a complete data trail.
            </p>
        
            <h4 class="mt-8 text-primary-navy font-semibold">5. User Rights</h4>
            <p>
                Users have the right to review and update their personal information at any time. Requests for data correction or account assistance can be submitted through the platform’s support feature.
            </p>
        
            <h4 class="mt-8 text-primary-navy font-semibold">6. Third-Party Services</h4>
            <p>
                The System may integrate third-party services for notifications or payment verification. These services adhere to their own privacy policies, and users are encouraged to review them when applicable.
            </p>
        
            <h4 class="mt-8 text-primary-navy font-semibold">7. Internet Connectivity</h4>
            <p>
                The System relies on stable internet access for full functionality. Users are responsible for ensuring reliable connectivity during application submission and document upload.
            </p>
        
            <h4 class="mt-8 text-primary-navy font-semibold">8. Policy Updates</h4>
            <p>
                We may modify this Privacy Policy as needed to reflect system improvements or legal compliance requirements. Updates will be posted within the System and take effect immediately upon publication.
            </p>
        
            <h4 class="mt-8 text-primary-navy font-semibold">9. Contact Us</h4>
            <p>
                If you have any questions or concerns regarding your data privacy, you may reach out to the system administrator via the platform’s contact page.
            </p>
        </div>
        <p class="mt-12 text-center text-gray-400 select-none text-xs">&copy; {{ date('Y') }} FranchTrike. All Rights Reserved.</p>
    </div>
</x-guest-layout>