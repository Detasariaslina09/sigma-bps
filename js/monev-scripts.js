/* 
 * JavaScript Terpusat untuk Semua Halaman Monev
 * File: monev-scripts.js
 * Deskripsi: Script universal untuk halaman monitoring dan evaluasi tim BPS
 */

$(document).ready(function() {
    // Informasi halaman dari URL
    const currentPage = window.location.pathname.split('/').pop();
    const pageNames = {
        'monev-umum.php': 'Tim Umum',
        'monev-sosial.php': 'Tim Sosial', 
        'monev-sektoral.php': 'Tim Statistik Sektoral',
        'monev-ptid.php': 'Tim PTID',
        'monev-produksi.php': 'Tim Produksi',
        'monev-nerwilis.php': 'Tim Nerwilis',
        'monev-distribusi.php': 'Tim Distribusi'
    };
    
    const currentPageName = pageNames[currentPage] || 'Monev';
    
    // Log halaman yang sedang dimuat
    console.log(`%cHalaman Monev ${currentPageName} dimuat dalam mode full screen`, 
                'color: #ff9800; font-weight: bold; font-size: 14px;');
    
    // Tambahkan animasi fade-in ke container utama
    $('.service-category-container').addClass('fade-in');
    
    // Smooth scroll untuk tombol kembali
    $('.btn-back').on('click', function(e) {
        showLoadingOverlay();
        // Biarkan link normal bekerja, loading overlay akan hilang saat halaman baru dimuat
    });
    
    // Smooth scroll untuk link aplikasi
    $('.service-links-list a').on('click', function(e) {
        const link = $(this);
        
        // Tambahkan efek visual saat link diklik
        link.css('transform', 'scale(0.95)');
        setTimeout(() => {
            link.css('transform', 'scale(1)');
        }, 150);
        
        console.log(`Membuka aplikasi: ${link.text().trim()}`);
    });
    
    // Hover effect untuk service items
    $('.service-category-item').hover(
        function() {
            $(this).find('h3').css('color', '#f57c00');
        },
        function() {
            $(this).find('h3').css('color', '#ff9800');
        }
    );
    
    // Lazy loading untuk gambar (jika ada)
    if ('IntersectionObserver' in window) {
        const imageObserver = new IntersectionObserver((entries, observer) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const img = entry.target;
                    img.src = img.dataset.src;
                    img.classList.remove('lazy');
                    imageObserver.unobserve(img);
                }
            });
        });
        
        document.querySelectorAll('img[data-src]').forEach(img => {
            imageObserver.observe(img);
        });
    }
    
    // Keyboard navigation support
    $(document).on('keydown', function(e) {
        // ESC key untuk kembali
        if (e.keyCode === 27) {
            window.location.href = '../monev.php';
        }
    });
    
    // Show/hide scroll to top button dengan animasi
    $(window).scroll(function() {
        const scrollTop = $(this).scrollTop();
        const scrollButton = $('.scrollup');
        
        if (scrollTop > 200) {
            scrollButton.fadeIn(300);
        } else {
            scrollButton.fadeOut(300);
        }
    });
    
    // Smooth scroll to top
    $('.scrollup').on('click', function(e) {
        e.preventDefault();
        $('html, body').animate({
            scrollTop: 0
        }, 600, 'easeInOutQuart');
    });
    
    // Performance monitoring
    if (window.performance) {
        const loadTime = window.performance.timing.loadEventEnd - window.performance.timing.navigationStart;
        console.log(`%cHalaman dimuat dalam ${loadTime}ms`, 'color: #4CAF50; font-weight: bold;');
    }
    
    // Error handling untuk link yang rusak
    $('.service-links-list a').on('error', function() {
        console.warn('Link mungkin tidak tersedia:', $(this).attr('href'));
        $(this).css('opacity', '0.6').append(' <small>(Link tidak tersedia)</small>');
    });
    
    // Auto-refresh data (opsional, untuk monitoring real-time)
    const autoRefresh = false; // Set true jika diperlukan
    if (autoRefresh) {
        setInterval(() => {
            console.log('Auto-refresh data monitoring...');
            // Implementasi refresh data jika diperlukan
        }, 300000); // 5 menit
    }
});

// Fungsi utilitas
function showLoadingOverlay() {
    if ($('.loading-overlay').length === 0) {
        $('body').append(`
            <div class="loading-overlay">
                <div class="loading-spinner"></div>
            </div>
        `);
    }
    $('.loading-overlay').addClass('active');
}

function hideLoadingOverlay() {
    $('.loading-overlay').removeClass('active');
    setTimeout(() => {
        $('.loading-overlay').remove();
    }, 300);
}

// Fungsi untuk menampilkan notifikasi
function showNotification(message, type = 'info') {
    const notificationClass = {
        'success': 'alert-success',
        'error': 'alert-danger', 
        'warning': 'alert-warning',
        'info': 'alert-info'
    };
    
    const notification = $(`
        <div class="alert ${notificationClass[type]} alert-dismissible fade show" 
             style="position: fixed; top: 20px; right: 20px; z-index: 10000; min-width: 300px;">
            ${message}
            <button type="button" class="close" data-dismiss="alert">
                <span>&times;</span>
            </button>
        </div>
    `);
    
    $('body').append(notification);
    
    // Auto dismiss after 5 seconds
    setTimeout(() => {
        notification.fadeOut(300, function() {
            $(this).remove();
        });
    }, 5000);
}

// Fungsi untuk validasi link
function validateLinks() {
    $('.service-links-list a').each(function() {
        const link = $(this);
        const url = link.attr('href');
        
        // Simple URL validation
        if (!url || url === '#' || !url.includes('http')) {
            link.addClass('invalid-link')
                .css('opacity', '0.5')
                .append(' <small class="text-muted">(Link tidak valid)</small>');
        }
    });
}

// Fungsi untuk copy link
function copyToClipboard(text) {
    if (navigator.clipboard) {
        navigator.clipboard.writeText(text).then(() => {
            showNotification('Link berhasil disalin!', 'success');
        });
    } else {
        // Fallback untuk browser lama
        const textArea = document.createElement('textarea');
        textArea.value = text;
        document.body.appendChild(textArea);
        textArea.select();
        document.execCommand('copy');
        document.body.removeChild(textArea);
        showNotification('Link berhasil disalin!', 'success');
    }
}

// Event listener untuk copy link (jika diperlukan)
$(document).on('contextmenu', '.service-links-list a', function(e) {
    e.preventDefault();
    const url = $(this).attr('href');
    copyToClipboard(url);
});

// Fungsi untuk print halaman
function printPage() {
    window.print();
}

// Fungsi untuk export data (placeholder)
function exportData(format = 'json') {
    const pageData = {
        page: window.location.pathname.split('/').pop(),
        timestamp: new Date().toISOString(),
        applications: []
    };
    
    $('.service-category-item').each(function() {
        const title = $(this).find('h3').text().trim();
        const links = [];
        
        $(this).find('.service-links-list a').each(function() {
            links.push({
                text: $(this).text().trim(),
                url: $(this).attr('href')
            });
        });
        
        pageData.applications.push({
            title: title,
            links: links
        });
    });
    
    console.log('Data halaman:', pageData);
    showNotification('Data siap untuk export', 'info');
    return pageData;
}
