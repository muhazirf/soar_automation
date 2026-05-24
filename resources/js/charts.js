/**
 * Chart Manager Module
 *
 * Handles Chart.js instances with API integration
 * Follows singleton pattern for efficient resource management
 */

import Chart from 'chart.js/auto';
import axios from 'axios';

// Chart configuration defaults
const DEFAULT_CHART_OPTIONS = {
    responsive: true,
    maintainAspectRatio: false,
    plugins: {
        legend: {
            display: true,
            position: 'top',
            labels: {
                color: '#c5c6cd',
                font: {
                    family: "'Inter', sans-serif",
                    size: 12,
                },
                usePointStyle: true,
                padding: 15,
            },
        },
        tooltip: {
            backgroundColor: 'rgba(30, 30, 40, 0.95)',
            titleColor: '#ffffff',
            bodyColor: '#c5c6cd',
            borderColor: 'rgba(143, 144, 151, 0.2)',
            borderWidth: 1,
            padding: 12,
            displayColors: true,
            callbacks: {
                label: function(context) {
                    let label = context.dataset.label || '';
                    if (label) {
                        label += ': ';
                    }
                    if (context.parsed.y !== null) {
                        label += context.parsed.y;
                    }
                    return label;
                },
            },
        },
    },
    scales: {
        x: {
            grid: {
                color: 'rgba(143, 144, 151, 0.1)',
                drawBorder: false,
            },
            ticks: {
                color: '#c5c6cd',
                font: {
                    family: "'JetBrains Mono', monospace",
                    size: 11,
                },
            },
        },
        y: {
            grid: {
                color: 'rgba(143, 144, 151, 0.1)',
                drawBorder: false,
            },
            ticks: {
                color: '#c5c6cd',
                font: {
                    family: "'JetBrains Mono', monospace",
                    size: 11,
                },
            },
        },
    },
    interaction: {
        intersect: false,
        mode: 'index',
    },
};

const LINE_CHART_OPTIONS = {
    ...DEFAULT_CHART_OPTIONS,
    elements: {
        line: {
            tension: 0.4,
            borderWidth: 2,
        },
        point: {
            radius: 3,
            hoverRadius: 6,
            borderWidth: 0,
        },
    },
};

const BAR_CHART_OPTIONS = {
    ...DEFAULT_CHART_OPTIONS,
    elements: {
        bar: {
            borderRadius: 4,
            borderSkipped: false,
        },
    },
};

const DOUGHNUT_CHART_OPTIONS = {
    responsive: true,
    maintainAspectRatio: false,
    plugins: {
        legend: {
            display: true,
            position: 'right',
            labels: {
                color: '#c5c6cd',
                font: {
                    family: "'Inter', sans-serif",
                    size: 12,
                },
                usePointStyle: true,
                padding: 15,
            },
        },
        tooltip: {
            backgroundColor: 'rgba(30, 30, 40, 0.95)',
            titleColor: '#ffffff',
            bodyColor: '#c5c6cd',
            borderColor: 'rgba(143, 144, 151, 0.2)',
            borderWidth: 1,
            padding: 12,
        },
    },
    cutout: '65%',
};

// API endpoints configuration
const API_ENDPOINTS = {
    alerts: '/api/charts/alerts/trends',
    threats: '/api/charts/threats/stats',
    incidents: '/api/charts/incidents/trends',
    system: '/api/charts/system/metrics',
    clearance: '/api/charts/users/clearance',
    realtime: '/api/charts/realtime',
};

/**
 * Chart Manager Class
 * Singleton pattern for managing chart instances
 */
class ChartManager {
    constructor() {
        if (ChartManager.instance) {
            return ChartManager.instance;
        }

        this.charts = new Map();
        this.updateInterval = null;
        this.abortControllers = new Map();

        ChartManager.instance = this;
    }

    /**
     * Create a new chart instance
     *
     * @param {string} canvasId - Canvas element ID
     * @param {string} type - Chart type (line, bar, doughnut, etc.)
     * @param {object} data - Chart data
     * @param {object} options - Chart options
     * @returns {Chart} Chart instance
     */
    createChart(canvasId, type, data, options = {}) {
        const canvas = document.getElementById(canvasId);
        if (!canvas) {
            console.error(`Canvas element with id "${canvasId}" not found`);
            return null;
        }

        // Destroy existing chart if present
        if (this.charts.has(canvasId)) {
            this.destroyChart(canvasId);
        }

        // Merge with default options
        const defaultOptions = this.getDefaultOptionsForType(type);
        const mergedOptions = this.mergeOptions(defaultOptions, options);

        const chart = new Chart(canvas, {
            type,
            data,
            options: mergedOptions,
        });

        this.charts.set(canvasId, chart);
        return chart;
    }

