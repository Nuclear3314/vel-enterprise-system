import React, { useState, useEffect } from 'react';
import { Grid, Paper } from '@mui/material';
import { PerformanceChart } from './charts/PerformanceChart';
import { SystemStatus } from './status/SystemStatus';

export const Dashboard = () => {
  const [metrics, setMetrics] = useState(null);

  useEffect(() => {
    const fetchMetrics = async () => {
      const response = await fetch('/api/metrics');
      const data = await response.json();
      setMetrics(data);
    };

    fetchMetrics();
    const interval = setInterval(fetchMetrics, 5000);
    return () => clearInterval(interval);
  }, []);

  return (
    <Grid container spacing={3}>
      <Grid item xs={12} md={8}>
        <Paper elevation={3}>
          <PerformanceChart data={metrics?.performance} />
        </Paper>
      </Grid>
      <Grid item xs={12} md={4}>
        <Paper elevation={3}>
          <SystemStatus data={metrics?.status} />
        </Paper>
      </Grid>
    </Grid>
  );
};
