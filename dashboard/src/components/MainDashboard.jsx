import React, { useState, useEffect } from 'react';
import { Grid, Paper, Typography } from '@mui/material';
import { PerformanceChart } from './charts/PerformanceChart';
import { SystemStatus } from './status/SystemStatus';
import { AlertList } from './alerts/AlertList';

export const MainDashboard = () => {
  const [dashboardData, setDashboardData] = useState(null);

  useEffect(() => {
    const fetchData = async () => {
      const response = await fetch('/api/dashboard/metrics');
      const data = await response.json();
      setDashboardData(data);
    };

    fetchData();
    const interval = setInterval(fetchData, 5000);
    return () => clearInterval(interval);
  }, []);

  return (
    <Grid container spacing={3}>
      <Grid item xs={12}>
        <Typography variant="h4">系統監控儀表板</Typography>
      </Grid>
      <Grid item xs={12} md={8}>
        <PerformanceChart data={dashboardData?.performance} />
      </Grid>
      <Grid item xs={12} md={4}>
        <SystemStatus data={dashboardData?.status} />
      </Grid>
    </Grid>
  );
};