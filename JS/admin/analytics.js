const ANALYTICS_API_URL = '../../src/router/AnalyticsRouter.php';

// Utility functions
function showToast(text, type = 'success') {
    let portalRoot = document.getElementById('toast-portal');

    if (!portalRoot) {
        portalRoot = document.createElement('div');
        portalRoot.id = 'toast-portal';
        document.body.appendChild(portalRoot);
    }

    const toast = document.createElement('div');
    toast.className = `toast ${type}`;
    toast.innerText = text;

    portalRoot.appendChild(toast);

    setTimeout(() => {
        toast.remove();
        if (portalRoot.children.length === 0) {
            portalRoot.remove();
        }
    }, 3000);
}

// API Functions
async function fetchAPI(endpoint, method = 'GET', body = null) {
    try {
        const options = {
            method,
            headers: { 'Content-Type': 'application/json' },
        };

        if (body) {
            options.body = JSON.stringify(body);
        }

        const response = await fetch(endpoint, options);
        const data = await response.json();

        if (!response.ok || data.error) {
            throw new Error(data.error || 'Request failed');
        }

        return data;
    } catch (error) {
        console.error(`API Error (${endpoint}):`, error);
        throw error;
    }
}

const AnalyticsService = {
    getTotalRevenue: async (startDate, endDate) => {
        return fetchAPI(`${ANALYTICS_API_URL}?action=getTotalRevenue&startDate=${startDate}&endDate=${endDate}`);
    },

    getOrderStats: async (startDate, endDate) => {
        return fetchAPI(`${ANALYTICS_API_URL}?action=getOrderStats&startDate=${startDate}&endDate=${endDate}`);
    },

    getTopProducts: async (startDate, endDate, limit = 10) => {
        return fetchAPI(`${ANALYTICS_API_URL}?action=getTopProducts&startDate=${startDate}&endDate=${endDate}&limit=${limit}`);
    },

    getCouponUsage: async (startDate, endDate) => {
        return fetchAPI(`${ANALYTICS_API_URL}?action=getCouponUsage&startDate=${startDate}&endDate=${endDate}`);
    }
};

// UI Functions
function formatCurrency(amount) {
    return new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' }).format(amount);
}

async function showAnalytics() {
    const startDate = document.getElementById('startDate')?.value || new Date(new Date().setMonth(new Date().getMonth() - 1)).toISOString().split('T')[0];
    const endDate = document.getElementById('endDate')?.value || new Date().toISOString().split('T')[0];

    if (!startDate || !endDate) {
        showToast('Vui lòng chọn khoảng thời gian hợp lệ', 'error');
        return;
    }

    try {
        const [totalRevenueResp, orderStatsResp, topProductsResp, couponUsageResp] = await Promise.all([
            AnalyticsService.getTotalRevenue(startDate, endDate),
            AnalyticsService.getOrderStats(startDate, endDate),
            AnalyticsService.getTopProducts(startDate, endDate),
            AnalyticsService.getCouponUsage(startDate, endDate)
        ]);

        // Update Total Revenue
        const totalRevenueEl = document.getElementById('total-revenue');
        if (totalRevenueEl) {
            totalRevenueEl.innerText = formatCurrency(totalRevenueResp.total_revenue || 0);
        }

        // Update Order Stats
        const orderStatsTbody = document.getElementById('order-stats-tbody');
        if (orderStatsTbody) {
            orderStatsTbody.innerHTML = '';
            const stats = orderStatsResp || {};
            const statuses = ['pending', 'processing', 'shipped', 'delivered', 'cancelled'];
            statuses.forEach(status => {
                const count = stats[status] || 0;
                const tr = document.createElement('tr');
                tr.innerHTML = `
                    <td>${status.charAt(0).toUpperCase() + status.slice(1)}</td>
                    <td>${count}</td>
                `;
                orderStatsTbody.appendChild(tr);
            });
        }

        // Update Top Products
        const topProductsTbody = document.getElementById('top-products-tbody');
        if (topProductsTbody) {
            topProductsTbody.innerHTML = '';
            const products = topProductsResp || [];
            products.forEach(product => {
                const tr = document.createElement('tr');
                tr.innerHTML = `
                    <td>${product.name}</td>
                    <td>${product.total_quantity}</td>
                `;
                topProductsTbody.appendChild(tr);
            });
        }

        // Update Coupon Usage
        const couponUsageTbody = document.getElementById('coupon-usage-tbody');
        if (couponUsageTbody) {
            couponUsageTbody.innerHTML = '';
            const coupons = couponUsageResp || [];
            coupons.forEach(coupon => {
                const tr = document.createElement('tr');
                tr.innerHTML = `
                    <td>${coupon.name}</td>
                    <td>${coupon.usage_count}</td>
                `;
                couponUsageTbody.appendChild(tr);
            });
        }

    } catch (error) {
        showToast(error.message || 'Lỗi khi tải dữ liệu phân tích', 'error');
    }
}

// Filter Functions
function showFormFilter() {
    const portalRoot = document.createElement('div');
    portalRoot.id = 'portal-root';
    portalRoot.innerHTML = `
        <div class="wrapperFilterCss">
            <div class="CloseCss"><i class="fa-solid fa-xmark" onclick="closeModal()" style="cursor: pointer;"></i></div>
            <div class="wrapperCss">
                <div class="infoCss">Lọc dữ liệu phân tích</div>
                
                <label for="startDate">Ngày bắt đầu</label>
                <div class="wrapperInputCss">
                    <input class="inputUserCss" type="date" id="startDate" value="${new Date(new Date().setMonth(new Date().getMonth() - 1)).toISOString().split('T')[0]}">
                </div>
                
                <label for="endDate">Ngày kết thúc</label>
                <div class="wrapperInputCss">
                    <input class="inputUserCss" type="date" id="endDate" value="${new Date().toISOString().split('T')[0]}">
                </div>
                
                <div class="wrapperButton">
                    <button class="buttonUserCss" onclick="applyFilter()">
                        <i class="fas fa-filter"></i> Áp dụng bộ lọc
                    </button>
                </div>
            </div>
        </div>
    `;
    document.body.appendChild(portalRoot);
}

function closeModal() {
    const portalRoot = document.getElementById('portal-root');
    if (portalRoot) {
        portalRoot.remove();
    }
}

async function applyFilter() {
    try {
        await showAnalytics();
        closeModal();
        showToast('Áp dụng bộ lọc thành công', 'success');
    } catch (error) {
        showToast(error.message || 'Lỗi khi áp dụng bộ lọc', 'error');
        closeModal();
    }
}

// Initialize
document.addEventListener('DOMContentLoaded', () => {
    showAnalytics();
});

// Add click outside event to close modal
document.addEventListener('click', function(event) {
    const portalRoot = document.getElementById('portal-root');
    if (portalRoot && !portalRoot.contains(event.target)) {
        closeModal();
    }
});