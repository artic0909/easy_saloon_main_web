# Database Design

## Users
- id (PK)
- name
- email
- username
- phone
- photo
- password
- role (enum: user, staff, admin)
- is_active (boolean)
- created_at
- updated_at

## Wallets
- id (PK)
- user_id (FK -> users.id)
- balance
- created_at
- updated_at

## Countries
- id (PK)
- name
- flag
- created_at
- updated_at

## States
- id (PK)
- name
- country_id (FK -> countries.id)
- created_at
- updated_at

## Cities
- id (PK)
- name
- state_id (FK -> states.id)
- country_id (FK -> countries.id)
- created_at
- updated_at

## Addresses
- id (PK)
- user_id (FK -> users.id)
- title (e.g. Home, Office)
- full_address
- landmark
- latitude
- longitude
- city_id (FK -> cities.id)
- state_id (FK -> states.id)
- country_id (FK -> countries.id)
- is_primary (boolean)
- created_at
- updated_at

## Categories
- id (PK)
- name
- slug
- image
- is_active (boolean)
- created_at
- updated_at

## Services
- id (PK)
- name
- slug
- category_id (FK -> categories.id)
- duration_minutes
- sale_price
- original_price
- details
- image
- is_active (boolean)
- created_at
- updated_at

## Packages
- id (PK)
- name
- slug
- sale_price
- original_price
- image
- created_by_user_id (FK -> users.id, nullable)
- created_by_admin (boolean)
- is_active (boolean)
- created_at
- updated_at

## Package Items
- id (PK)
- package_id (FK -> packages.id)
- service_id (FK -> services.id)
- quantity
- created_at
- updated_at

## Cart Items
- id (PK)
- user_id (FK -> users.id)
- service_id (FK -> services.id, nullable)
- package_id (FK -> packages.id, nullable)
- item_type (enum: service, package)
- quantity
- created_at
- updated_at

## Coupons
- id (PK)
- code
- title
- short_description
- description
- discount_type (fixed, percentage)
- discount_value
- min_order_amount
- max_discount (for percentage)
- usage_limit_per_user
- total_usage_limit
- expiry_date
- is_active (boolean)
- created_at
- updated_at

## Bookings
- id (PK)
- booking_number
- user_id (FK -> users.id)
- staff_id (FK -> staff.id, nullable)
- salon_id (FK -> salons.id, nullable)
- service_type (enum: home, salon_visit)
- total_price
- discount_amount
- payable_amount
- booking_date
- time_slot
- status (enum: pending, confirmed, accepted, on_the_way, started, completed, cancelled)
- is_paid (boolean)
- payment_type (online, wallet, cod)
- address_id (FK -> addresses.id, for home service)
- cancellation_reason
- created_at
- updated_at

## Booking Items
- id (PK)
- booking_id (FK -> bookings.id)
- service_id (FK -> services.id, nullable)
- package_id (FK -> packages.id, nullable)
- item_type (enum: service, package)
- quantity
- created_at
- updated_at

## Transactions
- id (PK)
- transaction_id
- booking_id (FK -> bookings.id)
- status
- payment_mode
- amount
- wallet_used (boolean)
- wallet_amount
- created_at
- updated_at

## Relationships
- users have one wallet
- users have many addresses
- users have many bookings
- users have many cart items
- countries have many states and cities
- states belong to a country and have many cities
- cities belong to a state and country
- categories have many services
- packages can include many services via package_items
- bookings can include services or packages via booking_items
- transactions belong to bookings
- staff belong to users (role: staff)
- staff can be assigned to many bookings
- salons have many staff
- bookings belong to one user, one staff (optional), and one salon (optional)
- bookings have one address (optional)

## Salons
- id (PK)
- name
- slug
- description
- address
- city_id (FK -> cities.id)
- latitude
- longitude
- phone
- email
- image
- is_active (boolean)
- created_at
- updated_at

## Staff
- id (PK)
- user_id (FK -> users.id)
- salon_id (FK -> salons.id, nullable)
- designation
- bio
- experience_years
- rating
- is_available (boolean)
- created_at
- updated_at

## Staff Availability
- id (PK)
- staff_id (FK -> staff.id)
- day_of_week (0-6)
- start_time
- end_time
- is_working_day (boolean)
- created_at
- updated_at

## Staff Locations (Real-time tracking)
- id (PK)
- staff_id (FK -> staff.id)
- booking_id (FK -> bookings.id)
- latitude
- longitude
- captured_at
- created_at

## Banners
- id (PK)
- title
- image
- link (optional)
- sort_order
- is_active (boolean)
- created_at
- updated_at

## Site Settings
- id (PK)
- key
- value
- created_at
- updated_at







