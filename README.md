# E-commerce Website
## Demo
![Desktop Demo](./images/desktop.png "Desktop Demo")
## Introduction

This document provides an overview of an e-commerce website built using HTML, CSS, Bootstrap, JavaScript, PHP, and MySQL. The website consists of both user-side and admin-side functionalities, allowing customers to browse and purchase products while enabling administrators to manage inventory, orders, and customer data.

## Conclusion

The e-commerce website built using HTML, CSS, Bootstrap, JavaScript, PHP, and MySQL offers a comprehensive set of features for both users and administrators. It provides an intuitive and visually appealing interface for customers to browse and purchase products, while administrators have the necessary tools to manage inventory, process orders, and track customer information.

## Installation Steps

Follow these steps to install and run the e-commerce website:

1. Clone the Repository:

   - If you have Git installed, open a terminal and run the following command:
     ```
     git clone https://github.com/trilaanh2k3/E-commerce.git
     ```

2. Configure Database:

   - Create a new MySQL database for the e-commerce website.
   - Import the provided SQL file (`database.sql`) into your newly created database. This file contains the necessary tables and sample data for the website.

3. Update Configuration:

   - Open the `config.php` file located in the root directory.
   - Update the database credentials (hostname, username, password, database name) to match your local setup.
   - Save the changes.

4. Start Web Server:

   - Start your Apache or Nginx web server and ensure it is running correctly.

5. Access the Website:
   - Open your web browser and navigate to the URL where the website is hosted (e.g., `http://localhost/E-Commerce`).
   - You should now see the home page of the e-commerce website.

## Admin Panel

To access the admin panel of the e-commerce website, follow these steps:

1. Open your web browser and navigate to the admin URL (e.g., `http://localhost/E-Commerce/login`).
2. Login using the default admin credentials:
   - Username: `trinhminhtri0509@gmail.com`
   - Password: `trinhminhtri0509`
3. Once logged in, you can manage products, orders, customers, and other administrative tasks from the admin panel.

Congratulations! You have successfully installed and set up the e-commerce website on your local development environment. You can now customize the website, add your products, and explore its various features.

Please note that this installation guide assumes a basic local development environment. For deploying the website to a production server, additional configuration and security considerations may apply.
