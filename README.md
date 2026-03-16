# E-Voting System

A web-based electronic voting and election management system built with Laravel 10.

## Features

- User registration and authentication
- Voter profile management with admin approval
- Admin dashboard for managing elections and candidates
- Secure vote casting (one vote per voter per election)
- Real-time election results
- Email notifications for voter approval

## Requirements

- PHP >= 8.1
- Composer
- MySQL
- Node.js & NPM

## Installation

1. Clone the repository
   ```bash
   git clone https://github.com/Sharif185/E-Voting.git
   cd E-Voting
   ```

2. Install dependencies
   ```bash
   composer install
   npm install
   ```

3. Set up environment
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

4. Configure your database in `.env`
   ```env
   DB_DATABASE=your_database
   DB_USERNAME=your_username
   DB_PASSWORD=your_password
   ```

5. Run migrations
   ```bash
   php artisan migrate
   ```

6. Start the server
   ```bash
   php artisan serve
   ```

Visit `http://127.0.0.1:8000` in your browser.

## Roles

- Admin — manages elections, candidates, and voter approvals
- Voter — registers, gets approved, and casts votes

## License

MIT
