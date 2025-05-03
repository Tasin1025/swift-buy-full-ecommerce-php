# SwiftBuy - E-Commerce Website

SwiftBuy is an e-commerce website built using **HTML**, **CSS**, **JavaScript**, **Tailwind CSS**, **PHP**, and **MySQL**. This project demonstrates skills in web development, including user authentication, database management, and dynamic product handling for an online store. It has both **user** and **admin** dashboards with various features for managing profiles, orders, and products.

## Features

### User Dashboard:
- **Registration & Login**: Users can register and log in to their accounts.
- **Profile Management**: Users can view and edit their profile information, including name, email, password, and profile picture.
- **Product Management**: Users can browse products, add them to the cart, and adjust the quantity in the cart.
- **Checkout**: Users can proceed with checkout, and once an order is confirmed, the inventory and order details are updated in the database.

### Admin Dashboard:
- **Admin Login**: Admins can log in with admin credentials.
- **Product Management**: Admins can add new products, upload product images, and delete products from the website.
- **Order Management**: Admins can view the total number of orders.
- **Sales Visualization**: Admins can view a monthly sales graph to monitor performance.

## Technologies Used

- **Frontend**: HTML, CSS, JavaScript, Tailwind CSS, Chart.js
- **Backend**: PHP
- **Database**: MySQL
- **Tools**: XAMPP, VS Code

## Installation

Follow these steps to set up the project locally:

1. **Clone the Repository**:
   ```
   git clone https://github.com/Tasin1025/swift-buy-full-ecommerce-php
    ```
2. **Place the Project in the XAMPP `htdocs` Folder:**
- Move the project folder to the `htdocs` directory in your XAMPP installation path (usually located at `C:\xampp\htdocs` on Windows).

3. **Set Up XAMPP:**
- Open **XAMPP** and start **Apache** and **MySQL** services.

4. **Set Up the Database:**
- Open **phpMyAdmin** (usually at `localhost/phpmyadmin`).
- Create a new database (e.g., `swiftbuy`).
- Import the `swiftbuy.sql` file (found in the project folder) into the database.

5. **Access the Project:**
- Open your browser and navigate to `http://localhost/swiftbuy`.
- The website should now be running locally.
## Live Website
 [Placeholder for Live Website Link]
## Login Credentials
**Admin Login:**
Email: admin@gmail.com
 Password: tasin1234

**User Login:**
Email: tasin@gmail.com
 Password: tasin1234

## Usage

### User Functionality:
- Register an account as a user.
- Log in and manage your profile.
- Browse products, add them to your cart, and complete your purchase.

### Admin Functionality:
- Log in using admin credentials.
- Add, delete, and manage products.
- View monthly sales graphs and monitor orders.


## Future Enhancements
- User reviews and ratings for products.
- Wishlist functionality for users.
- Improved admin analytics with more detailed reports.
