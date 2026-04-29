
# CampusMart

**Group Name:** WT_DT-Group_Secondhandmarket

## Project Overview
CampusMart is a web-based second-hand marketplace developed for students to buy, sell, and manage used items within a campus community. The project was created as a Web Technologies assignment and combines frontend design, backend logic, database operations, and user interaction in one complete dynamic website.

The platform allows users to register and log in, publish product listings, browse available items, search by keyword, filter by category and price range, save favorite products, and manage their own listings. The overall goal of the project is to provide a simple and practical campus trading experience while demonstrating a full web development workflow.

## Project Purpose
The purpose of CampusMart is:

- to help students trade second-hand items more conveniently on campus
- to practice full-stack web development with PHP and MySQL
- to implement a complete CRUD-based web application
- to improve UI design, page responsiveness, and user experience
- to strengthen teamwork through clear frontend, backend, and database responsibilities

## Main Features
- User registration, login, and logout
- Add, edit, and delete product listings
- Product listing page with keyword search
- Category filter and price range filter
- Product detail page with seller information
- Favorite products system
- Favorites page for saved items
- My Products page for managing personal listings
- Product image upload
- Related product recommendations
- Responsive page layout for desktop and mobile devices

## Technologies Used

### Frontend
- HTML
- CSS
- JavaScript

### Backend
- PHP

### Database
- MySQL

### Development Environment
- XAMPP
- phpMyAdmin
- Visual Studio Code

## Project Structure
```text
WT-project-main/
├── README.md
└── secondhandmarket/
    ├── assets/
    │   ├── css/
    │   │   └── style.css
    │   └── javascript/
    │       └── main.js
    ├── includes/
    │   └── dbconnect.php
    ├── sql/
    │   ├── database.sql
    │   └── update_existing_database.sql
    ├── add_product.php
    ├── cart.php
    ├── delete_product.php
    ├── edit_product.php
    ├── index.php
    ├── login.php
    ├── logout.php
    ├── my_products.php
    ├── product_detail.php
    ├── products.php
    ├── register.php
    ├── test_db.php
    └── toggle_favorite.php
```

## Key Pages
- `index.php`: homepage with hero section, search, category shortcuts, latest products, and favorite preview
- `products.php`: all products page with search, category filter, and price range filter
- `product_detail.php`: detailed information for a single product with seller profile and related products
- `add_product.php`: form for adding a new product with image upload
- `edit_product.php`: form for editing an existing product
- `delete_product.php`: product deletion logic
- `my_products.php`: page for managing the current user's listings
- `cart.php`: favorites page showing products saved by the user
- `login.php` / `register.php`: account access pages

## Database Design
The project mainly uses the following tables:

- `users`: stores user account information
- `categories`: stores product categories
- `products`: stores product details such as title, description, price, image, status, and views
- `favorites`: stores the relationship between users and saved products

The database script also includes default category data to support filtering and product publishing.

## How to Run the Project
1. Open XAMPP and start Apache and MySQL.
2. Put the `secondhandmarket` folder into the `htdocs` directory.
3. Open phpMyAdmin.
4. Create or import the database using `sql/database.sql`.
5. If needed, run `sql/update_existing_database.sql` for existing databases.
6. Open the project in a browser, for example:
   - `http://localhost/secondhandmarket/index.php`
   - `http://localhost/secondhandmarket/login.php`
   - `http://localhost/secondhandmarket/products.php`

## Challenges and Solutions
- **Database connection management**: solved by using a shared `includes/dbconnect.php` file across all pages.
- **Image upload handling**: solved by validating file type and storing images in the upload directory.
- **Search and filter logic**: solved by combining keyword, category, and price range conditions in SQL queries.
- **User permission control**: solved with PHP sessions so that users can only manage their own products.
- **Favorite interaction**: solved with AJAX-based favorite toggling and a dedicated favorites page.
- **Page consistency**: improved by using one shared stylesheet and a unified visual design.

## Team Contribution
This project was completed collaboratively for a Web Technologies assignment.

- **Wu Shangjun** was mainly responsible for the frontend part of the project, including page layout design, CSS styling, responsive design, homepage presentation, product listing page interface, product detail page layout, and JavaScript-based user interaction. He also contributed to interface optimization and overall user experience improvement.
- **Shi Chaolu** was mainly responsible for the backend and database part of the project, including database design, MySQL table structure, database connection, user authentication, product CRUD functions, search and filter query logic, favorites functionality, and project testing/setup.
- Both members contributed to refining the login and register modules, improving the final interface, and completing the project documentation.

## Future Improvements
- Add product condition information such as new, like new, or used
- Add pickup location or campus area for safer offline trading
- Add contact seller functionality
- Add product sorting options such as newest and price order
- Add pagination for large product lists
- Add sold or unavailable status instead of only active listings

## Conclusion
CampusMart demonstrates a practical full-stack web application that integrates frontend design, backend development, database management, and user interaction into one project. It provides a useful example of how a campus-focused second-hand marketplace can be developed with core web technologies in a clear and structured way.
```
