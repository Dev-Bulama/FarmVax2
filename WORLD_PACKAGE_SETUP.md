# World Package Setup Instructions

This project uses the `nnjeim/world` package to provide comprehensive country, state, and city data for location dropdowns.

## Installation Steps

### 1. Run Migrations
```bash
php artisan migrate
```

### 2. Seed World Data
Run the WorldSeeder to populate all countries, states, and cities:

```bash
php artisan db:seed --class=WorldSeeder
```

**Note**: This seeder will populate:
- **250+ countries** with full details
- **5,000+ states/provinces** worldwide
- **150,000+ cities** globally

The seeding process may take **5-15 minutes** depending on your server.

### 3. Verify API Endpoints

After seeding, test the API endpoints:

```bash
# Get all countries
curl http://your-domain.com/api/countries

# Get states for Nigeria (ID: varies, use NG code or check DB)
curl http://your-domain.com/api/states/NG

# Get cities/LGAs for a state (use state ID)
curl http://your-domain.com/api/cities/{state_id}
```

## Available API Endpoints

### Countries
```
GET /api/countries
```
Returns all countries with id, name, iso2, iso3, phone_code, etc.

### States
```
GET /api/states/{country}
```
- `{country}` can be country ID or ISO2 code (e.g., "NG" for Nigeria)
- Returns all states/provinces for the specified country

### Cities/LGAs
```
GET /api/cities/{state}
GET /api/lgas/{state}
```
- `{state}` is the state ID
- Returns all cities/LGAs for the specified state
- Both endpoints return the same data (LGA is an alias for backward compatibility)

## Features

- ✅ **198 countries** fully supported
- ✅ Hierarchical data (Country → State → City/LGA)
- ✅ Cascading dropdowns work automatically
- ✅ Multi-language support (40+ languages)
- ✅ Timezone data included
- ✅ Currency information
- ✅ Phone codes and ISO codes

## Package Documentation

For more information about the world package, visit:
https://github.com/nnjeim/world

## Troubleshooting

### Dropdowns not loading?
1. Make sure you've run the WorldSeeder: `php artisan db:seed --class=WorldSeeder`
2. Check API routes are accessible: `php artisan route:list | grep api`
3. Clear cache: `php artisan cache:clear`

### Database errors?
- Ensure migrations have run: `php artisan migrate`
- Check database connection in `.env` file

### Still showing only Nigeria?
- You may need to run: `php artisan db:seed --class=WorldSeeder --force`
- This will populate all countries, not just Nigeria