    /**
     * Create line chart from API
     *
     * @param {string} canvasId - Canvas element ID
     * @param {string} endpoint - API endpoint
     * @param {object} params - Query parameters
     * @param {object} options - Chart options
     * @returns {Promise<Chart>} Chart instance
     */
    async createLineChartFromApi(canvasId, endpoint, params = {}, options = {}) {
        try {
            const data = await this.fetchChartData(endpoint, params);
            return this.createChart(canvasId, 'line', data, options);
        } catch (error) {
            console.error('Failed to create line chart:', error);
            this.showChartError(canvasId, error);
            throw error;
        }
    }

    /**
     * Create bar chart from API
     *
     * @param {string} canvasId - Canvas element ID
     * @param {string} endpoint - API endpoint
     * @param {object} params - Query parameters
     * @param {object} options - Chart options
     * @returns {Promise<Chart>} Chart instance
     */
    async createBarChartFromApi(canvasId, endpoint, params = {}, options = {}) {
        try {
            const data = await this.fetchChartData(endpoint, params);
            return this.createChart(canvasId, 'bar', data, options);
        } catch (error) {
            console.error('Failed to create bar chart:', error);
            this.showChartError(canvasId, error);
            throw error;
        }
    }

    /**
     * Create doughnut chart from API
     *
     * @param {string} canvasId - Canvas element ID
     * @param {string} endpoint - API endpoint
     * @param {object} params - Query parameters
     * @param {object} options - Chart options
     * @returns {Promise<Chart>} Chart instance
     */
    async createDoughnutChartFromApi(canvasId, endpoint, params = {}, options = {}) {
        try {
            const data = await this.fetchChartData(endpoint, params);
            return this.createChart(canvasId, 'doughnut', data, options);
        } catch (error) {
            console.error('Failed to create doughnut chart:', error);
            this.showChartError(canvasId, error);
            throw error;
        }
    }

    /**
     * Update existing chart with new data
     *
     * @param {string} canvasId - Canvas element ID
     * @param {object} newData - New chart data
     */
    updateChart(canvasId, newData) {
        const chart = this.charts.get(canvasId);
        if (!chart) {
            console.warn(`Chart "${canvasId}" not found`);
            return;
        }

        chart.data = newData;
        chart.update('none'); // Update without animation for performance
    }

    /**
     * Update chart from API
     *
     * @param {string} canvasId - Canvas element ID
     * @param {string} endpoint - API endpoint
     * @param {object} params - Query parameters
     * @returns {Promise<void>}
     */
    async updateChartFromApi(canvasId, endpoint, params = {}) {
        try {
            const data = await this.fetchChartData(endpoint, params);
            this.updateChart(canvasId, data);
        } catch (error) {
            console.error('Failed to update chart:', error);
        }
    }

    /**
     * Destroy a chart instance
     *
     * @param {string} canvasId - Canvas element ID
     */
    destroyChart(canvasId) {
        const chart = this.charts.get(canvasId);
        if (chart) {
            chart.destroy();
            this.charts.delete(canvasId);
        }

        // Cancel any pending requests
        const controller = this.abortControllers.get(canvasId);
        if (controller) {
            controller.abort();
            this.abortControllers.delete(canvasId);
        }
    }

    /**
     * Destroy all charts
     */
    destroyAllCharts() {
        this.charts.forEach((chart, canvasId) => {
            this.destroyChart(canvasId);
        });
    }

    /**
     * Setup auto-refresh for charts
     *
     * @param {number} interval - Refresh interval in milliseconds
     * @param {Array} configs - Array of {canvasId, endpoint, params}
     */
    setupAutoRefresh(interval, configs = []) {
        this.clearAutoRefresh();

        this.updateInterval = setInterval(() => {
            configs.forEach(({ canvasId, endpoint, params }) => {
                this.updateChartFromApi(canvasId, endpoint, params);
            });
        }, interval);
    }

    /**
     * Clear auto-refresh
     */
    clearAutoRefresh() {
        if (this.updateInterval) {
            clearInterval(this.updateInterval);
            this.updateInterval = null;
        }
    }

    /**
     * Fetch chart data from API with error handling
     *
     * @param {string} endpoint - API endpoint
     * @param {object} params - Query parameters
     * @returns {Promise<object>} Chart data
     */
    async fetchChartData(endpoint, params = {}) {
        // Cancel previous request for the same endpoint
        const cacheKey = btoa(endpoint + JSON.stringify(params));
        const existingController = this.abortControllers.get(cacheKey);
        if (existingController) {
            existingController.abort();
        }

        // Create new abort controller
        const controller = new AbortController();
        this.abortControllers.set(cacheKey, controller);

        try {
            const response = await axios.get(endpoint, {
                params,
                signal: controller.signal,
            });

            if (!response.data.success) {
                throw new Error(response.data.message || 'API request failed');
            }

            return response.data.data;
        } finally {
            // Clean up controller after request completes
            this.abortControllers.delete(cacheKey);
        }
    }

