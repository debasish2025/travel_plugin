/**
 * Travel Package Animations and Interactions
 */

(function($) {
    'use strict';

    $(document).ready(function() {

        // =====================================
        // HORIZONTAL TABS FUNCTIONALITY
        // =====================================

        $('.stp-tab-btn').on('click', function() {
            var tabId = $(this).data('tab');

            // Remove active class from all buttons and panels
            $('.stp-tab-btn').removeClass('active');
            $('.stp-tab-panel').removeClass('active');

            // Add active class to clicked button and corresponding panel
            $(this).addClass('active');
            $('#' + tabId).addClass('active');
        });

        // =====================================
        // GSAP ANIMATIONS (if available)
        // =====================================

        if (typeof gsap !== 'undefined' && typeof ScrollTrigger !== 'undefined') {
            gsap.registerPlugin(ScrollTrigger);

            // Animate tabs container
            gsap.from('.stp-tabs-container', {
                opacity: 0,
                y: 40,
                duration: 0.8,
                ease: 'power3.out',
                scrollTrigger: {
                    trigger: '.stp-tabs-container',
                    start: 'top 80%'
                }
            });

            // Animate features section
            gsap.from('.stp-features-compact', {
                opacity: 0,
                y: 30,
                duration: 0.6,
                ease: 'power2.out',
                scrollTrigger: {
                    trigger: '.stp-features-compact',
                    start: 'top 85%'
                }
            });

            // Animate WhatsApp button
            gsap.from('.whatsapp-cta-container', {
                scale: 0.9,
                opacity: 0,
                duration: 0.5,
                ease: 'back.out(1.7)',
                scrollTrigger: {
                    trigger: '.whatsapp-cta-container',
                    start: 'top 90%'
                }
            });
        }

        console.log('✅ Animations loaded');
    });

})(jQuery);
