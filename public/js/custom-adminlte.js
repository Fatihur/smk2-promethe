/**
 * Custom JavaScript for SMK2 PROMETHEE AdminLTE
 */

$(document).ready(function() {
    
    // Initialize tooltips
    $('[data-toggle="tooltip"]').tooltip();
    
    // Initialize popovers
    $('[data-toggle="popover"]').popover();
    
    // Auto-hide alerts after 5 seconds
    $('.alert').not('.alert-permanent').delay(5000).fadeOut('slow');
    
    // Confirm delete actions
    $('.btn-delete, .delete-confirm').on('click', function(e) {
        e.preventDefault();
        const form = $(this).closest('form');
        const message = $(this).data('message') || 'Yakin ingin menghapus data ini?';
        
        if (confirm(message)) {
            form.submit();
        }
    });
    
    // Loading state for buttons
    $('.btn-loading').on('click', function() {
        const btn = $(this);
        const originalText = btn.html();
        
        btn.html('<span class="loading-spinner"></span> Loading...')
           .prop('disabled', true);
        
        // Re-enable after 10 seconds (fallback)
        setTimeout(function() {
            btn.html(originalText).prop('disabled', false);
        }, 10000);
    });
    
    // Auto-refresh functionality
    if (typeof autoRefreshInterval !== 'undefined' && autoRefreshInterval > 0) {
        setInterval(function() {
            location.reload();
        }, autoRefreshInterval * 1000);
    }
    
    // Form validation enhancements
    $('form').on('submit', function() {
        const form = $(this);
        const submitBtn = form.find('button[type="submit"]');
        
        // Add loading state to submit button
        if (!submitBtn.hasClass('no-loading')) {
            const originalText = submitBtn.html();
            submitBtn.html('<span class="loading-spinner"></span> Processing...')
                     .prop('disabled', true);
        }
    });
    
    // Number input formatting
    $('.number-format').on('input', function() {
        let value = $(this).val().replace(/[^0-9.]/g, '');
        if (value.split('.').length > 2) {
            value = value.substring(0, value.lastIndexOf('.'));
        }
        $(this).val(value);
    });
    
    // Auto-uppercase for certain inputs
    $('.uppercase').on('input', function() {
        $(this).val($(this).val().toUpperCase());
    });
    
    // Auto-format phone numbers
    $('.phone-format').on('input', function() {
        let value = $(this).val().replace(/\D/g, '');
        if (value.length > 0) {
            if (value.length <= 4) {
                value = value;
            } else if (value.length <= 8) {
                value = value.substring(0, 4) + '-' + value.substring(4);
            } else {
                value = value.substring(0, 4) + '-' + value.substring(4, 8) + '-' + value.substring(8, 12);
            }
        }
        $(this).val(value);
    });
    
    // DataTables default configuration
    if ($.fn.DataTable) {
        $.extend(true, $.fn.dataTable.defaults, {
            "language": {
                "url": "//cdn.datatables.net/plug-ins/1.10.24/i18n/Indonesian.json"
            },
            "pageLength": 25,
            "responsive": true,
            "autoWidth": false,
            "order": [],
            "columnDefs": [
                { "orderable": false, "targets": "no-sort" }
            ]
        });
    }
    
    // Chart.js default configuration
    if (typeof Chart !== 'undefined') {
        Chart.defaults.font.family = "'Source Sans Pro', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif";
        Chart.defaults.color = '#495057';
        Chart.defaults.plugins.legend.labels.usePointStyle = true;
    }
    
    // Enhanced sidebar menu active state
    const currentUrl = window.location.href;
    const currentPath = window.location.pathname;

    $('.nav-sidebar .nav-link').each(function() {
        const linkHref = $(this).attr('href');
        const linkPath = new URL(linkHref, window.location.origin).pathname;

        // Exact match or path-based matching
        if (linkHref === currentUrl || linkPath === currentPath) {
            $(this).addClass('active');
            $(this).closest('.nav-treeview').show();
            $(this).closest('.has-treeview').addClass('menu-open');

            // Expand parent menu if in submenu
            $(this).parents('.nav-treeview').show();
            $(this).parents('.has-treeview').addClass('menu-open');
        }

        // Pattern-based matching for dynamic routes
        if ($(this).data('active-pattern')) {
            const patterns = $(this).data('active-pattern').split(',');
            patterns.forEach(pattern => {
                const regex = new RegExp(pattern.replace('*', '.*'));
                if (regex.test(currentPath)) {
                    $(this).addClass('active');
                    $(this).closest('.nav-treeview').show();
                    $(this).closest('.has-treeview').addClass('menu-open');
                }
            });
        }
    });

    // Menu collapse/expand functionality
    $('.nav-sidebar .has-treeview > .nav-link').on('click', function(e) {
        const $this = $(this);
        const $parent = $this.parent();
        const $treeview = $parent.find('.nav-treeview');

        if ($parent.hasClass('menu-open')) {
            $treeview.slideUp(300, function() {
                $parent.removeClass('menu-open');
            });
        } else {
            // Close other open menus
            $('.nav-sidebar .has-treeview.menu-open').each(function() {
                $(this).find('.nav-treeview').slideUp(300);
                $(this).removeClass('menu-open');
            });

            // Open this menu
            $treeview.slideDown(300, function() {
                $parent.addClass('menu-open');
            });
        }
    });
    
    // Search functionality enhancement
    $('.sidebar-search input').on('keyup', function() {
        const searchTerm = $(this).val().toLowerCase();
        $('.nav-sidebar .nav-item').each(function() {
            const text = $(this).text().toLowerCase();
            if (text.includes(searchTerm)) {
                $(this).show();
            } else {
                $(this).hide();
            }
        });
    });
    
    // Print functionality
    $('.btn-print').on('click', function(e) {
        e.preventDefault();
        window.print();
    });
    
    // Export functionality
    $('.btn-export').on('click', function() {
        const btn = $(this);
        const originalText = btn.html();
        
        btn.html('<i class="fas fa-spinner fa-spin"></i> Exporting...')
           .prop('disabled', true);
        
        // Re-enable after 5 seconds
        setTimeout(function() {
            btn.html(originalText).prop('disabled', false);
        }, 5000);
    });
    
    // Progress bar animation
    $('.progress-bar').each(function() {
        const progressBar = $(this);
        const width = progressBar.attr('aria-valuenow') + '%';
        progressBar.animate({ width: width }, 1000);
    });
    
    // Smooth scrolling for anchor links
    $('a[href^="#"]').on('click', function(e) {
        e.preventDefault();
        const target = $($(this).attr('href'));
        if (target.length) {
            $('html, body').animate({
                scrollTop: target.offset().top - 100
            }, 500);
        }
    });
    
    // Auto-resize textareas
    $('textarea.auto-resize').on('input', function() {
        this.style.height = 'auto';
        this.style.height = (this.scrollHeight) + 'px';
    });
    
    // Copy to clipboard functionality
    $('.btn-copy').on('click', function() {
        const target = $($(this).data('target'));
        const text = target.text() || target.val();
        
        navigator.clipboard.writeText(text).then(function() {
            // Show success message
            const btn = $('.btn-copy');
            const originalText = btn.html();
            btn.html('<i class="fas fa-check"></i> Copied!');
            
            setTimeout(function() {
                btn.html(originalText);
            }, 2000);
        });
    });
    
    // Dynamic form fields
    $('.add-field').on('click', function() {
        const template = $($(this).data('template'));
        const container = $($(this).data('container'));
        const newField = template.clone().removeClass('d-none');
        container.append(newField);
    });
    
    $(document).on('click', '.remove-field', function() {
        $(this).closest('.dynamic-field').remove();
    });
    
    // File upload preview
    $('input[type="file"]').on('change', function() {
        const file = this.files[0];
        const preview = $($(this).data('preview'));
        
        if (file && preview.length) {
            const reader = new FileReader();
            reader.onload = function(e) {
                preview.attr('src', e.target.result).show();
            };
            reader.readAsDataURL(file);
        }
    });
    
    // Keyboard shortcuts
    $(document).on('keydown', function(e) {
        // Ctrl+S to save
        if (e.ctrlKey && e.which === 83) {
            e.preventDefault();
            $('form button[type="submit"]').first().click();
        }
        
        // Escape to close modals
        if (e.which === 27) {
            $('.modal').modal('hide');
        }
    });
    
    // Initialize custom components
    initializeCustomComponents();
});