    /**
     * Get default options for chart type
     *
     * @param {string} type - Chart type
     * @returns {object} Default options
     */
    getDefaultOptionsForType(type) {
        switch (type) {
            case 'line':
                return { ...LINE_CHART_OPTIONS };
            case 'bar':
                return { ...BAR_CHART_OPTIONS };
            case 'doughnut':
            case 'pie':
            case 'polarArea':
                return { ...DOUGHNUT_CHART_OPTIONS };
            default:
                return { ...DEFAULT_CHART_OPTIONS };
        }
    }

    /**
     * Merge chart options
     *
     * @param {object} defaults - Default options
     * @param {object} custom - Custom options
     * @returns {object} Merged options
     */
    mergeOptions(defaults, custom) {
        return this.deepMerge(defaults, custom);
    }

    /**
     * Deep merge objects
     *
     * @param {object} target - Target object
     * @param {object} source - Source object
     * @returns {object} Merged object
     */
    deepMerge(target, source) {
        const output = { ...target };

        if (this.isObject(target) && this.isObject(source)) {
            Object.keys(source).forEach(key => {
                if (this.isObject(source[key])) {
                    if (!(key in target)) {
                        Object.assign(output, { [key]: source[key] });
                    } else {
                        output[key] = this.deepMerge(target[key], source[key]);
                    }
                } else {
                    Object.assign(output, { [key]: source[key] });
                }
            });
        }

        return output;
    }

    /**
     * Check if value is object
     *
     * @param {*} item - Item to check
     * @returns {boolean}
     */
    isObject(item) {
        return item && typeof item === 'object' && !Array.isArray(item);
    }

    /**
     * Show error on chart canvas
     *
     * @param {string} canvasId - Canvas element ID
     * @param {Error} error - Error object
     */
    showChartError(canvasId, error) {
        const canvas = document.getElementById(canvasId);
        if (canvas) {
            const ctx = canvas.getContext('2d');
            const parent = canvas.parentElement;

            // Clear canvas
            ctx.clearRect(0, 0, canvas.width, canvas.height);

            // Show error message
            const errorDiv = document.createElement('div');
            errorDiv.className = 'absolute inset-0 flex items-center justify-center text-error text-sm';
            errorDiv.textContent = 'Failed to load chart data';

            if (parent.style.position !== 'relative') {
                parent.style.position = 'relative';
            }

            parent.appendChild(errorDiv);
        }
    }

    /**
     * Get chart instance
     *
     * @param {string} canvasId - Canvas element ID
     * @returns {Chart|null} Chart instance
     */
    getChart(canvasId) {
        return this.charts.get(canvasId) || null;
    }

    /**
     * Check if chart exists
     *
     * @param {string} canvasId - Canvas element ID
     * @returns {boolean}
     */
    hasChart(canvasId) {
        return this.charts.has(canvasId);
    }
}

// Create singleton instance
const chartManager = new ChartManager();

// Export for use in other modules
export default chartManager;

// Export API endpoints constant
export { API_ENDPOINTS };

// Export utility functions
export const createAlertTrendsChart = (canvasId, timeRange = '24h') => {
    return chartManager.createLineChartFromApi(
        canvasId,
        API_ENDPOINTS.alerts,
        { time_range: timeRange }
    );
};

export const createThreatStatsChart = (canvasId) => {
    return chartManager.createBarChartFromApi(
        canvasId,
        API_ENDPOINTS.threats,
        {},
        {
            plugins: {
                legend: { display: false },
            },
        }
    );
};

export const createThreatSeverityChart = (canvasId) => {
    return chartManager.createDoughnutChartFromApi(
        canvasId,
        API_ENDPOINTS.threats,
        {},
        {
            plugins: {
                legend: { position: 'bottom' },
            },
        }
    );
};

export const createIncidentTrendsChart = (canvasId, timeRange = '30d') => {
    return chartManager.createLineChartFromApi(
        canvasId,
        API_ENDPOINTS.incidents,
        { time_range: timeRange }
    );
};

export const createClearanceDistributionChart = (canvasId) => {
    return chartManager.createDoughnutChartFromApi(
        canvasId,
        API_ENDPOINTS.clearance
    );
};

// Debounce utility for auto-refresh
export const debounce = (func, wait) => {
    let timeout;
    return function executedFunction(...args) {
        const later = () => {
            clearTimeout(timeout);
            func(...args);
        };
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
    };
};

// Cleanup on page unload
window.addEventListener('beforeunload', () => {
    chartManager.destroyAllCharts();
});
