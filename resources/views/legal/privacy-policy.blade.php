<x-app-layout>
    <div class="flex flex-col justify-center gap-12 max-w-screen-xl px-4 py-32 mx-auto md:px-16 overflow-hidden">
        <h3>{{ __('Cookies & Privacy Policy') }}</h3>
        <p>{{ __('Last updated: February, 2025') }}</p>

        <div class="flex flex-col gap-4">
            <h6>{{ __('1. General Information') }}</h6>

            <p>{{ __('Chibalancetherapies.com is committed to protecting your privacy and ensuring that your personal data is handled in accordance with the General Data Protection Regulation (GDPR) and applicable data protection laws in Ireland.') }}</p>

            <p>{{ __('For the purposes of data protection legislation, Chi Balance Therapies acts as the data controller of the personal information collected through this website.') }}</p>
        </div>

        <div class="flex flex-col gap-4">
            <h6>{{ __('2. Data We Collect') }}</h6>

            <p>{{ __('We may collect personal information through the following methods:') }}</p>

            <p><strong>{{ __('Contact Forms') }}</strong></p>

            <ul class="list-disc pl-12">
                <li><p>{{ __('Name') }}</p></li>
                <li><p>{{ __('Email address') }}</p></li>
                <li><p>{{ __('Phone number (optional)') }}</p></li>
                <li><p>{{ __('Message content') }}</p></li>
            </ul>

            <p><strong>{{ __('Automatically through cookies and analytics tools') }}</strong></p>

            <ul class="list-disc pl-12">
                <li><p>{{ __('IP address') }}</p></li>
                <li><p>{{ __('Device and browser information') }}</p></li>
                <li><p>{{ __('Pages visited and time spent on the website') }}</p></li>
            </ul>
        </div>

        <div class="flex flex-col gap-4">
            <h6>{{ __('3. Purpose of Data Processing') }}</h6>

            <p>{{ __('We process personal data for the following purposes:') }}</p>

            <ul class="list-disc pl-12">
                <li><p>{{ __('Responding to contact requests and inquiries.') }}</p></li>
                <li><p>{{ __('Providing information about our services.') }}</p></li>
                <li><p>{{ __('Improving website functionality and performance through analytics.') }}</p></li>
                <li><p>{{ __('Maintaining the security and integrity of the website.') }}</p></li>
            </ul>

            <p>{{ __('We do not sell or rent personal data to third parties.') }}</p>
        </div>

        <div class="flex flex-col gap-4">
            <h6>{{ __('4. Use of Cookies') }}</h6>

            <p>{{ __('This website uses cookies to improve user experience and analyze website traffic.') }}</p>

            <p>{{ __('Examples of cookies that may be used include:') }}</p>

            <ul class="list-disc pl-12">
                <li><p>{{ __('Essential cookies required for website functionality (such as session cookies).') }}</p></li>
                <li><p>{{ __('Analytics cookies used to understand how visitors interact with the website (for example Google Analytics).') }}</p></li>
                <li><p>{{ __('Consent cookies that remember your cookie preferences.') }}</p></li>
            </ul>

            <p>{{ __('You can manage or withdraw your cookie consent through the cookie banner or by adjusting your browser settings.') }}</p>
        </div>

        <div class="flex flex-col gap-4">
            <h6>{{ __('5. Legal Basis for Processing') }}</h6>

            <p>{{ __('Under the GDPR, we rely on the following legal bases for processing personal data:') }}</p>

            <ul class="list-disc pl-12">
                <li><p>{{ __('User consent when submitting contact forms or accepting cookies.') }}</p></li>
                <li><p>{{ __('Legitimate interest in improving our website and services.') }}</p></li>
                <li><p>{{ __('Compliance with legal obligations when required.') }}</p></li>
            </ul>
        </div>

        <div class="flex flex-col gap-4">
            <h6>{{ __('6. Data Retention') }}</h6>

            <p>{{ __('Personal data will only be kept for as long as necessary to fulfill the purposes described in this policy or until a user requests its deletion, unless a longer retention period is required by law.') }}</p>
        </div>

        <div class="flex flex-col gap-4">
            <h6>{{ __('7. User Rights') }}</h6>

            <p>{{ __('Under the GDPR, users have the right to:') }}</p>

            <ul class="list-disc pl-12">
                <li><p>{{ __('Access their personal data.') }}</p></li>
                <li><p>{{ __('Request correction of inaccurate information.') }}</p></li>
                <li><p>{{ __('Request deletion of their personal data.') }}</p></li>
                <li><p>{{ __('Restrict or object to data processing.') }}</p></li>
                <li><p>{{ __('Withdraw previously given consent.') }}</p></li>
            </ul>

            <p>{{ __('To exercise these rights, please contact us at ') }}<a href="mailto:chibalancetherapies@gmail.com">chibalancetherapies@gmail.com</a>.</p>
        </div>

        <div class="flex flex-col gap-4">
            <h6>{{ __('8. Security Measures') }}</h6>

            <p>{{ __('We take reasonable technical and organizational measures to protect personal data, including:') }}</p>

            <ul class="list-disc pl-12">
                <li><p>{{ __('SSL encryption to secure communications.') }}</p></li>
                <li><p>{{ __('Secure hosting infrastructure.') }}</p></li>
                <li><p>{{ __('Regular backups and monitoring.') }}</p></li>
            </ul>
        </div>

        <div class="flex flex-col gap-4">
            <h6>{{ __('9. Policy Updates') }}</h6>

            <p>{{ __('We may update this Privacy Policy from time to time to reflect legal, technical, or operational changes. The latest update date will always appear at the top of this document.') }}</p>
        </div>

        <div class="flex flex-col gap-4">
            <h6>{{ __('10. Contact') }}</h6>

            <p>{{ __('If you have questions about this Privacy Policy or how your data is handled, you can contact us at ') }}<a href="mailto:chibalancetherapies@gmail.com">chibalancetherapies@gmail.com</a>.</p>
        </div>
    </div>
</x-app-layout>