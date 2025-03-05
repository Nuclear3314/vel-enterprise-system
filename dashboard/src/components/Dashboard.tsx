import React, { useState, useEffect } from 'react';
import { Grid, Paper, Typography } from '@mui/material';
import { MetricsPanel } from './panels/MetricsPanel';
import { AlertPanel } from './panels/AlertPanel';
import { PerformanceChart } from './charts/PerformanceChart';

export const Dashboard: React.FC = () => {
  const [systemData, setSystemData] = useState<SystemData | null>(null);

  useEffect(() => {
    const fetchData = async () => {
      const response = await fetch('/api/system/metrics');
      const data = await response.json();
      setSystemData(data);
    };

    fetchData();
    const interval = setInterval(fetchData, 5000);
    return () => clearInterval(interval);
  }, []);

  return (
    <Grid container spacing={2}>
      <Grid item xs={12}>
        <Typography variant="h4">系統監控儀表板</Typography>
      </Grid>
      <Grid item xs={12} md={8}>
        <PerformanceChart data={systemData?.performance} />
      </Grid>
      <Grid item xs={12} md={4}>
        <AlertPanel alerts={systemData?.alerts} />
      </Grid>
    </Grid>
  );
};