/**
 * Initialize custom components
 */
function initializeCustomComponents() {
    // Custom select2 initialization
    if ($.fn.select2) {
        $('.select2').select2({
            theme: 'bootstrap4',
            width: '100%'
        });
    }
    
    // Custom date picker initialization
    if ($.fn.datepicker) {
        $('.datepicker').datepicker({
            format: 'dd/mm/yyyy',
            autoclose: true,
            todayHighlight: true
        });
    }
    
    // Custom time picker initialization
    if ($.fn.timepicker) {
        $('.timepicker').timepicker({
            showInputs: false,
            format: 'HH:ii'
        });
    }
}

/**
 * Show loading overlay
 */
function showLoading() {
    if ($('#loading-overlay').length === 0) {
        $('body').append(`
            <div id="loading-overlay" style="
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background: rgba(0,0,0,0.5);
                z-index: 9999;
                display: flex;
                align-items: center;
                justify-content: center;
            ">
                <div style="
                    background: white;
                    padding: 20px;
                    border-radius: 5px;
                    text-align: center;
                ">
                    <div class="loading-spinner" style="margin-bottom: 10px;"></div>
                    <div>Loading...</div>
                </div>
            </div>
        `);
    }
}

/**
 * Hide loading overlay
 */
function hideLoading() {
    $('#loading-overlay').remove();
}

/**
 * Show notification
 */
function showNotification(message, type = 'info') {
    const alertClass = `alert-${type}`;
    const notification = $(`
        <div class="alert ${alertClass} alert-dismissible fade show" role="alert" style="
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 1050;
            min-width: 300px;
        ">
            ${message}
            <button type="button" class="close" data-dismiss="alert">
                <span>&times;</span>
            </button>
        </div>
    `);
    
    $('body').append(notification);
    
    // Auto-hide after 5 seconds
    setTimeout(function() {
        notification.fadeOut('slow', function() {
            $(this).remove();
        });
    }, 5000);
}

/**
 * Format number with thousands separator
 */
function formatNumber(num) {
    return num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
}

/**
 * Format currency
 */
function formatCurrency(amount) {
    return 'Rp ' + formatNumber(amount);
}

/**
 * Validate form before submit
 */
function validateForm(formSelector) {
    const form = $(formSelector);
    let isValid = true;
    
    form.find('input[required], select[required], textarea[required]').each(function() {
        if (!$(this).val()) {
            $(this).addClass('is-invalid');
            isValid = false;
        } else {
            $(this).removeClass('is-invalid');
        }
    });
    
    return isValid;
}
