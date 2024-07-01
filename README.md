# Project Overview

## Introduction

This project is a comprehensive system designed to manage recipes and ingredients, utilizing a Docker-based setup to separate the backend, frontend, and database services. The project structure is designed to be scalable and easy to maintain, allowing for future enhancements and integrations.

## Architecture

The project is divided into several Docker containers, each serving a different role:

- **Backend**: A Symfony-based API that handles all backend logic and interactions with the database.
- **Frontend**: A Vue.js application that provides a user-friendly interface for interacting with the backend services. The frontend is currently set up with a base template but has not yet been implemented due to time constraints.
- **Nginx**: Serves as a reverse proxy, routing requests to the appropriate backend or frontend service.
- **Database**: A MySQL database that stores all data related to recipes, ingredients, and users.

## Setup

To get the project up and running, follow these steps:

1. Clone the repository.
2. Navigate to the project directory.
3. Ensure Docker is installed on your system.
4. Run `make build` to build the Docker images.
5. Run `make up` to start all services.

## Directories

- `backend/`: Contains all the code for the Symfony backend.
- `frontend/`: Contains the Vue.js application.
- `nginx/`: Configuration files for the Nginx server.
- `docs/`: Documentation related to the API endpoints and database design.

## Documentation

Detailed information about the API endpoints and database design is available in the backend documentation:

- [API Documentation](backend/docs/API.md) , Sorry  I don't have time to implement nelmioApiDocBundle 
- [Database Documentation](backend/docs/Database.md)

## Note on the Frontend

The frontend is set up using Vue.js but currently contains only the base template. Implementation of the frontend features is planned for future development phases.


