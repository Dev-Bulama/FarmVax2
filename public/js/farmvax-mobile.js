/* ============================================
   FARMVAX MOBILE MENU - FIXED VERSION
   ============================================
   Fixes:
   - Sidebar not appearing on mobile
   - Wrong icon showing
   - Navigation not accessible
   ============================================ */

(function() {
    'use strict';

    document.addEventListener('DOMContentLoaded', function() {
        
        console.log('🚀 FarmVax Mobile Menu Initializing...');

        // ============================================
        // 1. CREATE HAMBURGER BUTTON
        // ============================================
        
        function createHamburgerButton() {
            // Remove existing button if any
            const existingButton = document.querySelector('.mobile-menu-button');
            if (existingButton) {
                existingButton.remove();
            }

            const button = document.createElement('button');
            button.className = 'mobile-menu-button';
            button.setAttribute('aria-label', 'Toggle mobile menu');
            button.setAttribute('type', 'button');
            
            // HAMBURGER ICON (3 lines)
            button.innerHTML = `
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
            `;
            
            document.body.appendChild(button);
            console.log('✅ Hamburger button created');
            return button;
        }

        // ============================================
        // 2. CREATE OVERLAY
        // ============================================
        
        function createOverlay() {
            const existingOverlay = document.querySelector('.mobile-overlay');
            if (existingOverlay) {
                existingOverlay.remove();
            }

            const overlay = document.createElement('div');
            overlay.className = 'mobile-overlay';
            overlay.setAttribute('aria-hidden', 'true');
            
            document.body.appendChild(overlay);
            console.log('✅ Overlay created');
            return overlay;
        }

        // ============================================
        // 3. FIX SIDEBAR CLASSES
        // ============================================
        
        function fixSidebarClasses() {
            const sidebar = document.querySelector('aside');
            
            if (!sidebar) {
                console.error('❌ Sidebar not found!');
                return null;
            }

            // Make sure sidebar has correct classes for mobile
            sidebar.classList.add('mobile-sidebar');
            
            // Ensure it's hidden on mobile by default
            if (window.innerWidth <= 768) {
                sidebar.classList.remove('mobile-menu-active');
            }
            
            console.log('✅ Sidebar classes fixed');
            return sidebar;
        }

        // ============================================
        // 4. TOGGLE MENU
        // ============================================
        
        function toggleMobileMenu() {
            const sidebar = document.querySelector('aside');
            const overlay = document.querySelector('.mobile-overlay');
            const button = document.querySelector('.mobile-menu-button');
            
            if (!sidebar) {
                console.error('❌ Cannot toggle: Sidebar not found');
                return;
            }

            const isOpen = sidebar.classList.contains('mobile-menu-active');
            
            if (isOpen) {
                // CLOSE MENU
                sidebar.classList.remove('mobile-menu-active');
                overlay?.classList.remove('active');
                document.body.style.overflow = '';
                
                // Change to HAMBURGER icon
                if (button) {
                    button.innerHTML = `
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    `;
                }
                console.log('📱 Menu closed');
            } else {
                // OPEN MENU
                sidebar.classList.add('mobile-menu-active');
                overlay?.classList.add('active');
                document.body.style.overflow = 'hidden';
                
                // Change to X icon
                if (button) {
                    button.innerHTML = `
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    `;
                }
                console.log('📱 Menu opened');
            }
        }

        // ============================================
        // 5. CLOSE MENU
        // ============================================
        
        function closeMobileMenu() {
            const sidebar = document.querySelector('aside');
            const overlay = document.querySelector('.mobile-overlay');
            const button = document.querySelector('.mobile-menu-button');
            
            if (!sidebar) return;

            sidebar.classList.remove('mobile-menu-active');
            overlay?.classList.remove('active');
            document.body.style.overflow = '';
            
            // Reset to HAMBURGER icon
            if (button) {
                button.innerHTML = `
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                `;
            }
            
            console.log('📱 Menu closed');
        }

        // ============================================
        // 6. INITIALIZE
        // ============================================
        
        function initializeMobileMenu() {
            // Create elements
            const hamburger = createHamburgerButton();
            const overlay = createOverlay();
            const sidebar = fixSidebarClasses();

            if (!sidebar) {
                console.error('❌ Mobile menu initialization failed: No sidebar');
                return;
            }

            // Hamburger click
            if (hamburger) {
                hamburger.addEventListener('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    toggleMobileMenu();
                });
            }

            // Overlay click
            if (overlay) {
                overlay.addEventListener('click', function(e) {
                    e.preventDefault();
                    closeMobileMenu();
                });
            }

            // Close on nav link click
            const navLinks = sidebar.querySelectorAll('a');
            navLinks.forEach(link => {
                link.addEventListener('click', function() {
                    if (window.innerWidth <= 768) {
                        setTimeout(closeMobileMenu, 300);
                    }
                });
            });

            // ESC key
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') {
                    closeMobileMenu();
                }
            });

            // Window resize
            let resizeTimer;
            window.addEventListener('resize', function() {
                clearTimeout(resizeTimer);
                resizeTimer = setTimeout(function() {
                    if (window.innerWidth > 768) {
                        closeMobileMenu();
                    }
                }, 250);
            });

            console.log('✅ Mobile menu initialized successfully!');
        }

        // ============================================
        // 7. AUTO-DISMISS NOTIFICATIONS
        // ============================================
        
        function initializeNotifications() {
            const notifications = document.querySelectorAll('[class*="bg-green-50"], [class*="bg-red-50"], [class*="bg-yellow-50"]');
            
            notifications.forEach(notification => {
                // Add close button
                if (!notification.querySelector('.notification-close')) {
                    const closeBtn = document.createElement('button');
                    closeBtn.className = 'notification-close ml-auto';
                    closeBtn.innerHTML = '×';
                    closeBtn.style.cssText = 'font-size: 1.5rem; color: #6b7280; cursor: pointer;';
                    closeBtn.setAttribute('aria-label', 'Close notification');
                    
                    closeBtn.addEventListener('click', function() {
                        notification.style.transition = 'opacity 0.3s ease';
                        notification.style.opacity = '0';
                        setTimeout(() => notification.remove(), 300);
                    });
                    
                    const flexDiv = notification.querySelector('.flex');
                    if (flexDiv) {
                        flexDiv.appendChild(closeBtn);
                    }
                }

                // Auto-dismiss after 5 seconds
                setTimeout(() => {
                    notification.style.transition = 'opacity 0.3s ease';
                    notification.style.opacity = '0';
                    setTimeout(() => notification.remove(), 300);
                }, 5000);
            });
        }

        // ============================================
        // 8. FORM LOADING STATES
        // ============================================
        
        function initializeLoadingStates() {
            const forms = document.querySelectorAll('form');
            
            forms.forEach(form => {
                form.addEventListener('submit', function(e) {
                    const submitButton = this.querySelector('button[type="submit"]');
                    if (submitButton && !submitButton.disabled) {
                        const originalText = submitButton.innerHTML;
                        submitButton.setAttribute('data-original-text', originalText);
                        submitButton.disabled = true;
                        submitButton.innerHTML = `
                            <svg class="animate-spin h-5 w-5 mx-auto" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                        `;
                    }
                });
            });
        }

        // ============================================
        // 9. RESPONSIVE TABLES
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
        // START EVERYTHING
        // ============================================
        
        // Small delay to ensure DOM is fully ready
        setTimeout(function() {
            initializeMobileMenu();
            initializeNotifications();
            initializeLoadingStates();
            makeTablesResponsive();
            
            console.log('🎉 FarmVax Mobile Features Ready!');
        }, 100);

        // Global functions
        window.toggleFarmVaxMenu = toggleMobileMenu;
        window.closeFarmVaxMenu = closeMobileMenu;
    });

})();