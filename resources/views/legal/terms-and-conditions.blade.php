<x-app-layout>
    <div class="flex flex-col justify-center gap-12 max-w-screen-xl px-4 py-32 mx-auto md:px-16 overflow-hidden">
        <h3>{{ __('Terms and Conditions') }}</h3>
        <p>{{ __('Last updated: February, 2025') }}</p>

        <div class="flex flex-col gap-4">
            <h6>{{ __('1. Introduction') }}</h6>

            <p>{{ __('Welcome to chibalancetherapies.com. By accessing or using this website, you agree to comply with these terms and conditions. If you do not agree with any part of these terms, please do not use this site.') }}</p>
        </div>

        <div class="flex flex-col gap-4">
            <h6>{{ __('2. Website Owner Information') }}</h6>
            
            <p>{{ __('This website is operated by Chi Balance Therapies, a business specializing in holistic wellness and therapy services based in Ireland. For any inquiries, you can contact us at ') }}<a href="mailto:chibalancetherapies@gmail.com">chibalancetherapies@gmail.com</a>.</p>
        </div>

        <div class="flex flex-col gap-4">
            <h6>{{ __('3. Use of the Website') }}</h6>
            
            <p>{{ __('This site is intended for users seeking information about our services and offerings.') }}</p>

            <p>{{ __('The following activities are prohibited:') }}</p>

            <ul class="list-disc pl-12">
                <li><p>{{ __('Using the site for illegal or unauthorized activities.') }}</p></li>
                <li><p>{{ __('Attempting to damage, overload, or disable the website or its infrastructure.') }}</p></li>
                <li><p>{{ __('Copying, distributing, or modifying any content without prior written consent.') }}</p></li>
            </ul>
        </div>
        
        <div class="flex flex-col gap-4">
            <h6>{{ __('4. Intellectual Property') }}</h6>
            
            <p>{{ __('All content on this website, including designs, text, images, graphics, and logos, is the property of Chi Balance Therapies or its licensors and is protected by applicable intellectual property laws.') }}</p>

            <p>{{ __('Unauthorized use, reproduction, or distribution of any content is prohibited.') }}</p>
        </div>
        
        <div class="flex flex-col gap-4">
            <h6>{{ __('5. Forms and Personal Data') }}</h6>

            <p>{{ __('By using contact or inquiry forms on this website, you agree to provide accurate and complete information.') }}</p>

            <p>{{ __('Any personal data submitted through this website will be processed in accordance with our ') }}<a href="/privacy-policy">{{ __('Privacy Policy') }}</a>.</p>

            <p>{{ __('You should review and accept the Privacy Policy before submitting any personal information through our forms.') }}</p>
        </div>

        <div class="flex flex-col gap-4">
            <h6>{{ __('6. Use of Cookies') }}</h6>

            <p>{{ __('This website uses cookies to enhance user experience and to collect anonymous statistical data about website usage. You can manage or withdraw your consent through the cookie banner displayed on the site.') }}</p>
        </div>

        <div class="flex flex-col gap-4">
            <h6>{{ __('7. Health and Wellness Disclaimer') }}</h6>

            <p>{{ __('The services provided by Chi Balance Therapies are complementary wellness practices intended to support general wellbeing.') }}</p>

            <p>{{ __('They are not intended to replace professional medical advice, diagnosis, or treatment. Always seek the advice of a qualified healthcare provider regarding any medical condition.') }}</p>
        </div>

        <div class="flex flex-col gap-4">
            <h6>{{ __('8. Limitation of Liability') }}</h6>

            <p>{{ __('Although we strive to keep the information on this website accurate and up to date, we make no guarantees regarding the completeness, reliability, or accuracy of the content.') }}</p>

            <p>{{ __('Chi Balance Therapies shall not be held liable for any damages resulting from the use of this website, including but not limited to:') }}</p>

            <ul class="list-disc pl-12">
                <li><p>{{ __('Temporary service interruptions.') }}</p></li>
                <li><p>{{ __('Errors or omissions in the information provided.') }}</p></li>
                <li><p>{{ __('Technical issues beyond our control.') }}</p></li>
            </ul>
        </div>
        
        <div class="flex flex-col gap-4">
            <h6>{{ __('9. Modifications') }}</h6>

            <p>{{ __('We reserve the right to update or modify these Terms and Conditions at any time. The date of the latest update will always be indicated at the top of this document. We recommend reviewing these terms periodically.') }}</p>
        </div>

        <div class="flex flex-col gap-4">
            <h6>{{ __('10. Applicable Law') }}</h6>

            <p>{{ __('These Terms and Conditions are governed by and interpreted in accordance with the laws of Ireland. Any disputes relating to these terms shall be subject to the jurisdiction of the Irish courts.') }}</p>
        </div>

        <div class="flex flex-col gap-4">
            <h6>{{ __('11. Contact') }}</h6>

            <p>{{ __('If you have any questions about these Terms and Conditions, you can contact us at ') }}<a href="mailto:chibalancetherapies@gmail.com">chibalancetherapies@gmail.com</a>.</p>
        </div>
    </div>
</x-app-layout>