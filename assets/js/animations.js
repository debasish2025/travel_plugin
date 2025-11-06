/**
 * Travel Package Animations with GSAP
 * Modern, professional animations for itinerary and features sections
 */

(function($) {
    'use strict';

    // Wait for DOM to be fully loaded
    $(document).ready(function() {

        // Register GSAP ScrollTrigger plugin
        if (typeof gsap !== 'undefined' && typeof ScrollTrigger !== 'undefined') {
            gsap.registerPlugin(ScrollTrigger);

            // =====================================
            // ITINERARY ANIMATIONS
            // =====================================

            // Animate each itinerary day item on scroll
            const itineraryItems = document.querySelectorAll('.stp-itinerary-day-item');

            itineraryItems.forEach((item, index) => {
                // Initial state
                gsap.set(item, {
                    opacity: 0,
                    y: 50,
                    scale: 0.95
                });

                // Animate on scroll
                gsap.to(item, {
                    opacity: 1,
                    y: 0,
                    scale: 1,
                    duration: 0.8,
                    ease: 'power3.out',
                    scrollTrigger: {
                        trigger: item,
                        start: 'top 85%',
                        end: 'top 20%',
                        toggleActions: 'play none none reverse'
                    },
                    delay: index * 0.1 // Stagger effect
                });

                // Add parallax effect to day number
                const dayNumber = item.querySelector('.stp-day-number');
                if (dayNumber) {
                    gsap.to(dayNumber, {
                        y: -20,
                        ease: 'none',
                        scrollTrigger: {
                            trigger: item,
                            start: 'top bottom',
                            end: 'bottom top',
                            scrub: 1
                        }
                    });
                }

                // Hover animation
                item.addEventListener('mouseenter', function() {
                    gsap.to(this, {
                        scale: 1.03,
                        duration: 0.3,
                        ease: 'power2.out'
                    });

                    gsap.to(this.querySelector('.stp-day-number'), {
                        rotation: 5,
                        scale: 1.1,
                        duration: 0.3,
                        ease: 'back.out(1.7)'
                    });
                });

                item.addEventListener('mouseleave', function() {
                    gsap.to(this, {
                        scale: 1,
                        duration: 0.3,
                        ease: 'power2.out'
                    });

                    gsap.to(this.querySelector('.stp-day-number'), {
                        rotation: 0,
                        scale: 1,
                        duration: 0.3,
                        ease: 'back.out(1.7)'
                    });
                });
            });

            // Animate itinerary timeline line (if it exists)
            const timeline = document.querySelector('.stp-itinerary-timeline');
            if (timeline) {
                gsap.from(timeline, {
                    opacity: 0,
                    duration: 0.8,
                    ease: 'power2.out',
                    scrollTrigger: {
                        trigger: timeline,
                        start: 'top 80%'
                    }
                });
            }

            // =====================================
            // FEATURES SECTION ANIMATIONS
            // =====================================

            const featureCards = document.querySelectorAll('.stp-feature-card');

            featureCards.forEach((card, index) => {
                // Initial state
                gsap.set(card, {
                    opacity: 0,
                    y: 60,
                    rotationX: -15
                });

                // Animate on scroll with 3D effect
                gsap.to(card, {
                    opacity: 1,
                    y: 0,
                    rotationX: 0,
                    duration: 0.9,
                    ease: 'power3.out',
                    scrollTrigger: {
                        trigger: card,
                        start: 'top 85%',
                        end: 'top 20%',
                        toggleActions: 'play none none reverse'
                    },
                    delay: index * 0.12 // Stagger effect
                });

                // Hover animations for feature cards
                card.addEventListener('mouseenter', function() {
                    gsap.to(this, {
                        y: -10,
                        scale: 1.05,
                        duration: 0.4,
                        ease: 'power2.out'
                    });

                    gsap.to(this.querySelector('.stp-feature-icon'), {
                        scale: 1.2,
                        rotation: 360,
                        duration: 0.6,
                        ease: 'back.out(1.7)'
                    });
                });

                card.addEventListener('mouseleave', function() {
                    gsap.to(this, {
                        y: 0,
                        scale: 1,
                        duration: 0.4,
                        ease: 'power2.out'
                    });

                    gsap.to(this.querySelector('.stp-feature-icon'), {
                        scale: 1,
                        rotation: 0,
                        duration: 0.4,
                        ease: 'power2.out'
                    });
                });
            });

            // Animate feature tags on hover
            const featureTags = document.querySelectorAll('.stp-feature-tag');
            featureTags.forEach(tag => {
                tag.addEventListener('mouseenter', function() {
                    gsap.to(this, {
                        scale: 1.15,
                        duration: 0.3,
                        ease: 'back.out(1.7)'
                    });
                });

                tag.addEventListener('mouseleave', function() {
                    gsap.to(this, {
                        scale: 1,
                        duration: 0.3,
                        ease: 'power2.out'
                    });
                });
            });

            // =====================================
            // SECTION TITLE ANIMATIONS
            // =====================================

            const sectionTitles = document.querySelectorAll('.stp-section-title');

            sectionTitles.forEach(title => {
                // Animate title
                gsap.from(title, {
                    opacity: 0,
                    x: -50,
                    duration: 0.8,
                    ease: 'power3.out',
                    scrollTrigger: {
                        trigger: title,
                        start: 'top 85%'
                    }
                });

                // Animate the underline
                const titleUnderline = title.querySelector('::after');
                gsap.from(title, {
                    '--underline-width': '0px',
                    duration: 1,
                    ease: 'power2.out',
                    scrollTrigger: {
                        trigger: title,
                        start: 'top 85%'
                    }
                });

                // Animate icon
                const icon = title.querySelector('.stp-title-icon');
                if (icon) {
                    gsap.from(icon, {
                        scale: 0,
                        rotation: -180,
                        duration: 0.8,
                        ease: 'back.out(1.7)',
                        scrollTrigger: {
                            trigger: title,
                            start: 'top 85%'
                        }
                    });

                    // Continuous subtle animation for icons
                    gsap.to(icon, {
                        y: -5,
                        duration: 2,
                        ease: 'power1.inOut',
                        yoyo: true,
                        repeat: -1
                    });
                }
            });

            // =====================================
            // PAGE LOAD ANIMATIONS
            // =====================================

            // Fade in description section
            const descriptionSection = document.querySelector('.stp-description-section');
            if (descriptionSection) {
                gsap.from(descriptionSection, {
                    opacity: 0,
                    y: 30,
                    duration: 0.8,
                    ease: 'power2.out',
                    delay: 0.2
                });
            }

            // =====================================
            // SMOOTH SCROLL ENHANCEMENTS
            // =====================================

            // Add smooth scrolling behavior for anchor links
            $('a[href^="#"]').on('click', function(e) {
                const target = $(this.getAttribute('href'));
                if (target.length) {
                    e.preventDefault();
                    gsap.to(window, {
                        duration: 1,
                        scrollTo: {
                            y: target,
                            offsetY: 80
                        },
                        ease: 'power3.inOut'
                    });
                }
            });

            console.log('✨ GSAP animations loaded successfully!');

        } else {
            console.warn('⚠️ GSAP or ScrollTrigger not loaded. Animations disabled.');
        }
    });

})(jQuery);
