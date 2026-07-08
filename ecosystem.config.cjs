module.exports = {
  apps: [{
    name: 'marketplace-worker',
    script: 'php',
    args: 'artisan queue:work --sleep=3 --tries=3 --max-time=3600',
    instances: 1,
    autorestart: true,
    watch: false,
    max_memory_restart: '1G',
    env: {
      NODE_ENV: 'production',
    },
  }]
};