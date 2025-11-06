/**
 * Ultra Modern Travel Package Animations
 */

(function($) {
    'use strict';

    $(document).ready(function() {

        // =====================================
        // ULTRA MODERN TAB SWITCHING
        // =====================================

        $('.stp-tab-button').on('click', function() {
            var tabId = $(this).data('tab');

            // Remove active from all
            $('.stp-tab-button').removeClass('active');
            $('.stp-tab-panel-modern').removeClass('active');

            // Add active to clicked
            $(this).addClass('active');
            $('#' + tabId).addClass('active');

            // Smooth scroll tabs into view on mobile
            if ($(window).width() < 768) {
                this.scrollIntoView({ behavior: 'smooth', inline: 'center', block: 'nearest' });
            }
        });

        // =====================================
        // GSAP SCROLL ANIMATIONS - FIXED
        // =====================================

        if (typeof gsap !== 'undefined' && typeof ScrollTrigger !== 'undefined') {
            gsap.registerPlugin(ScrollTrigger);

            // Section header - no initial hide
            gsap.from('.stp-section-header', {
                opacity: 0,
                y: 50,
                duration: 0.8,
                ease: 'power3.out',
                scrollTrigger: {
                    trigger: '.stp-section-header',
                    start: 'top 80%',
                    once: true
                }
            });

            // Tabs container - no initial hide
            gsap.from('.stp-tabs-modern', {
                opacity: 0,
                y: 40,
                duration: 0.8,
                ease: 'power3.out',
                scrollTrigger: {
                    trigger: '.stp-tabs-modern',
                    start: 'top 75%',
                    once: true
                }
            });

            // IMPORTANT: Don't animate tab buttons - they need to be visible!
            // Remove the tab-button animation that was hiding them

            // Features section
            gsap.from('.stp-features-modern', {
                opacity: 0,
                y: 30,
                duration: 0.6,
                ease: 'power2.out',
                scrollTrigger: {
                    trigger: '.stp-features-modern',
                    start: 'top 85%',
                    once: true
                }
            });

            // WhatsApp card
            gsap.from('.stp-whatsapp-card', {
                opacity: 0,
                scale: 0.95,
                duration: 0.6,
                ease: 'back.out(1.7)',
                scrollTrigger: {
                    trigger: '.stp-whatsapp-card',
                    start: 'top 85%',
                    once: true
                }
            });

            // Icon pulse animation
            gsap.to('.stp-section-icon-wrapper', {
                scale: 1.05,
                duration: 2,
                ease: 'power1.inOut',
                yoyo: true,
                repeat: -1
            });
        }

        console.log('✅ Ultra-modern animations loaded!');
    });

})(jQuery);
