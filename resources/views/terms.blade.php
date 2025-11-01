<x-guest-layout>
    <div class="min-h-screen w-full bg-[#e5e5e4] flex flex-col justify-start py-10 px-2 sm:px-0">
        <div class="flex flex-col items-center mb-8 px-2">
            <div>
                <img src="{{ asset('images/logo.png') }}" alt="FranchTrike Logo" class="h-20 w-20">
            </div>
            <h1 class="text-3xl sm:text-4xl font-bold text-primary-navy text-center">Terms and Conditions</h1>
            <p class="mt-3 text-gray-600 text-center max-w-2xl">
                Welcome to our Online Franchise Application System (“System”). By using this platform, you agree to comply with and be bound by the following Terms and Conditions. Please read them carefully before using the service.
            </p>
        </div>
        <div class="prose max-w-3xl w-full mx-auto px-2 sm:px-4">
            <h4 class="mt-8 text-primary-navy font-semibold">1. System Purpose</h4>
            <p>
                The System is designed to simplify and streamline the process of tricycle franchise registration and renewal. It enables online submission of applications, document verification, payment recording, and real-time tracking of application status for both applicants and administrators.
            </p>

            <h4 class="mt-8 text-primary-navy font-semibold">2. User Responsibilities</h4>
            <ul class="list-disc ml-6">
                <li>Users must provide accurate and complete information during registration and application.</li>
                <li>Any falsified or misleading data may result in application rejection or revocation of an existing franchise.</li>
                <li>Applicants are responsible for monitoring their application status and responding promptly to requests for additional information.</li>
            </ul>

            <h4 class="mt-8 text-primary-navy font-semibold">3. Administrator Responsibilities</h4>
            <p>
                Administrators are responsible for verifying submitted documents, reviewing applications, and updating statuses in accordance with established franchising policies and regulations.
            </p>

            <h4 class="mt-8 text-primary-navy font-semibold">4. Payment Transactions</h4>
            <p>
                All payments made through the System are recorded digitally for transparency and audit purposes. Currently, only walk-in payments are supported. Users will receive digital receipts once transactions are verified by the administrator.
            </p>

            <h4 class="mt-8 text-primary-navy font-semibold">5. Data Integrity and Access</h4>
            <p>
                To maintain complete audit trails and accountability, data deletion is not permitted within the System. However, users and administrators can update records to ensure accuracy.
            </p>

            <h4 class="mt-8 text-primary-navy font-semibold">6. Communication and Notifications</h4>
            <p>
                The System may send automated notifications for application status, renewal reminders, and pending requirements. Users are encouraged to keep their contact information up to date to ensure successful delivery of messages.
            </p>

            <h4 class="mt-8 text-primary-navy font-semibold">7. Limitation of Liability</h4>
            <p>
                While the System aims to ensure availability and accuracy, the administrators are not liable for delays or failures caused by system maintenance, technical issues, or unstable internet connections.
            </p>

            <h4 class="mt-8 text-primary-navy font-semibold">8. Modification of Terms</h4>
            <p>
                These Terms and Conditions may be updated periodically. Continued use of the System after changes indicates acceptance of the revised terms.
            </p>

            <h4 class="mt-8 text-primary-navy font-semibold">9. Contact Information</h4>
            <p>
                For questions or concerns about these Terms, users may contact the system administrator through the provided support channels within the platform.
            </p>
        </div>
        <p class="mt-12 text-center text-gray-400 select-none text-xs">&copy; {{ date('Y') }} FranchTrike. All Rights Reserved.</p>
    </div>
</x-guest-layout>
