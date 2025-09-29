# Claude Code Configuration

This file contains configuration and commands for Claude Code to help with development tasks.

## Development Commands

### Testing
```bash
# Run PHPUnit tests
vendor/bin/phpunit tests/
```

### Linting
```bash
# PHP CodeSniffer for code style
vendor/bin/phpcs --standard=PSR12 src/
```

### Type Checking
```bash
# PHP Static Analysis with PHPStan
vendor/bin/phpstan analyse src/
```

### Build
```bash
# Build and start Docker containers
docker-compose up --build
```

### Start Development Environment
```bash
# Start containers in detached mode
docker-compose up -d

# View logs
docker-compose logs -f

# Stop containers
docker-compose down
```

## Project Information

- **Framework/Language**: PHP 8.2 with Slim Framework 4
- **Package Manager**: Composer
- **Main Entry Point**: public/index.php
- **Database**: MySQL 8.0
- **Environment**: Docker containers

## Notes

- Update the commands above with your actual project commands
- Claude will use these commands to verify code quality after making changes