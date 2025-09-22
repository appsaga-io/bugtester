#!/bin/bash

# BugTester Application Test Runner
# This script runs all tests with proper configuration and reporting

echo "🚀 Starting BugTester Test Suite"
echo "================================="

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m' # No Color

# Function to print colored output
print_status() {
    echo -e "${BLUE}[INFO]${NC} $1"
}

print_success() {
    echo -e "${GREEN}[SUCCESS]${NC} $1"
}

print_warning() {
    echo -e "${YELLOW}[WARNING]${NC} $1"
}

print_error() {
    echo -e "${RED}[ERROR]${NC} $1"
}

# Check if we're in the right directory
if [ ! -f "artisan" ]; then
    print_error "Please run this script from the Laravel project root directory"
    exit 1
fi

# Check if PHPUnit is available
if ! command -v vendor/bin/phpunit &> /dev/null; then
    print_error "PHPUnit not found. Please run 'composer install' first"
    exit 1
fi

# Create test database if it doesn't exist
print_status "Setting up test environment..."

# Run database migrations for testing
print_status "Running database migrations..."
php artisan migrate --env=testing --force

# Run seeders for testing
print_status "Running database seeders..."
php artisan db:seed --env=testing --force

echo ""
print_status "Running Unit Tests..."
echo "=========================="

# Run unit tests
if vendor/bin/phpunit tests/Unit --colors=always; then
    print_success "Unit tests passed!"
else
    print_error "Unit tests failed!"
    exit 1
fi

echo ""
print_status "Running Feature Tests..."
echo "============================="

# Run feature tests
if vendor/bin/phpunit tests/Feature --colors=always; then
    print_success "Feature tests passed!"
else
    print_error "Feature tests failed!"
    exit 1
fi

echo ""
print_status "Running Integration Tests..."
echo "================================="

# Run integration tests
if vendor/bin/phpunit tests/Feature/IntegrationTest.php --colors=always; then
    print_success "Integration tests passed!"
else
    print_error "Integration tests failed!"
    exit 1
fi

echo ""
print_status "Running Performance Tests..."
echo "================================="

# Run performance tests
if vendor/bin/phpunit tests/Feature/PerformanceTest.php --colors=always; then
    print_success "Performance tests passed!"
else
    print_warning "Performance tests failed or took too long!"
fi

echo ""
print_status "Running API Tests..."
echo "========================="

# Run API tests
if vendor/bin/phpunit tests/Feature/ApiTest.php --colors=always; then
    print_success "API tests passed!"
else
    print_error "API tests failed!"
    exit 1
fi

echo ""
print_status "Running All Tests with Coverage..."
echo "======================================="

# Run all tests with coverage
if vendor/bin/phpunit --coverage-html coverage --colors=always; then
    print_success "All tests passed with coverage report generated!"
    print_status "Coverage report available at: coverage/index.html"
else
    print_error "Some tests failed!"
    exit 1
fi

echo ""
echo "🎉 Test Suite Complete!"
echo "======================"
print_success "All tests have been executed successfully!"
print_status "Check the coverage report for detailed analysis"
print_status "Coverage report: coverage/index.html"

# Optional: Open coverage report in browser (macOS)
if command -v open &> /dev/null; then
    read -p "Would you like to open the coverage report in your browser? (y/n): " -n 1 -r
    echo
    if [[ $REPLY =~ ^[Yy]$ ]]; then
        open coverage/index.html
    fi
fi
