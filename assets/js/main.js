/**
 * GreenCart - Enhanced JavaScript
 * Adds interactivity, animations, and user experience improvements
 */

console.log('DEBUG: main.js file loaded');

// Global error handler to catch JavaScript errors
window.addEventListener('error', function(e) {
    console.error('DEBUG: JavaScript error caught:', e.message, e.filename, e.lineno);
    // #region agent log
    fetch('http://127.0.0.1:7242/ingest/4e026db6-a57e-47e2-916c-8f237c3e564e',{method:'POST',headers:{'Content-Type':'application/json'},body:JSON.stringify({location:'main.js:11',message:'JavaScript error',data:{message:e.message,filename:e.filename,lineno:e.lineno,error:e.error?.toString()},timestamp:Date.now(),sessionId:'debug-session',runId:'run1',hypothesisId:'B'})}).catch(()=>{});
    // #endregion
});

// Wait for DOM to be fully loaded
document.addEventListener('DOMContentLoaded', function() {
    // #region agent log
    fetch('http://127.0.0.1:7242/ingest/4e026db6-a57e-47e2-916c-8f237c3e564e',{method:'POST',headers:{'Content-Type':'application/json'},body:JSON.stringify({location:'main.js:7',message:'DOMContentLoaded fired',data:{readyState:document.readyState},timestamp:Date.now(),sessionId:'debug-session',runId:'run1',hypothesisId:'E'})}).catch(()=>{});
    // #endregion
    
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
    // #region agent log
    fetch('http://127.0.0.1:7242/ingest/4e026db6-a57e-47e2-916c-8f237c3e564e',{method:'POST',headers:{'Content-Type':'application/json'},body:JSON.stringify({location:'main.js:36',message:'Forms found',data:{formCount:allForms.length},timestamp:Date.now(),sessionId:'debug-session',runId:'run1',hypothesisId:'C'})}).catch(()=>{});
    // #endregion
    
    allForms.forEach(form => {
        // #region agent log
        const formAction = form.getAttribute('action') || form.action || 'current';
        const formMethod = form.getAttribute('method') || form.method || 'GET';
        fetch('http://127.0.0.1:7242/ingest/4e026db6-a57e-47e2-916c-8f237c3e564e',{method:'POST',headers:{'Content-Type':'application/json'},body:JSON.stringify({location:'main.js:40',message:'Form found',data:{action:formAction,method:formMethod,id:form.id||'none'},timestamp:Date.now(),sessionId:'debug-session',runId:'run1',hypothesisId:'C'})}).catch(()=>{});
        // #endregion
        
        // Add submit event listener to log form submissions - DO NOT PREVENT DEFAULT
        form.addEventListener('submit', function(e) {
            // #region agent log
            const submitButton = this.querySelector('button[type="submit"]');
            const buttonTextBefore = submitButton ? submitButton.innerHTML : 'unknown';
            const willPrevent = e.defaultPrevented;
            fetch('http://127.0.0.1:7242/ingest/4e026db6-a57e-47e2-916c-8f237c3e564e',{method:'POST',headers:{'Content-Type':'application/json'},body:JSON.stringify({location:'main.js:59',message:'Form submit event',data:{action:this.action||'current',method:this.method,defaultPrevented:willPrevent,valid:this.checkValidity(),buttonText:buttonTextBefore},timestamp:Date.now(),sessionId:'debug-session',runId:'run1',hypothesisId:'C'})}).catch(()=>{});
            // #endregion
            // CRITICAL: Do NOT call e.preventDefault() - allow form to submit naturally
            // If defaultPrevented is true, something else is blocking submission
        }, true); // Use capture phase to catch early
        
        // Also log button clicks to see if they're being intercepted
        const submitButton = form.querySelector('button[type="submit"]');
        if (submitButton) {
            submitButton.addEventListener('click', function(e) {
                // #region agent log
                const originalText = this.innerHTML;
                fetch('http://127.0.0.1:7242/ingest/4e026db6-a57e-47e2-916c-8f237c3e564e',{method:'POST',headers:{'Content-Type':'application/json'},body:JSON.stringify({location:'main.js:72',message:'Submit button clicked',data:{formId:this.form?.id||'none',formAction:this.form?.action||'current',buttonText:originalText},timestamp:Date.now(),sessionId:'debug-session',runId:'run1',hypothesisId:'C'})}).catch(()=>{});
                // #endregion
                // Do NOT prevent default - let the click proceed to submit the form
            });
            
            // Monitor for button text changes (to detect if something is adding "processing")
            const observer = new MutationObserver(function(mutations) {
                mutations.forEach(function(mutation) {
                    if (mutation.type === 'childList' || mutation.type === 'characterData') {
                        // #region agent log
                        fetch('http://127.0.0.1:7242/ingest/4e026db6-a57e-47e2-916c-8f237c3e564e',{method:'POST',headers:{'Content-Type':'application/json'},body:JSON.stringify({location:'main.js:82',message:'Button text changed',data:{newText:submitButton.innerHTML,oldText:mutation.oldValue},timestamp:Date.now(),sessionId:'debug-session',runId:'run1',hypothesisId:'C'})}).catch(()=>{});
                        // #endregion
                    }
                });
            });
            observer.observe(submitButton, { childList: true, characterData: true, subtree: true });
        }
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
            // #region agent log
            const href = this.getAttribute('href');
            fetch('http://127.0.0.1:7242/ingest/4e026db6-a57e-47e2-916c-8f237c3e564e',{method:'POST',headers:{'Content-Type':'application/json'},body:JSON.stringify({location:'main.js:121',message:'Anchor link clicked',data:{href:href,isHash:true},timestamp:Date.now(),sessionId:'debug-session',runId:'run1',hypothesisId:'A'})}).catch(()=>{});
            // #endregion
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
    
    // Log all link clicks to detect mission page navigation issues
    document.querySelectorAll('a[href*="mission"]').forEach(link => {
        link.addEventListener('click', function(e) {
            // #region agent log
            fetch('http://127.0.0.1:7242/ingest/4e026db6-a57e-47e2-916c-8f237c3e564e',{method:'POST',headers:{'Content-Type':'application/json'},body:JSON.stringify({location:'main.js:135',message:'Mission link clicked',data:{href:this.getAttribute('href'),defaultPrevented:false},timestamp:Date.now(),sessionId:'debug-session',runId:'run1',hypothesisId:'A'})}).catch(()=>{});
            // #endregion
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
    // Format price displays with better styling (INR)
    const priceElements = document.querySelectorAll('.text-success');
    priceElements.forEach(element => {
        if (element.textContent.includes('$') || element.textContent.includes('₹')) {
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
    
    // ========== Product Search, Filter & Sort ==========
    const searchInput = document.getElementById('searchInput');
    const filterSelect = document.getElementById('filterSelect');
    const sortSelect = document.getElementById('sortSelect');
    const productsContainer = document.getElementById('productsContainer');
    const noResults = document.getElementById('noResults');
    const productCount = document.getElementById('productCount');
    
    if (searchInput && filterSelect && sortSelect && productsContainer) {
        const productItems = Array.from(document.querySelectorAll('.product-item'));
        let filteredProducts = [...productItems];
        
        function updateProductDisplay() {
            const searchTerm = searchInput.value.toLowerCase().trim();
            const filterValue = filterSelect.value;
            const sortValue = sortSelect.value;
            
            // Filter products
            filteredProducts = productItems.filter(item => {
                const name = item.dataset.name || '';
                const description = item.dataset.description || '';
                const surplus = item.dataset.surplus === 'true';
                
                // Search filter
                const matchesSearch = !searchTerm || 
                    name.includes(searchTerm) || 
                    description.includes(searchTerm);
                
                // Category filter
                let matchesFilter = true;
                if (filterValue === 'surplus') {
                    matchesFilter = surplus;
                } else if (filterValue === 'regular') {
                    matchesFilter = !surplus;
                }
                
                return matchesSearch && matchesFilter;
            });
            
            // Sort products
            filteredProducts.sort((a, b) => {
                switch(sortValue) {
                    case 'price-low':
                        return parseFloat(a.dataset.price) - parseFloat(b.dataset.price);
                    case 'price-high':
                        return parseFloat(b.dataset.price) - parseFloat(a.dataset.price);
                    case 'name':
                        return a.dataset.name.localeCompare(b.dataset.name);
                    case 'newest':
                    default:
                        return parseInt(b.dataset.id) - parseInt(a.dataset.id);
                }
            });
            
            // Hide all products first
            productItems.forEach(item => {
                item.style.display = 'none';
            });
            
            // Show filtered products
            if (filteredProducts.length > 0) {
                filteredProducts.forEach((item, index) => {
                    item.style.display = 'block';
                    // Add animation delay
                    setTimeout(() => {
                        item.style.opacity = '0';
                        item.style.transform = 'translateY(20px)';
                        setTimeout(() => {
                            item.style.transition = 'opacity 0.4s ease, transform 0.4s ease';
                            item.style.opacity = '1';
                            item.style.transform = 'translateY(0)';
                        }, 50);
                    }, index * 50);
                });
                productsContainer.style.display = 'flex';
                noResults.style.display = 'none';
            } else {
                productsContainer.style.display = 'none';
                noResults.style.display = 'block';
            }
            
            // Update product count
            if (productCount) {
                productCount.textContent = `${filteredProducts.length} product${filteredProducts.length !== 1 ? 's' : ''} found`;
            }
        }
        
        // Event listeners
        searchInput.addEventListener('input', updateProductDisplay);
        filterSelect.addEventListener('change', updateProductDisplay);
        sortSelect.addEventListener('change', updateProductDisplay);
        
        // Initial count
        if (productCount) {
            productCount.textContent = `${productItems.length} product${productItems.length !== 1 ? 's' : ''} available`;
        }
    }
    
    // ========== Back to Top Button ==========
    const backToTopBtn = document.getElementById('backToTop');
    if (backToTopBtn) {
        window.addEventListener('scroll', function() {
            if (window.pageYOffset > 300) {
                backToTopBtn.classList.add('show');
            } else {
                backToTopBtn.classList.remove('show');
            }
        });
        
        backToTopBtn.addEventListener('click', function() {
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        });
    }
    
    // ========== Enhanced Add to Cart Animation ==========
    const addToCartForms = document.querySelectorAll('.add-to-cart-form');
    addToCartForms.forEach(form => {
        form.addEventListener('submit', function(e) {
            const button = this.querySelector('button[type="submit"]');
            if (button) {
                const originalText = button.innerHTML;
                button.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span>Adding...';
                button.disabled = true;
                
                // Re-enable after a delay (form will submit naturally)
                setTimeout(() => {
                    button.innerHTML = originalText;
                    button.disabled = false;
                }, 2000);
            }
        });
    });
    
    // ========== Password Visibility Toggle ==========
    document.querySelectorAll('.password-toggle').forEach(btn => {
        btn.addEventListener('click', function() {
            const id = this.dataset.controls;
            const input = document.getElementById(id);
            if (!input) return;
            const icon = this.querySelector('i');
            if (input.type === 'password') {
                input.type = 'text';
                if (icon) { icon.classList.remove('bi-eye'); icon.classList.add('bi-eye-slash'); }
            } else {
                input.type = 'password';
                if (icon) { icon.classList.remove('bi-eye-slash'); icon.classList.add('bi-eye'); }
            }
        });
    });

    // ========== Dropdown Fallback Initialization ==========
    // Ensure dropdowns work even if native bootstrap attach has issues
    document.querySelectorAll('[data-bs-toggle="dropdown"]').forEach(btn => {
        btn.addEventListener('click', function(e) {
            try {
                // Use Bootstrap's Dropdown API to toggle
                const dd = bootstrap.Dropdown.getOrCreateInstance(this);
                dd.toggle();
            } catch (err) {
                // As a graceful fallback, toggle the show class
                const menu = this.nextElementSibling;
                if (menu && menu.classList.contains('dropdown-menu')) {
                    menu.classList.toggle('show');
                }
            }
        });
    });

    // ========== Console Log (Development) ==========
    console.log('🌿 GreenCart - Enhanced JavaScript Loaded Successfully!');
    // #region agent log
    // Test if fetch is working by trying a simple log first
    try {
        fetch('http://127.0.0.1:7242/ingest/4e026db6-a57e-47e2-916c-8f237c3e564e',{method:'POST',headers:{'Content-Type':'application/json'},body:JSON.stringify({location:'main.js:196',message:'JS initialization complete',data:{bootstrapAvailable:typeof bootstrap!=='undefined',formsFound:document.querySelectorAll('form').length,userAgent:navigator.userAgent},timestamp:Date.now(),sessionId:'debug-session',runId:'run1',hypothesisId:'E'})}).catch(err=>console.error('Log fetch failed:',err));
    } catch(e) {
        console.error('Logging error:', e);
    }
    // #endregion
    
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
    return new Intl.NumberFormat('en-IN', {
        style: 'currency',
        currency: 'INR'
    }).format(amount);
}

