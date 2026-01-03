/**
 * GreenCart - Enhanced JavaScript
 * Adds interactivity, animations, and user experience improvements
 */

// Wait for DOM to be fully loaded
document.addEventListener('DOMContentLoaded', function() {
    
    // ========== Smooth Scroll Animation ==========
    // Add fade-in animation to elements when they come into view
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    };
    
    const observer = new IntersectionObserver(function(entries) {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.style.opacity = '1';
                entry.target.style.transform = 'translateY(0)';
            }
        });
    }, observerOptions);
    
    // Observe all product cards for scroll animation
    const productCards = document.querySelectorAll('.product-card');
    productCards.forEach(card => {
        card.style.opacity = '0';
        card.style.transform = 'translateY(20px)';
        card.style.transition = 'opacity 0.6s ease-out, transform 0.6s ease-out';
        observer.observe(card);
    });
    
    // ========== Form Validation Enhancement & Loading States ==========
    // Add real-time validation feedback and loading states to forms
    const allForms = document.querySelectorAll('form');
    
    allForms.forEach(form => {
        // Add input validation to all required inputs
        const inputs = form.querySelectorAll('input[required]');
        
        inputs.forEach(input => {
            // Add validation on blur
            input.addEventListener('blur', function() {
                validateInput(this);
            });
            
            // Remove error state on input
            input.addEventListener('input', function() {
                if (this.classList.contains('is-invalid')) {
                    this.classList.remove('is-invalid');
                }
            });
        });
        
        // REMOVED: Form submission handler to ensure forms work without interference
        // Forms will submit naturally without JavaScript blocking them
        // If you want loading states, add them server-side or use a different approach
    });
    
    // ========== Input Validation Function ==========
    function validateInput(input) {
        const value = input.value.trim();
        const type = input.type;
        
        // Remove previous validation classes
        input.classList.remove('is-valid', 'is-invalid');
        
        // Skip validation if field is empty (required attribute handles this)
        if (!value) return;
        
        // Email validation
        if (type === 'email') {
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (emailRegex.test(value)) {
                input.classList.add('is-valid');
            } else {
                input.classList.add('is-invalid');
            }
        }
        
        // Password validation
        if (type === 'password' && value.length < 6) {
            input.classList.add('is-invalid');
        } else if (type === 'password' && value.length >= 6) {
            input.classList.add('is-valid');
        }
    }
    
    // ========== Cart Badge Animation ==========
    // Animate cart badge when it updates
    const cartBadge = document.querySelector('.badge.rounded-pill');
    if (cartBadge) {
        cartBadge.style.transition = 'transform 0.3s ease';
        cartBadge.addEventListener('animationend', function() {
            this.style.animation = '';
        });
    }
    
    // ========== Product Card Hover Effects ==========
    // Enhanced hover effects for product cards
    productCards.forEach(card => {
        card.addEventListener('mouseenter', function() {
            this.style.transition = 'all 0.3s ease';
        });
    });
    
    // ========== Alert Auto-dismiss ==========
    // Auto-dismiss success alerts after 5 seconds
    const successAlerts = document.querySelectorAll('.alert-success');
    successAlerts.forEach(alert => {
        setTimeout(() => {
            const bsAlert = new bootstrap.Alert(alert);
            bsAlert.close();
        }, 5000);
    });
    
    // ========== Smooth Scroll for Anchor Links ==========
    // Smooth scrolling for internal links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function(e) {
            const href = this.getAttribute('href');
            if (href !== '#' && href.length > 1) {
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
    
    // ========== Navbar Scroll Effect ==========
    // Add shadow effect when scrolling
    let lastScroll = 0;
    const navbar = document.querySelector('.navbar');
    
    window.addEventListener('scroll', function() {
        const currentScroll = window.pageYOffset;
        
        if (currentScroll > 50) {
            navbar.style.boxShadow = '0 4px 6px -1px rgba(0, 0, 0, 0.1)';
        } else {
            navbar.style.boxShadow = '0 1px 2px 0 rgba(0, 0, 0, 0.05)';
        }
        
        lastScroll = currentScroll;
    });
    
    // ========== Price Formatting ==========
    // Format price displays with better styling
    const priceElements = document.querySelectorAll('.text-success');
    priceElements.forEach(element => {
        if (element.textContent.includes('$')) {
            element.style.fontWeight = '700';
        }
    });
    
    // ========== Image Lazy Loading Enhancement ==========
    // Add loading animation for images
    const images = document.querySelectorAll('img');
    images.forEach(img => {
        img.addEventListener('load', function() {
            this.style.opacity = '1';
        });
        
        // Set initial opacity for fade-in effect
        if (!img.complete) {
            img.style.opacity = '0';
            img.style.transition = 'opacity 0.3s ease';
        }
    });
    
    // ========== Mobile Menu Enhancement ==========
    // Close mobile menu when clicking outside
    const navbarToggler = document.querySelector('.navbar-toggler');
    const navbarCollapse = document.querySelector('.navbar-collapse');
    
    if (navbarToggler && navbarCollapse) {
        document.addEventListener('click', function(e) {
            const isClickInsideNav = navbarCollapse.contains(e.target) || navbarToggler.contains(e.target);
            
            if (!isClickInsideNav && navbarCollapse.classList.contains('show')) {
                const bsCollapse = bootstrap.Collapse.getInstance(navbarCollapse);
                if (bsCollapse) {
                    bsCollapse.hide();
                }
            }
        });
    }
    
    // ========== Console Log (Development) ==========
    console.log('🌿 GreenCart - Enhanced JavaScript Loaded Successfully!');
    
});

// ========== Utility Functions ==========

/**
 * Show toast notification (if needed in future)
 */
function showToast(message, type = 'success') {
    // This can be implemented with Bootstrap toast component
    console.log(`${type.toUpperCase()}: ${message}`);
}

/**
 * Format currency
 */
function formatCurrency(amount) {
    return new Intl.NumberFormat('en-US', {
        style: 'currency',
        currency: 'USD'
    }).format(amount);
}

