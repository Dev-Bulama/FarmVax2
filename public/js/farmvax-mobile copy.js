/* ============================================
   FARMVAX MOBILE MENU & RESPONSIVENESS
   ============================================
   
   How to use:
   Add this line before closing </body> tag:
   <script src="{{ asset('js/farmvax-mobile.js') }}"></script>
   ============================================ */

(function() {
    'use strict';

    // Wait for DOM to be fully loaded
    document.addEventListener('DOMContentLoaded', function() {
        
        // ============================================
        // 1. CREATE HAMBURGER BUTTON
        // ============================================
        
        function createHamburgerButton() {
            // Check if button already exists
            if (document.querySelector('.mobile-menu-button')) {
                return;
            }

            const button = document.createElement('button');
            button.className = 'mobile-menu-button';
            button.setAttribute('aria-label', 'Toggle mobile menu');
            button.innerHTML = `
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
            `;
            
            document.body.appendChild(button);
            return button;
        }

        // ============================================
        // 2. CREATE MOBILE OVERLAY
        // ============================================
        
        function createOverlay() {
            // Check if overlay already exists
            if (document.querySelector('.mobile-overlay')) {
                return;
            }

            const overlay = document.createElement('div');
            overlay.className = 'mobile-overlay';
            overlay.setAttribute('aria-hidden', 'true');
            
            document.body.appendChild(overlay);
            return overlay;
        }

        // ============================================
        // 3. TOGGLE MOBILE MENU
        // ============================================
        
        function toggleMobileMenu() {
            const sidebar = document.querySelector('aside');
            const overlay = document.querySelector('.mobile-overlay');
            const button = document.querySelector('.mobile-menu-button');
            
            if (!sidebar) return;

            // Toggle active class
            sidebar.classList.toggle('mobile-menu-active');
            
            if (overlay) {
                overlay.classList.toggle('active');
            }

            // Update button icon
            if (button) {
                const isOpen = sidebar.classList.contains('mobile-menu-active');
                button.innerHTML = isOpen ? `
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                ` : `
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                `;
            }

            // Prevent body scroll when menu is open
            if (sidebar.classList.contains('mobile-menu-active')) {
                document.body.style.overflow = 'hidden';
            } else {
                document.body.style.overflow = '';
            }
        }

        // ============================================
        // 4. CLOSE MENU ON OUTSIDE CLICK
        // ============================================
        
        function closeMobileMenu() {
            const sidebar = document.querySelector('aside');
            const overlay = document.querySelector('.mobile-overlay');
            const button = document.querySelector('.mobile-menu-button');
            
            if (!sidebar) return;

            sidebar.classList.remove('mobile-menu-active');
            
            if (overlay) {
                overlay.classList.remove('active');
            }

            if (button) {
                button.innerHTML = `
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                `;
            }

            document.body.style.overflow = '';
        }

        // ============================================
        // 5. INITIALIZE MOBILE MENU
        // ============================================
        
        function initializeMobileMenu() {
            const hamburger = createHamburgerButton();
            const overlay = createOverlay();

            // Hamburger button click
            if (hamburger) {
                hamburger.addEventListener('click', toggleMobileMenu);
            }

            // Overlay click to close
            if (overlay) {
                overlay.addEventListener('click', closeMobileMenu);
            }

            // Close menu when clicking nav links (on mobile)
            const navLinks = document.querySelectorAll('aside nav a');
            navLinks.forEach(link => {
                link.addEventListener('click', function() {
                    if (window.innerWidth <= 768) {
                        closeMobileMenu();
                    }
                });
            });

            // Close on ESC key
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') {
                    closeMobileMenu();
                }
            });

            // Handle window resize
            let resizeTimer;
            window.addEventListener('resize', function() {
                clearTimeout(resizeTimer);
                resizeTimer = setTimeout(function() {
                    if (window.innerWidth > 768) {
                        closeMobileMenu();
                    }
                }, 250);
            });
        }

        // ============================================
        // 6. SMOOTH SCROLLING
        // ============================================
        
        function initializeSmoothScrolling() {
            document.querySelectorAll('a[href^="#"]').forEach(anchor => {
                anchor.addEventListener('click', function(e) {
                    const href = this.getAttribute('href');
                    if (href !== '#') {
                        e.preventDefault();
                        const target = document.querySelector(href);
                        if (target) {
                            target.scrollIntoView({
                                behavior: 'smooth',
                                block: 'start'
                            });
                        }
                    }
                });
            });
        }

        // ============================================
        // 7. ENHANCED TABLE RESPONSIVENESS
        // ============================================
        
        function makeTablesResponsive() {
            const tables = document.querySelectorAll('table');
            
            tables.forEach(table => {
                if (!table.parentElement.classList.contains('overflow-x-auto')) {
                    const wrapper = document.createElement('div');
                    wrapper.className = 'overflow-x-auto';
                    table.parentNode.insertBefore(wrapper, table);
                    wrapper.appendChild(table);
                }
            });
        }

        // ============================================
        // 8. LOADING STATES
        // ============================================
        
        function initializeLoadingStates() {
            const forms = document.querySelectorAll('form');
            
            forms.forEach(form => {
                form.addEventListener('submit', function(e) {
                    const submitButton = this.querySelector('button[type="submit"]');
                    if (submitButton && !submitButton.disabled) {
                        submitButton.disabled = true;
                        submitButton.innerHTML = `
                            <svg class="animate-spin h-5 w-5 mx-auto" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                        `;
                        
                        // Re-enable after 3 seconds (in case of client-side validation failure)
                        setTimeout(() => {
                            submitButton.disabled = false;
                            submitButton.innerHTML = submitButton.getAttribute('data-original-text') || 'Submit';
                        }, 3000);
                    }
                });
            });
        }

        // ============================================
        // 9. NOTIFICATION AUTO-DISMISS
        // ============================================
        
        function initializeNotifications() {
            const alerts = document.querySelectorAll('[class*="bg-green-50"], [class*="bg-red-50"], [class*="bg-yellow-50"]');
            
            alerts.forEach(alert => {
                // Add close button if not exists
                if (!alert.querySelector('.close-alert')) {
                    const closeBtn = document.createElement('button');
                    closeBtn.className = 'close-alert ml-auto text-gray-500 hover:text-gray-700';
                    closeBtn.innerHTML = '×';
                    closeBtn.style.fontSize = '1.5rem';
                    closeBtn.setAttribute('aria-label', 'Close notification');
                    
                    closeBtn.addEventListener('click', function() {
                        alert.style.opacity = '0';
                        setTimeout(() => alert.remove(), 300);
                    });
                    
                    alert.querySelector('.flex')?.appendChild(closeBtn);
                }

                // Auto-dismiss after 5 seconds
                setTimeout(() => {
                    alert.style.transition = 'opacity 0.3s ease';
                    alert.style.opacity = '0';
                    setTimeout(() => alert.remove(), 300);
                }, 5000);
            });
        }

        // ============================================
        // 10. DROPDOWN MENUS (if needed)
        // ============================================
        
        function initializeDropdowns() {
            const dropdownToggles = document.querySelectorAll('[data-dropdown-toggle]');
            
            dropdownToggles.forEach(toggle => {
                toggle.addEventListener('click', function(e) {
                    e.preventDefault();
                    const targetId = this.getAttribute('data-dropdown-toggle');
                    const dropdown = document.getElementById(targetId);
                    
                    if (dropdown) {
                        dropdown.classList.toggle('hidden');
                    }
                });
            });

            // Close dropdowns on outside click
            document.addEventListener('click', function(e) {
                if (!e.target.closest('[data-dropdown-toggle]')) {
                    document.querySelectorAll('[id$="-dropdown"]').forEach(dropdown => {
                        dropdown.classList.add('hidden');
                    });
                }
            });
        }

        // ============================================
        // INITIALIZE ALL FEATURES
        // ============================================
        
        console.log('🚀 FarmVax Mobile Features Initializing...');
        
        initializeMobileMenu();
        initializeSmoothScrolling();
        makeTablesResponsive();
        initializeLoadingStates();
        initializeNotifications();
        initializeDropdowns();
        
        console.log('✅ FarmVax Mobile Features Ready!');
        
        // Expose toggle function globally for manual use if needed
        window.toggleFarmVaxMenu = toggleMobileMenu;
        window.closeFarmVaxMenu = closeMobileMenu;
    });

})();