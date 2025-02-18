# Mistri Mama

## Overview

Mistri Mama is a service-oriented platform designed to streamline the process of connecting users with service providers. The platform includes features such as user order processing, service partner management, and financial transactions.

The platform shares similarities with **Sheba XYZ** and **Foodpanda** in Bangladesh, offering a seamless experience for users to book services or order products efficiently.

## Technologies Used

- **PHP** (40.0%) - Backend logic and APIs
- **Blade** (34.4%) - Frontend templating
- **Vue.js** (23.3%) - Interactive UI components
- **JavaScript** (2.0%) - Client-side enhancements

## Business Logic

### 1. Order Processing

- Supports both **guest users** and **registered users**.
- Orders can be placed through the website, mobile app, or over the phone.
- Users can refer a service to others.
- Orders can be modified, canceled, or updated as needed.

### 2. Service Partner Management

- Service partners have accounts that require balance recharges.
- Cash-out options are available for partners and referrers.
- Services can be added, removed, or adjusted dynamically.

### 3. Financial Transactions

- Secondary cash flow processes ensure transparency in payments.
- Ecommerce product orders integrate with service requests.
- Various cash-in and cash-out mechanisms are in place.

### 4. B2B and B2G Services

- The platform supports **Business-to-Business (B2B)** and **Business-to-Government (B2G)** services.
- Enterprises and government entities can place bulk service requests.
- Custom contract and invoicing options are available.
- Dedicated service management for large-scale operations.

## Installation

1. Clone the repository:
   ```sh
   git clone https://github.com/RaihanBhuiyan/mistrimama.git
   ```
2. Navigate to the project directory:
   ```sh
   cd mistri-mama
   ```
3. Install dependencies:
   ```sh
   composer install
   npm install
   ```
4. Configure the environment:
   ```sh
   cp .env.example .env
   php artisan key:generate
   ```
5. Run the migrations:
   ```sh
   php artisan migrate --seed
   ```
6. Start the development server:
   ```sh
   php artisan serve
   ```

## Usage

- Users can browse and place orders.
- Orders can be placed via the **mobile app** for convenience.
- Service providers can manage their bookings and payments.
- Admins can oversee financial transactions and service updates.
- Businesses and government entities can manage bulk service requests.

## Contribution

Contributions are welcome! Please follow the standard GitHub flow:

1. Fork the repository.
2. Create a new branch.
3. Commit changes and push.
4. Submit a pull request.

## License

This project is licensed under the MIT License.

