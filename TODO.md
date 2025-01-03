# To-Do List for Christmas Gift Site Development

## Front-End

- [x] **Set up project structure**
  - Create a `views` folder with subfolders for different sections:
    - `landing`: Landing page showing what the website does, with options to login or sign up.
    - `auth`: Login and registration pages.
    - `main`: Main page for gift selection, deposit options, and user interactions.
    - `admin`: Admin panel for deposit validation and admin-specific functions.
  - Create an `assets` folder for:
    - **Framework**: Bootstrap and jquery 
    - **CSS**: Custom css.
    - **JS**: Custom js.
    - **Images**: Logos and gift images.

- [ ] **User Interface Design**
  - Design the landing page using Bootstrap components (e.g., hero section, buttons for login/signup).
  - Design the login and registration forms.
  - Create a user dashboard with gift selection and deposit options.
  - Design the admin panel interface for deposit validation.

- [ ] **Frontend Interactions**
  - Use jQuery for dynamic page elements (e.g., interactive gift selection, AJAX calls).
  - Use Bootstrap for form validation, modals, and alerts.
  - Implement responsive design for mobile users.

---

## Back-End

- [x] **MySQL Database Management**
  - Set up MySQL database for user and gift management.
  - Design tables for users, gifts, and deposits.
  - Seed the database with at least 50 gift items categorized by type (boy, girl, neutral).

- [x] **Set up project structure**
  - Create folders for `routes`, `controllers`, and `models`.

- [x] **Routes**
  - Define routes for:
    - User authentication (login, registration).
    - Main page for selecting gifts and depositing money.
    - Admin panel for managing deposits.

- [x] **Controllers**
  - Create `AuthController` for user login and registration logic.
  - Create `MainController` for managing gift selection, deposits, and user-related actions.
  - Create `AdminController` for admin login and deposit validation.

- [ ] **Models**
  - Create `UserModel` for user authentication and data management.
  - Create `GiftModel` for handling gift data (e.g., gift categories, costs).
  - Create `MoveModel` for managing user deposits.

- [x] **User Authentication**
  - Implement secure user login and registration logic.
  - Use session management for authentication.

- [ ] **Deposit Management**
  - Create functionality to allow users to deposit money into their accounts.
  - Implement the admin panel route to validate deposits.

- [ ] **Gift Selection Logic**
  - Create an algorithm to suggest gifts based on user input (e.g., number of children, gender).
  - Implement logic for validating and replacing suggested gifts.

---

## Tools and Dependencies

- **Bootstrap**: For front-end styling (locally hosted CSS and JS).
- **jQuery**: For front-end dynamic behavior (locally hosted).
- **MySQL**: For database handling (users, gifts, and moves).

---
