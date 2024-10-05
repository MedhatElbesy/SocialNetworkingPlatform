# Social Networking Platform

## Table of Contents
- [Introduction](#introduction)
- [Features](#features)
- [Technologies Used](#technologies-used)
- [Installation](#installation)
- [API Documentation](#api-documentation)


## Introduction

This project is a basic social networking platform built with Laravel, allowing users to create profiles, connect with others, create posts, comment on posts, and like posts. The application utilizes Laravel's powerful features for authentication, database management, and API development, providing a user-friendly interface for interaction Also use repository pattern for bettern for better code organization.

## Features

- **User Authentication**: Users can register, log in, log out, and reset their passwords.
- **User Profiles**: Each user has a profile that includes their name, email, profile picture, and bio. Users can edit their profiles and view others' profiles.
- **Connections**: Users can send friend requests, and accept or reject requests. A list of friends is available for each user.
- **Posts**: Users can create text posts that display the author's information and interactions, such as likes and comments.
- **Comments & Likes**: Users can comment on posts and like them, with visibility on who liked each post.
- **Database Structure**: A structured database to handle users, posts, comments, likes, and connections, using migrations and seeders.
- **Frontend**: Built using Blade templates for a responsive and user-friendly interface.
- **API Authentication**: API endpoints secured with Laravel Passport or Sanctum, allowing authenticated access.
- **API Endpoints**: RESTful API design supporting CRUD operations for user profiles, posts, comments, likes, and connections.
- **Response Formats**: JSON responses with appropriate error handling and HTTP status codes.
- **API Documentation**: Generated API documentation for easy reference.
- **Image Uploads**: Users can upload images with their posts.
- **Real-Time Notifications**: Notifications for friend requests, comments, and likes using Pusher.
- **User Search**: Functionality for users to search and connect with other users.

## Technologies Used

- Laravel
- PHP
- MySQL
- Blade (Laravel's templating engine)
- Laravel Passport or Sanctum (for API authentication)
- Laravel Echo (for real-time notifications)
- JavaScript / jQuery (for front-end interactivity)

## Installation

1. **Clone the repository**:
   ```bash
   git clone https://github.com/yourusername/social-networking-platform.git
2. **Navigate to the project directory**:
    ```bash
    cd social-networking-platform

3. **Install dependencies**:
    ```bash
    composer install


4. **Set up your environment**:
   ```bash
    cp .env.example .env

5. **Serve the application**:
     ```bash
     php artisan serve

## API Documentation
**Access**:API endpoints are documented using Swagger. You can access the documentation at http://localhost:8000/api/documentation
