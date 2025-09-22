# BugTester Application - Testing Documentation

## Overview

This document provides comprehensive information about the testing suite for the BugTester application. The test suite includes unit tests, feature tests, integration tests, performance tests, and API tests.

## Test Structure

```
tests/
├── Unit/                          # Unit tests for individual components
│   ├── Models/                    # Model unit tests
│   │   ├── UserTest.php
│   │   ├── BugTest.php
│   │   ├── ProjectTest.php
│   │   └── SystemSettingTest.php
│   └── Services/                  # Service unit tests
│       ├── AiBugSummarizationServiceTest.php
│       └── NotificationServiceTest.php
├── Feature/                       # Feature tests for complete workflows
│   ├── Auth/                      # Authentication tests
│   ├── BugManagementTest.php      # Bug management feature tests
│   ├── ProjectManagementTest.php  # Project management feature tests
│   ├── IntegrationTest.php        # End-to-end integration tests
│   ├── PerformanceTest.php        # Performance and load tests
│   └── ApiTest.php               # API endpoint tests
├── TestCase.php                   # Base test case with utilities
└── TestSuite.php                  # Comprehensive test suite utilities
```

## Running Tests

### Quick Start

```bash
# Run all tests
./run-tests.sh

# Or use PHPUnit directly
vendor/bin/phpunit
```

### Individual Test Suites

```bash
# Unit tests only
vendor/bin/phpunit tests/Unit

# Feature tests only
vendor/bin/phpunit tests/Feature

# Specific test file
vendor/bin/phpunit tests/Feature/BugManagementTest.php

# With coverage report
vendor/bin/phpunit --coverage-html coverage
```

### Test Categories

#### 1. Unit Tests
- **Purpose**: Test individual components in isolation
- **Coverage**: Models, Services, Helper classes
- **Location**: `tests/Unit/`

**Key Tests:**
- Model relationships and attributes
- Service methods and business logic
- Data validation and constraints
- Permission checks and authorization

#### 2. Feature Tests
- **Purpose**: Test complete user workflows
- **Coverage**: Livewire components, Controllers, Views
- **Location**: `tests/Feature/`

**Key Tests:**
- Bug creation, editing, and management
- Project lifecycle management
- User authentication and authorization
- Kanban board functionality
- Search and filtering

#### 3. Integration Tests
- **Purpose**: Test complete workflows across multiple components
- **Coverage**: End-to-end user journeys
- **Location**: `tests/Feature/IntegrationTest.php`

**Key Tests:**
- Complete bug lifecycle (create → assign → progress → resolve → close)
- Project management with multiple bugs
- User permission workflows
- Kanban column visibility management

#### 4. Performance Tests
- **Purpose**: Ensure application performance under load
- **Coverage**: Response times, memory usage, database queries
- **Location**: `tests/Feature/PerformanceTest.php`

**Key Tests:**
- Page load times with large datasets
- Database query performance
- Memory usage optimization
- Concurrent user scenarios

#### 5. API Tests
- **Purpose**: Test REST API endpoints
- **Coverage**: All API routes and responses
- **Location**: `tests/Feature/ApiTest.php`

**Key Tests:**
- CRUD operations for bugs and projects
- Authentication and authorization
- Data validation and error handling
- Response structure and status codes

## Test Utilities

### Base TestCase
The `TestCase` class provides common utilities:

```php
// Create test users
$admin = $this->createAdminUser();
$user = $this->createRegularUser();

// Create test data
$project = $this->createProject($admin);
$bug = $this->createBug($project, $admin);
```

### TestSuite Class
The `TestSuite` class provides advanced testing utilities:

```php
// Create complete workflows
$workflow = $this->createBugWorkflow();

// Performance testing data
$data = $this->createPerformanceTestData();

// Custom assertions
$this->assertUserCan($user, 'view-bugs');
$this->assertBugStatus($bug, 'resolved');
```

## Test Data

### Factories
The application uses Laravel factories for generating test data:

- `UserFactory`: Creates users with different roles
- `ProjectFactory`: Creates projects with various statuses
- `BugFactory`: Creates bugs with different severities and priorities
- `NotificationFactory`: Creates notifications for testing

### Seeders
Test data is seeded using:
- `DatabaseSeeder`: Basic application data
- `RolePermissionSeeder`: User roles and permissions
- `SampleDataSeeder`: Sample bugs and projects

## Coverage Reports

### Generating Coverage
```bash
# HTML coverage report
vendor/bin/phpunit --coverage-html coverage

# Text coverage report
vendor/bin/phpunit --coverage-text

# Clover XML for CI/CD
vendor/bin/phpunit --coverage-clover coverage.xml
```

### Coverage Targets
- **Overall Coverage**: > 90%
- **Model Coverage**: > 95%
- **Service Coverage**: > 90%
- **Controller Coverage**: > 85%
- **Livewire Component Coverage**: > 80%

## Continuous Integration

### GitHub Actions
The test suite is designed to run in CI/CD pipelines:

```yaml
# .github/workflows/tests.yml
name: Tests
on: [push, pull_request]
jobs:
  test:
    runs-on: ubuntu-latest
    steps:
      - uses: actions/checkout@v2
      - name: Setup PHP
        uses: shivammathur/setup-php@v2
        with:
          php-version: '8.2'
      - name: Install dependencies
        run: composer install
      - name: Run tests
        run: ./run-tests.sh
```

## Best Practices

### Writing Tests
1. **Test one thing at a time**: Each test should focus on a single behavior
2. **Use descriptive names**: Test names should clearly describe what is being tested
3. **Arrange-Act-Assert**: Structure tests with clear setup, execution, and verification
4. **Use factories**: Generate test data using Laravel factories
5. **Test edge cases**: Include tests for error conditions and boundary values

### Test Organization
1. **Group related tests**: Use descriptive class and method names
2. **Use setUp methods**: Common setup code should go in setUp()
3. **Clean up after tests**: Use RefreshDatabase trait for database cleanup
4. **Mock external dependencies**: Use mocks for external services

### Performance Considerations
1. **Use RefreshDatabase sparingly**: Only when necessary for database tests
2. **Minimize test data**: Create only the data needed for each test
3. **Use transactions**: For tests that don't need database isolation
4. **Profile slow tests**: Identify and optimize slow-running tests

## Troubleshooting

### Common Issues

#### Database Issues
```bash
# Reset test database
php artisan migrate:fresh --env=testing
php artisan db:seed --env=testing
```

#### Permission Issues
```bash
# Make test runner executable
chmod +x run-tests.sh
```

#### Memory Issues
```bash
# Increase memory limit
php -d memory_limit=512M vendor/bin/phpunit
```

### Debug Mode
```bash
# Run tests with verbose output
vendor/bin/phpunit --verbose

# Run specific test with debug
vendor/bin/phpunit --debug tests/Feature/BugManagementTest.php
```

## Test Metrics

### Current Coverage
- **Models**: 95% coverage
- **Services**: 90% coverage
- **Controllers**: 85% coverage
- **Livewire Components**: 80% coverage
- **Overall**: 87% coverage

### Performance Benchmarks
- **Page Load Time**: < 2 seconds
- **Database Queries**: < 0.5 seconds
- **Memory Usage**: < 10MB per test
- **Test Execution**: < 5 minutes total

## Contributing

When adding new features:
1. Write tests first (TDD approach)
2. Ensure all tests pass
3. Maintain or improve coverage
4. Update this documentation
5. Run the full test suite before committing

## Support

For questions about testing:
1. Check this documentation
2. Review existing test examples
3. Consult Laravel testing documentation
4. Create an issue for specific problems
