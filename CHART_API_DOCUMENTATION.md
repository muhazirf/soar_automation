# Chart.js API Integration Documentation

## Overview

This project uses Chart.js v4 with a custom Laravel API backend following best practices:
- Singleton pattern for chart management
- API-first data loading with axios
- Proper cleanup and memory management
- Debounced API calls
- Real-time data updates

## Installation

```bash
npm install chart.js@4
```

## API Endpoints

### Base URL: `/api/charts`

All endpoints require authentication (session-based).

| Endpoint | Method | Description | Parameters |
|----------|--------|-------------|------------|
| `/alerts/trends` | GET | Get alert trends data | `time_range`: 24h, 7d, 30d, 90d<br>`interval`: hour, day, week |
| `/threats/stats` | GET | Get threat statistics | - |
| `/incidents/trends` | GET | Get incident trends | `time_range`: 7d, 30d, 90d |
| `/system/metrics` | GET | Get system metrics | - |
| `/users/clearance` | GET | Get clearance distribution | - |
| `/realtime/{type}` | GET | Get real-time data | `type`: alerts, incidents, threats, cpu, memory |

### Response Format

```json
{
  "success": true,
  "message": null,
  "data": {
    "labels": ["00:00", "01:00", "02:00"],
    "datasets": [
      {
        "label": "Alerts",
        "data": [25, 32, 28],
        "borderColor": "#ef4444",
        "backgroundColor": "rgba(239, 68, 68, 0.1)"
      }
    ]
  }
}
```

## JavaScript Usage

### Basic Chart Creation

```javascript
// Import the chart manager
import chartManager from './charts';

// Create a line chart
const chart = await chartManager.createLineChartFromApi(
    'canvasId',
    '/api/charts/alerts/trends',
    { time_range: '24h' }
);
```

### Predefined Chart Helpers

```javascript
import {
    createAlertTrendsChart,
    createThreatStatsChart,
    createIncidentTrendsChart,
    createClearanceDistributionChart
} from './charts';

// Create alert trends chart
await createAlertTrendsChart('alertsChart', '24h');
```

### Manual Chart Creation

```javascript
const chart = chartManager.createChart(
    'myCanvas',
    'line',
    {
        labels: ['A', 'B', 'C'],
        datasets: [{
            label: 'My Data',
            data: [10, 20, 30],
            borderColor: '#3b82f6',
            backgroundColor: 'rgba(59, 130, 246, 0.1)',
        }]
    },
    {
        responsive: true,
        maintainAspectRatio: false,
    }
);
```

### Updating Charts

```javascript
// Update with new data
await chartManager.updateChartFromApi(
    'canvasId',
    '/api/charts/alerts/trends',
    { time_range: '7d' }
);
```

### Auto-Refresh Setup

```javascript
// Refresh every 30 seconds
chartManager.setupAutoRefresh(30000, [
    { canvasId: 'alertsChart', endpoint: '/api/charts/alerts/trends' },
    { canvasId: 'cpuChart', endpoint: '/api/charts/realtime/cpu' },
]);
```

### Cleanup

```javascript
// Destroy single chart
chartManager.destroyChart('canvasId');

// Destroy all charts
chartManager.destroyAllCharts();

// Clear auto-refresh
chartManager.clearAutoRefresh();
```

## Chart Manager API

### Methods

| Method | Description |
|--------|-------------|
| `createChart(canvasId, type, data, options)` | Create a new chart |
| `createLineChartFromApi(canvasId, endpoint, params, options)` | Create line chart from API |
| `createBarChartFromApi(canvasId, endpoint, params, options)` | Create bar chart from API |
| `createDoughnutChartFromApi(canvasId, endpoint, params, options)` | Create doughnut chart from API |
| `updateChart(canvasId, newData)` | Update chart with new data |
| `updateChartFromApi(canvasId, endpoint, params)` | Update chart from API |
| `destroyChart(canvasId)` | Destroy a chart |
| `destroyAllCharts()` | Destroy all charts |
| `setupAutoRefresh(interval, configs)` | Setup auto-refresh |
| `clearAutoRefresh()` | Clear auto-refresh |
| `getChart(canvasId)` | Get chart instance |
| `hasChart(canvasId)` | Check if chart exists |

## Best Practices

1. **Always clean up** - Destroy charts when navigating away from pages
2. **Use API helpers** - Prefer predefined helpers for consistency
3. **Handle errors** - Always wrap async chart operations in try-catch
4. **Debounce updates** - For frequent updates, use debouncing
5. **Memory management** - Use destroyAllCharts() on page unload

## Example: Complete Dashboard Integration

```javascript
document.addEventListener('DOMContentLoaded', async function() {
    let alertsChart = null;

    try {
        // Initialize charts
        alertsChart = await createAlertTrendsChart('alertsChart', '24h');

        // Setup auto-refresh
        chartManager.setupAutoRefresh(30000, [
            {
                canvasId: 'alertsChart',
                endpoint: '/api/charts/alerts/trends',
                params: { time_range: '24h' }
            }
        ]);
    } catch (error) {
        console.error('Chart initialization failed:', error);
    }

    // Cleanup on page unload
    window.addEventListener('beforeunload', () => {
        chartManager.clearAutoRefresh();
        chartManager.destroyAllCharts();
    });
});
```

## Troubleshooting

### Chart not displaying
- Check canvas element exists in DOM
- Verify API returns correct data format
- Check browser console for errors

### Memory leaks
- Ensure charts are destroyed when page unloads
- Clear auto-refresh intervals
- Use destroyChart() before recreating charts

### API errors
- Check authentication status
- Verify endpoint URLs
- Check network tab in browser dev tools
