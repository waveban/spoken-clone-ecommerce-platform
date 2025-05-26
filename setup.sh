#!/bin/bash

# Colors for output
GREEN='\033[0;32m'
BLUE='\033[0;34m'
RED='\033[0;31m'
NC='\033[0m' # No Color

echo -e "${BLUE}Starting Spoken Clone setup...${NC}\n"

# Check if PHP is installed
if ! command -v php &> /dev/null; then
    echo -e "${RED}PHP is not installed. Please install PHP 8.1 or higher.${NC}"
    exit 1
fi

# Check if Composer is installed
if ! command -v composer &> /dev/null; then
    echo -e "${RED}Composer is not installed. Please install Composer first.${NC}"
    exit 1
fi

# Create necessary directories
echo -e "${BLUE}Creating necessary directories...${NC}"
mkdir -p data
mkdir -p public/assets
chmod 777 data

# Install dependencies
echo -e "\n${BLUE}Installing Composer dependencies...${NC}"
composer install

# Create environment file if it doesn't exist
if [ ! -f .env ]; then
    echo -e "\n${BLUE}Creating .env file...${NC}"
    cp .env.example .env
    echo -e "${GREEN}Created .env file. Please update it with your configuration.${NC}"
fi

# Initialize the application
echo -e "\n${BLUE}Initializing the application...${NC}"
php init.php

# Set up permissions
echo -e "\n${BLUE}Setting up permissions...${NC}"
chmod -R 755 public
chmod -R 755 src
chmod 755 .htaccess

echo -e "\n${GREEN}Setup completed successfully!${NC}"
echo -e "\nTo start the development server, run:"
echo -e "${BLUE}php -S localhost:8000 -t public${NC}"
echo -e "\nMake sure to:"
echo "1. Configure your Meilisearch settings in .env"
echo "2. Set up your Logto.ai credentials in .env"
echo "3. Update the BASE_URL in .env if needed"
echo -e "\n${GREEN}Happy coding!${NC}"
