from typing import List, Dict
import re

class AlertRuleEngine:
    def __init__(self):
        self.rules = []
        
    def add_rule(self, rule: Dict):
        """
        規則格式:
        {
            'name': 'rule_name',
            'condition': 'metric > threshold',
            'level': 'warning',
            'message': 'Alert message template'
        }
        """
        self.rules.append(rule)
        
    def evaluate_metrics(self, metrics: Dict) -> List[Dict]:
        triggered_alerts = []
        for rule in self.rules:
            if self._evaluate_condition(rule['condition'], metrics):
                triggered_alerts.append(self._create_alert(rule, metrics))
        return triggered_alerts