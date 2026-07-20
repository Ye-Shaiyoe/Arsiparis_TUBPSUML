# Contributing to Surat-Laravel (Arsiparis_TUBPSUML)

Thank you for your interest in contributing to this project! Here are some guidelines to help you get started.

## Code of Conduct

Please be respectful and constructive in all interactions.

## How Can I Contribute?

### Reporting Bugs
- Search existing issues to see if the bug has already been reported.
- If not, create a new issue with a clear title, description, steps to reproduce, and expected/actual behavior.

### Suggesting Enhancements
- Open an issue explaining the proposed feature and why it would be useful.

### Pull Requests
1. Fork the repository.
2. Create a new branch for your feature or bugfix (`git checkout -b feature/amazing-feature`).
3. Make your changes and commit them with descriptive messages.
4. Push to your fork (`git push origin feature/amazing-feature`).
5. Open a Pull Request against the `main` or `develop` branch of this repository.

## Development Setup

This is a Laravel-based project.
1. Clone the repository.
2. Run `composer install` and `npm install`.
3. Copy `.env.example` to `.env` and configure your database and environment.
4. Run php artisan key:generate.
5. Run migrations: `php artisan migrate`.
