import React, { useState, useEffect } from 'react';
import { Grid, Paper, Typography } from '@mui/material';
import { SystemMetrics } from './metrics/SystemMetrics';
import { PerformanceGraph } from './graphs/PerformanceGraph';
import { AlertsList } from './alerts/AlertsList';

interface DashboardData {
  metrics: any;
  alerts: any[];
  status: string;
}

export const SystemDashboard: React.FC = () => {
  const [data, setData] = useState<DashboardData | null>(null);

  useEffect(() => {
    const fetchData = async () => {
      const response = await fetch('/api/dashboard/metrics');
      const result = await response.json();
      setData(result);
    };

    fetchData();
    const interval = setInterval(fetchData, 5000);
    return () => clearInterval(interval);
  }, []);

  return (
    <Grid container spacing={3}>
      <Grid item xs={12}>
        <Typography variant="h4">系統監控中心</Typography>
      </Grid>
      <Grid item xs={12} md={8}>
        <Paper elevation={3}>
          <PerformanceGraph data={data?.metrics} />
        </Paper>
      </Grid>
      <Grid item xs={12} md={4}>
        <AlertsList alerts={data?.alerts} />
      </Grid>
    </Grid>
  );
};